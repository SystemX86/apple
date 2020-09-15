<?php

use kartik\slider\Slider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Apple */

$this->title = 'Eat '.$model->color.' apple';
?>

<div class="apple-update">
    <b><?= Html::encode($this->title) ?></b>
    <br>
    <div class="apple-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'ate')->widget(
            Slider::className(),[
                'name' => 'slider_eat',
                'sliderColor' => Slider::TYPE_PRIMARY,
                'handleColor' => Slider::TYPE_PRIMARY,
                'pluginOptions' => [
                    'orientation' => 'horizontal',
                    'handle' => 'round',
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                ],
            ]
        )->label('') ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>