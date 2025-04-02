<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Request Password Reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out your email. A link to reset your password will be sent there.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>