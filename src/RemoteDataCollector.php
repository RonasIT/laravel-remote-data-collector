<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 25.02.17
 * Time: 11:28
 */

namespace  RonasIT\Support\DataCollectors;

use RonasIT\Support\Interfaces\DataCollectorInterface;
use RonasIT\Support\Services\HttpRequestService;

/**
 * @property HttpRequestService $httpRequestService
*/
class RemoteDataCollector implements DataCollectorInterface
{
    protected $remoteUrl;
    protected $tempFilePath;
    protected $key;
    protected $httpRequestService;

    protected static $data = [];

    public function __construct()
    {
        $this->tempFilePath = config('remote-data-collector.temporary_path');
        $this->key = config('remote-data-collector.key');
        $this->remoteUrl = config('remote-data-collector.url');

        $this->httpRequestService = app(HttpRequestService::class);
    }

    public function saveTmpData($tempData)
    {
        self::$data = $tempData;
    }

    public function getTmpData() {
        return self::$data;
    }

    public function saveData() {
        $this->httpRequestService->sendPost(
            $this->getUrl(),
            self::$data,
            ['Content-Type' => 'application/json']
        );

        self::$data = [];
    }

    public function getDocumentation() {
        $response = $this->httpRequestService->sendGet($this->getUrl());

        return $this->httpRequestService->parseJsonResponse($response);
    }

    protected function getUrl() {
        return "{$this->remoteUrl}/documentations/{$this->key}";
    }
}