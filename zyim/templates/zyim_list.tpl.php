<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header','admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/zymessagesys/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/zymessagesys/layui/layui.all.js"></script>

    <form name="searchform" action="" method="get" >
        <input type="hidden" value="zyim" name="m">
        <input type="hidden" value="zyim" name="c">
        <input type="hidden" value="zyim_list" name="a">
        <div class="explain-col search-form">
            <select name="type" >
                <option >请选择</option>
                <option value="1" <?php if ($_GET['type']==1) {?>selected<?php }?>>昵称</option>
            </select>
            <input type="text" value="<?php echo $_GET['q']?>" class="input-text" name="q">

            <select name="isunlook">
                <option value="" selected="">全部</option>
                <option value="1" <?php if ($_GET['isunlook']==1) {?>selected<?php }?>>未读</option>
                <option value="2" <?php if ($_GET['isunlook']==2) {?>selected<?php }?>>已读</option>
            </select>

            <input type="submit" value="<?php echo L('search')?>" name="dosubmit" class="layui-btn layui-btn-sm" style="padding: 0 10px;">
        </div>
    </form>

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
<!--                                <a class="layui-btn layui-btn-normal layui-btn-sm">你有--><?php //echo $r['unlook']?><!--条未读信息</a>-->
                                <a class="layui-btn layui-btn-normal layui-btn-sm" onclick="talk(<?php echo $r['records_id'] ?>)">进入聊天<?php if($r['unlook']!=0){ ?><span class="layui-badge"><?php echo $r['unlook'];?></span><?php } ?></a>
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
<script src="<?php echo APP_PATH?>statics/zm/js/ajax.js"></script>
<script>
/**
 * api地址编辑
 */
function talk(id)
{
    aj.post('index.php?m=zyim&c=api&a=look_msg', {'type': '2','userid':id,'records_id':id}, function (res) {
        if (res.status == 'error') {
            layer.msg(res.message);
            if(res.code==-103)
                setTimeout("javascript:location.href='index.php?m=zymember&c=index&a=login'", 1000);
        } else {
            console.log(res.data);
            setTimeout("javascript:location.href='index.php?m=zyim&c=zyim&a=talk&records_id="+id+"&pc_hash=<?php echo $_GET['pc_hash']?>'", 0);
        }
    });
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