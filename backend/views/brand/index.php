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
        <tr>
            <td><?= $brand->id ?></td>
            <td><?= $brand->name ?></td>
            <td><?= $brand->intro ?></td>
            <td><img src="<?= $brand->logo ?>" alt="" style="width: 60px" class="img-circle"></td>
            <td><?= $brand->sort ?></td>
            <td><?= $brand->is_deleted == 0 ? '正常' : '删除' ?></td>

            <td>
                <a href="<?= \yii\helpers\Url::to(['brand/edit', 'id' => $brand->id]) ?>" class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['brand/delete', 'id' => $brand->id]) ?>" class="btn btn-info">删除</a>
            </td>

        </tr>
    <?php endforeach; ?>
</table>
