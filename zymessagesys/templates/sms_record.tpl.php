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
<!--消费列表管理-->
<form name="myform" action="?m=zymessagesys&c=messagesys&a=sms_record_del" method="post">
<div class="pad_10">
<div class="table-list">
    <table width="100%" cellspacing="0" class="layui-table">
        <thead>
            <tr>
			<th width="20" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
             <th width="5%" ><?php echo L('ID')?></th>
			 <th width="10%"><?php echo L('管理员')?></th>
			 <th width="50%"><?php echo L('内容')?></th>
			 <th><?php echo L('接收人')?></th>		 
			 <th><?php echo L('ip')?></th>
             <th><?php echo L('发送时间')?></th>
             <th><?php echo L('操作')?></th>
            </tr>
        </thead>
    <tbody>
<!--循坏输出对应的数据-->
<?php 
foreach($info as $r)
{
?>
	<tr>
	<td align="center" width="20"><input type="checkbox" name="id[]" value="<?php echo $r['id']?>"></td>
	<td width=" 5%" align="center"><?php echo $r['id']?></td>
	<td width="10%" align="center"><?php echo $r['admin']?></td>
	<td width="10%" align="center"><?php echo $r['content']?></td>
	<td width="10%" align="center"><?php echo $r['reception']?></td>
	<td width="10%" align="center"><?php echo $r['ip']?></td>
	<td width="10%" align="center"><?php echo date('Y-m-d h:i:s',$r['sendtime']);?></td>
<!--	删除操作-->
	<td width="10%" align="center"><a href="javascript:confirmurl('?m=zymessagesys&c=messagesys&a=sms_record_del&id=<?php echo $r['id'] ?>', '<?php echo L('确定删除此消息')?>')" class="btn btn-sm btn-danger" style="padding:0 10px;"><?php echo L('删除')?></a></td>
	</tr>	
<?php 
}
?>
		
    </tbody>
    </table>
<!--  	下菜单栏-->
	<div class="btn" style="height:50px;line-height:40px;width:100%;text-align:left;color:#444;">
		<a  class="btn btn-sm btn-info" style="padding:0 10px;">消费条数：<?php echo $count?></a>
	</div>				
	
<!--	分页-->
	<div id="pages">
		<?php echo $pages?>
	</div>
</div>
</div>
</form>
</body>
</html>