<?php
// function getArrayVar($array, $key, $default='') { 
    //     // if the value of $key is valid, 
    //         // return value of $key(page) (about, if page=about)
    //         // otherwise return default value ('home')
    //     return isset($array[$key]) ? $array[$key] : $default; 
    // } 


  //       <option' . (isset($allValuesandErrors['sex']['value']) && $allValuesandErrors['sex']['value'] == "man") ? "selected" : "" . 'value="man">Dhr.</option>
?>


echo '
                <h3>Contactform
                <h5>Voor je shit hieronder in:</h5>
                <form method="POST" action="index.php">
                    <div class="form">

                        <input type="hidden" id="page" name="page" value="contact">
                        
                        <label class="otherlabel" for="sex">Aanhef:</label>
                        <select required name="sex" id="sex" placeholder="...">
                            <option selected value="" disabled>Kies</option>
                            <option ' . ((isset($sex['value']) && $sex['value'] == 'man') ? "selected" : "") . ' value="man">Dhr.</option>
                            <option ' . ((isset($sex['value']) && $sex['value'] == 'woman') ? "selected" : "") . ' value="woman">Mevr.</option>
                        </select>
                        <span class="error">' . (isset($sex['error']) ? $sex['error'] : "") . '</span><br>

                        <label class="otherlabel" for="fname">Voornaam:</label>
                        <input  
                            type="text" id="fname" name="fname" 
                            value="' . (isset($fname['value']) ? $fname['value'] : "") . '"
                            placeholder="Jan">
                        <span class="error">' . (isset($fname['error']) ? $fname['error'] : "") . '</span><br>

                        <label class="otherlabel" for="lname">Achternaam:</label>
                        <input  
                            type="text" id="lname" name="lname" 
                            value="' . (isset($lname['value']) ? $lname['value'] : "") . '"
                            placeholder="van der Straat">
                        <span class="error">' . (isset($lname['error']) ? $lname['error'] : "") . '</span><br>

                        <label class="otherlabel" for="email">E-Mail Adres:</label>
                        <input  
                            type="email" id="email" name="email" 
                            value="' . (isset($email['value']) ? $email['value'] : "") . '"
                            placeholder="jan.vanderstraat@provider.com">
                        <span class="error">' . (isset($email['error']) ? $email['error'] : "") . '</span><br>

                        <label class="otherlabel" for="phone">Telefoon:</label>
                        <input
                            type="phone" id="phone" name="phone" pattern="[0-9]{2}[0-9]{8}|[0-9]{3}[0-9]{7}"
                            value="' . (isset($phone['value']) ? $phone['value'] : "") . '"
                            placeholder="0612345678 / 0101234567">
                        <span class="error">' . (isset($phone['error']) ? $phone['error'] : "") . '</span><br>

                        <p>Ik word het liefst benaderd via:</p>
                        <input required type="radio" id="tel" name="pref" ' . ((isset($pref['value']) && $pref['value'] == 'Telefoon') ? "checked" : "") . ' value="Telefoon">
                        <label for="tel">Telefoon</label>
                        <input type="radio" id="mail" name="pref" ' . ((isset($pref['value']) && $pref['value'] == 'E-Mail') ? "checked" : "") . ' value="E-Mail">
                        <label for="mail">E-Mail</label><br>
                        <br>

                        <label for="story">Reden van contact:</label><br>
                        <textarea id="story" name="story" rows="4" cols="50" placeholder="reden van contact">'. (isset($story['value']) ? $story['value'] : "") . '</textarea><br>
                        
                        <input type="submit" value="Verstuur">
                    </div>
                </form>
            ';
        }


        // $validForm = false;
        // $fname = $lname = $email = $phone = $sex = $pref = $story = "";
        // if ($_SERVER["REQUEST_METHOD"] == "POST") {
        //     $output = checkForErrors(['fname', 'lname', 'email', 'phone', 'sex', 'pref', 'story']);
        //     // echo var_dump($output);

        //     // $fname = $allValuesandErrors['fname'];
        //     // // echo '<br>' . $fname['value'];
        //     // $lname = $allValuesandErrors['lname'];
        //     // // echo '<br>' . $lname['value'];
        //     // $email = $allValuesandErrors['email'];
        //     // // echo '<br>' . $email['value'];
        //     // $phone = $allValuesandErrors['phone'];
        //     // // echo '<br>' . $phone['value'];
        //     // $sex = $allValuesandErrors['sex'];
        //     // // echo '<br>' . $sex['value'];
        //     // $pref = $allValuesandErrors['pref'];
        //     // // echo '<br>' . $pref['value'];
        //     // $story = $allValuesandErrors['story'];
        //     // // echo '<br>' . $story['value'];
        //     // $dinges = isset($story['value']);
        //     // // echo var_dump($dinges);
        // }

        // showForm($page, $css, $formArray);
        
        // $keyArray = array();
        // foreach($formArray as $itemArray) {
        //     $keyArray[] = $itemArray[0];
        // }




        echo'
                <h3>You are Amazing</h3>
                <h5>The data you entered:</h5>
                <div>
                    <p>Voornaam:</p><br>' . $fname['value'] . '
                    <p>Achternaam:</p><br>' . $lname['value'] . '
                    <p>E-Mail Adres:</p><br>' . $email['value'] . '
                    <p>Telefoon:</p><br>' . $phone['value'] . '
                    <p>Geslacht:</p><br>' . $sex['value'] . '
                    <p>Communicatievoorkeur:</p><br>' . $pref['value'] . '
                    <p>Reden van contact:</p><br>' . $story['value'] . '
                </div>
            ';