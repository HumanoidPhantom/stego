<?php
/**
 * Created by PhpStorm.
 * User: Phantom
 * Date: 27.11.2014
 * Time: 12:35
 */
namespace app\models;

class Code{
    public function toBinary($code)
    {
        $code = base64_encode($code);
        $bin = '';
        for ($i = 0; $i < strlen($code); $i++) {
            $bin .= sprintf('%08b', ord($code[$i]));
        }
        return $bin;
    }
}