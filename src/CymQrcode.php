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

    public function getXiehuiQrcode($key, $data, $name)
    {
        $key_str = $this->encode();

        $content = 'http://cre.qrcode.gzwea.com/cre/' . $name . '/' . $key_str;

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($content)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(5)
            ->roundBlockSizeMode(new RoundBlockSizeModeMargin())
            ->logoPath(__DIR__ . '/logot.png')
            ->logoResizeToWidth(50)
            ->logoResizeToHeight(50)
            ->build();
        $result->saveToFile(__DIR__ . '/qrcodessss.png');
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
