<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" href="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/layui.all.js"></script>
<script src="<?php echo APP_PATH?>statics/public/jquery/jquery-3.3.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<style>
    .btn { display: inline-block; padding: 0px 5px; margin-bottom: 0; font-size: 12px; font-weight: 400; line-height: 1.32857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; margin-left: 5px;}
    .btn-info { background-image: -webkit-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -o-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5bc0de),to(#2aabd2)); background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2aabd2', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #28a4c9;}
    .btn-info { color: #fff; background-color: #5bc0de; border-color: #46b8da;}

    .btn-danger { background-image: -webkit-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -o-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#d9534f),to(#c12e2a)); background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc12e2a', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #b92c28;}
    .btn-danger { color: #fff; background-color: #d9534f; border-color: #d43f3a;}

    .btn-success { background-image: -webkit-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -o-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5cb85c),to(#419641)); background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5cb85c', endColorstr='#ff419641', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #3e8f3e;}
    .btn-success { color: #fff; background-color: #5cb85c; border-color: #4cae4c;}
    a:hover{ text-decoration: none; }
    .page{
        text-align: right;
    }
    .page div{
        display: inline-block;
        text-align: right;
        border-radius: 5px;
        padding: 0;
        display: inline-block;
        list-style: none;
        border: 1px solid #ddd;
        color: #337ab7;
    }
    .page span{
        display: inline-block;
        line-height: 35px;
        padding: 0 15px;
        border-left: 1px solid #ddd;
        margin-right: -5px;
        cursor:pointer;
    }
    .page span:first-of-type{
        border-style: none;
        cursor:pointer;
    }
    .page-on{
        background-color: #337ab7;
        color: #fff;

    }
</style>
<style type="text/css">
    .table_form th{text-align: left;}
</style>
<style>
    td{
        min-width: 100px;
    }
    .wpb{
        background-color: red;
        color: white;
        cursor: pointer;
    }
    .pb{
        background-color: lightskyblue;
        color: white;
        cursor: pointer;
    }
</style>


<div class="pad-10">
    <div class="common-form">
        <div id="div_setting_2" class="contentList">
            <?php foreach($orderInfo as $key=>$data) { ?>
                <fieldset>
                    <legend id="<?php echo "comment_".$data["afterSaleid"]; ?>"  class="<?php if($data["isDeal"] == '1') echo "wpb"; else echo "pb"?>"><?php if($data["isDeal"] == '1') echo "未处理"; elseif($data["isDeal"]== "2")echo "同意"; else echo "不同意";?></legend>

                    <table class="table_form"  style="min-width: 100%;overflow:auto;display: block;">
                        <tbody>
                        <tr >
                            <td >商品名：</td>
                            <td colspan="1"><?php echo $data["goods_name"]?></td>
                            <td >原因：</td>
                            <td colspan="2"><?php echo $data["reason"]?></td>

                            <td >货物状态：</td>
                            <td colspan="1"><?php if($data["isDeliver"] == '1')echo "未发货"; else  echo"以发货"?> </td>
                        </tr>
                        <!--                    <tr>-->
                        <!--                        <td>商品简述：</td>-->
                        <!--                        <td colspan="5">-->
                        <!--                            --><?php //echo $data["sketch"]?>
                        <!--                        </td>-->
                        <!--                    </tr>-->
                        <tr>
                            <td >备注：</td>
                            <td colspan="6"><?php echo $data["remark"]?></td>
                        </tr>
                        <tr>
                            <td >图片:</td>
                            <td colspan="6" class="t1">
                                <?php $photo = json_decode($data["photo"], true);?>
                                <?php foreach($photo as $k=>$v){ ?>
                                    <img  onclick="ti()" style="object-fit: cover;" src="<?php echo $v?>"  height="90" width="90" >
                                <?php }?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div id="agree_<?php echo $data["afterSaleid"]?>">
                        <?php if($data["isDeal"] == '1'){?>
                            <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="changeCommentId_1('同意', '<?php echo$data['afterSaleid']?>')">同意</button>
                            <button class="layui-btn layui-btn-danger layui-btn-sm" onclick="changeCommentId_1('不同意','<?php echo $data['afterSaleid']?>')">不同意</button>
                        <?php } elseif($data["isDeal"] == '2') {?>
                            <p style="color: green">同意</p>
                        <?php } else {?>
                            <p style="color: red">不同意</p>
                        <?php }?>
                    </div>
                </fieldset>
                <div class="bk15"></div>
            <?php } ?>
        </div>
    </div>

</div>
</div>
</body>
<script>



</script>
<script>
    $(function () {
        ;
        ! function () {

            var layer = layui.layer,
                form = layui.form,
                $ = layui.jquery,
                upload = layui.upload,
                table = layui.table,
                flow = layui.flow;
        }();

    });

    function ti() {
        layer.photos({
            photos:'.t1',
            anim:5
        });
    }

    function changeCommentId_1(info, commentId)
    {
        layer.confirm('确定提交？',{icon:3, title:"提示"}, function (index){
            $.ajax({
                type : "post",
                url : "index.php?m=zyorder&c=zyorder_api&a=changeDeal&pc_hash=<?php echo $_GET["pc_hash"]?>",
                data:{
                    "commentid":commentId,
                    "isDeal":info
                },
                datatype : "JSON",
                success: function (data) {

                    console.log($("#comment_"+commentId).html());

                    $("#comment_"+commentId).html("已处理");
                    $("#comment_"+commentId).removeClass("wpb");
                    $("#comment_"+commentId).addClass("pb");
                    $("#agree_"+commentId).empty();
                    switch (info)
                    {
                        case "同意":
                            $("#agree_"+commentId).append('<p style="color: green">同意</p>');
                            break;
                        case "不同意":
                            $("#agree_"+commentId).append('<p style="color: red">不同意</p>');
                            break;
                    }

                }
            });
            layer.close(index);
        });

    }
</script>

</html>