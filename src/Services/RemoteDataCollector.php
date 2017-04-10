<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 25.02.17
 * Time: 11:28
 */

namespace  RonasIT\Support\DataCollectors;

use RonasIT\Support\AutoDoc\Exceptions\CannotFindTemporaryFileException;
use RonasIT\Support\AutoDoc\Interfaces\DataCollectorInterface;
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

    public function __construct()
    {
        $this->tempFilePath = config('remote-data-collector.temporary_path');
        $this->key = config('remote-data-collector.key');
        $this->remoteUrl = config('remote-data-collector.url');

        $this->httpRequestService = app(HttpRequestService::class);

        if (empty($this->tempfilePath)) {
            throw new CannotFindTemporaryFileException();
        }
    }

    public function saveTmpData($tempData) {
        $data = json_encode($tempData);

        file_put_contents($this->tempFilePath, $data);
    }

    public function getTmpData() {
        if (file_exists($this->tempFilePath)) {
            $content = file_get_contents($this->tempFilePath);

            return json_decode($content, true);
        }

        return null;
    }

    public function saveData($data) {
        $this->httpRequestService->sendPost($this->getUrl(), $data);
    }

    public function getDocumentation() {
        $response = $this->httpRequestService->sendGet($this->getUrl());

        return $this->httpRequestService->parseJsonResponse($response);
    }

    protected function getUrl() {
        return "{$this->remoteUrl}/{$this->key}";
    }
}