<?php

namespace backend\controllers;

use backend\filters\RbacFilters;
use backend\models\Admin;
use backend\models\Password;
use frontend\models\LoginForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\web\HttpException;

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
        //使用场景
        $model->scenario = Admin::SCENARIO_ADD;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());

            if ($model->validate()) {
//                //处理数据
//                $model->created_at = time();
//                $model->auth_key = \Yii::$app->security->generateRandomString();
//                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                //保存
                $model->save();
                //把选中的角色存入数据表
                $authManager = \Yii::$app->authManager;
                if (is_array($model->roles)) {
                    foreach ($model->roles as $roleName) {
                        $roles = $authManager->getRole($roleName);
                        //给用户添加角色
                        $id = $model->id;
                        //var_dump($this->id);die();
                        $authManager->assign($roles, $id);
                    }
                }
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
        //获取该用户所有角色回显
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRolesByUser($id);
        if (is_array($roles)) {
            $model->roles = [];
            foreach ($roles as $role) {
                $model->roles[] = $role->name;
            }
        }
        if (!$model) {
            throw new HttpException('404', '该用户不存在');
        }
        //指定使用场景
        $model->scenario = Admin::SCENARIO_EDIT;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());

            if ($model->validate()) {
                //清除原来的所有角色
                $authManager->revokeAll($id);
                // var_dump($model->roles);die();
                //给用户关联角色
                if (is_array($model->roles)) {
                    foreach ($model->roles as $rolesNmae) {
                        $roles = $authManager->getRole($rolesNmae);
                        //给用户赋予角色
                        $authManager->assign($roles, $id);
                    }
                }
                //处理数据
                $model->updated_at = time();
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //保存
                $model->save();
                //信息提示
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['admin/index']);
            }
        }

        //调用视图,分配数据
        return $this->render('add', ['model' => $model]);
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
            if ($user->validate()) {
                //重新把新密码赋值,存入数据库
                $user->password_hash = \Yii::$app->security->generatePasswordHash($admin->new_password);
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


        //调用视图
        return $this->render('change', ['admin' => $admin]);
    }

//过滤器
    public function behaviors()
    {
        return [
            'rbac' => [
                'class' => RbacFilters::class,
                //默认情况对所有操作生效
                //排除不需要授权的操作
                'except' => ['login', 'logout', 'change', 'captcha', 'index']
            ]
        ];
    }
}
