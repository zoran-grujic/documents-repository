<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DocumentTypes $model */

$this->title = 'Измени тип документа: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типови докумената', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Измени';
?>
<div class="document-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>