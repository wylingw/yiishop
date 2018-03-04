<?php

namespace backend\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 货号
 * @property string $logo LOGO
 * @property int $goods_category_id 商品分类
 * @property int $brand_id 品牌分类
 * @property string $market_price 市场价格
 * @property string $shop_price 商品价格
 * @property int $stock 库存
 * @property int $is-on_sale 是否在售(1在售 0下架)
 * @property int $status 状态(1正常 0回收站)
 * @property int $sort 排序
 * @property int $create_time 添加时间
 * @property int $view_times 浏览次数
 */
class Goods extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'logo', 'goods_category_id', 'brand_id', 'market_price',  'stock', 'is_on_sale', 'sort'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort', 'create_time', 'view_times'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name', 'sn'], 'string', 'max' => 20],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '货号',
            'logo' => 'LOGO',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌分类',
            'market_price' => '市场价格',
            'shop_price' => '商品价格',
            'stock' => '库存',
            'is_on_sale' => '是否在售',
            'status' => '状态',
            'sort' => '排序',
            'create_time' => '添加时间',
            'view_times' => '浏览次数',
        ];
    }

    //链表查询
    //商品分类
    public function getGoodsCategory()
    {
        return $this->hasOne(GoodsCategory::className(), ['id' => 'goods_category_id']);
    }

    public static function getGoodsCategoryOptions()
    {
        $rows = GoodsCategory::find()->all();
        $tmp = [];
        foreach ($rows as $row) {
            $tmp[$row->id] = $row->name;
        }
        return $tmp;
    }
    //品牌分类
    public function getBrand()
    {
        return $this->hasOne(Brand::className(), ['id' => 'brand_id']);
    }

    public static function getBrandOptions()
    {
        $rows = Brand::find()->all();
        $tmp = [];
        foreach ($rows as $row) {
            $tmp[$row->id] = $row->name;
        }
        return $tmp;
    }

}
