<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
	public function index()
	{
		
    }
    
    function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        //$this->output->set_content_type('application/json');
        header('Content-Type: application/json; charset=utf-8');
        $this->postdata = file_get_contents("php://input");
        $this->load->model('user_model');
       
    }
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
                    $responce=array("success"=>false,'data'=>'','message'=>'Invalid Username OR Password');
                }
            }else{
                $responce=array("success"=>false,'data'=>'','message'=>'Invalid Data');
            }
         echo json_encode($responce);
    }

     public function signup(){
        $resData = array();
        if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $name = $this->request->name??'';
             $birth_date = $this->request->birth_date??'';
             $gender = $this->request->gender??'';
             $email = $this->request->email??'';
             $profile_photo = $this->request->profile_photo??'';
             $username = $this->request->username??'';
             $password = $this->request->password??'';
             $user_type = 'student';
             $quick_blox_id = 0;
             $token = '';
        }else{
            $name = $this->input->post('name')??'';
            $birth_date = $this->input->post('birth_date')??'';
            $gender = $this->input->post('gender')??'';
            $email = $this->input->post('email')??'';
            $profile_photo = $this->input->post('profile_photo')??'';
            $username = $this->input->post('username')??'';
            $password = $this->input->post('password')??'';
            $user_type = 'student';
            $quick_blox_id = 0;
            $token = 0;
        }
        $checkUsername =   $this->user_model->checkUserName($username);
        if(!(empty($checkUsername))){
            $responce=array("success"=>true,'data'=>'','message'=>'Username already exists');
            echo json_encode($responce);
            exit();
        }
        $checkEmail =   $this->user_model->checkEmail($email);
         if(!(empty($checkEmail))){
            $responce=array("success"=>true,'data'=>'','message'=>'Email-ID already exists');
            echo json_encode($responce);
            exit();
        }
	$filename ='';
if($profile_photo!=''){
       $image = base64_decode($profile_photo);
       $baseUrl = "http://ec2-13-211-168-172.ap-southeast-2.compute.amazonaws.com/nakatomi/assets/images/";
        $image_name = md5(uniqid(rand(), true));
        $filename = $image_name . '.' . 'png';
        $path = "assets/images/";
        file_put_contents($path . $filename, $image);
        $profile_photo = $baseUrl.$filename;
}
        $data =array(
            "name" => $name,
            "birth_date" => date('Y-m-d', strtotime($birth_date)),
            "gender" => $gender,
            "email" => $email,
            "profile_photo" => $profile_photo,
            "username" => $username,
            "password" => md5($password),
            "user_type" => $user_type,
            "quick_blox_id" => $quick_blox_id,
            "token" => $token
        );
        if($name!='' && $email!='' && $username!=''){
              $resData =  $this->user_model->Msignup($data);
              $resData['birth_date']=date('d M Y', strtotime($resData['birth_date']));
              if(!empty($resData)){
             $responce=array("success"=>true,'data'=>$resData,'message'=>'Signup successfully');
            echo json_encode($responce);
            exit();
        }else{
            $responce=array("success"=>false,'data'=>'','message'=>'Error');
            echo json_encode($responce);
            exit();
        }
        }else{
             $responce=array("success"=>false,'data'=>'','message'=>'Invalid data');
            echo json_encode($responce);
            exit();
        }
    }
    public function profile(){
        if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $user_id = $this->request->user_id??'';
             $quick_blox_id = $this->request->quick_blox_id??'';
        }else{
            $user_id = $this->input->post('user_id')??'';
            $quick_blox_id = $this->input->post('quick_blox_id')??'';
        }
        if($user_id!='' && $user_id!=0 && $quick_blox_id!=''){
             $this->user_model->updateProfile($user_id,$quick_blox_id);
             $responce=array("success"=>true,'data'=>'','message'=>'Profile updated successfully');
            echo json_encode($responce);
            exit();
        }else{
             $responce=array("success"=>false,'data'=>'','message'=>'Invalid data');
            echo json_encode($responce);
            exit();
        }
       
    }
}
?>