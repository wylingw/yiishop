<a href="<?= \yii\helpers\Url::to(['article/index']) ?>" class="btn btn-info">返回</a>
<table class="table table-bordered table-hover">
    <tr>
        <td>id</td>
        <td>内容</td>
    </tr>
        <tr>
            <td><?= $model->article_id ?></td>
            <td><?= $model->content ?></td>
</table>


