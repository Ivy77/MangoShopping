<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php

session_start();

include_once('config.php');
include_once('dbutils.php');

$db = connectDB($DBHost, $DBUser, $DBPasswd, $DBName);

// init cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
};

function getCartProductCount() {
    $cart = $_SESSION['cart'];
    $count = 0;
    foreach ($cart as $item) {
        $count += $item['num'];
    }
    return $count;
}

function isLogin() {
    return isset($_SESSION['user']);
}

function getCartProductTotal() {
    $cart = $_SESSION['cart'];
    $cart[0] = 4;
    $count = 0;
    foreach ($cart as $item) {
        $count += $item['product']['UnitPrice'] * $item['num'];
    }
    return $count;
}

function echo_active($name, $expected) {
    echo $name === $expected ? 'active' : '';
}

function render($name, $data=[]) {
    extract($data);
    include 'views/partials/header.php';
    include 'views/' . $name . '.php';
    include 'views/partials/footer.php';
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
        case 'home':
            home();
            break;
        case 'register':
            render('register');
            break;
        case 'login':
            render('login');
            break;
        case 'cart':
            global $imagesDir;
            render('cart', [ 'imagesDir' => $imagesDir ]);
            break;
        case 'order':
            order();
            break;
        case 'addToCart':
            addToCart();
            break;
        case 'updateCart':
            updateCart();
            break;
        case 'doLogin':
            doLogin();
            break;
        case 'logout':
            logout();
            break;
        case 'doRegister':
            doRegister();
            break;
        case 'checkout':
            checkout();
            break;
        case 'doCheckout':
            doCheckout();
            break;
        default:
            render('404');
    }
} else {
    render('home');
}

function home() {
    global $db;
    global $imagesDir;
    $category_query = "SELECT * FROM Category ORDER BY CategoryID ASC";
    $categories = queryDB($category_query, $db);

    if (isset($_GET['categoryID'])) {
        $categoryID = makeStringSafe($db, $_GET['categoryID']);
        $products_query = 'select * from Products where CategoryID = ' . $categoryID;
    } else {
        $products_query = 'select * from Products';
    }
    $products = queryDB($products_query, $db);

    render('home', [
        'categories' => $categories,
        'products' => $products,
        'imagesDir' => $imagesDir,
    ]);
}

function doLogin() {
    // get data from form
    $email = $_POST['email'];
    $password = $_POST['password'];

    global $db;

    // check for required fields
    $isComplete = true;
    $errorMessage = "";

    if (!$email) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $email = makeStringSafe($db, $email);
    }

    if (!$password) {
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }

    if (!$isComplete) {
        header('Location: index.php?action=login&error=' . $errorMessage);
        return;
    }

    // get the hashed password from the user with the email that got entered
    $query = "SELECT * FROM Customers WHERE email='" . $email . "';";
    $result = queryDB($query, $db);
    if (nTuples($result) > 0) {
        // there is an account that corresponds to the email that the user entered
        // get the hashed password for that account
        $row = nextTuple($result);
        $hashedpass = $row['hashedpass'];

        // compare entered password to the password on the database
        if ($hashedpass = crypt($password, $hashedpass)) {
            // password was entered correctly

            $_SESSION['user'] = [
                'CustomerID' => $row['CustomerID'],
                'CustomerName' => $row['CustomerName'],
                'Email' => $row['Email'],
                'Address' => $row['Address'],
                'PhoneNumber' => $row['PhoneNumber'],
            ];
            header('Location: index.php?action=home&info=' . 'login success.');
        } else {
            // wrong password
            header('Location: index.php?action=login&error=Wrong password.');
        }
    } else {
        // email entered is not in the users table
        header('Location: index.php?action=login&error=This email is not in our system.');
    }
}

function doRegister() {
    global $db;
    // get data from form
    $customername = $_POST['customername'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];

    // check for required fields
    $isComplete = true;
    $errorMessage = "";

    if (!$customername) {
        $errorMessage .= " Please enter your name.";
        $isComplete = false;
    } else {
        $Customername = makeStringSafe($db, $customername);
    }

    if (!$email) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $email = makeStringSafe($db, $email);
    }

    if (!$password) {
        $errorMessage .= " Please enter a password.";
        $isComplete = false;
    }

    if (!$password2) {
        $errorMessage .= " Please enter your password again.";
        $isComplete = false;
    }

    if ($password != $password2) {
        $errorMessage .= " Your two passwords are not the same.";
        $isComplete = false;
    }

    if (!$phonenumber) {
        $errorMessage .= " Please enter your phone number.";
        $isComplete = false;
    } else {
        $phonenumber = makeStringSafe($db, $phonenumber);
    }
    if (!$address) {
        $errorMessage .= " Please enter your address.";
        $isComplete = false;
    } else {
        $address = makeStringSafe($db, $address);
    }


    if ($isComplete) {

        // check if there's a user with the same email
        $query = "SELECT * FROM Customers WHERE email='" . $email . "';";
        $result = queryDB($query, $db);
        if (nTuples($result) == 0) {
            // if we're here it means there's already a user with the same email

            // generate the hashed version of the password
            $hashedpass = crypt($password, getSalt());

            // put together sql code to insert tuple or record
            $insert = "INSERT INTO Customers(CustomerName, email, hashedpass, Address, PhoneNumber) VALUES ('$Customername', '$email', '$hashedpass', '$address', '$phonenumber');";

            // run the insert statement
            $result = queryDB($insert, $db);

            // we have successfully inserted the record
            header('Location: index.php?action=register&success=Successfully entered ' . $email . ' into the database.');
        } else {
            $isComplete = false;
            $errorMessage = "Sorry. We already have a user account under email " . $email;
        }
    }

    if (!$isComplete) {
        header('Location: index.php?action=register&error=' . $errorMessage);
    }
}

function addToCart() {
    $productID = $_POST['ProductID'];
    $cart = &$_SESSION['cart'];

    if (!isset($cart[$productID])) {
        $cart[$productID] = [
            'product' => [
                'ProductName' => $_POST['ProductName'],
                'ProductDescription' => $_POST['ProductDescription'],
                'ProductID' => $_POST['ProductID'],
                'UnitPrice' => $_POST['UnitPrice'],
                'UnitCost' => $_POST['UnitCost'],
                'Picture' => $_POST['Picture'],
            ],
            'num' => 1
        ];
    } else {
        $cart[$productID]['num'] += 1;
    }

    header('Location: index.php?action=home');
}

function updateCart() {
    $productID = $_POST['ProductID'];
    $cart = &$_SESSION['cart'];
    $num = $_POST['num'];

    if ($num == 0) {
        unset($cart[$productID]);
        header('Location: index.php?action=cart&info=Delete success!');
        return;
    }

    if (!isset($cart[$productID])) {
        $cart[$productID] = [
            'product' => [
                'ProductName' => $_POST['ProductName'],
                'ProductDescription' => $_POST['ProductDescription'],
                'ProductID' => $_POST['ProductID'],
                'UnitPrice' => $_POST['UnitPrice'],
                'UnitCost' => $_POST['UnitCost'],
                'Picture' => $_POST['Picture'],
            ],
            'num' => $num
        ];
    } else {
        $cart[$productID]['num'] = $num;
    }

    header('Location: index.php?action=cart&info=Update success!');
}

function logout() {
    unset($_SESSION['user']);
    header('Location: index.php?action=home&info=Log out success.');
}

function checkout() {
    global $imagesDir;
    render('checkout', [ 'imagesDir' => $imagesDir ]);
}


function doCheckout() {
    global $db;
    // get data from form
    $customername = $_POST['customername'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $payment = $_POST['payment'];
    $prefDeliveryDate = $_POST['prefdeliverydate'];

    // check for required fields
    $isComplete = true;
    $errorMessage = "";

    if (!$customername) {
        $errorMessage .= " Please enter your name.";
        $isComplete = false;
    } else {
        $Customername = makeStringSafe($db, $customername);
    }

    if (!$email) {
        $errorMessage .= " Please enter an email.";
        $isComplete = false;
    } else {
        $email = makeStringSafe($db, $email);
    }

    if (!$phonenumber) {
        $errorMessage .= " Please enter your phone number.";
        $isComplete = false;
    } else {
        $phonenumber = makeStringSafe($db, $phonenumber);
    }
    if (!$address) {
        $errorMessage .= " Please enter your address.";
        $isComplete = false;
    } else {
        $address = makeStringSafe($db, $address);
    }

    if (!$isComplete) {
        header('Location: index.php?action=checkout&error=' . $errorMessage);
        return;
    }

    if (isset($_SESSION['user'])) {
        $CustomerID = $_SESSION['user']['CustomerID'];
        $insert = "INSERT INTO Orders(CustomerID, Payment, Address, PrefDeliveryDate, Status) VALUES ('$CustomerID', '$payment', '$address', '$prefDeliveryDate', 'received');";
        queryDB($insert, $db);

        $ids = queryDB('SELECT LAST_INSERT_ID() id', $db);
        foreach ($ids as $item) {
            $id = $item['id'];
        }

        $cart = $_SESSION['cart'];
        foreach ($cart as $item) {
            $product = $item['product'];
            $productID = $product['ProductID'];
            for ($i = 0; $i < $item['num']; $i++) {
                queryDB("insert into OrderProduct(ProductID, OrderID) values('$productID', '$id')", $db);
            }
        }
    }

    unset($_SESSION['cart']);
    header('Location: index.php?action=home&info=Checkout success');
}

function order() {
    global $db;
    $orders_query = "SELECT * FROM Orders join OrderProduct on Orders.OrderID = OrderProduct.OrderID join Products on OrderProduct.ProductID = Products.ProductID ";
    $raw_orders = queryDB($orders_query, $db);

    $orders = [];
    foreach ($raw_orders as $order) {
        if (!isset($orders[$order['OrderID']])) {
            $orders[$order['OrderID']] = [
                'content' => $order,
                'items' => [$order]
            ];
        } else {
            $orders[$order['OrderID']]['items'][] =  $order;
        }
    }

    render('order', [ 'orders' => $orders ]);
}
