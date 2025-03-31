<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\UserOrganization;

/** @var yii\web\View $this */
/** @var app\models\User $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Корисници', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Измени', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Обриши', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Да ли сте сигурни да желите да обришете овог корисника?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'email',
            'status',
        ],
    ]) ?>

    <h3>Организације повезане са корисником</h3>

    <?php if (!empty($model->userOrganizations)): ?>
        <ul>
            <?php foreach ($model->userOrganizations as $link): ?>
                <li>
                    <?= Html::encode($link->organization->name) ?>
                    <?= Html::a('Уклони', ['unlink-organization', 'user_id' => $model->id, 'organization_id' => $link->organization_id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Да ли сте сигурни да желите да уклоните ову организацију?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Нема повезаних организација.</p>
    <?php endif; ?>

    <?= $this->render('@app/views/partials/_link_form', [
        'model' => new UserOrganization(),
        'userId' => $model->id,
    ]) ?>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            'date_create',
            'type.name', // Assuming a relation exists for document type
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'delete' => function ($url, $model, $key)
                    {
                        return Html::a('Delete', $url, [
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