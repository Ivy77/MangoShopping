<?php
// this kicks users out if they are not logged in
    session_start();
    if (!isset($_SESSION['email'])) {
        header('Location: Login.php');
        exit;
    }

?>

<html>

<!--see if there is need to add a function to delete category-->
    
        <head>
<!-- Bootstrap links -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>        

        
        <title>Insert New Category</title>
    </head>
    

    
    <body>

<!-- Menu bar -->
<!--Menu bar-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="InputAdministrator.php">Insert New Administrator</a></li>
            <li><a href="InsertProduct1.php">Insert New Product</a></li>
            <li class="active"><a href="InsertCategory.php">Insert New Category</a></li>
            <li><a href="OrderFilter.php">View Orders</a>
            <li><a href="ViewProduct.php">View Products</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="Logout.php">Log out</a></li>
        </ul>
    </div>
</nav>    
    

<?php
// check if form data needs to be processed

// include config and utils files
include_once('config.php');
include_once('dbutils.php');

if (isset($_POST['submit'])) {
    // if we are here, it means that the form was submitted and we need to process form data
    
    // get data from form
    $CategoryName = $_POST['CategoryName'];
    
    // variable to keep track if the form is complete (set to false if there are any issues with data)
    $isComplete = true;
    
    // error message we'll give user in case there are issues with data
    $errorMessage = "";
    
    // check each of the required variables in the table
    if (!$CategoryName) {
        $errorMessage .= "Please enter a name for the category.\n";
        $isComplete = false;
    } else {
        // if there's a name specified, make sure it's not already in the database for categories
        
        // connect to the database
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up query to check if the name is already used
        $query = "SELECT CategoryName FROM Category WHERE CategoryName='$CategoryName';";
        
        // run the query
        $result = queryDB($query, $db);
        
        // check if we got any records returned
        if (nTuples($result) > 0) {
            // this means the name is already in use and we need to generate an error
            $isComplete = false;
            $errorMessage .= "The category $CategoryName is already in the database.\n";
        }
    }
    // Stop execution and show error if the form is not complete
    if($isComplete) {
    
        // put together SQL statement to insert new record
        $query = "INSERT INTO Category(CategoryName) VALUES ('$CategoryName');";
                
        // run the insert statement
        $result = queryDB($query, $db);
        
        // we have successfully entered the data
        echo ("Successfully entered new category: " . $CategoryName);
        
        // reset variables so we can reset the form since we've successfully added a record
        unset($isComplete, $errorMessage, $CategoryName);
}
}
?>
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Insert New Category</h1>        
    </div>
</div>


<!-- Showing errors, if any -->
<div class="row">
    <div class="col-xs-12">
<?php
    if (isset($isComplete) && !$isComplete) {
        echo '<div class="alert alert-danger" role="alert">';
        echo ($errorMessage);
        echo '</div>';
    }
?>            
    </div>
</div>



<!-- form to enter new categories -->
<div class="row">
    <div class="col-xs-12">
        
        <form action="InsertCategory.php" method="post">
<!-- Category Name -->
<div class="form-group">
    <label for="CategoryName">Category Name:</label>
    <input type="text" class="form-control" name="CategoryName" value="<?php if($CategoryName) { echo $CategoryName; } ?>"/>
</div>


<button type="submit" class="btn btn-default" name="submit">Save</button>

</form>
        
        
    </div>
</div>







<!-- show contents of Category table -->
<div class="row">
    <div class="col-xs-12">
        
<!-- set up html table to show contents -->
<table class="table table-hover">
    <!-- headers for table -->
    <thead>
        <th>Category ID</th>
        <th>Category Name</th>
    </thead>

<?php
    /*
     * List all the categories in the database
     *
     */
    include_once('config.php');
    include_once('dbutils.php');
    $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
    // set up a query to get information on the toppings from the database
    $query = 'SELECT * FROM Category;';
    // run the query
    $result = queryDB($query, $db);
    while($row = nextTuple($result)) {
        echo "\n <tr>";
        echo "<td>" . $row['CategoryID'] . "</td>";
        echo "<td>" . $row['CategoryName'] . "</td>";
        
        
        //echo "<td><a href='UpdateCategory.php?CategoryID=" . $row['CategoryID'] . "'>update</a></td>";
        // link to delete record
        echo "<td><a href='DeleteCategory.php?CategoryID=" . $row['CategoryID']  .  "'>delete</a></td>";
        echo "</tr> \n";
    }
?>        
    
</table>
        
    </div>
</div>



     </body>
    
</html>