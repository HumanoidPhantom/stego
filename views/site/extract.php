<?php
/**
 * @var yii\web\View $this
 */
//use yii\web\View;
use yii\helpers\Html;
?>

<?php
$this->title = 'Извлечь текст';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
if (Yii::$app->getSession()->hasFlash('not-supported')) {
    echo Yii::$app->getSession()->getFlash('not-supported');
    Yii::$app->getSession()->setFlash('not-supported', null);
}

if (Yii::$app->getSession()->hasFlash('no-message')) {
    echo Yii::$app->getSession()->getFlash('no-message');
    Yii::$app->getSession()->setFlash('no-message', null);
}
?>

<div class="site-index">
    <?php
    $form = \yii\widgets\ActiveForm::begin([
        'options' => [
            'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data',
            'novalidate' => 'novalidate'
        ]
    ])
    ?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <div class="form-group">
        <?= \yii\helpers\Html::submitButton('Извлечь сообщение', ['class' => 'btn btn-primary', 'name' => 'hide-button'])?>
    </div>
    <?php \yii\widgets\ActiveForm::end()?>
</div>

<?php if($message) {
    ?>
    <h4>
        <?= 'Скрытое сообщение: '; ?>
    </h4>
    <p>
        <?= Html::encode($message); ?>
    </p>
<?php
}
?>
