<?php
    require_once("Presentation/formbuilder.php");

    function showRegistratieHeader() {
        echo '<h1>Halloooooooo</h1>';
    }

    function getXXXXXXXXData() {
        $directory = "Data";
        $file = "users.txt";
        $input = array(
            'sex' => array('type|select','label|Aanhef:','placeholder|Kies', 'options|man:Dhr.|woman:Mevr.')
            ,'fname' => array('type|text','label|Voornaam:','placeholder|Jan', 'checks|validName')
            ,'lname' => array('type|text','label|Achternaam:','placeholder|van der Steen', 'checks|validName')
            ,'email' => array('type|email','label|E-Mail:','placeholder|jan.v.d.steen@provider.com','checks|validEmail')
            ,'phone' => array('type|phone','label|Telefoon:','placeholder|0612345678 / 0101234567','checks|validPhone')
            ,'pref' => array('type|radio','label|Ik word het liefst benaderd via:', 'options|tel:Telefoon|mail:E-Mail')
            ,'story' => array('type|textbox','label|Reden van contact:','placeholder|Vul eens wat in dan!')
            ,'pword' => array('type|password','label|Wachtwoord:','placeholder|vul wachtwoord in','checks|validPassword')
            ,'pwordcheck' => array('type|password','label|Herhaal wachtwoord:','placeholder|herhaal wachtwoord','checks|equalTo:pword')
        );
        $fileString = createFileString($directory, $file);
        $data = buildFormArray($input);
        return(array('formArray' => $data, 'fileString' => $fileString));
    }

    function showXXXXXXXXXContent($page, $data) {
        echo '<h3>Doe InvulXSFAfgasfasf</h3><br>';
        $css = 'contactform';
        showForm($page, $css, $data);
    }
?>


