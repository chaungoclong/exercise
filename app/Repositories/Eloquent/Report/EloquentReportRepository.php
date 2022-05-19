<?php

namespace App\Repositories\Eloquent\Report;

use App\Models\Report;
use App\Exceptions\FailException;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Contracts\ReportRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;

class EloquentReportRepository extends EloquentBaseRepository implements
    ReportRepository
{
    public function __construct(Report $model)
    {
        $this->model = $model;
    }

    /**
     * Check If Can Save (working time per day max 8h)
     *
     * @param array $payload
     * @return boolean
     */
    public function canSave(array $payload): bool
    {
        if ($payload['working_time'] > Report::MAX_TIME_PER_DAY) {
            return false;
        }

        $totalTimeOnDate = $this->model
            ->where('user_id', $payload['user_id'])
            ->where('date', $payload['date'])
            ->sum('working_time');

        $totalTimeWillInsert = $totalTimeOnDate + $payload['working_time'];

        if ($totalTimeWillInsert > Report::MAX_TIME_PER_DAY) {
            return false;
        }

        return true;
    }

    /**
     * Check If Can Update (working time per day max 8h)
     *
     * @param array $payload
     * @return boolean
     */
    public function canUpdate(array $payload, int $oldTime): bool
    {
        if ($payload['working_time'] > Report::MAX_TIME_PER_DAY) {
            return false;
        }

        $totalTimeOnDate = $this->model
            ->where('user_id', $payload['user_id'])
            ->where('date', $payload['date'])
            ->sum('working_time');

        $newTime = $payload['working_time'];

        $totalTimeWillInsert = ($totalTimeOnDate - $oldTime) + $newTime;

        if ($totalTimeWillInsert > Report::MAX_TIME_PER_DAY) {
            return false;
        }

        return true;
    }

    /**
     * Create New Report
     *
     * @param array $payload
     * @return Report
     */
    public function createReport(array $payload): Report
    {
        if (!$this->canSave($payload)) {
            throw new FailException(__('Can only work 8 hours a day'));
        }

        DB::beginTransaction();

        try {
            $report = $this->create($payload);

            DB::commit();

            return $report;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * Update Report
     *
     * @param integer|string|Report $key
     * @param array $payload
     * @return Report
     */
    public function updateReport(
        int|string|Report $key,
        array $payload
    ): Report {
        $report = ($key instanceof Report) ? $key : $this->findById($key);

        // Check can update
        if (!$this->canUpdate($payload, $report->working_time)) {
            throw new FailException(__('Can only work 8 hours a day'));
        }

        DB::beginTransaction();

        try {
            $report = $this->update($key, $payload);

            DB::commit();

            return $report;
        } catch (\Exception $e) {
            DB::rollback();

            throw $e;
        }
    }

    /**
     * Delete Report
     *
     * @param integer|string|Report $key
     * @return boolean
     */
    public function deleteReport(int|string|Report $key): bool
    {
        DB::beginTransaction();

        try {
            $result = $this->delete($key);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Get DataTables Of Report
     *
     */
    public function datatables()
    {
        // Get all report of current User
        $eloquent = $this->model->with([
            'position',
            'project'
        ])->where('user_id', auth()->id());

        return DataTables::of($eloquent)
            ->filter(function ($query) {
                // Filter by Report Status
                if (request()->has('status') && !is_null(request('status'))) {
                    $query->where('status', request('status'));
                }

                // Filter by Working Type
                if (
                    request()->has('working_type')
                    && !is_null(request('working_type'))
                ) {
                    $query->where('working_type', request('working_type'));
                }

                // Filter by Date
                if (!empty(request('from_date'))) {
                    $fromDate = request('from_date');

                    $toDate = (!empty(request('to_date')))
                        ? request('to_date')
                        : date('Y-m-d');

                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);
                }

                // Filter by Search key
                if (!empty(request('search'))) {
                    $search = request('search');

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where(function ($reportQuery) use ($search) {
                            $reportQuery
                                ->where('working_time', $search)
                                ->orWhere('note', $search);
                        })->orWhereHas(
                            'position',
                            function ($positionQuery) use ($search) {
                                $positionQuery
                                    ->where('name', 'LIKE', "%$search%");
                            }
                        )->orWhereHas(
                            'project',
                            function ($projectQuery) use ($search) {
                                $projectQuery
                                    ->where('name', 'LIKE', "%$search%");
                            }
                        );
                    });
                }
            })
            ->editColumn('project_id', function ($report) {
                return optional($report->project)->name;
            })
            ->editColumn('position_id', function ($report) {
                return optional($report->position)->name;
            })
            ->editColumn('working_type', function ($report) {
                $displayConfig = config(
                    'constants.working_type.' . ($report->working_type)
                );

                return view(
                    'components.datatables.text-col',
                    [
                        'text' => $displayConfig['name'],
                        'class' => 'fw-bolder ' . $displayConfig['badge']
                    ]
                )->render();
            })
            ->editColumn('status', function ($report) {
                $displayConfig = config(
                    'constants.report_status.' . ($report->status)
                );

                return view(
                    'components.datatables.text-col',
                    [
                        'text' => $displayConfig['name'],
                        'class' => 'fw-bolder ' . $displayConfig['badge']
                    ]
                )->render();
            })
            ->addColumn('actions', function ($report) {
                return view(
                    'components.datatables.report-action-col',
                    ['report' => $report]
                )->render();
            })
            ->rawColumns([
                'working_type',
                'status',
                'actions'
            ])
            ->make(true);
    }

    /**
     * Approve Report
     *
     * @param integer|string|Report $key
     * @return boolean
     */
    public function approveReport(int|string|Report $key): bool
    {
        $report = ($key instanceof Report) ? $key : $this->findById($key);

        DB::beginTransaction();

        try {
            $result = $report->update(['status' => Report::STATUS_APPROVED]);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    // Get Datatables Of Admin Page
    public function datatablesManager()
    {
        // Get all report of current User
        $eloquent = $this->model->with([
            'position',
            'project'
        ]);

        return DataTables::of($eloquent)
            ->filter(function ($query) {
                // Filter by Report Status
                if (request()->has('status') && !is_null(request('status'))) {
                    $query->where('status', request('status'));
                }

                // Filter by Working Type
                if (
                    request()->has('working_type')
                    && !is_null(request('working_type'))
                ) {
                    $query->where('working_type', request('working_type'));
                }

                // Filter by Date
                if (!empty(request('from_date'))) {
                    $fromDate = request('from_date');

                    $toDate = (!empty(request('to_date')))
                        ? request('to_date')
                        : date('Y-m-d');

                    $query->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate);
                }

                // Filter by Search key
                if (!empty(request('search'))) {
                    $search = request('search');

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery->where(function ($reportQuery) use ($search) {
                            $reportQuery
                                ->where('working_time', $search)
                                ->orWhere('note', $search);
                        })->orWhereHas(
                            'position',
                            function ($positionQuery) use ($search) {
                                $positionQuery
                                    ->where('name', 'LIKE', "%$search%");
                            }
                        )->orWhereHas(
                            'project',
                            function ($projectQuery) use ($search) {
                                $projectQuery
                                    ->where('name', 'LIKE', "%$search%");
                            }
                        );
                    });
                }
            })
            ->addColumn('user', function ($report) {
                return view(
                    'components.datatables.user-col',
                    ['user' => $report->user]
                );
            })
            ->editColumn('project_id', function ($report) {
                return optional($report->project)->name;
            })
            ->editColumn('position_id', function ($report) {
                return optional($report->position)->name;
            })
            ->editColumn('working_type', function ($report) {
                $displayConfig = config(
                    'constants.working_type.' . ($report->working_type)
                );

                return view(
                    'components.datatables.text-col',
                    [
                        'text' => $displayConfig['name'],
                        'class' => 'fw-bolder ' . $displayConfig['badge']
                    ]
                )->render();
            })
            ->editColumn('status', function ($report) {
                $displayConfig = config(
                    'constants.report_status.' . ($report->status)
                );

                return view(
                    'components.datatables.text-col',
                    [
                        'text' => $displayConfig['name'],
                        'class' => 'fw-bolder ' . $displayConfig['badge']
                    ]
                )->render();
            })
            ->addColumn('actions', function ($report) {
                return view(
                    'components.datatables.report-action-col',
                    ['report' => $report]
                )->render();
            })
            ->rawColumns([
                'user',
                'working_type',
                'status',
                'actions'
            ])
            ->make(true);
    }
}
