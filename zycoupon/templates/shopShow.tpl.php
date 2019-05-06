<?php
defined('IN_ADMIN') or exit('No permission resources.');
include $this->admin_tpl('header', 'admin');
?>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>member_common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidator.js" charset="UTF-8"></script>
<script language="javascript" type="text/javascript" src="<?php echo JS_PATH?>formvalidatorregex.js" charset="UTF-8"></script>
<style type="text/css">
    .table_form th{text-align: left;}
</style>
<style>
    td{
        min-width: 100px;
    }
</style>


<div class="pad-10">
    <div class="common-form">
        <div id="div_setting_2" class="contentList">

            <fieldset>
                <legend>基本信息</legend>
                <table class="table_form"  style="min-width: 100%;overflow:auto;display: block;">
                    <tbody>
                    <tr >
                        <td >商品名：</td>
                        <td colspan="2"><?php echo $data["shopname"]?></td>

                        <td >缩略图:</td>
                        <td colspan="2">
                            <img src=<?php echo $data["thumb"]?>  height="90" width="90" >
                        </td>
                    </tr>
                    <tr>
                        <td>商品简述：</td>
                        <td colspan="5">
                            <?php echo $data["sketch"]?>
                        </td>
                    </tr>
                    <tr>
                        <td >价格：</td>
                        <td><?php echo $data["price"]?></td>
                        <td >购买数量：</td>
                        <td><?php echo $data["count"]?></td>
                        <td >购买总价：</td>
                        <td><?php echo $data["pricecount"]?></td>
                    </tr>
                    <tr>
                        <td>商品规格：</td>
                        <td colspan="5">
                            <?php echo $data["specification"]?>
                        </td>
                    </tr>
                    <tr>
                        <td >购买时间</td>
                        <td colspan="5"><?php echo date("Y-m-d H:i:s",$data["add_time"])?> </td>
                    </tr>
                    </tbody>
                </table>
            </fieldset>
            <div class="bk15"></div>

        </div>
    </div>

</div>
</div>
</body>
</html>