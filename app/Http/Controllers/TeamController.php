<?php

namespace App\Http\Controllers;

use App\Enum\PermissionEnum;
use App\Http\Pagination\PaginatedData;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TeamController extends BaseController
{

    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(Team::class)
            ->allowedFilters([
                AllowedFilter::exact('id')
            ])
            ->allowedSorts([
                AllowedSort::field('id')
            ]);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success', TeamResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function show($id): JsonResponse
    {
        $team = Team::find($id);
        if ($team == null)
            return $this->sendError('Not Found');
        else {
            $user = Auth::user();
            if ($user->checkPermissionTo(PermissionEnum::SHOW_USERS))
                return $this->response('Done', new TeamResource($team));
            return $this->sendError('You dont have permission', [], 403);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'max_number' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors(),  422);
        }

        $team = Team::create($request->all());
        return $this->response('Team Store successfully', new TeamResource($team));

    }

    public function update($id, Request $request): JsonResponse
    {
        $team = Team::find($id);
        if (is_null($team)) {
            return $this->sendError('Team Not Found', 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'max_number' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error', $validator->errors(), 422);
        }

        $team->update($request->all());
        return $this->response('Team Updated successfully', new TeamResource($team));
    }

    public function destroy($id): JsonResponse
    {
        $team = Team::find($id);
        if (is_null($team)) {
            return $this->sendError('Team Not Found', []);
        }
        else {
            $team->delete();
            return $this->sendMessage('Team deleted successfully');

        }
    }

}
