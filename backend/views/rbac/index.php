<a href="<?= \yii\helpers\Url::to(['rbac/add-permission']) ?>" class="btn btn-info">添加</a>
<table class="" id="table_id_example">
    <thead>
    <tr>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rbacs as $rbac): ?>
        <tr data-name="<?= $rbac->name ?>">
            <td><?= $rbac->name ?></td>
            <td><?= $rbac->description ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['rbac/edit-permission', 'name' => $rbac->name]) ?>"
                   class="btn btn-info">修改</a>
                <a href="javascript:;" class="btn btn-info btn_del">删除</a>
            </td>

        </tr>
    <?php endforeach; ?>
    </tbody>
</table>


<?php
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['rbac/delete-permission']);
$this->registerJs(
    <<<JS
        $(".btn_del").click(function() {
          if(confirm('确定删除该记录吗,删除后无法恢复')){
              var tr=$(this).closest('tr');
              var name=tr.attr('data-name');
              //console.log(id);
              $.get("{$url}",{name:name},function(data) {
               // location.reload(true);
               if (data==1){
                   //console.log('删除成功')
                     tr.fadeOut();
               }
              })
          }
        });

JS
);
//引入css,js
$this->registerCssFile('@web/DataTables-1.10.15/media/css/jquery.dataTables.css');
$this->registerJsFile('@web/DataTables-1.10.15/media/js/jquery.dataTables.js', [
    'depends' => \yii\web\JqueryAsset::class
]);


$this->registerJs(<<<JS
$(document).ready( function () {
    $('#table_id_example').DataTable();
} );
JS
);
?>

