<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_organization".
 *
 * @property int $id
 * @property int $user_id
 * @property int $organization_id
 *
 * @property User $user
 * @property Organization $organization
 */
class UserOrganization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'organization_id'], 'required'], // Both fields are required
            [['user_id', 'organization_id'], 'integer'], // Both fields must be integers
            [['user_id', 'organization_id'], 'unique', 'targetAttribute' => ['user_id', 'organization_id'], 'message' => 'This user is already linked to this organization.'], // Ensure unique combination
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']], // Ensure user_id exists in the users table
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['organization_id' => 'id']], // Ensure organization_id exists in the organizations table
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'organization_id' => 'Organization ID',
        ];
    }

    /**
     * Gets the related User model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets the related Organization model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::class, ['id' => 'organization_id']);
    }
}
