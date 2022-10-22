<?php
    // Identifying the requested page + all functions
    require_once("Business/basics.php");
    require_once("Business/validation.php");


    function getRequestedPage() {     
        $requested_type = $_SERVER['REQUEST_METHOD']; 
        if ($requested_type == 'POST') { 
            $requested_page = getPostVar('page','home'); 
        } else { 
            $requested_page = getUrlVar('page','home'); 
        } 
        return $requested_page;
    }

    function getVar($key, $default='') {
        $requested_type = $_SERVER['REQUEST_METHOD']; 
        if ($requested_type == 'POST') { 
            $var = getPostVar($key,'postvar'); 
        } else { 
            $var = getUrlVar($key,'getvar'); 
        } 
        return $var;
    }

    function getPostVar($key, $default='') { 
        $value = filter_input(INPUT_POST, $key); 
        return isset($value) ? $value : $default; 
    } 

    function getUrlVar($key, $default='') { 
        $value = filter_input(INPUT_GET, $key);
        return isset($value) ? $value : $default;  
    }

    function processRequest($page) {
        require_once('Pages/'.$page.'.php');
        $data = "";
        switch ($page) {
            case 'contact':
                $formArray = getContactData()['formArray'];
                $data = validateForm($formArray);
                if ($data['validForm']) {
                    $page = 'thanks';
                }
                break;
            case 'registratie':
                $formArray = getRegData()['formArray'];
                $data = validateForm($formArray);
                if ($data['validForm']) {
                    $userInfo = array();
                    $userInfo += ['username' => $data['uname']['value']];
                    $userInfo += ['email' => $data['email']['value']];
                    $userInfo += ['password' => $data['pword']['value']];
                    storeUser($userInfo);
                    $page = 'login';
                    $data = NULL;
                }
                break;
            case 'login':
                $formArray = getLoginData()['formArray'];
                $data = validateForm($formArray);
                if ($data['validForm']) {
                    $page = 'home';
                    $email = $data['email']['value'];
                    $userInfo = findUserByEmail($email);
                    doLoginUser($userInfo);
                    $data = NULL;
                }
                break;
            case 'thanks':
                //logoout
                break;
            case 'userpage':
                $formArray = getUserData()['formArray'];
                $data = validateForm($formArray);
                if ($data['validForm']) { 
                    $userInfo = array();
                    $userInfo += ['email' => $_SESSION['email']];
                    $userInfo += ['password' => $data['pword']['value']];
                    updatePassword($userInfo);
                    $page = 'updated';
                    $data = NULL;
                }
                break;
            case 'webshop':
                $name = getVar('name');
                handleActions($name);
                $data = NULL;
                break;
            case 'detail':
                $name = getVar('name');
                handleActions($name);
                $data = NULL;
                break;
            case 'cart':
                $name = getVar('name');
                handleActions($name);
                $data = NULL;
            default:
                //default
                break;
            }
        return (array('page' => $page, 'data' => $data));
    }

    function handleActions($id) {
        $action = getPostVar("action");
        switch($action) {
            case "addToCart":
                if (array_key_exists($id, $_SESSION['cart'])) {
                    $_SESSION['cart'][$id] += 1; 
                } else {
                    $_SESSION['cart'] += [$id => 1];
                }
                break;
            case "removeFromCart":
                if (!isset($_SESSION['cart'][$id])) {
                    // do nothing;
                } elseif ($_SESSION['cart'][$id] == 1) {
                    unset($_SESSION['cart'][$id]); 
                } else {
                    $_SESSION['cart'][$id] -= 1;
                }
                break;
            case "order":
                if ($_SESSION['cart']) {
                    placeOrder();
                    unset($_SESSION['cart']);
                    $_SESSION['cart'] = [];
                } else {
                    echo 'Mandje is leeg G'; 
                }
                break;
        }
    }

    // Constructing the requested page + all functions
    function showResponsePage($page, $pageTitle, $data) {
        beginDocument(); 
        showHeadSection($pageTitle); 
        showBodySection($page, $data); 
        endDocument(); 
    }

    function beginDocument() { 
        echo '<!doctype html> 
              <html>';
    } 

    function createTitle($page) {
        // $pageString = 'Pages/'.$page.'.php';
        // require_once($pageString);
        // $pagetitle = getTitle();
        $pagetitle = "doe ik weer met OOP";
        return $pagetitle;
    }

    function showHeadSection($pageTitle) {
        echo '<head>
                <title>' . $pageTitle . '</title>
                <link rel="stylesheet" href="Presentation/stylesheet.css">
              </head>';
    } 

    function showBodySection($page, $data) {
        echo '    <body>' . PHP_EOL; 
        showHeader($page);
        showSessionMenu($page);
        showMenu($page); 
        showContent($page, $data); 
        showFooter(); 
        echo '    </body>' . PHP_EOL; 
    } 

    function endDocument() { 
        echo  '</html>'; 
    } 

    function showHeader($page) { 
        require_once('Pages/'.$page.'.php');
        switch ($page) {
            case 'home':
                showHomeHeader();
                break;
            case 'about':
                showAboutHeader();
                break;
            case 'contact':
                showContactHeader();
                break;
            case 'registratie':
                showRegHeader();
                break;
            case 'thanks':
                showThanksHeader();
                break;
            case 'login':
                showLoginHeader();
                break;
            case 'userpage':
                showUserHeader();
                break;
            case 'updated':
                showUpdatedHeader();
                break;
            case 'webshop':
                showWebshopHeader();
                break;
            case 'detail':
                showDetailHeader();
                break;
            case 'cart':
                showCartHeader();
                break;
            default:
                $page = 'home';
                require_once('Pages/'.$page.'.php');
                showHomeHeader();
                break;
        }
    }

    function showMenu($page) { 
        echo '<ul class="menu">
                <li><a class="' . (($page == "home") ? "active" : "") . '"href="index.php?page=home">Home</a></li>
                <li><a class="' . (($page == "about") ? "active" : "") . '"href="index.php?page=about">About</a></li>
                <li><a class="' . (($page == "contact") ? "active" : "") . '"href="index.php?page=contact">Contact</a></li>
                <li><a class="' . (($page == "webshop") ? "active" : "") . '"href="index.php?page=webshop">Webshop</a></li>
              </ul>';        
    }

    function showSessionMenu($page) {
        if (isUserLoggedIn() == false) {
            echo '<ul class="menu">
            <li><a class="' . (($page == "registratie") ? "active" : "") . '"href="index.php?page=registratie">Registratie</a></li>
            <li><a class="' . (($page == "login") ? "active" : "") . '"href="index.php?page=login">Login</a></li>
          </ul>'; 
        } else {
            echo '<ul class="menu">
            <li><a class="' . (($page == "userpage") ? "active" : "") . '"href="index.php?page=userpage">UserPage</a></li>
            <li><a class="' . (($page == "loguit") ? "active" : "") . '"href="index.php?page=loguit">Loguit</a></li>
            <li><a class="' . (($page == "cart") ? "active" : "") . '"href="index.php?page=cart">Cart</a></li>
          </ul>';  
        }
    }

    function showContent($page, $data) { 
        require_once('Pages/'.$page.'.php');
        switch ($page) { 
            case 'home':
                showHomeContent();
                break;
            case 'about':
                showAboutContent();
                break;
            case 'contact':
                $data = $data ?? getContactData()['formArray'];
                showContactContent($page, $data);
                break;
            case 'registratie':
                $data = $data ?? getRegData()['formArray'];
                showRegContent($page, $data);
                break;
            case 'thanks':
                $data = $data ?? getContactData()['formArray'];
                showThanksContent($data);
                break;
            case 'updated':
                showUpdatedContent();
                break;
            case 'login':
                $data = $data ?? getLoginData()['formArray'];
                showLoginContent ($page, $data);
                break;
            case 'userpage':
                $data = $data ?? getUserData()['formArray'];
                showUserContent($page, $data);
                break;
            case 'webshop':
                $data = $data ?? getWebshopData();
                showWebshopContent($page, $data);
                showItems($page, $data);
                break;
            case 'detail':
                $id = getVar('id');
                $data = getDetailData($id);;
                showDetailContent($data);
                showDetailItem($page, $data);
                break;
            case 'cart':
                $data = $data ?? getCartData();
                showCartContent($page, $data);
                showCartItems($page, $data);
                break;
            default:
                require_once('Pages/home.php');
                showHomeContent();
                break;
        }     
    } 

    function showFooter() {
        echo ' 
            <footer>
                <p>Copyright &copy; <script>document.write(new Date().getFullYear())</script> Roland Felt</p>
            </footer>
            ';
    } 

    function showItems($page, $data) {
        echo '<div class="container">';
        foreach($data as $key => $info) {
            $id = $key;
            $name = $info['name'];
            $imageurl = $info['imageurl'];
            $price = $info['price'];
            $description = $info['description'];
            echo '
            <div class="webshop">
                <img src="Images/'.$imageurl.'" alt="'.$name.'"><br>
                <p class="name"><a href="index.php?page=detail&id='.$id.'">'.$name.'</a></p><br>
                <p class="id">Productnr. = '.$id.'</p>
                <p class="price">€ '.$price.'</p>
                <p class="description">'.$description.'</p><br>';
            if (isset($_SESSION['user'])) {
                addToCartForm($page, $name, $id);
                removeFromCartForm($page, $name, $id);
            }
            echo '</div>';
        echo '</div>';
        }
    }

    function showDetailItem($page, $info) {
        $id = $info['id'];
        $name = $info['name'];
        $imageurl = $info['imageurl'];
        $price = $info['price'];
        $description = $info['description'];
        echo '
        <div class="webshop">
            <img class="big" src="Images/'.$imageurl.'" alt="'.$name.'"><br>
            <p class="name">'.$name.'</a></p><br>
            <p class="id">Productnr. = '.$id.'</p>
            <p class="price">€ '.$price.'</p>
            <p class="description">'.$description.'</p><br>';
        if (isset($_SESSION['user'])) {
            addToCartForm($page, $name, $id);
            removeFromCartForm($page, $name, $id);
        }
        echo '</div>';
    }

    function showCartItems($page, $data) {
        echo '<div class="container">';
        $grandTotal = 0;
        $_SESSION['invoicelines'] = [];
        if ($data) {
            foreach($data as $key => $info) {
                $id = $key;
                $name = $info['name'];
                $imageurl = $info['imageurl'];
                $price = $info['price'];
                $description = $info['description'];
                $count = $_SESSION['cart'][$name];
                $_SESSION['invoicelines'] += [$name => array()];
                $_SESSION['invoicelines'][$name] += ['sales_amount' => $count];
                $_SESSION['invoicelines'][$name] += ['sales_price' => $price];
                $_SESSION['invoicelines'][$name] += ['article_id' => $id];
                echo '
                <div class="webshop">
                    <img class="cart" src="Images/'.$imageurl.'" alt="'.$name.'"><br>
                    <p class="name"><a href="index.php?page=detail&id='.$id.'">'.$name.'</a></p><br>
                    <p class="count">Items'.$count.'</p>
                    <p class="price">€ '.$price.'</p>
                    <p class="subtotal">€ '.$count*$price.'</p>
                    <p class="description">'.$description.'</p><br>';
                if (isset($_SESSION['user'])) {
                    addToCartForm($page, $name, $id);
                    removeFromCartForm($page, $name, $id);
                }
                $grandTotal += ($count*$price);
                echo '</div>';
            echo '</div>';
            }
            echo '<p class="total">Total = € '.$grandTotal.'</p><br>';
            orderForm($page, $name, $id);
        }
         else {
            echo 'cart is empty G';
            unset($_SESSION['invoicelines']);
        }
    }
?>