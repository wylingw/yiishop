<?php
/* @var $this yii\web\View */
?>
<a href="<?= \yii\helpers\Url::to(['goods-category/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>上一级</td>
        <td>简介</td>
        <td>操作</td>
    </tr>
    <?php foreach ($goods as $good): ?>
        <tr>
            <td><?= $good->id ?></td>
            <td><?= $good->name ?></td>
            <td><?= $good->parent_id ?></td>
            <td><?= $good->intro ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['goods-category/edit', 'id' => $good->id]) ?>" class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['goods-category/delete', 'id' => $good->id]) ?>"
                   class="btn btn-info">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
