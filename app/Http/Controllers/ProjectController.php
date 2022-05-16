<?php

namespace App\Http\Controllers;

use App\Exceptions\FailException;
use App\Http\Requests\Project\CreateRequest;
use App\Http\Requests\Project\UpdateRequest;
use App\Models\Position;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepository;
use App\Repositories\Contracts\ProjectRepository;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    private const VIEW_INDEX = 'pages.projects.index';

    private ProjectRepository $projectRepository;
    private UserRepository $userRepository;

    public function __construct(
        ProjectRepository $projectRepository,
        UserRepository $userRepository
    ) {
        $this->projectRepository = $projectRepository;
        $this->userRepository = $userRepository;

        $this->middleware('permission:projects_read')
            ->only(['index', 'show']);

        $this->middleware('permission:projects_create')
            ->only(['create', 'store']);

        $this->middleware('permission:projects_update')
            ->only(['edit', 'update']);

        $this->middleware('permission:projects_delete')
            ->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userRepository->findAll();
        $breadcrumbs =  [
            ['link' => 'home', 'name' => 'Home'],
            ['link' => 'projects', 'name' => 'List Project'],
            ['name' => 'index']
        ];

        return view(
            self::VIEW_INDEX,
            [
                'users' => $users,
                'breadcrumbs' => $breadcrumbs
            ]
        );
    }

    /**
     * Get DataTables Of Project
     *
     */
    public function datatables()
    {
        return $this->projectRepository->datatables();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dataForCreate = $this->projectRepository->getDataForCreate();

        return jsend_success($dataForCreate);
    }

    /**
     * Hanlde input before pass to Repository
     *
     * @param Request $request
     * @return array
     */
    public function handleInput(Request $request): array
    {
        $payload = $request->except(['users', 'positions']);

        if ($request->has('users') && $request->has('positions')) {
            $users = $request->users;
            $positions = $request->positions;

            if (count($users) !== count($positions)) {
                throw new FailException(__('Data Invalid'));
            }

            $data = [];

            foreach ($users as $key => $user) {
                $data[] = Arr::crossJoin($user, $positions[$key]);
            }

            $data = Arr::flatten($data, 1);

            $data = array_map(function ($row) {
                return array_combine(['user_id', 'position_id'], $row);
            }, $data);

            $payload['project_members'] = $data;
        }

        return $payload;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        try {
            $payload = $this->handleInput($request);

            $this->projectRepository->createProject($payload);
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
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view(
            'pages.projects.show',
            $this->projectRepository->makeProjectDetails($project)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $dataForEdit = $this->projectRepository->getDataForEdit();
        $dataForEdit['project'] = $project->load('projectMembers');

        return jsend_success($dataForEdit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Project $project)
    {
        try {
            $payload = $this->handleInput($request);

            $this->projectRepository->updateProject($project, $payload);
        } catch (FailException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Update failed'));
        }

        return jsend_success(null, __('Update success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        try {
            $this->projectRepository->deleteProject($project);
        } catch (\Exception $e) {
            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }
}
