<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class USer extends CI_Controller {

	public function index()
	{
		
    }
    
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        $this->output->set_content_type('application/json');
        //header('Content-Type: application/json; charset=utf-8');
        $this->postdata = file_get_contents("php://input");
        $this->load->model('user_model');
       
    }
    // public function login(){
    //     ini_set('display_errors', 1);

    //     if(!empty($this->postdata)){
    //         $this->request = json_decode($this->postdata);
    //          $username = $this->request->username;
    //         $password = $this->request->password; 
    //         }else{
    //         $username = $this->input->post('username');
    //         $password = $this->input->post('password');
    //         }
    //     $responce = array('user_id'=>1,'name'=>'Bhushan Jire');
    //      echo json_encode($responce);
    // }
    public function login(){
        if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $username = $this->request->username;
            $password = $this->request->password; 
            }else{
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            }

            if(!empty($username)&&!empty($password)){
                $data = $this->user_model->checkLogin($username, $password);
                if(!(empty($data))){
                    $responce=array("success"=>true,'data'=>$data,'message'=>'Login successfully');
                }else{
                    $responce=array("success"=>true,'data'=>$data,'message'=>'Invalid Username/Password');
                }
            }
        
         echo json_encode($responce);
    }
}
?>
