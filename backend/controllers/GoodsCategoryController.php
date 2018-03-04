<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use backend\models\GoodsCategoryQuery;
use creocoder\nestedsets\NestedSetsBehavior;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //获取所有数据
        $goods = GoodsCategory::find()->all();
        //调用视图
        return $this->render('index', ['goods' => $goods]);
    }

    //添加
    public function actionAdd()
    {
        //实例化
        $request = \Yii::$app->request;
        $model = new GoodsCategory();
        if ($request->isPost) {
            //接收数据
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id) {
                    //添加子节点
                    $countries = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($countries);
                    echo '成功';
                } else {
                    //根节点
                    $model->parent_id = 0;
                    $model->makeRoot();
                }
                //设置提示信息
                \Yii::$app->session->setFlash('success', '添加成功');
                //跳转
                return $this->redirect(['goods-category/index']);
            }
        }
        //获取所有节点数据
        $nodes = GoodsCategory::find()->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];
        //调用页面,分配数据
        return $this->render('add', ['model' => $model, 'nodes' => json_encode($nodes)]);
    }

    //修改
    public function actionEdit($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = GoodsCategory::findOne(['id' => $id]);
        if ($request->isPost) {
            //获取旧的parent_id
            // $old_parent_id = $model->parent_id;
            //接收数据
            $model->load($request->post());
            if ($model->validate()) {
                if ($model->parent_id) {
                    //添加子节点
                    $countries = GoodsCategory::findOne(['id' => $model->parent_id]);
                    $model->prependTo($countries);
                } else {
                    // $new_parent_id = $model->parent_id;
                    //根节点
                    if ($model->getOldAttribute('parent_id') == 0) {
                        //旧的parent_id改为新的parent_id时会报错
                        $model->save();
                    } else {
                        $model->makeRoot();
                    }

                }
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改成功');
                //跳转
                return $this->redirect(['goods-category/index']);
            }
        }
        //获取所有节点数据
        $nodes = GoodsCategory::find()->asArray()->all();
        $nodes[] = ['id' => 0, 'parent_id' => 0, 'name' => '顶级分类'];

        //调用页面,分配数据
        return $this->render('edit', ['model' => $model, 'nodes' => json_encode($nodes)]);
    }

    //删除
    public function actionDelete($id)
    {
        //实例化
        $request = \Yii::$app->request;
        $model = GoodsCategory::findOne(['id' => $id]);
        $model->deleteWithChildren();
        //跳转
        return $this->redirect(['goods-category/index']);
    }



    //测试
//    public function actionTest()
//    {
////        //根节点
////        $countries = new GoodsCategory();
////        $countries->name = '家用电器';
////        $countries->parent_id = 0;
////        $countries->makeRoot();
////        echo '成功';
//        //添加子节点
//        $countries = GoodsCategory::findOne(['id' => 1]);
//        $russia = new GoodsCategory();
//        $russia->name = '洗衣机';
//        $russia->prependTo($countries);
//        echo '成功';
//    }
//
//    //测试ztree
//    public function actionZtree()
//    {
//
//        $this->layout = false;//不引用laout样式
//        //获取所有节点数据
//        $nodes = GoodsCategory::find()->asArray()->all();
//        return $this->render('ztree',['nodes'=>json_encode($nodes)]);
//    }
}
