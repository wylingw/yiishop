<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'parent_id')->dropDownList(['placeholder'=>'=请选择上级菜单=',\backend\models\Menu::getData()]);
echo $form->field($model, 'url')->dropDownList(['placeholder'=>'=请选择路由=',\backend\models\Menu::getPermission()]);
echo $form->field($model, 'sort')->textInput();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();
//
//