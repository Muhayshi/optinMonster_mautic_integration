<?php
	$postdata = file_get_contents('php://input');
	$postdata = json_decode($postdata, true);

	if(isset($postdata['lead'])){
    $formId=$_GET['formId'];
    $filter_string='/[^a-zA-Z0-9 @_.-]/';
    $filter_ip='/[0-9.]/';

    //form_data index should match mautic Field HTML name under attributes tab
    $form_data=array(
      'f_name'    =>'',
      'last_name' =>'',
      'email'     =>'',
      'phone'     =>'',
      'formId'    =>(int)$formId,
      'ipAddress' =>'',
      'compaign' =>'' 
    );
    $lead_data=$postdata['lead'];
    
    if(isset($lead_data['firstName'])){
      $form_data['f_name'] = preg_replace($filter_string,'',$lead_data['firstName']);
    }
    if(isset($lead_data['lastName'])){
      $form_data['last_name'] = preg_replace($filter_string,'',$lead_data['lastName']);
    }
    if(isset($lead_data['email'])){
      $form_data['email'] = preg_replace($filter_string,'',$lead_data['email']);
    }
    if(isset($lead_data['ipAddress'])){
      $form_data['ipAddress'] = preg_replace($filter_ip,'',$lead_data['ipAddress']);
    }
    if(isset($lead_data['phone'])){
      $form_data['phone'] = (int)$lead_data['phone'];
    }


    send_form_to_Mautic($form_data,$formId);
	
	}else {
    response(500,"No lead data ");
	}
	
  function send_form_to_Mautic($form_data,$formId){
    //formurl path should match mautic_path/index.php/form/submit?formId=#

    $formUrl= 'http' . (($_SERVER['HTTPS'] == 'on') ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/'.basename(__DIR__);
    $formUrl.='/index.php/form/submit?formId='.$formId;
    $data = array('mauticform' => $form_data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $formUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    if(!curl_errno($ch)){
      response(200,'form info was inserted');
      
    }else {
      response(500,' form is an available');
     
    }
    curl_close($ch);
    return;
    
  }

  function response($code,$message){
    $response=array('code'=>$code,
    'message'=>$message);
    header('Content-Type: application/json');
    echo json_encode($response);
  }

?>
