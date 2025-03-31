<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_types".
 *
 * @property int $id
 * @property string $name
 *
 * @property Documents[] $documents
 */
class DocumentTypes extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'], // Name is required
            [['name'], 'string', 'max' => 128], // Limit name to 128 characters
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Назив типа документа',
        ];
    }

    /**
     * Gets the related Documents models.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Documents::class, ['type_id' => 'id']);
    }
}
