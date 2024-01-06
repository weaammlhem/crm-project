<?php

namespace App\Http\Controllers;

use App\Enum\PriorityTypeEnum;
use App\Http\Resources\TaskResource;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TaskController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->sendResponse(TaskResource::collection(Task::all()), 'Done');
    }

    public function show($id): JsonResponse
    {
        $task = Task::find($id);
        if (is_null($task))
            return $this->sendError('Task Not Found');
        else
            return  $this->sendResponse(new TaskResource($task), 'Done');
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'stage_id' => ['required', Rule::exists(Stage::class, 'id')],
            'priority' => ['required', Rule::in(PriorityTypeEnum::getValues())],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
            'users_ids' => ['array', 'required'],
            'users_ids.*' => [Rule::exists(User::class, 'id')]
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 'Please validate error', 422);
        }
        $task = Task::create($request->all());
        $task->users()->sync($request['users_ids']);
        return $this->sendResponse(new TaskResource($task), 'Task Store successfully');
    }

    public function update($id,Request $request): JsonResponse
    {
        $task = Task::find($id);
        if (is_null($task))
            return $this->sendError('Task Not Found');
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'description' => ['required', 'max:255'],
            'stage_id' => ['required', Rule::exists(Stage::class, 'id')],
            'priority' => ['required', Rule::in(PriorityTypeEnum::getValues())],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after:start_date'],
        ]);
        if ($validator->fails()) {
            return $this->sendError( $validator->errors(), 'Please validate error', 422);
        }
        $task->update($request->all());
        return $this->sendResponse(new TaskResource($task), 'Task Updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $task = Task::find($id);
        if (is_null($task)) {
            return $this->sendError('Task Not Found', []);
        }
        else {
            $task->delete();
            return $this->sendMessage('Task deleted successfully');

        }
    }
}
