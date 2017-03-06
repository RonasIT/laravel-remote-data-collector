<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 25.02.17
 * Time: 11:28
 */

namespace  RonasIT\Support\RemoteDataCollector\Services;


use RonasIT\Support\RemoteDataCollector\Exceptions\CannotFindTemporaryFileException;
use RonasIT\Support\AutoDoc\Interfaces\DataCollectorInterface;
use Illuminate\Support\Str;

class RemoteDataCollectorService implements DataCollectorInterface
{
    protected $remoteUrl;
    protected $tempFilePath;
    protected $key;

    public function __construct()
    {
        $this->tempfilePath = config('remote-data-collector.temporary_path');
        $this->key = config('remote-data-collector.key');
        $this->remoteUrl = config('remote-data-collector.url')."/{$this->key}";

        if (empty($this->tempfilePath)) {
            throw new CannotFindTemporaryFileException();
        }
    }

    public function saveData($tempFile){
        $this->tempfilePath = $tempFile;

        $this->makeRequest();
    }

    public function getFileContent() {
        $content = json_decode(file_get_contents($this->remoteUrl), true);

        return json_decode($content['document']);
    }

    protected function makeRequest() {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->remoteUrl,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'document' => file_get_contents($this->tempfilePath),
            ]
        ]);

        $response = curl_exec($curl);

        if (curl_error($curl)) {
            throw new CurlRequestErrorException();
        } else {
            curl_close($curl);
        }
    }
}