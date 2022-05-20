<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Exceptions\FailException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;


use App\Repositories\Contracts\ReportRepository;

class ReportController extends Controller
{
    private ReportRepository $reportRepository;
    private const PAGE_ADMIN = 'admin';
    private const SELECT_TYPE_PROJECT = 'project';
    private const SELECT_TYPE_POSITION = 'position';

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;

        $this->middleware('permission:reports_read')
            ->only([
                'index',
                'datatables',
                'datatablesManager'
            ]);

        $this->middleware('permission:reports_create')
            ->only([
                'create',
                'store',
                'getProjectOptions'
            ]);

        $this->middleware('permission:reports_update')
            ->only([
                'edit',
                'update',
                'switchStatus',
                'getProjectOptions'
            ]);

        $this->middleware('permission:reports_delete')
            ->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->routeIs('reports.index_manager')) {
            Gate::denyIf(
                !$request->user()->isAdminGroup(),
                __('This action is unauthorized.')
            );

            // Breadcrumbs Mamager Reports Page
            $breadcrumbs =  [
                ['link' => "home", 'name' => "Home"],
                ['link' => 'manager-reports', 'name' => "Manager Reports"],
                ['name' => 'Index']
            ];

            return view(
                'pages.reports.manager_index',
                ['breadcrumbs' => $breadcrumbs]
            );
        }

        // Breadcrumbs My Reports Page
        $breadcrumbs =  [
            ['link' => "home", 'name' => "Home"],
            ['link' => 'reports', 'name' => "My Reports"],
            ['name' => 'Index']
        ];

        return view(
            'pages.reports.index',
            ['breadcrumbs' => $breadcrumbs]
        );
    }

    /**
     * Datatables Report for Employee
     *
     */
    public function datatables()
    {
        return $this->reportRepository->datatables();
    }

    /**
     * Datatables Report for Admin
     *
     */
    public function datatablesManager()
    {
        return $this->reportRepository->datatablesManager();
    }

    /**
     *This method is used to get the return data for the create form. If the *incoming request is for the admin page, it will return a list of *converted users to select2. If the request is for an employee page, a *list of projects is returned. When the user selects fields on the form, *an Ajax request is sent to the "getSelectOptions" method to select the *relevant fields.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        // If is Page for Admin|Manager then return options User
        if (!empty($request->page) && $request->page == self::PAGE_ADMIN) {
            Gate::denyIf(
                !$request->user()->isAdminGroup(),
                __('This action is unauthorized.')
            );

            $users = User::where('status', User::STATUS_ACTIVE)->get();

            $userOptions = toSelect2($users, 'id', 'full_name');

            return jsend_success(['userOptions' => $userOptions]);
        }

        // If is Page For Employee then return project options of current user
        $projectOptions = $this->reportRepository
            ->getProjectOptions(auth()->id());

        return jsend_success(['projectOptions' => $projectOptions]);
    }

    /**
     * Get Select options for Project or Position
     *
     * @param Request $request
     */
    public function getSelectOptions(Request $request)
    {
        // Type: get 'project'|'position'
        $type = $request->type;

        if (empty($type)) {
            return jsend_success(['options' => []]);
        }

        $userId = $request->user_id;
        $projectId = $request->project_id;

        switch ($type) {
            case self::SELECT_TYPE_POSITION:
                if (empty($userId) || empty($projectId)) {
                    return jsend_success(['options' => []]);
                }

                $options = $this->reportRepository
                    ->getPositionOptions($userId, $projectId);

                return jsend_success([
                    'options' => $options
                ]);
                break;

            case self::SELECT_TYPE_PROJECT:
                if (empty($userId)) {
                    return jsend_success(['options' => []]);
                }

                $options = $this->reportRepository
                    ->getProjectOptions($userId);

                return jsend_success([
                    'options' => $options
                ]);
                break;

            default:
                return jsend_success(['options' => []]);
                break;
        }
    }

    /**
     * Create new Report
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->reportRepository->createReport($request->all());
        } catch (FailException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Create failed'));
        }

        return jsend_success(null, __('Create success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     *This method returns a list of data for the edit form. Here will return a *Report Model to display the information of the report. On the form there *are selection fields that are related to each other. When User is *selected, the list of User's Projects will be loaded. When Project is *selected a list of Positions will be loaded. So this method will return a *list of options that belong to the User object. If it is the Admin page, *it will return a list of Users, a list of Projects of the selected User, *the Positions of the selected Project. If it is an Employee page, it will *return a list of Projects of the current User, a list of Positions of the *selected Project. Selected options are determined based on the value of *equivalent properties on the Report object. An Option will be selected if *it is equal to the equivalent property on the Report object
     *
     * @param Report $report
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Report $report, Request $request): JsonResponse
    {
        $report->load(['user', 'project', 'position']);

        // List project options belong to Report's User
        $projectOptions = $this->reportRepository
            ->getProjectOptions($report->user_id);

        // List position options belong to Report's Project
        $positionOptions = $this->reportRepository
            ->getPositionOptions(
                $report->user_id,
                $report->project_id
            );

        // If is Page for Admin|Manager then return options User
        if (!empty($request->page) && $request->page == self::PAGE_ADMIN) {
            Gate::denyIf(
                !$request->user()->isAdminGroup(),
                __('This action is unauthorized.')
            );

            $users = User::where('status', User::STATUS_ACTIVE)->get();

            // List user options
            $userOptions = toSelect2($users, 'id', 'full_name');

            return jsend_success([
                'userOptions' => $userOptions,
                'projectOptions' => $projectOptions,
                'positionOptions' => $positionOptions,
                'report' => $report
            ]);
        }

        // If is Page For Employee then return project options of current user
        return jsend_success([
            'projectOptions' => $projectOptions,
            'positionOptions' => $positionOptions,
            'report' => $report
        ]);
    }

    /**
     * Update Report
     *
     * @param Request $request
     * @param Report $report
     * @return JsonResponse
     */
    public function update(Request $request, Report $report): JsonResponse
    {
        try {
            $this->reportRepository->updateReport($report, $request->all());
        } catch (FailException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Update failed'));
        }

        return jsend_success(null, __('Update success'));
    }

    /**
     * Delete Report
     *
     * @param Report $report
     * @return JsonResponse
     */
    public function destroy(Report $report): JsonResponse
    {
        try {
            $this->reportRepository->deleteReport($report);
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }


    /**
     * Approved Report
     *
     * @param Report $report
     * @return JsonResponse
     */
    public function approve(Report $report): JsonResponse
    {
        try {
            $this->reportRepository->approveReport($report);
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Update failed'));
        }

        return jsend_success(null, __('Update success'));
    }
}
