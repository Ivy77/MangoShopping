<?php
include_once('config.php');
include_once('dbutils.php');
$db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);

//$connect = mysqli_connect("localhost", "root", "", "testing1");
$tab_query = "SELECT * FROM Category ORDER BY CategoryID ASC";
$tab_result = queryDB($tab_query,$db);
$tab_menu = '';
$tab_content = '';
$i = 0;
while($row = mysqli_fetch_array($tab_result))
{
 if($i == 0)
 {
  $tab_menu .= '
   <li class="active"><a href="#'.$row["CategoryID"].'" data-toggle="tab">'.$row["CategoryName"].'</a></li>
  ';
  $tab_content .= '
   <div id="'.$row["CategoryID"].'" class="tab-pane fade in active">
  ';
 }
 else
 {
  $tab_menu .= '
   <li><a href="#'.$row["CategoryID"].'" data-toggle="tab">'.$row["CategoryName"].'</a></li>
  ';
  $tab_content .= '
   <div id="'.$row["CategoryID"].'" class="tab-pane fade">
  ';
 }
 
 $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
 $product_query = "SELECT * FROM Products WHERE CategoryID = '".$row["CategoryID"]."'";
 $product_result = queryDB($product_query,$db);
 while($sub_row = mysqli_fetch_array($product_result))
 {
  $tab_content .= '
  <div class="col-md-3" style="margin-bottom:36px;">
   <img src="'.$sub_row["Picture"].'" class="img-responsive img-thumbnail" />
   <h4>'.$sub_row["ProductName"].'</h4>
  </div>
  ';
 }
 $tab_content .= '<div style="clear:both"></div></div>';
 $i++;
}
?>

<!DOCTYPE html>
<html>
 <head>
  <title>Browsing Page</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 </head>
 <body>
  <div class="container">
   <h2 align="center">Name of Store -- Set this as a variable</a></h2>
   <br />
   <ul class="nav nav-tabs">
   <?php
   echo $tab_menu;
   ?>
   </ul>
   <div class="tab-content">
   <br />
   <?php
   echo $tab_content;
   ?>
   </div>
  </div>
 </body>
</html>