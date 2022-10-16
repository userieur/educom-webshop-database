<?php
    require_once("Presentation/pagebuilder.php");
    require_once("Business/basics.php");
    session_start();
    // echo ($_SERVER["PHP_SELF"]);
    // echo var_dump($_SERVER);

    $data=NULL;
    $page = getRequestedPage();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $processed = processRequest($page);
        $page = $processed['page'];
        $data = $processed['data'];
    }
    if ($page == 'loguit') {
        doLogoutUser();
        $page = 'home';
    }
    $pageTitle = createTitle($page);
    showResponsePage($page, $pageTitle, $data);

    // VAR-DUMP Template
    // echo '<br> Ik ben bij ************ : <br>';
    // var_dump($*********);

    // var_dump($_SESSION);
?>