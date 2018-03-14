<a href="<?= \yii\helpers\Url::to(['rbac/role-add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover" id="table_id_example" >
    <thead>
    <tr>
        <td>名称</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($roles as $role): ?>
        <tr data-name="<?= $role->name ?>">
            <td><?= $role->name ?></td>
            <td><?= $role->description ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['rbac/role-edit', 'name' => $role->name]) ?>"
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
$url = \yii\helpers\Url::to(['rbac/role-delete']);
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
