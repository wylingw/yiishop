
<?php if (isset($goods)): ?>
    <?php foreach ($goods as $good): ?>
        <tr>
            <td class="col1"><a href=""><img src="<?= "http://admin.yiishop.com" . $good->logo ?>" alt=""/></a>
                <strong><a href=""><?= $good->name ?></a></strong>
            </td>
            <td class="col3">￥<span><?= $good->shop_price ?></span></td>
            <?php if (Yii::$app->user->isGuest): ?>
                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?= $carts[$good->id] ?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>
            <?php endif; ?>
            <?php if (!Yii::$app->user->isGuest): ?>

                <td class="col4">
                    <a href="javascript:;" class="reduce_num"></a>
                    <input type="text" name="amount" value="<?= $amount ?>" class="amount"/>
                    <a href="javascript:;" class="add_num"></a>
                </td>

            <?php endif; ?>
            <td class="col5">￥<span><?= $good->shop_price ?></span></td>
            <td class="col6"><a href="javascript:;" class="btn_del">删除</a></td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>