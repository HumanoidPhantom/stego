<?php

namespace app\controllers;


use app\models\Stego;
use Yii;
use yii\web\Controller;
use app\models\AddForm;


class SiteController extends Controller
{
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionInsert()
    {
        // Очистить папку images от старых изображений
        $files = scandir('images');
        unset ($files[0], $files[1]);
        foreach ($files as $img) {
            unlink('images/'.$img);
        }

        $model = new AddForm();
        $model->scenario = 'insert';
        $url = 0;
        $stego = new Stego();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = $_FILES['AddForm']['tmp_name']['image'];

            
//            if ($model->validate()) {
//                if ($stego->steganography($model)) {
//                    $url = $stego->save();
//                }
//            }
        }
        return $this->render('insert', [
            'model' => $model,
            'url' => $url
        ]);
    }

    public function actionExtract()
    {
        $model = new AddForm();
        $model->scenario = 'extract';
        $message = 0;
        $stego = new Stego();
        if ($model->load(Yii::$app->request->post())) {
            $model->image = $_FILES['AddForm']['tmp_name']['image'];
            if ($model->validate()) {
                $message = $stego->de_stego($model);
            }
        }
        return $this->render('extract', [
            'model' => $model,
            'message' => $message
        ]);
    }
}
