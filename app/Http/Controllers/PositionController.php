<?php

namespace App\Http\Controllers;

use App\Http\Requests\Position\StoreRequest;
use App\Http\Requests\Position\UpdateRequest;
use App\Models\Position;
use App\Repositories\Contracts\PositionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PositionController extends Controller
{
    private PositionRepository $positionRepository;
    private const VIEW_INDEX = 'pages.positions.index';

    public function __construct(PositionRepository $positionRepository)
    {
        $this->positionRepository = $positionRepository;

        $this->middleware('permission:positions_read')
            ->only(['index']);

        $this->middleware('permission:positions_create')
            ->only(['create', 'store']);

        $this->middleware('permission:positions_update')
            ->only(['edit', 'update']);

        $this->middleware('permission:positions_delete')
            ->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(
            self::VIEW_INDEX,
            [
                'configData' => \App\Helpers\Helper::applClasses(),
                'breadcrumbs' => config('breadcrumbs.positions.index', [])
            ],
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Create New Position
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse;
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $this->positionRepository
                ->createPosition($request->all());
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Create failed'));
        }

        return jsend_success(null, __('Create success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Position $position)
    {
        //
    }

    /**
     * Show the form for editing Position.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Position $position): JsonResponse
    {
        return jsend_success(['position' => $position]);
    }

    /**
     * Update Position.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(
        UpdateRequest $request,
        Position $position
    ): JsonResponse {
        try {
            $this->positionRepository
                ->updatePosition($position, $request->all());
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Update failed'));
        }

        return jsend_success(null, __('Update success'));
    }

    /**
     * Remove Position.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Position $position): JsonResponse
    {
        try {
            $this->positionRepository
                ->deletePosition($position);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error($e);

            throw new \Exception(__('Delete failed'));
        }

        return jsend_success(null, __('Delete success'));
    }


    /**
     * Get DataTables Of Positions
     *
     */
    public function datatables()
    {
        return $this->positionRepository->datatables();
    }
}
