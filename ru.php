<?php
/*phpinfo();
die;*/
include('config.php');
include('PHPMailer/PHPMailerAutoload.php');
include('helpers.php');
$server=$_SERVER['HTTP_HOST'];
$action=$_REQUEST['action'];
date_default_timezone_set("Asia/Calcutta");
$timestamp =date('d-M-y H:i');
require_once 'stripe-php/init.php'; 
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

	case 'changePass':
		  changePass();
		  break;
		  

	case 'forgot_password':
		  forgot_password();
		  break;
		  

	case 'forgetPasswordByContact':
		  forgetPasswordByContact();
		  break;

	case 'update_profile':
		  update_profile();
		  break;

	case 'fetch_profile':
		  fetch_profile();
		  break;


	case 'get_profile':
		  get_profile();
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

	case 'search_user':
		  search_user();
		  break;

	case 'post_upload':
		  post_upload();
		  break;

	case 'fetch_feed':
		  fetch_feed();
		  break;

	case 'detail_page':
		  detail_page();
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

	case 'get_membership':
		  get_membership();
		  break;

	case 'is_membership':
		  is_membership();
		  break;

	case 'get_noti':
		  get_noti();
		  break;

	case 'live_noti':
		  live_noti();
		  break;

	case 'callback_url':
		  callback_url();
		  break;

	case 'get_broadcastData':
		  get_broadcastData();
		  break;

	case 'thumbnail_url':
		  thumbnail_url();
		  break;

	case 'test_noti':   //25th api test ios noti
		  test_noti();
		  break;

	case 'test_noti1':   //25th api test ios noti
		  test_noti1();
		  break;

	case 'update_profile_pic':  
		  update_profile_pic();
		  break;

	case 'add_friendlist':  
		  add_friendlist();
		  break;

	case 'get_friendlist':  
		  get_friendlist();
		  break;

	case 'paypal_payment':  
		  paypal_payment();
		  break;

	case 'get_clientToken':  
		  get_clientToken();
		  break;

	case 'add_activeStatus':  
		  add_activeStatus();
		  break;

	case 'del_post':  
		  del_post();
		  break;

	case 'support_chat':
		  support_chat();
		  break;

	case 'block_user':
		  block_user();
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

	case 'UserChat':
		  UserChat();
		  break;

	case 'fetchUserChat':
		  fetchUserChat();
		  break;

	case 'get_reportReasons':
		  get_reportReasons();
		  break;

	case 'add_reportReasons':
		  add_reportReasons();
		  break;

	case 'check_contact':
		  check_contact();
		  break;

	case 'count_views':
		  count_views();
		  break;

	case 'simple_paypal':
		  simple_paypal();
		  break;

	case 'test':
		  test();
		  break;
		  
    case 'testLive':
   		testLive();
        break;

    case 'exploreData':
   		exploreData();
        break;

    case 'tagSuggestions':
   		tagSuggestions();
        break;

    case 'saveStripeId':
   		saveStripeId();
        break;

    case 'splitPay':
   		splitPay();
        break;

    case 'sendShippingAddress':
   		sendShippingAddress();
        break;

    case 'followSubscription':
   		followSubscription();
        break;

    case 'purchaseFollowSubscription':
   		purchaseFollowSubscription();
        break;

    case 'delStripeAccount':
	     delStripeAccount();
	     break;

    case 'checkAddStripeAccount':
	     checkAddStripeAccount();
	     break;


	default:
        echo "Not Found!";      

}

function checkAddStripeAccount()
{
	global $pdo;
	extract($_REQUEST);
	$stmt=$pdo->prepare("select connectStripeId from register where user_id=:user_id");
    $stmt->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($row_sel['connectStripeId']))
    {
		$json=array('message'=>'Artist has not added his bank detail.','status'=>'0');	
    }
    else
    {
    	$json=array('message'=>'Artist already added his bank detail','status'=>'1');
    }
    echo "{\"response\":" . json_encode($json) . "}";
}
function delStripeAccount()
{
	global $pdo;
	extract($_REQUEST);
	require_once 'stripe-php/init.php'; 
	require_once 'stripe-payment/vendor/autoload.php';

	\Stripe\Stripe::setApiKey("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");

	$stmt=$pdo->prepare("select connectStripeId from register where user_id=:user_id");
    $stmt->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($row_sel['connectStripeId']))
    {
		$json=array('message'=>'Artist has not added his bank detail.','status'=>'0');	
    }
    else
    {
    	
    	$account=\Stripe\OAuth::deauthorize([
		  'client_id' => 'ca_HeLr0OZsb7k8sniBefkDEBheZqyrPkaE',
		  'stripe_user_id' => $row_sel['connectStripeId'],
		]);
		
    	if(empty($account))
    	{
    		$json=array('message'=>'This application is not connected to stripe account,or that account does not exist.!!','status'=>'0');
	    	
		}
    	else
    	{
    		$stmt_upd=$pdo->prepare("update register set connectStripeId=:connectStripeId where user_id=:user_id");
			$array_upd=array(':connectStripeId'=>'',':user_id'=>$user_id);
			$stmt_upd->execute($array_upd);
			$json=array('message'=>'Your account is successfully deleted','status'=>'1');
    	}
    		
	}
	echo "{\"response\":" . json_encode($json) . "}";
    	

}
function purchaseFollowSubscription()
{
	global $pdo;
	extract($_REQUEST);
	require_once 'stripe-php/init.php'; 
	require_once 'stripe-payment/vendor/autoload.php';
	
	$stmt=$pdo->prepare("select connectStripeId from register where user_id=:user_id");
    $stmt->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($row_sel['connectStripeId']))
    {
		$json=array('message'=>'Artist has not added his bank detail.','status'=>'0','data'=>'');	
    }
    else
    {

    	$amount=round($amount*100);
    
    	$stripeId=$row_sel['connectStripeId'];

    	//Stripe\Stripe::setApiKey("sk_test_51H00NpEvRdAFcIaCSpuoBXvA6WxMFh0kBt7JD07Kn4yvYqiWMu70dxRrefMTbdyNv2mjbzFzRkpV1q2cLx2lYHUs003aFMe95T");
    	//Stripe\Stripe::setApiKey("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");
    	Stripe\Stripe::setApiKey("sk_test_51H538PKI2f6ZXtXBTVKCcKpSt9WibnPQDCIlAUtdrmOCnvT37RMNsphG0e8YhnL0sEKASDTLTABjX4hM8zw6fLvD00OBEKhInw");

    	$stripeId=$row_sel['connectStripeId'];
    	//generate token
    	$token = \Stripe\Token::create([
		  "card" => array(
		    "number" => $cardNumber,
		    "exp_month" => $expMonth,
		    "exp_year" => $expYear,
		    "cvc" => $cvc
		  )
		]);

		//create customer	
		$customer = \Stripe\Customer::create(array( 
	                                              'email' => $email, 
	                                              'source' => $token[id], 
	                                               'name' => $userName,
	                                               'address' => [
                                                                  'line1' => $line1,
                                                                  'postal_code' => $postal_code,
                                                                  'city' => $city,
                                                                  'state' => $state,
                                                                  'country' => $country,
                                                                ],
	                                          )); 
		//create product
	    $product = \Stripe\Product::create([
											  'name' =>$subscriptionName,
											]);
		$id=$product[id];
		//create price	
		$price = \Stripe\Price::create([
										  'product' => $id,
										  'unit_amount' => $amount,
										  'currency' => 'usd',
										  'recurring' => [
														    'interval' => 'month',
														   ],
										]);
		
		//create invoice	
		/*$invoice_item = \Stripe\InvoiceItem::create([
		  'customer' => 'cus_Hcr1lnmlJSdEyH',
		 // 'price' => 'price_1H3O1yEvRdAFcIaChszFXmoa',
		  'currency'=>'usd',
		  'amount'=>2000
		]);*/
		//create subscription
		
		if($isSpitPayment==1)
		{
			/*try
			{*/
				$subscription = \Stripe\Subscription::create([
														  "customer" => $customer->id,
														  "items" => [
														    			["price" => $price->id],
														 	 		],
														  "expand" => ['latest_invoice.payment_intent'],
														  "application_fee_percent" => 20,
														  'transfer_data' =>[
																				'destination' =>$stripeId,
																			]
														]);
		/*}
		catch (\Stripe\Error\OAuth\InvalidGrant $e) {
		    return $response->withStatus(400)->withJson(array('error' => 'Invalid authorization code: ' . $authCode));
		  } catch (Exception $e) {
		    return $response->withStatus(500)->withJson(array('error' => 'An unknown error occurred.'));
		  }*/
			/*$subscriptionType=($subscriptionName=='vip')?'1':'0';
			followSubscription($user_id,$fromUserId,$subscriptionType);*/
		}
		else
		{
			$subscription = \Stripe\Subscription::create([
														  "customer" => $customer->id,
														  "items" => [
														    			["price" => $price->id],
														 	 		],
														  "expand" => ['latest_invoice.payment_intent'],
														  'transfer_data' =>[
																				'destination' =>'acct_1GwdKVD91fIBI4zJ',
																			]
														]);
			
		}
			$subscriptionType=($subscriptionName=='vip')?'1':'0';
			$json=followSubscription($user_id,$fromUserId,$subscriptionType,$subscription[id]);
		//$json=array('message'=>'success','status'=>'1','data'=>$subscription[id]);
	}
	/*else
	{
		$json=array('message'=>'something went wrong','status'=>'0','data'=>'');
	}*/
	echo "{\"response\":" . json_encode($json) . "}";
}


/////////////follow Subscription////////////////////
function followSubscription($toUserId,$fromUserId,$subscriptionType,$subscriptionId)
{
	global $pdo;
	extract($_REQUEST);
	$statDate=date('Y-m-d');
	$endDate=date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 month" ) );
	//$endYear=date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "+1 year" ) );

	$stmt=$pdo->prepare("select * from followSubscription where toUserId=:toUserId and fromUserId=:fromUserId"); 
    $stmt->execute(array(':toUserId'=>$toUserId,':fromUserId'=>$fromUserId));
    $rspns=$stmt->fetch(PDO::FETCH_ASSOC);
    if($rspns)
    {
    	if(strtotime($rspns['endDate']) > strtotime(date('Y-m-d')))
    	{
    		$msg="you already have subscription till ".$rspns['endDate'];
    		$json=array('status'=>'0','message'=>$msg);
    	}
    	else
    	{
    		//print_r($subscriptionType);die;
	  		/*$where=array('toUserId'=>$toUserId,'fromUserId'=>$fromUserId);
			$data1=array('subscriptionType'=>$subscriptionType,'startDate'=>$statDate,'endDate'=>$endDate);
			$stmt_updt=update_multi_where('followSubscription', $where, $data1); */
			
			$stmt_upd=$pdo->prepare("update followSubscription set subscriptionId=:subscriptionId,subscriptionType=:subscriptionType,startDate=:startDate,endDate=:endDate where toUserId=:toUserId and fromUserId=:fromUserId");
			$array_upd=array(':subscriptionId'=>$subscriptionId,':subscriptionType'=>$subscriptionType,':startDate'=>$startDate,':endDate'=>$endDate,':toUserId'=>$toUserId,':fromUserId'=>$fromUserId);
			$stmt_upd->execute($array_upd);

			
	  		//$json=array('status'=>'1','message'=>"you have purchased membership successfully ");
    	}
    	$json=array('message'=>'success','status'=>'1','data'=>$subscriptionId);
			return $json;
    }
    else
    {
    	$data2=array('subscriptionId'=>$subscriptionId,'toUserId'=>$toUserId,'fromUserId'=>$fromUserId,'subscriptionType'=>$subscriptionType,'startDate'=>$statDate,'endDate'=>$endDate);
  		$stmt_insert=insert('followSubscription',$data2);
  		$json=array('message'=>'success','status'=>'1','data'=>$subscriptionId);
  		return $json;
  		//$json=array('status'=>'1','message'=>"you have purchased membership successfully ");
    }
  //	echo "{\"response\":" . json_encode($json) . "}";

}
//////////////////add shipping address//////////////
function sendShippingAddress()
{
	global $pdo;
	extract($_REQUEST);
	$date=date('Y-m-d');
	    		
	$stmt=$pdo->prepare("select r.email,uc.price,r.user_id from uploads as u join register as r on r.user_id=u.user_id join upload_content as uc on u.id=uc.upload_id where u.id=:post_id"); 
    $stmt->execute(array('post_id'=>$post_id));
    $rspns=$stmt->fetch(PDO::FETCH_ASSOC);
   // print_r($rspns);
    $seller=$rspns['email'];
    //$seller="aroranits0895@gmail.com";
    $message1="You have successfully ordered the  Product  of cost ". $rspns['price']. "$ ,and will be delivered soon ,for any queries please contact us on this emali (". $rspns['email'] .") .<br><br>
    			Order Detail:<br>
				Orderno: ".$rspns['upload_id']."<br>
				Thank you.";
	$stmt=$pdo->prepare("select email,fullname,user_id from register where user_id=:user_id"); 
    $stmt->execute(array('user_id'=>$user_id));
    $rspns1=$stmt->fetch(PDO::FETCH_ASSOC);
    $buyer=$rspns1['email'];
   // $buyer="neetu2294garg@gmail.com";
	$message2="Product  is successfully ordered by ".$rspns1['fullname']. " on ".$date ."<br><br>
				Order Detail:<br>
				Orderno: ".$rspns['upload_id']."<br>
				email  :- " .$rspns1['email']. "<br>


				Delivery address:<br>
				User address:-  ".$address."<br><br>
				Billing Detail:<br><br>

				cost:- ". $rspns['price']."$<br>
				
				Thank you";

	$data=array('order_id'=>$post_id,'seller_id'=>$rspns['user_id'],'buyer_id'=>$rspns1['user_id'],'price'=>$rspns['price']);
	$stmt_insert=insert('purchaseProduct',$data);

    $d=sendConfirmationMessage($seller,$message2);
    $d=sendConfirmationMessage($buyer,$message1);
    if($d)
	{
		echo "{\"response\":" . json_encode($d) . "}";
	}     
}
function sendConfirmationMessage($email,$message)
{
	global $pdo;
	extract($_REQUEST);
	$to = $email;
    //$to = 'neetu2294garg@gmail.com';
       
        $subject = 'rawuncensored(Order informationn)';
        $from = 'support@rawuncensored.com';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
                    'Reply-To: '.$from."\r\n" .
                    'X-Mailer: PHP/' . phpversion();
 
        // Compose a simple HTML email message
        //$message = '<html><body>';
        $message = "<html><head></head><body>Hello,<br/> ".$message."</body></html>";
        //$message .= '</body></html>';
        $mail = new PHPMailer;
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'mail.rawuncensored.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'support@rawuncensored.com';
        $mail->Password = 'q8Y;HC&J;On&'; 
        $mail->SMTPSecure = 'STARTTLS';
        $mail->Port = 587;
        $mail->setFrom($from, 'RawUncensored');
        $mail->addReplyTo($to);
 
        // Sending email
        if(mail($to, $subject, $message, $headers))
        {
           $json=array('status'=>'1','message'=>"your order placed successfully.");
           return $json;
        } 
        else
        {
             $json=array('status'=>'0','message'=>"Something went wrong");
             return $json;
        }
        
}
////////////////get stripe id////////////////
function saveStripeId()
{
	global $pdo;
	extract($_REQUEST);
	//require_once 'vendor/autoload.php';
	require_once 'stripe-php/init.php'; 
	if($authCode)
	{
		\Stripe\Stripe::setApiKey("sk_test_51H538PKI2f6ZXtXBTVKCcKpSt9WibnPQDCIlAUtdrmOCnvT37RMNsphG0e8YhnL0sEKASDTLTABjX4hM8zw6fLvD00OBEKhInw");
		//\Stripe\Stripe::setApiKey("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");

		$response = \Stripe\OAuth::token([
		  'grant_type' => 'authorization_code',
		  'code' => $authCode,
		]);
		if($response)
		{
			$connected_account_id = $response->stripe_user_id;
		
			$stmt_upd=$pdo->prepare("update register set connectStripeId=:connected_account_id where user_id=:user_id ");
			$array_upd=array(':connected_account_id'=>$connected_account_id,':user_id'=>$user_id);
			$stmt_upd->execute($array_upd);
			$json=array('message'=>'Connected with Stripe Successfully','status'=>'1','data'=>$connected_account_id);	
		}
		else
		{
			$json=array('message'=>'Something went wrong','status'=>'0','data'=>'');	
		}
	}
	else
	{
		$json=array('message'=>'Invalid Auth Code ','status'=>'0','data'=>'');
	}
	echo "{\"response\":" . json_encode($json) . "}";

}
//////////////////////////////////////////////
function splitPay()
{
	global $pdo;
	extract($_REQUEST);

	require_once 'stripe-php/init.php'; 
	$stmt=$pdo->prepare("select connectStripeId,r.fullname from register as r join uploads as u on u.user_id=r.user_id where  u.id=:post_id");
    $stmt->execute(array(':post_id'=>$post_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($stmt);die;
    $amount=round($amount*100);
    $transferAmount=($amount*80)/100;
    //print_r(expression)
    if($row_sel)
    {
    	$stripeId=$row_sel['connectStripeId'];

		\Stripe\Stripe::setApiKey("sk_test_51H538PKI2f6ZXtXBTVKCcKpSt9WibnPQDCIlAUtdrmOCnvT37RMNsphG0e8YhnL0sEKASDTLTABjX4hM8zw6fLvD00OBEKhInw");
		//\Stripe\Stripe::setApiKey("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");

		$paymentIntent = \Stripe\PaymentIntent::create([
				
														  'payment_method_types' => ['card'],
														  'amount' => $amount,
														  'currency' => 'usd',
														  "description" => "Test payment from stripe.test." , 
														  'shipping' => [
															    'name' => $row_sel['fullname'],
															    'address' => [
															      'line1' => $line1,
															      'postal_code' => $postal_code,
															      'city' => $city,
															      'state' => $state,
															      'country' => $country,
															    ],
															  ],
														  //'application_fee_amount' =>$transferAmount,
														  'transfer_data' => [
																			    'amount' =>$transferAmount,
																			    'destination' => $stripeId,
																			]

				]);
		$json=array('message'=>'success','status'=>'1','data'=>$paymentIntent[client_secret]);
	}
	else
	{
		$json=array('message'=>'failure','status'=>'0','data'=>'');
	}

echo "{\"response\":" . json_encode($json) . "}";
	
}
////////////////tag suggestion list////////////////
function tagSuggestions()
{
	global $pdo;
	extract($_REQUEST);	
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("SELECT username,user_id,fullname,if((profile_pic!=''),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic FROM register WHERE fullname LIKE '%".$name."%'or username LIKE '%".$name."%' limit 50");
	$stmt->execute(array());
	$result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	//print_r($result);die;
	if($result)
	{
		$json=array('message'=>'success','status'=>'1','data'=>$result);
	}
	else
	{
		$json=array('message'=>'failure','status'=>'0','data'=>array());
	}
	echo "{\"response\":" . json_encode($json) . "}";
}
/*	----------------------------register------------------------------------*/

function testLive()
{
	global $pdo;
    extract($_REQUEST);
      $user_id=1;
		$request=file_get_contents('php://input');
		$array=json_decode($request,true);
		$status = $array['payload']['type'];
		$status=($status=='live')?'1':'0';
		$broadcast_id = $array['payload']['id'];
		$encodeUrl = urlencode($array['payload']['resourceUri']);
		$url="https://dist.bambuser.net/player/?resourceUri=".$encodeUrl."&showViewerCount=1";
		$data=array('status'=>$status,'broadcast_id'=>$broadcast_id,'b_url'=>$url);

       $stmt=$pdo->prepare("select * from admin_live where  broadcast_id=:broadcast_id");
		$ar=array(':broadcast_id'=>$broadcast_id);
		$stmt->execute($ar);
		$rsult=$stmt->fetch(PDO::FETCH_ASSOC);
		if(!empty($rsult))
		{	
			
			/*$stmt=$pdo->prepare("delete from admin_live where user_id=:user_id and broadcast_id=:broadcast_id");
				$ar=array('user_id'=>$user_id,':broadcast_id'=>$broadcast_id);
				$stmt->execute($ar);*/
				
			//print_r('update');die;
			$where=array('broadcast_id'=>$broadcast_id);
			$stmt_updt=update_multi_where('admin_live', $where, $data); 


			$player=($status=='1')?'2':'0';
			$where2=array('id'=>'1');
			$data2=array('player'=>$player);
			 
			$stmt_updt=update_multi_where('link_upload', $where2, $data2); 
			//$stmt_insert=insert('admin_live',$data);
			echo "<meta http-equiv='refresh' content='3' />";
			//echo '<meta http-equiv="refresh" content="0">';

		}
		


		
}


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

	  /*  $stmt=$pdo->prepare("select * from register where contact=:contact");
	    $stmt->execute(array(':contact'=>$contact));
	    $row_sel1=$stmt->fetch(PDO::FETCH_ASSOC);
	    if($row_sel1)
	    {
		    $status='0';
		    $message='Contact already exist';
			$json=new stdClass();
	    }
	    else
	    {*/
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
    	//}
    }

	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}

/*	----------------------------Login------------------------------------*/
function login()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$input= is_numeric ($_REQUEST['email']);
	if($input)
	{
	    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where contact=:contact");
	    $stmt->execute(array(':contact'=>$email));
	    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	    //print_r($input);die;
	    //$msz="Contact not found !";
	}
	else
	{
	    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where email=:email");
	    $stmt->execute(array(':email'=>$email));
	    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	   // $msz="Email not found !";
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
	$json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}

/*	----------------------------Logout------------------------------------*/
function logout()
{
	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
    $stmt->execute(array(':user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
    if($row_sel)
    {
    	/*$where=array('user_id'=>$user_id,'deviceId'=>$deviceId);
		$data=array('device_type'=>'','device_token'=>'','fcm_token'=>'');
		$stm=update_multi_where('register', $where, $data); */
		$stmt_upd=$pdo->prepare("update register set device_type='',device_token='',fcm_token='',deviceId='' where user_id=:user_id and deviceId=:deviceId");
		$array_upd=array(':user_id'=>$user_id,':deviceId'=>$deviceId);
		$stmt_upd->execute($array_upd);
		//if($stmt_upd->rowCount())
		
		$json=array('message'=>'success','status'=>'1','data'=>[]);	
		
    }
    else
    {
    	$json=array('message'=>'Invalid User','status'=>'0','data'=>[]);	
    }
	echo "{\"response\":" . json_encode($json) . "}";	
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

/*	----------------------------forget password------------------------------------*/
function forgot_password()
{

	global $pdo;	
	extract($_REQUEST);

    $stmt=$pdo->prepare("select * from register where email=:email");
    $stmt->execute(array(':email'=>$email));
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($row);die;
	if($row)
	{
		//$chars = "0123456789";
		//$pass = substr( str_shuffle( $chars ), 0, 6 ); //generate random password
	    if(!empty($row['password']))
		{
			
			$to = $email;
		   
		    $subject = 'Forgot Password';
		    $from = 'support@rawuncensored.com';

		    // To send HTML mail, the Content-type header must be set
		    $headers  = 'MIME-Version: 1.0' . "\r\n";
		    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     
    		// Create email headers
    		$headers .= 'From: '.$from."\r\n".
        				'Reply-To: '.$from."\r\n" .
        				'X-Mailer: PHP/' . phpversion();
     
    		// Compose a simple HTML email message
    		//$message = '<html><body>';
    		$message = "<html><head></head><body>Hello ".$row['fullname']."<br>Your password is : ".$row['password']."</body></html>";
    		//$message .= '</body></html>';
     		$mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'mail.rawuncensored.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'support@rawuncensored.com';
            $mail->Password = 'q8Y;HC&J;On&'; 
            $mail->SMTPSecure = 'STARTTLS';
            $mail->Port = 587;
            $mail->setFrom($from, 'RawUncensored');
            $mail->addReplyTo($to);
     
			// Sending email
			if(mail($to, $subject, $message, $headers))
			{
				/*$where=array('email'=>$to);
				$data=array('password'=>$pass);
				$stmt_updt=update_multi_where('register', $where, $data);*/ 

			   $json=array('status'=>'1','message'=>"Your password has been sent to your email-id.");
			} 
			else
			{
			   	   $json=array('status'=>'0','message'=>"Some server error.Try gain!");
			}
			//$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
			/*$chars = "0123456789";
			$pass = substr( str_shuffle( $chars ), 0, 5 ); //generate random password
			
			$to =$email;
			$subject = 'Forgot Password';
			$headers  = "MIME-Version: 1.0\r\n";
			$headers .= "Content-type: text/html; charset: utf8\r\n";
			$headers .= "From:<support@rawuncensored.com>";
			//$headers .= "From: <neetu@appzorro.com>";
			$message = "<html><head></head><body>Hello ".$row['fullname']."<br>Your password is : ".$pass."</body></html>";

		    $mail = new PHPMailer;
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'mail.rawuncensored.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'support@rawuncensored.com';
            $mail->Password = 'q8Y;HC&J;On&';
            $mail->SMTPSecure = 'STARTTLS';
            $mail->Port = 587;
            $mail->setFrom('support@rawuncensored.com', 'RawUncensored');
            $mail->addReplyTo($to);
			
			

			// Add a recipient
			$mail->addAddress($to);
			$mail->Subject = 'Forgot Password';
		   
			// Set email format to HTML
			$mail->isHTML(true);

			// Email body content
			$mailContent = "<html><head></head><body>Hello ".$row['fullname']."<br>Your password is : ".$pass."</body></html>";
			$mail->Body = $mailContent;
		    if(!$mail->send()) 
		    {
			   $json=array('status'=>'0','message'=>"Some server error.Try gain!");
			   echo 'Mailer Error: ' . $mail->ErrorInfo;
		    } 
		    else 
		    {
				$where=array('email'=>$to);
				$data=array('password'=>$pass);
				$stmt_updt=update_multi_where('register', $where, $data); 
				//print_r($where);die;
				
			   $json=array('status'=>'1','message'=>"Your password has been sent to your email-id.");
		    }*/
		    // $json=array('status'=>'1','message'=>"Your password has been sent to your email-id.");
	   }
	   else
	   {
	   		$json=array('status'=>'0','message'=>"Password is empty");
	   }
	}   
	else
	{
		$json=array('status'=>'0','message'=>"Email-Id does not exist");   	    	
	}
	echo "{\"response\":" . json_encode($json) . "}";

}

/*******forget password by contact*****/
function forgetPasswordByContact()
{
	global $pdo;
	extract($_REQUEST);
	
    $stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) from register where contact=:contact");
    $stmt->execute(array(':contact'=>$contact));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
	if($row_sel)
	{
		
			$where=array('contact'=>$contact);
			$data1=array('password'=>$password);
			$stmt_updt=update_multi_where('register', $where, $data1);
			if($stmt_updt)
			{
				$json=array('status'=>'1','message'=>'password reset successfully','data'=>array('email'=>$row_sel['email'],'password'=>$row_sel['password']));
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
/***************************************/

/*	---------------------------- profile update------------------------------------*/

function update_profile1()
{
	
	global $pdo;
	extract($_REQUEST);

 	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
    $stmt->execute(array('user_id'=>$user_id));
    $row_sel=$stmt->fetch(PDO::FETCH_ASSOC);
   // print_r($row_sel);die;
	if($row_sel)
	{
		
        if($_REQUEST['profile_pic']!= '')
        {
        	$ProfileImage = $_REQUEST['profile_pic'];
			$path=dirname(__FILE__)."/uploads/profile/";
			$profile_pic=upload_base64_image($ProfileImage,$path);

        }
		else
		{
        	$profile_pic=$row_sel['profile_pic'];
        }
        $stmt=$pdo->prepare("select * from register where username=:username");
	    $stmt->execute(array('username'=>$username));
	    $output=$stmt->fetch(PDO::FETCH_ASSOC);
	    if($output)
	    {

			$json['status']='1';
			$json['message']='enter unique username';
	    }
	    else
	    {
	    	$username=$username;
	    }

		
			$where=array('user_id'=>$user_id);
			$data=array('profile_pic'=>$profile_pic,'fullname'=>empty($name)?$row_sel['fullname']:$name,'username'=>empty($username)?$row_sel['username']:$username,'email'=>empty($email)?$row_sel['email']:$email,'country_code'=>empty($country_code)?$row_sel['country_code']:$country_code,'contact'=>empty($contact)?$row_sel['contact']:$contact,'gender'=>empty($gender)?$row_sel['gender']:$gender,'bio'=>empty($bio)?$row_sel['bio']:$bio,'website'=>empty($website)?$row_sel['website']:$website);
			$stmt_updt=update_multi_where('register', $where, $data); 

			$json['status']='1';
			$json['message']='Update successfully';
			
	}
	else
	{	
	    $json=array('status'=>'0','message'=>'No record found');	
	}
	 echo "{\"response\":" . json_encode($json) . "}";
}

/*	---------------------------- profile update------------------------------------*/

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
	        	
				$image_name=$_FILES['profile_pic']['name'];
				$tmp = explode('.', $image_name);
	    		$fileExtension = end($tmp);
	 			$target_dir = "uploads/profile/";
	          	$image_name='img_'.rand().'.'.$fileExtension;
				$img_temp_name=$_FILES['profile_pic']['tmp_name'];
			  	$target_file = $target_dir . basename($image_name);
	    		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	    		if (move_uploaded_file($img_temp_name, $target_file)) 
			    {
			       $profile_pic=$image_name;
			    }
			    else 
			    {
			         $profile_pic='';
			    }
	        }
			else
			{
	        	$profile_pic=$row_sel['profile_pic'];
	        }
	       
	  
	         // print_r($profile_pic);die;
			$where=array('user_id'=>$user_id);
			//$data=array('profile_pic'=>$profile_pic,'fullname'=>empty($name)?$row_sel['fullname']:$name,'username'=>empty($username)?$row_sel['username']:$username,'website'=>empty($website)?$row_sel['website']:$website,'bio'=>empty($bio)?$row_sel['bio']:$bio,'email'=>empty($email)?$row_sel['email']:$email,'contact'=>empty($contact)?$row_sel['contact']:$contact,'gender'=>empty($gender)?$row_sel['gender']:$gender,'paypal_id'=>empty($paypal_id)?$row_sel['paypal_id'] :$paypal_id);
			$data=array('profile_pic'=>$profile_pic,'fullname'=>$name,'username'=>$username,'website'=>$website,'bio'=>$bio,'email'=>$email,'country_code'=>$country_code,'contact'=>$contact,'gender'=>$gender,'paypal_id'=>$paypal_id,'countryIso'=>$countryIso);
			$updata_data=update_multi_where('register', $where, $data); 
			if($updata_data=1)
			{
				$pp="https://".$server."/uploads/profile/".$profile_pic;
				$data=array('profile_pic'=>$pp,'fullname'=>$name,'username'=>$username,'website'=>$website,'bio'=>$bio,'email'=>$email,'contact'=>$contact,'gender'=>$gender,'paypal_id'=>$paypal_id);
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

/*	----------------------------get profile ------------------------------------*/
function fetch_profile()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	/*$stmt=$pdo->prepare("select if((profile_pic!=''),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) as profile_pic,username,fullname,website,bio,email,password,country_code,contact,gender,paypal_id,countryIso from register where user_id=:user_id 
		");*/
	$stmt=$pdo->prepare("select if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) as profile_pic,username,fullname,website,bio,email,password,country_code,contact,gender,paypal_id,countryIso from register where user_id=:user_id 
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

/*	---------------------------- fetch profile feed------------------------------------*/
function get_profile_pic($avatar)
{
	   $server=$_SERVER['HTTP_HOST'];
	   $url = $avatar;
		if (strpos($url, "https")!==false){
		    //echo "Car here";
		    $profile_pic=$avatar;
		}
		else {
		   //echo "No car here :(";
		   $profile_pic="https://$server/uploads/profile/".$avatar;
		}

		return $profile_pic;

}

function get_profile()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	/*$stmt=$pdo->prepare("select if((r.profile_pic!=''),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) as profile_pic,r.website,r.fcm_token,r.bio,r.username,r.fullname,count(f.id) as followers from register as r left join followers as f on f.user_id=r.user_id where r.user_id=:user_id");*/

	$stmt=$pdo->prepare("select if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic,r.website,r.fcm_token,r.bio,r.username,r.fullname,count(f.id) as followers from register as r left join followers as f on f.user_id=r.user_id where r.user_id=:user_id");
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
	$connected_with_stripe=$stripeKey['connectStripeId']?1:0;
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
	  
    	$stmt=$pdo->prepare("select id,upload_type,if((explicit=2),('1'),('0')) as explicit,if((upload_type='I'),(concat('https://".$server."/uploads/images/', uploads)),(concat('https://".$server."/uploads/videos/', uploads))) AS post_url,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url  from uploads where user_id=:user_id and uploads!='0'  order by id desc ");
		$stmt->execute(array('user_id'=>$user_id));
	    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	    //print_r($stmt);die;

	    $stmt=$pdo->prepare("select count(id) as posts_count from uploads where user_id=:user_id and uploads!='0' ");
		$stmt->execute(array('user_id'=>$user_id));
	    $reslt=$stmt->fetch(PDO::FETCH_ASSOC);
	   	$posts=$result?$result:array();

	   	$copy_url="https://rawuncensored.com/profile_copy/".$user_id."";
	   //	print_r($copy_url);die;
	    $json=array('username'=>$res['username'],
	    			'user_id'=>$user_id,
	    			'name'=>$res['fullname'],
	    			'profile_pic'=>$profile_pic,
	    			//'username'=>$res['username'],
	    			'website'=>$res['website'],
	    			'copy_url'=>($from_userid!='')? $copy_url: '',
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

/*	---------------------------- followers------------------------------------*/

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
					
						$data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $noti,"noti_type" => $noti_type,"follower_id"=>$res['user_id'],"user_id"=>$row_sel['user_id'],"username"=>$row_sel['username'], "profile_pic"=>$result['profile_pic'],'noti_count'=>$ncount));
						$noti=android_noti2($data);
						//print_r($noti);die;
						
					}
					else
					{ 					
						//print_r('i');
						$data2=array('alert'=>$noti,'sound'=>'default','badge'=>intval($ncount),'message'=>$noti,'type'=>$noti_type,'follower_id'=>$res['user_id'],'user_id'=>$row_sel['user_id'],'username'=>$row_sel['username'],'profile_pic'=>$result['profile_pic'],'noti_count'=>$ncount);

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

}

/*	---------------------------- confirm followers------------------------------------*/

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

/*	---------------------------- follow List------------------------------------*/
function follow_list()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
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
	    		$sql="select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') and (fullname like '%".$search."%' or username like '%".$search."%' )";
		    	$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
		     	$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		     	//print_r($row);die;
		     	/*------pagination---------*/
		     	$totalCount = $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') and (fullname like '%".$search."%' or username like '%".$search."%' ) LIMIT $starting_limit,$size");
				$ar=array();
				$stmt1->execute($ar);
				/*--------------------------*/
	    	}
	    	else
	    	{
	    		$sql="select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') ";
		    	$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
		     	$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		     	//print_r($sql);die;
		     	/*------pagination---------*/
		     	$totalCount = $stmt->rowCount();
		    	$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) from register where user_id IN('".implode("','",$d)."') LIMIT $starting_limit,$size");
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
				   // $profile_pic=get_profile_pic($r['profile_pic']);
		    		$data[] =array('user_id'=>$r['user_id'],
		    					'username'=>$r['username'],
		    					'fullname'=>$r['fullname'],
		    					'profile_pic'=>$r['profile_pic'],
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

/*	---------------------------- search_user------------------------------------*/
function search_user()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
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
		    }

	    	if($ar_mrg)
	    	{
	   		 	foreach($ar_mrg as $a)
	   		 	{
	   		 		$thh[]=implode('',$a);
	   		 	}
	   		 	$th=implode(',',$thh);
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
   		 	$sql="select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and r.user_id not IN(".$sugges.") group by user_id order by count desc";
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
		    	$stmt1=$pdo->prepare("select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where  r.user_id!=:user_id and r.user_id not IN(".$sugges.") group by user_id order by count desc LIMIT $starting_limit, $size");
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
				//$sql="select user_id,username,fullname,if((profile_pic!=''),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) AS profile_pic from register where user_id!=:user_id  limit 20 ";
				$sql="select user_id,username,fullname,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) AS profile_pic from register where  user_id NOT IN(".$user_mrg.") limit 50 ";
		  		$stmt=$pdo->prepare($sql);
				$stmt->execute(array());
			    $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
			   // print_r($json);die;
				
				     /**********pagination***********/
		    		$totalCount = $stmt->rowCount();
		    		$total_pages = ceil($totalCount/$size);
			    	$starting_limit=pagination('50',$pageNo,$size);
			    	$stmt1=$pdo->prepare("select user_id,username,fullname,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) from register where user_id NOT IN(".$user_mrg.")  LIMIT $starting_limit, $size");
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
                        $profile_pic= $r['profile_pic'];
                       // $profile_pic= get_profile_pic($r['profile_pic']);

						$data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'user_type'=>$user_type);
					}
					//print_r($data);
				    $json=array('message'=>'Follow people to start seeing the photos and videos they share','status'=>'1','current_page'=>$pageNo,'page_size'=>$size,'total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'data'=>$data); 

			}
		}
		else
		{
			$block_users=empty($block_users)?'0':implode(',',$block_users);

			//$stmt=$pdo->prepare("select r.user_id,r.username,r.fullname,if((profile_pic!=''),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where r.fullname like '%".$search."%' or r.username like '%".$search."%'  and r.user_id!=:user_id  group by r.user_id");
			$qry="select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where ((r.user_id not IN(".$block_users.")) or r.user_id=:user_id) and (r.fullname like '%".$search."%' or r.username like '%".$search."%')   group by r.user_id ";
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
		    	$stmt1=$pdo->prepare("select r.user_id,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic ,f.follower_id,f.user_id as following FROM register as r left JOIN followers as f on (r.user_id=f.user_id and f.follower_id=:userid) where ((r.user_id not IN(".$block_users.")) or r.user_id=:user_id) and (r.fullname like '%".$search."%' or r.username like '%".$search."%')  group by r.user_id  LIMIT $starting_limit, $size");
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
		            $profile_pic= $r['profile_pic'];
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

/*	---------------------------- upload post------------------------------------*/
function post_upload()
{
	global $pdo;
	extract($_REQUEST);
	$time=date("H:i:s");
	$date=date('y-m-d');
	$data=$_REQUEST['content'];
	$uploadType=$_REQUEST['upload_type'];
	$content = json_decode($data, true);
	//$tagUsers=explode(',',$tagUsers);

	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    if($res)
    {
    	
		$image_name=$_FILES['posts']['name'];

		$img_temp_name=$_FILES['posts']['tmp_name'];
		$profile_pic=upload_images($image_name,'uploads/',$img_temp_name);

		$thumbnail = $_REQUEST['thumbnail'];
		$pp = str_replace('', '+',$thumbnail);
		$path=dirname(__FILE__)."/uploads/thumbnails/";
		
	    $thumbnail_img = upload_base64_image($thumbnail,$path);
		//print_r($path);die;
		if($profile_pic)
		{
			$data2=array('user_id'=>$user_id,'uploads'=>$profile_pic,'caption'=>$caption,'postMsg'=>$postMessage,'explicit'=>$explicit,'upload_type'=>$upload_type,'date'=>$date,'time'=>$time,'thumbnail'=>$thumbnail_img);
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
				foreach($content as $co)
				{
					$data=$co['data'];
					//$url=$co['url'];
					$price=$co['price'];
					$data2=array('upload_id'=>$id,'content'=>'','content_description'=>$data,'price'=>$price);
		  			$stmt_insert=insert('upload_content',$data2);	
				}
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
		}
		else
		{
			$status='0';
			$message='invalid uploaded file';
		}

    }
     else
    {
    	//$json=new stdClass();
		$status='0';
		$message='user not found';
    }

    $json=array('message'=>$message,'status'=>$status,'data'=>$json);
	echo "{\"response\":" . json_encode($json) . "}";
}


/*	---------------------------- Detail page of user------------------------------------*/
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

    	$stmt=$pdo->prepare("select r.*,u.*,if((r.profile_pic!=''),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,count(c.id) as comments_count,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url from uploads as u join register as r on r.user_id=u.user_id left join comments as c on c.upload_id=u.id   where u.id=:post_id ");
    		/*$stmt=$pdo->prepare("select r.*,u.id,u.uploads,u.thumbnail,u.upload_type,u.caption,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,count(c.id) as comments_count,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url from uploads as u join register as r on r.user_id=u.user_id left join comments as c on c.upload_id=u.id   where u.id=:post_id ");*/
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
	    		$stmt_sel=$pdo->prepare("SELECT if((profile_pic!='' && user_type='0'),(concat('https://rawuncensored.com/uploads/profile/', profile_pic)),(profile_pic)) AS follower_profile from register where user_id=:user_id");
				$array_sel=array('user_id'=>$user_id);
				$stmt_sel->execute($array_sel);
				$outputs=$stmt_sel->fetch(PDO::FETCH_ASSOC);
		
			    	//comments
		    	$stmt=$pdo->prepare("select c.comment,r.user_id,if((r.profile_pic!='' && user_type='0'),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,r.username from  comments as c join register as r on r.user_id=c.follower_id  where upload_id=:post_id limit 3");
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

				$copy_url="https://rawuncensored.com/post_copy/".$result['id']."";
			
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
			    				'total_views'=>$otpt['totalViews']? $otpt['totalViews']:'0',
			    				'copy_url'=>$copy_url? $copy_url : '' ,
			    				//	'is_purchased'=>($result['membership']==2) ? 1 : 0 ,
			    				'likes_count'=>($reslt1['like_count']) ? $reslt1['like_count'] :'0',
			    				'likes'=>($results)? '1' : '0',
			    				'comments_count'=>$counts ? $counts : '0',
			    				'comments'=>$output ? $output : array(),
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


/*	---------------------------- fetch  followers feed------------------------------------*/
function fetch_feed()
{	
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$size=10;
	$stmt=$pdo->prepare("select *,if((profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) as profile_pic from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($res);die;
    //donate
	$stmt=$pdo->prepare("select * from donate");
	$stmt->execute(array());
	$opt=$stmt->fetch(PDO::FETCH_ASSOC);
	//notifivation count
	$ncount=noti_count($user_id);

	//broadcast data
	$stmt=$pdo->prepare("select androidApiKey,iosApiKey from app_settings");
	$stmt->execute(array());
	$settings=$stmt->fetch(PDO::FETCH_ASSOC);
	//get subscription type
	$stmt=$pdo->prepare("select subscriptionType from followSubscription where fromUserId=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
	$subscriptionT=$stmt->fetch(PDO::FETCH_ASSOC);
	$subscriptionType=(empty($subscriptionT))?'2':$subscriptionT['subscriptionType'];
	//print_r($subscriptionType);die;
	//get stripe key
	$stmt=$pdo->prepare("select connectStripeId from register where  user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
	$stripeKey=$stmt->fetch(PDO::FETCH_ASSOC);
	$connected_with_stripe=$stripeKey['connectStripeId']?1:0;
	//print_r($row_sel['connectStripeId']);die;

    if($res)
    {
    	//$profile_pic= get_profile_pic($res['profile_pic']);
    	///////////update token///////////
    	$where=array('user_id'=>$user_id);
		$data1=array('device_token'=>$device_token,'device_type'=>$device_type,'fcm_token'=>$fcm_token,'deviceId'=>$device);
		$stmt_updt=update_multi_where('register', $where, $data1);
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
	    }
	    if($result)
	    {
	    	foreach($result as $rslt)
	    	{
	    		$d[]=implode(",",$rslt);
	    	
	    	}
	    	$fo=implode(',',$d);		//followers
	   		 	
		 	$ar_mrg = array_merge($output, $result);
		}
		if($ar_mrg)
		{
		 	foreach($ar_mrg as $a)
		 	{
		 		$thh[]=implode('',$a);
		 	}
		 	$th=implode(',',$thh);
		}
	 	
	 	//print_r($th);die;
	 	/*$dat1=blockUser($thh,$user_id);
	 	$tt=$dat1['block_users'];
	 	$tt=array_merge($thh,$tt);
	 	$tt=implode(',',$tt);*/
	 	//following+block
	 	//print_r($user_id);die;
	 	$block_users=blockUsers($user_id);
	 	//print_r($flwg);die;
	 	//$block_users=(array)$block_users;
	 	$flwg=(array)$flwg;

	 	$sugges=array_unique(array_merge($block_users,$flwg));
		$tt=implode(',',$sugges); //followings+block
		//print_r($sugges);die;
	 	$dat=blockUser($thh,$user_id);
	 	$th=implode(',',$dat['unblock_users']);
	 	//print_r($dat);die;
	 	//$dd=blockUser($z,$user_id); //unblock following
	 	//$z=implode(',',$dd['unblock_users']);
	 	$dd=blockUsers($user_id);
	 	$unblock_followings = array_diff($flwg, $dd); 
	 	$z=implode(',',$unblock_followings);
	 	//print_r($user_id);

	 	//$k=array();
	 	//$k=array('all'=>$thh,'tt'=>$tt);
	 	//print_r($z);die;

    	//if(!empty($th))
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
    		$sql="select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id) join reports as rs on rs.post_id!=u.id  where r.user_id IN(".$z.") or r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc";
    	
    		$stmt=$pdo->prepare($sql);
			$stmt->execute(array('user_id'=>$user_id));
		    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    // print_r($row);die;
		    if($row)
		    {
		   		/**********pagination***********/
	    		$totalCount = $stmt->rowCount();
	    		$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id) join reports as rs on rs.post_id!=u.id   where r.user_id IN(".$z.") or r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc LIMIT $starting_limit, $size");
				$ar=array('user_id'=>$user_id);
				$stmt1->execute($ar);
				 //$row=$stmt1->fetchAll(PDO::FETCH_ASSOC);
		    	//print_r($row);die;
				/*****************************/
				$qry="select l.user_id,l.b_url as broadcast_url,broadcast_id as broadcast_id,r.username,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as profile_pic from live_users as l join register as r on r.user_id=l.user_id where l.user_id IN(".$z.") and l.status='0' ";
			  	$stmt=$pdo->prepare($qry);
				$stmt->execute(array());
			    $live_user=$stmt->fetchAll(PDO::FETCH_ASSOC);
			    //print_r($qry);die;
			 
			    foreach($stmt1 as $result)
				{
					$copy_url="https://rawuncensored.com/post_copy/".$result['id']."";
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

					// $id=$res['user_id'];
					
					//print_r($res['profile_pic']);die;
					$profile_pic= get_profile_pic($res['profile_pic']);

					$data[]=array('user_id'=>$result['user_id'],
				    				'username'=>$result['username'],
				    				'profile_pic'=>$profile_pic,
				    				//'profile_pic'=>$res['profile_pic'],
				    				//'profile_pic'=>$result['dp'],
				    				'posts'=>$result['post_url'],
				    				'follower_profile'=>$result['dp'],
				    				'thumbnail_url'=>$result['thumbnail_url'],
				    				'posts_type'=>$result['upload_type'],
				    				'post_id'=>$result['id'],
				    				'copy_url'=>$copy_url,
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
				    				'caption'=>($result['caption']) ? $result['caption'] : '',
				    				'postMessage'=>$result['postMsg']
				    				
					    			);
					$json=array('live'=>$live_user? $live_user : array(),'result'=>$data,'suggestions'=>$sugg ?$sugg :array(),'is_donate'=>$opt['is_donate'] ? $opt['is_donate']:'','is_membership'=>$res['membership'],'subscriptionType'=>$subscriptionType,'noti_count'=>$ncount);

				}
				
				$json=array('message'=>'successs','status'=>'1','appSettings'=>$settings,'total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'connected_with_stripe'=>$connected_with_stripe,'data'=>$json);  
			}
			else
			{
				//if no post upload(suggestions)
				$sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic from register as r left join followers as f on f.user_id=r.user_id where r.user_type='0' and r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc";
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
                   // $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc LIMIT $starting_limit, $size";
                    $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic  from register as r left join followers as f on f.user_id=r.user_id where r.user_type='0' and r.user_id!=:user_id and r.user_id not IN(".$tt.") group by user_id order by count desc LIMIT $starting_limit, $size";
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
			    	$data1=array('live'=> array(),'result'=>array(),'suggestions'=>$data ?$data :array(),'is_donate'=>$opt['is_donate'] ? $opt['is_donate']:'','is_membership'=>$res['membership'],'subscriptionType'=>$subscriptionType,'noti_count'=>$ncount);
			    	$json=array('message'=>'The photos and videos they did not share','status'=>'1','appSettings'=>$settings,'total_records'=>(string)$totalCount,'last_page'=>(string)$total_pages,'connected_with_stripe'=>$connected_with_stripe ,'data'=>$data1);
			    }
			    else
			    {
			    	//call sugg() function
			    	//suggestions not exist
			    	
			    	$json=sugg($settings,$connected_with_stripe,$subscriptionType); 		    	
			    }

			}
    	}
    	else
    	{
    		//if no follower/following but own feed exist
    		$suggestion=sugg($settings,$connected_with_stripe,$subscriptionType);
    		$suggestion=$suggestion['data']['suggestions'];	 
    		//print_r($suggestion);die;		

    		$sql="select r.*,u.*,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id)  where r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc";
    		$stmt=$pdo->prepare($sql);
			$stmt->execute(array('user_id'=>$user_id));
		    $op=$stmt->fetchAll(PDO::FETCH_ASSOC);
		    if($op)
		    {
		    	/**********pagination***********/
	    		$totalCount = $stmt->rowCount();
	    		$total_pages = ceil($totalCount/$size);
		    	$starting_limit=pagination($totalCount,$pageNo,$size);
		    	$stmt1=$pdo->prepare("select r.*,u.*,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) as dp,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,if((thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url ,c.comment from register as r  join uploads as u on r.user_id=u.user_id left join comments as c on (c.upload_id=u.id and c.follower_id=:user_id)  where r.user_id=:user_id and u.uploads!='0' group by u.id  order by u.id desc LIMIT $starting_limit, $size");
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
			    	$isFollowSubscriptionPurchased=(strtotime($rspnss['endDate']) > strtotime(date('Y-m-d')))?'1':'0';
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
					$profile_pic= get_profile_pic($res['profile_pic']);
					$dp= get_profile_pic($result['dp']);
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
				    				'is_explicit'=>($result['explicit']==2) ? '1' : '0' ,
				    				'isFollowSubscriptionPurchased'=>$isFollowSubscriptionPurchased,
				    				'links'=>$op? $op:array(),
				    				//	'is_purchased'=>($result['membership']==2) ? 1 : 0 ,
				    				'likes_count'=>($reslt1['like_count']) ? $reslt1['like_count'] :'0',
				    				'likes'=>($results)? '1' : '0',
				    				//'comments_count'=>$counts ? $counts : '0',
				    				'comments_count'=>($reslt2['comment_count']) ? $reslt2['comment_count'] : '0',
				    				'comments'=>array(0=>$result['comment']? $result['comment']:''),
				    				//'comments'=>$cmnt ? $cmnt:array(),
				    				'caption'=>($result['caption']) ? $result['caption'] : '',
				    				'postMessage'=>$result['postMsg']
				    				
					    			);
					$suggestion=($pageNo==1)?$suggestion :array();
					$json=array('live'=>array(),'result'=>$data,'suggestions'=>$suggestion ?$suggestion :array(),'is_donate'=>$opt['is_donate'] ? $opt['is_donate']:'','is_membership'=>$res['membership'],'subscriptionType'=>$subscriptionType,'noti_count'=>$ncount );

				}
				

				$json=array('message'=>'success','status'=>'1','appSettings'=>$settings,'total_records'=> (string)$totalCount,'last_page'=>(string)$total_pages,'connected_with_stripe'=>$connected_with_stripe,'data'=>$json);  
		    }
		    else
		    {
		    	
	            // $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) AS profile_pic,count(f.user_id) as count  from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id group by user_id order by count desc";
	             $sql="select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic from register as r left join followers as f on f.user_id=r.user_id where r.user_id!=:user_id and user_type='0' group by user_id ";
	            $stmt=$pdo->prepare($sql);
	            $stmt->execute(array('user_id'=>$user_id));
	            //$stmt->execute(array());
	            $json=$stmt->fetchAll(PDO::FETCH_ASSOC);
	           
	              // print_r($json);die;
	             /**********pagination***********/
	            $totalCountt = $stmt->rowCount();
	            $totalCountt = $totalCountt >20 ? 20 :$totalCountt;
	            $total_pages = ceil($totalCountt/$size);
	            $starting_limit=pagination($totalCount,$pageNo,$size);
	            $stmt1=$pdo->prepare("select r.user_id,r.membership,r.username,r.fullname,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic  from register as r left join followers as f on f.user_id=r.user_id where user_type='0' and r.user_id!=:user_id group by user_id  LIMIT $starting_limit, $size");
	             $stmt=$pdo->prepare($sql);
	             $stmt1->execute(array('user_id'=>$user_id));
	         	//$json=$stmt->fetchAll(PDO::FETCH_ASSOC);
	           
	              //print_r($json);die;
	            /*****************************/
	            $users_type=$json ? '0' :'1';
	            foreach($stmt1 as $r)
	            {
	            	$profile_pic= get_profile_pic($r['profile_pic']);

	                $data[]=array('user_id'=>$r['user_id'],'username'=>$r['username'],'fullname'=>$r['fullname'],'profile_pic'=>$profile_pic,'users_type'=>$users_type);
	            }
	            $data1=array('live'=>array(),'result'=>array(),'suggestions'=>$data ?$data :array(),'is_donate'=>$opt['is_donate'],'is_membership'=>$res['membership'],'subscriptionType'=>$subscriptionType,'noti_count'=>$ncount );
                   

          		 $json=array('message'=>'Follow people to start seeing the photos and videos they share','status'=>'1','appSettings'=>$settings,'total_records'=> (string)$totalCountt,'last_page'=>(string)$total_pages,'connected_with_stripe'=>$connected_with_stripe,'data'=>$data1); 

            
		    }

    	}
    	
  	}
  	else
  	{
  		$json=array('message'=>'no user found','status'=>'0','data'=>new stdClass()); 
  	}

	echo "{\"response\":" . json_encode($json) . "}";	
    
}

/*	---------------------------- post comments------------------------------------*/
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
 		$data2=array('follower_id'=>$user_id,'upload_id'=>$post_id,'comment'=>$comment);
  		$stmt_insert=insert('comments',$data2); 
  		if($stmt_insert)
  		{
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
  			

  			$stmt=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by c.id desc");
			$stmt->execute(array('post_id'=>$post_id));
	     	$output=$stmt->fetch(PDO::FETCH_ASSOC);
	     	//print_r($output);
  			
	  		$stmt=$pdo->prepare("select *,if((profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from  register where user_id=:user_id");
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


/*	---------------------------- post likes------------------------------------*/
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
			  		$stmt=$pdo->prepare("select *,if((profile_pic!=''),(concat('https://".$server."/uploads/profile/', profile_pic)),('')) AS profile_pic from  register where user_id=:user_id");
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


/*	---------------------------- get comments------------------------------------*/
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
    	$stmt=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by id desc");
		$stmt->execute(array('post_id'=>$post_id));
     	$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
     	if($res)
	    {		
    		/**********pagination***********/
    		$totalCount = $stmt->rowCount();
    		$total_pages = ceil($totalCount/$size);
	    	$starting_limit=pagination($totalCount,$pageNo,$size);
	    	$stmt1=$pdo->prepare("select c.id,c.follower_id,c.comment,r.username,if((r.profile_pic!=''),(concat('https://rawuncensored.com/uploads/profile/', r.profile_pic)),('')) AS profile_pic from  comments as c join register as r on r.user_id=c.follower_id where c.upload_id=:post_id order by id desc LIMIT $starting_limit, $size");
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

/*	---------------------------- get likes------------------------------------*/
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
    	$stmt=$pdo->prepare("select l.id,l.follower_id,r.username,if((r.profile_pic!=''),(concat('https://rawuncensored.com/uploads/profile/',r.profile_pic)),('')) AS profile_pic from  likes as l join register as r on r.user_id=l.follower_id where l.upload_id=:post_id");
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
			$message=' no likes';
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

/*	---------------------------- premium membership------------------------------------*/
function get_membership()
{
	global $pdo;
	extract($_REQUEST);
	$date=date('Y-m-d');
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
     $result=$stmt->fetch(PDO::FETCH_ASSOC);
  	// print_r($date);die;
     if($result)
     {
		if($is_purchase==2 and $result['membership']==1)
		{
    		$where=array('user_id'=>$user_id);
			$data1=array('membership'=>2,'membership_date'=>$date);
			$stmt_updt=update_multi_where('register', $where, $data1);

			$status='1';
			$message='successfully purchased '; 
		}
		elseif($is_purchase==2 and $result['membership']==2)
		{
			$status='0';
			$message='already purchased'; 
		}
		else
		{
			$status='0';
			$message='not purchased'; 
		}
    }
    else
    {
    	
		$status='0';
		$message='user does not exist';
	
	}
	$json=array('message'=>$message,'status'=>$status);
	echo "{\"response\":" . json_encode($json) . "}";


}


/*	---------------------------- get membership status------------------------------------*/
function is_membership()
{
	global $pdo;
	extract($_REQUEST);
	$today_date=date("Y-m-d"); 

	$stmt=$pdo->prepare("select membership_date from register where user_id=:user_id and membership=2");
	$stmt->execute(array('user_id'=>$user_id));
     $result=$stmt->fetch(PDO::FETCH_ASSOC);
    //print_r($result);
     if($result)
     {
	    $date=$result['membership_date'];
	    $expire_date= date('Y-m-d',strtotime('+30 days',strtotime($date))) . PHP_EOL;
	 	 //$date='2019-08-02';
	    //print_r($date);
	    //print_r($expire_date);

	     if(strtotime($today_date) < strtotime($expire_date))
	     {
	    	$status='1';
			$message='you have successfully purchased membership'; 
	     }
	     else
	     {
	    	$where=array('user_id'=>$user_id);
			$data1=array('membership'=>1,'membership_date'=>'');
			$stmt_updt=update_multi_where('register', $where, $data1); 
	    	
			$status='0';
			$message='your Membership has been expired';
		}
	}
	else
	{
		$status='0';
		$message='You do not have any Membership yet';
	}
	$json=array('message'=>$message,'status'=>$status);
	echo "{\"response\":" . json_encode($json) . "}";

}

/*	---------------------------- fetch notifications------------------------------------*/
function get_noti()
{
	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$size=15;
	$stmt=$pdo->prepare("select * from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	if($result)
    {
    	$stmt=$pdo->prepare("select if((n.upload_id='0'),(''),(u.caption))as caption,if((n.upload_id='0'),(n.follower_id),(''))as users,n.notification,if((r.profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,upload_id,r.username from notifications as n join register as r on r.user_id=n.follower_id left join uploads as u on u.id=n.upload_id where n.user_id=:user_id order by n.id desc");
		$stmt->execute(array('user_id'=>$user_id));
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	     //print_r($res);die;
	    if($res)
	    {
	    	/**********pagination***********/
			$totalCount = $stmt->rowCount();
			$total_pages = ceil($totalCount/$size);
			$starting_limit=pagination($totalCount,$pageNo,$size);
			$stmt1=$pdo->prepare("select if((n.upload_id='0'),(''),(u.caption))as caption,if((n.upload_id='0'),(n.follower_id),(n.follower_id))as users,n.notification,if((r.profile_pic!='' && user_type='0'),(concat('https://".$server."/uploads/profile/', r.profile_pic)),(profile_pic)) AS profile_pic,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', u.uploads)),(concat('https://".$server."/uploads/videos/', u.uploads))) AS post_url,upload_id,r.username from notifications as n join register as r on r.user_id=n.follower_id left join uploads as u on u.id=n.upload_id where n.user_id=:user_id order by n.id desc LIMIT $starting_limit, $size");
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
}
	    


/*	---------------------------- send live noti(nodejs api working)------------------------------------*/
function live_noti($user_id,$b_url,$msg,$noti_type,$b_id)
{

	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select follower_id from followers where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    //print_r('$result');
    $stmt=$pdo->prepare("select * from app_settings");
	$stmt->execute(array());
    $ress=$stmt->fetch(PDO::FETCH_ASSOC);

	if($result)
    {

		$stmt=$pdo->prepare("select user_id from followers where follower_id=:user_id");
		$stmt->execute(array('user_id'=>$user_id));
	    $res=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   // print_r($res);die;
	    foreach($res as $r)
    	{
    		$fol[]=implode(",",$r);
    	}

	    foreach($result as $rslt)
    	{
    		$d[]=implode(",",$rslt);
    	}

    	$ar_mrg = array_merge($res, $result);
	 	foreach($ar_mrg as $a)
	 	{
	 		$tth[]=implode('',$a);
	 	}
	 	
	 	//unblock users
	 	$dat=blockUsers($user_id);
   		$th=array_diff($tth,$dat);
   		$th=implode(',',$th);
   		//$a=array('th'=>$th,'dat'=>$dat,'unblk'=>$thh);
   		//print_r($a);die;
     
		$stmt=$pdo->prepare("select * from register where user_id IN(".$th.")  ");
		$stmt->execute(array());
	    $row=$stmt->fetchAll(PDO::FETCH_ASSOC);
	     //print_r($row);die;
	    if($row)
	    {
	    	$stmt=$pdo->prepare("select *,if((profile_pic!=''  && user_type='0'),(concat('https://rawuncensored.com/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic from register where user_id=:user_id");
			$stmt->execute(array('user_id'=>$user_id));
			$rsult=$stmt->fetch(PDO::FETCH_ASSOC);
				
	    	foreach($row as $res)
	    	{
    		//print_r($res);
    			//$message=$rsult['username']." "."started a live video.Watch it before it ends!!";
    			$message=$rsult['username']." ".$msg;
			   // $noti_type="live noti";
			    $broadcast_id=$b_id;
			    $broadcast_url=$b_url;

			 
				if($res['device_type']=='A')
				{
					//print_r('a');
					
					//$noti=android_noti($res['device_token'],$message,$noti_type,$user_id=$res['user_id'],$broadcast_id,$rsult['user_id'],$rsult['username'],$rsult['profile_pic'],$broadcast_url);
					$data = array( 'registration_ids'  => array($res['device_token']),'data' => array("message" => $message,"noti_type" => $noti_type,"follower_id"=>$res['user_id'],"broadcast_id"=>$broadcast_id,"user_id"=>$rsult['user_id'], "username"=>$rsult['username'],'profile_pic'=>$rsult['profile_pic'],'broadcast_url'=>$broadcast_url,"appKey"=>$ress['androidApiKey']));
						$noti=android_noti2($data);
					//print_r($noti);die;
					
				}
				else
				{ 	
				
					if($noti_type=='archieved noti')
					{
						$data2=array('sound'=>'default','message'=>$message,'type'=>$noti_type,'follower_id'=>$res['user_id'],'broadcast_id'=>$broadcast_id,'user_id'=>$rsult['user_id'],'username'=>$rsult['username'],'profile_pic'=>$rsult['profile_pic'],'broadcast_url'=>$broadcast_url,"appKey"=>$ress['iosApiKey']);
					}
					else
					{
						$data2=array('alert'=>$message,'sound'=>'default','message'=>$message,'type'=>$noti_type,'follower_id'=>$res['user_id'],'broadcast_id'=>$broadcast_id,'user_id'=>$rsult['user_id'],'username'=>$rsult['username'],'profile_pic'=>$rsult['profile_pic'],'broadcast_url'=>$broadcast_url,"appKey"=>$ress['iosApiKey']);
					}
					
					
					$noti=ios($data2,$res['device_token'],$message);
					//$data2=array('alert'=>$message,'sound'=>'default','type'=>$noti_type);
					//$noti2=ios_notification($data2,$res['device_token'],$message);	
					
				}
				
	    	}

	   		return 1;
	    }
	    else
	    {
	    	return 0;
		}
    }
    else
    {
    		return -1;
    }
}

/*----------------------------------------get broadcast data(nodejs api working)--------------------------*/
function get_broadcastData()
{
	global $pdo;
	extract($_REQUEST);
  	$ch = curl_init();
 
 	$stmt=$pdo->prepare("select * from app_settings ");
	$ar=array();
	$stmt->execute($ar);
	$key=$stmt->fetch(PDO::FETCH_ASSOC);
	$apiKey=$key['ApiKey'];
     // set url
     curl_setopt($ch, CURLOPT_URL, "https://api.bambuser.com/broadcasts?limit=1&titleContains=");

     curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/vnd.bambuser.v1+json','Authorization:Bearer '.$apiKey));
    // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/vnd.bambuser.v1+json','Authorization:Bearer 3baavrcyur5l3cywy2ofo256y',));

     //return the transfer as a string
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

     // $output contains the output string
    $output = curl_exec($ch);
 	if($output)
 	{
 		//print_r($output);die;
		$json1=json_decode($output);
		$b_id=$json1->results[0]->id;
		$type=$json1->results[0]->type; //status

		$url=$json1->results[0]->resourceUri;
		//$encodeUrl=urlencode($burl);
		//$url="https://dist.bambuser.net/player/?resourceUri=".$encodeUrl."";
		//print_r($broadcast_id);die;
		//$noti=live_noti($user_id,$url);  //call the function to send notifications to followers
		//if($status==0 and $b_id==$broadcast_id )   //live
		if($status=='0')   //live
		//print_r($status);die;
		//if($status==0)   //live
		{

				//insert live data in table if exist then update
				$stmt=$pdo->prepare("select * from live_users where user_id=:user_id ");
				$ar=array('user_id'=>$user_id);
				$stmt->execute($ar);
				$rsult=$stmt->fetch(PDO::FETCH_ASSOC);

				if(!empty($rsult))
				{	
					//print_r('update');die;
					$where=array('user_id'=>$user_id);
					$data=array('status'=>'0','broadcast_id'=>$broadcast_id,'b_url'=>$url,'user_id'=>$user_id);
					$stmt_updt=update_multi_where('live_users', $where, $data); 
					if($stmt_updt)
  					{
  						$msg="started a live video.Watch it before it ends!!";
  						$noti_type="live noti";
						$noti=live_noti($user_id,$url,$msg,$noti_type,$b_id);  //call the function to send notifications to followers
  					}
					//$json=array('message'=>'still live','status'=>'1');
				}
				else
				{
					$data=array('user_id'=>$user_id,'broadcast_id'=>$broadcast_id,'b_url'=>$url,'status'=>'0');
  					$stmt_insert=insert('live_users',$data);
  					if($stmt_insert)
  					{
  						$msg="started a live video.Watch it before it ends!!";
  						$noti_type="live noti";
						$noti=live_noti($user_id,$url,$msg,$noti_type,$b_id);  //call the function to send notifications to followers
						//print_r($noti);die;
  					}
  					//$json=array('message'=>'success','status'=>'1');
				}

				//$json=array('message'=>'success','status'=>'1','data'=>$noti['data']);
				$json=array('message'=>'success','status'=>'1');
		
		}
		else if($status=='1') //archieved
		{
			$where=array('user_id'=>$user_id,'broadcast_id'=>$broadcast_id ,'status'=>0);
			$data=array('status'=>'1');
			$stmt_updt=update_multi_where('live_users', $where, $data); 

			//if($stmt_updt)
			//{
				$msg="archieved video !!!!";
				$noti_type="archieved noti";
				$noti=live_noti($user_id,$url,$msg,$noti_type,$broadcast_id); 
			//}
				$json=array('message'=>'archieved','status'=>'0');	
		}
		else
		{
			$json=array('message'=>'wrong input','status'=>'0');
		}
	}
	else
	{
			$json=array('message'=>'server error','status'=>'0');
	}
	echo "{\"response\":" . json_encode($json) . "}";
	
	// close curl resource to free up system resources
    curl_close($ch);   
}
/******************************update profile pic**********************************/
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
			$image_name=$_FILES['profile_pic']['name'];
			$tmp = explode('.', $image_name);
    		$fileExtension = end($tmp);
 			$target_dir = "uploads/profile/";
          	$image_name='img_'.rand().'.'.$fileExtension;
			$img_temp_name=$_FILES['profile_pic']['tmp_name'];
		  	$target_file = $target_dir . basename($image_name);
    		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    		if (move_uploaded_file($img_temp_name, $target_file)) 
		    {
		       $profile_pic=$image_name;
		    }
		    else 
		    {
		         $profile_pic='';
		    }
        }
		else
		{
        	$profile_pic=$result['profile_pic'];
        }
        $where=array('user_id'=>$user_id);
		$data1=array('profile_pic'=>$profile_pic);
		$stmt_updt=update_multi_where('register', $where, $data1);
		$pp="https://".$server."/uploads/profile/".$profile_pic;
		//print_r($pp);die;
		$json=array('message'=>'success','status'=>'1','profile_pic'=>$pp);
	}
	else
	{
		$json=array('message'=>'Invalid user','status'=>'0');
	}
echo "{\"response\":" . json_encode($json) . "}";
}

/************************firebase chat****************************************/
function add_friendlist()
{
 	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from friend_list where to_userid=:to_userid and from_userid=:from_userid");
	$stmt->execute(array('to_userid'=>$to_userid,'from_userid'=>$from_userid));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if(empty($result))
    {
		$data2=array('to_userid'=>$to_userid,'from_userid'=>$from_userid);
		$stmt_insert=insert('friend_list',$data2);
		if($stmt_insert)
		{
			$data2=array('to_userid'=>$from_userid,'from_userid'=>$to_userid);
			$stmt_insert1=insert('friend_list',$data2);
			$json=array('message'=>'success','status'=>'1');
		}
		else
		{
			$json=array('message'=>'Something went wrong','status'=>'0');
		}	
	}
	else
	{
		$json=array('message'=>'Already exist','status'=>'0');
	}
	echo "{\"response\":" . json_encode($json) . "}";
}

/************************firebase chat****************************************/
function add_activeStatus()
{
	global $pdo;
	extract($_REQUEST);

	$stmt=$pdo->prepare("select * from register where user_id=:user_id and is_active='1'");
	$stmt->execute(array('user_id'=>$user_id));
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    if($result)
    {
    	if($is_online=='1')
		{
			$json=array('message'=>'You are already online','status'=>'0');
		}
		else
		{
	    	$where=array('user_id'=>$user_id);
			$data=array('is_active'=>$is_online);
			$stmt_updt=update_multi_where('register', $where, $data);
			if($stmt_updt)
			{
				
				$json=array('message'=>'you are offline now','status'=>'1');
			}
			else
			{
				$json=array('message'=>'Something went wrong','status'=>'0');
			}
		}
    }
    else
    {
    	if($is_online=='0')
		{
			$json=array('message'=>'You are already offline','status'=>'0');
		}
		else
		{
	    	$where=array('user_id'=>$user_id);
			$data=array('is_active'=>$is_online);
			$stmt_updt=update_multi_where('register', $where, $data);
			if($stmt_updt)
			{
				
				$json=array('message'=>'you are online now','status'=>'1');
			}
			else
			{
				$json=array('message'=>'Something went wrong','status'=>'0');
			}
		}
    }
    echo "{\"response\":" . json_encode($json) . "}";
}


/************************fetch firebase chat****************************************/
function get_friendlist()
{
 	global $pdo;
	extract($_REQUEST);
	$server=$_SERVER['HTTP_HOST'];
	$stmt=$pdo->prepare("select from_userid from friend_list where to_userid=:to_userid");
	$stmt->execute(array('to_userid'=>$to_userid));
    $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
    if($result)
    {
    	$ids=array_column($result,'from_userid');

    	$sql="select username,fullname,user_id,fcm_token,if((profile_pic!=''  && user_type='0'),(concat('https://".$server."/uploads/profile/', profile_pic)),(profile_pic)) AS profile_pic,is_active from register where user_id IN('".implode("','",$ids)."') ";
    	$stmt=$pdo->prepare($sql);
		$stmt->execute(array());
     	$row=$stmt->fetchAll(PDO::FETCH_ASSOC);
     	if($row)
     	{
     		foreach($row as $r)
     		{
 				$stmt=$pdo->prepare("select fullname from register where user_id=:to_userid");
				$stmt->execute(array('to_userid'=>$to_userid));
			    $res=$stmt->fetch(PDO::FETCH_ASSOC);
			    //print_r($res);die;
			    $json[]=array(
			    			'username'=>$r['username'],
			    			'fullname'=>$r['fullname'],
			    			'user_id'=>$r['user_id'],
			    			'fcm_token'=>$r['fcm_token'],
			    			'profile_pic'=>$r['profile_pic'],
			    			'is_active'=>$r['is_active'],
			    			'to_fullname'=>$res['fullname']
			    			);
     		}
     		$json=array('message'=>'Success','status'=>'1','data'=>$json);

     	}
     	else
     	{
     		$json=array('message'=>'empty friend list','status'=>'1','data'=>$json);
     	}
     	
     	
    }
    else
    {
    	$json=array('message'=>'no record found','status'=>'0','data'=>array());
    }
    echo "{\"response\":" . json_encode($json) . "}";
}

/************************payment****************************************/
function paypal_payment()
{
 	global $pdo;
	extract($_REQUEST);
	require 'vendor/autoload.php';

	Braintree_Configuration::environment('sandbox');
	Braintree_Configuration::merchantId('ypghhzchk9y3dqhw');
	Braintree_Configuration::publicKey('y73f4wkxgn3pmk4n	');
	Braintree_Configuration::privateKey('32603560f5d43c5d200053341ecf338b');

	$gateway = new Braintree_Gateway([
	  'environment' => 'sandbox',
	  'merchantId' => 'ypghhzchk9y3dqhw',
	  'publicKey' => 'y73f4wkxgn3pmk4n',
	  'privateKey' => '32603560f5d43c5d200053341ecf338b'
	]);

	//$clientToken = $gateway->clientToken()->generate();

	$paymentMethodNonce = $payment_method_nonce;
	// $paymentMethodNonce = 'fake-valid-nonce';

	$amount = $amount;

	$res = $gateway->transaction()->sale([
	  	'amount' => $amount,
	    'customerId' => $customer_id,
	  	'paymentMethodNonce' =>$paymentMethodNonce,
	 
	  	'options' => [
	    	'submitForSettlement' => True
	  	]
	]);

	if ($res->success) 
	{
	  	$customer_id=$res->customer->id;
	   // $token=$result->customer->paymentMethods[0]->token;
	    $t_id=$res->transaction->id;
		$data=array('transaction_id'=>$t_id);
	   	$json=array('status'=>'1','message'=>'success','data'=>$data);
	} 
	else
	{
	    foreach($res->errors->deepAll() AS $error) 
	    {
	       $msg=$error->code . ": " . $error->message . "\n";
	        $json=array('status'=>'0','message'=>$msg,'data'=>array());
	    }
	   // print_r('err');
	}
	echo "{\"response\":" . json_encode($json) . "}";

}
/************************payment****************************************/
function get_clientToken()
{
 	global $pdo;
	extract($_REQUEST);
	require 'vendor/autoload.php';

	Braintree_Configuration::environment('sandbox');
	Braintree_Configuration::merchantId('ypghhzchk9y3dqhw');
	Braintree_Configuration::publicKey('y73f4wkxgn3pmk4n	');
	Braintree_Configuration::privateKey('32603560f5d43c5d200053341ecf338b');

	$gateway = new Braintree_Gateway([
	  'environment' => 'sandbox',
	  'merchantId' => 'ypghhzchk9y3dqhw',
	  'publicKey' => 'y73f4wkxgn3pmk4n',
	  'privateKey' => '32603560f5d43c5d200053341ecf338b'
	]);
	$res = $gateway->customer()->create([
       	'firstName' => $firstName,
	    'lastName' => $lastName,
	    'company' => $company,
	    'email' => $email,
	    'phone' => $phone,
	    'fax' => $fax,
	    'website' => $website,
	     'paymentMethodNonce' =>'fake-valid-nonce',
	]);
		$customer_id=$res->customer->id;
	    $token=$res->customer->paymentMethods[0]->token;
	    $data=array('Customer_id'=>$customer_id,'token'=>$token);
		//$clientToken = $gateway->clientToken()->generate();


	  $response=array('status'=>'1','message'=>'success','data'=>$data);

	 echo "{\"response\":" . json_encode($response) . "}";

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

/**************************************************/
function split_payment()
{
	global $pdo;
	extract($_REQUEST);
	include('adaptive-payments.php');

	$config = array(
		    "environment" => "sandbox", # or live
		    "userid" => "neetu-facilitator_api1.appzorro.com",
		    "password" => "TCBPVRTQ7BK2WMGR",
		    "signature" => "AcsjhE9xsm0lUzjhfeO5sNUKG0vDAOxcEaS3dPBjnZUNZ3JmhuXsBVlO",
		 /*   "environment" => "live", # or live
		    "userid" => "maxmartionii_api1.gmail.com",
		    "password" => "WVD8PVQEEFCEM8MW",
		    "signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31ATJdydW5jhptYjVIzPJ4CAl0elpw",*/
	        // "appid" => "", # You can set this when you go live
	);
	$paypal = new PayPal($config);
	//print_r($paypal);die;
	$master_merchant_amount=($amount*80)/100;
	$sub_merchant_amount=$amount-$master_merchant_amount;

	$stmt=$pdo->prepare("select paypal_id from register where user_id=:user_id");
	$stmt->execute(array('user_id'=>$user_id));
    $res=$stmt->fetch(PDO::FETCH_ASSOC);
   	//print_r($res);die;

	$result = $paypal->call(
        array(
			    'actionType' => 'PAY',
			    'currencyCode' => 'USD',
			    'feesPayer' => 'EACHRECEIVER',
			    'memo' => 'Order number #127',
			    'cancelUrl' => 'cancel.php',
			    'returnUrl' => 'success.php',
			    'receiverList' => array(
	        		'receiver' => array(
	            						array(
							                'amount' => $master_merchant_amount,
							                'email' => 'heenaverma@appzorro.com',
							                'primary' => 'false',
							            ),
							            array(
							                'amount' => $sub_merchant_amount,
							                'email' => 'sb-43u7m7229483@personal.example.com',
							            ),
	        						),
    							),
   		 ), 'Pay'
	);
	//print_r($result);die;
	if ($result['responseEnvelope']['ack'] == 'Success') 
	{
	    $_SESSION['payKey'] = $result["payKey"];
	    //$paypal->redirect($result);
	    $json=array('status'=>'1','message'=>'success','data'=>$result["payKey"]);
	} 
	else 
	{
	  	$msg='Handle the payment creation failure';
	    $json=array('status'=>'0','message'=>'Sub-merchant has no paypal account','data'=>'');
	}
	echo "{\"response\":" . json_encode($json) . "}";
	
}

function support_chat()
{
	global $pdo;
	extract($_REQUEST);
	$mail=sendemail($email,$message,$subject);
	//print_r($mail);
	//die;
	if($mail==1)
	{
		$json=array('status'=>'1','message'=>'Mail sent');
	}
	else
	{
		$json=array('status'=>'0','message'=>'Server Error');
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
/*************************block list******************************************/
function block_list()
{
	global $pdo;
	extract($_REQUEST);

	$stmt_sel=$pdo->prepare("select b.to_userid as user_id,r.username,r.fullname,if((r.profile_pic!=''),(concat('https://".$server."/uploads/profile/', r.profile_pic)),('')) AS profile_pic from block_users as b join register as r on r.user_id=b.to_userid where from_userid=:user_id");
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

        if($stmt_insert2)
		{
			$json=array('message'=>'Ticket Genrated','status'=>'1','query_id'=>$stmt_insert2);
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
/***********************chat********************************/
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
/***********************fetch chat********************************/
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
/***********************report reason fetch********************************/
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
/*********************check contact and email******************************/
function check_contact()
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
		else
		{
			$json=array('status'=>'0','message'=>'Contact is already  exist');
		}
		
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

}

/*************************************************************/
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
/*************************************************************/
function simple_paypal()
{
	global $pdo;
	extract($_REQUEST);
	$enableSandbox = false;
	//$enableSandbox = true;
	// PayPal settings. Change these to your account details and the relevant URLs
	// for your site.
	
	$stmt_sel=$pdo->prepare("SELECT paypal_id from register where user_id=:user_id");
	$stmt_sel->execute(array('user_id'=>$user_id));
	$res=$stmt_sel->fetch(PDO::FETCH_ASSOC);
	//print_r($res['paypal_id']);die;
	if(empty($res['paypal_id']))
	{
		$json=array('status'=>'0','message'=>'User does not have paypal account','data'=>'');
	}
	else
	{

		$paypalConfig = [
			//'email' => 'sb-43u7m7229483@personal.example.com',
			'return_url' => 'https://rawuncensored.com/success.php',
			'cancel_url' => 'http://example.com/payment-cancelled.html',
			'notify_url' => 'http://example.com/payments.php'
		];

		$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';


			// Grab the post data so that we can set up the query string for PayPal.
			// Ideally we'd use a whitelist here to check nothing is being injected into
			// our post data.
			$detail=array('cmd'=>'_xclick','lc'=>'US');
			$data = [];
			foreach ($detail as $key => $value) 
			{
				$data[$key] = stripslashes($value);	
			}

			// Set the PayPal account.
			$data['business'] = $res['paypal_id'];

			// Set the PayPal return addresses.
			$data['return'] = stripslashes($paypalConfig['return_url']);
			$data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
			$data['notify_url'] = stripslashes($paypalConfig['notify_url']);

			// Set the details about the product being purchased, including the amount
			// and currency so that these aren't overridden by the form data.
			//$data['item_name'] = $itemName;
			$data['amount'] = $amount;
			$data['currency_code'] = 'USD';

			// Add any custom fields for the query string. 
			//$data['custom'] = USERID;

			// Build the query string from the data.
			$queryString = http_build_query($data);
			$url=$paypalUrl . '?' . $queryString;
			// Redirect to paypal IPN
			//header('location:' . $paypalUrl . '?' . $queryString);
			//exit();
			$json=array('status'=>'1','message'=>'Success','data'=>$url);
	}
	echo "{\"response\":" . json_encode($json) . "}";

}
/**************************************************/
function exploreData()
{
	global $pdo;
	extract($_REQUEST);
	$Size=50;
	$server=$_SERVER['HTTP_HOST'];
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
	$connected_with_stripe=$stripeKey['connectStripeId']?1:0;

    if($res)
    {
		$stmt=$pdo->prepare("SELECT u.*,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', uploads)),(concat('https://".$server."/uploads/videos/', uploads))) AS post_url,if((u.thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url,count(l.id)as likess FROM `uploads` as u join likes as l on l.upload_id=u.id group by u.id order by likess desc");
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
			$show="SELECT u.*,if((u.upload_type='I'),(concat('https://".$server."/uploads/images/', uploads)),(concat('https://".$server."/uploads/videos/', uploads))) AS post_url,if((u.thumbnail!=''),(concat('https://".$server."/uploads/thumbnails/', thumbnail)),('')) AS thumbnail_url,count(l.id)as likess FROM `uploads` as u join likes as l on l.upload_id=u.id group by u.id order by likess desc LIMIT $starting_limit, $Size";
			$result=$pdo->prepare($show);
			$result->execute(array());
			foreach($result as $r)
			{
				////membership purchased for one person
				$stmt=$pdo->prepare("select endDate from followSubscription where toUserId=:toUserId and fromUserId=:fromUserId and subscriptionType='0' ");
				$stmt->execute(array('fromUserId'=>$user_id,'toUserId'=>$r['user_id']));
		    	$rspnss=$stmt->fetch(PDO::FETCH_ASSOC);
		    	$isFollowSubscriptionPurchased=(strtotime($rspnss['endDate']) > strtotime(date('Y-m-d')))?'1':'0';

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
			$json=array('message'=>'success','status'=>'1','current_page'=>$pageNo,'page_size'=>$Size,'total_records'=>"$totalCount",'last_page'=>"$total_pages",'is_membership'=>$res['membership'],'connected_with_stripe'=>$connected_with_stripe,'subscriptionType'=>$subscriptionType,'data'=>$data);
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
}