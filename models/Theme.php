<?php

namespace app\models;
use yii\db\ActiveRecord;

class Theme extends ActiveRecord
{

	public static function tableName()
	{
		return 'themes';
	}

	public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }

}	