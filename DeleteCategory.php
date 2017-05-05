<?php
/*
 * This php file prompts users on whether they want to delete a particular product
 * It obtains the id for the pizza to delete from an id variable passed using the GET method (in the url)
 *
 */
    session_start();
    if (!isset($_SESSION['email'])) {
        header('Location: Login.php');
        exit;
    }

    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user just made a decision on a deletion by using the form below, we process that below
     *
     */
    if (isset($_POST['submit'])) {
        // process the deletion (if selected) if the form below was submitted        
        
        // get data from form
        $CategoryID = $_POST['CategoryID'];
        $delete = $_POST['delete'];
        
        
        
        
        
        if ($delete == 'yes') {
            // if the user said yes to delete, we need to delete the product with ProductID = $ProductID
            
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // first delete any OrderProduct records that point to this product
            $query = "DELETE FROM Category WHERE CategoryID = $CategoryID;";
            
            // run the delete statement to remove OrderProduct records that point to this product
            queryDB($query, $db);
            
        }
        
        header('Location: InsertCategory.php');
        exit;
    }
    
    

    /*
     * Check if a GET variable was passed with the id for the category
     *
     */
    if(!isset($_GET['CategoryID'])) {
        // if the id was not passed through the url
        
        header('Location: InsertCategory.php');
        exit;
    }else{
        //Categories that have products cannot be deleted
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
      // set up a query
        $CategoryID = $_GET['CategoryID'];
        $query = "SELECT * FROM Products WHERE CategoryID=$CategoryID;";
        $result = queryDB($query, $db);
    
        // if the id is not in the pizza table, then we need to send the user back to pizza.php
        if (nTuples($result) != 0) {
        // send them out to pizza.php and stop executing code in this page
        header('Location: InsertCategory.php');
        exit;
    }
        
        
    }
    
    /*
     * Now we'll check to make sure the id passed through the GET variable matches the id of a product in the database
     */
    
    // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
    
    // set up a query
    $CategoryID = $_GET['CategoryID'];
    $query = "SELECT * FROM Category WHERE CategoryID=$CategoryID;";
    
    // run the query
    $result = queryDB($query, $db);
    
    // if the id is not in the pizza table, then we need to send the user back to pizza.php
    if (nTuples($result) == 0) {
        // send them out to pizza.php and stop executing code in this page
        header('Location: InsertCategory.php');
        exit;
    }
    
    /*
     * Now we know we got a valid product id through the GET variable
     */
    
    // get some data from the category table to ask a better question when confirming deletion
    $row = nextTuple($result);
    
    $CategoryName = $row['CategoryName'];    
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
        
        <title>Delete <?php echo $CategoryName; ?> Category?</title>
    </head>
    
    <body>
        
<!-- Visible title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Do you want to delete the <?php echo $CategoryName; ?> Category?</h1>
    </div>
</div>

<!-- form to ask users to confim deletion -->
<div class="row">
    <div class="col-xs-12">
        <form action="DeleteCategory.php" method="post">
    <div class="radio">
        <label>
            <input type="radio" name="delete" value="yes" checked>
            Yes
        </label>
    </div>
    <div class="radio">
        <label>
            <input type="radio" name="delete" value="no">
            No
        </label>
    </div>
    
    <input type="hidden" name="CategoryID" value="<?php echo $CategoryID; ?>"/>
    
    <button type="submit" class="btn btn-default" name="submit">Submit</button>
</form>

    </div>
</div>
        
    </body>
</html>