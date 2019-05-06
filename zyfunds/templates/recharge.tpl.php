<?php 
    defined('IN_ADMIN') or exit('No permission resources.');
    include $this->admin_tpl('header','admin');
?>
<script type="text/javascript">
<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#unit").formValidator({onshow:"请输入数量(资金)",onfocus:"<?php echo L('额度').L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('额度').L('empty')?>"}).regexValidator({regexp:"^(([1-9]{1}\\d*)|([0]{1}))(\\.(\\d){1,2})?$",onerror:"<?php echo L('must_be_price')?>"});
	$("#username").formValidator({onshow:"<?php echo L('input').L('用户手机号')?>",onfocus:"<?php echo L('用户手机号').L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('用户手机号').L('empty')?>"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=zyfunds&c=zyfunds&a=public_checkname_ajax",
		datatype : "html",
		async:'false',
		success : function(data){
			var s = data.indexOf('FALSE');
			
            /*if(data!= '')*/
			if(s == -1)
			{
            	$("#balance").html(data);
                return true;
			}
            else
			{
            	$("#balance").html('');
                return false;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('该手机号未绑定用户')?>",
		onwait : "<?php echo L('checking')?>"
	});
	/*$("#usernote").formValidator({onshow:"<?php echo L('input').'交易备注'?>",onfocus:"<?php echo '内容'.L('empty')?>"}).inputValidator({min:1,max:999,onerror:"<?php echo L('input').'交易备注'?>"});*/
})
//-->
</script>

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>在线充值</em></a>　    
    </div>
</div>



<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zyfunds&c=zyfunds&a=recharge" method="post" id="myform">
<table width="100%" class="table_form">
<tr>
<td  width="120"><?php echo '充值类型'?></td> 
<td><input name="pay_type" value="1" type="radio" id="pay_type" checked> <?php echo L('money')?> 
<!--<input name="pay_type" value="2" type="radio" id="pay_type"> <?php echo L('积分')?>--></td> 
</td>
</tr>
<tr>
<td  width="120"><?php echo L('用户手机号')?></td> 
<td><input type="text" name="username" size="15" value="" id="username"><span id="balance"><span></td>
</tr>
<tr>
<td  width="120"><?php echo '充值额度'?></td> 
<td><input name="pay_unit" value="1" type="radio" checked> <?php echo '增加'?>  <input type="text" name="amount" size="10" value="" id="unit"></td>
</tr>
<!--<tr>
<td  width="120"><?php echo '交易备注'?></td> 
<td><textarea name="usernote"  id="usernote" rows="5" cols="50"></textarea></td>
</tr>-->
</table>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="button" id="dosubmit">
</form>
</div>
</div>
</body>
</html>
<script type="text/javascript">

$(document).ready(function() {
	$("#paymethod input[type='radio']").click( function () {
		if($(this).val()== 0){
			$("#rate").removeClass('hidden');
			$("#fix").addClass('hidden');
			$("#rate input").val('0');
		} else {
			$("#fix").removeClass('hidden');
			$("#rate").addClass('hidden');
			$("#fix input").val('0');
		}	
	});
});
function category_load(obj)
{
	var modelid = $(obj).attr('value');
	$.get('?m=admin&c=position&a=public_category_load&modelid='+modelid,function(data){
			$('#load_catid').html(data);
		  });
}
</script>


