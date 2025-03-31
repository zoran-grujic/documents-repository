<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DocumentTypesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Типови докумената';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-type-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Креирај нови тип', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}', // Show view, update, and delete buttons
                'buttons' => [
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('Измени', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'delete' => function ($url, $model, $key)
                    {
                        return Html::a('Обриши', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Да ли сте сигурни да желите да обришете овај тип документа?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>