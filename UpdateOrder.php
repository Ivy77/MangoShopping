<?php
//has bug ... fix

/*
 * This php file enables users to edit a particular order
 * It obtains the id for the order to update from an id variable passed using the GET method (in the url)
 *
 */
    include_once('config.php');
    include_once('dbutils.php');
    
    /*
     * If the user submitted the form with updates, we process the form with this block of code
     *
     */
    if (isset($_POST['submit'])) {
        // process the update if the form was submitted
        
        // get data from form
        $OrderID = $_POST['OrderID'];
        if (!isset($OrderID)) {
            // if for some reason the OrderID didn't post, kick them back to Order1.php
            header('Location: Order1.php');
            exit;
        }

        // get data from form
        $ActDeliveryDate = $_POST['ActDeliveryDate'];
        $Status = $_POST['crust'];
        
        // variable to keep track if the form is complete (set to false if there are any issues with data)
        $isComplete = true;
        
        // error message we'll give user in case there are issues with data
        $errorMessage = "";
        
        
        // check each of the required variables in the table        
        if (!isset($ActDeliveryDate)) {
            // add error handling here .. exception for date form? check out documention on Datetime()
            $errorMessage .= "Please enter a shape for the pizza.\n";
            $isComplete = false;
        }
        
       
        if (!isset($Status)) {
            $errorMessage .= "Please choose a status for the order.\n";
            $isComplete = false;
        } 

        
        if($isComplete) {
            // if there's no error, then we need to update
            
            //
            // first update order record
            //
            // put together SQL statement to update pizza
            $query = "UPDATE Orders SET ActDeliveryDate = '$ActDeliveryDate', Status = '$Status' WHERE OrderID=$OrderID;";
            
            // connect to the database
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
            
            // run the update
            $result = queryDB($query, $db);            
                    
          
            
            // now that we are done, send user back to pizza.php and exit 
            header("Location: Order1.php?successmessage=Successfully updated order $OrderID");
            exit;
        }        
    } else {
        //
        // if the form was not submitted (first time in)
        //
    
        /*
         * Check if a GET variable was passed with the id for the pizza
         *
         */
        if(!isset($_GET['OrderID'])) {
            // if the id was not passed through the url
            
            // send them out to Order1.php and stop executing code in this page
            header('Location: Order1.php');
            exit;
        }
        
        /*
         * Now we'll check to make sure the OrderID passed through the GET variable matches the OrderID in the database
         */
        
        // connect to the database
        $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up a query
        $OrderID = $_GET['OrderID'];
        $query = "SELECT * FROM Orders WHERE OrderID=$OrderID;";
        
        // run the query
        $result = queryDB($query, $db);
        
        // if the id is not in the order table, then we need to send the user back to Order1.php
        if (nTuples($result) == 0) {
            // send them out to pizza.php and stop executing code in this page
            header('Location: Order1.php');
            exit;
        }
        
        /*
         * Now we know we got a valid pizza id through the GET variable
         */
        
        // get data on pizza to fill out form with existing values
        $row = nextTuple($result);
        
        $ActDeliveryDate = $row['ActDeliveryDate'];
        $shapeid = $row['Status'];
        
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
        
        <title>Update order <?php echo $OrderID; ?></title>
    </head>
    
    <body>
       
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1>Update order <?php echo $OrderID ?></h1>        
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



<!-- form to update order -->
<div class="row">
    <div class="col-xs-12">
        
        <form action="UpdateOrder.php.php" method="post">
<!-- Actual Delivery Date -->
<div class="form-group">
    <label for="ActDeliveryDate">Actual Delivery Date:</label>
    <input type="text" class="form-control" name="ActDeliveryDate" value="<?php if($ActDeliveryDate) { echo $ActDeliveryDate; } ?>"/>
</div>

<!-- Status -->
<div class="form-group">
    <label for="Status">Order Status: </label>
    <input type="text" class="form-control" name="Status" value="<?php if($Status) { echo $Status; } ?>"/>
</div>



<!-- hidden id (not visible to user, but need to be part of form submission so we know which pizza we are updating -->
<input type="hidden name="OrderID" value="<?php echo $OrderID; ?>"/>

<button type="submit" class="btn btn-default" name="submit">Save</button>

</form>
        
        
    </div>
</div>

       
       
        
    </body>
</html>