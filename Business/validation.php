<?php
    function validName($value, $param=NULL) {
        $error = NULL;
        if (empty($value)) {
            $error = "Name is required";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$value)) {
            $error = "Only letters and white space allowed"; 
        }
        return $error;
    }

    function validEmail($value, $param=NULL) {
        $error = NULL;
        if (empty($value)) {
            $error = "E-mail address is required";
        } elseif (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a correct e-mail address";
        }
        return $error;
    }

    function validPhone($value, $param=NULL) {
        $error = NULL;
        if (empty($value)) {
            $error = "Phone is required";
        } elseif (!is_numeric($value)) {
            $error = "Please enter a correct phone number";
        }
        return $error;
    }

    function validComment($value, $param=NULL) {
        $error = NULL;
        if (empty($value)) {
            $error = "Please enter reasons";
        }
        return $error;
    }

    function validPassword($value, $param=NULL) {
        $error = NULL;
        if (empty($value)) {
            $error = "Please enter password";
        } elseif (!preg_match("/^[a-zA-Z-' ]*$/",$value)) {
            $error = "Only letters and white space allowed"; 
        }
        return $error;
    }

    function equalTo($value, $param=NULL) {
        $error = NULL;
        $comparable = cleanInput($_POST[$param['functionVar']]);
        if (empty($value)) {
            $error = "Please repeat input";
        } elseif ($value != $comparable) {
            $error = "Input does not match"; 
        }  
        return $error;
    }

    function validLogin($value, $param=NULL) {
        $error = NULL;
        $fileString = $param['fileString'];
        if (passwordMatchEmail($fileString, $value) == false) {
            $error = "Password is incorrect";
        }
        return $error;
    }

    function emailNotKnown($value, $param=NULL) {
        $error = NULL;
        $fileString = $param['fileString'];
        $exists = doesEmailExist($fileString, $value);
        if ($exists == true) {
            $error = "E-Mail already exists";
        }
        return $error;
    }

    function emailKnown($value, $param=NULL) {
        $error = NULL;
        $fileString = $param['fileString'];
        $exists = doesEmailExist($fileString, $value);
        if ($exists == false) {
            $error = "E-Mail not known";
        }
        return $error;
    }

    function cleanInput ($value) {
        $value = trim($value);
        $value = stripslashes($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    function checkForError ($value, $check, $param=NULL) {
        // echo($value);
        // echo($check);
        // var_dump($param);
        $param = $param ?? array();
        $checkArray = explode(":", $check);
        $functionName = $checkArray[0] ?? $check;
        $functionVar = $checkArray[1] ?? NULL;
        $param += ['functionVar' => $functionVar];
        $error = call_user_func_array($functionName, array($value, $param));
        return $error;
    }

    function validateInput($key, $items, $param=NULL) {
        $checks = $items['checks'] ?? NULL;
        $value = cleanInput($_POST[$key]) ?? "";
        $output = array('value' => $value);
        if ($checks != NULL) {
            foreach($checks as $check) {
                $error = checkForError($value, $check, $param);
                if ($error != NULL) {
                    $output += ['error' => $error];
                    break;
                }
            }
        }
        return $output;
    }           

    function checkDatabase ($key, $database) {
        // open database
        // check entry voor 
        // finduser by email
        // save user
    }

    function matchRecord($value, $param=NULL) {
        $error = NULL;
        $email = cleanInput($_POST['email']);
        $fileString = $param['fileString'];
        $exists = doesEmailExist($fileString, $email);
        if ($exists == true) {
            $userInfo = findUserByEmail($fileString, $email);
            if ($value != cleanInput($userInfo[2])) {
                $error = "Password incorrect";
            }
        }
        return $error;  
    }
  

    function validateForm($data, $fileString=NULL) {
        $output = $data;
        $noErrors = true;
        $param = array('fileString' => $fileString);
        foreach($data as $key => $items) {
            if ($key != 'validForm') {
                $checkedInput = validateInput($key, $items, $param);
                $output[$key] += ['value' => $checkedInput['value']];
                if (isset($checkedInput['error'])) {
                    $noErrors = false;
                    $output[$key] += ['error' => $checkedInput['error']];
                }
            }
        }
        if ($noErrors == true) {
            $output['validForm'] = true;
        }
        return $output;
    }
?>