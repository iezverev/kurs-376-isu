<?php
namespace app\models;
use yii\base\Model;
 
class SignupForm extends Model{

    public $username;
    public $password;
    public $fio;
       

    public function rules() {
        return [
            [['username', 'password', 'fio'], 'required', 'message' => 'Обязательное поле'],
            ['username', 'unique', 'targetClass' => User::className(),  'message' => 'Этот логин уже занят'],
        ];
    }
}