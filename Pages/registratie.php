<?php
    require_once("Presentation/formbuilder.php");

    function showRegHeader() {
        echo '<h1>Halloooooooo</h1>';
    }

    function getRegTitle() {
        return 'Join us..';
    }

    function getRegData() {
        $directory = "Data";
        $file = "users.txt";
        $input = array(
            'uname' => array('type|text','label|Gebruikersnaam:','placeholder|Jan', 'checks|validName')
           ,'email' => array('type|email','label|E-Mail:','placeholder|j.v.d.steen@provider.com','checks|validEmail|emailNotKnown')
           ,'pword' => array('type|password','label|Wachtwoord:','placeholder|vul wachtwoord in','checks|validPassword')
           ,'pwordcheck' => array('type|password','label|Herhaal wachtwoord:','placeholder|herhaal wachtwoord','checks|equalTo:pword')
        );
        $fileString = createFileString($directory, $file);
        $data = buildFormArray($input);
        return(array('formArray' => $data, 'fileString' => $fileString));
    }

    function showRegContent($page, $data) {
        echo '<h3>Doe Invullen</h3><br>';
        $css = 'contactform';
        showForm($page, $css, $data);
    }
    
?>


