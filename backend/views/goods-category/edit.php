<?php
/**
 * @var $this \yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'parent_id')->hiddenInput();
//*************************ztree**********************
//引入css,js
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', [
    'depends' => \yii\web\JqueryAsset::class
]);
//html代码
echo <<<HTML
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;

//js代码
$this->registerJs(
    <<<JS
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
        data: {
		simpleData: {
			enable: true,
			idKey: "id",
			pIdKey: "parent_id",
			rootPId: 0
		     }
	    },
	    callback: {
		onClick: function(event, treeId, treeNode) {
		  //console.log(treeNode);exit;
		  //把节点值写入parent_id
		  $("#goodscategory-parent_id").val(treeNode.id);
		}
	}
 };
       // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$nodes};
       // zNodes = JSON.parse(zNodes);
        var zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        //展开全部节点
        zTreeObj.expandAll(true);
        //回显选中
        zTreeObj.selectNode(zTreeObj.getNodeByParam("id", "{$model->parent_id}", null));
JS
);


//*************************ztree**********************
echo $form->field($model, 'intro')->textarea();
echo '<button class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();