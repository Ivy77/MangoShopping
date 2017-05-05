<?php
/*
 * This php file enables users to edit a particular product
 *
 */

// this kicks users out if they are not logged in
    session_start();
    if (!isset($_SESSION['email'])) {
        header('Location: Login.php');
        exit;
    }

 
 
    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user submitted the form with updates, we process the form with this block of code
     *
     */
    if (isset($_POST['submit'])) {
        // process the update if the form was submitted
        
        // get data from form
        $ProductID = $_POST['ProductID'];
        if (!isset($ProductID)) {
            // if for some reason the id didn't post, kick them back to InsertProduct1.php
            header('Location: InsertProduct1.php');
            exit;
        }

   
        // get data from form
        $ProductName = $_POST['ProductName'];
        //$CategoryID = $_POST['CategoryID'];
        $ProductDescription = $_POST['ProductDescription'];
        $UnitPrice = $_POST['UnitPrice'];
        $UnitCost = $_POST['UnitCost'];
        $Unit = $_POST['Unit'];
        
        // get toppings selected by user in the form
        $CategoryID = $_POST['Category-CategoryID'];
        
        // variable to keep track if the form is complete (set to false if there are any issues with data)
        $isComplete = true;
        
        // error message we'll give user in case there are issues with data
        $errorMessage = "";
        
        
        // check each of the required variables in the table        
        if (!isset($CategoryID)) {
            $errorMessage .= "Please select a category for the product.\n";
            $isComplete = false;
        }
        
        if (!isset($ProductName)) {
            $errorMessage .= "Please enter a name for the product.\n";
            $isComplete = false;
        }
        
        if (!isset($UnitPrice)) {
            $errorMessage .= "Please enter the unit price for the product.\n";
            $isComplete = false;
        } else if ($UnitPrice < 0) {
            $errorMessage .="Please enter a valid unit price.\n";
            $isComplete = false;
        }
        if (!isset($UnitCost)){
            $errorMessage .= "Please enter the unit cost paid to the vendor for the product.\n";
            $isComplete = false;
        }
        
        if (!isset($Unit)) {
            $errorMessage .= "Please enter the unit of measure for the product.\n";
            $isComplete = false;
        }
        
        // If there's an error, they'll go back to the form so they can fix it
        
        if($isComplete) {
            // if there's no error, then we need to update
            
            //
            // first update product record

            $query = "UPDATE Products SET CategoryID=$CategoryID, ProductName='$ProductName', ProductDescription='$ProductDescription', UnitPrice=$UnitPrice, UnitCost=$UnitCost, Unit = '$Unit' WHERE ProductID=$ProductID;";
            
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // run the update
            $result = queryDB($query, $db);            
                    
            //
            // now we need to update the toppings
          
            // now that we are done, send user back to InsertProduct1.php and exit 
            header("Location: InsertProduct1.php?successmessage=Successfully updated product $ProductName");
            exit;
        }        
    } else {
        //
        // if the form was not submitted (first time in)
        //
    
        /*
         * Check if a GET variable was passed with the id for the product
         *
         */
        if(!isset($_GET['ProductID'])) {
            // if the id was not passed through the url
            
            header('Location: InsertProduct1.php');
            exit;
        }
        
        /*
         * Now we'll check to make sure the id passed through the GET variable matches the id of a product in the database
         */
        
        // connect to the database
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up a query
        $ProductID = $_GET['ProductID'];
        $query = "SELECT * FROM Products WHERE ProductID=$ProductID;";
        
        // run the query
        $result = queryDB($query, $db);
        
        // if the id is not in the pizza table, then we need to send the user back to pizza.php
        if (nTuples($result) == 0) {
            // send them out to InsertProduct1.php and stop executing code in this page
            header('Location: InsertProduct1.php');
            exit;
        }
        
        /*
         * Now we know we got a valid pizza id through the GET variable
         */
        
        // get data on product to fill out form with existing values
        $row = nextTuple($result);
        
        $ProductName = $row['ProductName'];
        $CategoryID = $row['CategoryID'];
        $ProductDescription = $row['ProductDescription'];
        $UnitPrice = $row['UnitPrice'];
        $UnitCost = $row['UnitCost'];
        $Unit = $row['Unit'];
        
    }
?>


<html>
    <head>
<!-- Bootstrap links -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>        
        
        <title>Update Product <?php echo $ProductName; ?></title>
    </head>
    
    <body>
       
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Update Product <?php echo $ProductName ?></h1>        
    </div>
</div>


<!-- Showing errors, if any -->
<div class="row">
    <div class="col-xs-12">
<?php
    if (isset($isComplete) && !$isComplete) {
        // executes only if form was previously submitted (and therefore $isComplete is set) and isComplete was set to false
        // you'll never be here if the form wasn't submitted (the first time you get in)
        
        echo '<div class="alert alert-danger" role="alert">';
        echo ($errorMessage);
        echo '</div>';
    }
?>            
    </div>
</div>



<!-- form to update product -->
<div class="row">
    
        
        <form action="UpdateProduct.php" method="post">
<!-- product name -->
<div class="form-group">
    <label for="ProductName">Product Name:</label>
    <input type="text" class="form-control" name="ProductName" value="<?php if($ProductName) { echo $ProductName; } ?>"/>
</div>

<!-- Category -->
<div class="form-group">
    <label for="Category-CategoryID">Shape:</label>
    <?php
    // connect to the database
    if (!isset($db)) {
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    }
    echo (generateDropdown($db, "Category", "CategoryName", "CategoryID", $CategoryID));        
    ?>
</div>


<!-- Description -->
<div class="form-group">
    <label for="ProductDescription">Product Description (Please limit description to no more than 100 characters): </label>
    <input type="text" class="form-control" name="ProductDescription" value="<?php if($ProductDescription) { echo $ProductDescription; } ?>"/>
</div>


<!-- Price -->
<div class="form-group">
    <label for="UnitPrice">Unit Selling Price:</label>
    <input type="UnitPrice" class="form-control" name="UnitPrice" value="<?php if($UnitPrice) { echo $UnitPrice; } ?>"/>
</div>


<!-- Unit Cost -->
<div class="form-group">
    <label for="UnitCost">Unit Cost to Vendor:</label>
        <input type="UnitCost" class="form-control" name="UnitCost" value="<?php if($UnitCost) { echo $UnitCost; } ?>"/> 
</div>

<div class="form-group">
    <label for="Unit">Unit of measure</label>
    <input type="Unit" class="form-control" name="Unit" value="<?php if($Unit){echo $Unit ;} ?>"/>

<!-- hidden id (not visible to user, but need to be part of form submission so we know which pizza we are updating -->
<input type="hidden" name="ProductID" value="<?php echo $ProductID; ?>"/>

<button type="submit" class="btn btn-default" name="submit">Save</button>

</form>
        
        
    </div>


       
       
        
    </body>
</html>