<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php else: ?>
        <?= $form->field($model, 'password')->passwordInput([
            'maxlength' => true,
            'value' => '', // Ensure the password field is blank
            'placeholder' => 'Остави празно да би задржао стару лозинку',
        ])->label('Нова лозинка') ?>
    <?php endif; ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList(['admin' => 'Admin', 'user' => 'User']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сними', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>