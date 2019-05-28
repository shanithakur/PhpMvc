<?php
class Admins extends Controller{

    public function __construct()
    {
        $this->adminModel = $this->model('Admin');
    }

    public function index(){

    }

    /**
     * @function addMenu
     * validate form data and add to database
     * @render to same page after successful submission or return something went wrong on failure
     */
    public function addMenu(){
        //check for post
        if($_SERVER['REQUEST_METHOD']== 'POST'){

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'label' =>trim($_POST['label']),
                'link' =>trim($_POST['link']),
                'target' =>trim($_POST['target']),
                'body'=> $_POST['page_body'],
                'label_err' =>'',
                'link_err' => '',
                'target_err' => ''
            ];

            if(empty($_POST['label'])){
                $data['label_err'] = 'label is required';
            }elseif (empty($_POST['link'])){
                $data['link_err']= 'link is requires';
            }else {
                if($this->adminModel->insertMenu($data)){
                    redirect('admins/addMenu');
                } else {
                    die('Something went wrong');
                }
            }
        } else{
            $data=[
                'label'=>'',
                'link'=> '',
                'target'=> ''

            ];
            $this->view('/admin/addMenu', $data);
        }
    }

    /**
     * @function getMenus
     * @param none
     * @return json encoded data which contains all the menu lists
     */
    public function getMenus(){
        $menu_list = $this->adminModel->getAllMenu();
        echo json_encode($menu_list);
    }

}


?>