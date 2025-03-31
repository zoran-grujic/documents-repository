<?php

use yii\helpers\Html;

$this->title = 'Направи новог корисника';
$this->params['breadcrumbs'][] = ['label' => 'Корисници', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>