<script type="text/javascript">
    function carisistem(){
        var carian=document.frmcariansistem.txtSystemGroup.value;
        var cariaset=document.frmcarianaset.CarianAset.value;

        if(cariaset=="")
            location.href="mainpage.php?module=Setup&task=list_engineer&kumpcariansistem="+carian;
        else
            location.href="mainpage.php?module=Setup&task=list_engineer&kumpcariansistem="+carian+"&kumpcarianaset="+cariaset;
    }

    function cariaset(){
        var carianaset=document.frmcarianaset.txtAsetGroup.value;
        var carisistem=document.frmcariansistem.CarianSistem.value;

        if(carisistem=="")
            location.href="mainpage.php?module=Setup&task=list_engineer&kumpcarianaset="+carianaset;
        else
            location.href="mainpage.php?module=Setup&task=list_engineer&kumpcarianaset="+carianaset+"&kumpcariansistem="+carisistem;
    }
</script>
<?php

include('include/function.php');
$Mfunction = new fungsi();
$limit = 25;

if(!isset($_GET["limit"]))
  $rowstart = 0;
else
  $rowstart = $_GET["limit"];

$staffagid = $_SESSION['staffagid'];
  

if($_POST['submit']){
    $code = $_POST['txtSearchCode'];
    $desc = mysql_real_escape_string($_POST['txtSearchDescription']);
}

if($_GET['delete']=="1"){
    $iddelete = $_GET['iddelete'];
    
    $delete = "DELETE FROM tbl_engineer WHERE eng_id='$iddelete'";
    sql_query($delete,$dbi);

    $deleteuser = "DELETE FROM user WHERE staff_id='$iddelete' or login='$iddelete'";
    // die($deleteuser);
    sql_query($deleteuser,$dbi);
    
    pageredirect("mainpage.php?module=Setup&task=list_engineer");
}

$kumpsistem=$_GET["kumpcariansistem"];
$kumpaset=$_GET["kumpcarianaset"];

?>
<div style="float:left;">
    <table>
        <form name="frmcariansistem">
        <tr>
            <td>Kump. Sistem</td>
            <td>:</td>
            <td>
                <input type="hidden" name="CarianSistem" id="CarianSistem" value="<?php echo $kumpsistem; ?>" />
                <select name="txtSystemGroup" id="txtSystemGroup" onchange="return carisistem();">
                    <option value="">- SEMUA -</option>
                    <?php
                        $sql = "SELECT sg_id, sg_desc FROM system_group ORDER BY sg_id";
                        $res = mysql_query($sql,$dbi);
                        while($sgdata = mysql_fetch_array($res)){
                            $sgid = $sgdata['sg_id'];
                            $sgdesc = $sgdata['sg_desc'];

                            echo "<option value='$sgid' "; 
                            if($sgid==$kumpsistem)
                                echo "selected "; 
                            echo ">$sgdesc</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        </form>
    </table>
</div>
<div style="float:left;">
    <table>
        <form name="frmcarianaset">
        <?php
            if($staffagid<>0){
                echo "<input type=\"hidden\" name=\"CarianAset\" id=\"CarianAset\" value=\"\" /> ";
            }
            else{
        ?>  
        <tr>
            <td>Kump. Aset</td>
            <td>:</td>
            <td>
                <input type="hidden" name="CarianAset" id="CarianAset" value="<?php echo $kumpaset; ?>" />
                <select name="txtAsetGroup" id="txtAsetGroup" onchange="return cariaset();">
                    <option value="">- SEMUA -</option>
                    <?php
                        $sql = "SELECT ag_id, ag_desc FROM asset_group WHERE 1 ";
                        if($staffagid<>0){
                            $sql.=" and ag_id='$staffagid'";
                        }
                        $sql.=" ORDER BY ag_id";
                        $res = mysql_query($sql,$dbi);
                        while($sgdata = mysql_fetch_array($res)){
                            $sgid = $sgdata['ag_id'];
                            $sgdesc = $sgdata['ag_desc'];

                            echo "<option value='$sgid' "; 
                            if($sgid==$kumpaset)
                                echo "selected "; 
                            echo ">$sgdesc</option>";
                        }
                    ?>
                </select>
            </td>
        </tr>
        <?php } ?>
        </form>
    </table>
</div>
<div style="text-align:right;font-weight:bold;"><a href="mainpage.php?module=Setup&task=setup_engineer">Tambah<img src="images/admin/btn_add.gif"></a></div><br>
<table width="100%" cellspacing="1" cellpadding="3" align="center" class="table">
    <tr>
        <td style="font-weight:bold;" class="formheader" colspan="6">Senarai Jurutera</td>
    </tr>
    <tr>
        <th class="formheader" width="100" align="center">ID</th>
        <th class="formheader">Nama</th>
        <th class="formheader" width="100">Kump. Sistem</th>
        <th class="formheader" width="100">Kump. Aset</th>
        <!-- <th class="formheader" width="100">Sub Sistem</th> -->
        <th class="formheader" width="100" align="center">Tindakan</th>
    </tr>
    <?php
    
    $sql = "SELECT eng_id, eng_name, eng_ag_id, eng_sg_id from tbl_engineer WHERE 1 ";
    // Ikut kumpulan sistem
    if ($kumpsistem<>"")
        $sql.="and eng_sg_id='$kumpsistem' ";
    // Ikut kumpulan aset
    if ($staffagid<>0)
        $sql.="and eng_ag_id='$staffagid' ";
    if($kumpaset<>"")
        $sql.="and eng_ag_id='$kumpaset' ";
    $sql.="ORDER BY eng_id";
    $sqlfull = $sql." LIMIT ".$rowstart.", ".$limit;
    $res = sql_query($sql,$dbi);
    $resfull = sql_query($sqlfull,$dbi);
    $cnt=$rowstart;
    $numrows = mysql_num_rows($res);
    while($data = mysql_fetch_array($resfull)){
        $tid = $data['eng_id'];
        $tdesc = $data['eng_name'];
        $tagid = $data['eng_ag_id'];
        $tsgid = $data['eng_sg_id'];
        // $ttgid = $data['eng_tg_id'];
        $cnt++;
        
        echo "<tr bgcolor=\"$bgcolor\" onMouseOver=\"this.bgColor = '$hlcolor'\" onMouseOut =\"this.bgColor = '$bgcolor'\">\n";
        echo "<td width=\"70\" align=\"center\">$tid</td>";
        echo "<td>$tdesc</td>";
        echo "<td>".GetDesc("system_group","sg_desc","sg_id",$tsgid)."</td>";
        echo "<td>".GetDesc("asset_group","ag_desc","ag_id",$tagid)."</td>";
        echo "<td align=\"center\"><a href=\"mainpage.php?module=Setup&task=setup_engineer&taskid=$tid\"><img src=\"images/admin/btn_edit.gif\"/></a>&nbsp;&nbsp;<a href=\"mainpage.php?module=Setup&task=list_engineer&delete=1&iddelete=$tid\" onClick=\"return confirm('Do you wish to proceed?');\"><img src=\"images/admin/btn_delete.gif\"/></a></td>";
        echo "</tr>";
    }
    
    ?>
</table>
<div style="text-align:center;">
    <?php
    print $Mfunction->page("?module=Setup&task=list_technician", $limit, $rowstart, $numrows);
    ?>
</div>
    
