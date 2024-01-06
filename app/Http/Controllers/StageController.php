<?php

namespace App\Http\Controllers;

use App\Http\Pagination\PaginatedData;
use App\Http\Resources\StageResource;
use App\Models\Project;
use App\Models\Stage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class StageController extends BaseController
{
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(Stage::class)
            ->allowedFilters(['project_id'])
            ->allowedSorts(['name']);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success', StageResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function show($id): JsonResponse
    {
        $stage = Stage::find($id);
        if (is_null($stage))
            return $this->sendError('Stage Not Found');
        else
            return  $this->sendResponse(new StageResource($stage), 'Done');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'color' => ['required', 'max:255'],
            'project_id' => ['required', Rule::exists(Project::class, 'id')],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Please validate error', 422);
        }
        $stage = Stage::create($request->all());
        return $this->sendResponse(new StageResource($stage), 'Stage Store successfully');
    }

    public function update($id,Request $request): JsonResponse
    {
        $stage = Stage::find($id);
        if (is_null($stage))
            return $this->sendError('Stage Not Found');
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'color' => ['required', 'max:255'],
            'project_id' => ['required', Rule::exists(Project::class, 'id')],
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Please validate error', 422);
        }
        $stage->update($request->all());
        return $this->sendResponse(new StageResource($stage), 'Stage Updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $stage = Stage::find($id);
        if (is_null($stage)) {
            return $this->sendError('Stage Not Found', []);
        }
        else {
            $stage->delete();
            return $this->sendMessage('Stage deleted successfully');

        }
    }
}
