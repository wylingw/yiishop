<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name 菜单名称
 * @property int $parent_id 上级菜单
 * @property string $url 地址/路由
 * @property int $sort 排序
 */
class Menu extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent_id', 'sort'], 'required'],
            [['parent_id', 'sort'], 'integer'],
            [['name', 'url'], 'string', 'max' => 100],
            ['url', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '菜单名称',
            'parent_id' => '上级菜单',
            'url' => '地址/路由',
            'sort' => '排序',
        ];
    }

    //获取所有数据
    public static function getData()
    {
        $menus = self::find()->where(['parent_id'=>0])->all();
        $tmp = [];
        foreach ($menus as $menu) {
            $tmp[$menu->id] = $menu->name;
        }
        return $tmp;

    }

    //获取所有权限数据
    public static function getPermission()
    {
        $authManager = Yii::$app->authManager;
        $permissions = $authManager->getPermissions();
        $tmp = [];
        foreach ($permissions as $permission) {
            $tmp[$permission->name] = $permission->name;
        }
        return $tmp;
    }

    //获取菜单
    public static function getMenus($menuItems)
    {
//       $menuItems[] = //二级菜单组
//            ['label'=>'用户管理','items'=>[
//                ['label' => '添加用户', 'url' => ['admin/add']],
//                ['label' => '用户列表', 'url' => ['admin/index']],
//            ]];
        //获取一级菜单
        $menus = self::find()->where(['parent_id' => 0])->all();
        foreach ($menus as $menu) {
            $items = [];
            $children = self::find()->where(['parent_id' => $menu->id])->all();
            foreach ($children as $child) {
                //只添加有权限的二级菜单
                if (Yii::$app->user->can($child->url))
                $items[]=['label' => $child->name, 'url' => [$child->url]];
            }
            //只显示有子菜单的一级菜单
            if ($items)
            $menuItems[] = ['label' => $menu->name, 'items' => $items];
        }
        return $menuItems;
    }
}
