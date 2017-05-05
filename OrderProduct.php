<?php

/*
 * This php file enables users to edit a particular order
 * It obtains the id for the order to update from an id variable passed using the GET method (in the url)
 *
 */
    include_once('config.php');
    include_once('dbutils.php');   
    
        /*
         * Check if a GET variable was passed with the id for the order
         *
         */
        if(!isset($_GET['OrderID'])) {
            // if the id was not passed through the url
            
            // send them out to Order1.php and stop executing code in this page
            /////////////This was run?????????????????????????!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            header('Location: AllOrder.php');
            exit;
        }
        
        /*
         * Now we'll check to make sure the OrderID passed through the GET variable matches the OrderID in the database
         */
        // connect to the database

    
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
        
        <title>View Order <?php echo $OrderID; ?></title>
    </head>
    
    <body>
       
<!-- Title -->
<div class="row">
    <div class="col-xs-12">
        <h1> Order Detail <?php echo $OrderID ?></h1>        
    </div>
</div>



<!--Show contents of product table-->
<div class='row'>
    <div class='col-xs-12'>
        <!--set up HTML table to show contents-->
        <table class="table table-hover">
            <!--headers for table-->
            <thead>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Product Quantity</th>
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
            $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);
        
        // set up a query
        $OrderID = $_GET['OrderID'];
        $query = "select Products.ProductID, ProductName, Quantity, Picture from Products,OrderProduct where Products.ProductID=OrderProduct.ProductID and OrderID = $OrderID;";
        
        // run the query
        $result = queryDB($query, $db);
        
        // if the id is not in the order table, then we need to send the user back to OrderFilter.php
        if (nTuples($result) == 0) {
      
            header('Location: AllOrder.php');
            exit;
        }

    

    while($row = nextTuple($result)){
        echo "\n <tr>";
        echo "<td>".$row["ProductID"]."</td>";
        echo "<td>".$row["ProductName"]."</td>";
        echo "<td>".$row["Quantity"]."</td>";
       
        
        // Picture
        echo "<td>";
        if($row['Picture']){
            $imageLocation = $row['Picture'];
            $altText = 'Products'.$row['ProductName'];
            echo "<img src='$imageLocation' width='150' alt='altText'>";
        }
        echo "</td>";

        
        echo "</tr> \n";
    }

?>              
       
       
        
    </body>
</html>