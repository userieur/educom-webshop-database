<?php

    function varCheck ($value) {
        if (isset($value)) {
            $output = $value;
        } else {
            $output = "";
        }
        return $output;
    }

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
            // echo($sql);
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
        $emailExists = false;
        if ($userInfo !== "NO_DATA_FOUND") {
            $emailExists = true;
        }
        return $emailExists;
    }

    function storeUser($userInfo) {
        $username = $userInfo['username'];
        $password = $userInfo['password'];
        $email = $userInfo['email'];
        $sql = "INSERT INTO users (email, username, password) VALUES ('".$email."','".$username."','".$password."')";
        echo($sql);
        $conn = connectDatabase('r_webshop');
        writeData($conn, $sql);
        mysqli_close($conn);
    }

    function updatePassword($userInfo) {
        $email = $userInfo['email'];
        $password = $userInfo['password'];
        $sql = "UPDATE users SET password='".$password."' WHERE email='".$email."'";
        // echo($sql);
        updateData('r_webshop', $sql);
    }    

    function createInvoiceNumber() {
        // functie maken die een format invoiceNumber aanlevert
        // eerst de laatste pakken uit de invoice database
        // dan volgens eigen stijl aanpassen
        // bijv: YYYYMMDD####
        // de #### optellen en beginnen bij 0001 elke dag
        // dus select last
        // als YYYYMMDD = die van last, dan #### + 1 => YYYYMMDD####+1
        // else YYYYMMDD0001
        // return
    }

    function placeOrder() {
        $conn = connectDatabase('r_webshop');
        $sqlInfo = $_SESSION['invoicelines'];
        $userId = $_SESSION['userId'];
        $invoiceNum = 10;
        $sql = "INSERT INTO invoices (user_id, invoice_num) VALUES ('".$userId."', '".$invoiceNum."')";
        writeData($conn, $sql);
        $sql = "SELECT id from invoices ORDER BY ID DESC LIMIT 1";
        $output = readData($conn, $sql);
        $values = array_values($output);
        $invoiceId = $values[0];
        $invoiceId = $invoiceId['id'];
        var_dump($invoiceId);

        foreach ($sqlInfo as $product) {
            $columnString = "invoice_id, ";
            $valueString = "'".$invoiceId."', ";
            $count = 0;
            foreach ($product as $column => $value) {
                if ((count($sqlInfo)-$count) > 1) {
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
        $sql = "SELECT * from users WHERE email = '" . $email . "'";
        $conn = connectDatabase('r_webshop');
        $output = readData($conn, $sql);
        $values = array_values($output);
        $output = $values[0];
        mysqli_close($conn);
        return $output;
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
        } else {
        $output = "NO_DATA_FOUND";
        }
        return $output;
    }

    function writeData($conn, $sql) {
        if (mysqli_query($conn, $sql)) {
            // echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }

    function updateData($conn, $sql) {
        if (mysqli_query($conn, $sql)) {
            // echo "Record updated successfully";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }

?>

