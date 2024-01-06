<?php

namespace App\Http\Controllers;

use App\Enum\GenderType;
use App\Enum\UserType;
use App\Http\Pagination\PaginatedData;
use App\Http\Resources\UserResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends BaseController
{
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $entities = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::exact('id')
            ])
            ->allowedSorts([
                AllowedSort::field('id')
            ]);
        $paginatedData =  new PaginatedData($entities, $per_page);
        return $this->response('success', UserResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);

    }

    public function show($id): JsonResponse
    {
        $user = User::find($id);
        if ($user == null)
            return $this->sendError('Not Found');
        else
            return $this->sendResponse(new UserResource($user), 'Done');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', Rule::unique(User::class, 'email')],
            'password' => ['required', 'min:8'],
            'team_id' => ['required', Rule::exists(Team::class, 'id')],
            'address' => ['required', 'max:255'],
            'age' => ['required', 'date'],
            'phone' => ['required', 'max:255'],
            'gender' => ['required', Rule::in(GenderType::getValues())],
            'specialize' => ['required', 'max:255'],
            'type' => ['required', Rule::in(UserType::getValues())],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors(), 422);
        }

        $user = User::create($request->all());
        return $this->response('The user added successfully', new UserResource($user));
    }

    public function update($id, Request $request): JsonResponse
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User Not Found', 404);
        }
        $validator = Validator::make($request->all(), [
            'name' => ['max:255'],
            'email' => ['email', Rule::unique(User::class, 'email')],
            'password' => ['min:8'],
            'team_id' => [Rule::exists(Team::class, 'id')],
            'address' => ['max:255'],
            'age' => ['date'],
            'phone' => ['max:255'],
            'gender' => [Rule::in(GenderType::getValues())],
            'specialize' => ['max:255'],
            'type' => [Rule::in(UserType::getValues())],
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors(), 422);
        }

        $user->update($request->all());
        return $this->response('The user updated successfully', new UserResource($user));
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User Not Found', []);
        }
        else {
            $user->delete();
            return $this->sendMessage('User deleted successfully');

        }
    }


}
