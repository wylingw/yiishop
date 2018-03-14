<?php

namespace backend\models;

use yii\base\Model;
use yii\db\ActiveRecord;

class Rbac extends Model
{
    //定义
    public $name;
    public $description;

    //验证规则
    public function rules()
    {
        return [
            [['name', 'description'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名称(路由)',
            'description' => '描述',
        ];
    }

    //添加权限
    public function permission()
    {
        $authManager = \Yii::$app->authManager;
        //创建权限
        $permission = $authManager->createPermission($this->name);
        $permission->description = $this->description;
        //保存到数据库
        return $authManager->add($permission);
    }


}