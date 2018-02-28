<?php

namespace backend\models;

use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{
    //设置验证规则
    public function rules()
    {
        return [
            ['content','required']
        ];
    }
    //设置标签的名称
    public function attributeLabels()
    {
        return [
            'content' => '内容',
        ];
    }
}