<?php

namespace backend\models;

use yii\base\Model;

class Password extends Model
{
    public $old_password;
    public $new_password;
    public $re_password;

    public function rules()
    {
        return [
            [['old_password', 'new_password', 're_password'], 'required'],
           ['old_password', 'validatePassword'],
           ['re_password', 'validateRePassword'],
           // ['re_password','compare','compareAttribute'=>'new_password','message'=>'两次密码输入不一致']

        ];
    }

    //自定义验证旧密码
    public function validatePassword()
    {
        $password_hash = \Yii::$app->user->identity->password_hash;
        $res = \Yii::$app->security->validatePassword($this->old_password, $password_hash);
        if ($res==false) {
           $this->addError('old_password', '旧密码输入错误');
        }
    }

    //自定义验证新密码是否等于确认密码
    public function validateRePassword()
    {
        $res=$this->new_password == $this->re_password;
        if ( $res==false){
            $this->addError('re_password', '两次密码输入不一致');
        }

    }

    public function attributeLabels()
    {
        return [
            'old_password' => '旧密码',
            'new_password' => '新密码',
            're_password' => '确认新密码',
        ];
    }
}