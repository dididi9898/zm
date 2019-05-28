<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>
<link rel="stylesheet" type="text/css" href="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/css/layui.css">
<script src="<?php echo APP_PATH?>statics/public/layui-v2.4.5/layui/layui.all.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.21/dist/vue.js"></script>
<script src="<?php echo APP_PATH?>statics/public/js/ajax.js"></script>
<style>
	div{
	}
</style>
<div class="subnav">
    <div class="content-menu ib-a blue line-x">
    <a class="add fb"><em>excel导入</em></a>　    
    </div>
</div>

<div class="layui-row">
	<div class="layui-col-md2">
		<div class="layui-row">
            <div class="pad-10">
                <fieldset>
                    <legend style=" display: inline-block; float:left;">会员资料导入</legend>
                    <div class="bk15"></div>

                    <div >

                        <button type="button" class="layui-btn" id="test1">
                            <i class="layui-icon">&#xe67c;</i>上传Excel
                            <button>
<!--                            <input type="file" name="file_stu"/>-->
<!--                        <input class="layui-upload-file" name="file-demo" type="file">-->

                    </div>
<!--                    <input type="submit" name="dosubmit" value="导入" />-->
                </fieldset>
            </div>
		</div>
		<div class="layui-row">
            <div class="pad-10">
                <fieldset>
                    <legend style=" display: inline-block; float:left;">配置文件</legend>
                    <div class="bk15"></div>
                    <form action="##" method="post" id="f1" onsubmit="return false">
                    <div style="margin: 10px;">
                        <div>
                        <?php foreach ($configArray as $k=>$v) {?>
                            <div>
                            <select style="width: 50px" name="config[<?php echo $k ?>]" >
                                    <option value="">列</option>
                                    <?php foreach (self::$excle_no as $x=>$y){?>
                                        <option <?php if($y == $v) echo "selected"?> value="<?php echo $y?>"><?php echo $y?></option>
                                    <?php }?></select>----><?php echo self::$CN[$k]?></div>
                        <?php } foreach (self::$CN as $k=>$v){ if(!array_key_exists($k, $configArray)){?>
                            <div>
                            <select style="width: 50px" name="config[<?php echo $k ?>]" >
                                    <option value="">列</option>
                                    <?php foreach (self::$excle_no as $x){?>
                                        <option value="<?php echo $x?>"><?php echo $x?></option>
                                    <?php }?></select>----><?php echo $v?></div>
                        <?php }} ?>
                        </div>
                        <button class="layui-btn" style="margin-top: 20px;" onclick="pull()">提交<button>
                    </div>
                    </form>
                </fieldset>
            </div>
		</div>
	</div>
    <button onclick="check()">点击</button>
	<div class="layui-col-md8">
        <div class="pad-10">
            <fieldset>
                <legend style=" display: inline-block; float:left;">报错信息</legend>
                <div class="bk15"></div>
                <div id="allcat">
                    往期错误信息:
                    <select v-model="chickNum">
                        <template v-for="x,y in errorNumber">
                            <option v-if="y==0" selected :key="x">{{x}}</option>
                            <option v-else :key="x">{{x}}</option>
                        </template>
                    </select>
                    <table width="100%">
                        <tr>
                            <th>id</th>
                            <th>错误行数</th>
                            <th>错误信息</th>
                            <th>错误发生时间</th>
                        </tr>
                        <template v-for="x,y in errorData">
                        <tr style="text-align: center">
                                <td>{{ x.id }}</td>
                                <td>{{ x.row }}</td>
                                <td>{{ x.info }}</td>
                                <td>{{ x.addtime }}</td>
                        </tr>
                        </template>
                        <button id="checkClick" @click="doc(chickNum)"> 查询</button>
                        <button id="checkErrNum" @click="checkErrNum()" hidden>2</button>
                    </table>
                </div>
            </fieldset>
        </div>
	</div>
</div>


<div class="bk15"></div>


<script>
    ;
    ! function () {

        var layer = layui.layer,
            form = layui.form,
            $ = layui.jquery,
            upload = layui.upload,
            table = layui.table;
        var uploadInst = upload.render({
            elem:'#test1',
            url:'index.php?m=zyexcel&c=excel&a=excel_dr_ceshi&pc_hash=<?php echo $_GET["pc_hash"]?>',
            accept: 'file',

            done:function(res){
                layer.msg(res.message);
                console.log(res);
                $('#checkErrNum').click();
            }
        });
        var allcat =  new Vue({
            el: '#allcat',
            data:{
                errorNumber:[<?php echo $errorInfo?>],
                errorData:[],
                chickNum :''
            },
            methods:{
                doc:function(data){
                    var that = this;
                    that.$options.methods.doClick(data, that);
                },
                doClick:function(data, that){
                    aj.post('index.php?m=zyexcel&c=excel&a=errorInfo&pc_hash=<?php echo $_GET["pc_hash"]?>',{"errorNumber":data}, function(data){
                        if(data.code=='-1'){
                            layer.msg(data.message);
                        }else {
                            that.errorData=(data.data);

                            console.log(data.data);
                            console.log(that.errorData);
                        }
                    })
                },
                checkErrNum:function(){
                    var that = this;
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'index.php?m=zyexcel&c=excel&a=checkErrNum&pc_hash=<?php echo $_GET["pc_hash"]?>',
                        success:function(data){
                            if(data.code=='-1'){
                                layer.msg(data.message);
                            }else {
                                that.chickNum = data.data[0];
                                that.$options.methods.doClick(that.chickNum, that);
                                that.errorNumber=(data.data);
//                                console.log( data.data[0]);
                            }
                        }
                    })
                }
            }
        });
    }();

</script>
<script>
    function pull(){
        $.ajax({
            type:'post',
            dataType:'json',
            url:'index.php?m=zyexcel&c=excel&a=changeConfig&pc_hash=<?php echo $_GET["pc_hash"]?>',
            data:$('#f1').serialize(),
            success:function(res){
                if(res.code == '-1')
                    layer.msg(res.message);
                else
                    window.location.reload();
            }
        })
    }

</script>
</body>
</html>
