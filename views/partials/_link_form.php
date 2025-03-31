<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User;
use app\models\Organization;

/** @var yii\web\View $this */
/** @var app\models\UserOrganization $model */
/** @var int|null $organizationId */
/** @var int|null $userId */

?>

<div class="link-form">
    <h3>Повежи корисника и организацију</h3>

    <?php
    // Dynamically set the form action based on the context
    $action = isset($organizationId)
        ? ['organization/link-organization']
        : ['user/link-organization'];
    ?>

    <?php $form = ActiveForm::begin([
        'action' => $action, // Use the dynamically determined action
        'method' => 'post',
    ]); ?>

    <?php if (!isset($organizationId)): ?>
        <?= $form->field($model, 'organization_id')->dropDownList(
            Organization::find()->select(['name', 'id'])->indexBy('id')->column(),
            ['prompt' => 'Изаберите организацију']
        ) ?>
    <?php else: ?>
        <?= $form->field($model, 'organization_id')->hiddenInput(['value' => $organizationId])->label(false) ?>
    <?php endif; ?>

    <?php if (!isset($userId)): ?>
        <?= $form->field($model, 'user_id')->dropDownList(
            User::find()->select(['name', 'id'])->indexBy('id')->column(),
            ['prompt' => 'Изаберите корисника']
        ) ?>
    <?php else: ?>
        <?= $form->field($model, 'user_id')->hiddenInput(['value' => $userId])->label(false) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Повежи', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>