<?php

namespace App\Helpers;

use Illuminate\Pagination\Paginator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class FractalResponseHelper
{

    private $transformer;
    private $transformerType;
    private $dataType;

    const IS_ITEM = 'Item';

    public function __construct($transformer, $type, $dataType)
    {
        $this->setTransformer($transformer);
        $this->setTransformerType($type);
        $this->dataType = $dataType;
    }

    private function customPaginateMeta($paginator)
    {
        $currentPage = $paginator->currentPage();
        return [
            'pagination' => [
                'count' => $paginator->count(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'links' => [
                    'next' => $paginator->url($currentPage + 1),
                    'previous' => $paginator->url($currentPage - 1)
                ]


            ]
        ];

    }

    public function setTransformer($transformer)
    {
        $this->transformer = $transformer;
    }

    public function setTransformerType($type)
    {
        $this->transformerType = $type;
    }

    public function response($data, $status, $meta = [], $headers = [], $paginator = null, $includes = null)
    {
        $fractal = app()->make('League\Fractal\Manager');
        if ($includes != null) {
            $fractal->parseIncludes($includes);
        }

        if ($this->transformerType === SELF::IS_ITEM) {
            $resource = new \League\Fractal\Resource\Item($data, $this->transformer);
        } else {
            $resource = new \League\Fractal\Resource\Collection($data, $this->transformer);
        }

        if (is_array($meta) && !empty($meta)) {
            $resource->setMeta($meta);
        }

        if ($paginator instanceof LengthAwarePaginator && $paginator != null) {
            $paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
            $resource->setPaginator($paginatorAdapter);
        } else if ($paginator instanceof Paginator && $paginator != null) {

            $resource->setMeta($this->customPaginateMeta($paginator));

        }

        return response()->json(
            $fractal->createData($resource)->toArray(),
            $status,
            $headers
        );
    }
}
