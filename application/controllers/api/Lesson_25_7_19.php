<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lesson extends CI_Controller {
	function __construct() {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        $this->output->set_content_type('application/json');
        //header('Content-Type: application/json; charset=utf-8');
        $this->postdata = file_get_contents("php://input");
        $this->load->model('lesson_model');
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
        }else{
            $user_id = $this->input->post('user_id')??'';
            $lesson_id = $this->input->post('lesson_id')??'';
            $lesson_status = $this->input->post('lesson_status')??'';
            $dialog_id = $this->input->post('dialog_id')??'';
            $video_status = $this->input->post('video_status')??'';
        }

        if($user_id !='' && $lesson_id!='' && $lesson_status!='' && $user_id !=0 && $lesson_id!=0){
        	$checkLessonUserExist = $this->lesson_model->McheckLessonUserExist($user_id,$lesson_id,$lesson_status);
        	if(empty($checkLessonUserExist)){
        		if($lesson_status=='in'){
        			$this->lesson_model->incrementUserCount($lesson_id);
        			$message = "Lesson in successfully";
        		}else{
        			$this->lesson_model->decrementUserCount($lesson_id);
        			$message = "Lesson out successfully";
        		}

        		$this->lesson_model->updateActionStatus($lesson_id,$user_id);
        		$UserData = array(
        			"user_id" => $user_id,
        			"lesson_id" => $lesson_id,
        			"lesson_status" => $lesson_status,
        			"dialog_id" => $dialog_id,
        			"video_status" => $video_status
        		);
        		$this->lesson_model->insertUserLesson($UserData);

        		if($video_status!=''){
        			$this->lesson_model->updateVideoStatus($video_status,$lesson_id);
        		}
                if($dialog_id!=''){
                    $this->lesson_model->updateDialogId($dialog_id,$lesson_id);
                }
        		
        	}else{
        		if($video_status!=''){
        			$this->lesson_model->updateVideoStatus($video_status,$lesson_id);
        		}

        		if($dialog_id!=''){
        			$this->lesson_model->updateDialogId($dialog_id,$lesson_id);
        		}

        		$message = "User already $lesson_status";

        	}

            $data = $this->lesson_model->getInList($lesson_id);

              
if(!empty($data)){
    $dialog_id = $data[0]['dialog_id'];
    $video_status = $data[0]['video_status'];
     $responce =array('success'=>true,'data'=>$data, 'dialog_id'=>$dialog_id, 'video_status'=>$video_status,'message'=>$message);
     
}else{
     $responce =array('success'=>true,'data'=>array(),'message'=>'Record not found');
}
        }else{
        	$responce=array("success"=>false,'data'=>'','message'=>'Invalid parameters');
        }

        echo json_encode($responce);
    }
}