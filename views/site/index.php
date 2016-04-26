<?php
/**
 * Created by PhpStorm.
 * User: Phantom
 * Date: 28.11.2014
 * Time: 21:07
 */
use \yii\helpers\Html;
$this->title = 'Стеганография';
?>

<div class="about">
    <h5 align="center">
        <strong> Стеганография </strong> - это наука о сокрытии передачи информации путем сохранения в тайне самого факта передачи информации.
    </h5>

    <p align="center">
        Пример:
    </p>
    <div class="row">
        <div class="col-lg-7">
            <p> Пустое изображение: </p>
            <?= Html::img('1.jpg', ['alt' => 'Пустое изображение'])?>
        </div>
        <div class="col-lg-5">
            <p> Изображение, содержащее сообщение <strong> Hello world! </strong> </p>
        <?= Html::img('2.png', ['alt' => 'Застеганографировано сообщение'])?>
        </div>
    </div>
</div>