<?php

namespace App\Repositories\Eloquent\Project;

use App\Models\Position;
use App\Models\User;
use App\Models\Project;
use App\Models\ProjectMember;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\ProjectRepository;
use App\Repositories\Eloquent\EloquentBaseRepository;
use Illuminate\Support\Arr;

class EloquentProjectRepository extends EloquentBaseRepository implements
    ProjectRepository
{
    private UserRepository $userRepository;

    public function __construct(Project $model, UserRepository $userRepository)
    {
        $this->model = $model;
        $this->userRepository = $userRepository;
    }

    /**
     * Create new Project
     *
     * @param array $payload
     * @return Project
     */
    public function createProject(array $payload): Project
    {
        DB::beginTransaction();

        try {
            // dd($payload);

            $project = $this->create($payload);

            if (!empty($payload['project_members'])) {
                $projectMemberRows = array_map(
                    function ($item) use ($project) {
                        $item['project_id'] = $project->id;

                        return $item;
                    },
                    $payload['project_members']
                );

                ProjectMember::upsert(
                    $projectMemberRows,
                    ['project_id', 'user_id', 'position_id'],
                    ['project_id', 'user_id', 'position_id']
                );
            }

            DB::commit();

            return $project->fresh();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Update exist Project
     *
     * @param integer|string|Project $key
     * @param array $payload
     * @return Project
     */
    public function updateProject(
        int|string|Project $key,
        array $payload
    ): Project {
        DB::beginTransaction();

        try {
            $project = $this->update($key, Arr::except($payload, 'slug'));

            if (!empty($payload['project_members'])) {
                $projectMemberRows = array_map(
                    function ($item) use ($project) {
                        $item['project_id'] = $project->id;

                        return $item;
                    },
                    $payload['project_members']
                );

                $project->projectMembers()->delete();

                ProjectMember::upsert(
                    $projectMemberRows,
                    ['project_id', 'user_id', 'position_id'],
                    ['project_id', 'user_id', 'position_id']
                );
            }

            DB::commit();

            return $project;
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Delete exist Project
     *
     * @param integer|string|Project $key
     * @return boolean
     */
    public function deleteProject(int|string|Project $key): bool
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
     * Get DataTables Of Project
     *
     */
    public function datatables()
    {
        $eloquent = $this->model
            ->with('projectMembers.user');

        return DataTables::of($eloquent)
            ->filter(function ($query) {
                // Filter By User
                $userId = null;

                if (request()->has('user_id') && !is_null(request('user_id'))) {
                    $userId = request('user_id');
                }

                if ((bool) request('my_project', false)) {
                    $userId = auth()->id();
                }

                if (!is_null($userId)) {
                    $query->whereHas(
                        'projectMembers.user',
                        function (Builder $user) use ($userId) {
                            $user->where('id', $userId);
                        }
                    );
                }

                // Filter By Status
                if (request()->has('status') && !is_null(request('status'))) {
                    $status = request('status');

                    $query->where('status', $status);
                }

                // Search By Name And Slug
                if (request()->has('search')) {
                    $search = request('search');

                    $query->where(function ($subQuery) use ($search) {
                        $subQuery
                            ->where('name', 'LIKE', "%$search%")
                            ->orWhere('slug', 'LIKE', "%$search%");
                    });
                }

                // Sort By Time and Alphabet
                if (request()->has('sort')) {
                    switch (request('sort')) {
                        case config('constants.sort.latest'):
                            $query->orderBy('created_at', 'desc');

                        case config('constants.sort.a-z'):
                            $query->orderBy('name', 'asc');

                        case config('constants.sort.z-a'):
                            $query->orderBy('name', 'desc');

                        case config('constants.sort.oldest'):
                        default:
                            $query->orderBy('created_at', 'asc');
                            break;
                    }
                }
            })
            ->addColumn('html', function ($project) {
                return view(
                    'components.datatables.project-card',
                    ['project' => $project]
                )->render();
            })
            ->rawColumns(['html'])
            ->make(true);
    }

    /**
     * Get Count Project By Status
     *
     * @return array
     */
    public function getCountByStatus(): array
    {
        $projects = $this->findAll();

        return $projects->groupBy('status')->map->count()->toArray();
    }

    /**
     * Get Data for create: list user, list position
     *
     * @return array
     */
    public function getDataForCreate(): array
    {
        $usersOption = toSelect2(
            User::where('status', 1)->get(),
            'id',
            'full_name'
        );

        $positionsOptions = toSelect2(Position::all(), 'id', 'name');

        return [
            'userOptions' => $usersOption,
            'positionOptions' => $positionsOptions
        ];
    }

    /**
     * Get Data for Edit: list user, list status, list position
     *
     * @return array
     */
    public function getDataForEdit(): array
    {
        $statusTexts = $this->model->getStatusText();

        $statusOptions = [
            [
                'id' => Project::STATUS_ON_HOLD,
                'text' => $statusTexts[Project::STATUS_ON_HOLD]
            ],
            [
                'id' => Project::STATUS_CANCELLED,
                'text' => $statusTexts[Project::STATUS_CANCELLED]
            ],
            [
                'id' => Project::STATUS_IN_PROGRESS,
                'text' => $statusTexts[Project::STATUS_IN_PROGRESS]
            ],
            [
                'id' => Project::STATUS_COMPLETED,
                'text' => $statusTexts[Project::STATUS_COMPLETED]
            ],
        ];


        return array_merge(
            $this->getDataForCreate(),
            ['statusOptions' => $statusOptions]
        );
    }

    /**
     * Make Project Details For Show Project
     *
     * @param Project $project
     * @return array
     */
    public function makeProjectDetails(Project $project): array
    {
        $project->load([
            'projectMembers.user',
            'projectMembers.position'
        ]);

        $projectMembers = $project->projectMembers;

        $userOfProjects = $projectMembers
            ->map(function ($projectMember) {
                return $projectMember->user;
            })->unique();

        $userWithPositions = $projectMembers
            ->mapToGroups(function ($projectMember) {
                return [$projectMember->user->id => $projectMember->position];
            })->map(function ($positions, $userId) use ($userOfProjects) {
                $user = $userOfProjects->where('id', $userId)->first();

                return [
                    'user' => $user,
                    'positions' => $positions,
                ];
            });

        return [
            'project' => $project,
            'userWithPositions' => $userWithPositions
        ];
    }
}
