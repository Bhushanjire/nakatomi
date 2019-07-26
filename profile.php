<?php
   include("conn.php");
    if(!empty($postdata)){
    $request = json_decode($postdata);
    $user_id = $request->user_id;
    $quick_blox_id = $request->quick_blox_id; 
    }else{
        $user_id = $_POST['user_id'] ;
        $quick_blox_id = $_POST['quick_blox_id']; 
    }
    //$conn=mysqli_connect('localhost','root','','nakatomi');
    $query = "UPDATE users SET quick_blox_id='".$quick_blox_id."' WHERE user_id='".$user_id."'";
    $res =mysqli_query($conn,$query);
    if($res){
    $res1 =array('success'=>true,'data'=>'','message'=>'Profile updated successfully');
}else{
    $res1 =array('succes'=>false,'data'=>'','message'=>'Error');
}
    echo json_encode($res1);
?>

