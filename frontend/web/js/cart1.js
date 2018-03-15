/*
 @功能：购物车页面js
 @作者：diamondwang
 @时间：2013年11月14日
 */
$(function () {

    //减少
    $(".reduce_num").click(function () {
        var amount = $(this).parent().find(".amount");
        if (parseInt($(amount).val()) <= 1) {
            alert("商品数量最少为1");
        } else {
            $(amount).val(parseInt($(amount).val()) - 1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function () {
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        //点击减少
        var goods_id = $(this).closest('tr').attr('data-id');
        changeAmount(goods_id, $(amount).val());
    });


    //增加
    $(".add_num").click(function () {
        var amount = $(this).parent().find(".amount");
        $(amount).val(parseInt($(amount).val()) + 1);
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(amount).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function () {
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        //增加点击事件
        var goods_id = $(this).closest('tr').attr('data-id');
        changeAmount(goods_id, $(amount).val());
    });

    //直接输入
    $(".amount").blur(function () {
        if (parseInt($(this).val()) < 1) {
            alert("商品数量最少为1");
            $(this).val(1);
        }
        //小计
        var subtotal = parseFloat($(this).parent().parent().find(".col3 span").text()) * parseInt($(this).val());
        $(this).parent().parent().find(".col5 span").text(subtotal.toFixed(2));
        //总计金额
        var total = 0;
        $(".col5 span").each(function () {
            total += parseFloat($(this).text());
        });

        $("#total").text(total.toFixed(2));
        //直接输入点击事件
        var goods_id = $(this).closest('tr').attr('data-id');
        changeAmount(goods_id, $(amount).val());

    });
    //删除
    $(".col6").click(function () {
        if (confirm('确定删除吗?删除后无法恢复')) {
            var tr = $(this).closest('tr');
            var goods_id = tr.attr("data-id");
            tr.remove();
            changeAmount(goods_id, 0);

        }
    });

    //向后台发送数据
    function changeAmount(goods_id, amount) {
        $.get("/goods/ajax-cart", {goods_id: goods_id, amount: amount});
    }
});
