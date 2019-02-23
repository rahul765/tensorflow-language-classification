<?php

/**
*Library WBSApi Withings
*@author webert zélé <webert.zele@withings.com>
*
*
*/

class WBSApi
{
	private $oauthToken;
	private $oauthSecret;
	private $consumer;
	private $acc_tok;
	private $hmac_method;
	private $sig_method;
	private $oauth;
	private $log;

	public function __construct($oauthToken = null,$oauthSecret = null)
	{

		$this->oauth = new OAuth(OAUTH_CONSUMER_KEY,OAUTH_CONSUMER_SECRET);
		$this->log = new Log("withings","API",LOG_FILE);
		$this->log->info("Construct API");

		if($oauthToken != null && $oauthSecret != null)
		{
			$this->oauthToken = $oauthToken;
			$this->oauthSecret = $oauthSecret;
			
		}

	}


	public function setToken($oauthToken,$oauthSecret)
	{
		$this->oauth->setToken($oauthToken,$oauthSecret);
		$this->oauthToken = $oauthToken;
			$this->oauthSecret = $oauthSecret;
		
	}


	public function api($service_name, $action, $postdata = array())
	{
		$this->log->info("New request api");


		$this->log->info("Parameters : ".var_export($postdata,true));

		$this->consumer = new OAuthConsumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
		$this->acc_tok = new OAuthToken($this->oauthToken,$this->oauthSecret);

		$this->hmac_method = new OAuthSignatureMethod_HMAC_SHA1();

		$this->sig_method = $this->hmac_method;

		$this->log->info("URLWSAPI ".URLWSAPI);
		$this->log->info("Service name : ".$service_name);
		$this->log->info("Action : ".$action);
		$this->url = URLWSAPI.$service_name ;
		$this->action = $action ;
		$this->postdata = $postdata ;
	 	
	 	$fields_string = "action=".$action."&";
	 
	 	if (count($postdata)>0)
	 	{
	 		
			foreach($postdata as $key=>$value) { $fields_string .= $key.'='.$value.'&'; } 
			
			$fields_string = rtrim($fields_string,'&');
		}
	 	
	 		$req = OAuthRequest::from_consumer_and_token($this->consumer, $this->acc_tok, "GET",  $this->url."?".$fields_string);

	 		$this->log->info("Url : ".$this->url."?".$fields_string);
	 		$this->log->info("Url oauth ".$req);
			$req->sign_request($this->sig_method, $this->consumer, $this->acc_tok);
		
	 	
	 	try {
			$s = curl_init();
			
			
			curl_setopt($s,CURLOPT_URL,$req);
			curl_setopt($s,CURLOPT_RETURNTRANSFER, 1);
			//curl_setopt($s, CURLOPT_HEADER, true);
			
			$output = curl_exec($s);
			curl_close($s);
			$this->log->info("Response ".$output);
			return $output;
			
		} catch ( Exception $e ) {
			print_r($e);
			return ( false );
		}
	}


	public function getRequestToken()
	{
		try {
			$this->log->info('Get Request Token');
	  
		    $request_token_info = $this->oauth->getRequestToken(BASE_URL."request_token?oauth_callback=".CALLBACK_URL);

		    $this->log->info('URL : '.BASE_URL."request_token?oauth_callback=".CALLBACK_URL);
		   
		    if(!empty($request_token_info)) {
		        //print_r($request_token_info);
		    } else {
		        print "Failed fetching request token, response was: " . $oauth->getLastResponse();
		        $this->log->info("Failed fetching request token, response was: " . $oauth->getLastResponse());
			    }
			} catch(OAuthException $E) {
			    echo "Response: ". $E->lastResponse . "\n";
			    $this->log->info("Response: ". $E->lastResponse);
			}
			session_start();

			$this->log->info("New session with oauth_token : ".$request_token_info["oauth_token"]." and oauth_secret : ".$request_token_info["oauth_token_secret"]);
			$_SESSION["oauth_token"] = $request_token_info["oauth_token"];
			$_SESSION["oauth_token_secret"] = $request_token_info["oauth_token_secret"];

			$req = BASE_URL."authorize?oauth_token=".$request_token_info["oauth_token"];

			$this->log->info("Authorize : ".BASE_URL."authorize?oauth_token=".$request_token_info["oauth_token"]);
			
			header("Location:".$req);
	}

	public function getAccessToken()
	{
		try {
			session_start();
			$this->log->info("Get Access Token");

		    $this->oauth = new OAuth(OAUTH_CONSUMER_KEY,OAUTH_CONSUMER_SECRET);
		    $this->oauth->setToken($_SESSION["oauth_token"],$_SESSION["oauth_token_secret"]);
		    
		    $access_token_info = $this->oauth->getAccessToken(BASE_URL."access_token");
		    if(!empty($access_token_info)) {
		        print_r($access_token_info);
		        $this->log->info($access_token_info);
		    } else {
		        print "Failed fetching access token, response was: " . $oauth->getLastResponse();
		        $this->log->info("Failed fetching access token, response was: " . $oauth->getLastResponse());
		    }
		} catch(OAuthException $E) {
			    echo "Response: ". $E->lastResponse . "\n";
			    $this->log->info("Response: ". $E->lastResponse);
		}

		$this->log->info("Return : $access_token_info");

		return $access_token_info;

	}


}