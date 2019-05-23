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


<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<script type="text/javascript">

<!--
$(function(){
	$.formValidator.initConfig({autotip:true,formid:"myform",onerror:function(msg){}});
	$("#title").formValidator({onshow:"请输入标题",onfocus:"标题不能为空"}).inputValidator({min:3,onerror:"标题不能为空"});
	$("#content").formValidator({onshow:"请输入内容",onfocus:"内容不能为空"}).inputValidator({min:3,onerror:"内容不能为空"});
	$("#userid").formValidator({onshow:"请输入用户id",onfocus:"用户id不能为空"}).inputValidator({min:1,onerror:"用户id不能为空"});
	/* $("#mobile").formValidator({onshow:"请输入手机号",onfocus:"手机号不能为空"}).inputValidator({min:1,max:999,onerror:"手机号不能为空"}).ajaxValidator({
	    type : "get",
		url : "",
		data :"m=zymember&c=verify&a=public_checkmobile_ajax",
		datatype : "html",
		async:'false',
		success : function(data){	
            if(data==1)
			{
                return false;
			}
            else
			{
                return true;
			}
		},
		buttons: $("#dosubmit"),
		onerror : "<?php echo L('用户不存在')?>",
		onwait : "<?php echo L('正在查询')?>"
	}); */
	
});
//-->
</script>



<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>单发短消息</em></a>　    
    </div>
</div>



<div class="pad-10">
<div class="common-form">
<form name="myform" action="?m=zymessagesys&c=messagesys&a=message_manage_adddf" method="post" id="myform">
<table width="100%" class="table_form">
<tr>
<td width="120"><?php echo '发送到：'?></td> 
<td><input type="text" name="userid" id="userid" size="30" class="input-text"></td> 
</tr>

<tr>
<td width="120"><?php echo '标题：'?></td> 
<td><input type="text" name="title" id="title" size="40" class="input-text"></td> 
</tr>

<tr>
<td width="120"><?php echo '消息链接：'?></td> 
<td><input type="text" name="url" id="url" size="50" class="input-text"></td> 
</tr>

<tr>
<td width="120"><?php echo '内容：'?></td> 
<td><textarea name="content" id="content" cols="50" rows="6"></textarea></td> 
</tr>

<tr>

</tr>
</table>
<div class="bk15"></div>
<input name="dosubmit" type="submit" value="<?php echo L('submit')?>" class="btn btn-sm" style="padding: 0 10px;" id="dosubmit">
</form>
</div>
</body>
</html>
