<?php 

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Exception;
use App\Exceptions\ApiException;

class Response extends JsonResource
{
    protected $model;
    protected $statusHttp;

    public function __construct($model, $statusHttp = 200)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->statusHttp = $statusHttp;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->model instanceof Exception) {
            if ($this->model instanceof ApiException) {
                return [
                    "status" => $this->status,
                    "err"    => $this->err,
                    "msg"    => $this->getMessage(),
                    'line'   => $this->getLine(),
                    'get'    => $this->getFile()
                ];
            } else {
                return [
                    'status' => false,
                    "err" => env("APP_ENV") == "local"
                        ? $this->__toString()
                        : $this->getMessage(),
                    'line' => $this->getLine(),
                    'get' => $this->getFile()
                ];
            }
        }

        $objectReturn = [
            "status" => true,
            "resData" => $this->model,
        ];

        $paginateCheck = ($this->model instanceof \Illuminate\Http\Resources\Json\JsonResource) ? $this->model->resource : $this->model;
        if (
            $paginateCheck instanceof
                \Illuminate\Pagination\Paginator ||
            $paginateCheck instanceof
                \Illuminate\Pagination\LengthAwarePaginator
        ) {
            $objectReturn["meta"] = [
                "count" => $paginateCheck->count(),
                "current_page" => $paginateCheck->currentPage(),
                "last_page" => $paginateCheck->lastPage(),
                "from" => $paginateCheck->firstItem(),
                "to" => $paginateCheck->lastItem(),
                "path" => $paginateCheck->path(),
                "per_page" => (int) $paginateCheck->perPage(),
                "total" => $paginateCheck->total(),
            ];

            $objectReturn["links"] = [
                "first" => $paginateCheck->url(1),
                "last" => $paginateCheck->url($this->lastPage()),
                "prev" => $paginateCheck->previousPageUrl(),
                "next" => $paginateCheck->nextPageUrl(),
            ];
        }

        return $objectReturn;
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response  $response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $statusHttp = $this->statusHttp;
        if ($this->model instanceof Exception) {
            if ($this->model instanceof ApiException) {
                $statusHttp = $this->model->statusHttp;
            } else {
                $statusHttp = 500;
            }
        }

        $response->setStatusCode($statusHttp);
    }
}
