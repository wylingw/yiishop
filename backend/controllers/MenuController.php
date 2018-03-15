<?php

namespace backend\controllers;

use backend\models\Menu;
use yii\data\Pagination;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $menus = Menu::find()->all();
        //将数据按照缩进简单排序
//        foreach ($menus as $k => $v) {
//            $menus[$k]["menuChild"] = Menu::find()->where(['parent_id' => $v->id])->all();
//
//        }

        return $this->render('index', ['menus' => $menus]);
    }

    //添加
    public function actionAdd()
    {
        //实例化
        $model = new Menu();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['menu/index']);
            }
        }


        //调用视图
        return $this->render('add', ['model' => $model]);
    }

    //修改
    public function actionEdit($id)
    {
        //实例化
        $model = Menu::findOne(['id' => $id]);
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            if ($model->validate()) {
                //保存
                $model->save();
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['menu/index']);
            }
        }


        //调用视图
        return $this->render('add', ['model' => $model]);
    }

    //删除
    public function actionDelete($id)
    {
        $model = Menu::findOne(['id' => $id]);
        if ($model) {
            $model->delete();
        }
        //跳转
        return $this->redirect(['menu/index']);
    }

}
