<?php
    require_once("Presentation/formbuilder.php");
    
    function showDetailHeader() {
        echo '<h1>Webshop</h1>';
    }

    function getDetailData($id) {
        $detailItem = getProductById($id);
        return $detailItem;
    }

    function showDetailContent($data) {
        echo '<h3>De Details...</h3><br>';
    }
