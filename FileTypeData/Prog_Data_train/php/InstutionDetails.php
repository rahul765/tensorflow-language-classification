<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "instution_details".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $mobile
 * @property string $contact_person
 */
class InstutionDetails extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'instution_details';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'email', 'phone', 'mobile', 'contact_person'], 'required'],
            [['address'], 'string'],
            [['name'], 'string', 'max' => 250],
            [['email', 'contact_person'], 'string', 'max' => 110],
            [['phone', 'mobile'], 'string', 'max' => 30],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
            'mobile' => 'Mobile',
            'contact_person' => 'Contact Person',
        ];
    }
}
