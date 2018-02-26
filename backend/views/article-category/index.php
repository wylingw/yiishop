<a href="<?= \yii\helpers\Url::to(['article-category/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>简介</td>
        <td>排序</td>
        <td>状态</td>
        <td>操作</td>
    </tr>
    <?php foreach ($arts as $art): ?>
        <tr>
            <td><?= $art->id ?></td>
            <td><?= $art->name ?></td>
            <td><?= $art->intro ?></td>
            <td><?= $art->sort ?></td>
            <td><?= $art->is_deleted == 0 ? '正常' : '删除' ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['article-category/edit', 'id' => $art->id]) ?>" class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['article-category/delete', 'id' => $art->id]) ?>"
                   class="btn btn-info">删除</a>
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
