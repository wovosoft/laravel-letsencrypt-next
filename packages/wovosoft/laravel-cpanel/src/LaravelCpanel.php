<?php

namespace Wovosoft\LaravelCpanel;

use Illuminate\Support\Facades\Http;

class LaravelCpanel extends CpanelClient
{
    public function getCpanelDiskQuotaInfo()
    {
        // Set the cPanel API module and function for getting disk quota info
        $module = "Quota";
        $function = "get_local_quota_info";

        // Call the CpanelApi function to perform the cPanel API request
//        return $this->requestCpanel($module, $function);
    }


//    public function requestCpanel(string $module, string $function, array $args = [])
//    {
//        // Construct the API call URL
//        $url = $this->getDomain() . ':' . $this->getPort() . '/execute/' . $module . '/' . $function;
//
//        // Set headers
//        $headers = [
//            "Authorization" => "cpanel " . $this->username . ':' . $this->token,
//            "cache-control" => "no-cache"
//        ];
//
//        // Perform the GET request with Laravel's HTTP Client
//        $response = Http::withHeaders($headers)
//            ->withOptions([
//                'verify' => false, // Disables SSL verification
//            ])
//            ->get($url, $args);
//
//        // Prepare the response
//        $responseArray = [
//            'inputs' => ['url' => $url],
//            'status' => $response->successful() ? 'success' : 'failed',
//            'curl_response' => [
//                'curl_response' => $response->body(),
//                'curl_response_decoded' => $response->json(),
//                'headers' => $response->headers(),
//                'body' => $response->body(),
//                'error' => $response->failed() ? $response->toPsrResponse()->getReasonPhrase() : null,
//            ]
//        ];
//
//        // Check for errors in the response
//        if ($response->failed() || isset($responseArray['curl_response']['curl_response_decoded']['errors'])) {
//            $responseArray['errors'] = $responseArray['curl_response']['curl_response_decoded']['errors'] ?? ['Unknown error'];
//            return $responseArray;
//        }
//
//        // Return success response with data
//        $responseArray['data'] = $response->json();
//        return $responseArray;
//    }

}
