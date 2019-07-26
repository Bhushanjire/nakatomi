<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_model extends CI_Model {
    public function checkLogin($username,$password){
        $this->db->select('*');
        $data = $this->db->get_where("users", ['username' => $username,'password'=>md5($password)])->row_array();
        return $data;
    }
    public function checkEmail($email){
        $this->db->select('user_id');
        $checkEmail = $this->db->get_where("users", ['email' => $email])->row_array();
        return $checkEmail;
    }
    public function checkUserName($username){
        $this->db->select('username');
        $checkUsername = $this->db->get_where("users", ['username' => $username])->row_array();
        return $checkUsername;
    }
    public function Msignup($data){
        if($this->db->insert('users',$data)){
            $insert_id = $this->db->insert_id(); //last inserted id
             $this->db->set('token', md5($insert_id));
			 $this->db->where('user_id', $insert_id);
			$this->db->update('users');
            $this->db->select('*');
           $returnData =  $this->db->get_where("users", ['user_id' => $insert_id])->row_array();
           return $returnData;
        }
    }
    public function Mprofile($data,$user_id){
        $this->db->where('user_id', $user_id);
        return $this->db->update('users', $data);
    }
    public function updateProfile($user_id,$quick_blox_id){
        $this->db->set('quick_blox_id',$quick_blox_id);
        $this->db->where('user_id',$user_id);
        $this->db->update('users');
    }
}
?>