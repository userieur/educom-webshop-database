<?php
    require_once("Presentation/formbuilder.php");
    
    function showWebshopHeader() {
        echo '<h1>Webshop</h1>';
    }

    function getWebshopData() {
        $webshopItems = getAllProducts();
        return $webshopItems;
    }

    function showWebshopContent($page, $data) {
        echo '<h3>Webshop inhoud</h3><br>';
    }
?>


