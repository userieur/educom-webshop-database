<?php
    require_once ("Presentation/formbuilder.php");
    
    function showThanksHeader() {
        echo '<h1>You are AWESOME-O</h1>';
    }

    function showThanksContent($data) {
        echo 'The information you have entered:';
        showThankYou($data);
    }

?>



