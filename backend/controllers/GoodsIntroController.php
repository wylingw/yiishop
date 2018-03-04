<?php

namespace backend\controllers;

use backend\models\GoodsIntro;

class GoodsIntroController extends \yii\web\Controller
{
    public function actionIndex($id)
    {
        //获取数据
        $model = GoodsIntro::findOne(['goods_id' => $id]);
        //调用页面,分配数据
        return $this->render('index', ['model' => $model]);
    }

}
