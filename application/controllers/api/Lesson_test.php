<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lesson_test extends CI_Controller {
	function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        $this->output->set_content_type('application/json');
        //header('Content-Type: application/json; charset=utf-8');
        $this->postdata = file_get_contents("php://input");
        $this->load->model('lesson_model_test');
    }
    public function lessonList(){
    	$data =  $this->lesson_model->Mlist();
    	if(!empty($data)){
    		$responce=array("success"=>true,'data'=>$data,'message'=>'Lesson list with user count');
    	}else{
    		$responce=array("success"=>false,'data'=>'','message'=>'Record not found');
    	}
    	echo json_encode($responce);
    }
    public function lessonInOut(){
    	if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $user_id = $this->request->user_id??'';
             $lesson_id = $this->request->lesson_id??'';
             $lesson_status = $this->request->lesson_status??'';
             $dialog_id = $this->request->dialog_id??'';
             $video_status = $this->request->video_status??'';
             $user_type = $this->request->video_status??'';	
        }else{
            $user_id = $this->input->post('user_id')??'';
            $lesson_id = $this->input->post('lesson_id')??'';
            $lesson_status = $this->input->post('lesson_status')??'';
            $dialog_id = $this->input->post('dialog_id')??'';
            $video_status = $this->input->post('video_status')??'';
            $user_type = $this->input->post('user_type')??'';
        }

        if($user_id !='' && $lesson_id!='' && $lesson_status!='' && $user_id !=0 && $lesson_id!=0){
            $UserData = array(
                    "user_id" => $user_id,
                    "lesson_id" => $lesson_id,
                    "lesson_status" => $lesson_status,
                    "dialog_id" => $dialog_id,
                    "video_status" => $video_status,
                    "user_type" => $user_type
                );
            if($lesson_status=='in'){
                $checkLessonUserExist = $this->lesson_model->McheckLessonUserExist($user_id,$lesson_id);
                if($checkLessonUserExist<1){
                     $totalUserCount = $this->lesson_model->MgetTotalUserCount($lesson_id);
                    if($totalUserCount>3){
                 $message = "Four user already in";
             }else{
                $this->lesson_model->insertUserLesson($UserData);
                 $this->lesson_model->updateUserCount($lesson_id);  
                 $this->lesson_model->lessonLog($UserData);
             }
                }else{
                    $message = "User already $lesson_status";
                }
            }else if($lesson_status=='out'){
                $delResult = $this->lesson_model->deleteUserFromLesson($lesson_id,$user_id);
                     if($delResult){
                        $this->lesson_model->updateUserCount($lesson_id);  
                        $this->lesson_model->lessonLog($UserData);
                         $message = "Lesson out successfully";
                    }else{
                        $message = "User already $lesson_status";
                    }
            }
            if($video_status!=''){
                    $this->lesson_model->updateVideoStatus($video_status,$lesson_id);
                }
                if($dialog_id!=''){
                    $this->lesson_model->updateDialogId($dialog_id,$lesson_id);
                }
            $data = $this->lesson_model->getInList($lesson_id);

if(!empty($data)){
    foreach ($data as $row) {
        if($row['dialog_id']!=''){
             $dialog_id = $row['dialog_id']; //$this->getDialogId($lesson_id);  //$data[0]['dialog_id'];
        }
        if($row['video_status']!=''){
            $video_status = $row['video_status']; //$this->getVideoStatus($lesson_id);//$data[0]['video_status'];
        }
        
    }
     $responce =array('success'=>true,'data'=>$data, 'dialog_id'=>$dialog_id, 'video_status'=>$video_status,'message'=>$message);
}else{
     $responce =array('success'=>true,'data'=>array(),'message'=>'Record not found');
}
        }else{
            $responce=array("success"=>false,'data'=>'','message'=>'Invalid parameters');
        }
        echo json_encode($responce);
    }
/*
        if($user_id !='' && $lesson_id!='' && $lesson_status!='' && $user_id !=0 && $lesson_id!=0){
        	  $checkLessonUserExist = $this->lesson_model->McheckLessonUserExist($user_id,$lesson_id,$lesson_status);
        	if($checkLessonUserExist<1){
                $UserData = array(
                    "user_id" => $user_id,
                    "lesson_id" => $lesson_id,
                    "lesson_status" => $lesson_status,
                    "dialog_id" => $dialog_id,
                    "video_status" => $video_status,
                    "user_type" => $user_type
                );

        		if($lesson_status=='in'){
                    $totalUserCount = $this->lesson_model->MgetTotalUserCount($lesson_id);
                    if($totalUserCount>3){
                 $message = "Four user already in";
             }else{
                 $this->lesson_model->insertUserLesson($UserData);
                 $this->lesson_model->updateUserCount($lesson_id);  
                 $this->lesson_model->lessonLog($UserData);
                $message = "Lesson in successfully";
             }
        		} else{
                     $delResult = $this->lesson_model->deleteUserFromLesson($lesson_id,$user_id);
                     if($delResult){
                        $this->lesson_model->updateUserCount($lesson_id);  
                        $message = "Lesson out successfully";
                        $this->lesson_model->lessonLog($UserData);
                    }else{
                        $message = "User already $lesson_status";
                    }
        		}
                            
        	}else{
        		$message = "User already $lesson_status";
        	}

            if($video_status!=''){
                    $this->lesson_model->updateVideoStatus($video_status,$lesson_id);
                }
                if($dialog_id!=''){
                    $this->lesson_model->updateDialogId($dialog_id,$lesson_id);
                }
            $data = $this->lesson_model->getInList($lesson_id);

if(!empty($data)){
    foreach ($data as $row) {
        if($row['dialog_id']!=''){
             $dialog_id = $row['dialog_id']; //$this->getDialogId($lesson_id);  //$data[0]['dialog_id'];
        }
        if($row['video_status']!=''){
            $video_status = $row['video_status']; //$this->getVideoStatus($lesson_id);//$data[0]['video_status'];
        }
        
    }
     $responce =array('success'=>true,'data'=>$data, 'dialog_id'=>$dialog_id, 'video_status'=>$video_status,'message'=>$message);
}else{
     $responce =array('success'=>true,'data'=>array(),'message'=>'Record not found');
}
        }else{
        	$responce=array("success"=>false,'data'=>'','message'=>'Invalid parameters');
        }
        echo json_encode($responce);
    } */
    public function getUserCount(){
        if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $lesson_id = $this->request->lesson_id??'';
        }else{
            $lesson_id = $this->input->post('lesson_id')??'';
        }
        $adminCount = $this->lesson_model->MgetAdminCount($lesson_id);
        $studentCount = $this->lesson_model->MgetStudentCount($lesson_id);

        $responce =array('success'=>true,'data'=>'', 'admin_count'=>$adminCount, 'student_count'=>$studentCount,'message'=>'Admin and Student count');
        echo json_encode($responce);
    }

    public function insertLesson(){
        if(!empty($this->postdata)){
            $this->request = json_decode($this->postdata);
             $user_id = $this->request->user_id??'';
             $lesson_id = $this->request->lesson_id??'';
             $lesson_status = $this->request->lesson_status??'';
             $dialog_id = $this->request->dialog_id??'';
             $video_status = $this->request->video_status??'';
             $user_type = $this->request->user_type??'';    
        }else{
            $user_id = $this->input->post('user_id')??'';
            $lesson_id = $this->input->post('lesson_id')??'';
            $lesson_status = $this->input->post('lesson_status')??'';
            $dialog_id = $this->input->post('dialog_id')??'';
            $video_status = $this->input->post('video_status')??'';
            $user_type = $this->input->post('user_type')??'';
        }

        $UserData = array(
                    "user_id" => $user_id,
                    "lesson_id" => $lesson_id,
                    "lesson_status" => $lesson_status,
                    "dialog_id" => $dialog_id,
                    "video_status" => $video_status,
                    "user_type" => $user_type
                );
        $result = $this->lesson_model_test->insertUserLesson($UserData);
        if($result){
            $responce =array('success'=>true,'data'=>'','message'=>'User in successfully');
        }else{
            $responce =array('success'=>true,'data'=>'','message'=>'User already in');
        }
        echo json_encode($responce);
    }

}
?>