<?php
/**
 * Created by PhpStorm.
 * User: Phantom
 * Date: 20.11.2014
 * Time: 23:25
 */
namespace app\models;

use yii\base\Model;

class AddForm extends Model {
    public $image;
    public $code;
    public $password;
    public $url;

    /*
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image', 'code'], 'required', 'on' => 'insert'],
            [['image'], 'required', 'on' => 'extract'],
            ['image', 'file'],
            ['code', 'string'],
        ];
    }
//
//    public function scenarios()
//    {
//        return [
//            'insert' => ['image', 'code'],
//            'extract' => ['image']
//            ];
//    }
    public function attributeLabels()
    {
        return [
            'image' => 'Изображение',
            'code' => 'Сообщение',
            'password' => 'Пароль',
        ];
    }

}