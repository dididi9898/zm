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
</div>

<form name="searchform" action="" method="get" id="sbt">

<input type="hidden" value="zypoints" name="m">
<input type="hidden" value="zypoints" name="c">
<input type="hidden" value="init_rec" name="a">
<input type="hidden" value="" name="page" id="page">
<div class="explain-col search-form">
<?php echo 'ID'?>  <input type="text" value="<?php echo $_GET['id']?>" class="input-text" name="id">
<input type="submit" value=<?php echo L(search);?> class="button" name="dosubmit" >

<?php if($fatherId != ""){?>
    <input class="button" type="button" onclick="back(this)" name=<?php echo $fatherId?> value="返回">
<?php }?>


</div>
</form>




<form name="myform" id="myform" action="?m=zypoints&c=zypoints&a=del" method="post" onsubmit="checkuid();return false;" >


<div class="table-list">
<table width="100%" cellspacing="0">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>id</strong></th>
            <th align="center"><strong>礼物名称</strong></th>
            <th align="center"><strong>缩略图</strong></th>
            <th align="center"><strong>昵称</strong></th>
            <th align="center"><strong>手机号</strong></th>
            <th align="center"><strong>领取时间</strong></th>
            <th align="center"><strong>操作</strong></th>
		</tr>
	</thead>
	<tbody>

			<?php foreach($infos AS $row) { ?>
			<tr>
			<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $row['id']?>"></td>
			<td align="center"><?php echo $row["id"];?></td>
			<td align="center"><?php echo $row["giftname"];?></td>
			<td align="center"><img src="<?php echo $row["thumb"];?>" height="100" width="100"></td>
            <td align="center"><?php echo $row["nickname"];?></td>
            <td align="center"><?php echo $row["mobile"];?></td>
            <td align="center"><?php echo date("Y-m-d H:i:s", $row["gettime"])?></td>
            <td align="center">
                <!--<a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="edit('<?php echo $row['id']?>')"><?php echo L('编辑')?></a>-->
                <a href="javascript:confirmurl('?m=zypoints&c=zypoints&a=del_user&id=<?php echo $row['id']?>', '<?php echo L('确定删除此物品吗')?>')" class="btn btn-danger btn-sm"><?php echo L('删除')?></a>
            </td>
			</tr>
			<?php }?>
	</tbody>
</table>
    <div>

        <!-- 页码 -->
        <div class="page">
            <div>
                <span onclick=pagesubmit(<?php echo $page-1<=0?1:$page-1;?>)>上一页</span>
                <?php for($i=1 ; $i < $pagenums+1; $i++) { ?>
                    <?php if($i == $page){?>
                        <span  class="page-on"  onclick=pagesubmit(<?php echo $i;?>);><?php echo $i;?></span>
                    <?php }else{?>
                        <span onclick=pagesubmit(<?php echo $i;?>)><?php echo $i;?></span>
                    <?php }?>
                <?php } ?>
                <span onclick=pagesubmit(<?php echo $page+1>=$pagenums?$pagenums:$page+1;?>)>下一页</span>
            </div>
        </div>
    </div>

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
            iframe:"?m=zypoints&c=zypoints&a=edit&id="+id,
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