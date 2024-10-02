<?php

namespace Wovosoft\LaravelCpanel\Modules;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Wovosoft\LaravelCpanel\CpanelClient;

class FileMan
{
    /**
     * @param string $filePath File to be uploaded in current pc
     * @param string $destinationPath Location where file will be upload in server machine
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public function uploadFile(string $filePath, string $destinationPath): PromiseInterface|Response
    {
        return CpanelClient::client()
            ->getHttpClient()
            ->asMultipart()
            ->attach(
                name: 'file-1',
                contents: file_get_contents($filePath),
                filename: basename($filePath)
            )->post("Fileman/upload_files", [
                'dir' => $destinationPath, // Destination directory on the server
            ]);
    }


}
