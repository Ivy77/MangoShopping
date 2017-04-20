<!-- Orders.php -->
<!-- Displays all orders and the four subcategories of the orders (sorted by status) -->



<?php
    include_once('config.php');
    include_once('dbutils.php');
    
?>

<html>
     <head>
         <title>View Orders</title>
<!--Menu bar-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-left">
            <li><a href="InputAdministrator.php">Insert New Administrator</a></li>
            <li><a href="InsertProduct1.php">Insert New Product</a></li>
            <li><a href="InsertCategory.php">Insert New Category</a></li>
            <li class="active"><a href="Orders.php">View Orders</a>
            </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="Logout.php">Log out</a></li>
        </ul>
    </div>
</nav>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 
<!--Order Categories subtabs-->
        
    
    </head>

    <body>
        
        
   
<div>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class = "active"><a href="#Received" aria-controls="Home" role="tab" data-toggle="tab">Received</a></li>
        <li role="presentation"><a href="#Shipped" aria-controls="Shipped" role="tab" data-toggle="tab">Shipped</a></li>
        <li role="presentation"><a href="#Delivered" aria-controls="Delivered" role="tab" data-toggle="tab">Delivered</a></li>
        <li role="presentation"><a href="#Returned" aria-controls="Returned" role="tab" data-toggle="tab">Returned</a></li>
        <li role="presentation"><a href="#All" aria-controls="All" role="tab" data-toggle="tab">All Orders</a></li>
    </ul>
    
    <!-- Tab Panes -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="Received">
            <p>Receved orders</p>
        </div>
        <div role="tabpanel" class="tab-pane" id="Shipped">
            <p>Shipped orders</p>
        </div>
        <div class="tab-pane" id="Delivered">
            <p>Delivered orders</p>
        </div>
        <div class="tab-pane" id="Returned">
            <p>Returned orders</p>
        </div>
        <div class="tab-pane" id="All">
            <p>All orders</p>
        </div>
        
    </div>
</div>            
 
<!-- link: getbootstrap.com/javascript/     section: tab -->
 
 
 
    <!--Show contents of Orders table-->
<div class='row'>
    <div class='col-xs-12'>
        <!--set up HTML table to show contents-->
        <table class="table table-hover">
            <!--headers for table-->
            <thead>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Payment Value</th>
                <th>Shipping Address</th>
                <th>Preferred Delivery Date</th>
                <th>Actual Delivery Date</th>
                <th>Order Status</th>
                
            </thead>
            

<?php
    /*
     *List all the orders in the database
     *
     */
    // Connect to the database
    
    include_once('config.php');
    include_once('dbutils.php');
    // connect to the db
    $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
    
    // set up a query to get info on the products from the database
    //$query = "SELECT * FROM Products;";
    $query = 'SELECT * FROM Orders;';
    //run the query
    //queryDB is a function from dbutils file
    // echo '1';
    $result = queryDB($query,$db);
    // var_dump($result);


    while($row = nextTuple($result)){
        echo "\n <tr>";
        echo "<td>".$row["OrderID"]."</td>";
        echo "<td>".$row["OrderDate"]."</td>";
        echo "<td>".$row["Payment"]."</td>";
        echo "<td>".$row["Address"]."</td>";
        echo "<td>".$row["PrefDeliveryDate"]."</td>";
        echo "<td>".$row["ActDeliveryDate"]."</td>";
        echo "<td>".$row["Status"]."</td>";
        echo "</tr> \n";
        
        
        
        // link to update record (Orders)
        echo "<td><a href='UpdateOrder.php?OrderID = " . $row['OrderID'] . "'>update</a></td>";
        
        // link to delete record (Actually should remove this because an order shouldn't be deleted....)
        echo "<td><a href='DeleteOrder.php?OrderID=" . $row['OrderID'] . "'>delete</a></td>";
        
        echo "</tr> \n";
    }

?>              
        </table>
       
    </div>
</div>    
        
        
        
        
        
        
        
        
        
        
        
        
    </body>
    
    </html>