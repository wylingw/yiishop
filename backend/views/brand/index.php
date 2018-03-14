<a href="<?= \yii\helpers\Url::to(['brand/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">

    <tr>
        <td>id</td>
        <td>名称</td>
        <td>简介</td>
        <td>LOGO</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($brands as $brand): ?>
        <tr data-id="<?= $brand->id ?>">
            <td><?= $brand->id ?></td>
            <td><?= $brand->name ?></td>
            <td><?= $brand->intro ?></td>
            <td><img src="<?= $brand->logo ?>" alt="" style="width: 60px" class="img-circle"></td>
            <td><?= $brand->sort ?></td>
            <td><?= $brand->is_deleted == 0 ? '正常' : '删除' ?></td>

            <td>
                <a href="<?= \yii\helpers\Url::to(['brand/edit', 'id' => $brand->id]) ?>" class="btn btn-info">修改</a>
                <a href="javascript:;" class="btn btn-info btn_del">删除</a>
            </td>

        </tr>
    <?php endforeach; ?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $pager,
    'prevPageLabel' => '上一页',
    'nextPageLabel' => '下一页',
    'hideOnSinglePage' => 0,
]);
/**
 * @var $this \yii\web\View
 */
$url = \yii\helpers\Url::to(['brand/delete']);
$this->registerJs(
    <<<JS
        $(".btn_del").click(function() {
          if(confirm('确定删除该记录吗,删除后无法恢复')){
              var tr=$(this).closest('tr');
              var id=tr.attr('data-id');
              //console.log(id);
              $.get("{$url}",{id:id},function(data) {
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