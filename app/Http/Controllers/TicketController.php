<?php

namespace App\Http\Controllers;

use App\Enum\TicketStatusEnum;
use App\Enum\TicketTypeEnum;
use App\Http\Pagination\PaginatedData;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class TicketController extends BaseController
{
    public function index(): JsonResponse
    {
        $per_page = request('per_page', 15);
        $data = QueryBuilder::for(Ticket::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
            ])
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('title'),
            ]);
        $paginatedData =  new PaginatedData($data, $per_page);
        return $this->response('success', TicketResource::collection($paginatedData->getData()), ['pagination' => $paginatedData->getPagination()]);
    }

    public function show($id): JsonResponse
    {
        $ticket = Ticket::find($id);
        if ($ticket == null)
            return $this->sendError('Not Found');
        else
            return $this->sendResponse(new TicketResource($ticket), 'Done');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'max:255'],
            'summary' => ['required', 'max:255'],
            'type' => ['required', Rule::in(TicketTypeEnum::getValues())],
            'status' => ['required', Rule::in(TicketStatusEnum::getValues())],
            'user_id' => ['required', Rule::exists(User::class, 'id')]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors(), 422);
        }

        $ticket = Ticket::create($request->all());
        return $this->response('The Ticket added successfully', new TicketResource($ticket));
    }

    public function update($id, Request $request)
    {
        $ticket = Ticket::find($id);
        if (is_null($ticket)) {
            return $this->sendError('Ticket Not Found', 404);
        }
        $validator = Validator::make($request->all(), [
            'title' => ['max:255'],
            'summary' => ['max:255'],
            'type' => [Rule::in(TicketTypeEnum::getValues())],
            'status' => [Rule::in(TicketStatusEnum::getValues())],
            'user_id' => [Rule::exists(User::class, 'id')]
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors(), 422);
        }

        $ticket->update($request->all());
        return $this->response('The ticket updated successfully', new TicketResource($ticket));
    }

    public function destroy($id): JsonResponse
    {
        $ticket = Ticket::find($id);
        if (is_null($ticket)) {
            return $this->sendError('Ticket Not Found', []);
        }
        else {
            $ticket->delete();
            return $this->sendMessage('Ticket deleted successfully');
        }
    }

}
