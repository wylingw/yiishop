<a href="<?= \yii\helpers\Url::to(['menu/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">

    <tr>

        <td>名称</td>
        <td>路由</td>
        <td>排序</td>
        <td>操作</td>
    </tr>
    <?php foreach ($menus as $menu): ?>
        <tr>
            <td><?= $menu->name ?></td>
            <td><?= $menu->url ?></td>
            <td><?= $menu->sort ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['menu/edit', 'id' => $menu->id]) ?>" class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['menu/delete', 'id' => $menu->id]) ?>"
                   class="btn btn-info">删除</a>
            </td>

        </tr>
    <?php endforeach; ?>

</table>




