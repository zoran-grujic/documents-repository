<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Organization[] $organizations */

$this->title = 'Organizations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="organization-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin'): ?>
        <p>
            <?= Html::a('Create Organization', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Назив</th>
                <th>Белешка</th>
                <?php if (Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($organizations as $organization): ?>
                <tr>
                    <td><?= Html::encode($organization->id) ?></td>
                    <td><?= Html::encode($organization->name) ?></td>
                    <td><?= Html::encode($organization->note) ?></td>
                    <?php if (Yii::$app->user->identity && Yii::$app->user->identity->status === 'admin'): ?>
                        <td>
                            <?= Html::a('View', ['view', 'id' => $organization->id], ['class' => 'btn btn-info btn-sm']) ?>
                            <?= Html::a('Update', ['update', 'id' => $organization->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('Delete', ['delete', 'id' => $organization->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this organization?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>