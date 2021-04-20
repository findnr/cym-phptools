<?php

namespace  CymPhptools;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Label\Font\NotoSans;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

class CymQrcode
{
    private $key;

    private $data;

    private $str;

    public function getXiehuiQrcode($data)
    {
        $this->data = $data['data'];
        $key_str = $this->encode();

        $content = 'http://api.gzwea.com/' . $data['control'] . '/' . $key_str;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath($data['config']['logo_path'])
            ->logoResizeToWidth(80)
            ->logoResizeToHeight(80)
            ->build();
        $result->saveToFile($data['config']['save_qrcode']);
    }
    public function getInfo()
    {
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
        return $decrypted;
    }
}
