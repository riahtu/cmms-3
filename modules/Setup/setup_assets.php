<script type="text/javascript">
    function checkform(){
        if (document.frmtask.txtSystemGroup.value=="") {
            alert("Sila pilih Kumpulan Sistem.");
            document.frmtask.txtSystemGroup.focus();
            return false;
        }
        else if (document.frmtask.txtAssetGroup.value==""){
            alert("Sila pilih Kumpulan Aset.");
            document.frmtask.txtAssetGroup.focus();
            return false;

        }
        else if (document.frmtask.txtZone.value==""){
            alert("Sila pilih zon.");
            document.frmtask.txtZone.focus();
            return false;
        }
        else if (document.frmtask.txtAssetDesc.value==""){
            alert("Sila masukkan penerangan aset.");
            document.frmtask.txtAssetDesc.focus();
            return false;
        }
        else{
            return confirm("Adakah anda pasti?");
        }
    }
</script>
<?php

$tid = $_GET['assetid'];
$staffagid = $_SESSION['staffagid'];

if($_POST['submit']){
    $taskdesc = mysql_real_escape_string($_POST['txtAssetDesc']);
    $systemgroup = $_POST['txtSystemGroup'];
    $assetgroup = $_POST['txtAssetGroup'];
    $assetzone = $_POST['txtZone'];
    
    $flg = $_POST['flg'];
    
    if($flg == "add"){
        $insert = "INSERT INTO asset (asset_desc,asset_ag_id,asset_zone,sg_id) VALUES ('$taskdesc','$assetgroup','$assetzone',$systemgroup')";
        sql_query($insert,$dbi);
    } elseif($flg == "edit"){
        $update = "UPDATE asset SET asset_desc='$taskdesc', asset_ag_id='$assetgroup', asset_zone='$assetzone', sg_id='$systemgroup' WHERE asset_id='$tid'";
        //die($update);
        sql_query($update,$dbi);
    }
    
    pageredirect("mainpage.php?module=Setup&task=list_asset");
    
}


$flg = "add";
$check = "SELECT asset_id, asset_desc, asset_ag_id, sg_id, asset_zone FROM asset WHERE asset_id='$tid'";
//echo $check;
$result = sql_query($check,$dbi);
if($a = mysql_fetch_array($result)){
    $flg = "edit";
    $adesc = $a['asset_desc'];
    $aid = $a['asset_id'];
    $tagid = $a['asset_ag_id'];
    $tsgid = $a['sg_id'];
    $tzid = $a['asset_zone'];
}

?>
<form name="frmtask" method="POST" action="">
<table width="100%" cellspacing="3" cellpadding="3" align="center" class="outerform">
    <tr>
        <td style="font-weight:bold;" class="formheader" colspan="3">Aset</td>
    </tr>
    <tr>
        <td width="220" valign="middle" class="title">Kumpulan Sistem</td>
        <td width="5" valign="middle" class="title">:</td>
        <td>
            <select name="txtSystemGroup" id="txtSystemGroup">
                <option value="">- PILIH -</option>
                <?php
                    $sql = "SELECT sg_id, sg_desc FROM system_group ORDER BY sg_id";
                    $res = mysql_query($sql,$dbi);
                    while($sgdata = mysql_fetch_array($res)){
                        $sgid = $sgdata['sg_id'];
                        $sgdesc = $sgdata['sg_desc'];

                        echo "<option ";
                        if($sgid==$tsgid){
                            echo " SELECTED ";
                        }
                        echo" value='$sgid'>$sgdesc</option>";
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="220" valign="middle" class="title">Kumpulan Aset</td>
        <td width="5" valign="middle" class="title">:</td>
        <td>
            <select name="txtAssetGroup" id="txtAssetGroup">
                <option value="">- PILIH -</option>
                <?php
                    $sql = "SELECT ag_id, ag_desc FROM asset_group WHERE 1";
                    if($staffagid<>0){
                        $sql.=" and ag_id='$staffagid'";
                    }
                    $sql .=" ORDER BY ag_id";
                    $res = mysql_query($sql,$dbi);
                    while($agdata = mysql_fetch_array($res)){
                        $agid = $agdata['ag_id'];
                        $agdesc = $agdata['ag_desc'];

                        echo "<option ";
                        if($agid==$tagid){
                            echo " SELECTED ";
                        }
                        echo" value='$agid'>$agdesc</option>";
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="220" valign="middle" class="title">Zon</td>
        <td width="5" valign="middle" class="title">:</td>
        <td>
            <select name="txtZone" id="txtZone">
                <option value="">- PILIH -</option>
                <?php
                    $sql = "SELECT zon_id, zon_desc FROM zone WHERE 1 ";
                    if($tagid<>"")
                        $sql .= "AND ag_id='$tagid'";
                    $sql .= " ORDER BY zon_id";
                    $res = mysql_query($sql,$dbi);
                    while($agdata = mysql_fetch_array($res)){
                        $agid = $agdata['zon_id'];
                        $agdesc = $agdata['zon_desc'];

                        echo "<option ";
                        if($agid==$tzid){
                            echo " SELECTED ";
                        }
                        echo" value='$agid'>$agdesc</option>";
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td width="220" valign="middle" class="title">Penerangan Aset</td>
        <td width="5" valign="middle" class="title">:</td>
        <td>
            <textarea name="txtAssetDesc" rows="3" wrap="physical" cols="100%"><?php echo $adesc;?></textarea>
        </td>
    </tr>
    <!-- <tr>
        <td width="120">Asset Group</td>
        <td width="5">:</td>
        <td><input type="text" name="txtTag" size="40" value="<?php echo $tagid;?>"/></td>
    </tr>
    <tr>
        <td width="120">Asset</td>
        <td width="5">:</td>
        <td><input type="text" name="txtTasset" size="40" value="<?php echo $tasset;?>"/></td>
    </tr>
    <tr>
        <td width="120">Person In Charge</td>
        <td width="5">:</td>
        <td><input type="text" name="txtTstaff" size="40" value="<?php echo $tstaff;?>"/></td>
    </tr>
    <tr>
        <td width="120">System</td>
        <td width="5">:</td>
        <td><input type="text" name="txtTsystem" size="40" value="<?php echo $tsystem;?>"/></td>
    </tr>
    <tr>
        <td width="120">Date</td>
        <td width="5">:</td>
        <td><input type="text" name="txtTdate" size="40" value="<?php echo $tdate;?>"/></td>
    </tr> -->
    <tr>
        <td colspan="3">
            <input type="hidden" name="assetid" value="<?php echo $aid;?>"/>
            <input type="hidden" name="flg" value="<?php echo $flg;?>"/>
            <input type="submit" value="Hantar" name="submit" class="button"/ onClick="return checkform();">
            <input type="button" name="back" value="Kembali" onclick="location.href='mainpage.php?module=Setup&task=list_asset'" class="button"/>
        </td>
    </tr> 
</table>
</form>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript">
$(function() {

 $("#txtAssetGroup").bind("change", function() {

     $.ajax({
         type: "GET",
         url: "modules/Setup/zone.php",
         data: "txtAssetGroup="+$("#txtAssetGroup").val(),
         success: function(html) {
             $("#txtZone").html(html);
         }
     });
 });

});
 </script>