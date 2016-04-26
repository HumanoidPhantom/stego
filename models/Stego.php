<?php
/**
 * Created by PhpStorm.
 * User: Phantom
 * Date: 27.11.2014
 * Time: 12:43
 */
namespace app\models;

use Yii;

class Stego {
    public $image;
    public $code;
    const BITS_FOR_LENGTH = 24;

    public function steganography($model){
        $this->image = new Image();
        $this->code = new Code();

        $this->image->initialize($model->image);
        if (!Yii::$app->getSession()->hasFlash('not-supported')) {

//             Приводим текст в двоичную форму (+ шифрование в будущем)
            $bin = $this->code->toBinary($model->code);

//             Проверка, достаточный ли размер изображения
            if (ceil(strlen($bin) / 3 + self::BITS_FOR_LENGTH/3) > $this->image->pixels){
                Yii::$app->getSession()->setFlash('small-image', 'Изображение слишком маленькое');
                return false;
            }
            else {
//             Встраивание текста в изображение
                $this->doStego($bin, $this->image);
            }
        }
        return true;
    }
    
    public function doStego($bin, Image $image)
    {
        $length = strlen($bin);
        $string = sprintf('%024b', strlen($bin));
        $x = 0;
        $y = 0;
        for ($i = 0; $i < self::BITS_FOR_LENGTH; $i+=3) {
            $rgbXY = imagecolorat($image->image, $x, $y);
            $rgb['r'] = bindec(substr(decbin(($rgbXY >> 16) & 0xFF), 0, -1) . $string[$i]);
            $rgb['g'] = bindec(substr(decbin(($rgbXY >> 8) & 0xFF), 0, -1) . $string[$i+1]);
            $rgb['b'] = bindec(substr(decbin($rgbXY & 0xFF), 0, -1) . $string[$i+2]);
            $color = imagecolorallocate($image->image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagesetpixel($image->image, $x, $y, $color);
            imagecolordeallocate($image->image, $color);

            if ($x == $image->width - 1) {
                $x = 0;
                $y++;
            } else
                $x++;
        }

        for ($i = 0; $i < $length; $i++) {
            $rgbXY = imagecolorat($image->image, $x, $y);
            $rgb['r'] = bindec(substr(decbin(($rgbXY >> 16) & 0xFF), 0, -1) . $bin[$i]);
            $i++;
            if (!($i < $length)){
                $rgb['g'] = ($rgbXY >> 8) & 0xFF;
                $rgb['b'] = $rgbXY & 0xFF;
                $color = imagecolorallocate($image->image, $rgb['r'], $rgb['g'], $rgb['b']);
                imagesetpixel($image->image, $x, $y, $color);
                imagecolordeallocate($image->image, $color);
                break;
            }

            $rgb['g'] = bindec(substr(decbin(($rgbXY >> 8) & 0xFF), 0, -1) . $bin[$i]);
            $i++;
            if (!($i < $length)) {
                $rgb['b'] = $rgbXY & 0xFF;
                $color = imagecolorallocate($image->image, $rgb['r'], $rgb['g'], $rgb['b']);
                imagesetpixel($image->image, $x, $y, $color);
                imagecolordeallocate($image->image, $color);
                break;
            }

            $rgb['b'] = bindec(substr(decbin($rgbXY & 0xFF), 0, -1) . $bin[$i]);

            $color = imagecolorallocate($image->image, $rgb['r'], $rgb['g'], $rgb['b']);
            imagesetpixel($image->image, $x, $y, $color);
            imagecolordeallocate($image->image, $color);

            if ($x == $image->width - 1) {
                $x = 0;
                $y++;
            } else
                $x++;
        }
    }

    public function save()
    {
        return $this->image->save();
    }

    public function de_stego($model)
    {
        $this->image = new Image();
        $this->image->initialize($model->image);
        if (!Yii::$app->getSession()->hasFlash('not-supported')) {
            $x = 0;
            $y = 0;
            $length = '';

            for ($i = 0; $i < self::BITS_FOR_LENGTH; $i+=3) {
                $rgbXY = imagecolorat($this->image->image, $x, $y);

                $length .= substr(decbin(($rgbXY >> 16) & 0xFF), -1, 1);
                $length .= substr(decbin(($rgbXY >> 8) & 0xFF), -1, 1);
                $length .= substr(decbin($rgbXY & 0xFF), -1, 1);

                if ($x == $this->image->width - 1) {
                    $x = 0;
                    $y++;
                } else
                    $x++;
            }
            $length = bindec($length);
            $code = '';
            $find = true;
            for ($i = 0; $i < $length; $i++) {
                $rgbXY = imagecolorat($this->image->image, $x, $y);

                $code .= substr(decbin(($rgbXY >> 16) & 0xFF), -1, 1);
                $i++;
                if ($length > $this->image->pixels) {
                    Yii::$app->session->setFlash('no-message', 'В данном изображении не скрыто сообщение, либо изображение повреждено');
                    $find = false;
                    break;
                }

                if ($i < $length) {
                    $code .= substr(decbin(($rgbXY >> 8) & 0xFF), -1, 1);
                }
                else
                    break;
                $i++;

                if ($i < $length) {
                    $code .= substr(decbin($rgbXY & 0xFF), -1, 1);
                }
                else
                    break;

                if ($x == $this->image->width - 1) {
                    $x = 0;
                    $y++;
                } else
                    $x++;
            }

            if ($find) {
                $message = '';
                $bin = '';
                for ($i = 0; $i < $length; $i++) {
                    if (($i % 8 != 0)) {
                        $bin .= $code[$i];
                    } elseif ($i != 0) {
                        $message .= chr(bindec($bin));
                        $bin = '';
                    }
                }
                $message .= chr(bindec($bin));
                $message = base64_decode($message);
                return $message;
            }
        }
        return false;
    }
}