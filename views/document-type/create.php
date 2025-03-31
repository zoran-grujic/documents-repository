<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DocumentType $model */

$this->title = 'Креирај нови тип документа';
$this->params['breadcrumbs'][] = ['label' => 'Типови докумената', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>