<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Lesson_model_test extends CI_Model {
   public function Mlist(){
    $this->db->select('*');
    $this->db->from('lessons');
    $query = $this->db->get();
    return $data = $query->result_array();
   }
   public function McheckLessonUserExist($user_id,$lesson_id,$lesson_status){
    $query = $this->db->query("SELECT user_id FROM user_lessons WHERE lesson_id='".$lesson_id."' AND user_id='".$user_id."'");
      return  $query->num_rows();
   }
   public function insertUserLesson($UserData){
    if($this->db->insert('user_lessons',$UserData)){
      return true;
    }else{
      return false;
    }
   }
   public function lessonLog($UserData){
    $this->db->insert('user_lessons_log',$UserData);
   }
  public function deleteUserFromLesson($lesson_id,$user_id){
    $this->db->delete('user_lessons',array('lesson_id'=>$lesson_id,'user_id'=>$user_id));
    return $this->db->affected_rows();
  }
   public function updateVideoStatus($video_status,$lesson_id){
    $this->db->set('video_status', $video_status);
    $this->db->where(['lesson_id'=>$lesson_id,'last_action'=>'yes']);
    $this->db->update('user_lessons');
   }
   public function updateDialogId($dialog_id,$lesson_id){
    $this->db->set('dialog_id', $dialog_id);
    $this->db->where(['lesson_id'=>$lesson_id,'last_action'=>'yes']);
    $this->db->update('user_lessons');
   }
   public function updateUserCount($lesson_id){
      $query = $this->db->query("SELECT user_id FROM user_lessons WHERE lesson_id='".$lesson_id."'");
      $user_count = $query->num_rows();

      $this->db->set('user_count', $user_count);
      $this->db->where('lesson_id', $lesson_id);
      $this->db->update('lessons');
   }
   //   public function checkUserExist($lesson_id){
// $query = $this->db->query("SELECT user_lesson_id FROM user_lessons WHERE lesson_id='".$lesson_id."'");
// return  $query->num_rows();
// }

   public function getInList($lesson_id){
     $this->db->select('users.user_id,users.name,user_lessons.lesson_id,users.user_type,users.quick_blox_id,user_lessons.dialog_id,user_lessons.video_status');
$this->db->from('users');
$this->db->join('user_lessons', 'users.user_id = user_lessons.user_id');
$this->db->join('lessons', 'lessons.lesson_id = user_lessons.lesson_id');
$this->db->where(
    [
    'user_lessons.lesson_id' => $lesson_id,
    'lessons.user_count >'=> 0,
    ]);
$query = $this->db->get();
return $query->result_array();
//print_r($this->db->last_query()); 
   }
   public function MgetAdminCount($lesson_id){
    $query_admin = $this->db->query("SELECT user_id FROM user_lessons WHERE lesson_id='".$lesson_id."' AND user_type='admin'");
      return $query_admin->num_rows();
   }
   public function MgetStudentCount($lesson_id){
    $query_student = $this->db->query("SELECT user_id FROM user_lessons WHERE lesson_id='".$lesson_id."' AND user_type='student'");
      return $query_student->num_rows();
   }
   public function MgetTotalUserCount($lesson_id){
    $query_admin = $this->db->query("SELECT user_id FROM user_lessons WHERE lesson_id='".$lesson_id."'");
      return $query_admin->num_rows();
   }

}
?>