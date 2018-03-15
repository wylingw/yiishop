<?php

namespace frontend\controllers;


use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Delivery;
use frontend\models\Order;
use frontend\models\OrderGoods;
use frontend\models\Payment;
use function GuzzleHttp\Psr7\copy_to_string;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\Cookie;

class GoodsCategoryController extends Controller
{
    //商品分类列表页
    public function actionIndex()
    {
        //拿到所有数据
        $categorys = \backend\models\GoodsCategory::find()->where(['parent_id' => 0])->all();
        //调用页面
        return $this->render('index', ['categorys' => $categorys]);
    }

    //商品列表页
    public function actionList()
    {
        $id = \Yii::$app->request->get('id');
        //var_dump($id);die();
        $goods = Goods::find()->where(['brand_id' => $id])->all();
        //调用页面
        return $this->render('list', ['goods' => $goods]);
    }

    //商品详情页
    public function actionGoods()
    {
        $id = \Yii::$app->request->get('id');
        $goodList = Goods::findOne(['id' => $id]);
        //获取相册数据
        $model = GoodsGallery::find()->where(['goods_id' => $id])->all();
        $inro = GoodsIntro::find()->where(['goods_id' => $id])->all();

        //调用页面
        return $this->render('goods', ['goodList' => $goodList, 'model' => $model, 'inro' => $inro]);
    }

    //加入购物车
    public function actionAddToCart($goods_id, $amount)
    {
        if (\Yii::$app->user->isGuest) {
            //用户未登录状态
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if ($value) {
                $carts = unserialize($value);
            } else {
                $carts = [];
            }
            //判断该购物车中是否已有加入的商品
            if (array_key_exists($goods_id, $carts)) {
                $carts[$goods_id] += $amount;
            } else {
                $carts[$goods_id] = $amount;
            }
            //加入的商品保存到cookies中
            $cookie = new Cookie();
            $cookie->name = 'carts';
            //序列化
            $cookie->value = serialize($carts);
            //设置过期时间
            $cookie->expire = time() + 7 * 24 * 60 * 60;
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);

//          var_dump($cookies);die();

        } else {
            //用户登录状态
            //获取用户id
            $id = \Yii::$app->user->id;
            // var_dump($id);die();
            $model = new Cart();
            $request = \Yii::$app->request;
            if ($request->isGet) {
                //加载数据
                $model->load($request->get(), '');
                if ($model->validate()) {
                    //找到对应goods_id的商品
                    $goods_id = $model->goods_id;
//                     var_dump($goods_id);die();
                    $carts = Cart::find()->where(['user_id' => $id])->all();
                    //var_dump($carts);die();
                    if (isset($carts)) {
                        $arrs = [];
                        foreach ($carts as $cart) {
                            $arrs[$cart->goods_id] = $cart->amount;
                        }
                        // var_dump($arrs);die();
                        //判断该数据表中是否已有加入的商品
                        if (array_key_exists($goods_id, $arrs)) {
                            $arrs[$goods_id] += $amount;
                            $model->amount = $arrs[$goods_id];
                            //保存
                            $m = Cart::findOne(['goods_id' => $goods_id]);
                            //var_dump($m);die();
                            $m->updateAttributes(['amount' => $model->amount]);
                        } else {
                            //保存
                            $model->user_id = $id;
                            $model->save();

                        }
                    }


                } else {
                    var_dump($model->getErrors());
                    die();
                }
            }
        }

        //调用页面

        return $this->render('add');
    }

    //购物车
    public function actionCart()
    {
        if (\Yii::$app->user->isGuest) {
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if ($value) {
                $carts = unserialize($value);
            } else {
                $carts = [];
            }
            //取出所有商品id
            $id = array_keys($carts);
            if (isset($id)) {
                //查询商品信息
                $goods = Goods::find()->where(['id' => $id])->all();
                //调用页面
                return $this->render('cart', ['goods' => $goods, 'carts' => $carts]);
            }


        } else {
            //同步购物车数据到数据库
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if ($value) {
                $carts = unserialize($value);
            } else {
                $carts = [];
            }

            $id = array_keys($carts);
            //var_dump($id);die();
            $goods = Cart::find()->select('goods_id,amount')->andWhere(['goods_id' => $id])->all();
            //var_dump($goods);die();

            if ($goods) {
                foreach ($goods as $good) {
                    $goods_id = $good->goods_id;
                }
                if (array_key_exists($goods_id, $carts)) {
                    $model = Cart::find()->where(['goods_id' => $goods_id])->all();
                    foreach ($model as $mo) {
                        $count = $good->amount + $carts[$goods_id];
                        $mo->amount = $count;
                        //var_dump($mo->amount);die();
                    }
                    $mo->updateAttributes(['amount' => $mo->amount]);
                    //清空cookie
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookies = \Yii::$app->response->cookies;
                    $cookies->remove($cookie);

                }
            } else {
                $g = new Cart();
                $gids = Goods::find()->where(['id' => $id])->all();
                // var_dump($id);die();
                if ($gids) {
                    foreach ($gids as $good) {
                        $g->amount = $carts[$good->id];
                        //var_dump($g->amount);die();
                        $g->goods_id = $good->id;

                    }
                    $g->user_id = \Yii::$app->user->id;
                    $g->save();
                    //清空cookie
                    $cookie = new Cookie();
                    $cookie->name = 'carts';
                    $cookies = \Yii::$app->response->cookies;
                    $cookies->remove($cookie);
                }

            }


            //根据用户id查询所有加入购物车的商品
            $user_id = \Yii::$app->user->id;
            $alls = Cart::find()->Where(['user_id' => $user_id])->all();
            $id = [];
            foreach ($alls as $v) {
                $id[$v->goods_id] = $v->goods_id;
            }

            //查询商品信息
            $goods = Goods::find()->where(['id' => $id])->all();
            $carts = Cart::find()->where(['goods_id' => $id])->all();
//            var_dump($carts);die();
            $amount = [];
            foreach ($carts as $cart) {
                $amount[$cart->goods_id] = $cart->amount;
            }
            //调用页面
            return $this->render('cart', ['goods' => $goods, 'amount' => $amount]);

        }


    }


    //商品在购物车里增加或减少
    public function actionAjaxCart($goods_id, $amount)
    {
        if (\Yii::$app->user->isGuest) {
            //获取cookie中的购物车
            $cookies = \Yii::$app->request->cookies;
            $value = $cookies->getValue('carts');
            if ($value) {
                $carts = unserialize($value);
            } else {
                $carts = [];
            }
            if ($amount != 0) {
                $carts[$goods_id] = $amount;

            } else {
                unset($carts[$goods_id]);
            }
            //将购物车数据保存到cookie
            $cookie = new Cookie();
            $cookie->name = 'carts';
            $cookie->value = serialize($carts);
            $cookies = \Yii::$app->response->cookies;
            $cookies->add($cookie);
        } else {
            $user_id = \Yii::$app->user->id;
            $model = Cart::findOne(['user_id' => $user_id, 'goods_id' => $goods_id]);
            if ($model) {
                if ($amount != 0) {
                    $model->amount = $amount;
                   // var_dump($model->getErrors());die();
                    $model->save();
                    var_dump($model->getErrors());die();
                } else {
                    $model->delete();
                }
            }
        }

    }

    //订单确认页
    public function actionBuy()
    {
        if (\Yii::$app->user->isGuest) {
            //未登录
            return $this->redirect(['users/login']);
        } else {
            //已登录
            $id = \Yii::$app->user->id;
            //该用户的收货地址
            $address = Address::find()->where(['user_id' => $id])->all();
            //配送方式
            $deliverys = Delivery::find()->all();
            //支付方式
            $pays = Payment::find()->all();
            //从数据表获取商品信息
            $ids = Cart::find()->select('goods_id')->where(['user_id' => $id])->all();
            $goods_id = [];
            foreach ($ids as $v) {
                $goods_id[$v->goods_id] = $v->goods_id;
            }
            $goods = Goods::find()->where(['id' => $goods_id])->all();
            $carts = Cart::find()->where(['goods_id' => $goods_id])->all();
            $amount = [];
            foreach ($carts as $cart) {
                $amount[$cart->goods_id] = $cart->amount;
            }
            $count = Cart::find()->where(['user_id' => $id])->count();


        }

        //调用页面
        return $this->render('buy', ['address' => $address, 'goods' => $goods, 'amount' => $amount, 'count' => $count, 'deliverys' => $deliverys, 'pays' => $pays]);
    }

    //处理确认页提交过来的数据
    public function actionOrder()
    {
        $order = new Order();
        $request = \Yii::$app->request;
        if ($request->isPost) {
            //加载数据
            $order->load($request->post(), '');
            if ($order->validate()) {
                //处理数据
                $order->create_time = time();
                $order->status = 1;//待付款
                $id = $request->post('address_id');
                $address = Address::findOne(['id' => $id]);
                $order->province = $address->cmbProvince;
                $order->city = $address->cmbCity;
                $order->area = $address->cmbArea;
                $order->address = $address->address;
                $order->name = $address->name;
                $order->tel = $address->tel;
                $order->total = 0;
                $order->user_id = \Yii::$app->user->id;
                //开启事务
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    //保存
                    $order->save();
                    $id = \Yii::$app->user->id;
                    //保存订单详情表
                    $carts = Cart::find()->where(['user_id' => $id])->all();
                    foreach ($carts as $cart) {
                        $goods = Goods::findOne(['id' => $cart->goods_id]);
                        //库存
                        if ($goods->stock < $cart->amount) {
                            //库存不足,跑出异常
                            throw new Exception("商品库存不足,请选购其他商品");
                        } else {
                            //减库存
                            $goods->stock -= $cart->amount;
                            $goods->save();
                            //实例化
                            $orderGoods = new OrderGoods();
                            $orderGoods->order_id = $order->id;
                            $orderGoods->goods_id = $goods->id;
                            $orderGoods->goods_name = $goods->name;
                            $orderGoods->logo = $goods->logo;
                            $orderGoods->price = $goods->shop_price;
                            $orderGoods->amount = $cart->amount;
                            $orderGoods->total = $orderGoods->price * $orderGoods->amount;
                            //订单总金额
                            $order->total += $orderGoods->total;
                            $orderGoods->save();
                        }
                    }
                    //加运费
                    $d = Delivery::findOne(['delivery_id' => $order->delivery_id]);
                    $order->total = $d->delivery_price;
                    //保存
                    $order->save();
                    //清空购物车
                    Cart::deleteAll(['user_id' => $id]);

                    //提交事务
                    $transaction->commit();
                } catch (Exception $e) {
                    //事务回滚
                    $transaction->rollBack();
                }


            } else {
                var_dump($order->getErrors());
                die();
            }
        }

        //调用页面
        return $this->render('order');
    }

    //我的订单
    public function actionOwn()
    {
        //查询订单详情表所有数据
        $id = \Yii::$app->user->id;
        $goods = OrderGoods::find()->where(['user_id' => $id])->all();
        $ids = OrderGoods::getOrderOptions();
        $orders = Order::find()->where(['id' => $ids])->all();
        //var_dump($orders);die();
        foreach ($orders as $order) {
            $name = $order->name;
            $time = date('Y-m-d H:i:s', $order->create_time);
            $status = $order->status;
        }

        //调用页面
        return $this->render('own', ['goods' => $goods, 'name' => $name, 'time' => $time, 'status' => $status]);
    }
}