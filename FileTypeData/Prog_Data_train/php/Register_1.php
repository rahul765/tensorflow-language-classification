<?php

namespace app\models;

use Yii;
use app\models\Tenant;

/**
 * This is the model class for table "landlords".
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
 * @property string $active_date
 */
class Register extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    public $repassword;

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
            [['name', 'last_name', 'phone', 'email','password','repassword'], 'required'],
            [['phone', 'ban'], 'integer'],
            [['signup_date'], 'safe'],
            ['repassword', 'compare', 'compareAttribute' => 'password'],
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
            'password'=>'Hasło',
            'repassword'=>'Powtórz hasło',
            'signup_date' => 'Data rejestracji',
            'ban' => 'Blokada',
        ];
    }
}


