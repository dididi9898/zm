<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
    $show_header = 1;
	include $this->admin_tpl('header','admin');
?>
<style>
    .btn { display: inline-block; padding: 0px 5px; margin-bottom: 0; font-size: 12px; font-weight: 400; line-height: 1.32857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; margin-left: 5px;}
    .btn-info { background-image: -webkit-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -o-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5bc0de),to(#2aabd2)); background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2aabd2', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #28a4c9;}
    .btn-info { color: #fff; background-color: #5bc0de; border-color: #46b8da;}

    .btn-danger { background-image: -webkit-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -o-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#d9534f),to(#c12e2a)); background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc12e2a', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #b92c28;}
    .btn-danger { color: #fff; background-color: #d9534f; border-color: #d43f3a;}

    .btn-success { background-image: -webkit-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -o-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5cb85c),to(#419641)); background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5cb85c', endColorstr='#ff419641', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #3e8f3e;}
    .btn-success { color: #fff; background-color: #5cb85c; border-color: #4cae4c;}
    a:hover{ text-decoration: none; }
    .page{
        text-align: right;
    }
    .page div{
        display: inline-block;
        text-align: right;
        border-radius: 5px;
        padding: 0;
        display: inline-block;
        list-style: none;
        border: 1px solid #ddd;
        color: #337ab7;
    }
    .page span{
        display: inline-block;
        line-height: 35px;
        padding: 0 15px;
        border-left: 1px solid #ddd;
        margin-right: -5px;
        cursor:pointer;
    }
    .page span:first-of-type{
        border-style: none;
        cursor:pointer;
    }
    .page-on{
        background-color: #337ab7;
        color: #fff;

    }

    .header_box{
        height: 50px;
    }
    .header_box .box{
        margin-left: 20px;
    }
    .header_box .submit{
        padding:5px;
        background-color: #2aabd2;
        color: white;
        font-size: 14px;
    }
    .body_box{
        border: 1px solid transparent;
    }
    .body_box thead{
        border-radius: 4px;
        background-color: #eef3f7;
        vertical-align: middle;
        display: table-header-group;
    }
    .body_box thead tr{
        display: table-row;
        vertical-align: inherit;
        border-color: inherit;
        font-size:20px;
        font-weight: normal;
    }


</style>

<div class="subnav">
    <div class="content-menu ib-a blue line-x">
        <a class="add fb" href="javascript:window.top.art.dialog({id:'add',iframe:'?m=zyorder&c=order&a=add',title:'添加商品分类', width:'800', height:'500', lock:true}, function(){var d = window.top.art.dialog({id:'add'}).data.iframe;var form = d.document.getElementById('dosubmit');form.click();return false;},function(){window.top.art.dialog({id:'add'}).close()});void(0);">
        <em>添加快递</em></a>
    </div>
</div>
<div  class="table-list">
    <table width="100%">
        <thead>
            <tr>
                <th>分类ID</th>
                <th>快递公司</th>
                <th>快递公司简称</th>
                <th>是否显示</th>
                <th>管理操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($info as $row){?>
            <tr>
                <td align="center"><?php echo $row["EXid"]?></td>
                <td style="text-align: center"><?php echo($row["name"])?>

                </td>
                <td align="center"><?php echo($row["value"])?></td>
                <td align="center"><img src="<?php if($row["isShow"]==1) echo IMG_PATH."right.png"; else echo IMG_PATH."wrong.png";?>" ></td>
                <td align="center">
                    <a href="javascript:void(0);" class="btn btn-info btn-sm" onclick="edit('<?php echo $row['EXid']?>')"><?php echo L('编辑')?></a>
                    <a href="javascript:confirmurl('?m=zyorder&c=order&a=drop&EXid=<?php echo $row['EXid']?>', '<?php echo L('确定删除吗')?>')" class="btn btn-danger btn-sm"><?php echo L('删除')?></a>
                </td>
            </tr>
            <?php }?>
        </tbody>
    </table>
</div>
<script>
    function edit(id){
        window.top.art.dialog({
            id:'edit',
            iframe:"?m=zyorder&c=order&a=edit&EXid="+id,
            title:'用户信息',
            width:'800',
            height:'500',
            lock:true
        },
        function () {
            var d = window.top.art.dialog({id:'edit'}).data.iframe;
            var form = d.document.getElementById('dosubmit');
            form.click();
            return false;
        },
        function(){
            window.top.art.dialog({id:'edit'}).close();
        });
        void(0);
    }
</script>