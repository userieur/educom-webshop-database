<?php

    function createOptions ($string) {
        $itemArray = explode("|", $string);
        $infoValue = array();
        $index = 1;
        while($index < count($itemArray)) {
            $option = explode(":", $itemArray[$index]);
            $infoValue += [$option[0] => $option[1]];
            $index ++;
        }
        return ($infoValue);
    }

    function createChecks ($string) {
        $itemArray = explode("|", $string);
        $infoValue = array();
        $index = 1;
        while ($index < count($itemArray)) {
            $infoValue[] = $itemArray[$index];
            $index ++;
        }
        return ($infoValue);
    }
    
    function buildFormArray ($array) {
        $formArray = array('validForm' => false);
        foreach($array as $key => $info) {
            $formArray += [$key => array()];
            foreach ($info as $item) {
                $itemArray = explode("|", $item);
                $infoType = $itemArray[0];
                $infoValue = $itemArray[1];
                if ($infoType == 'options') {
                    $infoValue = createOptions($item);
                } elseif ($infoType == 'checks') {
                    $infoValue = createChecks($item);
                }
                $formArray[$key] += [$infoType => $infoValue];
            }
        }
        return $formArray;
    }


    // BUSINESS
    function getAllProducts() {
        $conn = connectDatabase('r_webshop');
        $sql = $sql = "SELECT * from products";
        $items = readData($conn, $sql);
        mysqli_close($conn);
        return $items;
    }

    function getProductsByIdArray($array) {
        $conn = connectDatabase('r_webshop');
        $idString = "";
        $count = 0;
        // var_dump($array);
        if ($array){
            foreach ($array as $id => $amount) {
                if ((count($array)-$count) > 1) {
                    $idString.="'".$id."', ";
                    $count += 1;
                } else {
                    $idString.="'".$id."'";
                }
            }
            $sql = "SELECT * from products WHERE name IN (".$idString.")";
            $output = readData($conn, $sql);
            mysqli_close($conn);
            return $output;
        }
    }

    function getProductById($id) {
        $conn = connectDatabase('r_webshop');
        $sql = "SELECT * from products WHERE id='".$id."'";
        $items = readData($conn, $sql);
        $values = array_values($items);
        $output = $values[0];
        mysqli_close($conn);
        return $output;
    }
       
    function doesEmailExist($email) {
        $userInfo = findUserByEmail($email);
        return !empty($userInfo);
    }

    function storeUser($userInfo) {
        $conn = connectDatabase('r_webshop');
        $username = cleanSQLInput($conn, $userInfo['username']);
        $password = cleanSQLInput($conn, $userInfo['password']);
        $email = cleanSQLInput($conn, $userInfo['email']);
        $sql = "INSERT INTO users (email, username, password) VALUES ('".$email."','".$username."','".$password."')";
        writeData($conn, $sql);
        mysqli_close($conn);
    }

    function updatePassword($userInfo) {
        $conn = connectDatabase('r_webshop');
        $email = cleanSQLInput($conn, $userInfo['email']);
        $password = cleanSQLInput($conn, $userInfo['password']);
        $sql = "UPDATE users SET password='".$password."' WHERE email='".$email."'";
        updateData($conn, $sql);
        mysqli_close($conn);
    }    

    function currentDate () {
        date_default_timezone_set('Europe/Amsterdam');
        $date = date('Ymd');
        return $date;
    }

    function createInvoiceNumber() {
        $date = currentDate();
        $conn = connectDatabase('r_webshop');
        $sql = "SELECT invoice_num from invoices ORDER BY ID DESC LIMIT 1";
        $output = readData($conn, $sql);
        
        $values = array_values($output);
        $string = $values[0]['invoice_num'];

        if (str_contains($string, $date)) {
            $count = substr($string, -4);
            $int = intval($count);
            $int += 1;
            $count = str_pad(strval($int), 4, '0', STR_PAD_LEFT);
            $invoiceNumber = $date.$count;
        } else {
            $invoiceNumber = $date."0001";
        }
        echo($invoiceNumber);
        return $invoiceNumber;
    }

    function placeOrder() {
        $conn = connectDatabase('r_webshop');
        $invoiceLines = $_SESSION['invoicelines'];
        $userId = $_SESSION['userId'];
        $invoiceNum = createInvoiceNumber();
        
        // Create invoice
        $sql = "INSERT INTO invoices (user_id, invoice_num) VALUES ('".$userId."', '".$invoiceNum."')";
        writeData($conn, $sql);
        
        // Retrieve invoice number of created invoice from database
        $sql = "SELECT id from invoices ORDER BY ID DESC LIMIT 1";
        $output = readData($conn, $sql);
        
        $values = array_values($output);
        $invoiceId = $values[0]['id'];

        // Create SQL-strings for each invoice-line and insert them in invoice_lines database
        foreach ($invoiceLines as $line) {
            $columnString = "invoice_id, ";
            $valueString = "'".$invoiceId."', ";
            $count = 0;
            foreach ($line as $column => $value) {
                if ((count($line)-$count) > 1) {
                    $columnString.=$column.", ";
                    $valueString.="'".$value."', ";
                    $count += 1;
                } else {
                    $columnString.=$column;
                    $valueString.="'".$value."'";
                }
            }
            $sql = "INSERT INTO invoice_lines (".$columnString.") VALUES (".$valueString.")";
            writeData($conn, $sql);
        }

        mysqli_close($conn);
    }

    // CART STUFF
    function addToCartForm($page, $name, $id) {
        echo'
        <form method="POST" action="index.php">
        <input type="hidden" id="page" name="page" value="' . $page . '">
        <input type="hidden" id="action" name="action" value="addToCart">
        <input type="hidden" id="name" name="name" value="'.$name.'">
        <input type="hidden" id="id" name="id" value="'.$id.'">
        <input type="submit" value="Add">
        </form>';
    }

    function removeFromCartForm($page, $name, $id) {
        echo'
        <form method="POST" action="index.php">
        <input type="hidden" id="page" name="page" value="' . $page . '">
        <input type="hidden" id="action" name="action" value="removeFromCart">
        <input type="hidden" id="name" name="name" value="'.$name.'">
        <input type="hidden" id="id" name="id" value="'.$id.'">
        <input type="submit" value="Remove">
        </form>';
    }

    function orderForm($page, $name, $id) {
        echo'
        <form method="POST" action="index.php">
        <input type="hidden" id="page" name="page" value="' . $page . '">
        <input type="hidden" id="action" name="action" value="order">
        <input type="submit" value="Order">
        </form>';
    }

    function cleanSQLInput($conn, $value) {
        return mysqli_real_escape_string($conn, $value);
    }

    // SESSION MANAGER
    function doLoginUser($userInfo) {
        $_SESSION['user'] = $userInfo['username'];
        $_SESSION['userId'] = $userInfo['id'];
        $_SESSION['email'] = $userInfo['email'];
        $_SESSION['cart'] = array();
    }

    function isUserLoggedIn() {
        return isset($_SESSION['user']);
    }

    function getLoggedInUser() {
        return $_SESSION('user');
    }

    function doLogoutUser() {
        session_unset();
    }

    // DATA

    function FindUserByEmail($email) {      
        $conn = connectDatabase('r_webshop');
        $sql = "SELECT * from users WHERE email = '" . $email . "'";
        $output = readData($conn, $sql);
        // var_dump($output);
        $values = array_values($output);
        $id = $values[0]['id'];
        mysqli_close($conn);
        // VRAAG: Het voelt of deze functie slimmer kan.... Hoe?
        return empty($output) ? NULL : $output[$id]; 
    }

    function connectDatabase($dbname) {
        $servername = "127.0.0.1";
        $username = "r_webshop_usr";
        $password = "Z6zFwtYvjGGq5Y";
        $dbname = $dbname;

        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
          }
        return $conn;
    }

    function readData($conn, $sql) {
        $output = array();
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                if (isset($row['id'])) {
                    $output[$row['id']] = $row;
                } else {
                $output[] = $row;
                }
            }
        }
        return $output;
    }

    function writeData($conn, $sql) {
        if (mysqli_query($conn, $sql)) {
            // fffffff
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }

    function updateData($conn, $sql) {
        if (mysqli_query($conn, $sql)) {
            // fffffffffffff
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }

?>

