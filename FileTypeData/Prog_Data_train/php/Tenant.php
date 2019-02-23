<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tenants".
 *
 * @property integer $id
 * @property string $name
 * @property string $last_name
 * @property integer $phone
 * @property string $email
 * @property string $signup_date
 * @property string $password
 * @property integer $activate
 * @property integer $ban
 * @property string $active_link
 * @property integer $delete
 */
class Tenant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $repassword;
    public $oldpassword;

    public static function tableName()
    {
        return 'tenants';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'last_name', 'phone', 'email'], 'required'],
            [['phone', 'activate', 'ban'], 'integer'],
            [['signup_date'], 'safe'],
            [['name'], 'string', 'max' => 15],
            [['last_name'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 50],

            ['email', 'filter', 'filter' => 'trim'],
	        ['email', 'email'],
	        ['email', 'unique', 'targetClass' => 'app\models\Tenant', 'message' => 'Ten adres email już istnieje'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Imię',
            'last_name' => 'Nazwisko',
            'phone' => 'Telefon',
            'email' => 'Email',
            'signup_date' => 'Signup Date',
            'password' => 'Hasło',
            'repassword' => 'Powtórz hasło',
            'activate' => 'Activate',
            'ban' => 'Ban',
            'active_link' => 'Active Link',
            'delete' => 'Delete',
        ];
    }
}
