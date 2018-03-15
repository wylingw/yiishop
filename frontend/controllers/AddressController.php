<?php

namespace frontend\controllers;

use backend\models\GoodsCategory;
use frontend\models\Address;

class AddressController extends \yii\web\Controller
{
    //地址页
    public function actionIndex()
    {
        //获取所有地址
        $id = \Yii::$app->user->id;
        $address = Address::find()->where(['user_id' => $id])->all();
        return $this->render('index', ['address' => $address]);
    }

    //添加地址
    public function actionAdd()
    {
        //实例化
        $model = new Address();
        $request = \Yii::$app->request;
//        var_dump($request->post());
//        die();
        if ($request->isPost) {
            //加载数据
            $model->load($request->post(), '');
            if ($model->validate()) {
                if (isset($model->is_selected)) {
                    $model->is_selected = 1;
                } else {
                    $model->is_selected = 0;
                }
                $id = \Yii::$app->user->id;
                // var_dump($id);die();
                $model->user_id = $id;
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加地址成功');
                //跳转
                return $this->redirect(['address/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用页面
        return $this->render('address');
    }


    //修改地址
    public function actionEdit($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = Address::findOne(['id' => $id]);
        if ($request->isPost) {
            //加载数据
//            var_dump($request->post());
//            die();
            $model->load($request->post(), '');
            if ($model->validate()) {
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改地址成功');
                //跳转
                return $this->redirect(['address/index']);
            } else {
                var_dump($model->getErrors());
                exit();
            }
        }

        //调用页面
        return $this->render('edit', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = Address::findOne(['id' => $id]);
        if ($model) {
            $model->delete();
            echo 1;
        } else {
            echo 0;
        }
    }


}
