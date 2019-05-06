<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
    $show_header = 1;
	include $this->admin_tpl('header','admin');
?>
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
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a class="add fb" href="javascript:window.top.art.dialog({id:'add',iframe:'?m=zycoupon&c=zycoupon&a=add',title:'添加优惠券', width:'800', height:'600', lock:true}, function(){var d = window.top.art.dialog({id:'add'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;},function(){window.top.art.dialog({id:'add'}).close()});void(0);">
            <em>添加优惠券</em></a>
    </div>
</div>

<form name="searchform" action="" method="get" id="sbt">

<input type="hidden" value="zycoupon" name="m">
<input type="hidden" value="zycoupon" name="c">
<input type="hidden" value="init" name="a">
<input type="hidden" value="" name="page" id="page">
<div class="explain-col search-form">
<?php echo '优惠券名称'?>  <input type="text" value="<?php echo $_GET['couponname']?>" class="input-text" name="couponname">
<?php echo '修改日期'?>
<?php echo form::date('begintime',$_GET['begintime'])?>
<?php echo L('to')?>
<?php echo form::date('endtime',$_GET['endtime'])?>
<select name="type">
    <option  value="">商品类型类型</option>
    <?php foreach($type as $row ){?>
        <?php if($row["id"] == $_GET['type']) {?>
            <option  selected="selected" value=<?php echo $row["id"]?>><?php echo $row["cate_name"]?></option>
        <?php }else{?>
            <option  value=<?php echo $row["id"]?>><?php echo $row["cate_name"]?></option>
        <?php }?>
    <?php }?>
</select>
<select name="status">
        <option value="">优惠卷状态</option>
        <option value="1" <?php if($_GET['status'] == "1") echo "selected"?>>有效</option>
        <option value="2" <?php if($_GET['status'] == "2") echo "selected"?>>无效</option>
        <option value="3" <?php if($_GET['status'] == "3") echo "selected"?>>已过期</option>
</select>

<input type="submit" value=<?php echo L(search);?> class="button" name="dosubmit" >

<?php if($fatherId != ""){?>
    <input class="button" type="button" onclick="back(this)" name=<?php echo $fatherId?> value="返回">
<?php }?>


</div>
</form>




<form name="myform" id="myform" action="?m=zycoupon&c=zycoupon&a=del" method="post" onsubmit="checkuid();return false;" >


<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>id</strong></th>
            <th align="center"><strong>优惠卷名称</strong></th>
            <th align="center"><strong>满</strong></th>
            <th align="center"><strong>减</strong></th>
            <th align="center"><strong>开始时间</strong></th>
            <th align="center"><strong>结束时间</strong></th>
            <th align="center"><strong>领取后有效天数</strong></th>
            <th align="center"><strong>限制商品类型</strong></th>
            <th align="center"><strong>限制满减类型</strong></th>
            <th align="center"><strong>优惠卷总数量</strong></th>
            <th align="center"><strong>优惠卷已使用数量</strong></th>
            <th align="center"><strong>优惠卷领取数量</strong></th>
            <th align="center"><strong>修改时间</strong></th>
            <th align="center"><strong>优惠卷时间方式设置</strong></th>
            <th align="center"><strong>状态</strong></th>
            <th align="center"><strong>操作</strong></th>
		</tr>
	</thead>
	<tbody>

			<?php foreach($infos AS $row) { ?>
			<tr>
			<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $row['id']?>"></td>
			<td align="center"><?php echo $row["id"];?></td>
			<td align="center"><?php echo $row["couponname"];?></td>
			<td align="center"><?php echo $row["full"];?></td>
			<td align="center"><?php echo $row["minus"];?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["begintime"])?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["endtime"])?></td>
            <td align="center"><?php echo $row["days"];?></td>
            <td align="center">
                <?php if($row["limittype"]==0){?>
                    <span><?php echo '全场通用';?></span>
                <?php }else{ ?>
                    <?php foreach($type as $r ){?>
                        <?php if($r["id"] == $row["limittype"]) {?>
                            <span>仅限<?php echo $r["cate_name"];?>类别</span>
                        <?php }?>
                    <?php }?>
                <?php } ?>
            </td>
                <td align="center">
                    <?php if($row["type"]==0){?>
                        <span><?php echo '无门槛';?></span>
                    <?php }else if($row["type"]==1){?>
                        <span><?php echo '满减';?></span>
                    <?php }else{ ?>
                        <span><?php echo '叠加满减';?></span>
                    <?php } ?>
            </td>
            <td align="center"><?php echo $row["totalnum"];?></td>
            <td align="center"><?php echo $row["usednum"];?></td>
            <td align="center"><?php echo $row["takenum"];?></td>
			<td align="center"><?php echo date("Y-m-d H:i:s", $row["updatetime"])?></td>
            <td align="center">
                <?php if($row["vaild_type"]==1){?>
                    <a alt="固定时间段X-X-X x:x:x"><?php echo '固定时间';?></a>
                <?php }else if($row["vaild_type"]==2){?>
                    <a alt="N天有效期"><?php echo '相对时间';?></a>
                <?php } ?>
            </td>
            <td align="center">
                <?php if($row["status"]==1){?>
                    <span style="color: green"><?php echo '有效';?></span>
                <?php }else if($row["status"]==2){?>
                    <span style="color: red"><?php echo '无效';?></span>
                <?php }else{ ?>
                    <span style="color: grey"><?php echo '已过期';?></span>
                <?php } ?>
            </td>
            <td align="center">
                <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="edit('<?php echo $row['id']?>')"><?php echo L('编辑')?></a>
                <a href="javascript:confirmurl('?m=zycoupon&c=zycoupon&a=del&id=<?php echo $row['id']?>', '<?php echo L('确定删除此优惠卷吗')?>')" class="btn btn-danger btn-sm"><?php echo L('删除')?></a>
            </td>
			</tr>
			<?php }?>
	</tbody>
</table>
    <div id="pages"><?php echo $pages?></div>
</div>



<script type="text/javascript">
<!--
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
//function back(data) {
//    window.location.href="?m=zymall&c=money&a=member_manage_view&id="+data.name+"&pc_hash="+'<?php //echo $_SESSION['pc_hash'];?>//';
//}
function edit(id){
    window.top.art.dialog({
            id:'edit',
            iframe:"?m=zycoupon&c=zycoupon&a=edit&id="+id,
            title:'基本信息',
            width:'800',
            height:'600',
            lock:true
        },
        function () {
            var d = window.top.art.dialog({id:'edit'}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
            return false;
        },
        function(){
            window.top.art.dialog({id:'edit'}).close();
        });
    void(0);
}
function pagesubmit(page){
    $("#page").val(page);
    $("#sbt").submit();
};
//-->
</script>