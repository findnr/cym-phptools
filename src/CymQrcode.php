<?php

namespace  CymPhptools;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class CymQrcode
{
    private $key;

    private $data;

    private $str;
    /**
     * 协会内部使用
     */
    public function getXiehuiQrcode($data)
    {
        $this->key = $data['config']['key'];
        $this->data = $data['data'];
        $key_str = $this->encode();

        $margin = empty($data['margin']) ? 0 : (int)$data['margin'];

        $size = empty($data['size']) ? 200 : (int)$data['size'];

        $content = 'http://api.gzwea.com/' . $data['control'] . '/' . $key_str;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size($size)
            ->margin($margin)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath($data['config']['logo_path'])
            ->logoResizeToWidth(80)
            ->logoResizeToHeight(80)
            ->build();
        $result->saveToFile($data['config']['save_qrcode']);
    }
    /**
     * 获取二维码
     */
    public function getQrcode($data)
    {
        $config = $data['config'];
        $this->data = $data['data'];
        if (empty($config['key'])) {
            $key_str = $this->decode();
            $url = $config['url'] . '?keystr=' . $key_str;
        } else {
            $url = $config['url'] . '?';
            $i = 0;
            foreach ($this->data as $k => $v) {
                if ($i == 0) {
                    $url = $url . $k . '=' . $v;
                } else {
                    $url = $url . '&' . $k . '=' . $v;
                }
                $i++;
            }
        }
        $size =  empty($config['size']) ? 200 : (int) $config['size'];
        $margin = empty($config['margin']) ? 10 : (int) $config['margin'];
        try {
            $result = Builder::create();
            $result->writer(new PngWriter())->writerOptions([]);
            $result->data($url)->errorCorrectionLevel(new ErrorCorrectionLevelHigh());
            $result->size($size)->margin($margin)->roundBlockSizeMode(new RoundBlockSizeModeMargin());
            if (!empty($config['logo_path'])) {
                $logo_width = (empty($config['logo_width']) || !((int) $config['logo_width'])) ? 80 : (int) $config['logo_width'];
                $logo_height = empty($config['logo_height']) || !((int) $config['logo_height']) ? 80 : (int) $config['logo_height'];
                $result->logoPath($data['config']['logo_path'])->logoResizeToWidth($logo_width)->logoResizeToHeight($logo_height);
            }
            $result->build();
            $result->saveToFile($config['save_path']);
            $res_data['action'] = 1;
            $res_data['msg'] = '生成成功';
            $res_data['save_path'] = $config['save_path'];
            return $res_data;
        } catch (\Throwable $th) {
            //throw $th;
            $res_data['action'] = 0;
            $res_data['msg'] = "#getQrcode function error";
            $res_data['info'] = $th;
            return $res_data;
        }
    }
    /**
     * 获取解密数据
     */
    public function getInfo($data = [])
    {
        if (empty($data['key'])) {
        }
        if (empty($data['str'])) {
        }
        $this->key = $data['key'];
        $this->str = $data['str'];
        return $this->decode();
    }

    private function encode()
    {
        $str_data = json_encode($this->data);
        $data = openssl_encrypt($str_data, 'AES-128-ECB', $this->key, OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        return $data;
    }
    private function decode()
    {
        $decrypted = openssl_decrypt(base64_decode($this->str), 'AES-128-ECB', $this->key, OPENSSL_RAW_DATA);
        $data = json_decode($decrypted, true);
        return $data;
    }
}
