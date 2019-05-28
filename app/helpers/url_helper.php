<?php
/**
 * @param $page
 * @reditect to specified page name
 */
    function redirect($page){
        header('location:'. URLROOT. '/'. $page);
    }