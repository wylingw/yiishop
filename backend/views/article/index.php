<a href="<?= \yii\helpers\Url::to(['article/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">
    <tr>
        <td>id</td>
        <td>名称</td>
        <td>简介</td>
        <td>文章分类</td>
        <td>排序</td>
        <td>状态</td>
        <td>创建时间</td>
        <td>操作</td>
    </tr>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?= $article->id ?></td>
            <td><?= $article->name ?></td>
            <td><?= $article->intro ?></td>
            <td><?= $article->article_category_id ?></td>
            <td><?= $article->sort ?></td>
            <td><?= $article->is_deleted == 0 ? '正常' : '删除' ?></td>
            <td><?= date('Y-m-d',$article->create_time) ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['article/edit', 'id' => $article->id]) ?>"
                   class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['article/delete', 'id' => $article->id]) ?>"
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
