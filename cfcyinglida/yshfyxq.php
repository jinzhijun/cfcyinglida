<?php session_start(); 
@header('Content-type: text/html;charset=utf-8');
include('newdb.php');
$html_title="药品发放详细信息";
include('spap_head.php');
?>
    <div class="main">
		<div class="insmain">
			<div class="thislink">当前位置：<a href="kfcfcfy.php"><?php echo $html_title;?></a></div>
<?php
$fyid=$_GET['id'];
$sql = "select * from `zyff` where `id`='".$fyid."'";

$Query_ID = mysql_query($sql);
while($Record = mysql_fetch_array($Query_ID)){
?>
    <div>
        发药日期:<?php echo $Record[20];?></div>
<?php
  $hzhsql = "select * from `hzh` where `id`='".$Record[1]."'";
  $hzhQuery_ID = mysql_query($hzhsql);
  while($hzhRecord = mysql_fetch_array($hzhQuery_ID)){  
  $hzhjzhshl=$hzhRecord[26];
  $hzhxm=$hzhRecord[4];
  $hzhzhjhm=$hzhRecord[5].":".$hzhRecord[6];
?> 
    <div>
        患者姓名:<?php echo $hzhxm;?>&nbsp;&nbsp;患者身份号码:<?php echo $hzhzhjhm;?>&nbsp;&nbsp;患者编码：<?php echo "I-".$hzhRecord[2];?>&nbsp;&nbsp;病种：<?php echo $hzhRecord[7];?>
<?php
  }
?>
    </div>
    <div>
        预计开始服用此次赠药时间:<?php echo $Record[16];?></div>
    <div>
        本次药品规格:<?php echo $Record[5];?></div>
    <div>
        本次领药数量:<?php echo $Record[4];?>瓶</div>
    <div>
        药品批号:<?php echo $Record[21];
      ?></div>
<?php if($Record[9]=="0"){//echo $hzhxm;
?>
    <div>
        领药人姓名:<?php echo $hzhxm;?></div>
    <div>
        领药人身份号码:<?php echo $hzhzhjhm;?></div>
    <div>
        领药人与患者关系:<?php echo "本人";?></div>
<?php
}
else{
  $lyrsql = "select * from `zhxqsh` where `id`='".$Record[9]."'";
  $lyrQuery_ID = mysql_query($lyrsql);
  while($lyrRecord = mysql_fetch_array($lyrQuery_ID)){ 
    //echo $lyrRecord[0];
?>
    <div>
        领药人姓名:<?php echo $lyrRecord[2];?></div>
    <div>
        领药人身份号码:<?php echo $lyrRecord[3];?></div>
    <div>
        领药人与患者关系:<?php echo $lyrRecord[4];?></div>
<?php
  }
}
?>

    <div>
        发药人员:<?php 
  /*$fyrnsql = "select `names` from `manager` where `id`='".$Record[18]."'";
  $fyrnQuery_ID = mysql_query($fyrnsql);
  while($fyrnRecord = mysql_fetch_array($fyrnQuery_ID)){ 
    $fyrname=$fyrnRecord[0];
  }
  $fyrsql = "select `yfzhdysh` from `yf` where `yfyshname`='".$fyrname."'";
  $fyrQuery_ID = mysql_query($fyrsql);
  while($fyrRecord = mysql_fetch_array($fyrQuery_ID)){ 
    echo $fyrRecord[0];
  }*/
  echo $Record[19]." ".$Record[18];
  ?></div>
  
  <?php
  $fyrcshsql = "select * from `zyff` where `hzhid`='".$Record[1]."' and `id`<'".$fyid."'";
  $fyrcshQuery_ID = mysql_query($fyrcshsql);
  $fyrcsh = mysql_num_rows($fyrcshQuery_ID);
  while($fyrcshRecord = mysql_fetch_array($fyrcshQuery_ID)){
  $yjhgg=$fyrcshRecord[5];
  }
  if($yjhgg==1){$yjhgg="200mg";}else if($yjhgg==2){$yjhgg="250mg";}
  if($Record[5]==1){$bcjhgg="200mg";}else if($Record[5]==2){$bcjhgg="250mg";}
  if($fyrcsh==""){$fyrcsh=0;$yjhkpshl=12-$hzhjzhshl;}else{$yjhkpshl=1;}

  if($fyrcsh!=0){
    if($yjhgg!=$bcjhgg){$bfh=1;echo "应交回空瓶规格与实际交回空瓶规格不符！</br>";}
  ?>
    <div>
        应交回空瓶规格:<?php echo $yjhgg;?></div>
    <div>
        实际交回空瓶规格:<?php echo $bcjhgg;?></div>
   <?php
   }
   

  if($yjhkpshl!=$Record[7]){$bfh=1;echo "应交回空瓶数量与实际交回空瓶数量不符！</br>";}

   ?>
    <div>
        应交回空瓶数量:<?php echo $yjhkpshl;?>瓶</div>
    <div>
        实际交回空瓶数量:<?php echo $Record[7];?>瓶</div>
    <div>
        交回剩余药物量:<?php if($Record[8]>0){echo $Record[8];}else{echo "0";}?>粒</div>
<?php
  if($bfh==1){
    if($Record[29]==1){echo "有证明材料：".$Record[30]."</br>";}else{echo "无证明材料</br>";}
    if($Record[31]!=""){echo "SPAP项目办授权id：".$Record[31]."</br>";}
  }
}
?>
    <input id="Id" name="Id" type="hidden" value="82530" />
    <div class="formConN">
        <a href="yshdfqdgl.php">返回待发清单</a>&nbsp;&nbsp;
        <a href="yshfygl.php">发药管理</a>&nbsp;&nbsp;
        
        <div class="btnPos">
            <input type="button" value="返回" class="uusub2" onclick="javascript:window.history.go(-1);" /></div>
    </div>

            <div class="clearFoot noPrint">
            </div>
        </div>
    </div>
        </div>
    </div>
</body>
</html>
