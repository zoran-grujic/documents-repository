<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\UserOrganization;

/** @var yii\web\View $this */
/** @var app\models\Organization $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Организације', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Измени', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Обриши', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Да ли сте сигурни да желите да обришете ову организацију?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'note',
        ],
    ]) ?>

    <h3>Корисници повезани са организацијом</h3>

    <?php if (!empty($model->userOrganizations)): ?>
        <ul>
            <?php foreach ($model->userOrganizations as $link): ?>
                <li>
                    <?= Html::encode($link->user->name) ?>
                    <?= Html::a('Уклони', ['unlink-user', 'organization_id' => $model->id, 'user_id' => $link->user_id], [
                        'class' => 'btn btn-danger btn-sm',
                        'data' => [
                            'confirm' => 'Да ли сте сигурни да желите да уклоните овог корисника из организације?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Нема повезаних корисника.</p>
    <?php endif; ?>

    <?= $this->render('@app/views/partials/_link_form', [
        'model' => new UserOrganization(),
        'organizationId' => $model->id,
    ]) ?>
</div>