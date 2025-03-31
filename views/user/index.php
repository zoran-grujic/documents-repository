<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\User;

$this->title = 'Корисници';
$this->params['breadcrumbs'][] = $this->title;

// Check if the user is an admin
if (!Yii::$app->user->identity || Yii::$app->user->identity->status !== 'admin')
{
    throw new \yii\web\ForbiddenHttpException('You are not allowed to view this page.');
}

// Create a data provider for the GridView
$dataProvider = new ActiveDataProvider([
    'query' => User::find(),
    'pagination' => [
        'pageSize' => 10, // Number of users per page
    ],
]);
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Додај корисника', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], // Adds a serial number column

            'id',
            'name',
            'email', // Assuming the username field has been renamed to email
            'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}', // Show view, update, and delete buttons
                'buttons' => [
                    'view' => function ($url, $model, $key)
                    {
                        return Html::a('View', $url, ['class' => 'btn btn-info btn-sm']);
                    },
                    'update' => function ($url, $model, $key)
                    {
                        return Html::a('Update', $url, ['class' => 'btn btn-primary btn-sm']);
                    },
                    'delete' => function ($url, $model, $key)
                    {
                        return Html::a('Delete', $url, [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this user?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
                'visibleButtons' => [
                    'update' => function ($model)
                    {
                        return Yii::$app->user->identity->status === 'admin'; // Only admin can update
                    },
                    'delete' => function ($model)
                    {
                        return Yii::$app->user->identity->status === 'admin'; // Only admin can delete
                    },
                ],
            ],
        ],
    ]); ?>
</div>