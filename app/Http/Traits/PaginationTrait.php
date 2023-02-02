<?php

namespace App\Http\Traits;

trait PaginationTrait
{
    protected function meta($data): array
    {
        return [
           // 'data' => $data->data(),
            'current_page' => $data->currentPage(),
            'per_page' => $data->perPage(),
            'last_page' => $data->lastPage(),
            'total' => $data->total(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
        ];
    }
}
