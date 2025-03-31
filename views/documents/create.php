<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Documents $model */

$this->title = 'Додај документ';
$this->params['breadcrumbs'][] = ['label' => 'Документи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documents-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>