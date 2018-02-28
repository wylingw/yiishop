<?php

namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    //设置标签属性
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
        ];
    }

    //设置验证规则
    public function rules()
    {
        return [
            [['name', 'intro', 'logo', 'sort','is_deleted'], 'required'],

        ];
    }
}