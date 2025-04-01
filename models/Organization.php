<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "organizations".
 *
 * @property int $id
 * @property string $name
 * @property string $note
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizations';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'note'], 'required'], // Both fields are required
            [['name'], 'string', 'max' => 125], // Max length for name
            [['note'], 'string', 'max' => 255], // Max length for note
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назив организације',
            'note' => 'Напомена',
        ];
    }

    /**
     * Gets the related UserOrganization models.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserOrganizations()
    {
        return $this->hasMany(UserOrganization::class, ['organization_id' => 'id']);
    }
}
