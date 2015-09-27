<?php
define("AUTHORIZENET_API_LOGIN_ID", "85H5eJw2");
define("AUTHORIZENET_TRANSACTION_KEY", "8zK5S2T4A698dfzM");
//define("AUTHORIZENET_API_LOGIN_ID", "8dk96TspNPCX");
//define("AUTHORIZENET_TRANSACTION_KEY", "86D76GvE4sjtAV7D");
//require '/var/www/html/qanda/protected/modules/payment/lib/shared/AuthorizeNetTypes.php';
//require '/var/www/html/qanda/protected/modules/payment/lib/AuthorizeNetAIM.php';

Yii::import('application.modules.payment.lib.shared.AuthorizeNetTypes', true);
Yii::import('application.modules.payment.lib.AuthorizeNetAIM', true);
Yii::import('application.modules.payment.lib.AuthorizeNetARB', true);

class SiteController extends Controller {
	/**
	 * Declares class-based actions.
	 */

	
	public function behaviors() {
		return array(
			'seo' => array(
				'class' => 'ext.seo.components.SeoControllerBehavior'
			)
		);
	}


/*
	public function actionWelcome(){
		if(isset($_GET[''])){


		}
		$this->redirect('https://www.createpool.com');
	}
*/



public function actionChangeemailjjj() {

$model = Users::model()->findByPk($_POST['jjj']);
$profile = $model->profile;

//$this->actionResendjjj();

$redirect_to = $_GET['redirect'];
$redirect_to = str_replace('?email_error2', '', $redirect_to);
$redirect_to = str_replace('?email_error', '', $redirect_to);

if(isset($_GET['redirect'])) {
$url = "https://www.studypool.com/site/Changeemailjjj?redirect=" . $redirect_to;

} else {
$url = "https://www.studypool.com/site/Changeemailjjj";
}
			
					
if(isset($_POST['email'])) {
$other = Users::model()->findByAttributes(array('email'=>$_POST['email']));
if($other) {
if(isset($_GET['redirect'])) {
$this->redirect($redirect_to . "?email_error");
} else {
$this->redirect('https://www.studypool.com'. "?email_error");
}
}

if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
if(isset($_GET['redirect'])) {
$this->redirect($redirect_to . "?email_error2");
} else {
$this->redirect('https://www.studypool.com'. "?email_error2");
}
}
}

if(isset($_POST['email'])) {
	$model->email = $_POST['email'];
	$model->save(false);
if(isset($_GET['redirect'])) {
$this->redirect('https://www.studypool.com/site/changeemailjjj?id=' . $model->id . '&email=' . $model->email . '&redirect=' . $redirect_to);
 } else {
 $this->redirect('https://www.studypool.com/site/changeemailjjj?id=' . $model->id . '&email=' . $model->email);

 }
}
$this->redirect('https://www.studypool.com');
}




public function actionCurlChangeEmail(){
	if(!isset($_POST['pem']) || $_POST['pem'] != "86626728"){
		exit();
	}

	if(isset($_POST['id']) && isset($_POST['email'])){
		$user = Users::model()->findByPk($_POST['id']);
		if($user){
			$user->email = $_POST['email'];
			$user->save();
		}
	}
	echo 200;
}




public function actionApisignup(){

	header('content-type: application/json; charset=utf-8');

	if(!isset($_GET['callback'])){
		$data = array('error'=>'no callback for jsonp');
		echo json_encode($data);
		exit();
	}

	if(!isset($_GET['pin']) || $_GET['pin'] != "sp86626728app"){
		$data = array('error'=>'Wrong api pin');
		echo $_GET['callback']. '('.json_encode($data).')';
		exit();
	}

	if (isset($_GET['email']) && isset($_GET['username']) && isset($_GET['password']) && $_GET['email'] && $_GET['username'] && $_GET['password']){
			$user = Users::model()->findByAttributes(array('email'=>$_GET['email']));
			if($user){
					$data = array('error'=>$user->username);
			}else{
					$user = new Users;
					$profile = new Profile;
					$user->username = $_GET['username'];
					$user->email = $_GET['email'];
					$user->password = md5($_GET['password']);
					$user->user_type = 1;
					$user->createtime = time();
					$user->lastvisit = 0;
					$user->activkey = md5(microtime() . $user->password);
					$user->status = 1;
					if(isset($_GET['promo'])){
						$user->promo = $_GET['promo'];
					}
					if($user->validate()){
						$user->save(false);
						$profile->user_id = $user->id;
						$pics = array('https://www.studypool.com/uploads/systemavatars/g55.jpg','https://www.studypool.com/uploads/systemavatars/crazy_sponge_bob_avatar_picture_40984.jpg','https://www.studypool.com/uploads/systemavatars/b11.png','https://www.studypool.com/uploads/systemavatars/fallout.gif');
						$profile->avatar = $pics[array_rand($pics)];
						$profile->save(false);

					$url = "https://www.studypool.com/site/newuser";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //this prevent printing the 200json code
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$user->id."&username=".$user->username."&password=".$user->password."&email=".$user->email."&activkey=".$user->activkey."&createtime=".$user->createtime."&status=1&ip=".$user->ip."&cookie=".$user->cookie."&user_type=".$user->user_type."&promo=".$user->promo."&country=".$user->country."&pem=86626728");
					$result = curl_exec($ch);
					curl_close($ch);
						$data = array('user_id'=>$user->id, 'username'=>$user->username, 'token'=>$user->activkey);
					}else{
						$data = $user->getErrors();
					}
			}
	}else{
		$data = array('error'=>'Did not get post');
	}

	echo $_GET['callback']. '('.json_encode($data).')';
}


/*
	@desc API sign up function
	@Need to pass via GET:	ping  => sp86626728app
				email
				username
				password
				promo (optional)
	@data	=>	callback returned
	@user	=>	query tbl_users and try to find if the user with the same email exists 
	@profile=>	create record in tbl_profiles for the user - where we store some extra data for users
	@pics   =>	An array of random profile pictures - need to be updated in the future.
	@url    =>	SSO system, where we curl studypool's server and create the user in its database.
	@result =>	Callback from curl.
*/
public function actionApisignupStan(){

	header('content-type: application/json; charset=utf-8');

	if(!isset($_GET['callback'])){
		$data = array('error'=>'no callback for jsonp');
		echo json_encode($data);
		exit();
	}

	if(!isset($_GET['pin']) || $_GET['pin'] != "sp86626728app"){
		$data = array('error'=>'Wrong api pin');
		echo $_GET['callback']. '('.json_encode($data).')';
		exit();
	}
	
	if (isset($_GET['email']) && isset($_GET['username']) && isset($_GET['password']) && $_GET['email'] && $_GET['username'] && $_GET['password']){
			$user = Users::model()->findByAttributes(array('email'=>$_GET['email']));
			if($user){
					$data = array('error'=>$user->email);
			}else{
					$user = new Users;
					$profile = new Profile;
					$user->username = $_GET['username'];
					$user->email = $_GET['email'];
					$user->password = md5($_GET['password']);
					$user->user_type = 1;
					$user->createtime = time();
					$user->lastvisit = 0;
					$user->activkey = md5(microtime() . $user->password);
					$user->status = 1;
					if($user->validate()){
						if(isset($_GET['promo'])){
							$user->promo = $_GET['promo'];
						}
						$user->save(false);
						$profile->user_id = $user->id;
						$pics = array('https://www.studypool.com/uploads/systemavatars/g55.jpg','https://www.studypool.com/uploads/systemavatars/crazy_sponge_bob_avatar_picture_40984.jpg','https://www.studypool.com/uploads/systemavatars/b11.png','https://www.studypool.com/uploads/systemavatars/fallout.gif');
						$profile->save(false);

						$url = "https://www.studypool.com/site/newuser";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //this prevent printing the 200json code
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout 10s
						curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout 10s
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$user->id."&username=".$user->username."&password=".$user->password."&email=".$user->email."&mobile=1&avatar=".$pics[array_rand($pics)]."&activkey=".$user->activkey."&createtime=".$user->createtime."&status=1&ip=".$user->ip."&cookie=".$user->cookie."&user_type=".$user->user_type."&promo=".$user->promo."&country=".$user->country."&pem=86626728");
						$result = curl_exec($ch);
						curl_close($ch);
						if(isset($_GET['promo']) ) {
							$data = array('user_id'=>$user->id, 'username'=>$user->username, 'token'=>$user->activkey, 'promo'=>json_decode($result,true));	
						}else{
							$data = array('user_id'=>$user->id, 'username'=>$user->username, 'token'=>$user->activkey);	
						}
					}else{
						$data = $user->getErrors();
					}
			}
	}else{
		$data = array('error'=>'Did not get post');
	}

	echo json_encode($data);
}



public function actionApilogin(){

	header('content-type: application/json; charset=utf-8');

	if (isset($_GET['email']) && isset($_GET['password'])){
			$user = Users::model()->findByAttributes(array('username'=>$_GET['email']));
			if(!$user){
				$user = Users::model()->findByAttributes(array('email'=>$_GET['email']));
			}
			if($user){
				if($user->password == md5($_GET['password'])){
					$data = array('id'=>$user->id, 'username'=>$user->username, 'token'=>$user->activkey);	
				}else{
					$data = array('error'=>'Wrong password');
				}
			}else{
				$data = array('error'=>'Wrong email/username');
			}
	}else{
		$data = array('error'=>'Did not get post');
	}

		echo $_GET['callback']. '('.json_encode($data).')';
}

public function actionApiloginstan(){

	header('content-type: application/json; charset=utf-8');

	if (isset($_GET['email']) && isset($_GET['password'])){
			$user = Users::model()->findByAttributes(array('username'=>$_GET['email']));
			if(!$user){
				$user = Users::model()->findByAttributes(array('email'=>$_GET['email']));
			}
			if($user){
				if($user->password == md5($_GET['password'])){

					// $city = "";
					// if($user->city){
					// 	$city = $user->city;
					// }

					// $country = "";
					// if($user->country){
					// 	$country = $user->country;
					// }

					// $avatar = "";
					// if($user->profile->avatar){
					// 	$avatar = $user->profile->avatar;
					// }

					// $logins = 0;
					// if($user->logins){
					// 	$logins = $user->logins;
					// }

					// $referred = false;
					// $checkAffliate = Affliate::model()->findByAttributes(array("child"=>$user->id));
					// if ($checkAffliate) {
					// 	$referred = true;	
					// }

					// Analytics::track(array(
					//   "userId" => $user->id,
					//   "event" => "Logged In"
					// ));
					
					$data = array('id'=>$user->id, 'username'=>$user->username, 'token'=>$user->activkey);//, 'email'=>$user->email,'create_time'=>$user->createtime, 'city'=>"111",'country'=>"222", "avatar" => "333", "logins"=>0, "referred"=>false);	
				}else{
					$data = array('error'=>'Wrong password');
				}
			}else{
				$data = array('error'=>'Wrong email/username');
			}
	}else{
		$data = array('error'=>'Did not get post');
	}

		echo json_encode($data);
}

public function actionFirstuser(){
	
	if(!isset($_GET['pwd']) || !isset($_GET['activkey']) || !isset($_GET['email']) || !isset($_GET['redirect']) || !isset($_GET['from'])){
		throw new CHttpException(404, 'We can not find the page you requested.');
	}
	
	$user = Users::model()->findByAttributes(array('activkey'=>$_GET['activkey'],'email'=>$_GET['email']));
	
	if(!$user){
		throw new CHttpException(404, 'We can not find the user.');
	}

	$oldname = $user->username;
	
	$social=Social::model()->findByAttributes(array('yiiuser'=>Yii::app()->user->id));
	$tmpusername = CHtml::encode((strlen($user->email) > 20) ? mb_substr($user->email, 0, 20,'utf-8'): $user->email);
	$provideruser="";
	
	if($social){
		$provideruser="SNS".$social->provideruser;
	}
	
	$model = $user;
	$profile = $model->profile;
	
	if(isset($_POST['Users']))
	{

	$model->attributes = $_POST['Users'];

	if(isset($_POST['Profile'])){
		$profile->attributes = $_POST['Profile'];

	$user_type = 1;

	if(isset($_GET['user_type'])){
		$user_type = $_GET['user_type'];
	}

	if($user_type == 1 || $user_type == "1"){
		$model->scenario = "asker";
	}else{
		$model->scenario = "tutor";
	}

/*
	if($profile->lastname && $profile->firstname){

		$profile->firstname = ucfirst(strtolower($profile->firstname));
		$profile->lastname = ucfirst(strtolower($profile->lastname));

		$model->username = $profile->firstname." ".$profile->lastname;

		$old = Users::model()->findByAttributes(array('username'=>$model->username));
		if($old){
			$model->username .= rand(10,999);
			$old2 = Users::model()->findByAttributes(array('username'=>$model->username));
			if($old2){
				$model->username .= rand(10,999);
			}
		}

	}else{
		$model->scenario = "asker";
	}
*/


}
		//if(!Yii::app()->user->isGuest){
		if(true){
			if($model->validate()) {

				$model->password=md5($model->password);
			
				//curl here
				$tokenarray = array();
				array_push($tokenarray, $model->username);
				array_push($tokenarray, $model->password);
				array_push($tokenarray, $model->id);
				array_push($tokenarray, $model->activkey);
				array_push($tokenarray, $model->email);
				array_push($tokenarray, time());
				array_push($tokenarray, $model->promo);

				array_push($tokenarray, $profile->avatar);
				array_push($tokenarray, $profile->firstname);
				array_push($tokenarray, $profile->lastname);


				$sarray = serialize($tokenarray);
				$code = base64_encode($sarray);

				$model->save(false);


		if(isset($_POST['first_profile_id'])){
			$system_profile = $_POST['first_profile_id'];
		}else{
			$system_profile = "";
		}


				$this->redirect('https://www.' . $_GET['from'] . '.com/site/handleFirstLogin?code=' . $code.'&redirect='.$_GET['redirect'].'&system_profile='.$system_profile);
				
			}else{
				$error = serialize($model->getErrors());
				$this->redirect($_GET['redirect'].'?error='.$error);
			}

		}else{
			throw new CHttpException(404, 'Guest invalid');
		}
		
	}else{
		throw new CHttpException(404, 'No post');
	}
}





	public function actionEmailrefer(){
		if(isset($_GET['email1']) && isset($_GET['email2']) && isset($_GET['email3']) && isset($_GET['your_name']) && isset($_GET['content'])){

		$message       = new YiiMailMessage;
		$message->view = 'refer_email';
		$message->setBody(array('message' => $_GET['content']), 'text/html');
		$message->setSubject($_GET['your_name']. ' - Never waste time doing Homework again!');
		$message->addTo($_GET['email1']);
		$message->setFrom(array('no-reply@createpool.com' => $_GET['your_name']));
		Yii::app()->mail->send($message);


		$message       = new YiiMailMessage;
		$message->view = 'refer_email';
		$message->setBody(array('message' => $_GET['content']), 'text/html');
		$message->setSubject($_GET['your_name']. ' - Never waste time doing Homework again!');
		$message->addTo($_GET['email2']);
		$message->setFrom(array('no-reply@createpool.com' => $_GET['your_name']));
		Yii::app()->mail->send($message);


		$message       = new YiiMailMessage;
		$message->view = 'refer_email';
		$message->setBody(array('message' => $_GET['content']), 'text/html');
		$message->setSubject($_GET['your_name']. ' - Never waste time doing Homework again!');
		$message->addTo($_GET['email3']);
		$message->setFrom(array('no-reply@createpool.com' => $_GET['your_name']));
		Yii::app()->mail->send($message);
		}
	}




	public function actionReward($id){
		if(Yii::app()->user->id){
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$shareme = Shareme::model()->findByPk(Yii::app()->user->id);
			if($shareme){
				if($id == 1){
					if(!$shareme->facebook){
						$shareme->facebook = 1;
						$shareme->last = "facebook";
						$shareme->save(false);
						$profile->refund += 5;
						$profile->save(false);

							$avatar               = $profile->avatar;
							$noti                 = new Notification;
							$noti->owner_id       = $profile->user_id;
							$noti->sender_id      = 1;
							$noti->avatar         = $avatar;
							$noti->sender_name    = "createpool";
							$noti->money          = 5;
							$noti->create_time    = date('Y-m-d H:i:s', time());
							$noti->type_id        = '70'; //facebook
							$noti->save(false);	
					}
				}


				if($id == 2){
					if(!$shareme->email){
						$shareme->email = 1;
						$shareme->last = "email";
						$shareme->save(false);
						$profile->refund += 5;
						$profile->save(false);

							$avatar               = $profile->avatar;
							$noti                 = new Notification;
							$noti->owner_id       = $profile->user_id;
							$noti->sender_id      = 1;
							$noti->avatar         = $avatar;
							$noti->sender_name    = "createpool";
							$noti->money          = 5;
							$noti->create_time    = date('Y-m-d H:i:s', time());
							$noti->type_id        = '71'; //email
							$noti->save(false);	
					}
				}

				if($id == 3){
					if(!$shareme->twitter){
						$shareme->twitter = 1;
						$shareme->last = "twitter";
						$shareme->save(false);
						$profile->refund += 5;
						$profile->save(false);

							$avatar               = $profile->avatar;
							$noti                 = new Notification;
							$noti->owner_id       = $profile->user_id;
							$noti->sender_id      = 1;
							$noti->avatar         = $avatar;
							$noti->sender_name    = "createpool";
							$noti->money          = 5;
							$noti->create_time    = date('Y-m-d H:i:s', time());
							$noti->type_id        = '72'; //twitter
							$noti->save(false);	
					}
				}


			}
		}
	}



	public function actionUnreward($id){
		if(Yii::app()->user->id){	
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$shareme = Shareme::model()->findByPk(Yii::app()->user->id);

		     if($profile && $shareme){
			if($id == 1){
				$shareme->facebook = 0;
				$shareme->last = "";
				$shareme->save(false);
				$profile->refund -= 5;
				$profile->save(false);
				$noti = Notification::model()->findByAttributes(array('owner_id'=>$profile->user_id,'type_id'=>70));
				$noti->delete();
			}

			if($id == 2){
				$shareme->email = 0;
				$shareme->last = "facebook";
				$shareme->save(false);
				$profile->refund -= 5;
				$profile->save(false);
				$noti = Notification::model()->findByAttributes(array('owner_id'=>$profile->user_id,'type_id'=>71));
				$noti->delete();
			}

			if($id == 3){
				$shareme->twitter = 0;
				$shareme->last = "email";
				$shareme->save(false);
				$profile->refund -= 5;
				$profile->save(false);
				$noti = Notification::model()->findByAttributes(array('owner_id'=>$profile->user_id,'type_id'=>72));
				$noti->delete();
			}
	            }
		}
	}


	public function actionCancelmember_answer(){
	if(Yii::app()->request->isPostRequest || Yii::app()->request->isAjaxRequest){
	$user = Users::model()->findByPk(Yii::app()->user->id);
	$profile = $user->profile;
	if($profile->best_answers >= 3){
	$profile->gold = 1;
	}else{
	$profile->gold = 0;
	}
	$profile->save(false);
	//send emails

$message       = new YiiMailMessage;
$message->view = 'cancel_membership';
$message->setBody(array('user' => $user), 'text/html');
$message->setSubject('Cancel Membership for '.$user->username);
$message->addTo("contact@createpool.com");
$message->setFrom(array('no-reply@createpool.com' => 'createpool'));
Yii::app()->mail->send($message);

	}
	}


	public function actionCancelmember_asker(){
	if(Yii::app()->request->isPostRequest || Yii::app()->request->isAjaxRequest){
	$user = Users::model()->findByPk(Yii::app()->user->id);
	$profile = $user->profile;
	if($profile->reputation >= 30){
	$profile->gold_asker = 1;
	}else{
	$profile->gold_asker = 0;
	}

	$profile->save(false);
	//send emails

$message       = new YiiMailMessage;
$message->view = 'cancel_membership';
$message->setBody(array('user' => $user), 'text/html');
$message->setSubject('Cancel Membership for '.$user->username);
$message->addTo("contact@createpool.com");
$message->setFrom(array('no-reply@createpool.com' => 'createpool'));
Yii::app()->mail->send($message);

	}
	}

	
	public function actionGethistory(){

	$a = Users::model()->findByAttributes(array('username'=>$_POST['a']));
	$b = Users::model()->findByAttributes(array('username'=>$_POST['b']));

$message = MailboxConversation::model()->find(array(
 'condition'=>'((initiator_id=:self AND interlocutor_id=:else) OR (initiator_id=:else AND interlocutor_id=:self)) AND livechat = 1',
 'params' => array(
 ':self'=>$a->id,
 ':else'=>$b->id
                 ),
));

	if($message){
	$texts = MailboxMessage::model()->findAllByAttributes(array('conversation_id'=>$message->conversation_id), array('limit'=>'50','order'=>'created DESC'));
	$history = '{"chat":[';
	$texts = array_reverse($texts);
	foreach($texts as $text){

$text->sender->username = str_replace( "\0", "\\u0000", addcslashes( $text->sender->username, "\t\r\n\"\\" ) );

$text->text = strip_tags($text->text);
$text->text = str_replace( "\0", "\\u0000", addcslashes( $text->text, "\t\r\n\"\\" ) );

	$history .= '{"username":"'. $text->sender->username.'","text":"'. $text->text.'","time":"'.$text->created.'"},';
	}
	//you have to remove the last comma, or it's not validated….
	$history =  mb_substr($history, 0, -1);
	$history .= ']}';
	echo $history;
	} //if $message
	}
	


	public function actionGettype(){
	$user = Users::model()->findByAttributes(array('username'=>$_GET['username']));
	if($user->user_type == 1){
	$friendship = Friendship::model()->findByAttributes(array('receiver'=>$user->id)); 
	if($friendship){
	echo "1";  //now asker, was genius before
	}else{
	echo "2";  //now asker, never be genius
	}
	}else if($user->user_type == 2){
	$friendship = Friendship::model()->findByAttributes(array('sender'=>$user->id));
	if($friendship){
	echo "3";  //now genius, was asker before
	}else{
	echo "4";  //now genius, never be asker
	}
	}
	}



	public function actionLivechat(){

$else = $_POST['else'];
$body = $_POST['message'];
$online = $_POST['online'];

$other = Users::model()->findByAttributes(array('username'=>$else));
$me = Users::model()->findByPk(Yii::app()->user->id);

$message = MailboxConversation::model()->find(array(
 'condition'=>'((initiator_id=:self AND interlocutor_id=:else) OR (initiator_id=:else AND interlocutor_id=:self)) AND livechat = 1',
 'params' => array(
 ':self'=>Yii::app()->user->id,
 ':else'=>$other->id
                 ),
));

if($message){
$message->modified = time();
$message->initiator_id = $me->id;
$message->interlocutor_id = $other->id;
$message->creceiver = $other->id;
$message->csender = $me->id;
}else{
$message = new MailboxConversation;  //new conversation
$message->initiator_id = $me->id;
$message->interlocutor_id = $other->id;
$message->creceiver = $other->id;
$message->csender = $me->id;
$message->modified = time();
$message->subject = "Chat between ".$me->username." and ".$other->username;
$message->livechat = 1;
}

//test if user is online…if online, mark read as 3 otherwise, 0;
if($online > 0){
$message->bm_read = 3; //we need to load the history into the chat box...
}else{
$message->bm_read = 0;
}
$message->save(false);


$text = new MailboxMessage;  //new message
$text->conversation_id = $message->conversation_id;
$text->created = time();
$text->sender_id = $me->id;
$text->recipient_id = $other->id;
$text->text = $body;
$text->crc64 = 0;
$text->save(false);

echo "success";

	}




	public function actionGetpic(){
	$user = Users::model()->findByAttributes(array('username'=>$_GET['username']));
	echo $user->profile->avatar;
	}


	public function actionGetgroup(){
	$user = Users::model()->findByAttributes(array('username'=>$_GET['username']));
	$receiver = Friendship::model()->findByAttributes(array('sender'=>$user->id,'receiver'=>Yii::app()->user->id));
	$sender = Friendship::model()->findByAttributes(array('receiver'=>$user->id,'sender'=>Yii::app()->user->id));
	if($receiver){
	echo "My Students";
	}
	if($sender){
	echo "My Tutors";
	}
	}


	public function actionChatauth(){

$sessionid = $_GET['id']; 

//$session = Session::model()->findByPk($sessionid);
//$user = Users::model()->findByPk($session->user_id);

$user = Users::model()->findByAttributes(array('activkey'=>$sessionid));

echo json_encode(array('username' => $user->username));

	}



	public function actionGetfriends(){

$sessionid = $_GET['id']; 
$user = Users::model()->findByAttributes(array('activkey'=>$sessionid));

 $friends = Friendship::model()->findAll(array(
 'condition'=>'sender=:uid OR receiver=:uid',
 'params' => array(
 ':uid'=>$user->id,
                 ),
));

$arr= array();

foreach($friends as $friend){

if($user->id == $friend->receiver){
$guy = Users::model()->findByPk($friend->sender);
}else{
$guy = Users::model()->findByPk($friend->receiver);
}

$session = Session::model()->findByAttributes(array('user_id'=>$guy->id));

if($user->id == $friend->receiver){ //I am the genius

if($session){
$json[$friend->senderx->username] = array('status'=>array('available',''),'group'=>"My Students", 'profile'=>$guy->profile->avatar);
}else{
$json[$friend->senderx->username] = array('status'=>array('offline',''),'group'=>"My Students", 'profile'=>$guy->profile->avatar);
}

}else{ //I am the user

if($session){
$json[$friend->receiverx->username] = array('status'=>array('available',''),'group'=>"My Tutors", 'profile'=>$guy->profile->avatar);
}else{
$json[$friend->receiverx->username] = array('status'=>array('offline',''),'group'=>"My Tutors", 'profile'=>$guy->profile->avatar);
}

}
}

echo "[".json_encode($json)."]"; 
	}



	public function actionResend(){
	//re-send verification email…

	$from = $_GET['from'];

if(isset(Yii::app()->session['v_uid'])) {
	$user = Users::model()->findByPk(Yii::app()->session['v_uid']);
	if($user && !$user->status):
	//resend email
	
$activation_url =Yii::app()->createAbsoluteUrl('/user/activation/activation', array(
"activkey" => $user->activkey, "email" => $user->email,"from"=>$from));			
$message       = new YiiMailMessage;
$message->view = 'registration2';
$message->setBody(array('model' => $user, 'activation_url' => $activation_url, 'from'=>$from), 'text/html');
$message->setSubject('Welcome to '.$from.' - please verify your email address');
$message->addTo($user->email);
$message->setFrom(array('no-reply@'.$from.'.com' => $from));
Yii::app()->mail->send($message);

	endif;

} //if session
	}





  public function actionDownload()
    {
    if(isset($_GET['path']) && isset($_GET['filename'])){
    
/*
$_GET['filename'] has to be with extension
$OriginalUrl has to be in format: "/var/www/html/qanda/uploads/avatar/1/xunknown.jpg";
*/

$OriginalUrl = '/var/www/html/qanda/'.$_GET['path'];

$req = new CHttpRequest();

$req->xSendFile($OriginalUrl, array("terminate"=>false, "saveName"=>$_GET['filename']));

    }else{
    echo "FILE DOES NOT EXIST.";
    }
    }




	public function actions() {
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha' => array(
				'class' => 'CCaptchaAction',
				'backColor' => 0xFFFFFF
			),
				
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page' => array(
				'class' => 'CViewAction'
			)
		);
	}


	public function actionImpress(){
		$aid = $_GET['aid'];
		header('Content-type: image/gif');
		readfile('https://www.createpool.com/images/transparent.gif');
		$ad = Ads::model()->findByPk($aid);
		if($ad):
	$rand = rand(0, 10);
if($rand > 3){
		$ad->impressions++;
		$ad->today_impressions++;
		$ad->week_impressions++;
		$ad->month_impressions++;
}
		$ad->active = 1;
		$ad->save();
		endif;
	}


	public function actionMyrobots(){
	$this->renderPartial('myrobots');
	}


	public function actionInstruction(){
	$this->render('instruction');
	}


	public function actionSignup(){


	if(!isset($_GET['from'])){
		$this->redirect('https://www.createpool.com/site/signup/from/createpool');
	}

	if(!Yii::app()->user->isGuest){
	//Log the user in!!!!????
	}


		$model = new RegistrationForm;
		$profile          = new Profile;
		$profile->regMode = true;

$this->render('signup',array('model'=>$model,'profile' => $profile));
			
			
	
}



	public function actiondoneedit(){
		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}

	$this->render('doneedit');
	}


	public function actionMembership() {

		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}

		$user = Users::model()->findByPk(Yii::app()->user->id);
		$old_membership = Membership::model()->findByAttributes(array('user_id'=>Yii::app()->user->id,'status'=>1));
		if($user->profile->gold == 2 && $old_membership){	
		//$this->redirect('upgrade');
		}

		$token = Authorize::model()->findByAttributes(array(
			'user_id' => Yii::app()->user->id
		));
		$credit = new Creditcard;
		$billing = new Billing;
		$membership = new Membership;
		
		$this->layout = 'column1';
		
		if(isset($_POST['Membership'])) {
			$membership->type_id = $_POST['Membership']['type_id'];
			$credit->attributes = $_POST['Creditcard'];
			$billing->attributes = $_POST['Billing'];
			
			if($user->subscriber == 0) {
				if($membership->type_id == 1){
											
					if(true){
						$payer = Users::model()->findByPk(Yii::app()->user->id);
						$existcustomer = Authorize::model()->findByAttributes(array('user_id' => $payer->id));
					
						$request = new AuthorizeNetCIM;
						$request->setSandbox(false);
						$response1 = null;
						$response2 = null;
						if($credit->card_num || $credit->exp_year || $credit->exp_month || $credit->exp_year || $credit->cvc) { //new card

							$credit->scenario = 'newcard';
							
							// Create new customer profile
							$customerProfile = new AuthorizeNetCustomer;
							$customerProfile->description = $payer->username;
							$customerProfile->merchantCustomerId = time() . rand(1, 10);
							$customerProfile->email = $payer->email;
							$response = $request->createCustomerProfile($customerProfile);
							
							if($response->isOk()) {
								$customerProfileId = $response->getCustomerProfileId();
								///////////////////////////////////////////////////////
								
								// Add payment profile.
								$paymentProfile = new AuthorizeNetPaymentProfile;
								$paymentProfile->customerType = "individual";
								$paymentProfile->payment->creditCard->cardNumber = $credit->card_num;
								$paymentProfile->payment->creditCard->expirationDate = $credit->exp_year . '-' . $credit->exp_month;
								$response1 = $request->createCustomerPaymentProfile($customerProfileId, $paymentProfile);
								if($response1->isOk()) {
									$paymentProfileId = $response1->getPaymentProfileId();
									/////////////////////////////////////////////////////
									
									// Add shipping address.
									$address = new AuthorizeNetAddress;
									$address->firstName = $billing->firstname;
									$address->lastName = $billing->lastname;
									$address->address = $billing->address1 . ' ' . $billing->address2;
									$address->city = $billing->city;
									$address->country = $billing->country;
									if($address->country == "US") {
										$address->state = $billing->state;
									}
									$address->zip = $billing->zip;
									$address->phoneNumber = $billing->phone;
									$response2 = $request->createCustomerShippingAddress($customerProfileId, $address);
									if($response2->isOk()) {
										$customerAddressId = $response2->getCustomerAddressId();
										//////////////////////////////////////////////////////
										
										if($existcustomer) {
											$existcustomer->customerProfileId = $customerProfileId;
											$existcustomer->paymentProfileId = $paymentProfileId;
											$existcustomer->customerAddressId = $customerAddressId;
											$existcustomer->create_time = time();
											$existcustomer->save();
										} else {
											$existcustomer = New Authorize;
											$existcustomer->user_id = Yii::app()->user->id;
											$existcustomer->customerProfileId = $customerProfileId;
											$existcustomer->paymentProfileId = $paymentProfileId;
											$existcustomer->customerAddressId = $customerAddressId;
											$existcustomer->create_time = time();
											$existcustomer->save();
										}
										
									}
								}
							} else { //response  not OK
								throw new CHttpException(400, $response->getErrorMessage());
								
							}
							
						}
					
						
						if($existcustomer) {

						$customerProfileId = $existcustomer->customerProfileId;
						$paymentProfileId = $existcustomer->paymentProfileId;
						$customerAddressId = $existcustomer->customerAddressId;

						// Create Auth & Capture Transaction

							$transaction_auth = new AuthorizeNetTransaction;
							$lineItem = new AuthorizeNetLineItem;
							$lineItem->itemId = Yii::app()->user->id;
							$lineItem->name = "createpool";
							$lineItem->description = "createpool Gold Membership";
							$lineItem->quantity = '1';
							$lineItem->unitPrice = 14.95;
							$lineItem->taxable = false;
							$transaction_auth->lineItems[] = $lineItem;
							
							$transaction_auth->amount = 14.95;
							$transaction_auth->customerProfileId = $customerProfileId;
							$transaction_auth->customerPaymentProfileId = $paymentProfileId;
							$transaction_auth->customerShippingAddressId = $customerAddressId;
							
							//Charge them now
							$response = $request->createCustomerProfileTransaction("AuthCapture", $transaction_auth);
							$transactionResponse = $response->getTransactionResponse();
							
							if($transactionResponse->approved){
								$membership->user_id = Yii::app()->user->id;
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$profile->gold = 2;
			$profile->save(false);
								$membership->status = 1;
								$membership->type_id = 1;
								$membership->date = time();
								$membership->save();

Yii::app()->user->setFlash('success', 'You have successfully subscribed createpool Gold Pro Membership.');
$this->redirect(array('upgrade'));
							
							} else {
								$credit->addError('card_num', $response->getErrorMessage());
							} //transaction goes through
						} else {
							if ($response1) {
								$credit->addError('card_num', $response1->getErrorMessage());
							}
							if ($response2) {
								$billing->addError('address1', $response2->getErrorMessage());
							}
						}		
										
					} else {
						$credit->addError('card_num', 'Please ensure you correctly entered your credit card information.');
					}	
					
				} else if($membership->type_id == 2){
					$payer = Users::model()->findByPk(Yii::app()->user->id);
										
					$PayPalMode         = 'live'; // sandbox or live
					$PayPalApiUsername  = 'contact_api2.likeplum.com'; //PayPal API Username
					$PayPalApiPassword  = 'KZ55TVRNSN9Q78QK'; //Paypal API password
					$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31ADczAMSWpxxZNn6akfDzEKLHVajS';
					$PayPalCurrencyCode = 'USD'; //Paypal Currency Code
					$PayPalReturnURL    = Yii::app()->request->hostInfo . '/site/paypal'; //Point to process.php page
					$PayPalCancelURL    = Yii::app()->request->hostInfo . '/site/membership'; //Cancel URL if user clicks cancel
										
					$ItemName       = "createpool Gold Membership"; //Item Name
					$ItemPrice      = 14.95; //Item Price
					$ItemNumber     = 1; //Item Number
					$ItemQty        = 1; // Item Quantity
					$ItemTotalPrice = 14.95;
					$billingType = 'RecurringPayments';
					$billingAgreementDescription = urlencode('createpool Gold Member subscription');

					
					//Data to be sent to paypal
					
					$padata = 
					'&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) . 
					'&PAYMENTACTION=Sale' . 
					'&ALLOWNOTE=1' . 
					'&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) . 
					'&PAYMENTREQUEST_0_AMT=' . urlencode($ItemTotalPrice) . 
					'&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) . 
					'&L_PAYMENTREQUEST_0_QTY0=' . urlencode($ItemQty) . 
					'&L_PAYMENTREQUEST_0_AMT0=' . urlencode($ItemPrice) . 
					'&L_PAYMENTREQUEST_0_NAME0=' . urlencode($ItemName) . 
					'&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($ItemNumber) . 
					'&AMT=' . urlencode($ItemTotalPrice) . 
					'&RETURNURL=' . urlencode($PayPalReturnURL) . 
					'&CANCELURL=' . urlencode($PayPalCancelURL).
					'&L_BILLINGTYPE0=' . $billingType . 
					'&L_BILLINGAGREEMENTDESCRIPTION0=' . $billingAgreementDescription. 
					'&MAXAMT=120';
					
					
					//We need to execute the "SetExpressCheckOut" method to obtain paypal token..
					$paypal               = new MyPayPal();
					$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
					
					//Respond according to message we receive from Paypal
					if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
						
						if($PayPalMode=='sandbox'){
							$paypalmode 	=	'.sandbox';
						} else {
							$paypalmode 	=	'';
						}
						
						//Redirect user to PayPal store with Token received.
					 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
						header('Location: '.$paypalurl);
						
					} else {
						exit('<pre>CreateRecurringPaymentsProfile failed: ' . print_r($httpParsedResponseAr, true));
					}

					
				} // if type_id == 2
										
			} else {
				$membership->addError('status','You are already a createpool Gold Pro member.');
			}
		}
		$this->render('membership', array(
			'user' => $user,
			'token' => $token,
			'credit' => $credit,
			'billing' => $billing,
			'membership' => $membership
		));
	}
	
		public function actionPaypal() {
		
		if(isset($_GET["token"]) && isset($_GET["PayerID"])) {
			$token    = $_GET["token"];
			$playerid = $_GET["PayerID"];
			
			$padata = "&TOKEN=" . urlencode($token) . "&PAYERID=" . urlencode($playerid). "&METHOD=CreateRecurringPaymentsProfile"
        . "&AMT=" . urlencode( '14.95' )
        . "&CURRENCYCODE=USD"
        . "&PROFILESTARTDATE=" . date( 'Y-m-d\TH:i:s', time() )
        . "&BILLINGPERIOD=Month"
        . "&BILLINGFREQUENCY=1"
        . "&DESC=".urlencode('createpool Gold Member subscription')
        . "&INITAMT=".urlencode('0')
. "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital"
. "&L_PAYMENTREQUEST_0_NAME0=createpool Gold Membership"
. "&L_PAYMENTREQUEST_0_AMT0=14.95"
. "&L_PAYMENTREQUEST_0_QTY0=1"
. "&MAXAMT=120";


			//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
			
			$PayPalMode         = 'live'; // sandbox or live
			$PayPalApiUsername  = 'contact_api2.likeplum.com'; //PayPal API Username
			$PayPalApiPassword  = 'KZ55TVRNSN9Q78QK'; //Paypal API password
			$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31ADczAMSWpxxZNn6akfDzEKLHVajS';
			$PayPalCurrencyCode = 'USD'; //Paypal Currency Code
			$PayPalReturnURL    = Yii::app()->request->hostInfo . '/site/paypal'; //Point to process.php page
			$PayPalCancelURL    = Yii::app()->request->hostInfo . '/site/membership'; //Cancel URL if user clicks cancel.
			
			$paypal               = new MyPayPal();
			$httpParsedResponseAr = $paypal->PPHttpPost('CreateRecurringPaymentsProfile', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			
			//Check if everything went ok..
			
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {


			$membership = new Membership;

  	if(isset($httpParsedResponseAr["PROFILEID"])){
  	$membership->paypal_id = $httpParsedResponseAr["PROFILEID"];
	}

  	if(isset($httpParsedResponseAr["PROFILESTATUS"])){
  	$membership->paypal_status = $httpParsedResponseAr["PROFILESTATUS"];
  	}
			$membership->type_id = 2;
			$membership->user_id = Yii::app()->user->id;
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$profile->gold = 2;
			$profile->save(false);
			$membership->status = 1;
			$membership->date = time();
			$membership->save();

Yii::app()->user->setFlash('success', 'You have successfully subscribed createpool Gold Pro Membership.');
				$this->redirect(array('upgrade'));
				
			} //SUCCESS
			
			else { //if failure
/*
//Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
*/
				throw new CHttpException(400, 'Sorry, your payment is declined by Paypal. Please contact us at contact@createpool.com to solve the problem or use your credit card to pay.');

			}
			
		} //POST
		
		else {
			throw new CHttpException(404, 'The requested link does not exist.');
		}
		
	}






	public function actionAskermembership() {

		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}

		$user = Users::model()->findByPk(Yii::app()->user->id);
		$old_membership = Membership::model()->findByAttributes(array('user_id'=>Yii::app()->user->id,'status'=>1));
		if($user->profile->gold == 2 && $old_membership){	
		//$this->redirect('upgrade');
		}

		$token = Authorize::model()->findByAttributes(array(
			'user_id' => Yii::app()->user->id
		));
		$credit = new Creditcard;
		$billing = new Billing;
		$membership = new Membership;
		
		$this->layout = 'column1';
		
		if(isset($_POST['Membership'])) {
			$membership->type_id = $_POST['Membership']['type_id'];
			$credit->attributes = $_POST['Creditcard'];
			$billing->attributes = $_POST['Billing'];
			
			if($user->subscriber == 0) {
				if($membership->type_id == 1){
										
					if(true){
						$payer = Users::model()->findByPk(Yii::app()->user->id);
						$existcustomer = Authorize::model()->findByAttributes(array('user_id' => $payer->id));
					
						$request = new AuthorizeNetCIM;
						$request->setSandbox(false);
						$response1 = null;
						$response2 = null;
						if($credit->card_num || $credit->exp_year || $credit->exp_month || $credit->exp_year || $credit->cvc) { //new card

							$credit->scenario = 'newcard';
							
							// Create new customer profile
							$customerProfile = new AuthorizeNetCustomer;
							$customerProfile->description = $payer->username;
							$customerProfile->merchantCustomerId = time() . rand(1, 10);
							$customerProfile->email = $payer->email;
							$response = $request->createCustomerProfile($customerProfile);
							
							if($response->isOk()) {
								$customerProfileId = $response->getCustomerProfileId();
								///////////////////////////////////////////////////////
								
								// Add payment profile.
								$paymentProfile = new AuthorizeNetPaymentProfile;
								$paymentProfile->customerType = "individual";
								$paymentProfile->payment->creditCard->cardNumber = $credit->card_num;
								$paymentProfile->payment->creditCard->expirationDate = $credit->exp_year . '-' . $credit->exp_month;
								$response1 = $request->createCustomerPaymentProfile($customerProfileId, $paymentProfile);
								if($response1->isOk()) {
									$paymentProfileId = $response1->getPaymentProfileId();
									/////////////////////////////////////////////////////
									
									// Add shipping address.
									$address = new AuthorizeNetAddress;
									$address->firstName = $billing->firstname;
									$address->lastName = $billing->lastname;
									$address->address = $billing->address1 . ' ' . $billing->address2;
									$address->city = $billing->city;
									$address->country = $billing->country;
									if($address->country == "US") {
										$address->state = $billing->state;
									}
									$address->zip = $billing->zip;
									$address->phoneNumber = $billing->phone;
									$response2 = $request->createCustomerShippingAddress($customerProfileId, $address);
									if($response2->isOk()) {
										$customerAddressId = $response2->getCustomerAddressId();
										//////////////////////////////////////////////////////
										
										if($existcustomer) {
											$existcustomer->customerProfileId = $customerProfileId;
											$existcustomer->paymentProfileId = $paymentProfileId;
											$existcustomer->customerAddressId = $customerAddressId;
											$existcustomer->create_time = time();
											$existcustomer->save();
										} else {
											$existcustomer = New Authorize;
											$existcustomer->user_id = Yii::app()->user->id;
											$existcustomer->customerProfileId = $customerProfileId;
											$existcustomer->paymentProfileId = $paymentProfileId;
											$existcustomer->customerAddressId = $customerAddressId;
											$existcustomer->create_time = time();
											$existcustomer->save();
										}
										
									}
								}
							} else { //response  not OK
								throw new CHttpException(400, $response->getErrorMessage());
								
							}
							
						}
					
						
						if($existcustomer) {

						$customerProfileId = $existcustomer->customerProfileId;
						$paymentProfileId = $existcustomer->paymentProfileId;
						$customerAddressId = $existcustomer->customerAddressId;

						// Create Auth & Capture Transaction

							$transaction_auth = new AuthorizeNetTransaction;
							$lineItem = new AuthorizeNetLineItem;
							$lineItem->itemId = Yii::app()->user->id;
							$lineItem->name = "createpool";
							$lineItem->description = "createpool Gold Membership";
							$lineItem->quantity = '1';
							$lineItem->unitPrice = 4.85;
							$lineItem->taxable = false;
							$transaction_auth->lineItems[] = $lineItem;
							
							$transaction_auth->amount = 4.85;
							$transaction_auth->customerProfileId = $customerProfileId;
							$transaction_auth->customerPaymentProfileId = $paymentProfileId;
							$transaction_auth->customerShippingAddressId = $customerAddressId;
							
							//Charge them now
							$response = $request->createCustomerProfileTransaction("AuthCapture", $transaction_auth);
							$transactionResponse = $response->getTransactionResponse();
							
							if($transactionResponse->approved){
								$membership->user_id = Yii::app()->user->id;
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$profile->gold_asker = 3;
			$profile->save(false);
								$membership->status = 1;
								$membership->type_id = 1;
								$membership->date = time();
								$membership->save();

Yii::app()->user->setFlash('success', 'You have successfully subscribed createpool Gold Pro Membership.');
$this->redirect(array('upgrade'));
							
							} else {
								$credit->addError('card_num', $response->getErrorMessage());
							} //transaction goes through
						} else {
							if ($response1) {
								$credit->addError('card_num', $response1->getErrorMessage());
							}
							if ($response2) {
								$billing->addError('address1', $response2->getErrorMessage());
							}
						}		
										
					} else {
						$credit->addError('card_num', 'Please ensure you correctly entered your credit card information.');
					}	
					
				} else if($membership->type_id == 2){
					$payer = Users::model()->findByPk(Yii::app()->user->id);
										
					$PayPalMode         = 'live'; // sandbox or live
					$PayPalApiUsername  = 'contact_api2.likeplum.com'; //PayPal API Username
					$PayPalApiPassword  = 'KZ55TVRNSN9Q78QK'; //Paypal API password
					$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31ADczAMSWpxxZNn6akfDzEKLHVajS';
					$PayPalCurrencyCode = 'USD'; //Paypal Currency Code
					$PayPalReturnURL    = Yii::app()->request->hostInfo . '/site/paypalasker'; //Point to process.php page
					$PayPalCancelURL    = Yii::app()->request->hostInfo . '/site/membership'; //Cancel URL if user clicks cancel
										
					$ItemName       = "createpool Gold Membership"; //Item Name
					$ItemPrice      = 4.85; //Item Price
					$ItemNumber     = 1; //Item Number
					$ItemQty        = 1; // Item Quantity
					$ItemTotalPrice = 4.85;
					$billingType = 'RecurringPayments';
					$billingAgreementDescription = urlencode('createpool Gold Member subscription');

					
					//Data to be sent to paypal
					
					$padata = 
					'&CURRENCYCODE=' . urlencode($PayPalCurrencyCode) . 
					'&PAYMENTACTION=Sale' . 
					'&ALLOWNOTE=1' . 
					'&PAYMENTREQUEST_0_CURRENCYCODE=' . urlencode($PayPalCurrencyCode) . 
					'&PAYMENTREQUEST_0_AMT=' . urlencode($ItemTotalPrice) . 
					'&PAYMENTREQUEST_0_ITEMAMT=' . urlencode($ItemTotalPrice) . 
					'&L_PAYMENTREQUEST_0_QTY0=' . urlencode($ItemQty) . 
					'&L_PAYMENTREQUEST_0_AMT0=' . urlencode($ItemPrice) . 
					'&L_PAYMENTREQUEST_0_NAME0=' . urlencode($ItemName) . 
					'&L_PAYMENTREQUEST_0_NUMBER0=' . urlencode($ItemNumber) . 
					'&AMT=' . urlencode($ItemTotalPrice) . 
					'&RETURNURL=' . urlencode($PayPalReturnURL) . 
					'&CANCELURL=' . urlencode($PayPalCancelURL).
					'&L_BILLINGTYPE0=' . $billingType . 
					'&L_BILLINGAGREEMENTDESCRIPTION0=' . $billingAgreementDescription. 
					'&MAXAMT=120';
					
					
					//We need to execute the "SetExpressCheckOut" method to obtain paypal token..
					$paypal               = new MyPayPal();
					$httpParsedResponseAr = $paypal->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
					
					//Respond according to message we receive from Paypal
					if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
						
						if($PayPalMode=='sandbox'){
							$paypalmode 	=	'.sandbox';
						} else {
							$paypalmode 	=	'';
						}
						
						//Redirect user to PayPal store with Token received.
					 	$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
						header('Location: '.$paypalurl);
						
					} else {
						exit('<pre>CreateRecurringPaymentsProfile failed: ' . print_r($httpParsedResponseAr, true));
					}

					
				} // if type_id == 2
										
			} else {
				$membership->addError('status','You are already a createpool Gold Pro member.');
			}
		}
		$this->render('askermembership', array(
			'user' => $user,
			'token' => $token,
			'credit' => $credit,
			'billing' => $billing,
			'membership' => $membership
		));
	}





public function actionPaypalasker() {
		
		if(isset($_GET["token"]) && isset($_GET["PayerID"])) {
			$token    = $_GET["token"];
			$playerid = $_GET["PayerID"];
			
			$padata = "&TOKEN=" . urlencode($token) . "&PAYERID=" . urlencode($playerid). "&METHOD=CreateRecurringPaymentsProfile"
        . "&AMT=" . urlencode( '4.85' )
        . "&CURRENCYCODE=USD"
        . "&PROFILESTARTDATE=" . date( 'Y-m-d\TH:i:s', time() )
        . "&BILLINGPERIOD=Month"
        . "&BILLINGFREQUENCY=1"
        . "&DESC=".urlencode('createpool Gold Member subscription')
        . "&INITAMT=".urlencode('0')
. "&L_PAYMENTREQUEST_0_ITEMCATEGORY0=Digital"
. "&L_PAYMENTREQUEST_0_NAME0=createpool Gold Membership"
. "&L_PAYMENTREQUEST_0_AMT0=4.85"
. "&L_PAYMENTREQUEST_0_QTY0=1"
. "&MAXAMT=120";


			//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
			
			$PayPalMode         = 'live'; // sandbox or live
			$PayPalApiUsername  = 'contact_api2.likeplum.com'; //PayPal API Username
			$PayPalApiPassword  = 'KZ55TVRNSN9Q78QK'; //Paypal API password
			$PayPalApiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31ADczAMSWpxxZNn6akfDzEKLHVajS';
			$PayPalCurrencyCode = 'USD'; //Paypal Currency Code
			$PayPalReturnURL    = Yii::app()->request->hostInfo . '/site/paypalasker'; //Point to process.php page
			$PayPalCancelURL    = Yii::app()->request->hostInfo . '/site/membership'; //Cancel URL if user clicks cancel.
			
			$paypal               = new MyPayPal();
			$httpParsedResponseAr = $paypal->PPHttpPost('CreateRecurringPaymentsProfile', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			
			//Check if everything went ok..
			
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {


			$membership = new Membership;

  	if(isset($httpParsedResponseAr["PROFILEID"])){
  	$membership->paypal_id = $httpParsedResponseAr["PROFILEID"];
	}

  	if(isset($httpParsedResponseAr["PROFILESTATUS"])){
  	$membership->paypal_status = $httpParsedResponseAr["PROFILESTATUS"];
  	}
			$membership->type_id = 2;
			$membership->user_id = Yii::app()->user->id;
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$profile->gold_asker = 3;
			$profile->save(false);
			$membership->status = 1;
			$membership->date = time();
			$membership->save();

Yii::app()->user->setFlash('success', 'You have successfully subscribed createpool Gold Pro Membership.');
				$this->redirect(array('upgrade'));
				
			} //SUCCESS
			
			else { //if failure
/*
//Show error message
			echo '<div style="color:red"><b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			echo '<pre>';
			print_r($httpParsedResponseAr);
			echo '</pre>';
*/
				throw new CHttpException(400, 'Sorry, your payment is declined by Paypal. Please contact us at contact@createpool.com to solve the problem or use your credit card to pay.');

			}
			
		} //POST
		
		else {
			throw new CHttpException(404, 'The requested link does not exist.');
		}
		
	}



	
	public function actionUpgrade() {

		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}

		$user = Users::model()->findByPk(Yii::app()->user->id);
		if($user->user_type == 1) {
			$this->render('asker_membership', array(
				'user' => $user
			));
		} else if($user->user_type == 2) {
			$this->render('answer_membership', array(
				'user' => $user
			));
		} else {
			$this->redirect(Yii::app()->request->hostInfo . "/site/identity");
		}
		
	}
	

	public function actionRecommend() {
		$this->redirect('https://www.createpool.com/affiliate');
	}
	
	public function actionButton() {
		$this->render('button');
	}
	
	public function actionSearch() {
		$this->render('search');
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError() {
		if($error = Yii::app()->errorHandler->error) {
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	
	
	public function actionIdentity() {
		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}
	/*	
		if(isset($_POST['Users'])) {
			$model->attributes = $_POST['Users'];
			if($model->ask) {
				$model->user_type = 1;
			} else if($model->answer) {
				$model->user_type = 2;
			}
			$model->save();
		}
	*/	
		$this->render('identity', array(
	//		'model' => $model
		));
	}
	
	
	public function actionBeasker() {
		if(Yii::app()->user->isGuest) {
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}
		$model = Users::model()->findByPk(Yii::app()->user->id);
		$model->user_type = 1;
		$model->save();
		if(isset($_GET['redirect'])) {
			$this->redirect($_GET['redirect']); // redirect to specific url 
		} else {
			$this->redirect(Yii::app()->request->hostInfo . "/home");
		}
	}
	
	
	
	public function actionBeanswerer() {
		if(Yii::app()->user->isGuest) {
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}
		$model = Users::model()->findByPk(Yii::app()->user->id);
		$model->user_type = 2;
		$model->save();
		if(isset($_GET['redirect'])) {
			$this->redirect($_GET['redirect']); //redirect to specific url
		} else {
			$this->redirect(Yii::app()->request->hostInfo . "/home");
		}
	}



	public function actionMemberAccept() {

		$profile = Profile::model()->findByPk(Yii::app()->user->id);

		if(Yii::app()->user->isGuest || !$profile->gold_asker || $profile->avatar == "https://www.createpool.com/pictures/unknown.jpg") {
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}

		$profile->gold_asker = 1;
		$profile->save(false);
			$this->redirect(Yii::app()->request->hostInfo); //redirect to specific url
	}



	public function actionTutorAccept() {

		$profile = Profile::model()->findByPk(Yii::app()->user->id);

		if($profile->gold > 2 && $profile->bio){
		$profile->gold = 1;
		$profile->save(false);
		}
			$this->redirect(Yii::app()->request->hostInfo); //redirect to specific url
	}



	protected function changeReview(){ 
	$review = new Review;
	if(isset($_POST['Review']['id'])){
	$review = Review::model()->findByPk($_POST['Review']['id']);
	$review->attributes=$_POST['Review'];
if(Yii::app()->user->id == $review->commenter_id && time() - $review->create_time < 1209600){

	$all = $review->speed + $review->quality + $review->professionalism;
	$review->rating = round( ($all/3) , 1);
        $review->create_time = time();

	if($review->rating < 2){
		$review->power = 2;
	}

	$review->nowork = 0;
	if($review->save()){

//push notification

$message = new MailboxConversation;  //new conversation
$message->initiator_id = 1;
$message->interlocutor_id = $review->user_id;
$message->is_system = 'yes';
$message->creceiver = $review->user_id;
$message->csender = 1;
$message->questions_id = $review->questions_id;
$message->type_id = 2; //important, means review
$message->modified = time();
$message->subject = $review->commenter->username." has edited the review of you.";
$message->save(false);


	}
}
	}
	return $review;
	}




	
	public function actionFeed() {
		if(Yii::app()->user->isGuest) {
			$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
		}else{
			$this->redirect('https://www.studypool.com');
		}
		$model = Users::model()->findByPk(Yii::app()->user->id);
		$profile = $model->profile;
		
		if(!$model->user_type) {
			$this->redirect(Yii::app()->request->hostInfo . "/site/identity");
		}

		if($model->affiliate){
		$model->affiliate = 0;
		$model->save(false);
		$this->redirect(Yii::app()->request->hostInfo . "/user/profile/affiliate");
		}
		
		$pastdue = Pool::model()->find(array(
			'condition' => 'owner_id=:uId AND free = 0 AND paid = 0 AND real_answers > 0 AND withdrawed = 2 AND UNIX_TIMESTAMP(create_time) < :time - time_limit',
			'params' => array(
				':uId' => Yii::app()->user->id,
				':time' => time()
			)
		));
		if($pastdue) {
			$pastdue->id = $pastdue->questions_id;
		}
		
		if($pastdue && strstr(Yii::app()->request->requestUri, '?', true) !== "/questions/" . $pastdue->id) {
			$profile = Profile::model()->findByPk(Yii::app()->user->id);
			$profile->remindpay = 1;
			$profile->save(false);
			header('Location: ' . Yii::app()->request->hostInfo . '/questions/' . $pastdue->id);
		}
		
		if($model) {
			$model->ip = $_SERVER['REMOTE_ADDR'];
			$model->lastaction = time();
			$model->save(false);
		}

		$transfer = null;

		if(CUploadedFile::getInstancesByName('files1')){
                $profile->files=CUploadedFile::getInstancesByName('files1');
		}else if(CUploadedFile::getInstancesByName('files2')){
                $profile->files=CUploadedFile::getInstancesByName('files2');
		}else if(CUploadedFile::getInstancesByName('files3')){
                $profile->files=CUploadedFile::getInstancesByName('files3');
		}else if(CUploadedFile::getInstancesByName('files4')){
                $profile->files=CUploadedFile::getInstancesByName('files4');
		}else if(CUploadedFile::getInstancesByName('files5')){
                $profile->files=CUploadedFile::getInstancesByName('files5');
		}
if($profile->files){
$files=$profile->files;
if (isset($files) && count($files) > 0){
$fileSavePath = "uploads/avatar/".$model->id."/";
if (!file_exists ($fileSavePath)){
mkdir ($fileSavePath, 0777, true);
                                 }
if (($profile->avatar)&&(file_exists($profile->avatar))){
unlink($profile->avatar);   
}
foreach ($files as $file =>$pic ){
$filename = strtolower(preg_replace('/[^a-zA-Z0-9\.]/', '_', $pic));
$pic->saveAs("uploads/avatar/".$model->id."/".$filename); 
$thumb = new imagick("uploads/avatar/".$model->id."/".$filename);
$thumb->setImageFormat( "png" );
$thumb->thumbnailImage( 135, 135);
$thumb->writeImage("uploads/avatar/".$model->id."/x".$filename);
 if (file_exists ("uploads/avatar/".$model->id."/".$filename)){
unlink("uploads/avatar/".$model->id."/".$filename); 
}
$profile->avatar = Yii::app()->request->hostInfo."/uploads/avatar/".$model->id."/x".$filename;
$profile->save(false);
}
}
}		
		$time2 = date('Y-m-d H:i:s', time() - 2419200); //recent 28 days	
		if($model->user_type == 2):
			$worksDataProvider = new CActiveDataProvider('Mile', array(
				'criteria' => array(
					'with' => array(
						'question',
						'question.resolution',
					),
					'condition' => 'accept > 0 AND receiver=:userId AND (question.withdrawed = 0 OR resolution.solve = 0 OR (question.withdrawed = 2 AND t.create_time + question.time_limit + 259200 < :time )) AND (question.paid = 0 OR (question.withdrawed = 1 AND question.paid = 1)  OR (question.paid = 1 AND UNIX_TIMESTAMP(question.create_time) + question.time_limit + 259200 >= :time))',
					'params' => array(
						':userId' => $model->id,
						':time'=>time()
					)
				),
				'sort' => array(
					'defaultOrder' => 't.expert_read ASC, t.create_time ASC' // this is it.
				),
				'pagination' => false,
			));
			$works = $worksDataProvider->getData();


$premsDataProvider = new CActiveDataProvider('Mile', array(
				'criteria' => array(
					'with' => array(
						'question'
					),
					'condition' => 'receiver=:userId AND accept != 2 AND (question.closed != 1 OR accept != 1) AND question.withdrawed != 1',
					'params' => array(
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 'accept DESC' // this is it.
				),
				'pagination'=>false,
			));
			$prems = $premsDataProvider->getData();


		endif;
		if($model->user_type == 1):

			$change = $this->changeReview();

			$myquestionsDataProvider = new CActiveDataProvider('Questions', array(
				'criteria' => array(
					'offset' => 0,
					'limit' => 10,
					'condition' => 'owner_id=:userId AND (closed != 1 OR withdrawed != 0 OR paid != 0)',
					'params' => array(
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => '`read` ASC,  create_time DESC' // this is it.
				),

				'pagination' => array(
					'pageSize' => 10
				)
			));
			$myquestions = $myquestionsDataProvider->getData();


			$myreviewsDataProvider = new CActiveDataProvider('Review', array(
				'criteria' => array(
'with'=>array('question'),
					'offset' => 0,
					'limit' => 8,
					'condition' => 'commenter_id=:userId AND send=2 AND t.create_time + 1209600 > :time AND question.free=0',
					'params' => array(
						':userId' => $model->id,
						':time'=>time()
					)
				),
				'sort' => array(
					'defaultOrder' => 't.create_time DESC' // this is it.
				),

				'pagination' => array(
					'pageSize' => 8
				)
			));
			$myreviews = $myreviewsDataProvider->getData();

	
			$cutsDataProvider = new CActiveDataProvider('Questions', array(
				'criteria' => array(
					'offset' => 0,
					'limit' => 10,
					'condition' => 'owner_id=:userId AND withdrawed = 3',
					'params' => array(
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 'create_time DESC' // this is it.
				),

				'pagination' => array(
					'pageSize' => 10
				)
			));
			$cuts = $cutsDataProvider->getData();

		endif;

		if($model->user_type == 2):

			$transfer = $this->transfer();

			$withdrawDataProvider = new CActiveDataProvider('Resolution', array(
				'criteria' => array(
					'condition' => 'genius_id = :userId AND solve = 1',
					'params' => array(
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 't.create_time DESC' // this is it.
				),
				'pagination'=>false,
			));
			$withdraw = $withdrawDataProvider->getData();


			$privateDataProvider = new CActiveDataProvider('Notification', array(
				'criteria' => array(
					'with' => array(
					'question'
					),
					'condition' => 't.owner_id=:userId AND (type_id = 19 OR type_id = 18) AND UNIX_TIMESTAMP(t.create_time) + 259200 > :time AND question.paid=0 AND question.withdrawed=0',
					'offset' => 0,
					'limit' => 100,
					'params' => array(
						':time' => time(),
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 't.type_id DESC, t.create_time DESC' // this is it.
				),
				'pagination' => array(
					'pageSize' => 10
				)
			));
			$private = $privateDataProvider->getData();
		endif;
		if($model->user_type == 2):
			$blogDataProvider = new CActiveDataProvider('Notification', array(
				'criteria' => array(
					'condition' => 'owner_id=:userId AND type_id = 20 AND UNIX_TIMESTAMP(create_time) + 604800 > :time',
					'offset' => 0,
					'limit' => 100,
					'params' => array(
						':time' => time(),
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 'create_time DESC' // this is it.
				),
				'pagination' => array(
					'pageSize' => 10
				)
			));
			$blogs = $blogDataProvider->getData();
			$blog = false;
		//$myanswers = Answers::Model()->count("owner_id=:uid", array(":uid" => $model->id));
			$myanswers = Answers::model()->findByAttributes(array(
				'owner_id' => $model->id
			));
			if($blogs || ($myanswers && !$model->no_blog)) {
				$blog = true;
			}

			$followsDataProvider = new CActiveDataProvider('Notification', array(
				'criteria' => array(
					'with'=>'question',
					'condition' => 't.owner_id=:userId AND type_id = 9 AND UNIX_TIMESTAMP(t.create_time) + 259200 > :time AND (question.closed != 1 OR question.paid != 0 OR question.withdrawed != 0 )',
					'offset' => 0,
					'limit' => 100,
					'params' => array(
						':time' => time(),
						':userId' => $model->id
					)
				),
				'sort' => array(
					'defaultOrder' => 'question.create_time DESC' // this is it.
				),
				'pagination' => array(
					'pageSize' => 30
				)
			));
			$follows = $followsDataProvider->getData();

		endif;
		
		$review = $this->newReview();
		
		if($model->user_type == 1) {
			
			$this->render('askerfeed', array(
				'model' => $model,
				'profile' => $profile,
				'review' => $review,
				
				'myquestionsDataProvider' => $myquestionsDataProvider,
				'myquestions' => $myquestions,


				'cutsDataProvider'=>$cutsDataProvider,
				'cuts'=>$cuts,

				'myreviewsDataProvider'=>$myreviewsDataProvider,
				'myreviews'=>$myreviews,
				
			));
			
		} else if($model->user_type == 2) {
			$this->render('answerfeed', array(
				'model' => $model,
				'profile' => $profile,
				
				'blogDataProvider' => $blogDataProvider,
				'blog' => $blog,
				'blogs' => $blogs,
				
				'worksDataProvider' => $worksDataProvider,
				'works' => $works,
				'review' => $review,
				
				'followsDataProvider' => $followsDataProvider,
				'follows' => $follows,

				'withdrawDataProvider'=>$withdrawDataProvider,
				'withdraw'=>$withdraw,
				
				'privateDataProvider' => $privateDataProvider,
				'private' => $private,
				
				'premsDataProvider' => $premsDataProvider,
				'prems' => $prems,

				'transfer'=>$transfer,
				
			));
		}
		
	}
	
	
	public function actionImageUpload() {
		$image = new PrequestionsPictures;
		$image->file = CUploadedFile::getInstanceByName('file');
		$image->create_time = time();
		
		if($image->validate()) {
			$date = date("YmdHis");
			$fileSavePath = "uploads/prequestions/" . $date . "/";
			if(!file_exists($fileSavePath)) {
				mkdir($fileSavePath, 0777, true);
			}
			
$filename = strtolower(preg_replace('/[^a-zA-Z0-9\.]/', '_', $image->file));

			
			$image->file->saveAs("uploads/prequestions/" . $date . "/" . $date . $filename);
			
			$image->path = "uploads/prequestions/" . $date . "/" . $date . $filename;
			$image->save();
			
			$pictures = Yii::app()->session['prepictures'];
			
			if(!is_array($pictures)) {
				$pictures = array();
			}
			
			array_push($pictures, $image->path);
			
			Yii::app()->session['prepictures'] = $pictures; //update the session
			
			
			$array = array(
				'filelink' => Yii::app()->request->hostInfo . "/uploads/prequestions/" . $date . "/" . $date . $filename
			);
			
			echo stripslashes(json_encode($array));
			
			
		}
		
		else {
			throw new CHttpException(500, CJSON::encode(array(
				'error' => 'You can only upload images here. If you want to upload  files with other formats, please choose attach files.'
			)));
		}
	}
	
	
	
	public function actionFileUpload() {
		$file = new PrequestionsFiles;
		$file->file = CUploadedFile::getInstanceByName('file');
		$file->create_time = time();
		
		if($file->validate()) {
			$date = date("YmdHis");
			$fileSavePath = "uploads/prequestions/" . $date . "/";
			if(!file_exists($fileSavePath)) {
				mkdir($fileSavePath, 0777, true);
			}
			
$filename = strtolower(preg_replace('/[^a-zA-Z0-9\.]/', '_', $file->file));
			
			$file->file->saveAs("uploads/prequestions/" . $date . "/" . $date . $filename);
			
			$file->path = "uploads/prequestions/" . $date . "/" . $date . $filename;
			$file->save();
			
			$files = Yii::app()->session['prefiles'];
			
			if(!is_array($files)) {
				$files = array();
			}
			
			array_push($files, $file->path);
			
			Yii::app()->session['prefiles'] = $files; //update the session
			
			$array = array(
				'filelink' =>Yii::app()->request->hostInfo . "/uploads/prequestions/" . $date . "/" . $date . $filename,
				'filename' => $_FILES['file']['name']
			);
			
			echo stripslashes(json_encode($array));
			
		} else {
			throw new CHttpException(500, CJSON::encode(array(
				'error' => 'Sorry, you can only upload images, documents and programming codes smaller than 20M.'
			)));
		}
	}
	


	public function actionNewlanding() {
		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/task/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/task/create');
			}
			
		} else {
			
			if(isset($_GET['friends'])) {
				
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
			}
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			$questions->task = 1;

			$questions->scenario = 'task';
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker
			}
			
			if(isset($_POST['Prequestions'])) {
				
				$questions->attributes = $_POST['Prequestions'];

				$questions->milestone = 1;

				if(isset(Yii::app()->session['from'])) {
				$from = new Advertisers;
				$from->provider = Yii::app()->session['from'];
				$from->create_time = time();
				$from->save();
				unset(Yii::app()->session['from']);
				}


					$questions->time_limit = 604800;

				
				if(isset($_GET['friends']) && $_GET['friends'] !== 0 && $_GET['friends'] !== '0') {
					$questions->category_id = 20;
					$questions->friends = $_GET['friends'];
				} else if(!$questions->category_id) {
					$questions->addError('category_id', 'Please select a category of your question.');
				}

				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {

    //category part

if($questions->categories){
    $category = array();
    foreach($questions->categories as $ca) {
    $category[] = $ca;
    }
 if(!empty($category[0])){
    $questions->category_id = $category[0];
			 }
 if(!empty($category[1])){
                       $questions->category_id2 = $category[1];
 } 
 if(!empty($category[2])){
                       $questions->category_id3 = $category[2];
 }
 if(!empty($category[3])){
                       $questions->category_id4 = $category[3];
 }
 if(!empty($category[4])){
                       $questions->category_id5 = $category[4];
 }

}else{
if(isset($_POST['primary'])) {
$questions->category_id = $_POST['primary'];
}else{
$questions->category_id = 195;
}
}

				
					if($model->validate()) {
						$model->save();
						$profile->user_id = $model->id;
						$profile->save();

					Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to createpool - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@createpool.com' => 'createpool'
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your task will be posted once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->save(false);
						
					} else {
						$model->validate();
					}
					
				} //if question validate..
				
			}


			$this->render('testlanding', array(
				'questions' => $questions,
				'model' => $model,
			));


		}
		
	}




	public function actionNonpla(){
	if(!Yii::app()->request->isPostRequest || Yii::app()->user->isGuest) {
	throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
	}
	$user = Users::model()->findByPk(Yii::app()->user->id);
	$user->nonpla = 1;
	$user->save();
	}




/*
	public function actionLandingfree(){

		if(!isset($_GET['from'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$from = $_GET['from'];
		}

		if(!isset($_GET['qid'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$qid = $_GET['qid'];
		}

		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create');
			}
			
		} else {
			
			if(isset($_GET['friends'])) {
				
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
			}
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker

				if(!$model->username){
					$model->username = time();
				}
			}
			
			if(isset($_POST['Prequestions'])) {

				$questions->attributes = $_POST['Prequestions'];

				$questions->day = 1;
				$questions->milestone = 0;
				$questions->free = 1;

				if(isset($_POST['primary'])) {
				$questions->category_id = $_POST['primary'];
				}else{
				$questions->category_id = 31; //others
				}
				$questions->time_limit = 86400;
				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {
				
					if($model->validate()) {
					
					
						$model->status = 1;
						$model->save(false);
						$profile->user_id = $model->id;
						$profile->fake_register = 1;
						$profile->save(false);

						
	
		
						
						
						
						
						
						$profile->user_id = $model->id;
						$profile->save();

					Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email,
							"from"=>$from
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'from'=>$from,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to '.$from.' - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@'.$from.'.com' => $from
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your question will be posted once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->save(false);						
						
						
						
						
						
						
						$questions = Prequestions::model()->findByPk($questions->id);
						
						
				$url = "https://www.studypool.com/site/newquestion";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //this prevent printing the 200json code
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

					curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$questions->id.
					"&name=".$questions->name."&description=".
					$questions->description."&create_time=".
					$questions->create_time."&owner_id=".
					$questions->owner_id."&money=".
					$questions->money."&category_id=".
					$questions->category_id."&time_limit=".
					$questions->time_limit."&private=".
					$questions->private."&high=".
					$questions->high."&milestone=".
					$questions->milestone."
					&pem=86626728&free=".
					$questions->free
					."&hide=1");

					$result = curl_exec($ch);
					curl_close($ch);
					echo $result;
					$questions->delete();
					
						
												
									$tokenarray = array();
									array_push($tokenarray, $model->username);
									array_push($tokenarray, $model->password);
									array_push($tokenarray, $model->id);
									array_push($tokenarray, $model->activkey);
									array_push($tokenarray, $model->email);
									array_push($tokenarray, $model->user_type);
									array_push($tokenarray, $model->createtime);
									array_push($tokenarray, time());
									if($model->promo){
										array_push($tokenarray, $model->promo);
									}
									if($profile->firstname){
										array_push($tokenarray, $profile->firstname);
									}
									if($profile->lastname){
										array_push($tokenarray, $profile->lastname);
									}
									$sarray = serialize($tokenarray);
									$code = base64_encode($sarray);

									$this->redirect('https://www.studypool.com/site/handleSerializedTokenFromParentAndLoginBamWhattaname?question&code=' . $code );			//bam2			
						
						
			

						
					} else {

						$error = serialize($model->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error.'&title='.$questions->name.'&description='.$questions->description.'&category='.$questions->category_id);

						}
						
					}
					
				}else{

						$error = serialize($questions->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error);

						}

				}
				
			}


			$this->render('newfreeindex', array(  //free
				'questions' => $questions,
				'model' => $model,
			));


		}
	}
*/




	public function actionLandingprepay(){

		if(!isset($_GET['from'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$from = $_GET['from'];
		}

		if(!isset($_GET['qid'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$qid = $_GET['qid'];
		}

		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create');
			}
			
		} else {
			
			if(isset($_GET['friends'])) {
				
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
			}
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker

				if(!$model->username){
					$model->username = time();
				}
			}
			
			if(isset($_POST['Prequestions'])) {

				$questions->attributes = $_POST['Prequestions'];

				$questions->day = 1;
				$questions->milestone = 0;
				$questions->free = 1;	//not this??
				$questions->prepaid = 1;

				if(isset($_POST['primary'])) {
				$questions->category_id = $_POST['primary'];
				}else{
				$questions->category_id = 31; //others
				}
				$questions->time_limit = 86400;
				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {
				
					if($model->validate()) {
						$model->save();
						$profile->user_id = $model->id;
						$profile->save();

						Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email,
							"from"=>$from
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'from'=>$from,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to '.$from.' - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@'.$from.'.com' => $from
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your question will be posted once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->prepaid = 1;
						$questions->save(false);

						
					} else {

						$error = serialize($model->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else if(isset($_GET['redirect'])){
							$this->redirect($_GET['redirect'].'?error='.$error);
						
						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error.'&title='.$questions->name.'&description='.$questions->description.'&category='.$questions->category_id);

						}
						
					}
					
				}else{

						$error = serialize($questions->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else if(isset($_GET['redirect'])){
							$this->redirect($_GET['redirect'].'?error='.$error);

						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error);

						}

				}
				
			}


			$this->render('newfreeindex', array(  //free
				'questions' => $questions,
				'model' => $model,
			));


		}
	}





	public function actionLandingfree(){

		if(!isset($_GET['from'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$from = $_GET['from'];
		}

		if(!isset($_GET['qid'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$qid = $_GET['qid'];
		}

		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create');
			}
			
		} else {
			
			if(isset($_GET['friends'])) {
				
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
			}
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker

				if(!$model->username){
					$model->username = time();
				}
			}
			
			if(isset($_POST['Prequestions'])) {

				$questions->attributes = $_POST['Prequestions'];

				$questions->day = 1;
				$questions->milestone = 0;
				$questions->free = 1;

				if(isset($_POST['primary'])) {
				$questions->category_id = $_POST['primary'];
				}else{
				$questions->category_id = 31; //others
				}
				$questions->time_limit = 86400;
				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {
				
					if($model->validate()) {
						$model->save();
						$profile->user_id = $model->id;
						$profile->save();

						Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email,
							"from"=>$from
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'from'=>$from,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to '.$from.' - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@'.$from.'.com' => $from
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your question will be posted once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->save(false);

						
					} else {

						$error = serialize($model->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else if(isset($_GET['redirect'])){
							$this->redirect($_GET['redirect'].'?error='.$error);
						
						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error.'&title='.$questions->name.'&description='.$questions->description.'&category='.$questions->category_id);

						}
						
					}
					
				}else{

						$error = serialize($questions->getErrors());
						if($qid > 0){
							$this->redirect('https://www.'.$from.'.com/free/'.$qid.'?error='.$error);
						}else if(isset($_GET['redirect'])){
							$this->redirect($_GET['redirect'].'?error='.$error);

						}else{
							$this->redirect('https://www.'.$from.'.com/site/easy?error='.$error);

						}

				}
				
			}


			$this->render('newfreeindex', array(  //free
				'questions' => $questions,
				'model' => $model,
			));


		}
	}




	public function actionLanding(){

		if(!isset($_GET['from'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$from = $_GET['from'];
		}

		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create');
			}
			
		} else {
			
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker


				if(!$model->username){
					$model->username = time();
				}

			}
			
			if(isset($_POST['Prequestions'])) {

				$questions->attributes = $_POST['Prequestions'];

				$questions->milestone = 1;

				if(isset($_POST['primary'])) {
				$questions->category_id = $_POST['primary'];
				}else{
				$questions->category_id = 31; //others
				}
				
				if($questions->type == 1 || $questions->type == '1') {
					$questions->time_limit = $questions->day * 86400;
				} else {
					$questions->time_limit = $questions->day * 3600;
				}


				if(isset($_GET['friends'])){
					$questions->friends = $_GET['friends'];
				}
				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {
				
					if($model->validate()) {
					$model->status = 1;
						$model->save();
						$profile->user_id = $model->id;
						$profile->fake_register = 1;
						$profile->save();
													

					Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email,
							"from"=>$from
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'from'=>$from,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to '.$from.' - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@'.$from.'.com' => $from
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your question will be live once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->save(false);
						
						$questions = Prequestions::model()->findByPk($questions->id);
						
						
				$url = "https://www.".$from.".com/site/newquestion";
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //this prevent printing the 200json code
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_TIMEOUT, 10); //timeout 10s
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

					curl_setopt($ch, CURLOPT_POSTFIELDS, "id=".$questions->id.
					"&name=".$questions->name."&description=".
					$questions->description."&create_time=".
					$questions->create_time."&owner_id=".
					$questions->owner_id."&money=".
					$questions->money."&category_id=".
					$questions->category_id."&time_limit=".
					$questions->time_limit."&private=".
					$questions->private."&high=".
					$questions->high."&milestone=".
					$questions->milestone."
					&pem=86626728&free=".
					$questions->free."&friends=".$questions->friends
					);

					$result = curl_exec($ch);
					curl_close($ch);
					echo $result;
					$questions->delete();
					
									
									$tokenarray = array();
									array_push($tokenarray, $model->username);
									array_push($tokenarray, $model->password);
									array_push($tokenarray, $model->id);
									array_push($tokenarray, $model->activkey);
									array_push($tokenarray, $model->email);
									array_push($tokenarray, $model->user_type);
									array_push($tokenarray, $model->createtime);
									array_push($tokenarray, time());
									if($model->promo){
										array_push($tokenarray, $model->promo);
									}
									if($profile->firstname){
										array_push($tokenarray, $profile->firstname);
									}
									if($profile->lastname){
										array_push($tokenarray, $profile->lastname);
									}
									$sarray = serialize($tokenarray);
									$code = base64_encode($sarray);

									if($from == "Studypool" || $from == "studypool"){

										$this->redirect('https://www.'.$from.'.com/site/handleSerializedTokenFromParentAndLoginBamWhattaname2?question&code=' . $code );	

									}else{

										$this->redirect('https://www.'.$from.'.com/site/handleSerializedTokenFromParentAndLoginBamWhattaname?question&code=' . $code );
									}


						
					} else {

						$error = serialize($model->getErrors());
						$this->redirect('https://www.'.$from.'.com/site/landing?error='.$error);
						
					}
					
				}else{

						$error = serialize($questions->getErrors());
						$this->redirect('https://www.'.$from.'.com/site/landing?error='.$error);

				}
				
			}


			$this->render('newfreeindex', array(  //free
				'questions' => $questions,
				'model' => $model,
			));



		}
	}


/*
	public function actionLanding(){

		if(!isset($_GET['from'])){
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}else{
			$from = $_GET['from'];
		}

		if(!Yii::app()->user->isGuest) {
			if(isset($_GET['friends'])) {
				$fid = $_GET['friends'];
				if(isset($_SERVER['HTTP_REFERER']) && !strpos($_SERVER['HTTP_REFERER'], Yii::app()->request->hostInfo)):
					Yii::app()->request->cookies['blog'] = new CHttpCookie('blog', $fid);
					$tracking = Tracking::model()->findByAttributes(array(
						'website' => $_SERVER['HTTP_REFERER']
					));
					if($tracking) {
						$tracking->click++;
						$tracking->save();
					} else {
						$tracking = new Tracking;
						$tracking->website = $_SERVER['HTTP_REFERER'];
						$tracking->create_time = time();
						$tracking->save();
					}
				endif;
				
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create/friends/' . $fid);
			} else {
				$this->redirect(Yii::app()->request->hostInfo . '/questions/create');
			}
			
		} else {
			
			
			//create new user
			$model = new Users;
			$profile = new Profile;
			$questions = new Prequestions;
			
			if(isset($_POST['Users'])) {
				$model->attributes = $_POST['Users'];
				$salt = rand(100000, 900000);
				$model->password = md5($salt);
				$model->superuser = 0;
				$model->user_type = 1; //asker
				
				$truncated = CHtml::encode((strlen($model->email) > 20) ? mb_substr($model->email, 0, 20, 'utf-8') : $model->email);
				
				$model->username = $truncated;
				$model->createtime = time();
				$model->lastvisit = 0;
				$model->activkey = md5(microtime() . $model->password);
				$model->status = 0;
				$model->user_type = 1; //1 for asker

				if(!$model->username){
					$model->username = time();
				}

			}
			
			if(isset($_POST['Prequestions'])) {

				$questions->attributes = $_POST['Prequestions'];

				$questions->milestone = 1;

				if(isset($_POST['primary'])) {
				$questions->category_id = $_POST['primary'];
				}else{
				$questions->category_id = 31; //others
				}
				
				if($questions->type == 1 || $questions->type == '1') {
					$questions->time_limit = $questions->day * 86400;
				} else {
					$questions->time_limit = $questions->day * 3600;
				}

				if(isset($_GET['friends'])){
					$questions->friends = $_GET['friends'];
				}
				
				$questions->create_time = date('Y-m-d H:i:s', time());
				
				if($questions->validate()) {
				
					if($model->validate()) {
						$model->save();
						$profile->user_id = $model->id;
						$profile->save();

						Yii::app()->session['v_uid'] = $model->id;
						
						//email confirmation
						$activation_url = $this->createAbsoluteUrl('/user/activation/activation', array(
							"activkey" => $model->activkey,
							"email" => $model->email,
							"from"=>$from
						));
						$message = new YiiMailMessage;
						$message->view = 'registration';
						$message->setBody(array(
							'model' => $model,
							'activation_url' => $activation_url,
							'from'=>$from,
							'salt' => $salt
						), 'text/html');
						$message->setSubject('Welcome to '.$from.' - please verify your email address');
						$message->addTo($model->email);
						$message->setFrom(array(
							'no-reply@'.$from.'.com' => $from
						));
						Yii::app()->mail->send($message);
						
						Yii::app()->user->setFlash('registration', UserModule::t("<h4>Your question will be posted once you confirm your email.</h4><br><p>If you did not receive our email, please <b><u>Check Your Spam Folder</u></b> and mark our email as not-spam.</p> Thank you! "));
						
						$questions->owner_id = $model->id;
						$questions->save(false);

						
					} else {

						$error = serialize($model->getErrors());

						if(isset($_POST['primary'])) {
							$this->redirect('https://www.'.$from.'.com/site/landing?error='.$error.'&category_id='.$_POST['primary']);
						}else{
							$this->redirect('https://www.'.$from.'.com/site/landing?error='.$error);
						}
						
					}
		
				}else{

						$error = serialize($questions->getErrors());
						$this->redirect('https://www.'.$from.'.com/site/landing?error='.$error.'&category_id='.$_POST['primary']);

				}
				
			}


			$this->render('newfreeindex', array(  //free
				'questions' => $questions,
				'model' => $model,
			));



		}
	}
*/
		



	protected function transfer(){

		$model=new Transfer;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Transfer']))
		{
			$model->create_time = time();
			$model->user_id = Yii::app()->user->id;
			$model->attributes=$_POST['Transfer'];
			$model->save(false);
		}
		return $model;
 		
	}




	/**
	 * Displays the contact page
	 */
	public function actionContact() {
		$model = new ContactForm;
		if(isset($_POST['ContactForm'])) {
			$model->attributes = $_POST['ContactForm'];
			if($model->validate()) {
				$headers = "From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'], $model->subject, $model->body, $headers);
				Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact', array(
			'model' => $model
		));
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout() {
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	
	public function actionActive() {
		if(isset($_POST['ACT'])) {
			Yii::app()->session['ACT'] = $_POST['ACT'];
		} else {
			throw new CHttpException(404, 'Sorry, we can not find the page you requested.');
		}
	}
	
	
	protected function newReview() {
		$review = Review::model()->findByAttributes(array(
			'commenter_id' => Yii::app()->user->id,
			'send' => 1
		));

		if($review && isset(Yii::app()->session['receipt'])){
			$pay = Yii::app()->session['receipt'];
			if($pay->questions_id == $review->questions_id){
				$this->redirect('https://www.createpool.com/answers/receipt?redirect=1');
			}
		}


		if($review) {
			if(isset($_POST['Review'])) {
				$review->scenario = 'final';
				$review->attributes = $_POST['Review'];
				$all = $review->speed + $review->quality + $review->professionalism;
				$review->rating = round(($all / 3), 1);
				$review->create_time = time();
				$review->send = 2;


	if($review->nowork){
	$questions = Questions::model()->findByPk($review->questions_id);
	if($questions->milestone){
	$review->rating = 1;
	$review->professionalism = 1;
	$review->speed = 1;
	$review->quality = 1;
	}
	}

	if($review->rating < 2){
		$review->power = 2;
	}

				$review->save(false);


if($review->rating < 3):
$message = new MailboxConversation;  //new conversation
$message->initiator_id = 1;
$message->interlocutor_id = $review->user_id;
$message->is_system = 'yes';
$message->creceiver = $review->user_id;
$message->csender = 1;
$message->questions_id = $review->questions_id;
$message->type_id = 1; //important, means review
$message->modified = time();
if($review->pending){
$message->subject = "You have received a negative review. This review is not visible for other people unless we approve the corresponding withdrawal.";
}else{
$message->subject = "You have received a negative review.";
}
$message->save(false);
endif;


			} //if POST
		} //if($review)
		return $review;
	}




	public function actionEnableAccount(){
	if(!Yii::app()->user->isGuest):
	$user = Users::model()->findByPk(Yii::app()->user->id);
	if($user->status == 2){
	$this->render('enable');
	}else{
	$this->redirect('https://www.createpool.com');
	}
	else:
	$this->redirect('https://www.createpool.com');
	endif;
	}




	public function actionEnable(){
	if(!Yii::app()->user->isGuest):
	$user = Users::model()->findByPk(Yii::app()->user->id);
	if($user->status == 2){
	$user->status = 1;
	$user->save();
	$this->redirect('https://www.createpool.com');
	}else{
	$this->redirect('https://www.createpool.com');
	}
	else:
	$this->redirect('https://www.createpool.com');
	endif;
	}
	
	public function actionLoginRedirect(){
	if(Yii::app()->user->isGuest){
	$this->redirect(Yii::app()->request->hostInfo . "/user/registration");
	} else {
	$this->render('memberHome');
	}

}



	
	
}