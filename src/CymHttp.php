<?php

namespace CymPhptools;

use GuzzleHttp\Client;

class CymHttp
{
    /**
     * 
     */
    public function get($config)
    {
    }
    /**
     * 
     */
    public function post($config)
    {
        $url = $config['url'];
        $data = $config['data'];
        $client = new Client();
        $response = $client->request('POST', $url, [
            'form_params' => $data
        ]);
        return $response;
    }
    /**
     * 
     */
    public function getPuls()
    {
    }
    /**
     * 
     */
    public function postPuls()
    {
    }
}
