<?php
// Contains some common PHP db functions. Here, we always check the  
// return object/value for errors.  Uses the mysqli functional interface
// as opposed to the mysqli object interface.

// Connect to DB: config.php contains DB configuration.
function connectDB($dbhost,$dbuser,$dbpasswd,$dbname) {
  $db = mysqli_connect($dbhost,$dbuser,$dbpasswd,$dbname);
  if (mysqli_connect_errno() != 0)
    punt("Can't connect to MySQL server $dbhost db $dbname as user $dbuser");
  return($db);
}

// Submit a query and return a result object. This is just syntactic 
// sugar and error trapping.
// like the mysqli_query function,returns FALSE on failure. For successful SELET, SHOW, DESCRIBE
// or EXPLAIN queries mysqliquery() will return a mysqli_result object. For other successful
// queries mysqli_query() will return TRUE
function queryDB($query, $db) {
  $result = mysqli_query($db, $query);
  if (!$result)
    punt ('Error in queryDB()', $query, $db);
  return ($result);
}

// How many tuples in the result? Syntactic sugar.
function nTuples($result) {
  return(mysqli_num_rows($result));
}

// Get next record as an associative array. Syntactic sugar.
// array mysql_fetch assoc(resource $result):
// returns an associative array that correponds to the fetched row and moves the internal data pointer ahead.
// the result parameter comes from a call to mysqli_query
function nextTuple($result) {
  return (mysqli_fetch_assoc($result));
}

// Used for debugging. If invoked with a SQL query string
// as the optional second argument, will also retrieve and
// display MySQL error information. Otherwise, if invoked
// only with one argument, will print that argument.
function punt($message, $query = '', $db = '') {
  $lastPart = '';
  // Check to see if error resulted from a bad query
  if ($query != '')
    $lastPart = "<br><i>$query</i>\n" . '<br>[' . mysqli_errno($db) . '] ' . mysqli_error($db) . "\n";
  die("\n<br><br><b>Error: $message</b>\n" . $lastPart);
}

// Used for converting a string so it doesn't have characters
// that will confuse MySQL and cause a problem with your SQL statement
function makeStringSafe($db, $mystring) {
  $newstring = mysqli_real_escape_string($db, $mystring);
  return $newstring;
}

    function getSalt() {
        $salt = sprintf('$2a$%02d$', 12);
    
        $bytes = getRandomBytes(16);
    
        $salt .= encodeBytes($bytes);

        return $salt;
    }
    
    function getRandomBytes($count) {
        $bytes = '';
    
        if(function_exists('openssl_random_pseudo_bytes') &&
            (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')) { // OpenSSL is slow on Windows
          $bytes = openssl_random_pseudo_bytes($count);
        }
    
        if($bytes === '' && is_readable('/dev/urandom') &&
           ($hRand = @fopen('/dev/urandom', 'rb')) !== FALSE) {
          $bytes = fread($hRand, $count);
          fclose($hRand);
        }
    
        if(strlen($bytes) < $count) {
          $bytes = '';
    
          if(randomState === null) {
            $randomState = microtime();
            if(function_exists('getmypid')) {
              $randomState .= getmypid();
            }
          }
    
          for($i = 0; $i < $count; $i += 16) {
            $randomState = md5(microtime() . $randomState);
    
            if (PHP_VERSION >= '5') {
              $bytes .= md5($randomState, true);
            } else {
              $bytes .= pack('H*', md5($randomState));
            }
          }
    
          $bytes = substr($bytes, 0, $count);
        }
    
        return $bytes;
    }
    
    function encodeBytes($input) {
        // The following is code from the PHP Password Hashing Framework
        $itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    
        $output = '';
        $i = 0;
        do {
          $c1 = ord($input[$i++]);
          $output .= $itoa64[$c1 >> 2];
          $c1 = ($c1 & 0x03) << 4;
          if ($i >= 16) {
            $output .= $itoa64[$c1];
            break;
          }
    
          $c2 = ord($input[$i++]);
          $c1 |= $c2 >> 4;
          $output .= $itoa64[$c1];
          $c1 = ($c2 & 0x0f) << 2;
    
          $c2 = ord($input[$i++]);
          $c1 |= $c2 >> 6;
          $output .= $itoa64[$c1];
          $output .= $itoa64[$c2 & 0x3f];
        } while (1);
    
        return $output;
    }

    function printBoolean($boolean){
      if($boolean){
        return 'Yes';
      }else{
        return 'No';}
    }
  
//
//Checkbox generating function returns html/bootstrap checkboxes based on the contents of a
//specific table within that table
//Specifying values to show users and values to return
//
//$db is a handle to the database
//$table is the name of the table
//$uniVariable is the name of the variable in $table with values the user will see
//indexVariable is the name of the variable in $table with the values returned by the form
//$checkBoxes in an array with values corresponding to $indexVaribale. If a value is present,
// then that checkbox is checked by default.     2/16 5:19
 
      function generateCheckboxes($db, $table, $uiVariable, $indexVariable, $checkedBoxes = array()){
        
        // set up a query to get the two variables from the table
        $query = "SELECT $uiVariable,$indexVariable FROM $table ORDER BY $uiVariable;";
        
        //run the query
        //queryDB is a function from dbutils file
        $result = queryDB($query,$db);
        
        //put together html for the checkbox
        $checkboxHTML = "";
        
  
        while($row = nextTuple($result)){
            //for each record in the table
            $checkboxHTML.= "<div class='checkbox'>";
            $checkboxHTML.="<label>";
            
            //get the values for the ui and index variables
            $ui = $row["$uiVariable"];
            $index = $row["$indexVariable"];
            
            // see if this checkbox should be checked by default
            $checked = "";
            
            // if there are checkBoxes to be checked by default
            // see if index in one of the values in checkedBoxes
            if (in_array($index,$checkedBoxes)){
              //this box should be checked
              $checked = "checked";
            }
            
            //this is where my checkbox goes
            $checkboxHTML.="<input type = 'checkbox' value = '$index' name = '$table-$indexVariable'>$ui";
            
            $checkboxHTML.="</label>";
            $checkboxHTML.="</div>\n";

             
        }
        return $checkboxHTML;
    }
  
  //
  // Dropdown list generating function returns html/bootstrap dropdown list based on the contents of a specific table and specific variables within that table
  // specifying values to show users, and values to return
  //
  // $db is a handle to the database
  // $table is the name of the table
  // $uiVariable is the name of the variable in $table with values the user will see
  // $indexVariable ist he name of the variable in $table with the values returned by the form
  // $defaultValue is the default value for the dropdown list
  //  
    function generateDropdown($db, $table, $uiVariable, $indexVariable, $defaultValue="") {
   
    // set up a query to get the two variables from the table 
    $query = "SELECT $uiVariable, $indexVariable FROM $table ORDER BY $uiVariable;";
    
    // run the query
    $result = queryDB($query, $db);
    
    // put together html for the checkbox
    $dropdownHTML = "<select class='form-control' name='$table-$indexVariable'>\n";
    
    while($row = nextTuple($result)) {
      // get the values for the ui and index variables
      $ui = $row["$uiVariable"];
      $index = $row["$indexVariable"];
      
      // check if the current value should be the default value
      if ($index == $defaultValue) {
        $selected = "selected='selected'";
      } else {
        $selected = "";
      }

      // for each record in the table
      $dropdownHTML .= "<option value='$index' $selected>$ui</option>\n";
    }
    
    // close select tag
    $dropdownHTML .="</select>";
    
    // return the html for the dropdowns
    return $dropdownHTML;
    
  }      
  
  
  
    //connect to the database
      //  include_once('config.php');
      //  $db = connectDB($DBHost, $DBUser,$DBPasswd,$DBName);
      
//          <label>
//            <input type = "checkbox" value = "">
//          </label>
//        </div>
   
?>
