<?php

namespace backend\controllers;

use backend\models\Rbac;
use backend\models\Role;
use yii\base\Model;
use yii\web\Controller;

class RbacController extends Controller
{
    //rbac所有的操作通过authManager实现
    public function actionTest()
    {
        $authManager = \Yii::$app->authManager;
//        //>>>1.添加权限
        //创建权限
//        $permission = $authManager->createPermission('brand/add');
//        $permission->description = '添加品牌';
//        //保存到数据库
//        $authManager->add($permission);

//        $permission = $authManager->createPermission('brand/index');
//        $permission->description = '品牌列表';
//        //保存到数据库
//        $authManager->add($permission);

        //创建权限
//        $permission2 = $authManager->createPermission('brand/delete');
//        $permission2->description = '删除品牌';
//        //保存到数据库
//        $authManager->add($permission2);
        //>>>2.添加角色 超级给管理员 普通管理员
//        //创建角色
//        $role = $authManager->createRole('超级管理员');
//        //保存到数据库
//        $authManager->add($role);
        //创建角色
//        $role2 = $authManager->createRole('普通管理员');
//        //保存到数据库
//        $authManager->add($role2);
        //>>>3.给角色关联权限  超级管理员 品牌添加,修改 普通管理员 品牌添加
        //获取角色
//        $role = $authManager->getRole('超级管理员');
//        //获取权限
//        $permission = $authManager->getPermission('brand/add');
//        $permission2 = $authManager->getPermission('brand/delete');
//        //给角色赋予权限
//        $authManager->addChild($role,$permission);
//        $authManager->addChild($role,$permission2);
        //获取角色
//        $role = $authManager->getRole('普通管理员');
//        //获取权限
//        $permission = $authManager->getPermission('brand/index');
//        //给角色赋予权限
//        $authManager->addChild($role, $permission);

        //>>>4.给用户添加角色 李白->超级管理员
        //获取角色
//        $role = $authManager->getRole('超级管理员');
//        $role2 = $authManager->getRole('普通管理员');
//        //给角色赋予权限
//        $authManager->assign($role,3);
//        $authManager->assign($role2,1);
        // 测试普通管理员的删除权限
//        $res = \Yii::$app->user->can('brand/add');
//        var_dump($res);

    }

    public function actionIndexPermission()
    {
        //获取所有数据
        $authManager = \Yii::$app->authManager;
        $rbacs = $authManager->getPermissions();
        //分配数据
        return $this->render('index', ['rbacs' => $rbacs]);

    }

    //添加
    public function actionAddPermission()
    {
        $model = new Rbac();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {//验证通过
                if ($model->permission()) {
                    //设置提示信息
                    \Yii::$app->session->setFlash('success', '添加权限成功');
                    //跳转
                    return $this->refresh();
                }
            }
        }

        //调用视图
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEditPermission($name)
    {
        //接收数据
        $request = \Yii::$app->request;
        $authManager = \Yii::$app->authManager;
        //获取name对应的数据
        $permission = $authManager->getPermission($name);
        $model = new Rbac();
        //给新模型赋值
        $model->description = $permission->description;
        $model->name = $permission->name;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {//验证通过
                $permission->description = $model->description;
                $authManager->update($name, $permission);
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改权限成功');
                //跳转
                return $this->redirect(['rbac/permission-index']);

            }
        }

        //调用视图
        return $this->render('edit', ['model' => $model]);
    }

    //删除
    public function actionDeletePermission($name)
    {
        $authManager = \Yii::$app->authManager;
        $permission = $authManager->getPermission($name);
        if ($permission) {
            $authManager->remove($permission);
            echo 1;
        } else {
            echo 0;
        }
    }

    //角色列表
    public function actionRoleIndex()
    {
        //获取所有数据
        $authManager = \Yii::$app->authManager;
        $roles = $authManager->getRoles();
        //调用页面
        return $this->render('roleIndex', ['roles' => $roles]);
    }

    //添加角色
    public function actionRoleAdd()
    {
        //实例化
        $model = new Role();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收数据
            //  var_dump($request->post());die();
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->getRole()) {
                    //设置提示信息
                    \Yii::$app->session->setFlash('success', '添加角色成功');
                    //跳转
                    return $this->refresh();
                }
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用页面
        return $this->render('addRole', ['model' => $model]);
    }

    //修改角色
    public function actionRoleEdit($name)
    {
        //实例化
        $model = new Role();
        //根据name找到所对应数据
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        $model->name = $role->name;
        $model->description = $role->description;
        //获取角色所有的权限
        $permissions = $authManager->getPermissionsByRole($role->name);
        if (is_array($permissions)) {
            $model->permissions = [];
            foreach ($permissions as $permission) {
                $model->permissions[] = $permission->name;
            }
        }
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            if ($model->validate()) {
                //清除该角色的所有权限
                $authManager->removeChildren($role);
                //赋值
                $role->name = $model->name;
                $role->description = $model->description;
                //给角色关联权限
                if (is_array($model->permissions)) {
                    foreach ($model->permissions as $permissionName) {
                        $permission = $authManager->getPermission($permissionName);
                        //给角色赋予权限
                        $authManager->addChild($role, $permission);
                    }
                }
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改角色成功');
                //跳转
                return $this->redirect(['rbac/role-index']);

            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用页面
        return $this->render('addRole', ['model' => $model]);
    }

    //删除
    public function actionRoleDelete($name)
    {
        $authManager = \Yii::$app->authManager;
        $role = $authManager->getRole($name);
        if ($role) {
            $authManager->remove($role);
            echo 1;
        } else {
            echo 0;
        }
    }
}