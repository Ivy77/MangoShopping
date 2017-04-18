<!-- this file cannot insert a product into the db yet. Probably because cannot be entered-->

<!--to do: welcome user-->
<!--todo: error prevention: duplicate product-->

<!--This file will enable sellers to enter new products, and see existing products in the db-->
<html>
    <head>
        <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <title>Enter Product</title>
         
    </head>
    <body>
<!--This is the php code to manage the data submitted by the form-->

<!--Menu bar-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-left">
            <li>
                <a href="InputAdministrator.php">Insert New Administrator</a>
            </li>
            <li class="active">
                <a href="InsertProduct1.php">Insert New Product</a>
            </li>
            <li>
                <a href="InsertCategory.php">Insert New Category</a>
            </li>
            <li>
                <a href="Orders.php">View Orders</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="Logout.php">Log out</a></li>
        </ul>
    </div>
</nav>
<?php

include_once('config.php');
include_once('dbutils.php');
// check if form data needs to be processed

if(isset($_POST['submit'])){
    //if we are here, it means that form was submitted and we need to process form data
    
    //get data from the form
    $ProductName = $_POST['ProductName'];
    $ProductDescription = $_POST['ProductDescription'];
    $UnitPrice = $_POST['UnitPrice'];
    $UnitCost = $_POST['UnitCost'];
    $Unit = $_POST['Unit'];
    
    //get CategoryID selected by user in the dropdown list
    $CategoryID = $_POST['Category-CategoryID'];
    //////////////////////////
    echo $CategoryID;   

    /////////////////////////////
    //Variables to keep track if the form is complete 
    $isComplete = true;
    
    //error message we'll give to user in case there are any issues with the data
    $errorMessage = "";
 
    //check each of the requirements
    if(!isset($ProductName)){
        $errorMessage.="Please enter a name for the product\n";
        $isComplete = false;
    }
    if(!isset($UnitPrice)){
        $errorMessage.="Please enter the unit price for the product\n";
        $isComplete = false;   
    }
    if(!isset($UnitCost)){
        $errorMessage.="Please enter the unit cost for the product\n";
        $isComplete = false;
    }
    
    if(!isset($Unit)){
        $errorMessage.="Please enter the unit for the product\n";
        $isComplete = false;
    }
    if(!isset($CategoryID)){
        $errorMessage.="Please select a category for the product\n";
        $isComplete = false;
    }
    if($isComplete){
    
    //insert record SQL
    $query = "INSERT INTO Products(CategoryID, ProductName, ProductDescription, UnitPrice, UnitCost, Unit) VALUES('$CategoryID','$ProductName','$ProductDescription',$UnitPrice,'$UnitCost','$Unit');";
    
    //connect to db
    $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
    
    //run the insert stmt
    $result = queryDB($query,$db);
    
    $ProductID = mysqli_insert_id($db);
    
    
    if ($_FILES['Picture']['size']>0){
     //if there is a Picture
     echo "there's a picture";
     echo '<br>';
     echo $_FILES;
     echo '<br>';
     //copy image to images directory
     $tmpName = $_FILES['Picture']['tmp_name'];
     echo $tmpName;
     $fileName = $_FILES['Picture']['name'];
     echo '<br>';
     echo $fileName;
     echo($_FILES['Picture']);
     $newFileName = $imagesDir.$ProductID.$fileName;
     echo '<br>';
     echo $newFileName;
     if(move_uploaded_file($tmpName,$newFileName)){
        //since we successfully copied the file, we now enter its filename in the Products table
        $query = "UPDATE Products SET Picture = '$newFileName' WHERE ProductID = $ProductID;";
        
        //run insert query
        queryDB($query,$db);
     }else{
        echo "error copying image";
     }
    }
    
    $success = ("Successfully entered new product".$ProductName);
    
    // reset values of variables so the form is cleared
    unset($ProductName,$ProductDescription,$UnitPrice,$UnitCost,$Unit,$CategoryID);
    
    
    }
}       
?>    
    
        <!--Title_-->
        <div class='row'>
            <div class='col-xs-12'>
                <h1>Enter New Product</h1>
            </div>
        </div>

<!--Showing errors, if any-->
<div class="row">
    <div class="col-xs-12">
         <?php
    if(isset($isComplete) && !$isComplete){
        echo '<div class="alert alert-danger" role="alert">';
        echo($errorMessage);
        echo '</div>';
    }
?>
   
        </div>
    </div>
</div>

<!-- Showing successfully entering the new product, if that actually happend -->
<div class="row">
    <div class="col-xs-12">
<?php
    if(isset($success)){
        // for successes after the form was submitted
        echo '<div class="alert alert-success" role = "alert">';
        echo($success);
        echo '</div>';
    }elseif(isset($_GET['successmessage'])){
        // for success from another form that redirects users to this page
        echo '<div class = "alert alert-success" role="alert">';
        echo($_GET['successmessage']);
        echo '</div>';
    }


?>
       
    </div>    
 
</div>



<!--Form to enter new product-->        
        <div class='row'>
            <div class='col-xs-12'>
                <form action="InsertProduct1.php" method="post" enctype="multipart/form-data">
                    
                    
<!--ProductName-->
<div class="formg-roup">
    <label for="ProductName">Product Name:</label>
    <input type= "text" class="form-control" name="ProductName" value="<?php if($ProductName){echo $ProductName;} ?>"></input>
</div>

<!--Category-->
<div class="form-group">
    <label for="CategoryID">Category: </label>
    <?php
    //connect to the database
    if (!isset($db)){
        $db = connectDB($DBHost,$DBUser,$DBPasswd,$DBName);
    }
    //why is the last argument $CategoryID ???
    echo(generateDropdown($db,"Category","CategoryName","CategoryID",$CategoryID));

    ?>
</div>

<!--ProductDescription-->
<div class="formg-roup">
    <label for="ProductDescription">Product Description:</label>
    <input type= "text" class="form-control" name="ProductDescription" value="<?php if($ProductDescription){echo $ProductDescription;} ?>"></input>
</div>
<!--UnitPrice-->
<div class="formg-roup">
    <label for="UnitPrice">Unit Price:</label>
    <input type= "text" class="form-control" name="UnitPrice" value="<?php if($UnitPrice){echo $UnitPrice;} ?>"></input>
</div>
<!--UnitCost-->
<div class="formg-roup">
    <label for="UnitCost">Unit Cost:</label>
    <input type= "text" class="form-control" name="UnitCost" value="<?php if($UnitCost){echo $UnitCost;} ?>"></input>
</div>
<!--Unit-->
<div class="formg-roup">
    <label for="Unit">Unit:</label>
    <input type= "text" class="form-control" name="Unit"  value="<?php if($Unit){echo $Unit;} ?>"></input>
</div>

<!--Picture-->
<div class="form-group">
    <label for="Picture">Picture of Product</label>
    <input type='file' class="form-control" name="Picture"/>
</div>

<button type="submit" class="btn btn-default" name = "submit">Save</button>
                </form>
            </div>
        </div>






<!--Show contents of product table-->
<div class='row'>
    <div class='col-xs-12'>
        <!--set up HTML table to show contents-->
        <table class="table table-hover">
            <!--headers for table-->
            <thead>
                <th>Product Name</th>
                <th>Category</th>
                <th>Product Description</th>
                <th>Unit Price</th>
                <th>Unit Cost</th>
                <th>Unit</th>
                
            </thead>
            

<?php
    /*
     *List all the products in the database
     *
     */
    // Connect to the database
    
    include_once('config.php');
    include_once('dbutils.php');
    // connect to the db
    $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
    
    // set up a query to get info on the products from the database
    //$query = "SELECT * FROM Products;";
    $query = 'SELECT Products.ProductID,Products.ProductName, Category.CategoryName as Category, ProductDescription, UnitPrice, UnitCost, Unit, Picture FROM Products, Category WHERE Products.CategoryID = Category.CategoryID;';
    //run the query
    //queryDB is a function from dbutils file
    $result = queryDB($query,$db);


    

    while($row = nextTuple($result)){
        echo "\n <tr>";
        echo "<td>".$row["ProductName"]."</td>";
        echo "<td>".$row["Category"]."</td>";
        echo "<td>".$row["ProductDescription"]."</td>";
        echo "<td>".$row["UnitPrice"]."</td>";
        echo "<td>".$row["UnitCost"]."</td>";
        echo "<td>".$row["Unit"]."</td>";
        echo "</tr> \n";
        
        // Picture
        echo "<td>";
        if($row['Picture']){
            $imageLocation = $row['Picture'];
            $altText = 'Products'.$row['ProductName'];
            echo "<img src='$imageLocation' width='150' alt='altText'>";
        }
        echo "</td>";
        
        // link to update record (Products)
        echo "<td><a href='UpdateProduct.php?ProductID = " . $row['ProductID'] . "'>update</a></td>";
        
        // link to delete record
        echo "<td><a href='DeleteProduct.php?ProductID=" . $row['ProductID'] . "'>delete</a></td>";
        
        echo "</tr> \n";
    }

?>              
        </table>
       
    </div>
</div>
        





    </body>
    
</html>
