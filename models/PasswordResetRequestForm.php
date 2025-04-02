<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'email', 'message' => 'There is no user with this email address.'],
        ];
    }

    public function sendEmail()
    {
        $user = User::findOne(['email' => $this->email]);

        if (!$user)
        {
            return false;
        }

        // Generate a password reset token
        $user->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        if (!$user->save())
        {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' Support'])
            ->setTo($this->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();
    }
}
