<?php
   include("conn.php");
    $responce=array();
    $lessonList =array();
    //$conn=mysqli_connect('localhost','root','','nakatomi');
    $query = "SELECT * FROM lessons";
    $res =mysqli_query($conn,$query);
    if(mysqli_num_rows($res)){
        while($data = mysqli_fetch_assoc($res)){
            array_push($responce,$data);
        }
    $res1 =array('success'=>true,'data'=>$responce,'message'=>'Sesson List With User Count');
}else{
    $res1 =array('succes'=>false,'data'=>'','message'=>'Record not found');
}
    echo json_encode($res1);
?>

