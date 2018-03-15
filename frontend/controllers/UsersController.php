<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Users;
use yii\captcha\CaptchaAction;

class UsersController extends \yii\web\Controller
{
//    //用户列表页
//    public function actionIndex()
//    {
//        return $this->render('index');
//    }

    //用户注册
    public function actionRegist()
    {
        //实例化
        $model = new Users();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post(), '');
//            var_dump($request->post());
//            die();
            if ($model->validate()) {
                //处理数据
                $model->created_at = time();
                $model->status = 1;
                $model->password = \Yii::$app->security->generatePasswordHash($model->password);
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改密码成功');
                //跳转
                return $this->redirect(['users/login']);
            } else {
                var_dump($model->getErrors());
                die();
            }

        }


        //调用视图
        return $this->render('regist');
    }

    //验证用户名是否存在
    public function actionValidateUser($username)
    {
        $model = Users::findOne(['username' => $username]);
        if ($model) {
            return 'false';
        } else {
            return 'true';
        }
    }

    //验证邮箱是否存在
    public function actionValidateEmail($email)
    {
        $model = Users::findOne(['email' => $email]);
        if ($model) {
            return 'false';
        } else {
            return 'true';
        }
    }

    //验证电话是否存在
    public function actionValidateTel($tel)
    {
        $model = Users::findOne(['tel' => $tel]);
        if ($model) {
            return 'false';
        } else {
            return 'true';
        }
    }

    //发送短信
    public function actionSms($tel)
    {
        // var_dump($tel);die();
        $code = rand(1000, 9999);
        //开启redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //保存code
        $redis->set('code_' . $tel, $code, 10 * 60);
        $result = \Yii::$app->sms->setTel($tel)->setParams(['code' => $code])->send();
        if ($result) {
            return true;
        }
        return false;
    }

    //验证手机短信验证码
    public function actionValidateSms($tel, $code)
    {
        //开启redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1');
        //去值
        $rel = $redis->get('code_' . $tel);
        if ($code == $rel) {
            return 'true';
        } else {
            return 'false';
        }
    }

    //登录
    public function actionLogin()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new LoginForm();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post(), '');
            if ($model->validate()) {
                //验证用户名和密码
                if ($model->login()) {//登录成功
                    //设置提示信息
                    \Yii::$app->session->setFlash('success', '登录成功');
                    //跳转
                    return $this->redirect(['goods-category/index']);
                }
            } else {
                var_dump($model->getErrors());
                exit;
            }

        }
        //调用页面
        return $this->render('login');
    }

    //退出
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        //设置提示信息
        \Yii::$app->session->setFlash('success', '登录成功');
        //跳转
        return $this->redirect(['users/login']);

    }


//    //验证码
//    public function actions()
//    {
//        return [
//            'captcha' => [
//                'class' => CaptchaAction::className(),
//                'minLength' => 3,
//                'maxLength' => 5,
//            ]
//        ];
//

}
