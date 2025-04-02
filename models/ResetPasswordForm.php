<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class ResetPasswordForm extends Model
{
    public $password;
    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string', 'min' => 6],
        ];
    }

    public function resetPassword()
    {
        $this->_user->password = Yii::$app->security->generatePasswordHash($this->password);
        $this->_user->password_reset_token = null;

        return $this->_user->save(false);
    }
}
