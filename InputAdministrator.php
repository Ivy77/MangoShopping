<!-- This file provides input capabalities into a table of administators -->
<!-- It also lists the contents of the table -->
<!-- It uses bootstrap for formatting -->


<!--todo : catch errors like: duplicate administrator, value error... start session... staff can't see this page and error message-->
<!--Menu bar-->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <ul class="nav navbar-nav navbar-left">
            <li class="active">
                <a href="InputAdministrator.php">Insert New Administrator</a>
            </li>
            <li>
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
    
?>

<html>
    <head>

<title>Enter Administrators</title>

<!-- This is the code from bootstrap -->        
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        
    </head>
    
    <body>

<!-- Visible title -->
        <div class="row">
            <div class="col-xs-12">
                <h1>Enter Administrators</h1>
            </div>
        </div>
        
<!-- Processing form input -->        
        <div class="row">
            <div class="col-xs-12">
<?php
//
// Code to handle input from form
//

if (isset($_POST['submit'])) {
    // only run if the form was submitted
    
    // get data from form
    $Email = $_POST['Email'];
	$password = $_POST['password'];
	$password2 = $_POST['password2'];
    $AdminName = $_POST['AdminName'];
    $Address = $_POST['Address'];
    $AdminLevel = $_POST['AdminLevel'];
   // connect to the database
    $db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);    
    
    // check for required fields
    $isComplete = true;
    $errorMessage = "";
   
    if (!$AdminName) {
        $errorMessage .= " Please enter your name";
        $isComplete = false;
    }
    
    if (!$Email) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $Email = makeStringSafe($db, $Email);
    }

    if (!$password) {
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }
	
	if (!$password2) {
        $errorMessage .= " Please enter a password again.";
        $isComplete = false;
    }
	
	if ($password != $password2) {
		$errorMessage .= " Your two passwords are not the same.";
		$isComplete = false;
	}
    if (!$Address) {
        $errorMessage .= " Please enter your address.";
        $isComplete = false;
    }
    if (!$AdminLevel) {
        $errorMessage .= " Please select the administration level for the user.";
        $isComplete = false;
    }
	    
	
    if ($isComplete) {
    
		// check if there's a user with the same email
		$query = "SELECT * FROM users WHERE Email='" . $Email . "';";
		$result = queryDB($query, $db);
		if (nTuples($result) == 0) {
			// if we're here it means there's already a user with the same email
			
			// generate the hashed version of the password
			$Hashedpass = crypt($password, getSalt());
			
			// put together sql code to insert tuple or record
			$insert = "INSERT INTO Administrator(AdminName, Email, Hashedpass, Address, AdminLevel) VALUES ('$AdminName','" . $Email . "', '" . $Hashedpass . "','$Address', '$AdminLevel');";
		
			// run the insert statement
			$result = queryDB($insert, $db);
			
			// we have successfully inserted the record
			echo ("Successfully entered " . $Email . " into the database.");
		} else {
			$isComplete = false;
			$errorMessage = "Sorry. We already have a user account under email " . $Email;
		}
	}
}
?>
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

<!-- form for inputting data -->
        <div class="row">
            <div class="col-xs-12">
                
<form action="InputAdministrator.php" method="post">

<!-- AdminName -->
    <div class="form-group">
        <label for="AdminName">Name</label>
        <input type="AdminName" class="form-control" name="AdminName"/>
    </div>
<!-- Email -->
    <div class="form-group">
        <label for="Email">Email</label>
        <input type="Email" class="form-control" name="Email"/>
    </div>

<!-- password1 -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" name="password"/>
    </div>

<!-- password2 -->
    <div class="form-group">
        <label for="password2">Enter password again</label>
        <input type="password" class="form-control" name="password2"/>
    </div>
<!-- Address -->
    <div class="form-group">
        <label for="Address">Address</label>
        <input type="Address" class="form-control" name="Address"/>
    </div>
 <!-- AdminLevel -->
 	<div class="form-group">
 	
 		<label for="AdminLevel">Administrator Level: 	</label>
 		<label class="radio-inline">
 			<input type="radio" name="AdminLevel" value="1" <?php if($AdminLevel || !isset($AdminLevel)){echo 'checked';}?>> Manager
 		</label>
 		<label class="radio-inline">
 			<input type="radio" name="AdminLevel" value="0" <?php if(!$AdminLevel && isset($AdminLevel)){echo 'checked';}?>> Staff 
 		</label>
 		
 		
 		
 	</div>


    <button type="submit" class="btn btn-default" name="submit">Add</button>
</form>
                
            </div>
        </div>
      

        
    </body>
    
</html>