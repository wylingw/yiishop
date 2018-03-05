<?php

namespace backend\models;

use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password_hash;
    public $code;
    public $remember;


    //设置标签名称
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password_hash' => '密码',
            'code' => '验证码',
            'remember' => '请记住我',

        ];
    }

    //设置验证规则
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required'],
            //验证码
            ['code', 'captcha', 'captchaAction' => 'admin/captcha',],
            ['remember', 'safe']

        ];
    }

    //登录方法
    public function login()
    {
        $admin = Admin::findOne(['username' => $this->username]);
        if ($admin) { //用户名存在
            //验证密码
            if (\Yii::$app->security->validatePassword($this->password_hash, $admin->password_hash)) {
                //密码正确
                //保存到cookie
                //保存用户信息到session
                $duration = $this->remember ? 7*24*3600 : 0;
                return \Yii::$app->user->login($admin, $duration);
            } else {
                //密码错误，设置提示信息
                $this->addError('password', '账号或密码错误');
            }

        } else {
            //账户名不存在，设置提示信息
            $this->addError('username', '账号或密码错误');
        }
        return false;
    }
}