<?php
    function showCartHeader () {
        echo '<h1>The wheels of the cart go round and round</h1>';
    }

    function getCartTitle() {
        return 'Circle != 0';
    }
    
    function getCartData() {
        $cart = getProductsByIdArray($_SESSION['cart']);
        return($cart);
    }

    function showCartContent($page, $data) {
        echo '<h3>Jadajdjajdaj</h3>';
    }
