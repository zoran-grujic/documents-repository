<?php

/** @var yii\web\View $this */
/** @var app\models\User $user */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/reset-password', 'token' => $user->password_reset_token]);
?>

Hello <?= $user->name ?>,

Follow the link below to reset your password:

<?= $resetLink ?>

If you did not request a password reset, please ignore this email.