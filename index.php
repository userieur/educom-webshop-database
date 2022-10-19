<?php
    require_once("Presentation/pagebuilder.php");
    require_once("Business/basics.php");
    session_start();

    // echo($_SESSION['email']);
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

    // $email = 'test@test.nl';
    // $ding = FindUserByEmail($email);
    // var_dump($ding);
?>