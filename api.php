<?php

//ini_set('display_errors', 'On'); error_reporting(E_ALL);
ini_set('post_max_size', '800M');
ini_set('upload_max_filesize', '2000M'); 
//ini_set('max_execution_time', '300');
//ini_set('MAX_FILE_SIZE', '300');

include('config.php');

//include('PHPMailer/PHPMailerAutoload.php');
include('helpers.php');
include($_SERVER['DOCUMENT_ROOT']."/Danda-Backend/vendor/autoload.php");
error_reporting(0);
$server=$_SERVER['HTTP_HOST'];
$action=$_REQUEST['action'];
date_default_timezone_set("Asia/Calcutta");
$timestamp =date('d-M-y H:i');
//require_once 'stripe-php/init.php'; 
switch($action)
{
	case 'register':
		  register();
		  break;

    case 'login':
		  login();
		  break;

    case 'logout':
		  logout();
		  break;
		  
	case 'check_contact':
		  check_contact();
		  break;

	case 'forgetPasswordByContact':
		  forgetPasswordByContact();
		  break;	  	  

	case 'test_noti':   //25th api test ios noti
		  test_noti();
		  break;	  
    
    case 'get_profile':
		  get_profile();
		  break;

    case 'update_profile':
		  update_profile();
		  break;		  

	case 'fetch_profile':
		  fetch_profile();
		  break;	  

	case 'update_profile_pic':  
		  update_profile_pic();
		  break;	  

	case 'follow_request':
		  follow_request();
		  break;

	case 'confirm_request':
		  confirm_request();
		  break;

	case 'follow_list':
		  follow_list();
		  break;
    
    case 'post_upload':
		  post_upload();
		  break;

	case 'del_post':
		  del_post();
		  break;	  

    case 'exploreData':
		  exploreData();
		  break;

    case 'post_comments':
		  post_comments();
		  break;

	case 'post_likes':
		  post_likes();
		  break;

	case 'post_unlike':
		  post_unlike();
		  break;

	case 'get_comments':
		  get_comments();
		  break;

	case 'get_likes':
		  get_likes();
		  break;

    case 'fetch_feed':
		  fetch_feed();
		  break;

	 case 'trending':
		  trending();
		  break;

	case 'count_views':
		  count_views();
		  break;	  	  
  

    case 'get_reportReasons':
		  get_reportReasons();
		  break;

    case 'search_user':
		  search_user();
		  break;

	case 'detail_page':
		  detail_page();
		  break;

	case 'watermark_video':
		  watermark_video();
		  break;
    
    case 'delete_watermark_vdo':
		  delete_watermark_vdo();
		  break;

	case 'viewAllTrendings':
		  viewAllTrendings();
		  break;

    case 'sharePost':
		  sharePost();
		  break;	
    case 'get_noti':
		  get_noti();
		  break;

    case 'block_list':
		  block_list();
		  break;

    case 'genrateTicket':
		  genrateTicket();
		  break;

	case 'fetchTicket':
		  fetchTicket();
		  break;		  		  
	case 'block_user':
		  block_user();
		  break;

   case 'UserChat':
		  UserChat();
		  break;

	case 'fetchUserChat':
		  fetchUserChat();
		  break;

   case 'test_mail':
		  test_mail();
		  break;

	case 'add_reportReasons':
		  add_reportReasons();
		  break;	
  
  case 'changePass':
  		changePass();
  		break;

	default:
	echo "Not Found!";      

}
//https://sammyekaran.com/api/api.php?action=register
function register()
{
      global $pdo;
    extract($_REQUEST);
   
	$query_sel="select * from register where email=:email";
	$stmt_sel=$pdo->prepare($query_sel);
    $array_sel=array(':email'=>$email);
    $stmt_sel->execute($array_sel);
    $row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
    // print_r($query_sel);die;
    if($row_sel)
    {
    	$status='0';
		$message='Email already exist';
		$json=new stdClass();
    }
    else
    {

	
	    	$countryIso=$countryIso?$countryIso:'';
	    	$username =  strstr($email, '@',true);
	  		$data2=array('username'=>$username,'fullname'=>$fullname,'email'=>$email,'password'=>$password,'country_code'=>$country_code,'contact'=>$contact,'created_at'=> date('Y-m-d H:i:s'),'device_token'=>$device_token,'fcm_token'=>$fcm_token,'device_type'=>$device_type,'deviceId'=>$deviceId,'countryIso'=>$countryIso);
	  		
	  		$stmt_insert=insert('register',$data2);

      	 	if($stmt_insert)
       		{
		 	 	$json=array('user_id'=>$pdo->lastInsertId(),'username'=>$username);
		 	 	$status='1';
		  		$message='Register successfully';
	   		}
		   	else
		   	{
			  $json=new stdClass();
			  $status='0';
			  $message='failure';
			   
		   	}   
    	
    }

	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}

function login()
{
	global $pdo;
	global $server;
	extract($_REQUEST);

  
	//if social login
    if($type=='socialLogin')
    {
    	$stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where email=:email");
    	$stmt->execute(array(':email'=>$email));
    	$row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    	if ($row_sel) {
            $where=array('email'=>$email);
			$data1=array('device_token'=>$device_token,'fcm_token'=>$fcm_token,'device_type'=>$device_type,'deviceId'=>$deviceId);
			$stmt_updt=update_multi_where('register', $where, $data1); 


    		$status='1';
			$message='Login Successfully';
			$json= array('user_id'=>$row_sel['user_id'],'fullname'=>$row_sel['fullname'],'username'=>$row_sel['username'],'profile_pic'=>$row_sel['profile_pic'],'email'=>$row_sel['email'] );	
    	}else{
    		$status='0';
	   		$message='Email not found !';
	        $json= new stdClass();
    	}
    }else{

	$input= is_numeric ($_REQUEST['email']);
	if($input)
	{
		//check with mobile
	    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where contact=:contact");
	    $stmt->execute(array(':contact'=>$email));
	    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	   
	}
	else
	{
		//check with email
	    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where email=:email");
	    $stmt->execute(array(':email'=>$email));
	    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	   

	}
	//print_r($row_sel);die;
    if(empty($row_sel))
    {
	   $status='0';
	   $message='Email/Contact not found !';
	   $data= new stdClass();
	}
	else
	{
	    if($row_sel['password']==$password)
	    {
			
			$where=array('email'=>$email);
			$data1=array('device_token'=>$device_token,'fcm_token'=>$fcm_token,'device_type'=>$device_type,'deviceId'=>$deviceId);
			$stmt_updt=update_multi_where('register', $where, $data1); 
			
			$status='1';
			$message='Login Successfully';
			$json= array('user_id'=>$row_sel['user_id'],'fullname'=>$row_sel['fullname'],'username'=>$row_sel['username'],'profile_pic'=>$row_sel['profile_pic'],'email'=>$row_sel['email'] );		
		}
		else
		{	
		    $status='0';
	        $message='Invalid Password';
	        $json= new stdClass();
		}	
	}
   }//normal login

 
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}

function logout()
{
	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
    $stmt->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	
		$stmt_upd=$pdo->prepare("update register set device_type='',device_token='',fcm_token='',deviceId='' where user_id=:user_id and deviceId=:deviceId");
		$array_upd=array(':user_id'=>$user_id,':deviceId'=>$deviceId);
		$stmt_upd->execute($array_upd);
		
		$json=array('message'=>'success','status'=>'1','data'=>[]);	
		
    }
    else
    {
    	$json=array('message'=>'Invalid User','status'=>'0','data'=>[]);	
    }
	echo "{\"response\":" . json_encode($json) . "}";	
}

function check_contact()
{
    global $pdo;
    extract($_REQUEST);
	if($contact)
	{
		//echo "contact";
		$mobile=array('contact'=>$contact);

		$row_company2=fetch_specific_fields('register',$mobile,'contact');
		if(!empty($row_company2))
		{
	      $json=array('status'=>'0','message'=>'Contact is already  exist');	
		}else{
				if($email)
			    {
			    	$data=array('email'=>$email);

			       $row_company=fetch_specific_fields('register',$data,'email');
				    if(!empty($row_company))
					{
				       $json=array('status'=>'0','message'=>'Email is already exist');	
					}else{
						$json=array('status'=>'1','message'=>'success');
					}

			    }else{
			    	$json=array('status'=>'1','message'=>'success');
			    }
	   }


	}else{
		//chk email
		
		$data=array('email'=>$email);

       $row_company=fetch_specific_fields('register',$data,'email');
       //print_r($row_company); die;
	    if(!empty($row_company))
		{
	       $json=array('status'=>'0','message'=>'Email is already exist');		
		}else{
			$json=array('status'=>'1','message'=>'success');
		}

	}
	echo "{\"response\":" . json_encode($json) . "}";	

}
/*function check_contact()
{

	global $pdo;
	extract($_REQUEST);

	$query_sel="SELECT * from register where RIGHT(contact,8) = RIGHT(:contact, 8) ";
    $stmt_sel=$pdo->prepare($query_sel);
    $array_sel=array(':contact'=>$contact);
    $stmt_sel->execute($array_sel);
    $row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
    if($row_sel)
    {	
		$query_sel="SELECT * from register where email=:email ";
		$stmt_sel=$pdo->prepare($query_sel);
		$array_sel=array(':email'=>$email);
		$stmt_sel->execute($array_sel);
		$row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
		if($row_sel)
		{
			$json=array('status'=>'0','message'=>'Email and Contact both are exist');
		}
		
		$json=array('status'=>'0','message'=>'Contact is already  exist');
		
	}
	else
	{
		$query_sel="SELECT * from register where email=:email ";
		$stmt_sel=$pdo->prepare($query_sel);
		$array_sel=array(':email'=>$email);
		$stmt_sel->execute($array_sel);
		$row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
		if($row_sel)
		{
			$json=array('status'=>'0','message'=>'Email is already exist');
		}
		else
		{
			$json=array('status'=>'1','message'=>'success');
		}
	}
	echo "{\"response\":" . json_encode($json) . "}";	

}*/

function forgetPasswordByContact()
{
	global $pdo;
	extract($_REQUEST);
	
    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) from register where contact=:contact");
    $stmt->execute(array(':contact'=>$contact));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row_sel)
	{
		
			$where=array('contact'=>$contact);
			$data1=array('password'=>$password);
			$stmt_updt=update_multi_where('register', $where, $data1);
			if($stmt_updt)
			{
				$json=array('status'=>'1','message'=>'Password reset successfully','data'=>array('email'=>$row_sel['email'],'password'=>$row_sel['password']));
			}
			else
			{
				$json=array('status'=>'0','message'=>'something went wrong','data'=>array());
			}
	}
	else
	{	
	    $json=array('status'=>'0','message'=>'invalid email/contact','data'=>array());	
	}
	 echo "{\"response\":" . json_encode($json) . "}";

}
//hye
function get_profile_pic($avatar)
{
	    $server=$_SERVER['HTTP_HOST'];
	   if($avatar)
	   {
		   	if (strpos($avatar, "https")!==false){
			    //echo "Car here";
			    $profile_pic=$avatar;
			}else{
				$profile_pic="https://$server/api/uploads/profile/".$avatar;

			}
	   
	   }else{
	   	 $profile_pic="";
	   }
	  
	 /*  $url = $avatar;
		if (strpos($url, "https")!==false){
		    //echo "Car here";
		    $profile_pic=$avatar;
		}
		else {*/
		   //echo "No car here :(";
		   
		//}

		return $profile_pic;

}
function get_profile()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	/*$stmt=$pdo->prepare("select if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) as profile_pic,r.website,r.fcm_token,r.bio,r.username,r.fullname,count(f.id) as followers from register as r left join followers as f on f.user_id=r.user_id where r.user_id=:user_id");*/

	$stmt=$pdo->prepare("select if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic,r.website,r.fcm_token,r.bio,r.username,r.fullname,count(f.id) as followers from register as r left join followers as f on f.user_id=r.user_id where r.user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
      //print_r($server);die;
    $stmt=$pdo->prepare("select * from followSubscription where  fromUserId=:fromUserId"); 
    $stmt->execute(array(':fromUserId'=>$from_userid));
    $rspns1=$stmt->fetch(PDO::FETCH_ASSOC);
    //getting connected account detail
    $stmt=$pdo->prepare("select connectStripeId from register where  user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
	$stripeKey=$stmt->fetch(PDO::FETCH_ASSOC);
  
	$connected_with_stripe=$stripeKey ? ($stripeKey['connectStripeId']?1:0):0;
	//print_r($connected_with_stripe);die;
    if($rspns1)
    {


    	if($rspns1['subscriptionType']=='1')
    	{
    		$isFollowSubscriptionPurchased=(strtotime($rspns1['endDate']) > strtotime(date('Y-m-d')))?'1':'0';
    	}
    	else
    	{
    		$stmt=$pdo->prepare("select * from followSubscription where toUserId=:toUserId and fromUserId=:fromUserId"); 
		    $stmt->execute(array(':toUserId'=>$user_id,':fromUserId'=>$from_userid));
		    $rspns=$stmt->fetch(PDO::FETCH_ASSOC);
		    if($rspns)
		    {
		    	$isFollowSubscriptionPurchased=(strtotime($rspns['endDate']) > strtotime(date('Y-m-d')))?'1':'0';
		    }
		    else
		    {
		    	$isFollowSubscriptionPurchased='0';
		    }
    	}
    }
    else
    {
    	$isFollowSubscriptionPurchased='0';
    }
	

    if($res)
    {	


    	$profile_pic=get_profile_pic($res['profile_pic']);
		
    	///////////////////////type(follower or not)/////////////////////
    	$stmt=$pdo->prepare("select * from followers where follower_id=:from_userid and user_id=:user_id ");
		$stmt->execute(array('from_userid'=>$from_userid,'user_id'=>$user_id));
	    $output=$stmt->fetch(PDO::FETCH_ASSOC);
	    //print_r($output);die;
	    $users_types=(!empty($output)) ? '1' : '0';   //0=follow,1=following

	    ////////////////////////block unblock////////////////////////////
	    $qry="select * from block_users where to_userid=:user_id  and from_userid=:from_userid";
		$stmt=$pdo->prepare($qry);
		$stmt->execute(array('user_id'=>$user_id,'from_userid'=>$from_userid));
	    $opt=$stmt->fetch(PDO::FETCH_ASSOC);
		$is_block=(!empty($opt)) ? '1' : '0';   //0=unblock,1=block	 
		///////////////////////////////from username////////////////////////////////////////////////

		$stmt=$pdo->prepare("select fullname from register where user_id=:from_userid ");
		$stmt->execute(array('from_userid'=>$from_userid));
	    $from_username=$stmt->fetch(PDO::FETCH_ASSOC);
	    //print_r($from_username['fullname']);die;
	 
		//////////////////////////count follower/followings///////////////////////////
		/*$stmt=$pdo->prepare("SELECT  fs.follower_id as followers,fg.user_id as followings from followers as fs join followers as fg on fs.user_id=fg.follower_id where fs.user_id=:user_id  and fg.follower_id=:follower_id");
		$stmt->execute(array('user_id'=>$user_id,'follower_id'=>$user_id));
	    $output=$stmt->fetchAll(PDO::FETCH_ASSOC);	
	    $fs=array_unique(array_column($output,'followers'));
	    $fg=array_unique(array_column($output,'followings'));
	    
	    $fg=blockUser($fg,$user_id);
	    $fs=blockUser($fs,$user_id);
	    $followings=count($fg['unblock_users']);
	    $followers=count($fs['unblock_users']);*/
	    $q="SELECT COUNT(CASE WHEN user_id=:user_id THEN 1 END) AS followers, COUNT(CASE WHEN follower_id=:follower_id THEN 1 END) AS followings FROM `followers`";
	    $stmt=$pdo->prepare($q);
		$stmt->execute(array('user_id'=>$user_id,'follower_id'=>$user_id));
	    $output=$stmt->fetch(PDO::FETCH_ASSOC);
	    //print_r($output);die;
	    ///////////////////////////////////////////////////////////////////////////////////

    	$stmt=$pdo->prepare("select count(id) as followings from followers where follower_id=:user_id ");
		$stmt->execute(array('user_id'=>$user_id));
	    $row=$stmt->fetch(PDO::FETCH_ASSOC);
	  
    	$stmt=$pdo->prepare("select id,upload_type,if((explicit=2),('1'),('0')) as explicit,CASE upload_type
		      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', uploads)
		      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', uploads)
		      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', uploads)
		  END as post_url,if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url  from uploads where user_id=:user_id and uploads!='0'  order by id desc ");



		$stmt->execute(array('user_id'=>$user_id));
	    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    //print_r($stmt);die;

	    $stmt=$pdo->prepare("select count(id) as posts_count from uploads where user_id=:user_id and uploads!='0' ");
		$stmt->execute(array('user_id'=>$user_id));
	    $reslt=$stmt->fetch(PDO::FETCH_ASSOC);
	   	$posts=$result?$result:array();

	   //	$copy_url="https://rawuncensored.com/profile_copy/".$user_id."";
	   //	print_r($copy_url);die;
	    $json=array('username'=>$res['username'],
	    			'user_id'=>$user_id,
	    			'name'=>$res['fullname'],
	    			'profile_pic'=>$res['profile_pic'],
	    			//'username'=>$res['username'],
	    			'website'=>$res['website'],
	    			//'copy_url'=>($from_userid!='')? $copy_url: '',
	    			'bio'=>$res['bio'],
	    			'fcm_token'=>$res['fcm_token'],
	    			'posts_count'=>($is_block=='1') ? '0' :$reslt['posts_count'],
	    			'followers_count'=>($is_block=='1') ? '0' :$output['followers'],
	    			'following_count'=>($is_block=='1') ? '0' :$output['followings'],
	    			'users_types'=>!(empty($from_userid)) ? $users_types : '',
	    			'is_block'=>!(empty($from_userid)) ? $is_block : '',
	    			'from_fullname'=>!empty($from_userid) ? $from_username['fullname'] : '',
	    			'posts'=>($is_block=='1') ? array() :$posts,
	    			'isFollowSubscriptionPurchased'=>$isFollowSubscriptionPurchased,
	    			'connected_with_stripe'=>$connected_with_stripe
	    );
	    $status='1';
	    $message='success';

    }
    else
    {
		$json=new stdClass();
		$status='0';
		$message='user not found';
    }
    $json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}


function follow_request()
{
    global $pdo;
    extract($_REQUEST);
    
    
    $stmt=$pdo->prepare("select * from register where user_id=:follower_id");
    $stmt->execute(array('follower_id'=>$follower_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    // print_r($row_sel);die;
    if($row_sel)
    {
    	$stmt=$pdo->prepare("select * from followers where user_id=:user_id and follower_id=:follower_id");
   		$stmt->execute(array('user_id'=>$user_id,'follower_id'=>$follower_id));
        $ress=$stmt->fetchAll(PDO::FETCH_ASSOC);
        //print_r($res);die;

        $stmt=$pdo->prepare("select * from register where user_id=:user_id ");
   		$stmt->execute(array('user_id'=>$user_id));
        $res=$stmt->fetch(PDO::FETCH_ASSOC);

        if($follower=='1')
        {
	        if(empty($ress))
	    	{
	    		$data=array('user_id'=>$user_id,'follower_id'=>$follower_id);
	    		$stmt_insert=insert('followers',$data);
	    		if($stmt_insert)
				{
					$noti=$row_sel['fullname']." "."started following you";
					$noti1="started following you";
					$data=array('user_id'=>$user_id,'follower_id'=>$follower_id,'notification'=>$noti1);
	    			$data_insert=insert('notifications',$data);

	    			/*$stmt=$pdo->prepare("select * from register where user_id=:user_id ");
			   		$stmt->execute(array('user_id'=>$user_id));
			        $res=$stmt->fetch(PDO::FETCH_ASSOC);*/
			       // print_r($res);die;
				    $noti_type="follow_request";
					
					
					$ncount=noti_count($user_id);
					if($res['device_type']=='A')
					{
						//print_r('a');
						//$notii=android_noti($res['device_token'],$noti,$noti_type,$follower_id=$res['user_id'],'',$row_sel['user_id'],$row_sel['username'],$row_sel['profile_pic'],'');
					
						$data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $noti,"noti_type" => $noti_type,"follower_id"=>$res['user_id'],"user_id"=>$row_sel['user_id'],"username"=>$row_sel['username'], "profile_pic"=>$row_sel['profile_pic'],'noti_count'=>$ncount));
						$noti=android_noti2($data);
						//print_r($noti);die;
						
					}
					else
					{ 					
						//print_r('i');
						$data2=array('alert'=>$noti,'sound'=>'default','badge'=>intval($ncount),'message'=>$noti,'type'=>$noti_type,'follower_id'=>$res['user_id'],'user_id'=>$row_sel['user_id'],'username'=>$row_sel['username'],'profile_pic'=>$row_sel['profile_pic'],'noti_count'=>$ncount);

						$noti2=ios_notification($data2,$res['device_token'],$message);
						//$notii=ios_noti($res['device_token'],$noti,$noti_type,$follower_id,'',$row_sel['user_id'],$row_sel['username'],$row_sel['profile_pic'],'');	
						//print_r($noti);die;	 
					}


	    			//print_r($data_insert);die;
				 	$json=array('id'=>$pdo->lastInsertId(),'message'=>'send follow request successfully');
				 	$status='1';
				  	$message='success';
			   	}
				else
				{
					$json=new stdClass();
					$status='0';
					$message='failure';	   
				} 
	    	}
	    	else
	    	{
	    		$json=new stdClass();
				$status='0';
				$message='Already follow';
	    	}
	    }
	    else if($follower=='2')
		{
			if($ress)
			{
				$data=array('follower_id'=>$follower_id,'user_id'=>$user_id);
				$del=delete('followers', $data);
				if($del)
				{
					$data=array('follower_id'=>$follower_id,'user_id'=>$user_id);
					$del1=delete('notifications', $data);

				}
				
				$json=new stdClass();
			 	$status='1';
			  	$message='success';
			}
			else
			{
				$json=new stdClass();
				$status='0';
				$message='not exist in follower list';
			}
		}
		else
		{
			$json=new stdClass();
			$status='0';
			$message='invalid input';
		}
    	
	}
	else
	{
		$json=new stdClass();
		$status='0';
		$message='invalid user ';
	}
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
	exit();

}

function confirm_request()
{
	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from followers where user_id=:user_id and follower_id=:follower_id and follow_status='1'");
	$stmt->execute(array('user_id'=>$user_id,'follower_id'=>$follower_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	$data=array('follow_status'=>$follow_status);
		$where=array('user_id'=>$user_id,'follower_id'=>$follower_id);
		$stmt_updt=update_multi_where('followers', $where, $data); 
		//print_r($stmt_updt);die;
		if($stmt_updt=='1')
		{	
			$stmt=$pdo->prepare("select * from register where user_id=:follower_id ");
			$stmt->execute(array('follower_id'=>$follower_id));
		     $row=$stmt->fetch(PDO::FETCH_ASSOC);

		     $stmt=$pdo->prepare("select * from register where user_id=:user_id");
			$stmt->execute(array('user_id'=>$user_id));
		     $res=$stmt->fetch(PDO::FETCH_ASSOC);
		   // print_r($res);die;

		    $message=$res['fullname']." "."accepted your follow request";
		    $noti_type="confirm request";
			if($row['device_type']=='A')
			{
				android_noti($row['device_token'],$message,$noti_type,$user_id=$res['user_id'],'','','','','');
		
			}
			else
			{ 					
				ios_noti($row['device_token'],$message,$noti_type,$user_id=$res['user_id'],'','','','','');
					 
			}
		}
		$json=array('user_id'=>$user_id,'message'=>'Confirm follow request');
		$status='1';
		$message='success';
    }
    else
    {
    	$json=new stdClass();
		$status='0';
		$message='no follow request found';
    }
    $json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}



function follow_list()
{
	global $pdo;
	extract($_REQUEST);
	global $server;
	$size=10;
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    if($res)
    {
			//GET COUNT OF FOLLOWERS AND FOLLOWINGS
		$stmt=$pdo->prepare("SELECT COUNT(CASE WHEN user_id=:user_id THEN 1 END) AS followers, COUNT(CASE WHEN follower_id=:follower_id THEN 1 END) AS followings FROM `followers`");
		$stmt->execute(array('user_id'=>$user_id,'follower_id'=>$user_id));
	    $output=$stmt->fetch(PDO::FETCH_ASSOC);	
	   // print_r($output);die;
	 
	    if($user_type==1) //followers
	    {
			$stmt=$pdo->prepare("select follower_id from followers where user_id=:user_id ");
			$stmt->execute(array('user_id'=>$user_id));
		    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    $follower_count=count($result);
		    $fol=array_column($result,'follower_id');
		    	
		}
		elseif($user_type==2) //following
		{
			$stmt=$pdo->prepare("select user_id from followers where follower_id=:user_id");
			$stmt->execute(array('user_id'=>$user_id));
		    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    $following_count=count($result);
		}
		else
		{
			$json=new stdClass();
			$status='0';
			$message='undefined user Type';
		}
	    if($result)
	    {
	    	foreach($result as $rslt)
	    	{
	    		
	    		$d[]=implode(",",$rslt); //following
	    	}
	    	//print_r($d);die;
	    	//get followers or followings information
	    	if($search!='')
	    	{
	    		$sql="select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') and (fullname like '%".$search."%' or username like '%".$search."%' )";
		    	$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
		     	$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		     	//print_r($row);die;
		     	/*------pagination---------*/
		     	$totalCount = $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') and (fullname like '%".$search."%' or username like '%".$search."%' ) LIMIT $starting_limit,$size");
				$ar=array();
				$stmt1->execute($ar);
				/*--------------------------*/
	    	}
	    	else
	    	{
	    		$sql="select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') ";
		    	$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
		     	$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		     	//print_r($sql);die;
		     	/*------pagination---------*/
		     	$totalCount = $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') LIMIT $starting_limit,$size");
				$ar=array();
				$stmt1->execute($ar);
				/*------pagination---------*/
	    	}
		
		    if($row)
		    {
				foreach ($stmt1 as  $r)
			    {
			    	//print_r($r);die;
		    		/////////////////my profile followers following//////////////////////
		    		if($id=='')
		    		{
			    		if($user_type==2)  
				    	{
				    		$users_types='1';  //following
				    	}
				    	else 
				    	{	

				    		$sql="select * from followers where (follower_id =:id and user_id=:user_id) or (user_id=:us_id and follower_id=:f_id) ";
							$stmt=$pdo->prepare($sql);
							$arr=array('user_id'=>$user_id,'id'=>$r['user_id'],'us_id'=>$r['user_id'],'f_id'=>$user_id);
							$stmt->execute($arr);
						    $resultt=$stmt->fetchAll(PDO::FETCH_ASSOC);
						
				    		if(count($resultt)==2)
				    		{
				    			$users_types='2';  //both side following
				    		}
				    		else
				    		{
				    			$users_types='0';  //follow
				    		}
				    	}
				    }
				    //////////////////others profile followers/following////////////////////////
				    else
				    {
			    		$sql="select * from followers where (follower_id =:id and user_id=:user_id) or (user_id=:us_id and follower_id=:f_id) ";
						$stmt=$pdo->prepare($sql);
						$arr=array('user_id'=>$id,'id'=>$r['user_id'],'us_id'=>$r['user_id'],'f_id'=>$id);
						$stmt->execute($arr);
					    $resultt=$stmt->fetchAll(PDO::FETCH_ASSOC);
					    if(count($resultt)==2)
			    		{
			    			$users_types='2';    //both side following
			    		}
			    		else
			    		{
			    			$sql="select * from followers where (follower_id =:id and user_id=:user_id) ";
							$stmt=$pdo->prepare($sql);
							$arr=array('id'=>$id,'user_id'=>$r['user_id']);
							$stmt->execute($arr);
							$op=$stmt->fetchAll(PDO::FETCH_ASSOC);
			    			if($op)
			    			{
			    				$users_types='1';  //following
			    			}
			    			elseif($id==$r['user_id'])
			    			{
			    				$users_types='3'; //own id
			    			}
			    			else
			    			{
			    				$users_types='0';  //follow
			    			}
			    			
			    		}

				    }
				    $profile_pic=get_profile_pic($r['profile_pic']);
		    		$data[] =array('user_id'=>$r['user_id'],
		    					'username'=>$r['username'],
		    					'fullname'=>$r['fullname'],
		    					'profile_pic'=>$profile_pic,
		    					'users_type'=>$users_types
		    					);
		    		$json=array(
		    					'followers'=>$output['followers'],
		    					'following'=>$output['followings'],
		    					'detail'=>$data
		    					);
		    	}
				$json=array('message'=>'success','status'=>'1','total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$json ?$json :array());
		    }
		    else
		    {
				$json=array('message'=>'No Record found','status'=>'0','data'=>new stdClass());
		    }	
		}
		else
		{
			$json=array(
					'followers'=>$output['followers'],
					'following'=>$output['followings'],
					'detail'=>array()
					);
			//$json=array('message'=>'No following found','status'=>'1','data'=>$json ?$json :array());
			$json=array('message'=>'No following found','status'=>'1','total_records'=>'0','last_page'=>'1','data'=>$json ?$json :array());
		}
    }
    else
    {
		$json=array('message'=>' user-id not found','status'=>'0','data'=>new stdClass());
    }

	echo "{\"response\":" . json_encode($json) . "}";
}

function post_upload()
{
  //try{
	global $pdo;
	extract($_REQUEST);
	$time=date("H:i:s");
	$date=date('y-m-d');
	$data=$_REQUEST['content'];
	$content = json_decode($data, true);
	//$tagUsers=explode(',',$tagUsers);
 
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
   //print_r($res);die;
    if(empty($res))
    {
      

          //$json=new stdClass();
          $status='0';
          $message='user not found';
     }

    if($_REQUEST['thumbnail'])
    {
      //echo "string";
      $thumbnail = $_REQUEST['thumbnail'];
      $pp = str_replace('', '+',$thumbnail);
      $path=dirname(__FILE__)."/uploads/thumbnails/";
      $thumbnail_img = upload_base64_image($thumbnail,$path);

    }else{
      //echo "fghfhfg";
      $status='0';
      $message='Please select thumbnail';
      $json=new stdClass();

    }

    	
		$image_name=$_FILES['posts']['name'];

        if($image_name == '')
          {
          //echo "1";
           $status='0';
            $message='Please select file';
            $json=new stdClass();
        }
   // echo "hlo";
      	//print_r($image_name);die;
		$img_temp_name=$_FILES['posts']['tmp_name'];
		$profile_pic=upload_images($image_name,'uploads/',$img_temp_name);

        
		
		//print_r($profile_pic);die;
		if($profile_pic == '')
		{
          
			$status='0';
			$message='invalid uploaded file'; 
		}
			$data2=array('user_id'=>$user_id,'uploads'=>$profile_pic,'caption'=>$caption,'explicit'=>'1','upload_type'=>$upload_type,'date'=>$date,'time'=>$time,'thumbnail'=>$thumbnail_img);
    //print_r($data2);die;
	  		$stmt_insert=insert('uploads',$data2);
         
	  		if($stmt_insert)
         
	  		{
	  			
		  		$id=$pdo->lastInsertId();
		  		if($tagUsers)
	  			{
	  				$username=$res['fullname'];
	  				$stmt=$pdo->prepare("select * from register where user_id in('".$tagUsers."')");
					$stmt->execute(array());
				    $r=$stmt->fetchAll(PDO::FETCH_ASSOC);
				   	
				   	foreach($r as $res)
				    {
				    	$noti_type='tags';
				        $msg='you are mentioned in '.$userName.' post ';
				        if($res['device_type']=='A')
				        {
				             $data = array( 'registration_ids'  =>array($res['device_token']),'data' => array("message" =>$msg ,"noti_type" => $noti_type,'post_id'=>$id));
				             $noti=android_noti2($data);
				        }
				        else
				        {   
				            $data2=array('alert'=>$noti,'sound'=>'default',"noti_type" => $noti_type,'post_id'=>$postId);
				            $noti2=ios_notification($data2,$res['device_token'],$msg);   
				        }
				    }
	  			}
				/*foreach($content as $co)
				{
					$data=$co['data'];
					//$url=$co['url'];
					$price=$co['price'];
					$data2=array('upload_id'=>$id,'content'=>'','content_description'=>$data,'price'=>$price);
		  			$stmt_insert=insert('upload_content',$data2);	
				}*/
				$json=array('post_id'=>$id);
				$status='1';
				$message='success';
			}
			else
			{
				$json=new stdClass();
				$status='0';
				$message='something went wrong';
			}
		

	



    $json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
	exit();
 // }
 // catch(Exception $e) {
 // echo 'Message: ' .$e->getMessage();
//}
  
}

/*********************delete post******************/
function del_post()
{
	global $pdo;
	extract($_REQUEST);
	$stmt=$pdo->prepare("select * from uploads where id=:post_id");
	$stmt->execute(array('post_id'=>$post_id));
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result)
    {
    	////////post del//////////
		//$data=array('id'=>$post_id);
		//$del=delete('uploads', $data);
		//////////comment del////////
	
		$stmt=$pdo->prepare("DELETE comments,likes,uploads,notifications FROM uploads left join comments on comments.upload_id=uploads.id left JOIN likes on likes.upload_id=uploads.id left join notifications on notifications.upload_id=uploads.id  where uploads.id=:post_id");
		$res=$stmt->execute(array('post_id'=>$post_id));
    	if($res=='1')
    	{

    		$json=array('status'=>'1','message'=>'success');
    	}
    	else
    	{
    		$json=array('status'=>'0','message'=>'server error');
    	}
	}
	else
	{
		  $json=array('status'=>'0','message'=>'Not Found!!');
	}
  echo "{\"response\":" . json_encode($json) . "}";
}

function exploreData()
{
	global $pdo;
	global $server;
	extract($_REQUEST);
	$Size=50;
  	$pageNo = isset($pageNo) ? $pageNo : 1;

	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);

    $stmt=$pdo->prepare("select subscriptionType from followSubscription where fromUserId=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
	$subscriptionT=$stmt->fetch(PDO::FETCH_ASSOC);
	$subscriptionType=(empty($subscriptionT))?'2':$subscriptionT['subscriptionType'];
	//print_r($subscriptionType);die;
	//get stripe key
	$stmt=$pdo->prepare("select connectStripeId from register where  user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
	$stripeKey=$stmt->fetch(PDO::FETCH_ASSOC);

	$connected_with_stripe= $stripeKey ? ($stripeKey['connectStripeId']?1:0 ) : 0;

    if($res)
    {
		$stmt=$pdo->prepare("SELECT u.*,if((u.upload_type='I'),(concat('https://".$server."/Danda-Backend/uploads/images/', uploads)),(concat('https://".$server."/Danda-Backend/uploads/videos/', 		uploads))) AS post_url,if((u.thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url,count(l.id)as likess FROM `uploads` as u join likes as l on l.upload_id=u.id group by u.id order by likess desc");
		$stmt->execute(array());
		$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($pageNo);die;
		if($result)
		{

				//pagination
			$totalCount = $stmt->rowCount();
			$total_pages = ceil($totalCount/$Size);
			$page = $pageNo;
			if(!isset($page)) 
			{
				$pageno = 1;
			} 
			else
			{
				$pageno = $page;
			}
			$starting_limit = ($pageno-1)*$Size;
			$show="SELECT u.*,if((u.upload_type='I'),(concat('https://".$server."/Danda-Backend/uploads/images/', uploads)),(concat('https://".$server."/Danda-Backend/uploads/videos/', uploads))) AS 				post_url,if((u.thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url,count(l.id)as likess FROM `uploads` as u join likes as l on 				l.upload_id=u.id group by u.id order by likess desc LIMIT $starting_limit, $Size";
			$result=$pdo->prepare($show);
			$result->execute(array());
          	$data = [];
			foreach($result as $r)
			{
				////membership purchased for one person
				$stmt=$pdo->prepare("select endDate from followSubscription where toUserId=:toUserId and fromUserId=:fromUserId and subscriptionType='0' ");
				$stmt->execute(array('fromUserId'=>$user_id,'toUserId'=>$r['user_id']));
		    	$rspnss=$stmt->fetch(PDO::FETCH_ASSOC);
		    	$isFollowSubscriptionPurchased=$rspnss ? ((strtotime($rspnss['endDate']) > strtotime(date('Y-m-d')))?'1':'0') :'0';

				$data[]=array(
								'user_id'=>$r['user_id'],
								'upload_id'=>$r['id'],
								'upload'=>$r['post_url'],
								'thumbnail'=>$r['thumbnail_url'],
								'upload_type'=>$r['upload_type'],
								'is_explicit'=>($r['explicit']==2) ? '1' : '0' ,
								'isFollowSubscriptionPurchased'=>$isFollowSubscriptionPurchased,
								);
             
			}
												$json=array('message'=>'success','status'=>'1','current_page'=>$pageNo,'page_size'=>$Size,'total_records'=>"$totalCount",'last_page'=>"$total_pages",'is_membership'=>$res['membership'],'subscriptionType'=>$subscriptionType,'data'=>$data);
		}
		else
		{
			$json=array('message'=>'data not found','status'=>'0','current_page'=>$pageNo,'page_size'=>$Size,'total_records'=>"$totalCount",'last_page'=>"$total_pages",'data'=>array());
		}
	}
	else
	{
			$json=array('message'=>'Invalid user','status'=>'0','current_page'=>$pageNo,'page_size'=>$Size,'total_records'=>"$totalCount",'last_page'=>"$total_pages",'data'=>array());

	}
	echo "{\"response\":" . json_encode($json) . "}";
	exit();
}
function get_noti()
{
	global $pdo;
	global $server;
	extract($_REQUEST);
	
	$size=15;
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if($result)
    {
    	$stmt=$pdo->prepare("select if((n.upload_id='0'),(''),(u.caption))as caption,if((n.upload_id='0'),(n.follower_id),(''))as users,n.notification,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  END as 'post_url',upload_id,r.username from notifications as n left join register as r on r.user_id=n.follower_id left join uploads as u on u.id=n.upload_id where n.user_id=:user_id order by n.id desc");
		$stmt->execute(array('user_id'=>$user_id));
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	     //print_r($res);die;
	    if($res)
	    {
	    	/**********pagination***********/
			$totalCount = $stmt->rowCount();
			$total_pages = ceil($totalCount/$size);
			$starting_limit=pagination($totalCount,$pageNo,$size);
			$stmt1=$pdo->prepare("select if((n.upload_id='0'),(''),(u.caption))as caption,if((n.upload_id='0'),(n.follower_id),(n.follower_id))as users,n.notification,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  END as 'post_url',upload_id,r.username from notifications as n left join register as r on r.user_id=n.follower_id left join uploads as u on u.id=n.upload_id where n.user_id=:user_id order by n.id desc LIMIT $starting_limit, $size");
			$ar=array('user_id'=>$user_id);
			$stmt1->execute($ar);
			/**********************************/
	    	foreach($stmt1 as $r)
	    	{
	    		$a=strip_tags($r['notification']);
	    		//$a=str_replace("<b></b>", '', $r['notification']);;
	    		//print_r($a);die;
	    		//////////////update notification status(seen)/////////
    			$where=array('user_id'=>$user_id);
				$data=array('is_seen'=>'1');
				$stmt_updt=update_multi_where('notifications', $where, $data); 
				////////////////////////////////////////////////////////

	    		$json[]=array('post_id'=>$r['upload_id'],'notification'=>$a,'profile_pic'=>$r['profile_pic'],'caption'=>$r['caption'] ? $r['caption'] : '','user_id'=>$r['users'],'username'=>$r['username'] ?$r['username']:'');
	    	}
	    	
	    	$json=array('message'=>'success','status'=>'1','total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$json ?$json :array());
	    }
	    else
	    {
	    	$json=array('message'=>'no notifications','status'=>'0','data'=>array());
	    }
    }
    else
	{
		$json=array('message'=>'Invalid user','status'=>'0','data'=>new stdClass());
	}
	echo "{\"response\":" . json_encode($json) . "}";
	exit();
}

function search_user()
{
	global $pdo;
	global $server;
	extract($_REQUEST);
	
	$size=15;
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    if($res)
	{
    	if($search=='')
		{
			$stmt=$pdo->prepare("select follower_id from followers where user_id=:user_id"); //followers
			$stmt->execute(array('user_id'=>$user_id));
		    $results=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    //print_r($results);die;
		    $stmt=$pdo->prepare("select user_id from followers where follower_id=:user_id"); //following
			$stmt->execute(array('user_id'=>$user_id));
		    $rsults=$stmt->fetchAll(PDO::FETCH_ASSOC);
		  	if($results)
		  	{
		    	foreach($results as $d)
		    	{
		    		$id[]=$d['follower_id'];   //followers
		    		
		    	}

		    	$fo=implode(',',$id);
		    }
		    if($rsults)
		    {
		    	foreach($rsults as $de)
		    	{
		    		$ids[]=$de['user_id'];	//following
		    	
		    	}
		    	$fol=implode(',',$ids);
		    	$ar_mrg = array_merge($results, $rsults);
              
                if($ar_mrg)
                {
                    foreach($ar_mrg as $a)
                    {
                        $thh[]=implode('',$a);
                    }
                    $th=implode(',',$thh);
                }
		    }

	    	
	   		//print_r($th);die;
   		}
   		else
   		{
	   		$stmt=$pdo->prepare("select user_id from register where user_id!=:user_id"); //following
			$stmt->execute(array('user_id'=>$user_id));
		    $rspns=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    foreach($rspns as $a)
   		 	{
   		 		$thh[]=implode('',$a);
   		 	}
   		 	$th=implode(',',$thh);
   		 	//print_r($th);die;
   		}
   			
			$block_users=blockUsers($user_id);
			/*$sugges=array_unique(array_merge($block_users,$thh));
			$sugges=implode(',',$sugges); //followers/followings+block*/
			//print_r($ids);die;
			$mrge=array_merge($block_users,(array)$ids);
			$sugges=array_unique($mrge);
			$sugges=implode(',',$sugges); //followers/followings+block
			$userid=array($user_id);
			$user_mrg=array_unique(array_merge($block_users,$userid));
			$user_mrg=implode(',',$user_mrg); //block+userid
			//print_r($sugges);die;
	   	if($search=='')
		{	 	
	   		//suggestion list when search is empty
   		 	$sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and r.user_id not IN(".$sugges.") group by user_id order by count desc";
	    	$stmt=$pdo->prepare($sql);
			$stmt->execute(array('user_id'=>$user_id));
		    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
		   // print_r($res);die;			
		    if($res)
		    {
		    	/*--------------pagination---------------*/
		    	$totalCount =  $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination('50',$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where  r.user_id!=:user_id and r.user_id not IN(".$sugges.") group by user_id order by count desc LIMIT $starting_limit, $size");
				$ar=array('user_id'=>$user_id);
				$stmt1->execute($ar);
				/*----------------------------------*/
		    	foreach($stmt1 as $r)
		    	{
		    		
		    		$data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$r['profile_pic'],'user_type'=>'0');
		    	}
		 		// print_r($data);die;
		    	$json=array('message'=>'success','status'=>'1','total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data ? $data :array());
			}
			else
			{
				//$sql="select user_id,username,fullname,if((profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', profile_pic)),('')) AS profile_pic from register where user_id!=:user_id  limit 20 ";
				$sql="select user_id,username,fullname,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),('')) AS profile_pic from register where  user_id NOT IN(".$user_mrg.") limit 50 ";
		  		$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
			    $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
			    //print_r($json);die;
				
				     /**********pagination***********/
		    		$totalCount = $stmt->rowCount();
		    		$total_pages = ceil($totalCount/$size);
			    	$starting_limit=pagination('50',$pageNo,$size);
			    	$stmt1=$pdo->prepare("select user_id,username,fullname,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),('')) from register where user_id NOT IN(".$user_mrg.")  LIMIT $starting_limit, $size");
					$ar=array('user_id'=>$user_id);
					$stmt1->execute($ar);
					/*****************************/
					foreach($stmt1 as $r)
					{
						if (in_array($r['user_id'], $ids))
                        {
                            $user_type='1';
                        }
                        else
                        {
                            $user_type='0';
                        }
                        //$profile_pic= $r['profile_pic'];
                       $profile_pic= get_profile_pic($r['profile_pic']);

						$data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'user_type'=>$user_type);
					}
					//print_r($data);
				    $json=array('message'=>'Follow people to start seeing the photos and videos they share','status'=>'1','current_page'=>$pageNo,'page_size'=>$size,'total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data); 

			}
		}
		else
		{
			$block_users=empty($block_users)?'0':implode(',',$block_users);

			//$stmt=$pdo->prepare("select r.user_id,r.username,r.fullname,if((profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where r.fullname like '%".$search."%' or r.username like '%".$search."%'  and r.user_id!=:user_id  group by r.user_id");
			$qry="select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where ((r.user_id not IN(".$block_users.")) or r.user_id=:user_id) and (r.fullname like '%".$search."%' or r.username like '%".$search."%')   group by r.user_id ";
			$stmt=$pdo->prepare($qry);
			$ar=array('userid'=>$user_id,':user_id'=>$user_id);
			$stmt->execute($ar);
		    $res=$stmt->fetchAll(PDO::FETCH_ASSOC); 
		  	// print_r($qry);die;
		    if(!empty($res))
		    {
		    	   /*------pagination---------*/
		    	$totalCount = $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where ((r.user_id not IN(".$block_users.")) or r.user_id=:user_id) and (r.fullname like '%".$search."%' or r.username like '%".$search."%')  group by r.user_id  LIMIT $starting_limit, $size");
				$ar=array('userid'=>$user_id,':user_id'=>$user_id);
				$stmt1->execute($ar);
				/*-------------------------------*/
			
		    	foreach($stmt1 as $r)
		    	{
		    		//1=followers 0=following
		    		//print_r($r);die;
		    		$user_type=($r['follower_id']==$user_id)  ? '1' :'0' ;
		    		$user_type=($user_id==$r['user_id']) ? '3' :$user_type;
		            $profile_pic= get_profile_pic($r['profile_pic']);
		           // $profile_pic= $r['profile_pic'];
		    		$data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'user_type'=>$user_type);
		    	}
		    	$json=array('message'=>'success','status'=>'1','current_page'=>$pageNo,'page_size'=>$size,'total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data?$data: array());
		
		    }
		    else
		    {
		    	$json=array('message'=>'no user found','status'=>'0','data'=>array());
				
		    }
		}		
    }
    else
    {
    	
    	$json=array('message'=>'No user_id found','status'=>'0','data'=>array());
    }
 
	echo "{\"response\":" . json_encode($json) . "}";
}

function fetch_feed()
{	
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$size=10;

	$res=get_userData($user_id);
    //print_r($res);die;
   
	//notifivation count
	$ncount=noti_count($user_id);
    if($res)
    {
    	$profile_pic= get_profile_pic($res['profile_pic']);
    	if($device_token!=''&& $device_type!='' && $fcm_token!='')
    	{
	    		///////////update token///////////
	    	$where=array('user_id'=>$user_id);
			$data1=array('device_token'=>$device_token,'device_type'=>$device_type,'fcm_token'=>$fcm_token,'deviceId'=>$deviceId);
			$stmt_updt=update_multi_where('register', $where, $data1);
    	}
    	
    	/////////////////////////////////
    	

    	$stmt=$pdo->prepare("select follower_id from followers where user_id=:user_id "); 
		$stmt->execute(array('user_id'=>$user_id));
	    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    //print_r($result);die;
	    $stmt=$pdo->prepare("select user_id from followers where follower_id=:user_id ");
		$stmt->execute(array('user_id'=>$user_id));
	    $output=$stmt->fetchAll(PDO::FETCH_ASSOC);
		//print_r($output);die;
		if($output)
		{
			foreach($output as $r)
	    	{
	    		$flwg[]=implode(",",$r);
	    	
	    	}
          
	    	$fs=implode(',',$flwg);    //followings
          
			$block_users=blockUsers($user_id);
			$sugges=array_unique(array_merge($block_users,$flwg));
			$tt=implode(',',$sugges); //followings+block

			$dd=blockUsers($user_id);
			$unblock_followings = array_diff($flwg, $dd); 
			$z=implode(',',$unblock_followings);
	    }
	    if($result)
	    {
	    	foreach($result as $rslt)
	    	{
	    		$d[]=implode(",",$rslt);
	    	
	    	}
	    	$fo=implode(',',$d);		//followers
	   		 	
		 	$ar_mrg = array_merge($output, $result);
          
            if($ar_mrg)
            {
                foreach($ar_mrg as $a)
                {
                    $thh[]=implode('',$a);
                }
                $th=implode(',',$thh);

                $dat=blockUser($thh,$user_id);
                $th=implode(',',$dat['unblock_users']);
            }
		}
		
	 	
	 	
    	if(!empty($z))
    	{

    	

    		$suggestion=suggest($z);
    		$sugges=$suggestion['data']['suggestions'];
    		//$sugg=suggestion($th,$z) ? suggestion($th,$z) :$sugges ;
    		$sugg=suggestion($tt,$z) ? suggestion($tt,$z) :$sugges ;
    		$sugg=($pageNo==1)?$sugg :array();
    		//$t=suggestion($tt,$z);
    		//print_r($suggestion);die;
    		

    		//if followers and following exist
    		$sql="select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,
    		    CASE u.upload_type
      			WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
     			WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      			ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  				END as 'post_url',
  				if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id) left join reports as rs on rs.post_id!=u.id  where r.user_id IN(".$z.") or r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc";
    	
    		$stmt=$pdo->prepare($sql);
			$stmt->execute(array('user_id'=>$user_id));
		    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    // print_r($sql);die;
		    if($row)
		    {
		   		/**********pagination***********/
	    		$totalCount = $stmt->rowCount();
	    		$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,CASE u.upload_type
				      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
				      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
				      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
				  END as 'post_url',if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id) left join reports as rs on rs.post_id!=u.id   where r.user_id IN(".$z.") or r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc LIMIT $starting_limit, $size");
				$ar=array('user_id'=>$user_id);
				$stmt1->execute($ar);
				 //$row=$stmt1->fetchAll(PDO::FETCH_ASSOC);
		    	//print_r($row);die;
				/*****************************/
				
			 
			    foreach($stmt1 as $result)
				{
					//$copy_url="https://rawuncensored.com/post_copy/".$result['id']."";
					////membership purchased for one person
					$stmt=$pdo->prepare("select endDate from followSubscription where toUserId=:toUserId and fromUserId=:fromUserId and subscriptionType='0' ");
					$stmt->execute(array('fromUserId'=>$user_id,'toUserId'=>$result['user_id']));
			    	$rspnss=$stmt->fetch(PDO::FETCH_ASSOC);
			    	$isFollowSubscriptionPurchased=(strtotime($rspnss['endDate']) > strtotime(date('Y-m-d')))?'1':'0';

					///////////content
					//$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id and content!='' ");
					$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id  ");
					$stmt->execute(array('upload_id'=>$result['id']));
			    	$op=$stmt->fetchAll(PDO::FETCH_ASSOC);
			    	//print_r($op);die;
			    		//views  
					$stmt_sel=$pdo->prepare("SELECT * from upload_views where upload_id=:post_id and user_id=:user_id");
					$array_sel=array('post_id'=>$result['id'],'user_id'=>$user_id);
					$stmt_sel->execute($array_sel);
					$outpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);

					$stmt_sel=$pdo->prepare("SELECT count(id) as totalViews from upload_views where upload_id=:post_id");
					$array_sel=array('post_id'=>$result['id']);
					$stmt_sel->execute($array_sel);
					$otpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);
					//print_r($otpt);die;
					//print_r($outpt);die;
					//likes 
		    		$stmt=$pdo->prepare("select * from  likes where upload_id=:upload_id and follower_id=:user_id");
					$stmt->execute(array('upload_id'=>$result['id'],'user_id'=>$user_id));
			    	$results=$stmt->fetch(PDO::FETCH_ASSOC);
			    	//like count
		    		$stmt=$pdo->prepare("select count(l.id) as like_count,u.* from uploads as u join likes as l on l.upload_id=u.id where l.upload_id=:id group by u.id  ");
			    	$stmt->execute(array('id'=>$result['id']));
					$reslt1=$stmt->fetch(PDO::FETCH_ASSOC);
					//$counts=trim($result['comments_count'],'"');  //remove '' 
					//comments count
					//$stmt=$pdo->prepare("select count(c.id) as comment_count,u.* from uploads as u join comments as c on c.upload_id=u.id where c.upload_id=:id group by u.id  ");
					$stmt=$pdo->prepare("select count(c.id) as comment_count,u.* from uploads as u join comments as c on c.upload_id=u.id where c.upload_id=:id  ");
					$stmt->execute(array('id'=>$result['id']));
					$reslt2=$stmt->fetch(PDO::FETCH_ASSOC);
					$com=array('comments'=>$result['comment']);

					//post shared or not
					$stmt=$pdo->prepare("select r.user_id,r.username,(SELECT caption FROM uploads WHERE id=:post_id) as caption,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic
                        from register as r   join uploads as u on r.user_id=u.post_parent WHERE u.post_parent=:post_parent GROUP by user_id");
					$stmt->execute(array('post_parent'=>$result['post_parent'],'post_id'=>$result['post_id']));
					$shared=$stmt->fetchAll(PDO::FETCH_ASSOC);
					$sh=($shared)? $shared : array();
					
					

					// $id=$res['user_id'];
					
					//print_r($res['profile_pic']);die;
					$profile_pic= get_profile_pic($res['profile_pic']);

					$data[]=array('user_id'=>$result['user_id'],
				    				'username'=>$result['username'],
				    				'isblock'=>$result['isblock'],
				    				//'profile_pic'=>$profile_pic,
				    				'profile_pic'=>$res['profile_pic'],
				    				//'profile_pic'=>$result['dp'],
				    				'posts'=>$result['post_url'],
				    				'follower_profile'=>$result['dp'],
				    				'thumbnail_url'=>$result['thumbnail_url'],
				    				'posts_type'=>$result['upload_type'],
				    				'post_id'=>$result['id'],
				    				//'copy_url'=>$copy_url,
				    				'is_explicit'=>($result['explicit']==2) ? '1' : '0' ,
				    				'isFollowSubscriptionPurchased'=>$isFollowSubscriptionPurchased,
				    				'is_view'=>!empty($outpt) ? '1' :'0',
				    				'total_views'=>$otpt['totalViews']? $otpt['totalViews']:'0',
				    				'links'=>$op? $op:array(),
				    				//'is_explicit'=>array(0=>$result['content_description']? $result['content_description']:''),
				    				//	'is_purchased'=>($result['membership']==2) ? 1 : 0 ,
				    				'likes_count'=>($reslt1['like_count']) ? $reslt1['like_count'] :'0',
				    				'likes'=>($results)? '1' : '0',
				    				'comments_count'=>($reslt2['comment_count']) ? $reslt2['comment_count'] : '0',
				    				'comments'=>array(0=>$result['comment']? $result['comment']:''),
				    				'shared'=>$sh,
				    				'caption'=>($result['caption']) ? $result['caption'] : '',
				    				'postMessage'=>$result['postMsg']
				    				
					    			);
					$data1=array('result'=>$data,'suggestions'=>$sugg ?$sugg :array(),'is_membership'=>$res['membership'],'noti_count'=>$ncount);

				}
				
				
				$json=fetchFeedResponse('successs',$res['isblock'],$totalCount,$total_pages,$data1,'follower and following exist');  
			}
			else
			{
             
				//if no post upload(suggestions)
				$sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic from register as r left join followers as f on f.user_id=r.user_id where r.user_type='0' and r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc";
                $stmt=$pdo->prepare($sql);
                $stmt->execute(array('user_id'=>$user_id));
                $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
               // print_r($sql);die;
                if($json)
                {                 
                     /**********pagination***********/

                    $totalCountt = $stmt->rowCount();
                    $totalCount = $totalCountt >20 ? 20 :$totalCountt;
                    $total_pages = ceil($totalCount/10);
                    $starting_limit=pagination($totalCount,$pageNo,10);
                   // $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc LIMIT $starting_limit, $size";
                    $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic  from register as r left join followers as f on f.user_id=r.user_id where r.user_type='0' and r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc LIMIT $starting_limit, $size";
                    $stmt1=$pdo->prepare($sql);
                    $ar=array('user_id'=>$user_id);
                    $stmt1->execute($ar);
                            // $json1=$stmt1->fetchAll(PDO::FETCH_ASSOC);
                            // print_r($json1);die;
                    /*****************************/
                    foreach($stmt1 as $r)
                    {
                    	if (in_array($r['user_id'], $z))
						{
							$user_type='1';
						}
						else
						{
							$user_type='0';
						}
						$profile_pic= get_profile_pic($r['profile_pic']);

                        $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'users_type'=>$user_type);
                    }
			    	$data1=array('result'=>array(),'suggestions'=>$data ?$data :array(),'is_membership'=>$res['membership'],'noti_count'=>$ncount);

			    	$json=fetchFeedResponse('The photos and videos they did not share',$res['isblock'],$settings,$totalCount,$total_pages,$data1,'no post only suggestions');
			    	
			    }
			    else
			    {
			    	//call sugg() function
			    	//suggestions not exist
			    	
			    	$json=sugg($user_id); 		    	
			    }

			}
    	}
    	else
    	{
         
    		//if no follower/following but own feed exist
    		$suggestion=sugg();
    		$suggestion=$suggestion['data']['suggestions'];	 
          	$data_suggestion=($pageNo==1)?$suggestion :array();
    		//print_r($data_suggestion);die;		

    		$sql="select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,
    		CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  		END as 'post_url',if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id)  where r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc";
    		$stmt=$pdo->prepare($sql);
			$stmt->execute(array('user_id'=>$user_id));
		    $op=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    if($op)
		    {
		    	/**********pagination***********/
	    		$totalCount = $stmt->rowCount();
	    		$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.*,u.*,u.id as post_id,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  END as 'post_url',if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id)  where r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc LIMIT $starting_limit, $size");
				$ar=array('user_id'=>$user_id);
				$stmt1->execute($ar);
			    //$op=$stmt1->fetchAll(PDO::FETCH_ASSOC);
			    //print_r($op);die;

				/*****************************/
			    foreach($stmt1 as $result)
				{

					////membership purchased for one person
					$stmt=$pdo->prepare("select endDate from followSubscription where toUserId=:user_id and fromUserId=:fromUserId and subscriptionType='0' ");
					$stmt->execute(array('user_id'=>$user_id,'fromUserId'=>$result['user_id']));
			    	$rspnss=$stmt->fetch(PDO::FETCH_ASSOC);
			    	$isFollowSubscriptionPurchased= $rspnss ? ((strtotime($rspnss['endDate']) > strtotime(date('Y-m-d')))?'1':'0') : '0';
					//content
					//$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id and content!='' ");
					$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id  ");
					$stmt->execute(array('upload_id'=>$result['id']));
			    	$op=$stmt->fetchAll(PDO::FETCH_ASSOC);
					
					//likes 
		    		$stmt=$pdo->prepare("select * from  likes where upload_id=:upload_id and follower_id=:user_id");
					$stmt->execute(array('upload_id'=>$result['id'],'user_id'=>$user_id));
			    	$results=$stmt->fetch(PDO::FETCH_ASSOC);
			    	//like count
		    		$stmt=$pdo->prepare("select count(l.id) as like_count,u.* from uploads as u join likes as l on l.upload_id=u.id where l.upload_id=:id   ");
			    	$stmt->execute(array('id'=>$result['id']));
					$reslt1=$stmt->fetch(PDO::FETCH_ASSOC);
					//$counts=trim($result['comments_count'],'"');  //remove '' 
					//comments count
					$stmt=$pdo->prepare("select count(c.id) as comment_count,u.* from uploads as u join comments as c on c.upload_id=u.id where c.upload_id=:id  ");
					$stmt->execute(array('id'=>$result['id']));
					$reslt2=$stmt->fetch(PDO::FETCH_ASSOC);
					$com=array('comments'=>$result['comment']);

					//post shared or not
					$stmt=$pdo->prepare("select r.user_id,r.username,(SELECT caption FROM uploads WHERE id=:post_id) as caption,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic
                        from register as r   join uploads as u on r.user_id=u.post_parent WHERE u.post_parent=:post_parent GROUP by user_id");
					$stmt->execute(array('post_parent'=>$result['post_parent'],'post_id'=>$result['post_id']));
					$shared=$stmt->fetchAll(PDO::FETCH_ASSOC);
					$sh=($shared)? $shared : array();
					//$profile_pic= get_profile_pic($res['profile_pic']);
					//$dp= get_profile_pic($result['dp']);
					$data[]=array('user_id'=>$result['user_id'],
				    				'username'=>$result['username'],
				    				'isblock'=>$result['isblock'],
				    				//'profile_pic'=>$profile_pic,
				    				'profile_pic'=>$res['profile_pic'],
				    				'posts'=>$result['post_url'],
				    				'follower_profile'=>$result['dp'],
				    				//'follower_profile'=>$result['dp'],
				    				'thumbnail_url'=>$result['thumbnail_url'],
				    				'posts_type'=>$result['upload_type'],
				    				'post_id'=>$result['id'],
				    				'is_explicit'=>($result['explicit']==2) ? '1' : '0' ,
				    				'isFollowSubscriptionPurchased'=>$isFollowSubscriptionPurchased,
				    				'links'=>$op? $op:array(),
				    				//	'is_purchased'=>($result['membership']==2) ? 1 : 0 ,
				    				'likes_count'=>($reslt1['like_count']) ? $reslt1['like_count'] :'0',
				    				'likes'=>($results)? '1' : '0',
				    				//'comments_count'=>$counts ? $counts : '0',
				    				'comments_count'=>($reslt2['comment_count']) ? $reslt2['comment_count'] : '0',
				    				'comments'=>array(0=>$result['comment']? $result['comment']:''),
				    				'shared'=>$sh,
				    				'caption'=>($result['caption']) ? $result['caption'] : '',
				    				'postMessage'=>$result['postMsg']
				    				
					    			);
                
					

				}
          
              $data1=array('result'=>$data,'suggestions'=>$data_suggestion ?$data_suggestion :array(),'is_membership'=>$res['membership'],'noti_count'=>$ncount );

				 $json=fetchFeedResponse('success',$res['isblock'],$totalCount,$total_pages,$data1,'no follower/following but own feed exist');  
		    }
		    else
		    {
             
		    	//echo "data not exist";
	            // $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id group by user_id order by count desc";
	             $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic from register as r  join followers as f on f.user_id=r.user_id where f.follower_id =:user_id and user_type='0' group by user_id ";
	            $stmt=$pdo->prepare($sql);
	            $stmt->execute(array('user_id'=>$user_id));
	            //$stmt->execute(array());
	            $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
	           
	             // print_r($json);die;
	             /**********pagination***********/
	            $totalCountt = $stmt->rowCount();
	            $totalCount = $totalCountt >20 ? 20 :$totalCountt;
	            $total_pages = ceil($totalCountt/$size);
	            $starting_limit=pagination($totalCount,$pageNo,$size);
	            $stmt1=$pdo->prepare("select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic  from register as r left join followers as f on f.user_id=r.user_id where user_type='0' and r.user_id!=:user_id group by user_id  LIMIT $starting_limit, $size");
	             $stmt=$pdo->prepare($sql);
	             $stmt1->execute(array('user_id'=>$user_id));
	         	$json=$stmt->fetchAll(PDO::FETCH_ASSOC);
	           
	              //print_r($json);die;
	            /*****************************/
	            $users_type=($json!='') ? '0' :'1';
	            foreach($stmt1 as $r)
	            {
	            	$profile_pic= get_profile_pic($r['profile_pic']);

	                $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'users_type'=>$users_type);
	            }
	            $data1=array('result'=>array(),'suggestions'=>$data ?$data :array(),'is_membership'=>$res['membership'],'noti_count'=>$ncount );
                   

          		 $json=fetchFeedResponse('Follow people to start seeing the photos and videos they share',"0",$totalCount,$total_pages,$data1,'data not exist');

            
		    }

    	}
    	
  	}
  	else
  	{
  		$json=array('message'=>'no user found','status'=>'0','data'=>new stdClass()); 
  	}

	echo "{\"response\":" . json_encode($json) . "}";	
    
}

function post_comments()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select r.*,u.* from register as r join uploads as u on r.user_id=u.user_id where u.id=:post_id");
	$stmt->execute(array('post_id'=>$post_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
   	if($res)
    {
     	//insert comment
 		$data2=array('follower_id'=>$user_id,'upload_id'=>$post_id,'comment'=>$comment,'time'=>time());
  		$stmt_insert=insert('comments',$data2); 
  		if($stmt_insert)
  		{

  			//add trending count of post
  			$stmt=$pdo->prepare("select * from trending where  upload_id=:post_id");
			$stmt->execute(array('post_id'=>$post_id));
		    $trending=$stmt->fetch(PDO::FETCH_ASSOC);
		    if($trending)
		    {
		    	//update
		    	$trending_count=$trending['trending_count']+1;
		    	$where=array('upload_id'=>$post_id);
				$data=array('trending_count'=>$trending_count);
				$stmt_updt=update_multi_where('trending', $where, $data);
		    }else{
		    	//insert

		    	$trending_count=1;
		    	$data2=array('trending_count'=>$trending_count,'upload_id'=>$post_id);
  		        $stmt_insert=insert('trending',$data2); 

		    }

		   

  			if($tagUsers)
  			{
  				$userName=$res['fullname'];
  				$stmt=$pdo->prepare("select * from register where user_id in('".$tagUsers."')");
				$stmt->execute(array());
			    $r=$stmt->fetchAll(PDO::FETCH_ASSOC);
			   	
			   	foreach($r as $res)
			    {
			    	$noti_type='tags';
			        $msg='you are mentioned in '.$userName.' post ';
			        if($res['device_type']=='A')
			        {
			             $data = array( 'registration_ids'  =>array($res['device_token']),'data' => array("message" =>$msg ,"noti_type" => $noti_type,'post_id'=>$post_id));
			             $noti=android_noti2($data);
			        }
			        else
			        {   
			            $data2=array('alert'=>$noti,'sound'=>'default',"noti_type" => $noti_type,'post_id'=>$postId);
			            $noti2=ios_notification($data2,$res['device_token'],$msg);   
			        }
			    }
	  			
  			}
  			

  			$stmt=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by c.id desc");
			$stmt->execute(array('post_id'=>$post_id));
	     	$output=$stmt->fetch(PDO::FETCH_ASSOC);
	     	//print_r($output);
  			
	  		$stmt=$pdo->prepare("select *,if((profile_pic!=''  && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from  register where user_id=:user_id");
			$stmt->execute(array('user_id'=>$user_id));
		    $result=$stmt->fetch(PDO::FETCH_ASSOC);

		    if($result['device_token']==$res['device_token'])
		    {
		    	$status='1';
				$message="comments successfully"; 
				$json=$output;
		    }
		    else
		    {
		   
			     $message=$result['fullname']." "."commented ".$comment;
			     $message1="commented ".$comment." on ";

			     $data2=array('user_id'=>$res['user_id'],'follower_id'=>$user_id,'upload_id'=>$post_id,'notification'=>$message1);
	  			$data_insert=insert('notifications',$data2); 

			    $noti_type="post comment";
			    //noti count
			    $id=$res['user_id'];
				$ncount=noti_count($id);
			 
				if($res['device_type']=='A')
				{
					$data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $message,"noti_type" => $noti_type,"follower_id"=>$user_id,"post_id"=>$post_id, "profile_pic"=>$result['profile_pic'],'noti_count'=>$ncount) );
					//$noti=android_noti($res['device_token'],$message,$noti_type,$user_id,'','','',$result['profile_pic'],'');
					$noti=android_noti2($data);
					//print_r($noti);die;	
				}
				else
				{ 	
					$data2=array('alert'=>$message,'sound'=>'default','badge'=>intval($ncount),'message'=>$message,'type'=>$noti_type,'follower_id'=>$user_id,'post_id'=>$post_id,'profile_pic'=>$result['profile_pic'],'noti_count'=>$ncount);


					//$data2=array('alert'=>$message,'sound'=>'default','message'=>$message,'type'=>$noti_type,'follower_id'=>$user_id,'post_id'=>$post_id,'profile_pic'=>$result['profile_pic']);

					$noti2=ios_notification($data2,$res['device_token'],$message);

				}
	  			$status='1';
				$message=" commented successfully "; 
				$json=$output;
			}
		}
		else
		{
			$status='1';
			$message='failure'; 
			$json=new stdClass();
		}
    }
    else
    {
		$status='0';
		$message='Post does not exist';
		$json=new stdClass();
	}
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}

function post_likes()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select r.*,u.* from register as r join uploads as u on r.user_id=u.user_id where u.id=:post_id ");
	$stmt->execute(array('post_id'=>$post_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
     //  print_r($res);die;

   	if($res)
    {
	    if($is_like==1)   //like
		{
    		$stmt=$pdo->prepare("select * from likes where follower_id=:user_id and upload_id=:post_id");
			$stmt->execute(array('user_id'=>$user_id,'post_id'=>$post_id));
		    $rslt=$stmt->fetch(PDO::FETCH_ASSOC);
		    if(empty($rslt))
		    {
		 		$data2=array('follower_id'=>$user_id,'upload_id'=>$post_id);
		  		$stmt_insert=insert('likes',$data2);  	
		  		//print_r($stmt_insert);die;
		  		if($stmt_insert)
		  		{
		  			//add trending count of post
		  			$stmt=$pdo->prepare("select * from trending where  upload_id=:post_id");
					$stmt->execute(array('post_id'=>$post_id));
				    $trending=$stmt->fetch(PDO::FETCH_ASSOC);
				    if($trending)
				    {
				    	//update
				    	$trending_count=$trending['trending_count']+1;
				    	$where=array('upload_id'=>$post_id);
						$data=array('trending_count'=>$trending_count);
						$stmt_updt=update_multi_where('trending', $where, $data);
				    }else{
				    	//insert

				    	$trending_count=1;
				    	$data2=array('trending_count'=>$trending_count,'upload_id'=>$post_id);
		  		        $stmt_insert=insert('trending',$data2); 

				    }


                   // 6BUUBBCr
		  			//send noti
			  		$stmt=$pdo->prepare("select *,if((profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),('')) AS profile_pic from  register where user_id=:user_id");
					$stmt->execute(array('user_id'=>$user_id));
				    $result=$stmt->fetch(PDO::FETCH_ASSOC);

				    if($result['device_token']==$res['device_token'])
				    {
				    	
				    	$status='1';
						$message="liked successfully"; 
				    }
				    else
				    {	
				    	
				    	//notifications
					    $message=$result['fullname']." "."Liked your post";
					    $message1='Liked your post';
					   // print_r($message1);die;

					    $data2=array('user_id'=>$res['user_id'],'follower_id'=>$user_id,'upload_id'=>$post_id,'notification'=>$message1);
	  					$data_insert=insert('notifications',$data2); 
						$noti_type="post like";
						$id=$res['user_id'];
						$ncount=noti_count($id);
    					//print_r($ncount);die;
						if($res['device_type']=='A')
						{
							$data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $message,"noti_type" => $noti_type,"follower_id"=>$user_id,"post_id"=>$post_id, "profile_pic"=>$result['profile_pic'],'noti_count'=>$ncount));
							$noti=android_noti2($data);
							//$noti=android_noti($res['device_token'],$message,$noti_type,$user_id,'','','',$result['profile_pic'],'');	
							
						}
						else
						{ 	
							$data2=array('alert'=>$message,'sound'=>'default','badge'=>intval($ncount),'message'=>$message,'type'=>$noti_type,'follower_id'=>$user_id,'post_id'=>$post_id,'profile_pic'=>$result['profile_pic'],'noti_count'=>$ncount);

							$noti2=ios_notification($data2,$res['device_token'],$message);

							//$noti=ios_noti($res['device_token'],$message,$noti_type,$user_id,'','','',$result['profile_pic'],'');
						}
						
						$status='1';
						$message="liked successfully"; 
					}
				}
				else
				{
				
					$status='0';
					$message='failure'; 
					
				}	
			}
			else
			{
				$status='0';
				$message='already liked'; 
			}
		}
		else if($is_like==0) //unlike
		{
			$data=array('upload_id'=>$post_id,'follower_id'=>$user_id);
    		$del=delete('likes', $data);
    		if($del==1)
			{
				$data1=array('upload_id'=>$post_id,'follower_id'=>$user_id);
	    	    $dlt=delete('notifications', $data1);

				$status='1';
				$message='unlike successfully';
			}	
			else
			{
				$status='0';
				$message='Something went wrong';
			}    
		}
		else
		{
			$status='0';
			$message=' wrong  Input';
		}
    }
    else
    {	
		$status='0';
		$message='Post does not exist';
	}
	$json=array('message'=>$message,'status'=>$status);
	echo "{\"response\":" . json_encode($json) . "}";
}

function get_comments()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$size=10;
	$stmt=$pdo->prepare("select * from  uploads where id =:post_id");
	$stmt->execute(array('post_id'=>$post_id));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if($result) 
    {
    	$stmt=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by id desc");
		$stmt->execute(array('post_id'=>$post_id));
     	$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
     	if($res)
	    {		
    		/**********pagination***********/
    		$totalCount = $stmt->rowCount();
    		$total_pages = ceil($totalCount/$size);
	    	$starting_limit=pagination($totalCount,$pageNo,$size);
	    	$stmt1=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by id desc LIMIT $starting_limit, $size");
			$ar=array('post_id'=>$post_id);
			$stmt1->execute($ar);
			/*****************************/
	    	foreach($stmt1 as $r)
	    	{
	    		$json[]=array('id'=>$r['id'],'follower_id'=>$r['follower_id'],'comment'=>$r['comment'],'username'=>$r['username'],'profile_pic'=>$r['profile_pic']);
	    	}

			//$json=$res;
			$json=array('message'=>'success','status'=>'1','total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$json);
	    }
	    else
	    {
			$json=array('message'=>'comment not found','status'=>'0','data'=>array());
		}
    }
    else
    {
		$json=array('message'=>'Post does not exist','status'=>'0','data'=>array());
	}
	echo "{\"response\":" . json_encode($json) . "}";

}
function get_likes()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select * from  uploads where id =:post_id");
	$stmt->execute(array('post_id'=>$post_id));
     $result=$stmt->fetch(PDO::FETCH_ASSOC);
     if($result) 
     {
    	$stmt=$pdo->prepare("select l.id,l.follower_id,r.username,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/',r.profile_pic)),('')) AS profile_pic from  likes as l join register as r on r.user_id=l.follower_id where l.upload_id=:post_id");
		$stmt->execute(array('post_id'=>$post_id));
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    if($res)
	    {
	    	$json=$res;
			$status='1';
			$message='success';
	    }
	    else
	    {
	    	
			$status='0';
			$message='no likes';
			$json=new stdClass();
		}
    }
    else
    {	
		$status='0';
		$message='Post does not exist';
		$json=new stdClass();
	}
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";

}

function trending()
{
	//get trending videos based on likes and comments
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
		$stmt=$pdo->prepare("SELECT u.*,t.trending_count FROM `uploads` as u JOIN trending as t on u.id=t.upload_id ORDER by t.trending_count desc limit 10");
		$stmt->execute(array());
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    if($res)
	    {
	    	foreach ($res as  $value) {
	    		if ($value['upload_type']=='I') {
	    			//images
	    			$images[]=array(
	    				           'image'=>"https://".$server."/Danda-Backend/uploads/images/".$value['uploads'],
	    				           'upload_id'=>$value['id'],
	    				           'upload_type'=>$value['upload_type'],
	    		                  );
	    		}elseif($value['upload_type']=='V')
	    		{
	    			//video
	    			$videos[]=array(
	    				           'image'=>"https://".$server."/Danda-Backend/uploads/videos/".$value['uploads'],
	    				           'upload_id'=>$value['id'],
	    				           'upload_type'=>$value['upload_type'],
	    		                  );
	    		}else{
	    			//gif
	    			$gif[]=array(
	    				           'image'=>"https://".$server."/Danda-Backend/uploads/gif/".$value['uploads'],
	    				           'upload_id'=>$value['id'],
	    				           'upload_type'=>$value['upload_type'],
	    		                  );
	    		}
	    		
	    	}//end foreach

	    	$data1=array('images'=>($images)??array(),'videos'=>($videos)??array(),'gif'=>($gif)??array());
	    	$json=$data1;
			$status='1';
			$message='success';
			
	    }
	    else
	    {
	    	
			$status='0';
			$message='No trending post yet';
			$json=new stdClass();
		}
    
   
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";


}

function viewAllTrendings()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$size=10;
	$pageNo=1;


	$stmt=$pdo->prepare("SELECT u.*,t.trending_count ,usr.username,usr.profile_pic,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  END as 'post_url',if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url  FROM `uploads` as u left JOIN trending as t on u.id=t.upload_id left JOIN register as usr on usr.user_id=u.user_id where u.upload_type=:type group by u.id  ORDER by t.trending_count desc");
		$stmt->execute(array('type'=>$type));
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    if($res)
	    {
	    	/**********pagination***********/
    		$totalCount = $stmt->rowCount();
    		$total_pages = ceil($totalCount/$size);
	    	$starting_limit=pagination($totalCount,$pageNo,$size);

	    	$stmt1=$pdo->prepare("SELECT u.*,t.trending_count ,usr.username,usr.profile_pic,CASE u.upload_type
			      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
			      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
			      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
			  END as 'post_url',if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url  FROM `uploads` as u left JOIN trending as t on u.id=t.upload_id left JOIN register as usr on usr.user_id=u.user_id where u.upload_type=:type group by u.id  ORDER by t.trending_count desc  LIMIT $starting_limit, $size");
				$ar=array('type'=>$type);
				$stmt1->execute($ar);
				foreach($stmt1 as $result)
				{

					//likes 
		    		$stmt=$pdo->prepare("select * from  likes where upload_id=:upload_id and follower_id=:user_id");
					$stmt->execute(array('upload_id'=>$result['id'],'user_id'=>$result['user_id']));
			    	$results=$stmt->fetch(PDO::FETCH_ASSOC);

			    	//like count
		    		$stmt=$pdo->prepare("select count(l.id) as like_count,u.* from uploads as u join likes as l on l.upload_id=u.id where l.upload_id=:id   ");
			    	$stmt->execute(array('id'=>$result['id']));
					$reslt1=$stmt->fetch(PDO::FETCH_ASSOC);
					//$counts=trim($result['comments_count'],'"');  //remove '' 

					//views  
					$stmt_sel=$pdo->prepare("SELECT * from upload_views where upload_id=:post_id");
					$array_sel=array('post_id'=>$result['id']);
					$stmt_sel->execute($array_sel);
					$outpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);

                    //total views
					$stmt_sel=$pdo->prepare("SELECT count(id) as totalViews from upload_views where upload_id=:post_id");
					$array_sel=array('post_id'=>$result['id']);
					$stmt_sel->execute($array_sel);
					$otpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);


					//comments count
					$stmt=$pdo->prepare("select count(c.id) as comment_count,u.* from uploads as u join comments as c on c.upload_id=u.id where c.upload_id=:id  ");
					$stmt->execute(array('id'=>$result['id']));
					$reslt2=$stmt->fetch(PDO::FETCH_ASSOC);
                   
                   $profile_pic= get_profile_pic($result['profile_pic']);

                   $data[]=array('user_id'=>$result['user_id'],
				    				'username'=>$result['username'],
				    				'profile_pic'=>$profile_pic,
				    				//'profile_pic'=>$res['profile_pic'],
				    				'posts'=>$result['post_url'],
				    				'follower_profile'=>$dp,
				    				//'follower_profile'=>$result['dp'],
				    				'thumbnail_url'=>$result['thumbnail_url'],
				    				'posts_type'=>$result['upload_type'],
				    				'post_id'=>$result['id'],
				    				'is_view'=>!empty($outpt) ? '1' :'0',
				    				'total_views'=>$otpt['totalViews']? $otpt['totalViews']:'0',
				    				
				    				//'links'=>$op? $op:array(),
				    				
				    				'likes_count'=>($reslt1['like_count']) ? $reslt1['like_count'] :'0',
				    				'likes'=>($results)? '1' : '0',
				    				//'comments_count'=>$counts ? $counts : '0',
				    				'comments_count'=>($reslt2['comment_count']) ? $reslt2['comment_count'] : '0',
				    				'comments'=>array(0=>$result['comment']? $result['comment']:''),
				    				//'comments'=>$cmnt ? $cmnt:array(),
				    				 'caption'=>($result['caption']) ? $result['caption'] : '',
				    				 //'postMessage'=>$result['postMsg']
				    				
					    			);

                   $json=array('result'=>$data);


				}//end foreach

			$json=array('message'=>'success','status'=>'1','total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$json); 
	    }

	
	echo "{\"response\":" . json_encode($json) . "}";

}

function profilePic($oldPic)
{
	
	$image_name=$_FILES['profile_pic']['name'];
	$tmp = explode('.', $image_name);
	$fileExtension = end($tmp);
	$target_dir = "uploads/profile/";
	if (!is_dir($target_dir))
    {
        mkdir($target_dir, 0775, true);
    }
  	$image_name='img_'.rand().'.'.$fileExtension;
	$img_temp_name=$_FILES['profile_pic']['tmp_name'];
  	$target_file = $target_dir . basename($image_name);
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if (move_uploaded_file($img_temp_name, $target_file)) 
    {
       $profile_pic=$image_name;

        //unlink old image
		@unlink(__DIR__."/uploads/profile/".$oldPic);// /home/sammyekaran/public_html/api/uploads/profile/
    }
    else 
    {
         $profile_pic='';
    }

		

    return $profile_pic;
}

function update_profile()
{
	
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
 	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
    $stmt->execute(array('user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
  
	if($row_sel)
	{ 	
		//check username
        $stmt=$pdo->prepare("select * from register where username=:username and user_id!=:user_id");
	    $stmt->execute(array('username'=>$username,'user_id'=>$user_id));
	    $output=$stmt->fetch(PDO::FETCH_ASSOC);
	    if(!empty($output))
	    {
			$json=array('status'=>'0','message'=>"Username already exist",'data'=>new stdClass()); 
	    }
	    else
	    {
	    	//check profile pic
	        if($_FILES['profile_pic']['name']!= '')
	        {
	        	
				$profile_pic=profilePic($row_sel['profile_pic']);
	        }
			else
			{
	        	$profile_pic=$row_sel['profile_pic'];
	        }
	       
	  
	         // print_r($profile_pic);die;
			$where=array('user_id'=>$user_id);
			//$data=array('profile_pic'=>$profile_pic,'fullname'=>empty($name)?$row_sel['fullname']:$name,'username'=>empty($username)?$row_sel['username']:$username,'website'=>empty($website)?$row_sel['website']:$website,'bio'=>empty($bio)?$row_sel['bio']:$bio,'email'=>empty($email)?$row_sel['email']:$email,'contact'=>empty($contact)?$row_sel['contact']:$contact,'gender'=>empty($gender)?$row_sel['gender']:$gender,'paypal_id'=>empty($paypal_id)?$row_sel['paypal_id'] :$paypal_id);
			$data=array('profile_pic'=>$profile_pic,'fullname'=>$name,'username'=>$username,'website'=>"",'bio'=>$bio,'email'=>$email,'country_code'=>$country_code,'contact'=>$contact,'gender'=>$gender,'paypal_id'=>"",'countryIso'=>$countryIso);
			$updata_data=update_multi_where('register', $where, $data); 
			if($updata_data=1)
			{
				$pp="https://".$server."/Danda-Backend/uploads/profile/".$profile_pic;
				$data=array('profile_pic'=>$pp,'fullname'=>$name,'username'=>$username,'bio'=>$bio,'email'=>$email,'contact'=>$contact,'gender'=>$gender);
				$json=array('status'=>'1','message'=>"Success",'data'=>$data);   	
			}
			else
			{
				$json=array('status'=>'0','message'=>"failure",'data'=>new stdClass());   	
			}
		}
	}
	else
	{
		$json=array('status'=>'0','message'=>"User does not exist",'data'=>new stdClass());   	    	
	}
	echo "{\"response\":" . json_encode($json) . "}";
}




function update_profile_pic()
 {
 	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    
	if($result)
    {	
    	if($_FILES['profile_pic']['name']!= '' )
        {
			$profile_pic=profilePic($result['profile_pic']);
        }
		else
		{
        	$profile_pic=$result['profile_pic'];
        }
        $where=array('user_id'=>$user_id);
		$data1=array('profile_pic'=>$profile_pic);
		$stmt_updt=update_multi_where('register', $where, $data1);
		$pp="https://".$server."/Danda-Backend/uploads/profile/".$profile_pic;
		//print_r($pp);die;
		$json=array('message'=>'success','status'=>'1','profile_pic'=>$pp);
	}
	else
	{
		$json=array('message'=>'Invalid user','status'=>'0');
	}
  echo "{\"response\":" . json_encode($json) . "}";
}



function fetch_profile()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	/*$stmt=$pdo->prepare("select if((profile_pic!=''),(concat('https://".$server."/api/uploads/profile/', profile_pic)),('')) as profile_pic,username,fullname,website,bio,email,password,country_code,contact,gender,paypal_id,countryIso from register where user_id=:user_id 
		");*/
	$stmt=$pdo->prepare("select if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) as profile_pic,username,fullname,bio,email,password,country_code,contact,gender,countryIso from register where user_id=:user_id 
		");

	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
   // print_r($res);die;
    if($res)
    {
    	$json=array('status'=>'1','message'=>"Success",'data'=>$res);   	
    }
    else
    {
    	$json=array('status'=>'0','message'=>"User does not exist",'data'=>new stdClass());   	
    }
    echo "{\"response\":" . json_encode($json) . "}";
}



function count_views()
{
	global $pdo;
	extract($_REQUEST);
	$query_sel="SELECT * from uploads where id=:post_id ";
	$stmt_sel=$pdo->prepare($query_sel);
	$array_sel=array(':post_id'=>$post_id);
	$stmt_sel->execute($array_sel);
	$row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
	if($row_sel)
	{
		$query_sel="SELECT * from upload_views where upload_id=:post_id and user_id=:user_id";
		$stmt_sel=$pdo->prepare($query_sel);
		$array_sel=array(':post_id'=>$post_id,'user_id'=>$user_id);
		$stmt_sel->execute($array_sel);
		$res=$stmt_sel->fetch(PDO::FETCH_ASSOC);
		if($res)
		{
			$json=array('status'=>'0','message'=>'already view','data'=>'');
		}
		else
		{
			$data=array('upload_id'=>$post_id,'user_id'=>$user_id);
		  	$stmt_insert=insert('upload_views',$data);
		  	if($stmt_insert)
		  	{
	  			$query_sel="SELECT count(id) as totalViews from upload_views where upload_id=:post_id ";
				$stmt_sel=$pdo->prepare($query_sel);
				$array_sel=array(':post_id'=>$post_id);
				$stmt_sel->execute($array_sel);
				$output=$stmt_sel->fetch(PDO::FETCH_ASSOC);
			  	$json=array('status'=>'1','message'=>'success','data'=>$output['totalViews']);
		  	}
		  	else
		  	{
		  		$json=array('status'=>'0','message'=>' view','data'=>'');
		  	}
		  
		}

	}
	else
	{
		$json=array('status'=>'0','message'=>'Post does not exist','data'=>'');
	}
	echo "{\"response\":" . json_encode($json) . "}";	
}



function get_reportReasons()
{
	global $pdo;
    extract($_REQUEST);
    
    $stmt_sel=$pdo->prepare("select * from report_reasons");
    $stmt_sel->execute(array());
    $row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
   	// print_r($row_sel);die;
    if($row_sel)
    {
		$json=array('status'=>'1','message'=>'success','data'=>$row_sel);
    }
    else
    {
        $json=array('message'=>'No record found','status'=>'0');
    }
    echo "{\"response\":" . json_encode($json) . "}";  
}





function detail_page()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select * from uploads where id=:post_id");
	$stmt->execute(array('post_id'=>$post_id));
   	$res=$stmt->fetch(PDO::FETCH_ASSOC);
    if($res)
    {

    	$stmt=$pdo->prepare("select r.*,u.*,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/Danda-Backend/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/Danda-Backend/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/Danda-Backend/uploads/videos/', u.uploads)
  END as 'post_url',count(c.id) as comments_count,if((thumbnail!=''),(concat('https://".$server."/Danda-Backend/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url from uploads as u join register as r on r.user_id=u.user_id left join comments as c on c.upload_id=u.id   where u.id=:post_id ");
    		/*$stmt=$pdo->prepare("select r.*,u.id,u.uploads,u.thumbnail,u.upload_type,u.caption,CASE u.upload_type
      WHEN 'I' THEN concat('https://".$server."/api/uploads/images/', u.uploads)
      WHEN 'G' THEN concat('https://".$server."/api/uploads/gif/', u.uploads)
      ELSE concat('https://".$server."/api/uploads/videos/', u.uploads)
  END as 'post_url',count(c.id) as comments_count,if((thumbnail!=''),(concat('https://".$server."/api/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url from uploads as u join register as r on r.user_id=u.user_id left join comments as c on c.upload_id=u.id   where u.id=:post_id ");*/
		$stmt->execute(array('post_id'=>$post_id));
	    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    if($row)
	    {
	   		foreach($row as $result)
			{
				//is view
				$stmt_sel=$pdo->prepare("SELECT * from upload_views where upload_id=:post_id and user_id=:user_id");
				$array_sel=array('post_id'=>$post_id,'user_id'=>$user_id);
				$stmt_sel->execute($array_sel);
				$outpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);
				//totalviews
				$stmt_sel=$pdo->prepare("SELECT count(id) as totalViews from upload_views where upload_id=:post_id");
				$array_sel=array('post_id'=>$post_id);
				$stmt_sel->execute($array_sel);
				$otpt=$stmt_sel->fetch(PDO::FETCH_ASSOC);
				//extra uploaded content
				//$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id and content!='' ");
				$stmt=$pdo->prepare("select content,content_description,price from  upload_content where upload_id=:upload_id ");
				$stmt->execute(array('upload_id'=>$result['id']));
		    	$rslts=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    	//own profile_pic
	    		$stmt_sel=$pdo->prepare("SELECT if((profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', profile_pic)),(profile_pic)) AS follower_profile from register where user_id=:user_id");
				$array_sel=array('user_id'=>$user_id);
				$stmt_sel->execute($array_sel);
				$outputs=$stmt_sel->fetch(PDO::FETCH_ASSOC);
		
			    	//comments
				
		    	$stmt=$pdo->prepare("select c.comment,r.user_id,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,r.username from  comments as c join register as r on r.user_id=c.follower_id  where upload_id=:post_id limit 3");
				$stmt->execute(array('post_id'=>$post_id));
		    	$output=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    	//print_r($output);die;
			    
			    	//likes
	    		$stmt=$pdo->prepare("select * from  likes where upload_id=:upload_id and follower_id=:user_id");
				$stmt->execute(array('upload_id'=>$post_id,'user_id'=>$user_id));
		    	$results=$stmt->fetch(PDO::FETCH_ASSOC);
		       //print_r($results);die;

			    	//likes count
	    		$stmt=$pdo->prepare("select count(l.id) as like_count,u.* from uploads as u join likes as l on l.upload_id=u.id where l.upload_id=:id group by u.id  ");
		    	$stmt->execute(array('id'=>$result['id']));
				$reslt1=$stmt->fetch(PDO::FETCH_ASSOC);
				$counts=trim($result['comments_count'],'"');  //remove '' 

				//post shared or not
					$stmt=$pdo->prepare("select r.user_id,r.username,(SELECT caption FROM uploads WHERE id=:post_id) as caption,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic
                        from register as r   join uploads as u on r.user_id=u.post_parent WHERE u.post_parent=:post_parent GROUP by user_id");
					$stmt->execute(array('post_parent'=>$result['post_parent'],'post_id'=>$post_id));
					$shared=$stmt->fetchAll(PDO::FETCH_ASSOC);
					$sh=($shared)? $shared : array();
					

				//$copy_url="https://rawuncensored.com/post_copy/".$result['id']."";
			
				$data=array('user_id'=>$result['user_id'],
			    				'username'=>$result['username'],
			    				'profile_pic'=>$result['profile_pic'],
			    				'posts'=>$result['post_url'],
			    				'thumbnail_url'=>$result['thumbnail_url'],
			    				'posts_type'=>$result['upload_type'],
			    				'desc'=>$rslts,
			    				'post_id'=>$result['id'],
			    				'caption'=>($result['caption']) ? $result['caption'] :'',
			    				'is_explicit'=>($result['explicit']==2) ? '1' : '0' ,
			    				'is_view'=>!empty($outpt) ? '1' :'0',
			    				'total_views'=>$otpt ? $otpt['totalViews']:'0',
			    				//'copy_url'=>$copy_url? $copy_url : '' ,
			    				//	'is_purchased'=>($result['membership']==2) ? 1 : 0 ,
			    				'likes_count'=> $reslt1 ? $reslt1['like_count'] :'0',
			    				'likes'=>($results)? '1' : '0',
			    				'comments_count'=>$counts ? $counts : '0',
			    				'comments'=>$output ? $output : array(),
			    				'shared'=>$sh,
			    				'follower_profile'=>$outputs['follower_profile'],
			    				'postMessage'=>$result['postMsg']
			    			
				    			);
			}  	
			//print_r($json);
			$status='1';
			$message='success';
   		}
   		else
   		{
   			$data=new stdClass();
   			$status='0';
			$message='Something went wrong';
   		}
    }
    else
    {
     	$data=new stdClass();
     	$status='0';
		$message='No post exist';
    }
    $json=array('message'=>$message,'status'=>$status,'data'=>$data);
	//$json=array('message'=>'no user found','status'=>'0','data'=>new stdClass()); 
 // print_r($json);ðŸ

	echo "{\"response\":" . json_encode($json) . "}";	
}



function watermark_video($value='')
{
	
	global $pdo;
	global $server;
	extract($_REQUEST);
	$vdo_name = $_FILES['vdo_name']['name'];
	//print_r($_SERVER['DOCUMENT_ROOT']);die;

	if(!file_exists($vdo_name))
	{
		    $videoSource = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$vdo_name;
		    $reqExtension = 'mp4';
		    $watermark = $_SERVER['DOCUMENT_ROOT'].'/uploads/DANDA_72.png';
			

			$ffmpeg = \FFMpeg\FFMpeg::create([
                'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe', // the path to the FFMpeg binary
                'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe', // the path to the FFProbe binary
                'timeout'          => 3600, // the timeout for the underlying process
                'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
            ]);

		    $video = $ffmpeg->open($vdo_name);

			$video
            ->filters()
            ->resize(new \FFMpeg\Coordinate\Dimension(320, 240))
            ->synchronize();
        $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
        $frame->save($videoSource);
	}else{
    
     
    	$videoSource = $vdo_name;

    }

		$json=array('message'=>'success','status'=>'1','url'=>"https://$server/Danda-Backend/uploads/watermark_videos/$videoSource");
	
	// echo "{\"response\":" . json_encode($json) . "}";

	// 	    $format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');
    //         $width=200;
    //         $height=200;


	// 	    if (!empty($watermark))
	// 	    {
	// 	        $video  ->filters()
	// 	                ->resize(new FFMpeg\Coordinate\Dimension($width, $height), FFMpeg\Filters\Video\ResizeFilter::RESIZEMODE_SCALE_HEIGHT)
	// 	                ->watermark($watermark, array(
	// 	                    'position' => 'relative',
	// 	                    'bottom' => 0,
	// 	                    'right' => 0,
	// 	                ));
		          
	// 	    }



	// 	    $format
	// 	    -> setKiloBitrate(1000)
	// 	    -> setAudioChannels(2)
	// 	    -> setAudioKiloBitrate(256);

	// 	//ffmpeg -i video.mp4 -i watermark.png -filter_complex "[1][0]scale2ref=w='iw*5/100':h='ow/mdar'[wm][vid];[vid][wm]overlay=10:10" output.mp4


	// 	    $randomFileName = rand().".$reqExtension";
	// 	    $path='/uploads/watermark_videos';
	// 	     if (!is_dir($path))
	// 	    {
	// 	        mkdir($path, 0777, true);
	// 	    }
	// 	    $saveLocation = getcwd(). $path.'/'.$randomFileName;
	// 	    $video->save($format, $saveLocation);
    // }else{
    // 	$randomFileName = $vdo_name;

    // }
    // //print_r($video);
	// $json=array('message'=>'success','status'=>'1','url'=>"https://$server/Danda-Backend/uploads/watermark_videos/$randomFileName");
	
	// echo "{\"response\":" . json_encode($json) . "}";

	

	// ///////////////
	// $sec = 3;
    //     $movie = $file;

    //     $dirPath = app()->basePath('public/events/thumbnail/'); //The directory where the setting file is saved
    //     if (!is_dir($dirPath)) {
    //         //Create a directory if the directory does not exist
    //         @mkdir($dirPath);
    //     }
    //     $name = 'thumb-' . time() . '.png';
    //     $thumbnail = $dirPath . $name;

    //     if (env('APP_ENV') == 'local') {
    //         //staging
    //         $ffmpeg = \FFMpeg\FFMpeg::create([
    //             'ffmpeg.binaries'  => 'C:\ffmpeg\bin\ffmpeg.exe', // the path to the FFMpeg binary
    //             'ffprobe.binaries' => 'C:\ffmpeg\bin\ffprobe.exe', // the path to the FFProbe binary
    //             'timeout'          => 3600, // the timeout for the underlying process
    //             'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
    //         ]);
    //     } else {
    //         //production
    //         $ffmpeg = \FFMpeg\FFMpeg::create([
    //             //Under linux
    //             'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
    //             'ffprobe.binaries' => '/usr/bin/ffprobe',

    //             'timeout'          => 3600, // the timeout for the underlying process
    //             'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
    //         ]);
    //     }

    //     $video = $ffmpeg->open($movie);
    //     $video
    //         ->filters()
    //         ->resize(new \FFMpeg\Coordinate\Dimension(320, 240))
    //         ->synchronize();
    //     $frame = $video->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
    //     $frame->save($thumbnail);
	
}

function delete_watermark_vdo($value='')
{
	global $pdo;
	extract($_REQUEST);
	
   //unlink old image
	@unlink(__DIR__."/uploads/watermark_videos/".$vdo_name);// /home/sammyekaran/public_html/api/uploads/watermark_video/

	$json=array('message'=>'success','status'=>'1');
	
	echo "{\"response\":" . json_encode($json) . "}";
}



function test_noti()
{

	global $pdo;
	extract($_REQUEST);
	if($device_type=='I')
	{
		$data=ios_customer($device_token,'asd','a','8','ddede','dedede','ddd','ddede','jhghujh');
	}else{
		 $data=android_noti($device_token,'asd','a','8','ddede','dedede','ddd','ddede','jhghujh');
		
	}

	print_r($data);
	echo "{\"response\":" . json_encode($data) . "}";
	

}

function sharePost($value='')
{
	global $pdo;
	extract($_REQUEST);
	$time=date("H:i:s");
	$date=date('y-m-d');
	$data=$_REQUEST['content'];
	$content = json_decode($data, true);
	//$tagUsers=explode(',',$tagUsers);

	$stmt=$pdo->prepare("select * from uploads where id=:post_id");
	$stmt->execute(array('post_id'=>$post_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    if($res)
    {

			$data2=array('user_id'=>$user_id,'uploads'=>$res['uploads'],'caption'=>$caption,'explicit'=>0,'upload_type'=>$res['upload_type'],'date'=>$date,'time'=>$time,'thumbnail'=>$res['thumbnail'],'post_parent'=>$res['user_id'],'post_id'=>$post_id);
	  		$stmt_insert=insert('uploads',$data2);
	  		if($stmt_insert)
	  		{
	  			
		  		$id=$pdo->lastInsertId();
		  		if($tagUsers)
	  			{
	  				$username=$res['fullname'];
	  				$stmt=$pdo->prepare("select * from register where user_id in('".$tagUsers."')");
					$stmt->execute(array());
				    $r=$stmt->fetchAll(PDO::FETCH_ASSOC);
				   	
				   	foreach($r as $res)
				    {
				    	$noti_type='tags';
				        $msg='you are mentioned in '.$userName.' post ';
				        if($res['device_type']=='A')
				        {
				             $data = array( 'registration_ids'  =>array($res['device_token']),'data' => array("message" =>$msg ,"noti_type" => $noti_type,'post_id'=>$id));
				             $noti=android_noti2($data);
				        }
				        else
				        {   
				            $data2=array('alert'=>$noti,'sound'=>'default',"noti_type" => $noti_type,'post_id'=>$postId);
				            $noti2=ios_notification($data2,$res['device_token'],$msg);   
				        }
				    }
	  			}
				/*foreach($content as $co)
				{
					$data=$co['data'];
					//$url=$co['url'];
					$price=$co['price'];
					$data2=array('upload_id'=>$id,'content'=>'','content_description'=>$data,'price'=>$price);
		  			$stmt_insert=insert('upload_content',$data2);	
				}*/
				$json=array('post_id'=>$id);
				$status='1';
				$message='success';
			}
			else
			{
				$json=new stdClass();
				$status='0';
				$message='something went wrong';
			}
		
		
	

	}else{
		//$json=new stdClass();
		$status='0';
		$message='Post not found';
	}

    $json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
	exit();
}

/*************************block list******************************************/
function block_list()
{
	global $pdo;
	global $server;
	extract($_REQUEST);

	$stmt_sel=$pdo->prepare("select b.to_userid as user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/Danda-Backend/uploads/profile/', r.profile_pic)),('')) AS profile_pic from block_users as b join register as r on r.user_id=b.to_userid where from_userid=:user_id");
    $stmt_sel->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	$json=array('status'=>'1','message'=>'success','data'=>$row_sel);
    }
    else
    {
    	$json=array('status'=>'0','message'=>'failure','data'=>array());
    }
   echo "{\"response\":" . json_encode($json) . "}";

}

/*********************ticket generate(support)********************************/
function genrateTicket()
{
	global $pdo;
	extract($_REQUEST);
    $stmt_sel=$pdo->prepare("select * from register where user_id=:user_id");
    $stmt_sel->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	//insert in supportchat
    	$data=array('user_id'=>$user_id,'query'=>$query,'user_name'=>$row_sel['fullname'],'user_email'=>$row_sel['email']);
	  	$stmt_insert=insert('support_chat',$data); 
	  	//insert supportchat2
	  	$data2=array('user_id'=>$user_id,'query_id'=>$stmt_insert,'message'=>$query); 
	  	$stmt_insert2=insert('support_chat2',$data2); 
	  	$subject='Danda support';
        $message="Hello ".$row_sel['fullname']."  we have successfully received your query and will get back to you within 48 hours Thank You.";

        if($stmt_insert2)
		{
			$json=array('message'=>'Ticket Genrated','status'=>'1','query_id'=>$stmt_insert2);

			if(!empty($row_sel['email']))
	        {
	        	$mail= sendemail($row_sel['email'],$subject,$message);
	        }
		}
		else
		{
			$json=array('status'=>'0','message'=>'Some server error.Try again!');
		}
    }
	else
	{
        $json=array('message'=>'No record found','status'=>'0');
    }
    echo "{\"response\":" . json_encode($json) . "}"; 
}
/***********************fetch ticket************************************/
function fetchTicket()
{
	global $pdo;
    extract($_REQUEST);

	$query_sel=   "select * from support_chat where user_id = :user_id";
    $stmt_sel=$pdo->prepare($query_sel);
    $array_sel=array(':user_id'=>$user_id);
    $stmt_sel->execute($array_sel);
    $row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	$json=array('status'=>'1','message'=>'success','data'=>$row_sel);
    }
    else
 	{
       $json=array('message'=>'No record found','status'=>'0');
  	}
  	echo "{\"response\":" . json_encode($json) . "}";  
}

function fetchUserChat()
{
	global $pdo;
    extract($_REQUEST);
    
    $stmt_sel=$pdo->prepare("select * from support_chat2 where query_id=:query_id");
    $stmt_sel->execute(array(':query_id'=>$query_id));
    $row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
    if($row_sel)
    {
		$json=array('status'=>'1','message'=>'success','data'=>$row_sel);
    }
    else
    {
        $json=array('message'=>'No record found','status'=>'0');
    }
    echo "{\"response\":" . json_encode($json) . "}";  
}
function UserChat()
{
	global $pdo;
    extract($_REQUEST);
    $query_sel="select * from support_chat where id=:query_id and user_id=:user_id";
    $stmt_sel=$pdo->prepare($query_sel);
    $array_sel=array(':query_id'=>$query_id,':user_id'=>$user_id);
    $stmt_sel->execute($array_sel);
    $row_sel=$stmt_sel->fetch(PDO::FETCH_ASSOC);

    if($row_sel)
    {
    	$data=array('user_id'=>$user_id,'query_id'=>$query_id,'message'=>$query ); 
	  	$stmt_insert=insert('support_chat2',$data); 
    
						
	    if($stmt_insert)
	    {
			$last_id=$stmt_insert;
			$stmt_sel=$pdo->prepare("select message,created,flag,query_id,id from support_chat2 where query_id=:query_id and id=:last_id");
			$stmt_sel->execute(array(':query_id'=>$query_id,':last_id'=>$last_id));
			$row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
			if($row_sel)
			{
				$json=array('message'=>'Ticket Genrated','status'=>'1','data'=>$row_sel);
			}
	    }
	    else
	    {
			$json=array('status'=>'0','message'=>'Some server error.Try again!');
		}
    }
    else
    {
    	$json=array('status'=>'0','message'=>'Input Error');
    }

    echo "{\"response\":" . json_encode($json) . "}";  

}

/*********************************block/unblock user************************/
function block_user()
{
	global $pdo;
	extract($_REQUEST);
	$stmt=$pdo->prepare("select * from register where user_id=:to_userid");
	$stmt->execute(array('to_userid'=>$to_userid));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
    	if($is_block=='1') //block
    	{
    		$stmt=$pdo->prepare("select * from block_users where to_userid=:to_userid and from_userid=:from_userid");
			$stmt->execute(array('to_userid'=>$to_userid,'from_userid'=>$from_userid));
		    $rslt=$stmt->fetch(PDO::FETCH_ASSOC);
		    if($rslt)
		    {
		    	$json=array('status'=>'0','message'=>'already block');
		    	
		    }
		    else
		    {
		    	$data2=array('to_userid'=>$to_userid,'from_userid'=>$from_userid);
	  			$stmt_insert=insert('block_users',$data2); 
				$json=array('status'=>'1','message'=>'success');
				if($stmt)
				{
					$data=array('user_id'=>$to_userid,'follower_id'=>$from_userid);
					$del=delete('followers', $data);

					$data=array('user_id'=>$from_userid,'follower_id'=>$to_userid);
					$delt=delete('followers', $data);
				}
		    }
    	}
    	elseif($is_block=='0') //unblock
    	{
    		$stmt=$pdo->prepare("select * from block_users where to_userid=:to_userid and from_userid=:from_userid");
			$stmt->execute(array('to_userid'=>$to_userid,'from_userid'=>$from_userid));
		    $rslt=$stmt->fetch(PDO::FETCH_ASSOC);
		    if($rslt)
		    {

		    	$data=array('to_userid'=>$to_userid,'from_userid'=>$from_userid);
				$del=delete('block_users', $data);
				$json=array('status'=>'1','message'=>'success');
		    }
		    else
		    {
		    	$json=array('status'=>'0','message'=>'already unblock');
		    }
    	}
    	else
    	{
    		$json=array('status'=>'0','message'=>'Invalid Input');
    	}
    }
    else
    {
    		$json=array('status'=>'0','message'=>'Invalid User');

    }
	echo "{\"response\":" . json_encode($json) . "}";
}

/***********************report reason insert********************************/
function add_reportReasons()
{
	global $pdo;
    extract($_REQUEST);
    
    $stmt_sel=$pdo->prepare("select * from report_reasons where id=:reason_id");
    $stmt_sel->execute(array('reason_id'=>$reason_id));
    $row_sel=$stmt_sel->fetchAll(PDO::FETCH_ASSOC);
   	// print_r($row_sel);die;
    if($row_sel)
    {
    	$data=array('reason_id'=>$reason_id,'to_userid'=>$to_userid,'from_userid'=>$from_userid,'post_id'=>$post_id ); 
	  	$stmt_insert=insert('reports',$data);
	  	$msg=($post_id=='') ? "profile":"post";
		$json=array('status'=>'1','message'=>'Thanks for reporting this '.$msg.'','data'=>$row_sel);
    }
    else
    {
        $json=array('message'=>'Invalid Input','status'=>'0');
    }
    echo "{\"response\":" . json_encode($json) . "}";  
}

function test_mail($value='')
{
	$data=sendemail('test@gmail.com', 'Hello', "hello howz u?");
    print_r($data); 
}


/*	----------------------------Change password------------------------------------*/
function changePass()
{
	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from register where password=:oldpassword and user_id=:user_id");
    $stmt->execute(array(':oldpassword'=>$oldpassword,':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	//print_r($row_sel);die;
	if($row_sel['password']!=$oldpassword)
	{
		$status='0';
		$message='invalid oldpassword';
		//$json= new stdClass();
	}
	else
	{
		if(strlen($newpassword) < '6')
		{
			$status='0';
			$message='Password must be more than 6 character';
		}
		else
		{
		
			$where=array('user_id'=>$user_id);
			$data=array('password'=>$newpassword);
			$stmt_updt=update_multi_where('register', $where, $data); 
			
			
			$status='1';
			$message='success';
		}
	}
	    $json=array('message'=>$message,'status'=>$status);
	    echo "{\"response\":" . json_encode($json) . "}";	
}

