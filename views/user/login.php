<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\User; // Import the User model

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <!-- Display error messages if login fails -->
            <?php if ($model->hasErrors()): ?>
                <div class="alert alert-danger">
                    <strong>Login failed:</strong>
                    <ul>
                        <?php foreach ($model->errors as $attribute => $errors): ?>
                            <?php foreach ($errors as $error): ?>
                                <li><?= Html::encode($error) ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <---
                <!-- Display hashed password for debugging -->
                <?php if (Yii::$app->request->isPost): ?>
                    <?php
                    // Fetch the hashed password from the database
                    $user = User::findOne(['email' => $model->email]);
                    $dbPasswordHash = $user ? $user->password : 'User not found';

                    // Generate a hash of the entered password
                    $enteredPasswordHash = Yii::$app->getSecurity()->generatePasswordHash($model->password);
                    ?>
                    <div class="alert alert-info">
                        <strong>Hashed Password from Database:</strong>
                        <pre><?= Html::encode($dbPasswordHash) ?></pre>
                    </div>
                    <div class="alert alert-info">
                        <strong>Hash of Entered Password:</strong>
                        <pre><?= Html::encode($enteredPasswordHash) ?></pre>
                    </div>
                    --->
                <?php endif; ?>

        </div>
    </div>
</div>