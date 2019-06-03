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
            <?php foreach($data as $key=>$data) { ?>
            <fieldset>
                <legend>商品信息</legend>

                <table class="table_form"  style="min-width: 100%;overflow:auto;display: block;">
                    <tbody>
                    <tr >
                        <td >商品id：</td>
                        <td colspan="1"><?php echo $data["goods_id"]?></td>
                        <td >商品名：</td>
                        <td colspan="1"><?php echo $data["goods_name"]?></td>
                        <td >缩略图:</td>
                        <td colspan="1">
                            <img src="<?php echo $data["goods_img"]?>"  height="90" width="90" >
                        </td>
                    </tr>
<!--                    <tr>-->
<!--                        <td>商品简述：</td>-->
<!--                        <td colspan="5">-->
<!--                            --><?php //echo $data["sketch"]?>
<!--                        </td>-->
<!--                    </tr>-->
                    <tr>
                        <td >价格：</td>
                        <td><?php echo $data["goods_price"]?></td>
                        <td >购买数量：</td>
                        <td><?php echo $data["goods_num"]?></td>
                        <td >购买总价：</td>
                        <td><?php echo $data["final_price"]?></td>
                    </tr>
                    <tr>
                        <td>商品规格：</td>
                        <td colspan="5">
                            <?php echo $data["specid_name"]?>
                        </td>
                    </tr>
                    <tr>
                        <td >购买时间</td>
                        <td colspan="5"><?php echo date("Y-m-d H:i:s",$data["ordersn"])?> </td>
                    </tr>

                    </tbody>
                </table>

            </fieldset>
            <div class="bk15"></div>
            <?php } ?>
        </div>
    </div>

</div>
</div>
</body>
</html>