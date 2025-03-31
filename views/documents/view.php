<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Documents $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Документи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Измени', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Обриши', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Да ли сте сигурни да желите да обришете овај документ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'organization.name', // Display organization name
            'user.name', // Display user name
            'date_create',
            'type.name', // Display document type name
            'description:ntext',
            'url:url',
        ],
    ]) ?>

</div>