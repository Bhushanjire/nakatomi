<?php
    //echo phpinfo();
   include("conn.php");


    if(!empty($postdata)){
    $request = json_decode($postdata);
    $name = $request->name??'';
    $email = $request->email??'';
    $username = $request->username??''; 
    $birth_date = date('Y-m-d', strtotime($request->birth_date));
    $profile_photo = $request->profile_photo??'';
    $gender = $request->gender??'';
    $theme_color_code = $request->theme_color_code??'';
    $password =  $request->password??''; 
    
    
    }else{
        $name = $_POST['name'] ?? '';
        $email = $_POST['email']??'' ;
        $username = $_POST['username']??''; 
        $birth_date = date('Y-m-d', strtotime($_POST['birth_date'])); 
        $password = $_POST['password']??''; 
        $profile_photo = $_POST['profile_photo']??''; 
        $gender = $_POST['gender']??''; 
        $theme_color_code = $_POST['theme_color_code']??''; 
    }
      // $conn=mysqli_connect('localhost','root','','nakatomi');

       $query_Check_Email="SELECT * FROM users WHERE email='".$email."'";
       $result_Check_Email = mysqli_query($conn,$query_Check_Email);

       if(mysqli_num_rows($result_Check_Email)>0){
        $res1 =array('success'=>false,'data'=>'','message'=>'Email-ID already exists');
        echo json_encode($res1);
        exit();
       }

       $query_Check_Username="SELECT * FROM users WHERE username='".$username."'";
       $result_Check_Username = mysqli_query($conn,$query_Check_Username);

       if(mysqli_num_rows($result_Check_Username)>0){
        $res1 =array('success'=>false,'data'=>'','message'=>'Username already exists');
        echo json_encode($res1);
        exit();
       }

       $filename ='';
if($profile_photo!=''){
       $image = base64_decode($profile_photo);
       $baseUrl = "http://ec2-13-211-168-172.ap-southeast-2.compute.amazonaws.com/nakatomi/assets/images/";
        $image_name = md5(uniqid(rand(), true));
        //$filename = $image_name . '.' . 'png';
        $filename = $image_name . '.' . 'png';
        //rename file name with random number
        $path = "assets/images/";
        
        //image uploading folder path
        file_put_contents($path . $filename, $image);
        // image is bind and upload to respective folde
        $filename = $baseUrl.$filename;

}

     $query = "INSERT INTO  users(name,email,username,birth_date,password,profile_photo,gender,theme_color_code) 
    VALUES('".$name."','".$email."','".$username."','".$birth_date."','".md5($password)."','".$filename."','".$gender."','".$theme_color_code."')";
    $res =mysqli_query($conn,$query);
    $user_id = mysqli_insert_id($conn);
    if($user_id>0){
    $responce = array();
    $res1=array();
    $query1="SELECT * FROM users WHERE user_id='".$user_id."'";
    $result1 = mysqli_query($conn,$query1);
    $data1 = mysqli_fetch_assoc($result1);
    $data1['birth_date']=date('d M Y', strtotime($data1['birth_date']));
    $res1 =array('success'=>true,'data'=>$data1,'message'=>'Signup Successfully');
   // $res1 =['success'=>true,'data'=>$data1,'message'=>'Signup Successfully'];
}else{
   // $res1 =['success'=>false,'data'=>'','message'=>'Error'];
    $res1 =array('success'=>false,'data'=>'','message'=>'Error');
}
    echo json_encode($res1);
?>

