<a href="<?= \yii\helpers\Url::to(['admin/add']) ?>" class="btn btn-info">添加</a>
<table class="table table-bordered table-hover">
    <tr>
        <td>id</td>
        <td>用户名</td>
        <td>邮箱</td>
        <td>最后登录时间</td>
        <td>最后登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($admins as $admin): ?>
        <tr>
            <td><?= $admin->id ?></td>
            <td><?= $admin->username ?></td>
            <td><?= $admin->email ?></td>
            <td><?= date('Y-m-d H:i:s',$admin->last_login_time) ?></td>
            <td><?= $admin->last_login_ip ?></td>
            <td>
                <a href="<?= \yii\helpers\Url::to(['admin/edit', 'id' => $admin->id]) ?>"
                   class="btn btn-info">修改</a>
                <a href="<?= \yii\helpers\Url::to(['admin/delete', 'id' => $admin->id]) ?>"
                   class="btn btn-info">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
//echo \yii\widgets\LinkPager::widget([
//    'pagination' => $pager,
//    'prevPageLabel' => '上一页',
//    'nextPageLabel' => '下一页',
//    'hideOnSinglePage' => 0,
//]);
