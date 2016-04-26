<?php
/**
 * Created by PhpStorm.
 * User: Phantom
 * Date: 24.11.2014
 * Time: 0:24
 */

namespace app\models;

class Image
{
    public $width;
    public $height;
    public $pixels;
    public $image;
    public $type;
    public $path;

    public function initialize($img)
    {
        $file = getimagesize($img);
        $this->width = $file[0];
        $this->height = $file[1];
        $this->pixels = $this->width * $this->height;
        $this->type = $file[2];

        switch ($this->type) {
            case IMAGETYPE_JPEG:
                $this->image = imagecreatefromjpeg($img);
                break;
            case IMAGETYPE_PNG;
                $this->image = imagecreatefrompng($img);
                break;
            default:
                \Yii::$app->getSession()->setFlash('not-supported', 'Данный формат файла не поддерживается');
                return false;
        }
        return true;
    }

    public function save() {

        $path = 'images/'.time().'.png';

        imagepng($this->image, $path, 0);
        return $path;
    }
}