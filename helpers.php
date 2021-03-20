<?php
//include('config.php');
include('PHPMailer/PHPMailerAutoload.php'); 

function sendemail($email,$message,$subject)
{
                $from = "support@sammyekaran.com";
                $from_name = "Danda Support";
                $pass = "&Ioo[gtai1$M";

                $to =$email;
                $subject = $subject;
                $headers  = "MIME-Version: 1.0\r\n";
                $headers .= "Content-type: text/html; charset: utf8\r\n";
                $headers .= "From: <support@dandafun.com>";
                $headers .= "Content-type: text/html; charset: utf8\r\n";
                $headers .= "From: <ip-172-31-24-140.us-west-2.compute.internal>";
                $message="<html><head></head><body>".$message."</body></html>";
                
                $mail = new PHPMailer;
                $mail->CharSet = 'UTF-8';
                $mail->isSMTP();
                $mail->Host = 'mail.dandafun.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'support@dandafun.com';
                $mail->Password = 'dandafun@123';
                $mail->SMTPSecure = 'STARTTLS';
                $mail->Port = 587;
                $mail->setFrom('support@dandafun.com', $from_name);
                $mail->addReplyTo($email);

            // Add a recipient
                $mail->addAddress($email);
                $mail->Subject = $subject;
               
                // Set email format to HTML
                $mail->isHTML(true);

                // Email body content
                $mailContent = "<html><head></head><body>".$message."</body></html>";
                $mail->Body = $mailContent;
                if(!$mail->send()) 
                {
                   
                   echo 'Mailer Error: ' . $mail->ErrorInfo;
                   return $mail->error;
                } 
                else 
                {
                    return true;
                }

         

}


function fetch_specific_fields($table, $data, $cols)
{
    global $pdo;
    $fields = array();
    $placeholders = array();
    $values = array();
    foreach ($data as $key => $value)
    {
        $fields[] = $key;
        // you can also process some special values like 'now()' here
        $placeholders[] = '?';
    }
    $fields = implode($fields, ','); // firstname, lastname
    $placeholders = implode($placeholders, ','); // ?, ?
    $sql = "select $cols from  $table where $fields = $placeholders";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute(array_values($data)) === false)
    {
        print 'Error: ' . json_encode($stmt->errorInfo()) . PHP_EOL;
    }
    else
    {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //print json_encode($row) . PHP_EOL;
        return $row;

    }

}


/*-----------------------------base64 image upload------------------------------*/
function upload_base64_image($ProfileImage,$path)
{
     if (!is_dir($path))
    {
        mkdir($path, 0775, true);
    }
     $profile_pic = str_replace('', '+',$ProfileImage);
     $data = base64_decode($profile_pic);
     $profile_pic ='img_'.time().'.jpg';
     file_put_contents($path.$profile_pic, $data);
    // print_r($profile_pic);die;
     return $profile_pic;

  /*  $profile_pic = str_replace('', '+',$ProfileImage);
    $data = base64_decode($profile_pic);
    $profile_pic ='img_'.time().'.jpg';
    file_put_contents(dirname(__FILE__).$path.$profile_pic, $data);
    return $profile_pic;*/
     
    
}

/*-----------------------------multipart image upload------------------------------*/
function upload_images($image_name,$path,$img_temp_name)
{
   // print_r($path);die;
    $tmp = explode('.', $image_name);
    $fileExtension = end($tmp);
    //print_r($fileExtension); die;
    if($fileExtension != "mp4" && $fileExtension != "avi" && $fileExtension != "mov" && $fileExtension != "3gp" && $fileExtension != "mpeg" && $fileExtension != "gif")
    {
        //image
         $target_dir = $path."images/";
          $image_name='img_'.rand().'.'.$fileExtension;

    } elseif ($fileExtension == "gif") {
        //gif images
          $target_dir = $path."gif/";
          $image_name='img_'.rand().'.'.$fileExtension;
    }
    else
    {
        //video
          $target_dir = $path."videos/";
           $image_name='vid'.rand().'.'.$fileExtension;
    }

    if(!is_dir($target_dir)) 
    {
        mkdir($target_dir, 0775,true); //create directory
    }
   /* $image_name='img_'.rand().'.'.$fileExtension;*/
    $target_file = $target_dir . basename($image_name);
   // print_r($target_dir); die;
   
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    if (move_uploaded_file($img_temp_name, $target_file)) 
    {
        //echo "yes";
        return $image_name;
    }
    else 
    {
       // echo "no";
        return 0;
    }
}

//common function for insertion
function insert($table,$data)
{
    global $pdo;
    $fields = array();
    $placeholders = array();
    $values = array();
    foreach($data as $key=>$value) {
        $fields[] = $key;
        // you can also process some special values like 'now()' here
        $placeholders[] = '?';
    }
    $fields = implode($fields, ','); // firstname, lastname

    $placeholders = implode($placeholders, ','); // ?, ?

    $sql = "INSERT INTO $table ($fields) values ($placeholders)";
    $stmt = $pdo->prepare($sql);
    //print_r($sql);
    if ($stmt->execute(array_values($data)) === false) {
        print 'Error: ' . json_encode($stmt->errorInfo()). PHP_EOL;
    }else{
        return $pdo->lastInsertId();
    }

}

//_______________________update common method with multiple where condition_________________


function update_multi_where($table, $where, $data)  //update with multiple condition
{
    global $pdo;
    $fields = array();
    $args=array();
    foreach($data as $key=>$value)
    {
       $fields[] = $key."="."'".$value."'";
    }
    foreach($where as $key1=>$value1)
    {
       $args[] = $key1."="."'".$value1."'";
    }
    $fields = implode($fields,','); // firstname=?, lastname=?
    $args = implode($args, ' and '); // firstname=?, lastname=?
    $sql = "UPDATE $table SET $fields where $args";
    $stmt = $pdo->prepare($sql);
    $arr_updt=$data;
    $stm=$stmt->execute($arr_updt);
    //print_r($stm);die;
    if ($stm === false) 
    {
        return 0;
       // print 'Error: ' . json_encode($stmt->errorInfo()). PHP_EOL;
    }
    else
    {
        return 1;
    }
}

function fetchAll($table)
{
    global $pdo;
    $sql = "Select * from $table";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    if ($stmt->execute() === false)
    {
        print 'Error: ' . json_encode($stmt->errorInfo()) . PHP_EOL;
    }
    while ($row = $stmt->fetchAll(PDO::FETCH_ASSOC))
    {
        // print_r($row); exit();
        return $row;
    }

}

function fetchAll_data($table,$data,$where)  //fetchall with multiple condition
{

    global $pdo;
    $fields = array();
    $placeholders=array();
    foreach($where as $key1=>$value1)
    {
    $fields[] = $key1."=".$value1;

    }
   /* foreach($data as $value)
    {
    $param[] = $value;

    }*/
    $fields = implode($fields, ' and '); // firstname=?, lastname=?
    //$param = implode($param, ','); // firstname=?, lastname=?
    $sql = "select $data from $table where $fields";
    $stmt = $pdo->prepare($sql);
    $res=$stmt->execute($where);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //  print_r($row);die;
   /* if ($res === false) {
    print 'Error: ' . json_encode($stmt->errorInfo()). PHP_EOL;
    }else{
    $row = $stmt->fetch(PDO::FETCH_ASSOC);*/
    return $row;
  //  }
    

}

function fetch_data($table, $where, $data)  //fetch with multiple condition
{

    global $pdo;
    $fields = array();
    $placeholders=array();
    foreach($where as $key1=>$value1)
    {
    $fields[] = $key1."=".$value1;

    }
   /* foreach($data as $value)
    {
    $param[] = $value;

    }*/
    $fields = implode($fields, ' and '); // firstname=?, lastname=?
    //$param = implode($param, ','); // firstname=?, lastname=?
    $sql = "select $data from $table where $fields";
    $stmt = $pdo->prepare($sql);
    $res=$stmt->execute($where);
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
   // print_r($row);die;
   /* if ($res === false) {
    print 'Error: ' . json_encode($stmt->errorInfo()). PHP_EOL;
    }else{
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $row;
    }
*/
}


function delete($table, $data)
{
    global $pdo;
    foreach($data as $key1=>$value1)
    {
        $fields[] = $key1."=".$value1;

    }
    $fields = implode($fields, ' and ');
    $sql = "Delete from $table  where $fields";
    $stmt = $pdo->prepare($sql);
   // $data['id'] = $id;
    //print_r($sql);die;
    if ($stmt->execute(array_values($data)) === false) 
    {
        print 'Error: ' . json_encode($stmt->errorInfo()). PHP_EOL;
    }
    else
    {
        return 1;
    }
}

function android_noti2($fields)
{
    //print_r($fields);die;
    $apiKey="AAAAwAEcaDg:APA91bEm2Nf0mv5mO97dL0wzTlN70vxIFPdzPsNuoJh6DuD77IXnInAH5pUcY0yQ5lm8PR5na7QX1HYLtqOFi99nTrHxOnpTQmFXR9i2aWdZyFh8sYdDSK_4jWfmsYCCwlcgww0EPny1";
    //Legacy server key 
    //AIzaSyCLElDtimtZM67_jl7_Rv7H6ZdlsdeuCVU

    //print_r($fields);die;   
    $url = 'https://fcm.googleapis.com/fcm/send';
    $headers = array( 
                        'Authorization: key=' . $apiKey,
                        'Content-Type: application/json'
                    );
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $res = curl_exec($ch);
    //print_r($res);die;
    if($res===FALSE)
    {
        die('Curl failed: ' . curl_erroe($ch));
    }
    
    curl_close($ch);  
    return $res;  
}
/* ===================android push notification================================*/
function test_noti1()
{
    global $pdo;
    extract($_REQUEST);
    $data=android_noti($device_token,'asd','a','','');
    print_r($data); die;

}
function android_noti($registrationID,$message_data,$type,$follower_id,$broadcast_id,$user_id,$username,$profile_pic,$broadcast_url)
{

    $apiKey="AAAAwAEcaDg:APA91bEm2Nf0mv5mO97dL0wzTlN70vxIFPdzPsNuoJh6DuD77IXnInAH5pUcY0yQ5lm8PR5na7QX1HYLtqOFi99nTrHxOnpTQmFXR9i2aWdZyFh8sYdDSK_4jWfmsYCCwlcgww0EPny1";
    //Legacy server key 
    //AIzaSyCLElDtimtZM67_jl7_Rv7H6ZdlsdeuCVU
    $fields = array(
                        'registration_ids'  => array($registrationID),
                        'data'              => array("message" => $message_data,
                                                     "noti_type" => $type,
                                                     "follower_id"=>$follower_id,
                                                     "broadcast_id"=>$broadcast_id,
                                                     "user_id"=>$user_id,
                                                     "username"=>$username,
                                                     "profile_pic"=>$profile_pic,
                                                     "broadcast_url"=>$broadcast_url
                                                  
                                                    
                                                    )
                   );
      //print_r($fields);die;   
    $url = 'https://fcm.googleapis.com/fcm/send';
    $headers = array( 
                        'Authorization: key=' . $apiKey,
                        'Content-Type: application/json'
                    );
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, true );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $res = curl_exec($ch);
    //print_r($res);die;
    if($res===FALSE)
    {
        die('Curl failed: ' . curl_erroe($ch));
    }
    return $res;
    
    curl_close($ch);    
}

/* ===================IOS push notification================================*/

/*function test_noti1()
{
    global $pdo;
    extract($_REQUEST);
  //  ios_noti($device_token,'asd','a','','','','','tyy.jpg');
   // ios_noti($recivertok_id,'hlo','','','','','','','');
    ios_notification($value,$recivertok_id,'jkjghikgdh');
   // print_r('hlo');die;
}*/

function ios_notification($value,$recivertok_id,$message)
{
   
     $deviceToken = $recivertok_id;

        // Put your private key's passphrase here:
        $passphrase = '';//sandbox
        //$passphrase = 'Appzorro@123';//production

       // Put your alert message here:
        $message = $message;

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushRU.pem'); //production
        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem'); //sandbox

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
  
        // Open a connection to the APNS server
       $fp = stream_socket_client(
            //'ssl://gateway.sandbox.push.apple.com:2195', $err,  
            'ssl://gateway.push.apple.com:2195', $err, //for production
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

       /* if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);*/

          //   echo 'Connected to APNS' . PHP_EOL;
    
       
            // Create the payload body
            $body['aps'] = $value;
           

        // Encode the payload as JSON
        $payload = json_encode($body);
      //echo"<pre>";print_r($payload);"</pre>";

        // Build the binary notification
    $token = pack('H*', "2133"); /* str_replace useless since token does not appear to have spaces? */
    $msg = chr(0).pack('n', 32).$token.pack('n', strlen($payload)).$payload;

     //   $msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack('n', strlen($payload)) . $payload;
       // $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
        /*print_r($result);
         if (!$result)
        echo 'Message not delivered' . PHP_EOL;
         else
             echo 'Message successfully delivered' . PHP_EOL;*/

        //Close the connection to the server
         
}

function ios_noti($recivertok_id,$message,$type,$follower_id,$broadcast_id,$user_id,$username,$profile_pic,$broadcast_url)
//function ios_noti($recivertok_id,$message,$type,$follower_id,$broadcast_id)
{  
  //  error_reporting(0);
        // Put your device token here (without spaces):
        $deviceToken = $recivertok_id;

        // Put your private key's passphrase here:
        $passphrase = '';//sandbox
       // $passphrase = 'Appzorro@123';//production

       // Put your alert message here:
        $message = $message;

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushRU.pem'); //production
       // stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem'); //sandbox
     
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            //'ssl://gateway.sandbox.push.apple.com:2195', $err,  
            'ssl://gateway.push.apple.com:2195', $err, //for production
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
    /*
        if($type=='archieved noti')
        {
            $body['aps'] = array(
              //  'alert' => $message,
                'sound' => 'default',
                'message' => $message, 
                'type' => $type,
                'follower_id'=>$follower_id,
                'broadcast_id'=>$broadcast_id,
                'user_id'=>$user_id,
                'username'=>$username,
                'profile_pic'=>$profile_pic,
                'broadcast_url'=>$broadcast_url
            );
           // print_r($body);
        }
        else
        {*/
            // Create the payload body
            $body['aps'] = array(
    
                'alert' => $message,
                'sound' => 'default',
                'message' => $message, 
                'type' => $type,
                'follower_id'=>$follower_id,
                'broadcast_id'=>$broadcast_id,
                'user_id'=>$user_id,
                'username'=>$username,
                'profile_pic'=>$profile_pic,
                'broadcast_url'=>$broadcast_url
            );
      /*  }    */

        // Encode the payload as JSON
        $payload = json_encode($body);
        //echo"<pre>";print_r($payload);"</pre>";

        // Build the binary notification
        //$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        $msg = chr(0) . pack('n', 32) . pack('H*', "2133") . pack('n', strlen($payload)) . $payload;

        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
      /*  print_r($result);
         if (!$result)
        echo 'Message not delivered' . PHP_EOL;
         else
             echo 'Message successfully delivered' . PHP_EOL;*/

        //Close the connection to the server
         

}

function pagination($totalCount,$pageNo,$size)
{
    global $pdo;
  
    //  $totalCount = $stmt_sel->rowCount();
    $total_pages = ceil($totalCount/$size);
    $page = $pageNo;
    if(!isset($page)) 
    {
        $pageno = 1;
    } 
    else
    {
        $pageno = $page;
    }
    $starting_limit = ($pageno-1)*$size;
    return $starting_limit;

}

function suggestion($th,$z) //with followers and following
{
    $size=10;
    global $pdo;
    extract($_REQUEST);
    $server=$_SERVER['HTTP_HOST'];
    //$th=implode(',',$th);

    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id  and r.user_id not IN(".$th.") group by user_id order by count desc";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array('user_id'=>$user_id));
    $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r($th);die;
                 
                     /**********pagination***********/
                    $totalCount = $stmt->rowCount();
                    $totalCount = $totalCount >20 ? 20 :$totalCount;
                    $total_pages = ceil($totalCount/10);
                    $starting_limit=pagination($totalCount,$pageNo,10);
                    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and user_type='0' and r.user_id not IN(".$th.") group by user_id order by count desc LIMIT $starting_limit, $size";
                    $stmt1=$pdo->prepare($sql);
                    $ar=array('user_id'=>$user_id);
                    $stmt1->execute($ar);
                            // $json1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
                            // print_r($sql);die;
                    /*****************************/
                    foreach($stmt1 as $r)
                    {
                         $userz=array($r['user_id']);
                        $z=array($z);
                       if (in_array($userz, $z))
                        {
                            $user_type='1';
                        }
                        else
                        {
                            $user_type='0';
                        }
                        //$profile_pic= get_profile_pic($r['profile_pic']);

                        $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$r['profile_pic'],'users_type'=>$user_type);
                    }
                  //  print_r($data);die;
                    return $data;
}

function suggest($z) //without followers and following
{
    $size=10;
    global $pdo;
    extract($_REQUEST);
    $server=$_SERVER['HTTP_HOST'];

    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id  group by user_id order by count desc";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array('user_id'=>$user_id));
    $json1=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $data=array_column($json1,'user_id');
    $dat=blockUser($data,$user_id);
    $th=$dat['block_userid'];
    //print_r($json1);die;

    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id NOT IN(".$th.")  group by user_id order by count desc";
            $stmt=$pdo->prepare($sql);
            $stmt->execute(array('user_id'=>$user_id));
            $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
           
             // print_r($json);die;
             /**********pagination***********/
            $totalCount = $stmt->rowCount();
            $totalCount = $totalCount >20 ? 20 :$totalCount;

           // $totalCount = 20;
            $total_pages = ceil($totalCount/$size);
            $starting_limit=pagination($totalCount,$pageNo,$size);
            $stmt1=$pdo->prepare("select r.user_id,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id NOT IN(".$th.")  group by user_id order by count desc LIMIT $starting_limit, $size");
            $ar=array('user_id'=>$user_id);
            $stmt1->execute($ar);
         
            /*****************************/
          //  $users_type=$json ? '0' :'1';
             $data=array();
            foreach($stmt1 as $r)
            {
               // var_dump($z);die;
                $userz=array($r['user_id']);
                $z=array($z);
                if (in_array($r['user_id'], $z))
                {
                    $users_type='1';
                }
                else
                {
                    $users_type='0';
                }
                //$profile_pic= get_profile_pic($r['profile_pic']);

                $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$r['profile_pic'],'users_type'=>$users_type);
            }
            $data1=array('live'=>array(),'result'=>array(),'suggestions'=>$data ?$data :array());
                   

           $json=array('message'=>'Follow people to start seeing the photos and videos they share','status'=>'1','total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data1);
         
            return $json;
           // print_r($json['data']['suggestions']);die;
}

function fetchFeedResponse($message,$isblock,$totalCount,$total_pages,$data1,$key)
{

   $json=array('message'=>$message,'status'=>'1','isblock'=>$isblock,'total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data1,'key'=>$key);  
   return $json;
}

function get_userData($user_id)
{
    global $pdo;
    global $server;
    $stmt=$pdo->prepare("select *,if((profile_pic!=''  && user_type='0'),(concat('https://".$server."/api/uploads/profile/', profile_pic)),(profile_pic)) as profile_pic from register where user_id=:user_id");
    $stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    return $res;

}


function sugg() //without followers and following
{
   //print_r($user_id); die;
    $size=10;
    global $pdo;
    extract($_REQUEST);
    $server=$_SERVER['HTTP_HOST'];
    
    $res=get_userData($user_id);

    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id group by user_id order by count desc";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array('user_id'=>$user_id));
    $json1=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $data=array_column($json1,'user_id');
    //print_r($data);die;
    //call blocked user with own userid
    $dat=blockUser($data,$user_id);
    $th=$dat['block_userid'];
    //print_r($dat);die;
     //$sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id group by user_id order by count desc ";
    $sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,(case when (count(f.user_id)>0) THEN 1 ELSE 0 END) as users_type,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id NOT IN(".$th.") group by user_id order by count desc";
    $stmt=$pdo->prepare($sql);
    $stmt->execute(array());
    //$stmt->execute(array('user_id'=>$user_id));
    $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
    
    //print_r($json); die;

    
      //print_r($sql);die;
     /**********pagination***********/
    $totalCount = $stmt->rowCount();
    $totalCount = $totalCount >20 ? 20 :$totalCount;

   // $totalCount = 20;
    $total_pages = ceil($totalCount/$size);
    $starting_limit=pagination($totalCount,$pageNo,$size);
    $stmt1=$pdo->prepare("select r.isblock,r.user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,(case when (count(f.user_id)>0) THEN 1 ELSE 0 END) as users_type,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id NOT IN(".$th.") group by user_id order by count desc LIMIT $starting_limit, $size");
    $ar=array('user_id'=>$user_id);
    $stmt1->execute($ar);


    $stmt=$pdo->prepare("select follower_id from followers where user_id=:user_id"); //followers
     $stmt->execute(array('user_id'=>$user_id));
     $results=$stmt->fetchAll(PDO::FETCH_ASSOC);


       if($results)
            {
                foreach($results as $d)
                {
                    $id[]=$d['follower_id'];   //followers
                    
                }

               
            }
        //print_r($id); die;


 
    /*****************************/
   // $dataa=array_column($json,'count');
   
   // print_r($users_type); die;
    $data=array();
     
    foreach($stmt1 as $r)
    {
        //$profile_pic= get_profile_pic($r['profile_pic']);
       
        if (in_array($r['user_id'], $id))
                        {
                            $user_type='1';
                        }
                        else
                        {
                            $user_type='0';
                        }

        $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'isblock'=>$r['isblock'],'fullname'=>$r['fullname'],'profile_pic'=>$r['profile_pic'],'users_type'=>$user_type);
    }
    
   // print_r($data);die;
    $data1=array('result'=>array(),'suggestions'=>$data ?$data :array(),'is_membership'=>$res['membership']);
           

   $json=fetchFeedResponse('Follow people to start seeing the photos and videos they share',$r['isblock'],$totalCount,$total_pages,$data1,'only suggestions');  
 
    return $json;
   // print_r($json['data']['suggestions']);die;
}

function blockUser($data,$user_id)
{
    global $pdo;
    extract($_REQUEST);
   // $th=implode(',',$data);
    /////////////////block/unblock///////////////////
            //$qry="select bu.from_userid,b.to_userid from block_users as bu left join block_users as b on b.from_userid=bu.to_userid where (bu.to_userid=:to_user_id and bu.from_userid IN(".$th.")) or (b.to_userid IN(".$th.") and b.from_userid=:user_id) ";

            $qry2="select user_id from register where user_id!=:user_id";
            $stmt=$pdo->prepare($qry2);
            $stmt->execute(array('user_id'=>$user_id));
            $output=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $data=array_column($output,'user_id');
            $th=implode(',',$data);
            //print_r($th);die;


            $qry="select from_userid from block_users where to_userid=:to_user_id and from_userid IN(".$th.") ";
            $stmt=$pdo->prepare($qry);
            $stmt->execute(array('to_user_id'=>$user_id));
            $opt=$stmt->fetchAll(PDO::FETCH_ASSOC);

            $qry1="select to_userid from block_users where to_userid IN(".$th.") and from_userid=:user_id ";
            $stmt=$pdo->prepare($qry1);
            $stmt->execute(array('user_id'=>$user_id));
            $res=$stmt->fetchAll(PDO::FETCH_ASSOC);

            $blk=array_column($opt,'from_userid');
            $blk1=array_column($res,'to_userid');
            $block = array_merge($blk, $blk1);
            $unblock = array_diff($data, $block); //in
            $blocked=implode(',',$block);  //implode array 
           // $bl=array_merge($block,$data);
           // $bl=implode(',',$bl);
            $userid=array($user_id);
            $user_mrg=array_unique(array_merge($block,$userid));
            $user_mrg=implode(',',$user_mrg); //not in
            //print_r($block);die;
            $data=array('block_users'=>$block,'unblock_users'=>$unblock,'block_userid'=>$user_mrg);

            return $data;
}
/*********************************************************/
function blockUsers($user_id)
{
    global $pdo;
    extract($_REQUEST);
  

            $qry2="select user_id from register where user_id!=:user_id ";
            $stmt=$pdo->prepare($qry2);
            $stmt->execute(array('user_id'=>$user_id));
            $output=$stmt->fetchAll(PDO::FETCH_ASSOC);
            $data=array_column($output,'user_id');
            $th=implode(',',$data);
           // print_r($th);die;


            $qry="select from_userid from block_users where to_userid=:to_user_id and from_userid IN(".$th.") ";
            $stmt=$pdo->prepare($qry);
            $stmt->execute(array('to_user_id'=>$user_id));
            $opt=$stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($qry);die;
            $qry1="select to_userid from block_users where to_userid IN(".$th.") and from_userid=:user_id ";
            $stmt=$pdo->prepare($qry1);
            $stmt->execute(array('user_id'=>$user_id));
            $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
            //print_r($qry1);die;
            $blk=array_column($opt,'from_userid');
            $blk1=array_column($res,'to_userid');
            $block = array_merge($blk, $blk1);

            return $block
            ;
}

/*function test_noti()
{
    global $pdo;
    extract($_REQUEST);
  //  ios_noti($device_token,'asd','a','','','','','tyy.jpg');
   // ios_noti($recivertok_id,'hlo','','','','','','','');
    ios('hlo',$recivertok_id);
   // print_r('hlo');die;
}*/
//function ios($data,$devicetoken)
function ios($value,$recivertok_id,$message)
{
  
        $deviceToken = $recivertok_id;
        $ctx = stream_context_create();
        $passphrase=" ";

        $message = $message;

        $ctx = stream_context_create();
        //stream_context_set_option($ctx, 'ssl', 'local_cert', 'cabmaps.pem');
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushRU.pem'); //21june 2019
       // stream_context_set_option($ctx, 'ssl', 'local_cert', '/public_html/insta/maxRuPem.pem'); //production
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // $server=new ApnsPHP_Push_Server(ApnsPHP_Abstract::ENVIRONMENT_SANDBOX, 'developMaxRu.pem');
        // print_r($ctx);die;
        // Open a connection to the APNS server
       $fp = stream_socket_client(
            //'ssl://gateway.sandbox.push.apple.com:2195', $err,  
            'ssl://gateway.push.apple.com:2195', $err, //for production
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
      // print_r($fp);die;
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = $value;
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
         $msg = chr(0) . pack('n', 32) . pack('H*', "2133") . pack('n', strlen($payload)) . $payload;
       // $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        /*$token = pack('H*',"2133");  //str_replace useless since token does not appear to have spaces? 
        $msg = chr(0).pack('n', 32).$token.pack('n', strlen($payload)).$payload;*/
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));
     //   print_r($result);
        // Close the connection to the server
        fclose($fp);
       /* if (!$result)
            //return 'Message not delivered' . PHP_EOL;
            print_r('Message not delivered');
        else
             print_r('Message successfully delivered');*/
            //return 'Message successfully delivered' . PHP_EOL;   
}

function noti_count($id)
{
    global $pdo;
    //extract($_REQUEST);
    //print_r($id);die;
    $stmt=$pdo->prepare("select count(id) as noti from notifications where user_id=:id and is_seen='0'");
    $stmt->execute(array('id'=>$id));
    $rslt=$stmt->fetch(PDO::FETCH_ASSOC);
    if($rslt)
    {
       // print_r($rslt['noti']);die;
        return $rslt['noti'];
    }
    else
    {
        //print_r('err');die;
        return '0';
    }
}
/*function tagMe($postId,$userName,$tagUsers)
{
    $noti_type="tag";
    $stmt=$pdo->prepare("select * from register where user_id in('".$tagUsers."')");
    $stmt->execute(array());
    $r=$stmt->fetchAll(PDO::FETCH_ASSOC);
    //$deive_token=array_column($res,'device_token');
    //$b=array_values($deive_token);
    echo $stmt;die;
    foreach($r as $res)
    {
        $msg='you are mentioned in '.$userName.' post ';
        if($res['device_type']=='A')
        {
            $data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $noti,"noti_type" => $noti_type,"follower_id"=>$res['user_id'],"user_id"=>$row_sel['user_id'],"username"=>$row_sel['username'], "profile_pic"=>$result['profile_pic'],'noti_count'=>$ncount));
            $noti=android_noti2($data);
             $data = array( 'registration_ids'  =>array($res['device_token']),'data' => array("message" =>$msg ,"noti_type" => $noti_type,'post_id'=>$postId));
             $noti=android_noti2($data);
        }
        else
        {   
            /*$data2=array('alert'=>$noti,'sound'=>'default','badge'=>intval($ncount),'message'=>$noti,'type'=>$noti_type,'follower_id'=>$res['user_id'],'user_id'=>$row_sel['user_id'],'username'=>$row_sel['username'],'profile_pic'=>$result['profile_pic'],'noti_count'=>$ncount);
            $data2=array('alert'=>$noti,'sound'=>'default',"noti_type" => $noti_type,'post_id'=>$postId);
            $noti2=ios_notification($data2,$res['device_token'],$msg);   
        }
    }
    
}*/
?>