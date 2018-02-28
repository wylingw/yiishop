<?php

namespace backend\controllers;

use backend\models\ArticleDetail;
use yii\web\Controller;

class ArticleDetailController extends Controller
{
    //查看内容
    public function actionIndex($id)
    {
        //获取数据
        $model = ArticleDetail::findOne(['article_id' => $id]);
        //调用页面,分配数据
        return $this->render('index', ['model' => $model]);
    }

}