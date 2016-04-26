<?php
/**
 * @var yii\web\View $this
 */
//use yii\web\View;
use yii\helpers\Html;
?>

<?php
$this->title = 'Спрятать сообщение';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->getSession()->hasFlash('small-image')) {
    echo Yii::$app->getSession()->getFlash('small-image');
    Yii::$app->getSession()->setFlash('small-image', null);
}
if (Yii::$app->getSession()->hasFlash('not-supported')) {
    echo Yii::$app->getSession()->getFlash('not-supported');
    Yii::$app->getSession()->setFlash('not-supported', null);
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
    <span class="help-image">Поддерживаемые форматы: png, jpeg</span>

    <div class="code-insert">
        <?= $form->field($model, 'code')->textInput() ?>
    </div>

    <div class="form-group">
        <?= \yii\helpers\Html::submitButton('Спрятать данные', ['class' => 'btn btn-primary', 'name' => 'hide-button'])?>
    </div>
    <?php \yii\widgets\ActiveForm::end()?>


</div>
<?php
if ($url) {
    echo Html::a('Сохранить', $url, ['download' => 'image.png']);
    echo Html::img($url);
}?>
