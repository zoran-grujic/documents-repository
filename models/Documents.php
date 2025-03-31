<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "documents".
 *
 * @property int $id
 * @property string $title
 * @property int $organization_id
 * @property int $user_id
 * @property string $date_insert
 * @property string $date_create
 * @property string $url
 * @property string $description
 * @property int $type_id
 * @property UploadedFile $file Virtual property for file upload
 *
 * @property Organization $organization
 * @property User $user
 * @property DocumentType $type
 */
class Documents extends ActiveRecord
{
    /**
     * @var UploadedFile Virtual property for file upload
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'organization_id', 'user_id', 'type_id', 'url'], 'required'], // Required fields
            [['organization_id', 'user_id', 'type_id'], 'integer'], // Ensure organization_id, user_id, and type_id are integers
            [['date_insert', 'date_create'], 'safe'], // Allow date and datetime fields
            [['title', 'url'], 'string', 'max' => 256], // Limit title and URL to 256 characters
            [['description'], 'string'], // Allow description to be a text field
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['organization_id' => 'id']], // Ensure organization_id exists
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']], // Ensure user_id exists
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentTypes::class, 'targetAttribute' => ['type_id' => 'id']], // Ensure type_id exists
            [['file'], 'file', 'extensions' => 'png, jpg, pdf, docx, doc, xlsx, xls', 'maxSize' => 1024 * 1024 * 5], // File validation rules
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наслов',
            'organization_id' => 'Организација',
            'user_id' => 'Корисник',
            'date_insert' => 'Датум уноса',
            'date_create' => 'Датум креирања',
            'url' => 'URL',
            'description' => 'Опис',
            'type_id' => 'Тип документа',
            'file' => 'Фајл',
        ];
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
     * Gets the related DocumentType model.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(DocumentTypes::class, ['id' => 'type_id']);
    }

    /**
     * Sets the default value for date_create if it is not provided.
     *
     * @param bool $insert Whether this is a new record being inserted.
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if ($this->isNewRecord && empty($this->date_create))
            {
                $this->date_create = date('Y-m-d'); // Set to the current date
            }
            return true;
        }
        return false;
    }
}
