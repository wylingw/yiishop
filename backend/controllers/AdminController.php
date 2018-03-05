<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Password;
use frontend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;

class AdminController extends \yii\web\Controller
{
    //列表页
    public function actionIndex()
    {
        $id = \Yii::$app->user->id;
        $user = Admin::findOne(['id' => $id]);
        if ($user) {
            $user->last_login_ip = $_SERVER["REMOTE_ADDR"];
            $user->last_login_time = time();
            if ($user->validate()) {
                $user->save();
            }
        }


        //获取所有数据
        $admins = Admin::find()->all();
        //调用视图
        return $this->render('index', ['admins' => $admins]);
    }

    //添加
    public function actionAdd()
    {
        //实例化\
        $request = \Yii::$app->request;
        $model = new Admin();
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {
                //处理数据
                $model->created_at = time();
                $model->auth_key = \Yii::$app->security->generateRandomString();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }

        //调用视图,分配数据
        return $this->render('add', ['model' => $model]);
    }

//修改
    public function actionEdit($id)
    {
        //实例化\
        $request = \Yii::$app->request;
        $model = Admin::findOne(['id' => $id]);
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {
                //处理数据
                $model->updated_at = time();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }

        //调用视图,分配数据
        return $this->render('edit', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = Admin::findOne(['id' => $id]);
        $model->delete();
        //跳转
        return $this->redirect(['admin/index']);

    }

    //登录
    public function actionLogin()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new \backend\models\LoginForm();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            if ($model->validate()) {
                //验证用户名和密码
                if ($model->login()) {//登录成功

                    //设置提示信息
                    \Yii::$app->session->setFlash('success', '登录成功');
                    //跳转
                    return $this->redirect(['admin/index']);
                }
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }

        //调用页面
        return $this->render('login', ['model' => $model]);
    }

    //退出
    public function actionLogout()
    {
        \Yii::$app->user->logout();
        //设置提示信息
        \Yii::$app->session->setFlash('success', '退出成功');
        //跳转
        return $this->redirect(['admin/login']);
    }
    //配置过滤器
//    public function behaviors()
//    {
//        return [
//            'acf'=>[
//                'class'=>AccessControl::className(),
//                'only'=>['info','about','money'],
//                'rules'=>[
//                    [//允许登录用户访问info
//                        'allow'=>true,//是否允许
//                        'actions'=>['index','add','edit','delete'],//针对哪些操作
//                        'roles'=>['@'],//?未认证 @已认证
//                    ],
//                    [
//                        'allow'=>true,//是否允许
//                        'actions'=>['about'],//针对哪些操作
//                        'roles'=>['?'],//?未认证 @已认证
//                    ],
//                    [
//                        'allow'=>true,
//                        'actions'=>['money'],
//                        'matchCallback'=>function(){
//                            //return true;//允许访问
//                            //return false;//允许访问
//                            //只有admin用户可以访问
//                            return \Yii::$app->user->id && \Yii::$app->user->identity->username == 'admin';
//                        }
//                    ]
//                    //其它均禁止
//                ]
//            ]
//        ];
//    }
    //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::className(),
                'minLength' => 3,
                'maxLength' => 5,
            ]
        ];
    }

    //修改密码
    public function actionChange()
    {
        $admin = new Password();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收数据
            $admin->load($request->post());
            // var_dump($admin->old_password);die();
            //根据id找到当前用户的信息
            $id = \Yii::$app->user->id;
            $user = Admin::findOne(['id' => $id]);
            //$admin->old_password = \Yii::$app->security->generatePasswordHash($admin->old_password);
//            var_dump(\Yii::$app->security->validatePassword($admin->old_password, $user->password_hash));
//            die();
            if (\Yii::$app->security->validatePassword($admin->old_password, $user->password_hash)) {
                //输入旧密码等于数据库中的密码
                if ($admin->re_password !== $admin->new_password) {
                    echo '两次输入的密码不一致,请重新输入';
                } else {
                    //重新把新密码赋值,存入数据库
                    $admin->new_password = \Yii::$app->security->generatePasswordHash($admin->new_password);
                    $user->password_hash = $admin->new_password;
//                    var_dump($user->password_hash);
//                    die();
                    if ($user->validate()) {
                        $user->save();
                        //设置提示信息
                        \Yii::$app->session->setFlash('success', '修改密码成功');
                        //跳转
                        return $this->redirect(['admin/index']);
                    } else {
                        var_dump($user->getErrors());
                        die();
                    }

                }

            } else {
                echo '密码输入不正确';
            }
        }


        //调用视图
        return $this->render('change', ['admin' => $admin]);
    }

}
