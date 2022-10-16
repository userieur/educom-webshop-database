<?php
    require_once("Presentation/formbuilder.php");

    function showLoginHeader() {
        echo '<h1>Halloooooooo</h1>';
    }

    function getLoginData() {
        $directory = "Data";
        $file = "users.txt";
        $input = array(
            'email' => array('type|email','label|E-Mail:','placeholder|jan.v.d.steen@provider.com','checks|validEmail|emailKnown')
            ,'pword' => array('type|password','label|Wachtwoord:','placeholder|vul wachtwoord in','checks|validPassword|matchRecord')
        );
        $fileString = createFileString($directory, $file);
        $data = buildFormArray($input);
        return(array('formArray' => $data, 'fileString' => $fileString));
    }

    function showLoginContent($page, $data) {
        echo '<h3>HIERRR INLOGGEN</h3><br>';
        // var_dump($data);
        $css = 'contactform';
        showForm($page, $css, $data);
    }
?>


