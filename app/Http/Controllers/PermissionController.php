<?php

namespace App\Http\Controllers;

use App\Enum\PermissionEnum;
use App\Http\Pagination\PaginatedData;
use App\Http\Resources\PermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends BaseController
{
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(Permission::class)
            ->allowedFilters([
                AllowedFilter::exact('id')
            ])
            ->allowedSorts([
                AllowedSort::field('id')
            ]);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success', PermissionResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'name' => ['required', Rule::in(PermissionEnum::getValues()), Rule::unique(Permission::class, 'name')]
        ]);
        if ($validator->fails())
        {
            return $this->sendError('Please Validate Data', $validator->errors(), 422);
        }
        $permission = Permission::create([
            'name' => $request['name'],
            'guard_name' => 'api'
        ]);
        return $this->sendResponse($permission, 'DONE');
    }

}
