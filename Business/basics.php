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
        // echo($string);
        $itemArray = explode("|", $string);
        $infoValue = array();
        $index = 1;
        while($index < count($itemArray)) {
            $option = explode(":", $itemArray[$index]);
            $infoValue += [$option[0] => $option[1]];
            $index ++;
        }
        // var_dump($infoValue);
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
        // var_dump($array);
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
        writeData('r_webshop', $sql);
    }

    function updatePassword($userInfo) {
        $email = $userInfo['email'];
        $password = $userInfo['password'];
        $sql = "UPDATE users SET password='".$password."' WHERE email='".$email."'";
        // echo($sql);
        updateData('r_webshop', $sql);
    }    

    function authenticateUser() {
        $userInfo = findUserByEmail($email);
        // return NULL / Array
    }

    // SESSION MANAGER
    function doLoginUser($userInfo) {
        // session_start();
        $_SESSION['user'] = $userInfo['username'];
        $_SESSION['email'] = $userInfo['email'];
        $_SESSION['password'] = $userInfo['password'];
    }

    function isUserLoggedIn() {
        $loggedIn = false;
        if (isset($_SESSION['user'])) {
            $loggedIn = true;
        }
        return $loggedIn;
    }

    function getLoggedInUser() {
        return $_SESSION('user');
    }

    function doLogoutUser() {
        session_unset();
        // session_destroy();
    }

    // DATA
    // Data Access Object
    // File repository

    function FindUserByEmail($email) {      
        $sql = "SELECT * from users WHERE email = '" . $email . "'";
        $output = readData('r_webshop', $sql);
        $output = $output[0];
        return $output;
    }

    function findUserById($id) {

    }

    function findUserByUsername($username) {

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

    function readData($dbname, $sql) {
        $conn = connectDatabase($dbname);
        $output = array();
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $output[] = $row;
            }
          } else {
            $output = "NO_DATA_FOUND";
          }
        mysqli_close($conn);
        return $output;
    }

    function writeData($dbname, $sql) {
        $conn = connectDatabase($dbname);
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
        mysqli_close($conn);
    }

    function updateData($dbname, $sql) {
        $conn = connectDatabase($dbname);
        if (mysqli_query($conn, $sql)) {
            // echo "Record updated successfully";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
        mysqli_close($conn);
    }

    function removeData() {

    }



?>
