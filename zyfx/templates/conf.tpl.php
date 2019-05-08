<?php 
	defined('IN_ADMIN') or exit('No permission resources.');
	include $this->admin_tpl('header', 'admin');
?>

<style>
.clear{ clear: both; }
.fjpz{ margin: 5px 0; float: left; width: 80%; }
.fjpz label{}
.fjpz span{ width: 50px; float: left;}
.fjpzs{ margin: 5px 0; float: left; width: 100%; }
td input{
    text-align: center;
}
.upleft{ float:left; width: 15%; text-align: center; line-height: 30px; }
.upright{ float: right; width: 85%; }
.upright .fjpz span{ width: 100px; float: left; line-height: 24px;}
.upright .fjpz input{ width: 100px; margin-left: 10px; margin-top: 4px;}
.cont{}
#persta1, #persta2, #persta3{ margin: 10px; }

.btn { display: inline-block; padding: 5px 12px !important; margin-bottom: 0; font-size: 12px; font-weight: 400; line-height: 1.32857143; text-align: center; white-space: nowrap; vertical-align: middle; -ms-touch-action: manipulation; touch-action: manipulation; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-image: none; border: 1px solid transparent; border-radius: 4px; margin-left: 5px;}
.btn-info { background-image: -webkit-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -o-linear-gradient(top,#5bc0de 0,#2aabd2 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5bc0de),to(#2aabd2)); background-image: linear-gradient(to bottom,#5bc0de 0,#2aabd2 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2aabd2', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #28a4c9;}
.btn-info { color: #fff; background-color: #5bc0de; border-color: #46b8da;}

.btn-infos { background-image: -webkit-linear-gradient(top,#ceeaf3 0,#ceeaf3 100%); background-image: -o-linear-gradient(top,#ceeaf3 0,#ceeaf3 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#ceeaf3),to(#ceeaf3)); background-image: linear-gradient(to bottom,#ceeaf3 0,#ceeaf3 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff2aabd2', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #ceeaf3;}
.btn-infos { color: #fff; background-color: #ceeaf3; border-color: #ceeaf3;}
.btn-infos:hover{  cursor:not-allowed; } 
    
.btn-danger { background-image: -webkit-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -o-linear-gradient(top,#d9534f 0,#c12e2a 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#d9534f),to(#c12e2a)); background-image: linear-gradient(to bottom,#d9534f 0,#c12e2a 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc12e2a', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #b92c28;}  
.btn-danger { color: #fff; background-color: #d9534f; border-color: #d43f3a;}

.btn-dangers { background-image: -webkit-linear-gradient(top,#f9e2e1 0,#f9e2e1 100%); background-image: -o-linear-gradient(top,#f9e2e1 0,#f9e2e1 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#f9e2e1),to(#f9e2e1)); background-image: linear-gradient(to bottom,#f9e2e1 0,#f9e2e1 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc12e2a', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #f9e2e1;}  
.btn-dangers { color: #fff; background-color: #f9e2e1; border-color: #f9e2e1;}
.btn-dangers:hover{  cursor:not-allowed; } 

.btn-success { background-image: -webkit-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -o-linear-gradient(top,#5cb85c 0,#419641 100%); background-image: -webkit-gradient(linear,left top,left bottom,from(#5cb85c),to(#419641)); background-image: linear-gradient(to bottom,#5cb85c 0,#419641 100%); filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5cb85c', endColorstr='#ff419641', GradientType=0); filter: progid:DXImageTransform.Microsoft.gradient(enabled=false); background-repeat: repeat-x; border-color: #3e8f3e;}
.btn-success { color: #fff; background-color: #5cb85c; border-color: #4cae4c;}
a:hover{ text-decoration: none; }

 /*隐藏radio按钮*/
input[type="radio"] { opacity: 0;}
.myradio { display: inline-block; vertical-align: middle; margin: 0; padding: 0; width: 70px; height: 24px; border-radius: 20px; position: relative; overflow: hidden;}
.mrclose { background-color: #f40;/*#e8e8e8;*/}
.mropen { background-color: #67e66c;}
.myradio .open, .myradio .close { width: 22px; height: 22px; font-size: 13px; border-radius: 50%; background: #fff; color: #fff; position: absolute; top: 0; left: 0; border: 1px solid #e8e8e8;}
.myradio .open { color: #fff; background-color: #fff;}
.hidden { display: none}
.disabled { pointer-events: none; cursor: default;}
.myradio .close { left: auto; right: 0; }
.myradio .open:after { content: '开启'; position: absolute; top: 0; left: 30px; width: 28px; height: 24px; line-height: 22px; }
.myradio .close:before { content: '关闭'; position: absolute; top: 0; left: -35px; width: 28px; height: 24px; line-height: 22px;}
th{ width: 12%; margin-right: 5px;}

input[type="radio"] + label::before { content: "\a0"; /*不换行空格*/ display: inline-block; vertical-align: middle; font-size: 16px; width: 1em; height: 1em; margin-right: .4em; border-radius: 50%; border: 1px solid #01cd78; text-indent: .15em; line-height: 1; margin-left: 10px; margin-top: 5px; -moz-box-sizing: border-box;  /*Firefox3.5+*/-webkit-box-sizing: border-box; /*Safari3.2+*/-o-box-sizing: border-box; /*Opera9.6*/-ms-box-sizing: border-box; /*IE8*/box-sizing: border-box; margin-top: -2px;}
input[type="radio"]:checked + label::before { background-color: #01cd78; background-clip: content-box; padding: .18em; font-size: 16px;}
input[type="radio"] { position: absolute; clip: rect(0, 0, 0, 0);}

</style>


<div class="pad-10">
	<div class="common-form">
		<fieldset>
			<form name="myform" action="?m=zyfx&c=fxBack&a=config&pc_hash=<?php echo $_SESSION['pc_hash'];?>" method="post">
				<legend>分销机制配置列表</legend>
				<div class="bk15"></div>
				<div class="explain-col search-form">

					<div class="common-form">
					<div id="div_setting_2" class="contentList">
				    
				    	<fieldset>
				        <legend>基本配置</legend>
						<table width="100%" class="table_form">
							<tbody>
								<tr> 
									<th>分销级数</th>  
									<td><input id="rankval" type="text" name="tier" class="input-text" required="" pattern="^[1-9]{1}[0-9]*$" value="<?php echo $info['tier']?>" onchange="change()"> 级</td>
								</tr>



								<tr id='gjfhlss'>
									<th width="120">分红方式</th>
									<td>
										<input type="radio" name="awardType"  <?php if ($info['awardType']==1) {?>checked <?php }?> id="fhmet1" value="1"><label for="fhmet1">固定金额（元）</label>
										<input type="radio" name="awardType"  <?php if ($info['awardType']==2) {?>checked <?php }?> id="fhmet2" value="2"><label for="fhmet2">百分比（%）</label>
									</td>
								</tr>
                                <tr>
                                    <th>提现手续费（不收取设置为0）</th>
                                    <td><input id="TXcharge" type="text" name="TXcharge" class="input-text" required="" pattern="^[1-9]{3}[0-9]*$" value="<?php echo $info['TXcharge']?>" "> %</td>
                                </tr>
				                <tr id='gjfhl' ">
									<th>各级基础分红设置</th>

									<td class="rankfather">
										<?php

                                            foreach($info["awardNumber"] as $key => $value){
										?>
										<div class="fjpz cont">
											<span><?php echo $key?>级成员</span>
											<input  type="text" style="width: 70px; text-align: center" name="awardNumber[<?php echo $key?>]" value="<?php echo $value?>">
										</div>
										<?php

										}
										?>

									</td>

								</tr>

								<tr id='gjfhls'>
									<th>是否统一头衔</th>
                                    <input type="hidden" value="<?php echo $info['gradeTitleType']?>" name="gradeTitleType" id="gradeTitleType">
									<td>
										<?php
							                if($info['gradeTitleType']==1){
							            ?>        
							            <p class="myradio mropen">
								            <label class="open">
								                <input type="radio" value="open"  data-val="mdpstatus" />
								            </label>
								            <label class="close hidden">
								                <input type="radio" value="close" data-val="mdpstatus" />
								            </label>
								        </p>
							            <?php
							                }else{ 
							            ?>
							            <p class="myradio mrclose">
								            <label class="open hidden">
								                <input type="radio" value="open" data-val="mdpstatus" />
								            </label>
								            <label class="close">
								                <input type="radio" value="close" data-val="mdpstatus" />
								            </label>
								        </p>
							            <?php
							                }
							            ?> 
							            (一般适用于后台添加客户的情况,统一头衔模式为多)

								    </td>
								</tr>

                                <tr>
                                    <th width="120">成员头衔</th>
                                    <td>
                                        <div id='mnam1' style="display: <?php if ($info['gradeTitleType']==1) {?>none<?php }?>;">
                                            <input type="button" class="btn btn-info btn-sm addinput" value="增加" >
                                            <input type="button" class="btn btn-info btn-sm delinput" value="减少">
                                            <table style="width: 100%;text-align: center">
                                                <tr >
                                                    <th style="text-align: center">
                                                        头衔等级
                                                    </th>
                                                    <th style="text-align: center">
                                                        头衔名称
                                                    </th>
                                                    <th style="text-align: center">
                                                        需要人数
                                                    </th>
                                                    <th style="text-align: center">
                                                        奖励金额
                                                    </th>
                                                </tr>
                                            <tbody class="father">
                                            <input type="hidden" value="<?php echo $info["gradeNumber"]?>" name="gradeNumber" id="gradeNumber">
                                            <?php $num = 1;
                                                foreach($grideInfo as $key => $value){ if($num > $info["gradeNumber"]) break;
                                                    ?>
                                                    <tr class="fixinput">
                                                        <div class="fjpz" style=" width: 50%;">
    <!--                                                                <span style=" margin:0 10px;">--><?php //echo $value['tname']?><!--</span>-->
                                                            <td>等级<?php echo $value['titleID']?></td>
                                                            <td><input type="text" name="titleID[<?php echo $key+1?>][TitleName]" required=""  value="<?php echo $value['TitleName']?>"></td>
                                                            <td><input type="text" name="titleID[<?php echo $key+1?>][neadMember]" value="<?php echo $value['neadMember']?>"></td>
                                                            <td><input type="text" name="titleID[<?php echo $key+1?>][gradeAward]" value="<?php echo $value['gradeAward']?>"></td>
                                                        </div>
                                                    </tr>
                                                    <?php $num++;
                                                }
                                            ?>
                                            </tbody>
                                            </table>

                                        </div>
                                        <div id='mnam2' style="display: <?php if ($info['gradeTitleType']==2) {?>none<?php }?>;">
                                            <input type="text" name="titleID[<?php echo 0?>][TitleName]" value="<?php echo $unifyTitle['TitleName']?>">
                                        </div>
                                    </td>
                                </tr>
						</tbody>
					</table>
					<div class="bk15"></div>
			        <input class="btn btn-info btn-sm" name="dosubmit" id="dosubmit" type="button" onclick="submit()" value="提交"/>
			        </fieldset>
			        
				</div>
				

			</div>

			</div>
		</form>



		</fieldset>


		
	</div>
</div>






</body>
</html>
<script type="text/javascript">
	$('.addinput').on('click',function(){
		var index = $(".fixinput").size()+1;
		if(index<=10){
		var imgshow =' <tr class="fixinput"> <div class="fjpz" style=" width: 50%;" > <td>等级'+index+'</td> <td><input type="text" name="titleID['+index+'][TitleName]" class="input-text" required=""  value="等级'+index+'"></td> <td><input type="text" class="input-text" name="titleID['+index+'][neadMember]" value="0""></td> <td><input type="text" class="input-text" name="titleID['+index+'][gradeAward]" value="0"></td> </div> </tr>';
		$('.father').append(imgshow);
		}else{
			alert("等级不能超过十");
		}
		$("#gradeNumber").val(index);
	});
	$('.delinput').on('click',function(){
        //var index = $(".father").size();
        $('.fixinput').last().remove();
        $("#gradeNumber").val($(".fixinput").size());
    });

    function change() {
        $s = $('#rankval').val();
        $num = $('.cont').size();
        $a = 1;
        while($s != $num)
        {
            if($s > $num)
            {
                var imgshow ='<div class="fjpz cont"> <span>'+($num+1)+'级成员</span> <input class="input-text" type="text" style="width: 70px; text-align: center" name="awardNumber['+($num + 1)+']" value=" "> </div>'
                $('.rankfather').append(imgshow);
            }
            else if($s < $num)
                $('.cont').last().remove();
            $s = $('#rankval').val();
            $num = $('.cont').size();
            $a =$a+1;
            if($a >50)
                break;
        }
    }
</script>
<script>
	onload = function(){
	  //单选	  
	  var radios = document.getElementsByName('mcnt');
	    for (var i = 0; i < radios.length; i++) {
	          radios[i].indexs = i + 1;
	        radios[i].onchange = function () {
	            if (this.checked) {
	                document.getElementById("mnam1").style.display="none";
	                document.getElementById("mnam2").style.display="none";
	                document.getElementById("mnam" + this.indexs).style.display="block";
	            } 
	        }
	    }


	    var radioss = document.getElementsByName('upmet');
	    for (var i = 0; i < radioss.length; i++) {
	          radioss[i].indexs = i + 1;
	          radioss[i].onchange = function () {
	            if (this.checked) {
	                document.getElementById("upmed1").style.display="none";
	                document.getElementById("upmed2").style.display="none";
	                document.getElementById("upmed" + this.indexs).style.display="block";
	            } 
	        }
	    }

	    var radiosss = document.getElementsByName('upmets');
	    for (var i = 0; i < radiosss.length; i++) {
	          radiosss[i].indexs = i + 1;
	          radiosss[i].onchange = function () {
	            if (this.checked) {
	                document.getElementById("persta1").style.display="none";
	                document.getElementById("persta2").style.display="none";
	                document.getElementById("persta3").style.display="none";
	                document.getElementById("persta" + this.indexs).style.display="block";
	            } 
	        }
	    }
	}

        $(".myradio input").click(function(e){
        	val = $(this).attr("data-val");
        	// alert(val);
            var state = e.delegateTarget.defaultValue;
            var myradio = $(".myradio");
            var iclose = $(this).parents(".myradio").find('.close');
            // console.log(iclose);
            var iopen = $(this).parents(".myradio").find('.open');
            // console.log(state);
            $(this).parents(".myradio").find(':radio').removeAttr('checked');
            $(this).parent('label').addClass('disabled');
            $(this).parent('label').siblings('label').find(':radio').attr('checked',true);
            if (state == 'open') {
                $(this).parents(".myradio").removeClass('mropen').addClass('mrclose');
                $(this).parents(".myradio").prev('span').html('<font style="color: #e8e8e8;">关闭状态</font>：');
                open();

     //            	var t = document.getElementById("centerlist")
				 //  	var a = t.getElementsByTagName("input");
					// for(var i = 0; i < a.length; i++) {
				 //    	a[i].setAttribute("disabled","true");
					// }
                $('#mnam1').show();
                $('#mnam2').hide();
                $('#gradeTitleType').val(2);
            } else {
                $(this).parents(".myradio").removeClass('mrclose').addClass('mropen');
                $(this).parents(".myradio").prev('span').html('<font style="color: #67e66c;">开启状态</font>：');
                close();
//                changestatus(val);
                $('#mnam2').show();
                $('#mnam1').hide();
                $('#gradeTitleType').val(1);

            }
            
            function open(){
                iopen.animate({left:"50px"},100);
                setTimeout(function(){
                    iopen.hide();
                    iclose.show();
                    iopen.css('left',0);
                    $(".myradio label").removeClass('disabled');
                 },300);
            }

            function close(){
                iclose.animate({left:"0px"},100);
                setTimeout(function(){
                    iclose.hide();
                    iopen.show();
                    iclose.css('left','47px');
                    $(".myradio label").removeClass('disabled');
                 },300);
            }


            function changestatus(val){
            	$.ajax({
            		url:'index.php?m=distribution&c=distribution&a=changestatus'+'&pc_hash='+'<?php echo $_SESSION['pc_hash'];?>',   
			        type:'post',   
			        data:{fid : val},    
			        dataType:'html', 
			        error:function(){   
			            alert('发生错误');  
			        },   
			        success:function(data){   
		                // alert(data.trim());                                      
			        } 
            	});        	
            }
        })
    function submit(){
	    $('.myform').submit();
    }
    </script>