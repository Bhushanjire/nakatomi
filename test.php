<?php
    //echo phpinfo();
   include("conn.php");
    if(!empty($postdata)){
    $request = json_decode($postdata);
    $username = $request->username;
    $password = $request->password; 
    }else{
        $username = $_POST['username'] ;
        $password = $_POST['password']; 
    }
   
       //$conn=mysqli_connect('localhost','root','','nakatomi');

    $query = "select * from users WHERE username='".$username."' AND password='".md5($password)."'";
    $res =mysqli_query($conn,$query);
    if(mysqli_num_rows($res)>0){
    $responce = array();
    $res1=array();
    $data = mysqli_fetch_assoc($res);
    // while($data = mysqli_fetch_assoc($res)){
    //     $temp['user_id']=$data['user_id'];
    //     $temp['name']=$data['name'];
    //     $temp['birth_date']=$data['birth_date'];
    //     $temp['gender']=$data['gender'];
    //     $temp['email']=$data['email'];
    //     $temp['username']=$data['username'];
    //     $temp['theme_color_code']=$data['theme_color_code'];
    //     $temp['quick_blox_id']=$data['quick_blox_id'];
    //     $temp['token']=$data['token'];
    //     array_push($responce, $temp);
    // }
    $res1 =array('success'=>true,'data'=>$data,'message'=>'Login Successfully');
    
}else{
    $res1 =array('succes'=>false,'data'=>'','message'=>'Invalid username/password');
}

    echo json_encode($res1);
?>

