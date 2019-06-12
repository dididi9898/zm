<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
    <link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/css/layui.css">
    <script src="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/layui.all.js"></script>
    <script type="text/javascript" src="<?php echo APP_PATH?>statics/public/js/ajax.js"></script>
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

<div style="border: 1px solid transparent"></div>
<div class="pad-lr-10">

<form name="searchform" action="" method="get" id="sbt">

<input type="hidden" value="zyorder" name="m">
<input type="hidden" value="order" name="c">
<input type="hidden" value="try_order_list" name="a">
<input type="hidden" value="" name="page" id="page">
<div class="explain-col search-form">
<?php echo '订单编号'?>  <input type="text" value="<?php echo $_GET['ordersn']?>" class="input-text" name="ordersn">
<?php echo '支付方式	'?>
<select name="pay_type">
    <option value="">全部</option>
    <?php foreach(self::$pay_type as $key=>$value ){?>
        <?php if($key == $pay_type) {?>
            <option  selected="selected" value=<?php echo intval($key)?>><?php echo $value?></option>
        <?php }else{?>
            <option  value=<?php echo intval($key)?>><?php echo $value?></option>
        <?php }?>
    <?php }?>
</select>
<?php echo '申请日期'?>
<?php echo form::date('start_addtime',$_GET['start_addtime'])?>
<?php echo L('to')?>
<?php echo form::date('end_addtime',$_GET['end_addtime'])?>
<select name="status">
    <option  value="">商品状态</option>
    <?php foreach(self::$tryStatusType as $key=>$value ){?>
        <?php if($key == $status) {?>
            <option  selected="selected" value=<?php echo intval($key)?>><?php echo $value?></option>
        <?php }else{?>
            <option  value=<?php echo intval($key)?>><?php echo $value?></option>
        <?php }?>
    <?php }?>
</select>

<input type="submit" value=<?php echo L(search);?> class="button" name="dosubmit" >

<?php if($fatherId != ""){?>
    <input class="button" type="button" onclick="back(this)" name=<?php echo $fatherId?> value="返回">
<?php }?>


</div>
</form>




<form name="myform" id="myform" action="?m=zyman&c=zyfunds&a=funs_record_del" method="post" onsubmit="checkuid();return false;" >


<div class="table-list" >
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>id</strong></th>
            <th align="center"><strong>订单编号</strong></th>
            <th align="center"><strong>购买者</strong></th>
            <th align="center"><strong>联系方式</strong></th>
            <th align="center"><strong>商品名称</strong></th>
            <th align="center"><strong>申请时间</strong></th>
            <th align="center"><strong>发货时间</strong></th>
            <th align="center"><strong>完成时间</strong></th>
            <th align="center"><strong>快递单号</strong></th>
            <th align="center"><strong>地址详情</strong></th>
            <th align="center"><strong>备注</strong></th>
            <th align="center"><strong>状态</strong></th>
            <th align="center"><strong>操作</strong></th>
		</tr>
	</thead>
	<tbody>

			<?php $n=1;if(is_array($info)) foreach($info AS $row) { ?>
			<tr>
			<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $row['order_id']?>"></td>
			<td align="center"><?php echo $n;?></td>
			<td align="center"><?php echo $row["ordersn"];?></td>
			<td align="center"><?php echo $row["nickname"];?></td>
			<td align="center"><?php echo $row["mobile"];?></td>
			<td align="center">
                查看商品
                <a href="javascript:void(0);" onclick="view_shop('<?php echo $row['order_id']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>
            </td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["addtime"])?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["deltime"])?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["overtime"])?></td>
            <td align="center"><?php echo $row["logistics_order"];?></td>

			<td align="center">
                <?php echo $row['province'].$row["city"]?>
                <a href="javascript:void(0);" onclick="view_address('<?php echo $row['order_id']?>')"><img src="<?php echo IMG_PATH?>admin_img/detail.png"></a>
            </td>
            <td align="center"><?php echo $row["usernote"];?></td>
            <td align="center"><?php echo self::$tryStatusType[$row["status"]];?></td>
            <td align="center">
                <?php if($row["status"] == "1"){?>
                    <span class="btn btn-info btn-sm"><?php echo L('未付款')?></span>
                <?php }elseif($row["status"] == "11"){?>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="deliver('<?php echo $row['ordersn']?>')"><?php echo L('发货')?></a>
                <?php }elseif($row["status"] == '3'){?>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="deliver('<?php echo $row['ordersn']?>')"><?php echo L('修改快递单')?></a>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="check('<?php echo $row['ordersn']?>')"><?php echo L('快递详情')?></a>
                <?php }elseif($row["status"] == '4' || $row["status"] == '5'){?>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="check('<?php echo $row['ordersn']?>')"><?php echo L('快递详情')?></a>
                <?php }elseif($row["status"] == "7"){?>
                    <span class="btn btn-info btn-sm" onclick="checkTry('<?php echo $row['ordersn']?>')"><?php echo L('通过')?></span>
                    <span class="btn btn-danger btn-sm" onclick="checkNoTry('<?php echo $row['ordersn']?>')"><?php echo L('不通过')?></span>
                <?php }elseif($row["status"] == '8' || $row["status"] == '10'){?>
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="check('<?php echo $row['ordersn']?>')"><?php echo L('快递详情')?></a>
                    <a href="javascript:void(0);" class="btn btn-danger btn-sm" onclick="checkAfterSale('<?php echo $row['order_id']?>')"><?php echo L('售后商品')?></a>
                <?php }elseif($row["status"] == '12'){?>
                    <span class="btn btn-danger btn-sm"><?php echo L('未通过')?></span>
                <?php }?>
                <a href="javascript:confirmurl('?m=zyorder&c=order&a=dropOrder&order_id=<?php echo $row['order_id']?>', '<?php echo L('确定删除此订单吗，删除后无法恢复。')?>')" class="btn btn-danger btn-sm"><?php echo L('删除')?></a>
            </td>
			</tr>
			<?php $n++;}unset($n); ?>

	</tbody>
</table>
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



<script type="text/javascript">
<!--
;
! function () {
    var layer = layui.layer,
        form = layui.form,
        $ = layui.jquery,
        upload = layui.upload,
        table = layui.table;
}();
function checkuid() {
	var ids='';
	$("input[name='id[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({content:'<?php echo L('请先选择记录')?>',lock:true,width:'200',height:'50',time:1.5},function(){});
		return false;
	} else {
		myform.submit();
	}
}

function view_shop(id) {
    window.top.art.dialog({
            id:'view_shop',
            iframe:'?m=zyorder&c=order&a=showShop&order_id='+id,
            title:'商品信息',
            width:'700',
            height:'350',
            lock:true
        },
        function(){
            window.top.art.dialog({id:'view_shop'}).close()
        });
    void(0);
}
function view_address(id) {
    window.top.art.dialog({
            id:'view_address',
            iframe:'?m=zyorder&c=order&a=showAddress&order_id='+id,
            title:'地址信息',
            width:'500',
            height:'250',
            lock:true
        },
        function(){
            window.top.art.dialog({id:'view_address'}).close()
        });
    void(0);
}
function deliver(id)
{
    window.top.art.dialog({
        id:"deliver",
        iframe:"?m=zyorder&c=order&a=addEX&ordersn="+id,
            title:'填写快递信息',
            width:'500',
            height:'250',
            lock:true
        },
        function(){
            var d = window.top.art.dialog({id:'deliver'}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
            return false;
        },
        function(){
            window.top.art.dialog({id:'deliver'}).close();
        });
    void(0);

}
function checkTry(id)
{
    layer.confirm("确定审核通过吗",{icon:3, title:"提示"},function(index) {
        aj.post("index.php?m=zyorder&c=order&a=passTryAjax&pc_hash=<?php echo $_GET["pc_hash"]?>", {ordersn: id}, function (data) {
            if (data.code == '1') {
                window.location.reload();
            }
        });
        layer.close(index);
    });
}
function checkNoTry(id)
{
    layer.confirm("确定审核不通过吗",{icon:3, title:"提示"},function(index) {
        aj.post("index.php?m=zyorder&c=order&a=notPassTryAjax&pc_hash=<?php echo $_GET["pc_hash"]?>", {ordersn: id}, function (data) {
            if (data.code == '1') {
                window.location.reload();
            }
        });
        layer.close(index);
    });
}
function check(id)
{
    window.top.art.dialog({
            id:"check",
            iframe:"?m=zyorder&c=order&a=checkEX&ordersn="+id,
            title:'快递信息',
            width:'1000',
            height:'800',
            lock:true
        },
        function(){
            window.top.art.dialog({id:'check'}).close();
        });
    void(0);
}
function checkAfterSale(id)
{
    window.top.art.dialog({
            id:"checkAfterSale",
            iframe:"?m=zyorder&c=order&a=checkAfterSale&XDEBUG_SESSION_START=18804&order_id="+id,
            title:'售后信息',
            width:'800',
            height:'500',
            lock:true
        },

        function(){
            window.top.art.dialog({id:'checkAfterSale'}).close();
        });
    void(0);
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
//-->
</script>

