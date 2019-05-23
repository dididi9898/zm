<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zymessagesys/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zymessagesys/layui/layui.all.js"></script>

<div class="pad_10">
    <div class="table-list">
        <form name="myform" id="myform" action="?m=zyim&c=zyim&a=del" method="post" onsubmit="checkuid();return false;" >
            <div class="table-list">
                <table width="100%" cellspacing="0" class="layui-table">
                    <thead>
                        <tr>
                            <th width="35" align="center"><input type="checkbox" value="" id="check_box" onclick="selectall('id[]');"></th>
                            <th >用户昵称</th>
                            <th >头像</th>
                            <th >最后聊天记录</th>
                            <th >最后聊天时间</th>
                            <th >操作</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                        foreach($info as $r)
                        {
                    ?>
                        <tr>
                            <td align="center" width="35"><input type="checkbox" name="id[]" value="<?php echo $r['id']?>"></td>
                            <td  align="center"><?php echo $r['talk_from_name']?></td>
                            <td  align="center"><img src="<?php echo $r['talk_from_img']?>"></td>
                            <td  align="center"><?php echo $r['last_word']?></td>
                            <td  align="center"><?php echo date('Y-m-d H:i:s',$r['last_time'])?></td>
                            <td align="center">
                                <a class="layui-btn layui-btn-normal layui-btn-sm" href="index.php?m=zyim&c=zyim&a=talk&records_id=<?php echo $r['records_id'] ?>" ,)">进入聊天</a>
                                <a href="javascript:confirmurl('?m=zyim&c=zyim&a=del&id=<?php echo $r['id'] ?>', '确定删除')"
                                   class="layui-btn layui-btn-danger layui-btn-sm">删除</a>
                            </td>
                        </tr>
                    <?php
                        }
                    ?>
                </tbody>
                </table>
            </div>
            <div class="btn" style="height:40px;line-height:40px;text-align:left;">
                <label for="check_box">全选/取消</label>
                <input type="submit" class="layui-btn layui-btn-danger layui-btn-sm" name="dosubmit" value="批量删除" onclick="return confirm('确定删除')"/>
            </div>
        </form>
    </div>
</div>
<script>
/**
 * api地址编辑
 */
function talk(id,name)
{
    setTimeout("javascript:location.href='index.php?m=zyim&c=zyim&a=talk&records_id='+id", 1000);
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
</body>
</html>