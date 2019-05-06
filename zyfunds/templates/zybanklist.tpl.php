<?php
defined('IN_ADMIN') or exit('No permission resources.');
$show_dialog = 1;
include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/funds/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/funds/layui/layui.all.js"></script>

<form name="myform" id="myform" action="index.php?m=zyfunds&c=zyfunds&a=zybanklistdel" method="post" onsubmit="checkuid();return false;" >
    <input type="hidden" value="board" name="m">
    <input type="hidden" value="board" name="c">
    <input type="hidden" value="lists" name="a">
    <div class="table-list">
        <table width="100%" cellspacing="0" class="layui-table">
            <thead>
            <tr>
                <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
                <th align="center">ID</th>
                <th align="left" width="300">银行LOGO</th>
                <th align="left">银行</th>
                <th align="center">银行类型</th>
                <th align="center">状态</th>
                <th align="center">操作</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($info as $k => $r){?>
                    <tr>
                        <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $r['id']?>"></td>
                        <td align="center"><?php echo $r['id']?></td>
                        <td align="left"><?php echo "<img src='{$r['thumb']}' style='height:30px;padding:10px 0;'>";?></td>
                        <td align="left"><?php echo $r['bank']?></td>
                        <td align="center"><?php echo $r['desc']?></td>
                        <td align="center">
                            <?php if($r['status']==1){?>
                                <img src="<?php echo APP_PATH?>statics/funds/images/toggle_enabled.gif"/>
                            <?php }else{?>
                                <img src="<?php echo APP_PATH?>statics/funds/images/toggle_disabled.gif"/>
                            <?php }?>
                        </td>
                        <td align="center">
                            <a href="javascript:;" class="layui-btn layui-btn-success layui-btn-sm"
                               onclick="edit('<?php echo $r['id']?>')">编辑</a>
                            <a href="javascript:confirmurl('?m=zyfunds&c=zyfunds&a=zybanklistdel&id=<?php echo $r['id']?>', '确定删除')"
                               class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
    <div class="btn" style="height:50px;line-height:40px;width:100%;text-align:left;">
        <label for="check_box">全选/取消</label>
        <input type="submit" class="layui-btn layui-btn-danger layui-btn-sm" name="dosubmit" value="批量删除" onclick="return confirm('确定删除')"/>
    </div>
    <div id="pages"><?php echo $pages?></div>
</form>
</body>
<script>
    function edit(id)
    {
        window.top.art.dialog(
            {
                id:'edit',
                iframe:'?m=zyfunds&c=zyfunds&a=zybanklistedit&id='+id,
                title:'修改信息',
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
