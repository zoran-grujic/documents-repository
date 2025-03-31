<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var app\models\DocumentsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Додај документ', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'organization.name', // Display organization name
            'user.name', // Display user name
            'date_create',
            'type.name', // Display document type name

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('Погледај', $url, ['class' => 'btn btn-info btn-sm']);
                    },
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('Измени', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'delete' => function ($url, $model, $key)
                    {
                        return Html::a('Обриши', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Да ли сте сигурни да желите да обришете овај документ?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>