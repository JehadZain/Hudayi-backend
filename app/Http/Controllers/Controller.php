<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model;

    protected $baseRepo;

    protected $mobileRepo;

    protected $appRepo;

    private $data;

    protected string $ip = '';

    private int $status = Response::HTTP_ACCEPTED;

    private string $apiStatus;

    protected $responseHeaders = [];

    private $request;

    public function __construct()
    {
    }

    public function setRequest($request): void
    {
        $this->request = $request;
    }

    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    private function getIp(): string
    {
        return $this->ip;
    }

    public function setData($data): void
    {
        $this->data = $data->data;
        $this->apiStatus = $data->api;
        $this->hints = $data->hints;
    }

    private function setLog($message, $request): void
    {
    }

    protected function response($request, $data): JsonResponse
    {
        try {
            $this->setRequest($request);

            $this->setData($data);

            return response()
                ->json(
                    [
                        'api' => $this->apiStatus,
                        'hints' => $this->hints,
                        'data' => $this->data,
                    ],
                    $this->status,
                    [
                        ...$this->responseHeaders,
                        'IP' => $this->getIp(),
                    ]
                );
        } catch (Exception $e) {
            if (App::hasDebugModeEnabled()) {
                return response()
                    ->json(
                        [
                            'api' => 'FAILED',
                            'hints' => [
                                $e->getMessage(),
                            ],
                            'data' => null,
                        ],
                        $this->status,
                        [
                            ...$this->responseHeaders,
                            'IP' => $this->getIp(),
                        ]
                    );
            } else {
                //make log into errors logs
                $this->setLog($e->getMessage(), '');

                return response()->json(
                    [
                        'api' => 'FAILED',
                        'hints' => [
                            'SOMETHING_WENT_WRONG',
                        ],
                        'data' => null,
                    ],
                    500,
                    [
                        ...$this->responseHeaders,
                        'IP' => $this->getIp(),
                    ]
                );
            }
        }
    }
}
