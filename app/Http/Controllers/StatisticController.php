<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

/**
 * Overview:
 * Statistics for: User, Project
 * Requirements:
 *    + Common: Filterable by time
 *    + User:
 *        - Total working time by working type
 *        - Total working time by project
 *    + Project:
 *        - Total working time of each role in the project
 *        - Time use in project by each member
 */
class StatisticController extends Controller
{
    /**
     * Get Statistic for User
     *
     * @param Request $request
     * @param integer|string|null $userId
     */
    public function getUserStatistics(
        Request $request,
        int|string|null $userId = null
    ) {
        $currentUser = auth()->user();
        $user = ($userId !== null) ? User::findOrFail($userId) : $currentUser;

        // Only Admins can see other people's statistics
        Gate::denyIf(
            !($currentUser->is($user)) && !$currentUser->isAdminGroup(),
            __('This action is unauthorized.')
        );

        // List project of user for select
        $projects = $user->projects;

        // Date filter range
        $fromDate = $request->from_date ?? now()->firstOfMonth()->toDateString();
        $toDate = $request->to_date ?? now()->toDateString();

        // Project Id for filter by Project
        $projectId = $request->project_id ?? null;

        // Working time by Working type data statistics
        $workingTimeByType = $this
            ->getUserWorkingTimeByType(
                $user->id,
                $projectId,
                $fromDate,
                $toDate
            );

        // Working time by Project data statistics
        $workingTimeByProject = $this
            ->getUserWorkingTimeByProject(
                $user->id,
                $projectId,
                $fromDate,
                $toDate
            );

        return view(
            'pages.statistics.work-statistic',
            [
                'projects' => $projects,
                'user' => $user,
                'workingTimeByType' => $workingTimeByType,
                'workingTimeByProject' => $workingTimeByProject,
                'breadcrumbs' =>  [
                    ['link' => "home", 'name' => "Home"],
                    ['name' => 'Project Statistics']
                ]
            ]
        );
    }

    /**
     * Get statistics Working time by Working type for User
     *
     * @param integer|string $userId
     * @param integer|string|null $projectId
     * @param integer|string $fromDate
     * @param integer|string $toDate
     * @return array
     */
    public function getUserWorkingTimeByType(
        int|string $userId,
        int|string|null $projectId,
        int|string $fromDate,
        int|string $toDate
    ): array {
        $query = Report::select('working_type')
            ->selectRaw('SUM(working_time) as total_time')
            ->where('user_id', $userId)
            ->where('status', Report::STATUS_APPROVED)
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate);

        // Filter by Project if exist
        if ($projectId !== null) {
            $query->where('project_id', $projectId);
        }

        // Group by Working type
        $query->groupBy('working_type');

        $workingTimesByType = $query->get()
            ->pluck('total_time', 'working_type')
            ->toArray();

        $labelsWithData = [
            'Offsite' => $workingTimesByType[Report::WORKING_TYPE_OFFSITE] ?? 0,
            'Onsite' => $workingTimesByType[Report::WORKING_TYPE_ONSITE] ?? 0,
            'Remote' => $workingTimesByType[Report::WORKING_TYPE_REMOTE] ?? 0,
            'Off' => $workingTimesByType[Report::WORKING_TYPE_OFF] ?? 0,
        ];

        $labels = array_keys($labelsWithData);
        $data = array_values($labelsWithData);

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get statistics Working time by Project for User
     *
     * @param integer|string $userId
     * @param integer|string|null $projectId
     * @param integer|string $fromDate
     * @param integer|string $toDate
     * @return array
     */
    public function getUserWorkingTimeByProject(
        int|string $userId,
        int|string|null $projectId,
        int|string $fromDate,
        int|string $toDate
    ): array {
        $query = Report::select('project_id')
            ->selectRaw('SUM(working_time) as total_time')
            ->where('user_id', $userId)
            ->where('status', Report::STATUS_APPROVED)
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate);

        // Filter by Project if exist
        if ($projectId !== null) {
            $query->where('project_id', $projectId);
        }

        // Group by Working type
        $query->groupBy('project_id');

        $labels = [];
        $data = [];

        $workingTimesByProject = $query->get();

        foreach ($workingTimesByProject as $key => $item) {
            $labels[] = optional(Project::find($item->project_id))->name;
            $data[] = $item->total_time;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     */
    public function getProjectStatistics(Request $request)
    {
        // Only Admins can see Project statistics
        Gate::denyIf(
            !(auth()->user()->isAdminGroup()),
            __('This action is unauthorized.')
        );

        // List project of user for select
        $projects = Project::all();

        // Date filter range
        $fromDate = $request->from_date ?? now()->firstOfMonth()->toDateString();
        $toDate = $request->to_date ?? now()->toDateString();

        // Project Id for filter by Project
        $project = ($request->project_id === null)
            ? Project::first()
            : Project::find($request->project_id);

        $workingTimesByPosition = $this
            ->getProjectWorkingTimeByPositions(
                $project->id,
                $fromDate,
                $toDate
            );

        // Project's User and their working time by working type
        $projectUsers = $this->getProjectWorkingTimeByUser($project);

        return view('pages.statistics.project-statistic', [
            'projects' => $projects,
            'workingTimesByPosition' => $workingTimesByPosition,
            'projectUsers' => $projectUsers,
            'breadcrumbs' =>  [
                ['link' => "home", 'name' => "Home"],
                ['name' => 'Project Statistics']
            ]
        ]);
    }

    public function getProjectWorkingTimeByPositions(
        int|string|null $projectId,
        int|string $fromDate,
        int|string $toDate
    ) {
        $workingTimesByPosition = Report::select('position_id')
            ->selectRaw('SUM(working_time) as total_time')
            ->where('project_id', $projectId)
            ->where('status', Report::STATUS_APPROVED)
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->groupBy('position_id')
            ->get();

        // Data for chart
        $labels = [];
        $data = [];

        foreach ($workingTimesByPosition as $key => $item) {
            $labels[] = optional(Position::find($item->position_id))->name;
            $data[] = $item->total_time;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getProjectWorkingTimeByUser(Project $project)
    {
        $projectUsers = $project->users;

        $projectUsers = $projectUsers->map(function ($user) use ($project) {
            $workingTimes = $user->reports
                ->where('status', 1)
                ->where('project_id', $project->id)
                ->groupBy('working_type')
                ->mapWithKeys(function ($item, $key) {
                    return [$key => $item->sum('working_time')];
                });

            $workingTimes = $workingTimes
                ->mapWithKeys(function ($item, $key) {
                    return [
                        config('constants.working_type.' . $key . '.name') => $item
                    ];
                });

            return [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'workingTimes' => $workingTimes->toArray()
            ];
        });

        return $projectUsers->toArray();
    }
}
