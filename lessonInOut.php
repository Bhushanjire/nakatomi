<?php
    //echo phpinfo();
   include("conn.php");
   $responce =array();
    if(!empty($postdata)){
    $request = json_decode($postdata);
    $user_id = $request->user_id??'';
    $lesson_id = $request->lesson_id??'';
    $lesson_status = $request->lesson_status??''; 
    $dialog_id = $request->dialog_id??'';
    $video_status = $request->video_status??'';
    $user_type = $request->user_type??'';
    }else{
        $user_id = $_POST['user_id']??'' ;
        $lesson_id = $_POST['lesson_id']??''; 
        $lesson_status = $_POST['lesson_status']??''; 
        $dialog_id = $_POST['dialog_id']??''; 
        $video_status = $_POST['video_status']??''; 
        $user_type = $_POST['user_type']??'';
    }

   if($user_id !='' && $lesson_id!='' && $lesson_status!='' && $user_id !=0 && $lesson_id!=0){

   $query6 = "SELECT user_id FROM user_lessons WHERE user_id='".$user_id."' AND lesson_id='".$lesson_id."' AND lesson_status='".$lesson_status."' AND last_action='yes'";
   $res6 = mysqli_query($conn,$query6);
   $checkInout = mysqli_num_rows($res6);

   if(mysqli_num_rows($res6)<1){
if($lesson_status=="in"){
    $query1 = "UPDATE lessons SET user_count=user_count+1 WHERE lesson_id='".$lesson_id."'";
    $message = "Lesson in successfully";
}else{
    $query1 = "UPDATE lessons SET user_count=user_count-1 WHERE lesson_id='".$lesson_id."'";
    $message = "Lesson out successfully";
}
$res1 = mysqli_query($conn,$query1);


$query5 = "UPDATE user_lessons SET last_action='no' WHERE lesson_id='".$lesson_id."' AND user_id='".$user_id."'";
$res5 = mysqli_query($conn,$query5);


$query2 = "INSERT INTO user_lessons(user_id,lesson_id,lesson_status,dialog_id,video_status) VALUES('".$user_id."','".$lesson_id."','".$lesson_status."','".$dialog_id."','".$video_status."')";
$res2 = mysqli_query($conn,$query2);

if($video_status!=""){
	$query7 ="UPDATE user_lessons SET video_status='".$video_status."' WHERE lesson_id='".$lesson_id."' AND last_action='yes'";
	//$query7 ="UPDATE user_lessons SET video_status='".$video_status."' WHERE lesson_id='".$lesson_id."'";
	 $res7 = mysqli_query($conn,$query7);
    }


}else{
    if($video_status!=""){
	$query7 ="UPDATE user_lessons SET video_status='".$video_status."' WHERE lesson_id='".$lesson_id."' AND last_action='yes'";
	//$query7 ="UPDATE user_lessons SET video_status='".$video_status."' WHERE lesson_id='".$lesson_id."'";
	 $res7 = mysqli_query($conn,$query7);
    }
        
   if($dialog_id!=""){
	$query8 ="UPDATE user_lessons SET dialog_id='".$dialog_id."' WHERE lesson_id='".$lesson_id."' AND last_action='yes'";
	//$query8 ="UPDATE user_lessons SET dialog_id='".$dialog_id."' WHERE lesson_id='".$lesson_id."'";
	 $res8 = mysqli_query($conn,$query8);
    }

    $message = "User already $lesson_status";
}

    $query3 = "SELECT U.user_id,U.name,UL.lesson_id,U.user_type,U.quick_blox_id,UL.dialog_id,UL.video_status 
    FROM `users` U 
    JOIN user_lessons UL ON U.user_id=UL.user_id
    JOIN lessons L ON L.lesson_id=UL.lesson_id 
    WHERE UL.lesson_status='in' 
    AND UL.lesson_id='".$lesson_id."' 
    AND L.user_count>0 
    AND UL.user_lesson_id 
    AND UL.last_action='yes'";


    //IN (SELECT MAX(user_lesson_id)FROM user_lessons GROUP BY user_id)
    $res3 = mysqli_query($conn,$query3);
    
    $user_count = mysqli_num_rows($res3);

if($user_count>0){
    while($data = mysqli_fetch_assoc($res3)){
        $data['user_count']=$user_count;  
        $dialog_id = $data['dialog_id'];
        $video_status = $data['video_status'];
        array_push($responce,$data);
    }
    $res1 =array('success'=>true,'data'=>$responce, 'dialog_id'=>$dialog_id, 'video_status'=>$video_status,'message'=>$message);
}else{
    $res1 =array('success'=>true,'data'=>$responce,'message'=>'Record not found');
}   
}else{
    $res1 =array('success'=>false,'data'=>$responce,'message'=>'Invalid Parameters');
}
    echo json_encode($res1);
?>

