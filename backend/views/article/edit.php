<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'article_category_id')->dropDownList(\backend\models\Article::getArticleCategoryOptions());
echo $form->field($model, 'sort')->textInput();
echo $form->field($articleDetail, 'content')->widget('kucha\ueditor\UEditor', []);
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();