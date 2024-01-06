<?php

namespace App\Http\Controllers;

use App\Http\Pagination\PaginatedData;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends BaseController
{

    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(Role::class)
            ->allowedFilters([
                AllowedFilter::exact('id')
            ])
            ->allowedSorts([
                'id'
            ]);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success',  RoleResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $role = Role::find($id);
        if ($role == null)
            return $this->sendError('Not Found');

        $validator = Validator::make($request->all(), [
            'name' => [Rule::unique(Role::class, 'name')],
            'permission_ids' => ['array', 'required'],
            'permission_ids.*' => [Rule::exists(Permission::class, 'id'), 'required']
        ]);
        if ($validator->fails())
        {
            return $this->sendError('Please Validate Data', $validator->errors(), 422);
        }
        $role->update($request->all());
        if (isset($request['permission_ids']))
            $role->permissions()->sync($request['permission_ids']);

        return $this->response('Role Updated successfully', new RoleResource($role));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', Rule::unique(Role::class, 'name')],
        ]);
        if ($validator->fails())
        {
            return $this->sendError('Please Validate Data', $validator->errors(), 422);
        }
        $role = Role::create([
            'name' => $request['name'],
            'guard_name' => 'api'
        ]);
        return $this->response('Role Updated successfully', new RoleResource($role));
    }

}
