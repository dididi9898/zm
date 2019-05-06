<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>

<div class="pad_10">
	<div class="table-list">
		<form name="searchform" action="" method="get" >
			<input type="hidden" value="zyfunds" name="m">
			<input type="hidden" value="zyfunds" name="c">
			<input type="hidden" value="rechargelist" name="a">

			<div class="explain-col search-form">
				<select name="type">
					<option value="" selected="">全部状态</option>
					<option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>支付单号</option>
					<option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>用户手机</option>
				</select>
				<input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">
				订单时间<?php echo form::date('start_addtime',$start_addtime,1)?><?php echo L('to')?>   <?php echo form::date('end_addtime',$end_addtime,1)?>
				<input type="submit" value="搜索" class="layui-btn layui-btn-success layui-btn-sm">
			</div>
		</form>
		<form name="myform" id="myform" method="post" action="index.php?m=zyfunds&c=zyfunds&a=recharge_del" onsubmit="checkuid();return false;" >
			<table width="100%" cellspacing="0" class="layui-table">
				<thead>
					<tr>
						<th width="35" align="center">
							<input type="checkbox" value="" id="check_box" onclick="selectall('id[]');">
						</th>
						<th width="8%"><strong>交易订单号</strong></th>
						<th width="9%"><strong>用户名</strong></th>
						<th width="16%"><strong>昵称</strong></th>
						<th width="13%"><strong>手机</strong></th>
						<th width="11%"><strong>支付方式</strong></th>
						<th width="11%"><strong>充值金额</strong></th>
						<th width="10%"><strong>状态</strong></th>
						<th width="11%"><strong>时间</strong></th>
						<th width="10%"><strong>管理操作</strong></th>
					</tr>
				</thead>
			<tbody>
				<?php
				if(is_array($infos)){
					foreach($infos as $info){
						$type = [1=>'支付宝',2=>'微信'];
				?>
				<tr>
					<td align="center" width="35">
						<input type="checkbox" name="id[]" value="<?php echo $info['id']?>">
					</td>
					<td width="8%" align="center"><?php echo $info['trade_sn']?></td>
					<td width="9%" align="center"><?php echo $info['username']?></td>
					<td width="16%" align="center"><?php echo $info['nickname']?></td>
					<td width="13%" align="center"><?php echo $info['phone']?></td>
					<td width="11%" align="center"><?php echo $type[$info['type']]?></td>
					<td width="11%" align="center"><?php echo "￥".$info['amount']?></td>
					<td width="10%" align="center">
						<?php
						if($info['status']==0){
							echo '<span style="color:#1cbb9b;">交易成功</span>';
						}elseif($info['status']==1){
							echo '<span style="color:#f40;">未支付</span>';
						}
						?>
					</td>
					<td width="11%" align="center"><?php echo date('Y-m-d H:i:s',$info['addtime'])?></td>
					<td width="10%" align="center">
						<a href="javascript:confirmurl('?m=zyfunds&c=zyfunds&a=recharge_del&id=<?php echo $info['id']?>','确定删除此记录'"
						   class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
					</td>
				</tr>
				<?php }} ?>
			</tbody>
			</table>
			<div class="btn" style="height:40px;line-height:40px;width:100%;text-align:left;">
				<label for="check_box">全选/取消</label>
				<input type="submit" class="button layui-btn layui-btn-danger layui-btn-sm" name="dosubmit" value="批量删除"
					   onclick="return confirm('确定删除')" style="padding:0 18px;" /></div>
			<div id="pages"><?php echo $pages?></div>
		</form>
	</div>
</div>
</body>
<script>
function checkuid() {
	var ids='';
	$("input[name='id[]']:checked").each(function(i, n){
		ids += $(n).val() + ',';
	});
	if(ids=='') {
		window.top.art.dialog({
				content:'请先选择记录',
				lock:true,
				width:'200',
				height:'50',
				time:1.5
			},
			function(){});
		return false;
	} else {
		myform.submit();
	}
}
</script>
</html>