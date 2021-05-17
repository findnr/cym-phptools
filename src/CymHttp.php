<?php
/*
 * @Author: 程英明
 * @Date: 2021-04-23 15:39:24
 * @LastEditTime: 2021-05-17 14:15:07
 * @LastEditors: 程英明
 * @Description: 
 * @FilePath: \cym-phptools\src\CymHttp.php
 * QQ:504875043@qq.com
 */

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
        // $data['info'] = $response;
        $data['data'] = json_decode((string) $response->getBody(), true);
        return $data['data'];
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
