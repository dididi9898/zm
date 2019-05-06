<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header','admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>

<div class="pad-lr-10">
    <form name="searchform" action="" method="get" >
        <input type="hidden" value="zyfunds" name="m">
        <input type="hidden" value="zyfunds" name="c">
        <input type="hidden" value="zhmanage" name="a">

        <div class="explain-col search-form">
            <select name="type" >
                <option value="">请选择</option>
                <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>用户名</option>
                <option value="2" <?php if ($_GET['type']==2) {?>selected<?php }?>>手机号</option>
                <option value="3" <?php if ($_GET['type']==3) {?>selected<?php }?>>账号</option>
            </select>
            <input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">
            <?php echo 提现日期?>  <?php echo form::date('start_addtime',$_GET['start_addtime'],1)?>-
            <?php echo form::date('end_addtime',$_GET['end_addtime'],1)?>
            <input type="submit" value="搜索" class="layui-btn layui-btn-success layui-btn-sm">
        </div>
    </form>

    <form name="myform" id="myform" method="post" action="index.php?m=zyfunds&c=zyfunds&a=zhmanagedel" onsubmit="checkuid();return false;" >
        <div class="table-list">
        <table width="100%" cellspacing="0" class="layui-table">
            <thead>
                <tr>
                    <th width="35" align="center">
                        <input type="checkbox" value="" id="check_box" onclick="selectall('id[]');">
                    </th>
                    <th align="center"><strong>ID</strong></th>
                    <th align="center"><strong>用户ID</strong></th>
                    <th align="center"><strong>用户名</strong></th>
                    <th align="center"><strong>用户昵称</strong></th>
                    <th align="center"><strong>手机号码</strong></th>
                    <th align="center"><strong>账户类型</strong></th>
                    <th align="center"><strong>账号</strong></th>
                    <th align="center"><strong>收款人</strong></th>
                    <th align="center"><strong>是否默认</strong></th>
                    <th align="center"><strong>时间</strong></th>
                    <th align="center"><strong>操作</strong></th>
                </tr>
            </thead>
        <tbody>
        <?php
        if(is_array($infos)){
            foreach($infos as $info){
            $default = [1=>'默认',-1=>'/'];
        ?>
            <tr>
                <td align="center" width="35">
                    <input type="checkbox" name="id[]" value="<?php echo $info['id']?>">
                </td>
                <td align="center"><?php echo $info['id']?></td>
                <td align="center"><?php echo $info['userid']?></td>
                <td align="center"><?php echo $info['username']?></td>
                <td align="center"><?php echo $info['nickname']?></td>
                <td align="center"><?php echo $info['phone']?></td>
                <td align="center">
                    <?php
                        if(strstr($info['tname'],',')){
                            echo explode(',',$info['tname'])[1];
                        }else{
                            echo $info['tname'];
                        }
                    ?>
                </td>
                <td align="center"><?php echo $info['account']?></td>
                <td align="center"><?php echo $info['accountname']?></td>
                <td align="center"><?php echo $default[$info['is_first']]?></td>
                <td align="center"><?php echo date('Y-m-d H:i:s',$info['addtime']);?></td>
                <td align="center">
                    <a href="javascript:confirmurl('?m=zyfunds&c=zyfunds&a=zhmanagedel&id=<?php echo $info['id']?>', '确定删除')"
                       class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
                </td>
            </tr>
        <?php }}?>
        </tbody>
    </table>
    </div>
    <div class="btn" style="height:50px;line-height:40px;width:100%;text-align:left;">
        <label for="check_box">全选/取消</label>
        <input type="submit" class="button layui-btn layui-btn-danger layui-btn-sm" name="dosubmit" value="批量删除"
               onclick="return confirm('确定删除')" style="padding:0 18px;" /></div>
        <div id="pages"><?php echo $pages?></div>
    </form>
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
