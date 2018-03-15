<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property string $name 收货人姓名
 * @property string $address 收货地址
 * @property string $tel 手机号码
 * @property int $user_id 所属用户
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'address', 'tel', 'cmbProvince','cmbCity','cmbArea'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'address'], 'string', 'max' => 20],
            [['tel'], 'string', 'max' => 255],
            [['default'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '收货人姓名',
            'address' => '收货地址',
            'tel' => '手机号码',
            'user_id' => '所属用户',
        ];
    }
}
