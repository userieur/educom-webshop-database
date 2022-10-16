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
    function doesEmailExist($fileString, $email) {
        $userInfo = findUserByEmail($fileString, $email);
        $emailExists = false;
        if ($userInfo !== Null) {
            $emailExists = true;
        }
        return $emailExists;
    }

    function passwordMatchEmail($fileString, $value) {
        $userInfo = findUserByEmail($fileString, $value);
        $match = false;
        if ($value == $userInfo[2]) {
            $match = true;
        }
        return $match;
    }

    function authenticateUser() {
        $userInfo = findUserByEmail($fileString, $email);
        // return NULL / Array
    }

    function storeUser($fileString, $userInfo) {
        $myFile = addToFile($fileString);
        $line = implode("|", $userInfo)."\n";
        fwrite($myFile, $line);
        closeFile($myFile);
    }

    // SESSION MANAGER
    function doLoginUser($userInfo) {
        // session_start();
        $_SESSION['user'] = $userInfo[1];
        $_SESSION['email'] = $userInfo[0];
        $_SESSION['password'] = $userInfo[2];
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
    function findUserByEmail($fileString, $email) {
        $myFile = readOnlyFile($fileString);
        while(!feof($myFile)) {
            $lineArray = explode("|", fgets($myFile));
            if ($lineArray[0] == $email) {
                $userInfo = $lineArray;
            }
        }
        closeFile($myFile);
        $output = $userInfo ?? NULL;
        return $output;
    }


    function findUserById() {

    }

    function findUserByUsername() {

    }

    function createFileString ($directory, $fileName) {
        $directory = trim($directory, "/");
        $file = $fileName;
        $fileString = $directory.'/'.$file;
        return $fileString;
    }

    function readOnlyFile($fileString) {
        $myFile = fopen($fileString, "r") or die("Unable to open file!");
        return $myFile;
    }

    function addToFile($fileString) {
        $myFile = fopen($fileString, "a") or die("Unable to open file!");
        return $myFile;
    }

    function closeFile($file) {
        fclose($file);
    }

    function readData() {

    }

    function writeData() {

    }

    function updateData() {

    }

    function removeData() {

    }



?>

