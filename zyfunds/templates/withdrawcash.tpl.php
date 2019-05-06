<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>
<div class="pad-lr-10">
    <form name="searchform" action="" method="get" >
        <input type="hidden" value="zyfunds" name="m">
        <input type="hidden" value="zyfunds" name="c">
        <input type="hidden" value="withdrawcash" name="a">
        <div class="explain-col search-form">
            <select name="types" >
                <option value=""><?php echo L('please_select')?></option>
                <option value="1" <?php if ($_GET['types']==1) {?>selected<?php }?>>订单号</option>
                <option value="2" <?php if ($_GET['types']==2) {?>selected<?php }?>>用户手机</option>
            </select>
            <input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">
            <select name="type" >
                <option value=""><?php echo L('please_select')?></option>
                <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>支付宝</option>
                <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>微信</option>
                <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>银行卡</option>
            </select>
            提现金额范围
            <input type="text" value="<?php echo $_GET['s']?>" class="input-text" name="s">-
            <input type="text" value="<?php echo $_GET['l']?>" class="input-text" name="l">
            <?php echo 提现日期?><?php echo form::date('start_addtime',$_GET['start_addtime'])?>-<?php echo form::date('end_addtime',$_GET['end_addtime'])?>
            <select name="status">
                <option value="" selected="">全部状态</option>
                <option value="1" <?php if ($_GET['status']==1) {?>selected<?php }?>>已审核</option>
                <option value="2" <?php if ($_GET['status']==2) {?>selected<?php }?>>未审核</option>
                <option value="3" <?php if ($_GET['status']==3) {?>selected<?php }?>>错误退回</option>
            </select>
            <input type="submit" value="搜索" class="layui-btn layui-btn-success layui-btn-sm">
        </div>
    </form>

<form name="myform" id="myform" action="?m=zyfunds&c=zyfunds&a=wco_del" method="post" onsubmit="checkuid();return false;" >
<div class="table-list">
<table width="100%" cellspacing="0" class="layui-table">
	<thead>
		<tr>
			<th width="35" align="center">
                <input type="checkbox" value="" id="check_box" onclick="selectall('id[]');">
            </th>
            <th align="center"><strong>交易订单号</strong></th>
            <th align="center"><strong>用户名</strong></th>
            <th align="center"><strong>用户昵称</strong></th>
            <th align="center"><strong>手机号码</strong></th>
            <th align="center"><strong>提现方式</strong></th>
            <th align="center"><strong>提现账户</strong></th>
            <th align="center"><strong>提现金额</strong></th>
			<th align="center"><strong>提现日期</strong></th>
			<th align="center"><strong>状态</strong></th>
            <th align="center"><strong>操作</strong></th>
		</tr>
	</thead>
<tbody>
<?php
if(is_array($info)){
	foreach($info as $info){
        $type = [1=>'支付宝',2=>'微信',3=>'银行卡']
?>
	<tr>
		<td align="center" width="35">
            <input type="checkbox" name="id[]" value="<?php echo $info['id']?>" />
        </td>
        <td align="center"><?php echo $info['trade_sn']?></td>
        <td align="center"><?php echo $info['username']?></td>
        <td align="center"><?php echo $info['nickname']?></td>
        <td align="center"><?php echo $info['phone']?></td>
        <td align="center"><?php echo $type[$info['type']]?></td>
        <td align="center"><?php echo $info['account']?></td>
        <td align="center"><?php echo "￥".$info['amount']?></td>
        <td align="center"><?php echo date('Y-m-d H:i:s',$info['addtime'])?></td>
        <td align="center">
            <?php if($info['status'] =='0'){?>
                <span style="color:#1cbb9b;" >已审核</span>
            <?php }elseif($info['status'] =='1'){?>
                <span style="color:#f40;" >待审核</span>
            <?php }elseif($info['status'] =='2'){?>
                <span style="color:#55acee;" >退回</span>
            <?php }elseif($info['status'] =='3'){?>
                <span style="color:#cc0000;" >支付失败</span>
            <?php }?>
        </td>
		<td align="center">
            <?php if($info['status'] =='0'){?>
                <button type="button" class="layui-btn layui-btn-disabled layui-btn-sm">已审核</button>
            <?php }elseif($info['status'] =='1'){?>
                <a href="?m=zyfunds&c=zyfunds&a=repass&id=<?php echo $info['id']?>" onClick="return confirm('确认通过?')" class="layui-btn layui-btn-normal layui-btn-sm">审核</a>
                <a href="javascript:;"  onclick="edit('<?php echo $info['id']?>')" class="layui-btn layui-btn-danger layui-btn-sm">退回</a>
            <?php }elseif($info['status'] =='2'){?>
                <button type='button' class="layui-btn layui-btn-disabled layui-btn-sm">已退回</button>
                <button type='button' class="layui-btn layui-btn-success layui-btn-sm" onclick="view('<?php echo $info['id']?>')">查看退回原因</button>
            <?php }elseif($info['status'] =='3'){?>
                <button type="button" class="layui-btn layui-btn-disabled layui-btn-sm">支付失败</button>
            <?php }?>
        </td>
        </tr>
	<?php
	}} ?>
</tbody>
</table>
</div>
<div class="btn"> <label for="check_box"><?php echo L('selected_all')?>/<?php echo L('cancel')?></label>
<!--<input type="submit" class="button" name="dosubmit" onClick="document.myform.action='?m=zyfunds&c=zyfunds&a=wco_del'" value="批量删除" />-->
<input type="submit" class="button" name="dosubmit" value="批量删除" onclick="return confirm('<?php echo L('确定删除')?>')"/></div>
<div id="pages"><?php echo $pages?></div>
</form>
</div>

<script type="text/javascript">
function checkuid() {
    var ids = '';
    $("input[name='id[]']:checked").each(function (i, n) {
        ids += $(n).val() + ',';
    });
    if (ids == '') {
        window.top.art.dialog({
            content: '<?php echo L('请先选择记录')?>',
            lock: true,
            width: '200',
            height: '50',
            time: 1.5
        }, function () {
        });
        return false;
    } else {
        myform.submit();
    }
}

function edit(id)
{
    window.top.art.dialog(
        {
            id:'edit',
            iframe:'?m=zyfunds&c=zyfunds&a=withdrawcashedit&id='+id,
            title:'填写驳回理由',
            width:'800',
            height:'500',
            lock:true
        },
        function()
        {
            var d = window.top.art.dialog({id:'edit'}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
            return false;
        },
        function()
        {
            window.top.art.dialog({id:'edit'}).close()
        });
    void(0);
}

function view(id)
{
    window.top.art.dialog(
        {
            id:'view',
            iframe:'?m=zyfunds&c=zyfunds&a=withdrawcashview&id='+id,
            title:'查阅驳回理由',
            width:'800',
            height:'500',
            lock:true
        },
        function()
        {
            var d = window.top.art.dialog({id:'view'}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
            return false;
        },
        function()
        {
            window.top.art.dialog({id:'view'}).close()
        });
    void(0);
}

</script>

</body>
</html>
