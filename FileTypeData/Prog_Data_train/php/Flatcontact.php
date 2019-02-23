<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "flats_contacts".
 *
 * @property integer $id
 * @property integer $flat_id
 * @property string $name
 * @property integer $phone
 * @property string $category
 * @property string $email
 * @property string $address
 * @property string $city
 * @property integer $delete
 */
class Flatcontact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'flats_contacts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['flat_id', 'name', 'phone', 'category', 'email', 'address', 'city', 'delete'], 'required'],
            [['flat_id', 'phone', 'delete'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['category'], 'string', 'max' => 15],
            [['email'], 'string', 'max' => 40],
            [['address', 'city'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'flat_id' => 'Flat ID',
            'name' => 'Name',
            'phone' => 'Phone',
            'category' => 'Category',
            'email' => 'Email',
            'address' => 'Address',
            'city' => 'City',
            'delete' => 'Delete',
        ];
    }
}
