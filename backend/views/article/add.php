<?php
use \kucha\ueditor\UEditor;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'article_category_id')->dropDownList(\backend\models\Article::getArticleCategoryOptions());
echo $form->field($model, 'sort')->textInput();
//echo $form->field($articleDetail, 'content')->textarea();
echo $form->field($articleDetail, 'content')->widget('kucha\ueditor\UEditor', []);

//echo UEditor::widget([
//    'clientOptions' => [
//        //编辑区域大小
//        'initialFrameHeight' => '200',
//        //设置语言
//        'lang' => 'en', //中文为 zh-cn
//        //定制菜单
//        'toolbars' => [
//            [
//                'fullscreen', 'source', 'undo', 'redo', '|',
//                'fontsize',
//                'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
//                'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
//                'forecolor', 'backcolor', '|',
//                'lineheight', '|',
//                'indent', '|'
//            ],
//        ]
//    ]
//]);


echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();