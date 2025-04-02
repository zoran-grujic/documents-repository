<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Finds user by email
     *
     * @param string $email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Checks if the user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->status === 'admin';
    }

    /**
     * Checks if the current user has admin privileges
     *
     * @return bool
     */
    public static function isCurrentUserAdmin()
    {
        $currentUser = \Yii::$app->user->identity;
        return $currentUser && $currentUser->status === 'admin';
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            // Hash the password only if it is being set or updated
            if ($this->isAttributeChanged('password') && !empty($this->password))
            {
                $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            }
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'status'], 'required'], // These fields are always required
            ['email', 'email'], // Ensure email is a valid email address
            ['email', 'unique'], // Ensure email is unique
            ['status', 'in', 'range' => ['admin', 'user']], // Validate status
            ['password', 'string', 'min' => 6], // Ensure password has a minimum length
            ['password', 'required', 'on' => 'create'], // Password is required only when creating a new user
            [['password'], 'required', 'on' => ['create']], // Ensure password is required on creation
            [['password'], 'string', 'min' => 6], // Ensure password has a minimum length
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Име',
            'email' => 'Електронска адреса',
            'password' => 'Лозинка',
            'auth_key' => 'Authentication Key',
            'access_token' => 'Access Token',
            'status' => 'Статус',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        // Define the attributes for each scenario
        $scenarios['create'] = ['name', 'email', 'password', 'status']; // All fields required for creating a user
        $scenarios['update'] = ['name', 'email', 'password', 'status']; // Password is optional for updates

        return $scenarios;
    }

    /**
     * Gets the related UserOrganization models.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserOrganizations()
    {
        return $this->hasMany(UserOrganization::class, ['user_id' => 'id']);
    }
}
