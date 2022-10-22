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
        // var_dump($_SESSION['cart']);
        // showCartItems();
    }


    // Bestellingen worden bewaard in de sessie. -> meteen orders maken
    // Maak een pagina 'shoppingcart' aan die een overzicht geeft van de bestelde items (inclusief sub-totaal en een klein plaatje) en een berekening van de totale prijs.

    // Als je op de regel klikt dan ga je naar de detail pagina van dit product. 
    // (Optioneel) kan je nog toevoegen dat je in de shoppingcart pagina het aantal bestelde producten kan verhogen of verlagen.
