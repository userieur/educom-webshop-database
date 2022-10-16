<?php

function showForm($page, $css, $data) {
    showFormStart($page, $css);
    showFormItems($data);
    showFormEnd();
}

    function showFormStart($page, $css) {
        echo '<form method="POST" action="index.php">
                <div class="' . $css . '">
                <input type="hidden" id="page" name="page" value="' . $page . '">' . PHP_EOL . PHP_EOL;
    }

    function showFormItems($data) {
        // print_r($data);
        foreach($data as $key => $items){
            switch($key) {
                case 'validForm':
                    // var_dump($data['validForm']);
                    break;
                default:
                    // print_r($items);
                    showFormItem($key, $items);
                    break;
            }
        }   
    }

    function showFormEnd() {
        echo '<input type="submit" value="Verstuur">' . PHP_EOL;
        echo '</div>' . PHP_EOL;
        echo '</form>' . PHP_EOL;
    }


    function showFormItem($key, $items) {
        // var_dump($items);
        createLabel($key, $items);
        createInputField($key, $items);
        createSpan($key, $items);
    }

    function createLabel($key, $items) {
        // var_dump($items);
        $type = $items['type'];
        $label = $items['label'];
        switch ($type) {
            case 'text':
            case 'email':
            case 'phone':
            case 'select':
                echo '<label class="field" for="' . $key . '">' . $label . '</label>' . PHP_EOL;
                break;
            case 'radio':
                echo '<p>' . $label . '</p>' . PHP_EOL;
                break;
            case 'textbox':
                echo '<label for="' . $key . '">' . $label . '</label><br>' . PHP_EOL;
                break;
            case 'password':
                break;
            default:
                break;
        }
    }

    function createInputField($key, $items) {
        $type = $items['type'];
        $label = $items['label'];
        $placeholder = $items['placeholder'] ?? "";
        $options = $items['options'] ?? "";
        $value = $items['value'] ?? "";
        echo($value);
        $error = $items['error'] ?? "";
        switch ($type) {
            case 'text':
            case 'email':
            case 'phone':
                echo '<input type="' . $type . '" id="' . $key . '" name="' . $key . '" ' 
                        . (($type == 'phone') ? ('pattern"=[0-9]{2}[0-9]{8}|[0-9]{3}[0-9]{7}" ') : "") 
                        . 'value="' . $value . '" placeholder="' . $placeholder . '">' .PHP_EOL;
                break;
            case 'select':
                echo '<select required id="'.$key.'" name="'.$key.'" placeholder="...">'
                    .'<option selected value="" disabled>'.$placeholder.'</option>' .PHP_EOL;
                foreach($options as $option => $itemtext) {
                    echo '<option '.(($value == $option) ? "selected" : "").' value="'.$option.'">'.$itemtext.'</option>' .PHP_EOL;
                }   echo '</select>' . PHP_EOL;
                break;
            case 'radio':
                foreach($options as $option => $itemtext) {
                    echo '<input required type="radio" id="' . $option . '" name="' . $key . '" ' 
                            . (($value == $itemtext) ? "checked" : "") . ' value="' . $itemtext . '">'
                            . '<label for="' . $option . '">' . $itemtext . '</label>' . PHP_EOL;
                } echo '<br>';
                break;
            case 'textbox':
                echo '<textarea id="' . $key . '" name="' . $key . '" rows="4" cols="50" placeholder="' . $placeholder . '">' 
                      . $value . '</textarea><br>' . PHP_EOL;
                break;
            case 'password':
                echo '<label class="field" for="' . $key . '">' . $label . '</label>' . PHP_EOL;
                echo '<input required type="' . $type . '" id="' . $key . '" name="' . $key . '"' ;
                            // . ' pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"'
                            // . ' title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"';
                break;
            default:
                break;
        }
    }

    function createSpan($key, $items) {
        $error = $items['error'] ?? "";
        echo '<span class="error">'.$error.'</span><br>' . PHP_EOL . PHP_EOL;
    }
    
    function showThankYou($data) {
        foreach($data as $key => $item) {
            switch($key) {
                case 'validForm':
                    // var_dump($data['validForm']);
                    break;
                default:
                    $value = $item['value'];
                    $label = $item['label'];
                    echo '<p>' . $label . '</p><br>' . $value;
                    echo '</div>';
                    break;
            }
        }
    }
?>