<?php

namespace App\Http\Controllers;

use App\Http\Pagination\PaginatedData;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends BaseController
{

    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::exact('id')
            ])
            ->allowedSorts([
                AllowedSort::field('id')
            ]);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success',  ProjectResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function show($id): JsonResponse
    {
        $project = Project::find($id);
        if (is_null($project))
            return $this->sendError('Project Not Found');
        else
            return $this->response('Done', new ProjectResource($project));
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'team_id' => ['required', Rule::exists(Team::class, 'id')],
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error' , $validator->errors(),  422);
        }
        $project = Project::create($request->all());
        return $this->response('The project added successfully', new ProjectResource($project));

    }

    public function update($id,Request $request): JsonResponse
    {
        $project = Project::find($id);
        if (is_null($project))
            return $this->sendError('Project Not Found');
        $validator = Validator::make($request->all(), [
            'team_id' => [Rule::exists(Team::class, 'id')],
            'title' => ['max:255'],
            'description' => ['max:255'],
            'start_date' => ['date_format:Y-m-d'],
            'end_date' => ['date_format:Y-m-d', 'after:start_date'],
        ]);
        if ($validator->fails()) {
            return $this->sendError( 'Please validate error', $validator->errors(),  422);
        }
        $project->update($request->all());
        return $this->response('The project Updated successfully', new ProjectResource($project));
    }

    public function destroy($id): JsonResponse
    {
        $project = Project::find($id);
        if (is_null($project)) {
            return $this->sendError('Project Not Found');
        }
        else {
            $project->delete();
            return $this->sendMessage('Project deleted successfully');
        }
    }
}
