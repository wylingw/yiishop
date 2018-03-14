<?php

namespace backend\models;

use yii\base\Model;

class Role extends Model
{
    public $name;
    public $description;
    public $permissions;
//    //定义场景
//    const SCENARIO_ADD = 'add';
//    const SCENARIO_EDIT = 'edit';

    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'description' => '描述',
            'permissions' => '权限',
        ];
    }

    public function rules()
    {
        return [
            [['name', 'description',], 'required'],
            ['permissions','safe']
        ];
    }

    //处理权限数据
    public static function getPermission()
    {
        $authManager = \Yii::$app->authManager;
        $rbacs = $authManager->getPermissions();
        $tmp = [];
        foreach ($rbacs as $rbac) {
            $tmp[$rbac->name] = $rbac->name;
        }
        return $tmp;
    }

    //添加角色
    public function getRole()
    {
        $authManager = \Yii::$app->authManager;
        //创建角色
        $role = $authManager->createRole($this->name);
        $role->description = $this->description;
        //保存到数据库
        $authManager->add($role);
        foreach ($this->permissions as $permissionName) {
            $permission = $authManager->getPermission($permissionName);
            //给角色赋予权限
            $authManager->addChild($role, $permission);
        }
        return true;

    }
    //修改角色
    public function update($name){
        $authManager = \Yii::$app->authManager;
        $role=$authManager->getRole($name);

    }
}
