<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "delivery".
 *
 * @property int $id
 * @property string $delivery_name 配送方式名称
 * @property double $delivery_price 配送方式价格
 */
class Delivery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'delivery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['delivery_name', 'delivery_price'], 'required'],
            [['delivery_price'], 'number'],
            [['delivery_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
        ];
    }
}
