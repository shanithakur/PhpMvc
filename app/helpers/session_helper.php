<?php
    session_start();

/**
 * @param string $name
 * @param string $message
 * @param string $class
 * @return html div with flash message
 */
    function flash($name = '', $message = '', $class = 'alert alert-success'){
        if(!empty($name)){
            if(!empty($message) && empty($_SESSION[$name])){
                if(!empty($_SESSION[$name])){
                    unset($_SESSION[$name]);
                }
                if(!empty($_SESSION[$name.'_class'])){
                    unset($_SESSION[$name.'_class']);
                }
                $_SESSION[$name] = $message;
                $_SESSION[$name.'_class'] = $class;
            } elseif (empty($message) && !empty($_SESSION[$name])){
                $class = !empty($_SESSION[$name.'_class']) ? $_SESSION[$name.'_class'] : '';
                echo '<div class="'.$class.'" id="msg_flash">'.$_SESSION[$name].'</div>';
                unset($_SESSION[$name]);
                unset($_SESSION[$name.'_class']);
            }
        }
    }

/**
 * check if user is logged in or not
 * @return bool true on success else false
 */
     function isLoggedIn(){
        if(isset($_SESSION['user_id'])){
            return true;
        } else {
            return false;
        }
    }
