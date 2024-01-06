<?php

namespace App\Http\Pagination;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class PaginatedData
{
    private QueryBuilder $queryBuilder;

    private LengthAwarePaginator $paginator;

    public function __construct(QueryBuilder $queryBuilder, $perPage)
    {
        $this->queryBuilder = $queryBuilder;
        $this->paginator = $this->queryBuilder->paginate($perPage);
    }

    public function getData(): array
    {
        return $this->paginator->items();
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return [
            'total' => $this->paginator->total(),
            'per_page' => $this->paginator->perPage(),
            'count' => count($this->paginator->items()),
            'current_page' => $this->paginator->currentPage(),

        ];
    }
}
