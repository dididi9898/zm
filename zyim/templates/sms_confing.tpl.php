<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zymessagesys/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zymessagesys/layui/layui.all.js"></script>


<!-- 样式库 -->
<style type="text/css">
    .clear{ clear: both; }
    .btn:hover{text-decoration: none;}
    .btn {display: inline-block; height: 34px; line-height: 34px; padding: 0 14px; background-color: #009688; color: #fff; white-space: nowrap; text-align: center; font-size: 14px; border: none; border-radius: 2px; cursor: pointer; transition: all .3s; -webkit-transition: all .3s; box-sizing: border-box;}
    .btn:hover {opacity: .8;color: #fff;}
    .btn-primary {
        background-color: #fff;
        border: 1px solid #C9C9C9;
        color: #555;
    }
    .btn-warm {
        background-color: #FFB800;
    }
    .btn-danger {
        background-color: #FF5722;
    }
    .btn-info {
        background-color: #1E9FFF;
    }


    .btn-sm {
        height: 30px;
        line-height: 30px;
        padding: 0 10px;
        font-size: 12px;
    }
    .btn-xs {
        height: 22px;
        line-height: 22px;
        padding: 0 5px;
        font-size: 12px;
    }
</style>


<form action="?m=zymessagesys&c=messagesys&a=sms_confing" method="post" id="myform">
<div class="pad-10">
<div class="col-tab">
<!-- 头标签 -->
<ul class="tabBut cu-li">
	<li id="tab_setting_1" class="on" onclick="SwapTab('setting','on','',5,1);"><?php echo '邮箱配置'?></li>
	<li id="tab_setting_2" onclick="SwapTab('setting','on','',5,2);"><?php echo L('短信配置')?></li>
</ul>
<div id="div_setting_1" class="contentList pad-10">

<table width="100%"  class="table_form" >
	<tr>
		<th>是否开启邮箱接口</th>
		<td>
			<label><input name="setconfig['mail_off']" type="radio" <?php if($mail_off==0){ ?>checked="checked"<?php }?> value="0"/>开</label> 
			<label><input name="setconfig['mail_off']" type="radio" <?php if($mail_off==1){ ?>checked="checked"<?php }?> value="1"/>关</label>
		</td>
	</tr>
	<tr>
		<th>邮件服务器</th>
		<td><input type="text" name="setconfig['mail_host']" class="input-text" value="<?php echo $mail_host ?>" style="width:300px;"></td>
	</tr>
	<tr>
		<th>邮件发送端口</th>
		<td><input type="text" name="setconfig['mail_port']" class="input-text" value="<?php echo $mail_port ?>" style="width:300px;"></td>
	</tr>
	<tr>
		<th>发送协议</th>
		<td><input type="text" name="setconfig['mail_secure']" class="input-text" value="<?php echo $mail_secure ?>" style="width:300px;"></td>
	</tr>
	<tr>
		<th>验证用户名</th>
		<td><input type="text" name="setconfig['mail_username']" class="input-text" value="<?php echo $mail_username ?>" style="width:300px;"></td>
	</tr>
	<tr>
		<th>验证密码</th>
		<td><input type="password" name="setconfig['mail_pwd']" class="input-text" value="<?php echo $mail_pwd ?>" style="width:300px;"></td>
	</tr>
	<tr>
		<th>发件人邮箱</th>
		<td><input type="text" name="setconfig['mail_set_from']" class="input-text" value="<?php echo $mail_set_from ?>" style="width:300px;"></td>
	</tr>


	

	<tr>
		<th>邮件测试</th>
		<td class="y-bg"><input type="text" class="input-text" name="mail_to" id="mail_to" size="30" value=""/> <input type="button"  class="btn btn-sm" style="padding: 0 10px;" onClick="javascript:test_mail();" value="<?php echo L('测试发送')?>"></td>
	</tr>

</table>
</div>



<div id="div_setting_2" class="contentList pad-10 hidden">
	<table width="100%"  class="table_form">

	<tr>
		<th>是否开启阿里云短信接口</th>
		<td>
			<label><input name="setconfig['alisms_off']" type="radio" <?php if($alisms_off==0){ ?>checked="checked"<?php }?> value="0"/>开</label> 
			<label><input name="setconfig['alisms_off']" type="radio" <?php if($alisms_off==1){ ?>checked="checked"<?php }?> value="1"/>关</label>
		</td>
	</tr>

	<tr>
		<th>用户id</th>
		<td><input type="text" name="setconfig['alisms_uid']" class="input-text" value="<?php echo $alisms_uid ?>" style="width:300px;"></td>
	</tr>

	<tr>
		<th>产品帐号</th>
		<td><input type="text" name="setconfig['alisms_pid']" class="input-text" value="<?php echo $alisms_pid ?>" style="width:300px;"></td>
	</tr>

	<tr>
		<th>产品密码</th>
		<td><input type="password" name="setconfig['alisms_passwd']" class="input-text" value="<?php echo $alisms_passwd ?>" style="width:300px;"></td>
	</tr>

	
	</table>
</div>





<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo '提交'?>" class="btn btn-sm" style="padding: 0 10px;">
</div>
</div>
</form>
</body>
<script type="text/javascript">

function SwapTab(name,cls_show,cls_hide,cnt,cur){
    for(i=1;i<=cnt;i++){
        if(i==cur){
             $('#div_'+name+'_'+i).show();
             $('#tab_'+name+'_'+i).attr('class',cls_show);
        }else{
             $('#div_'+name+'_'+i).hide();
             $('#tab_'+name+'_'+i).attr('class',cls_hide);
        }
    }
}

function showsmtp(obj,hiddenid){
    hiddenid = hiddenid ? hiddenid : 'smtpcfg';
    var status = $(obj).val();
    if(status == 1) $("#"+hiddenid).show();
    else  $("#"+hiddenid).hide();
}
function test_mail() {
	var mail_title = '测试邮件';		//标题
	var mail_content = '欢迎使用邮件发送系统';	//内容
	var mail_address = $('input[name=mail_to]').val();	//接收人邮箱
	if (!mail_address) {
		layer.msg('请填写收件人邮箱');
	}
		
	
    $.ajax({
      url: "<?php echo APP_PATH?>index.php?m=zymessagesys&c=messagesys_api&a=pub_sendemail",
      data: {
        title: mail_title,
        content: mail_content,
        address: mail_address,
      },        //规定要发送到服务器的数据。
      type:'POST',       //规定请求的类型（GET 或 POST）。
      dataType:'json',      //预期的服务器响应的数据类型。
      timeout: '3000',      //设置本地的请求超时时间（以毫秒计）。
      success: function( result,status,xhr ) {
        layer.msg(result.message);
      }        //当请求成功时运行的函数。
    });


}

</script>
</html>