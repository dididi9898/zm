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
<form name="searchform" action="" method="get" id="sbt">

    <input type="hidden" value="hpshop" name="m">
    <input type="hidden" value="goods" name="c">
    <input type="hidden" value="goodsCommint" name="a">
    <input type="hidden" value="" name="page" id="page">
    <input type="hidden" value="<?php echo $goods_id?>" name="goods_id" >
    <div class="explain-col search-form">
        好评度：
        <select name="status">
            <option  value="">好评度</option>
            <?php foreach(self::$commintGrade as $key=>$value ){?>
                <?php if($key == $status) {?>
                    <option  selected="selected" value=<?php echo intval($key)?>><?php echo $value?></option>
                <?php }else{?>
                    <option  value=<?php echo intval($key)?>><?php echo $value?></option>
                <?php }?>
            <?php }?>
        </select>
        是否屏蔽：
        <select name="Shield">
            <option  value="">是否屏蔽</option>
            <?php foreach(self::$isShield as $key=>$value ){?>
                <?php if($key == $Shield) {?>
                    <option  selected="selected" value=<?php echo intval($key)?>><?php echo $value?></option>
                <?php }else{?>
                    <option  value=<?php echo intval($key)?>><?php echo $value?></option>
                <?php }?>
            <?php }?>
        </select>
        <input type="submit" value=<?php echo L(search);?> class="button" name="dosubmit" >



    </div>
</form>

<div class="pad-10">
    <div class="common-form">
        <div id="div_setting_2" class="contentList">
            <?php foreach($info as $key=>$data) { ?>
                <fieldset>
                    <legend id="<?php echo "comment_".$data["commentid"]; ?>" onclick="changeCommentId(<?php echo $data["commentid"]; ?>)" class="<?php if($data["isShield"] == '1') echo "pb"; else echo "wpb"?>"><?php if($data["isShield"] == '1') echo "屏蔽"; else echo "已屏蔽"?></legend>

                    <table class="table_form"  style="min-width: 100%;overflow:auto;display: block;">
                        <tbody>
                        <tr >
                            <td >用户名：</td>
                            <td colspan="1"><?php echo $data["nickname"]?></td>
                            <td >手机号：</td>
                            <td colspan="1"><?php echo $data["mobile"]?></td>
                            <td >好评度：</td>
                            <td colspan="1"><?php echo self::$commintGrade[$data["commentGrade"]]?></td>
                            <td >评论图片:</td>
                            <td colspan="5" class="t1">
                                <?php $photo = json_decode($data["photo"], true);?>
                                <?php foreach($photo as $k=>$v){ ?>
                                    <img  onclick="ti()" style="object-fit: cover;" src="<?php echo $v?>"  height="90" width="90" >
                                <?php }?>
                            </td>
                        </tr>
                        <!--                    <tr>-->
                        <!--                        <td>商品简述：</td>-->
                        <!--                        <td colspan="5">-->
                        <!--                            --><?php //echo $data["sketch"]?>
                        <!--                        </td>-->
                        <!--                    </tr>-->
                        <tr>
                            <td >评论</td>
                            <td colspan="10"><?php echo $data["content"]?> </td>
                        </tr>

                        </tbody>
                    </table>

                </fieldset>
                <div class="bk15"></div>
            <?php } ?>
            <div >

                <!-- 页码 -->
                <div class="page">
                    <div>
                        <span onclick=pagesubmit(<?php echo $page<=1?1:$page-1;?>)>上一页</span>
                        <?php for($i=$pageStart ; $i <= $pagenums; $i++) { ?>
                            <?php if($i == $page){?>
                                <span  class="page-on"  onclick=pagesubmit(<?php echo $i;?>);><?php echo $i;?></span>
                            <?php }else{?>
                                <span onclick=pagesubmit(<?php echo $i;?>)><?php echo $i;?></span>
                            <?php }?>
                        <?php } ?>
                        <span onclick=pagesubmit(<?php echo $page>=$pagenums?$pagenums:$page+1;?>)>下一页</span>
                        <span>共<?php echo $pageCount?>页</span>
                        <input style="width: 40px; text-align: center;" onkeyup="value=value.replace(/[^\d]/g,'')" type="text" id="page_info">
                        <span onclick="pagesubmit($('#page_info').val())"> 跳转</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
</div>
</body>
<script>
    function changeCommentId(commentId)
    {
        $.ajax({
            type : "get",
            url : "index.php?m=hpshop&c=goods&a=CommintShield&pc_hash=<?php echo $_GET["pc_hash"]?>&commentid="+commentId,
            datatype : "JSON",
            success: function (data) {
                if(data == 1)
                {
                    console.log($("#comment_"+commentId).html());
                    switch ($("#comment_"+commentId).html())
                    {
                        case "已屏蔽":
                            $("#comment_"+commentId).html("屏蔽");
                            $("#comment_"+commentId).removeClass("wpb");
                            $("#comment_"+commentId).addClass("pb");break;
                        case "屏蔽":$("#comment_"+commentId).html("已屏蔽");
                            $("#comment_"+commentId).removeClass("pb");
                            $("#comment_"+commentId).addClass("wpb");break;
                    }
                }
            }
        })
    }
    function pagesubmit(page){
        var pageCount =  <?php echo $pageCount?>;
        if(page > pageCount)
        {
            window.alert("页数过大");
            return ;
        }
        else if(page <=0)
        {
            window.alert("页数不能小于0");
            return ;
        }
        $("#page").val(page);
        $("#sbt").submit();
    };
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
</script>
</html>