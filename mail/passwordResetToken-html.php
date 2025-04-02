<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>

<p>Hello <?= Html::encode($user->name) ?>,</p>

<p>Follow the link below to reset your password:</p>

<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

<p>If you did not request a password reset, please ignore this email.</p>