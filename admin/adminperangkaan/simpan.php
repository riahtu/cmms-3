<?php
// capaian mesti melalui page admin
defined( '_UMPORTAL_ADMIN' ) or die( 'Akses tidak dibenarkan !' );


 global $dbi;
  $id=$_REQUEST["id"];  
  $author=$_REQUEST["txt_author"];  
  $title=$_REQUEST["txt_title"];
  $content=$_REQUEST["txt_content"];
  $active=$_REQUEST["txt_active"];
  if (!isset($active))
    $active=0;
  $now = date("Y-m-d G:i:s");	
  $dbtrans=$_REQUEST["dbtrans"];  
  if ($dbtrans=="0") //insert
     $qry="insert into perangkaan(author,title,createddate,content,active)
	       values('$author','$title','$now','$content','1')"; 
  else if ($dbtrans=="1") //update
     $qry="update perangkaan set author='$author',title='$title',content='$content',active='1'";
		   
  sql_query($qry,$dbi);
  //echo $qry;
?>
<script type="text/javascript">
alert('Mesej berjaya disimpan ?');
</script>
<?php  
  pageredirect("admin.php?module=adminperangkaan");
  

?>


 
