<?php

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


    function oldfindUserByEmail($fileString, $email) {
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

    function storeUser($fileString, $userInfo) {
        $myFile = addToFile($fileString);
        $line = implode("|", $userInfo)."\n";
        fwrite($myFile, $line);
        closeFile($myFile);
    }


    function createFileString ($directory, $fileName) {
        $directory = trim($directory, "/");
        $file = $fileName;
        $fileString = $directory.'/'.$file;
        return $fileString;
    }

?>