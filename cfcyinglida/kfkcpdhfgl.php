<?php session_start();
@header('Content-type: text/html;charset=utf-8');
include('newdb.php');

$pagesize = 10;//每页显示的条数：
$url = $_SERVER["REQUEST_URI"];//获取本页地址-网址
$url = parse_url($url);// 解析网址--得到的是一数组
//print_r($url);
$url[query] = preg_replace("/page=(\d+)&/", "", $url[query]);
$url[query] = preg_replace("/&page=(\d+)/", "", $url[query]);
$url[query] = preg_replace("/page=(\d+)/", "", $url[query]);

if ($url[query] != "undefined" && $url[query] != "") {
    $url = $url[path] . "?" . $url[query] . "&";
} else {
    $url = $url[path] . "?";//得到解析网址的 具体信息
}
if ($_GET[page]) {
    $pageval = $_GET[page];
    $page = ($pageval - 1) * $pagesize;
    $page .= ',';
}

//if ($_GET[guanjianci] != "") {
//    $guanjianci = $_GET[guanjianci];
//
//    $guanjiancisql = "(";
//    $guanjiancisql .= " `ypgg` LIKE '%" . $guanjianci . "%'";
//
//    $guanjiancisql .= ") ";
//
//}
if ($_GET[kshrq] != "" && $_GET[jshrq] != "") {
    $kshrq = $_GET[kshrq];
    $jshrq = $_GET[jshrq];

    if ($guanjiancisql == "") {
        $guanjiancisql = "( `pdrq` >= '" . $kshrq . "' and `pdrq` <= '" . $jshrq . "' )";
    } else {
        $guanjiancisql .= " and ( `pdrq` >= '" . $kshrq . "' and `pdrq` <= '" . $jshrq . "' )";
    }
}
if ($_GET[yf] != "") {//按药房
    if ($guanjiancisql == "") {
        $guanjiancisql = "`dwmch`='" . $_GET[yf] . "'";
    } else {
        $guanjiancisql .= " and `dwmch`='" . $_GET[yf] . "'";
    }
}


if ($guanjiancisql == "") {
    $numsql = "SELECT * FROM `kfkcpd` WHERE `islock`=1 GROUP  BY `dwmch`";
} else {
    $numsql = "SELECT * FROM `kfkcpd` WHERE `islock`=1 AND " . $guanjiancisql . " GROUP BY `dwmch`";
}
$numq=mysql_query($numsql);
$num = mysql_num_rows($numq);//获取总条数
$pagenum = ceil($num/$pagesize);

$html_title = "库存盘点锁定恢复";
include('spap_head.php');
?>
<div class="main">
    <div class="insmain">
        <div class="thislink">当前位置：<a href="yppsgl.php"><?php echo $html_title; ?></a></div>
        <div class="inwrap flt top">
            <div class="title w977 flt">
                <strong><?php echo $html_title; ?></strong>
            </div>
            <div class="incontact w955 flt">
                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                    <tr>
                        <td>
                            <div class="insinsins" style="width:100%;">
                                <span>
<!--                                    <input type="text" id="Guanjianci" name="Guanjianci"-->
<!--                                           value="--><?php //echo $_GET[guanjianci]; ?><!--" placeholder="药品规格"-->
<!--                                           class="grd-white"/>-->
                                    <input type="text" id="KaishiRiqi" name="KaishiRiqi" readonly="readonly"
                                           placeholder="请输入开始日期" size="12" value=""
                                           class="grd-white" style="width:120px"/>-
                                    <input type="text" id="JiezhiRiqi" name="JiezhiRiqi" readonly="readonly"
                                           placeholder="请输入结束日期" size="12" value="" class="grd-white"
                                           style="width:120px"/>
                                </span>
                            </div>
                            <div class="insinsins">
                                <span>
                                    <select id="YaoFangId" name="YaoFangId" style="width:400px;" class="grd-white2">
                                        <?php
                                        if ($_GET[yf] == "") {
                                            ?>
                                            <option selected="selected" value="">全部药房</option>
                                        <?php
                                        } else {
                                            ?>
                                            <option selected="selected"
                                                    value="<?php echo $_GET[yf]; ?>"><?php echo $_GET[yf]; ?></option>
                                            <option value="">全部药房</option>
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        $yfsql = "select id,yfshijx,yfmch from `yf` where `shfzt`='1' order by yfshijx ASC";
                                        $yfQuery_ID = mysql_query($yfsql);
                                        while ($yfRecord = mysql_fetch_array($yfQuery_ID)) {
                                            echo "<option value=\"" . $yfRecord[2] . "\">" . $yfRecord[1] . " " . $yfRecord[2] . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <input type="button" value="查找" onclick="chazhao();" class="uusub2"/>
                                </span>
                            </div>
            </div>


            <table width="100%" border="0" cellpadding="5" cellspacing="1" bgcolor="#cdcdcd">
                <tr style="color:#1f4248; font-weight:bold; height:30px;">
                    <td width="10%" align="center" bgcolor="#FFFFFF">药房名称</td>
                    <td width="9%" align="center" bgcolor="#FFFFFF">锁定日期</td>
                    <td width="9%" align="center" bgcolor="#FFFFFF">药房状态</td>
                    <td width="9%" align="center" bgcolor="#FFFFFF">操作</td>
                </tr>
                <?php

                $sql = "SELECT * FROM `kfkcpd` WHERE `islock`=1";
                if ($guanjiancisql != "") {
                    $sql .= " and". $guanjiancisql;
                }
                $sql .= " group by `dwmch` order by id DESC limit $page $pagesize ";
                $Query_ID = mysql_query($sql);
                while ($Record = mysql_fetch_array($Query_ID)) {
                    echo "<tr style=\"color:#1f4248;  font-size:12px;\">";
                    echo "<td align=\"center\" bgcolor=\"#FFFFFF\">" . $Record[4] . "</td>";

                    echo "<td align=\"center\" bgcolor=\"#FFFFFF\">";
                    echo $Record[2];
                    echo "</td>";

                    echo "<td align=\"center\" bgcolor=\"#FFFFFF\">";
                    echo "已锁定";
                    echo "</td>";

                    echo "<td align=\"center\" bgcolor=\"#FFFFFF\">";
                    echo "<a href=\"kfkcpdhfac.php?id=".$Record[0]."\">解除锁定</a>";
                    echo "</td>";


                    echo "</tr>";
                }
                ?>

            </table>
            <table width="100%" border="0" cellspacing="0" cellpadding="5" class="top">
                <tr>
                    <td>
                        <?php
                        include('pagefy.php');
                        ?>
                    </td>
                </tr>
            </table>
            <script type="text/javascript">
                function chazhao() {
                    var url = 'kfkcpdhfgl.php?kshrq=' + encodeURIComponent($('#KaishiRiqi').val()) +
                        '&jshrq=' + encodeURIComponent($('#JiezhiRiqi').val()) + '&yf=' + encodeURIComponent($('#YaoFangId').val());
                    location.href = url;
                }
                ;
                $(function () {
                    chooseDateRange('KaishiRiqi', 'JiezhiRiqi', true, true);
                    <?php
                    if($_GET[kshrq]!=""){
                    ?>
                    $("#KaishiRiqi").val('<?php echo $_GET[kshrq];?>');
                    <?php
                    }
                    if($_GET[jshrq]!=""){
                    ?>
                    $("#JiezhiRiqi").val('<?php echo $_GET[jshrq];?>');
                    <?php
                    }
                    ?>
                });
            </script>
        </div>
    </div>
</div>
</body>
</html>
