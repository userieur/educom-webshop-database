<?php
    require_once("Presentation/formbuilder.php");
    
    function showUserHeader() {
        echo '<h1>Halloooooooo</h1>';
    }

    function getUserData() {
        $input = array(
            'opword' => array('type|password','label|Wachtwoord:','placeholder|vul wachtwoord in','checks|validPassword|matchRecord')
            ,'pword' => array('type|password','label|Wachtwoord:','placeholder|vul wachtwoord in','checks|validPassword')
            ,'pwordcheck' => array('type|password','label|Herhaal wachtwoord:','placeholder|herhaal wachtwoord','checks|equalTo:pword')
        );
        $data = buildFormArray($input);
        return(array('formArray' => $data));
    }

    function showUserContent($page, $data) {
        echo '<h3>WW aanpasseeeeen</h3><br>';
        $css = 'contactform';
        showForm($page, $css, $data);
    }
