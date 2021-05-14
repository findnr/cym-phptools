<?php

namespace CymPhptools;

use Firebase\JWT\JWT;

/**
 *    iss: jwt签发者 (可选)
 *    sub: jwt所面向的用户 (可选) 
 *    aud: 接收jwt的一方 (可选)
 *    exp: jwt的过期时间，过期时间必须要大于签发时间 
 *    nbf: 定义在什么时间之前，某个时间点后才能访问 
 *    iat: jwt的签发时间 
 *    jti: jwt的唯一身份标识，主要用来作为一次性token。
 */
class CymJwt
{
    /**
     * @param array $config 配制信息
     *                      key: 加密密钥
     *                      time: 设置过期时间（单位S）
     *                      data: array 用户数据
     * 
     *                      
     */
    public static function token_encode($config)
    {
        $time = time();
        $key = $config['key'];
        $token['iss'] = '';
        $token['aud'] = '';
        $token['iat'] = $time;
        $token['exp'] = $time + $config['time'];
        $token['data'] = $config['data'];
        return JWT::encode($token, $key);
    }
    /**
     * @param array $config 配制信息
     *                      key: 加密密钥
     *                      token: token字符串
     */
    public static function token_decode($config)
    {
        $key = $config['key'];
        $token = $config['token'];
        try {
            $decoded = JWT::decode($token, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
            $arr['data'] = (array)$decoded;
            $arr['msg'] = "获取成功";
            $arr['code'] = 1;
            return $arr;
        } catch (\Firebase\JWT\SignatureInvalidException $e) {  //签名不正确
            $arr['code'] = 2;
            $arr['msg'] = "密钥不正确";
            return $arr;
        } catch (\Firebase\JWT\BeforeValidException $e) {  // 签名在某个时间点之后才能用
            $arr['code'] = 2;
            $arr['msg'] = "签名在某个时间点之后才能用";
            return $arr;
        } catch (\Firebase\JWT\ExpiredException $e) {  // token过期
            $arr['code'] = 3;
            $arr['msg'] = "token过期";
            return $arr;
        }
    }
}
