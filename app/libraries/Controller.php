<?php
    /*
     * Base Controller
     * Loads the models & views
     */

    class Controller {
        //Load model
        public  function model($model){
            //Require model files
            require_once  '../app/models/'. $model. '.php';

            //Instantiate model
            return new $model();
        }

        //Laod view
        public  function view($view, $data = []){
            //check for view files
            if(file_exists('../app/views/'. $view. ".php")){
                //Require view files
                require_once '../app/views/'. $view. '.php';

            } else {
                die('View does not exits');
            }


        }
    }
?>