<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
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

<div class="pad-lr-10">
<form name="searchform" action="" method="get" >
<input type="hidden" value="zymessagesys" name="m">
<input type="hidden" value="messagesys" name="c">
<input type="hidden" value="message_manage" name="a">
<input type="hidden" value="<?php echo $_GET['menuid']?>" name="menuid">
<div class="explain-col search-form">
<select name="type" >
    <option value="">请选择</option>
    <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>用户名</option>
    <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>用户ID</option>
    <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>昵称</option>
    <option value="4" <?php if ($_GET['type']==4) {?>selected<?php }?>>手机号</option>
</select>
<input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">

<select name="type2" >
    <option value="">请选择</option>
    <option value="1" <?php if ($_GET['type2']==1) {?>selected<?php }?>>标题</option>
</select>
<input type="text" value="<?php echo $_GET['q2']?>" class="input-text" name="q2">

<select name="status">
	<option value="">全部类型</option>
    <option value="1" <?php if ($_GET['status']==1) {?>selected<?php }?>>单发消息</option>
    <option value="2" <?php if ($_GET['status']==2) {?>selected<?php }?>>群发消息</option>
</select>

发布日期  <?php echo form::date('start_addtime',$_GET['start_addtime'])?><?php echo L('to')?>   <?php echo form::date('end_addtime',$_GET['end_addtime'])?>

<input type="submit" value="<?php echo L('search')?>" class="btn btn-sm" style="padding: 0 10px;" name="dosubmit">
</div>
</form>
<form name="myform" id="myform" action="?m=zymessagesys&c=messagesys&a=message_manage_del" method="post" >
<div class="table-list">
<table width="100%" cellspacing="0"  class="layui-table">
	<thead>
		<tr>
			<th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
            <th align="center"><strong>用户ID</strong></th>
            <th align="center"><strong>用户帐号</strong></th>
            <th align="center"><strong>用户昵称</strong></th>
            <th align="center"><strong>手机号码</strong></th>
            <th align="center"><strong>标题</strong></th>
			<th align="center"><strong>内容</strong></th>
            <th align="center"><strong>发布日期</strong></th>
            <th align="center"><strong>发件人</strong></th>
            <th align="center"><strong>类型</strong></th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($info)){
	foreach($info as $info){
		?>
	<tr>
		<td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $info['id']?>"></td>
        <td align="center"><?php echo $info['userid']?></td>
        <td align="center"><?php echo $info['username']?></td>
        <td align="center"><?php echo $info['nickname']?></td>
        <td align="center"><?php echo $info['mobile']?></td>
        <td align="center"><a target="_blank" href="<?php echo $info['url']?>"><?php echo $info['title']?></a></td>
        <td align="center" title="<?php echo $info['content']?>"><?php echo str_cut(strip_tags($info['content']), 150,'...')?></td>
		<td align="center"><?php echo date('Y-m-d H:i:s',$info['addtime']);?></td>
        <td align="center"><?php echo $info['sendname']?></td>
        <td align="center">
		<?php if($info['status']==1){?>
        	<font color="#1cbb9b">单发</font>
        <?php }?>
		<?php if($info['status']==2){?>
        	<font color="#16a0d4">群发</font>
        <?php }?>
        </td>
        </tr>
	<?php
	}
}
?>
</tbody>
</table>
</div>

<div class="btn" style="height:50px;line-height:40px;width:100%;text-align:left;color:#444;"><label for="check_box"><?php echo L('selected_all')?>/取消</label>
<input type="submit" style="padding:0 10px;" class="btn btn-sm btn-danger" name="dosubmit" onClick="document.myform.action='?m=zymessagesys&c=messagesys&a=message_manage_del'" value="批量删除"/>
</div> 
<div id="pages"><?php echo $pages?></div>

</form>
</div>




</body>
</html>
