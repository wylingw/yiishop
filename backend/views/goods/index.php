<?php
$form = \yii\bootstrap\ActiveForm::begin([
    'action' => \yii\helpers\Url::to(['goods/index']),
    'method' => 'get',
    'options'=>['class'=>'form-inline']
]);
echo $form->field($model, 'name')->textInput(['placeholder' => '商品名称', 'style' => 'width:130px'])->label(false);
echo $form->field($model, 'sn')->textInput(['placeholder' => '货号', 'style' => 'width:130px'])->label(false);
echo $form->field($model, 'min')->textInput(['placeholder' => '价格', 'style' => 'width:130px'])->label(false);
echo $form->field($model, 'max')->textInput(['placeholder' => '价格', 'style' => 'width:130px'])->label(false);
echo '<button class="btn btn-info">搜索</button>';
\yii\bootstrap\ActiveForm::end();

?>


    <table class="table table-bordered table-hover">

        <tr>
            <td>id</td>
            <td>商品名称</td>
            <td>货号</td>
            <td>LOGO</td>
            <td>商品价格</td>
            <td>操作</td>
        </tr>
        <?php foreach ($goods as $good): ?>
            <tr data-id="<?= $good->id ?>">
                <td><?= $good->id ?></td>
                <td><?= $good->name ?></td>
                <td><?= $good->sn ?></td>
                <td><img src="<?="http://admin.yiishop.com". $good->logo ?>" alt="" style="width: 60px" class="img-circle"></td>
                <td><?= $good->shop_price ?></td>
                <td>
                    <a href="<?= \yii\helpers\Url::to(['goods/gallery', 'id' => $good->id]) ?>"
                       class="btn btn-info">相册</a>
                    <a href="<?= \yii\helpers\Url::to(['goods/edit', 'id' => $good->id]) ?>" class="btn btn-info">修改</a>
                    <a href="javascript:;" class="btn btn-info btn_del">删除</a>
                    <a href="<?= \yii\helpers\Url::to(['goods-intro/index', 'id' => $good->id]) ?>"
                       class="btn btn-info">查看内容</a>
                    <a href="<?= \yii\helpers\Url::to(['goods/add']) ?>" class="btn btn-info">添加</a>
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
$url = \yii\helpers\Url::to(['goods/delete']);
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
