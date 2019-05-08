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

<div class="pad-10">
    <div class="common-form">
        <div id="div_setting_2" class="contentList">

            <fieldset>
                <legend>基本信息</legend>
                <table class="table_form" style="width: 100%">
                    <tbody >
                    <tr >
                        <td >收货人姓名：</td>
                        <td ><?php echo $data["lx_name"]?></td>
                    </tr>
                    <tr>
                        <td >收货人手机:</td>
                        <td >
                            <?php echo $data["lx_mobile"]?>
                        </td>
                    </tr>
                    <tr>
                        <td>省</td>
                        <td >
                            <?php echo $data["province"]?>
                        </td>
                    </tr>
                    <tr>
                        <td >区：</td>
                        <td><?php echo $data["city"]?><?php echo $data["area"]?></td>
                    </tr>
                    <tr>
                        <td>详细地址：</td>
                        <td>
                            <?php echo $data["address"]?>
                        </td>
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