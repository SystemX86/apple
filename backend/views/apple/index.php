<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use lo\widgets\modal\ModalAjax;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Apple', ['create'], ['class' => 'btn pjax-btn btn-success']) ?>
        <?= Html::a('Generate Apples', ['generate'], ['class' => 'btn pjax-btn btn-success']) ?>
    </p>

    <?= ModalAjax::widget([
        'id' => 'updateEat',
        'selector' => 'a.pjax-btn', // all buttons in grid view with href attribute

        'options' => ['class' => 'header-primary'],
        'events'=>[
            ModalAjax::EVENT_MODAL_SHOW => new \yii\web\JsExpression("
            function(event, data, status, xhr, selector) {
                selector.addClass('warning');
            }
       "),
            ModalAjax::EVENT_MODAL_SUBMIT => new \yii\web\JsExpression("
            function(event, data, status, xhr, selector) {
                if(status){
                    $(this).modal('toggle');
                    $.pjax.reload({container : '#grid-company-pjax', timeout : 5000 });
                }
            }
        "),
            ModalAjax::EVENT_MODAL_SHOW_COMPLETE => new \yii\web\JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        "),
            ModalAjax::EVENT_MODAL_SUBMIT_COMPLETE => new \yii\web\JsExpression("
            function(event, xhr, textStatus) {
                if (xhr.status == 403) {
                    $(this).modal('toggle');
                    alert('You do not have permission to execute this action');
                }
            }
        ")
        ]
    ]);
    ?>

    <? Pjax::begin(['id' => 'grid-company-pjax']); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'color',
            [
                'attribute' => 'status',
                'value' => function($data) {
                    $labels = [1 => 'Tree', 2 => 'Ground', 3 => 'Rotten'];
                    return $labels[$data->status];
                }
            ],
            [
                'attribute' => 'created_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
            ],
            [
                'attribute' => 'fall_at',
                'format' =>  ['date', 'dd.MM.Y HH:mm:ss'],
            ],
            'ate',

            [
                'header' => 'Actions',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{fall} {eat} {delete}',
                'buttons' => [
                    'fall' => function($model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-tree-deciduous">',
                            Url::to($model),
                            ['title' => "Fall", 'class' => "pjax-btn"]
                        );
                    },
                    'eat' => function($model) {
                        return Html::a(
                            '<span class="glyphicon glyphicon-cutlery">',
                            Url::to($model),
                            ['title' => "Eat apple ", 'class' => "pjax-btn", 'data-scenario' => "eat"]
                        );
                    },
                ]
            ],
        ],
    ]); ?>

    <?php \yii\widgets\Pjax::end(); ?>

</div>