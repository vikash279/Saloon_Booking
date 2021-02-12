<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
use Twilio\Rest\Client;
class Master extends CI_Controller {
    
    
    public function __construct()
      {
          parent :: __construct();
          $this->load->helper('url');
          $this->load->library('session');
          $this->load->model('MasterModel');
          $this->load->model('ClientModel');
          $this->load->library('upload');
          $this->load->library('image_lib');

    
      }
    
   /* function for master signup
    * Vikash Rai
   */  

   public function index(){
   	 $name = $this->input->post('name');
     $country_code = $this->input->post('country_code');
     $phone = $this->input->post('mobile_number');
     $gender = $this->input->post('gender');
     $user_type = $this->input->post('user_type');
    // print_r($user_type);die;
     if($user_type == "Client"){
          $phonenoexist = $this->ClientModel->verifyClientPhone($phone);
       if($phonenoexist){
            $response['status'] = false;
            $response['data_status'] = false;
            $response['message'] = "Phone number already exist!!";
       }else{
         if(!empty($name)){
          if(!empty($country_code)){
            if(!empty($phone)){
              if(!empty($gender)){
                $otp = $this->getRandomString(4);
                $data = array(
                   'full_name' => $name,
                   'country_code' => $country_code,
                   'mobile_number' => $phone,
                   'gender' => $gender,
                    'uid' => $this->input->post('uid'),
                    'email' => $this->input->post('email')
                  );
              // print_r($data);die;
               $table = "client";
       
               $res = $this->MasterModel->saveData($data,$table); 
                $table1 = "client_otp_verification";
                $data1 = array(
                 'client_id' => $res, 
                 'client_name' => $name,
                 'country_code' => $country_code,
                 'client_number' => $phone,
                 'otp' => $otp,
                 // 'uid' => $this->input->post('uid'),
                 //   'email' => $this->input->post('email')
              );
               // print_r($data1);die;
             $res1 = $this->MasterModel->saveData($data1,$table1);
               if($res){
      $data123 = ['phone' => '+'.$country_code.$phone, 'text' => 'Your otp is :'.$otp];
               $message_sent = $this->sendSMS($data123);
               /*$mobile = '+'.$country_code.$phone;
               $sms = 'Your otp is :'.$otp;
                $message_sent = $this->sendSmsByApi($mobile,$sms);*/
                
                $result = $this->MasterModel->fetchData($res,$table);
               // print_r($result);die;
                 $responsedata = array(
                  'id' => $result[0]['id'],
                  'shop_banner' => "",
                  'country_code' => $result[0]['country_code'],
                  'mobile_number' => $result[0]['mobile_number'],
                  'shop_name' => "",
                  'gender' => $result[0]['gender'],
                  'shop_area' => "",
                  'name'=> $result[0]['full_name'],
                  'shop_address' => "",
                  'shop_city' => "",
                  'brand' => "",
                  'shop_portfolio_image' => "",
                  'shop_service_for' => "",
                  'user_type' => "Client",
                  'select_service' => "",
                  'created_at' => $result[0]['created_at'],
                  'otp' => $otp,
                  'uid' => $result[0]['uid'],
                  'email'=> $result[0]['email']
                );
                //$new = array_merge( $result[0], array( "otp" => $otp ) );
                 $response['status'] = true;
                 $response['data_status'] = true;
                 $response['message'] = "Data Saved Successfully!!";
                 $response['data'] = $responsedata;
 
               }else{
                 $response['status'] = false;
                 $response['data_status'] = false;
                 $response['message'] = "Something wrong!!";
               }
              }else{
                $response['status'] = false;
                $response['data_status'] = false;
                $response['message'] = "Please fill the gender!!";
              }

            }else{
              $response['status'] = false;
              $response['data_status'] = false;
              $response['message'] = "Please fill the mobile number!!";
            }

          }else{
            $response['status'] = false;
            $response['data_status'] = false;
            $response['message'] = "Please fill the country code!!";
          }

         }else{
            $response['status'] = false;
            $response['data_status'] = false;
            $response['message'] = "Please fill the full name!!";
         }
       }
       $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
     }else{
     $phonenoexist = $this->MasterModel->verifyPhone($phone,$user_type);
     if($phonenoexist){
          $response['status'] = false;
          $response['data_status'] = false;
          $response['message'] = "Phone number already exist!!";
     }else{
     $response = array();
     if(!empty($name) && !empty($phone) && !empty($gender) && !empty($country_code)){
     // $otp = rand(10,10000);
      $otp = $this->getRandomString(4);
     	  $data = array(
              'country_code' => $country_code,
     		      'shop_phone_number' => $phone,
              'shop_owner_name' => $name,
              'gender' => $gender,
              'user_type' => $this->input->post('user_type'),
               'uid' => $this->input->post('uid'),
                    'email' => $this->input->post('email')
     		);
        //print_r($data);die;
        $table = "master_saloon";
        //$res = 1;
        $res = $this->MasterModel->saveData($data,$table);
       // print_r($res);die;
        $table1 = "login_otp_verification";
        $data1 = array(
         'shop_id' => $res,
         'country_code' => $country_code,
         'user_name' => $name,
         'user_phone' => $phone,
         'otp' => $otp
      );
     $res1 = $this->MasterModel->saveData($data1,$table1);
        if($res){
     $data123 = ['phone' => '+'.$country_code.$phone, 'text' => 'Your otp is :'.$otp];
      $message_sent = $this->sendSMS($data123);
      /* $mobile = '+'.$country_code.$phone;
               $sms = 'Your otp is :'.$otp;
                $message_sent = $this->sendSmsByApi($mobile,$sms);
        	$result = $this->MasterModel->fetchData($res,$table);*/
         // print_r($result);die;
          $responsedata = array(
              'id' => $result[0]['id'],
              'shop_banner' => $result[0]['shop_banner'],
              'country_code' => $result[0]['country_code'],
              'mobile_number' => $result[0]['shop_phone_number'],
              'shop_name' => $result[0]['shop_name'],
              'gender' => $result[0]['gender'],
              'shop_area' => $result[0]['shop_area'],
              'name'=> $result[0]['shop_owner_name'],
              'shop_address' => $result[0]['shop_address'],
              'shop_city' => $result[0]['shop_city'],
              'brand' => $result[0]['brand'],
              'shop_portfolio_image' => $result[0]['shop_portfolio_image'],
              'shop_service_for' => $result[0]['shop_service_for'],
              'user_type' => $result[0]['user_type'],
              'select_service' => $result[0]['select_service'],
              'created_at' => $result[0]['created_at'],
              'otp' => $otp,
              'uid' => $result[0]['uid'],
              'email'=> $result[0]['email']
            );
         // $new = array_merge( $result[0], array( "otp" => $otp ) );
            $response['status'] = true;
            $response['data_status'] = true;
            $response['message'] = "Data Saved Successfully!!";
	        $response['data'] = $responsedata;
        }else{
        	$response['status'] = false;
	        $response['message'] = "Something Wrong!";
        }	
  
      }else{
        $response['status'] = false;
	    $response['message'] = "Please Fill All The Mandatory Fields!";
     }
   }
     $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
     }       
   	   
   }

    /* function for adding master-saloon registration data
      * Vikash Rai
    */

    public function saveRegistrationData(){
     $id = $this->input->post('shop_id');

     //code to upload shop banner
       $config['upload_path'] = './image/banner';
      // print_r($config['upload_path']);die;
       $config['allowed_types'] = 'gif|jpg|png|jpeg';
      // $config['max_size'] = 2500;
       $this->load->library('upload', $config);
       $this->upload->do_upload('shop_banner');
       $this->upload->initialize($config);
         if (!$this->upload->do_upload('shop_banner')) {
            $error = array('error' => $this->upload->display_errors());
        //     echo $error['error'];
         }
       $data_upload_files = $this->upload->data();

       $shop_banner = $data_upload_files['file_name'];  

       //code to upload shop portfolio image

       
        $files = $_FILES;
          $cpt = count($_FILES['shop_portfolio_image']['name']);
          for($i=0; $i<$cpt; $i++)
          {           
              $_FILES['shop_portfolio_image']['name']= $files['shop_portfolio_image']['name'][$i];
              $_FILES['shop_portfolio_image']['type']= $files['shop_portfolio_image']['type'][$i];
              $_FILES['shop_portfolio_image']['tmp_name']= $files['shop_portfolio_image']['tmp_name'][$i];
              $_FILES['shop_portfolio_image']['error']= $files['shop_portfolio_image']['error'][$i];
              $_FILES['shop_portfolio_image']['size']= $files['shop_portfolio_image']['size'][$i];    
             
              $config1['upload_path'] = './image/portfolio';
              // print_r($config['upload_path']);die;
               $config1['allowed_types'] = 'gif|jpg|png|jpeg';
               $config1['max_size'] = 2500;
                $this->load->library('upload', $config1);
                $this->upload->initialize($config1);

            // Upload file to server
            if($this->upload->do_upload('shop_portfolio_image')){
                // Uploaded file data
                $imageData = $this->upload->data();
                 $uploadImgData[$i]['shop_portfolio_image'] = $imageData['file_name'];
              //$this->upload->initialize($this->set_upload_options());
             // $this->upload->do_upload();
             // $dataInfo[] = $this->upload->data();
          }
          
    }
       foreach($uploadImgData as $val1){
           $storeproductimage = array(
              'shop_id' => $id,
              'portfolio_image' => $val1['shop_portfolio_image']
            ); 
                   
         $table2 = "master_saloon_portfolio_image";
       $storeproductresult = $this->MasterModel->saveData($storeproductimage,$table2);
     }
       
      $data = array(
          'shop_banner' => $shop_banner,
          'shop_name' => $this->input->post('shop_name'),
          'shop_area' => $this->input->post('shop_area'),
          'shop_address' => $this->input->post('shop_address'),
          'shop_city' => $this->input->post('shop_city'),
          'brand' => $this->input->post('brand'),
          'shop_service_for' => $this->input->post('shop_service_for'),
          'select_service' => $this->input->post('select_service'),
          'home_appointment' => $this->input->post('home_appointment'),
          'saloon_appointment' => $this->input->post('saloon_appointment')
         );  
      //print_r($data);die;
      $table = "master_saloon";
      $res = $this->MasterModel->updateData1($id,$data,$table);
      $response = array();
        if($res){
          $result = $this->MasterModel->fetchData($id,$table);
          $response['status'] = true;
          $response['data_status'] = true;
          $response['message'] = "Data Saved Successfully!!";
          $response['data'] = $result;
        }else{
          $response['status'] = false;
          $response['data_status'] = false;
        $response['message'] = "Something wrong!";
        }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
     }


   /* function for fetching all gender type
    * Vikash Rai
   */ 
   public function fetchAllGender(){
   	$res = $this->MasterModel->fetchAllGenderTypes();
   	$response = array();
   	if($res){
      $response['status'] = true;
      $response['message'] = "Data found!!";
	  $response['data'] = $res;
   	}else{
      $response['status'] = false;
	  $response['message'] = "No Data Found!";
   	}
   	 $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   }


   /* function for fetch all user type
    * Vikash Rai
   */ 
   public function fetchAllUserType(){
   	$res = $this->MasterModel->fetchAllUserType();
   	$response = array();
   	if($res){
      $response['status'] = true;
      $response['message'] = "Data found!!";
	  $response['data'] = $res;
   	}else{
      $response['status'] = false;
	  $response['message'] = "No Data Found!";
   	}
   	 $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   }

   
   /* function for fetch all saloon services
    * Vikash Rai
   */ 
   public function fetchAllSaloonServices(){
   	$res = $this->MasterModel->fetchAllSaloonServices();
   	$response = array();
   	if($res){
      $response['status'] = true;
      $response['message'] = "Data found!!";
	  $response['data'] = $res;
   	}else{
      $response['status'] = false;
	  $response['message'] = "No Data Found!";
   	}
   	 $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   }
  
   /* function for generation random string to be used as otp
    * Vikash Rai
   */ 
   private function getRandomString($length) {
    $characters = '0123456789';
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= $characters[mt_rand(0, strlen($characters) - 1)];
    }

    return $string;
}

    /* function for sending sms to user phone
     * Vikash Rai
    */ 
    protected function sendSMS($data123) {
          // Your Account SID and Auth Token from twilio.com/console
            $sid = 'ACcb5a6b7df7e2502b24724ea0eb477f68';
            $token = '54bd2f3b5f41596114c7d7d3dcc8406b';
           // print_r($data123);die;
            $client = new Twilio\Rest\Client($sid, $token);
             $message = $client->messages
                  ->create($data123['phone'], // to
                       [
                           "from" => "+12059536292",
                           "body" => $data123['text']
                       ]
                  );    

    }

   /* function for master/saloon login
    * Vikash Rai
   */ 
   public function login(){
    $country_code = $this->input->post('country_code');
    $phone = $this->input->post('mobile_number');
    $user_type = $this->input->post('user_type');
   // print_r($user_type);die;
    if($user_type == "Client"){
    $result = $this->ClientModel->fetchClientLoginData($phone);
    // print_r($result);die;
      $otp = $this->getRandomString(4);
     if(!empty($result[0])){
      $table = "client_otp_verification";
     $data = array(
         'client_id' => $result[0]['client_id'],
         'client_name' => $result[0]['full_name'],
         'country_code' => $result[0]['country_code'],
         'client_number' => $result[0]['mobile_number'],
         'otp' => $otp
      );
    // print_r($data);die;
     $res = $this->MasterModel->saveData($data,$table);
   }
   
     $response = array();
    if($result){
       // print_r($result);die;
      $data123 = ['phone' => '+'.$country_code.$phone, 'text' => 'Your otp is :'.$otp];
      $message_sent = $this->sendSMS($data123);
      /* $mobile = '+'.$country_code.$phone;
               $sms = 'Your otp is :'.$otp;
                $message_sent = $this->sendSmsByApi($mobile,$sms);*/
      $data11 = array(
          'id' => $result[0]['client_id'],
          'country_code' => $result[0]['country_code'],
          'mobile_number' => $result[0]['mobile_number'],
          'name' => $result[0]['full_name'],
          'gender' => $result[0]['gender'],
          'user_type' => "Client",
          'otp' => $otp
        );
      $response['status'] = true;
      $response['message'] = "Log in successfull!!";
      $response['data'] = $data11;
     }else{
      $response['status'] = false;
      $response['message'] = "No Data Found!";
     }
     $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }else{
     $result = $this->MasterModel->fetchUserLoginData($phone,$user_type);
      $otp = $this->getRandomString(4);
     if(!empty($result[0])){
      $table = "login_otp_verification";
     $data = array(
         'shop_id' => $result[0]['master_id'],
         'country_code' => $result[0]['country_code'],
         'user_name' => $result[0]['shop_owner_name'],
         'user_phone' => $result[0]['shop_phone_number'],
         'otp' => $otp
      );
     $res = $this->MasterModel->saveData($data,$table);
   }
   // print_r($data);die;
     $response = array();
    if($result){
      $data123 = ['phone' => '+'.$country_code.$phone, 'text' => 'Your otp is :'.$otp];
      $message_sent = $this->sendSMS($data123);
      /* $mobile = '+'.$country_code.$phone;
               $sms = 'Your otp is :'.$otp;
                $message_sent = $this->sendSmsByApi($mobile,$sms);*/
      $data11 = array(
          'id' => $result[0]['master_id'],
          'country_code' => $result[0]['country_code'],
          'mobile_number' => $result[0]['shop_phone_number'],
          'name' => $result[0]['shop_owner_name'],
          'gender' => $result[0]['gender'],
          'user_type' => $result[0]['user_type'],
          'otp' => $otp
        );
      $response['status'] = true;
      //$response['otp'] = $otp;
      $response['message'] = "Log in successfull!!";
      $response['data'] = $data11;
     }else{
      $response['status'] = false;
      $response['message'] = "No Data Found!";
     }
     $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }        
   }

   /* function for otp verification for login
    * Vikash Rai
   */
    public function otpVerification(){
      $phone = $this->input->post('mobile_number');
      $otp_received = $this->input->post('otp');
      $user_type = $this->input->post('user_type');
      $fcm_token = $this->input->post('fcm_token');
      if($user_type == "Client"){
       // print_r("expression");die;
        $res = $this->ClientModel->fetchOtpDetails($phone);
        $table = "client";
        $res1 = $this->ClientModel->fetchFullShopDetails($phone,$table);
       // print_r($res1);die;
       if($res1[0]){
           if(!empty($res1[0]['profile_image'])){
              $profile_image = base_url().'image/profile/'.$res1[0]['profile_image'];
          }else{
              $profile_image = ""; 
          }      
           $data[] = array(
                'id'=> $res1[0]['id'],
                'full_name'=> $res1[0]['full_name'],
                'country_code'=> $res1[0]['country_code'],
                'mobile_number'=> $res1[0]['mobile_number'],
                'email'=> $res1[0]['email'],
                'uid'=> $res1[0]['uid'],
                'profile_image'=> $profile_image,
                'gender'=> $res1[0]['gender'],
                'created_at'=> $res1[0]['created_at'],
               );
       }
        $response = array();
        if($res['otp'] == $otp_received){
            $client_id = $res1[0]['id'];
            $tokendata = $this->MasterModel->saveFCMToken($client_id,$fcm_token,$table);
          $response['status'] = true;
          $response['data_status'] = $data;
          $response['message'] = "OTP matched";
          $response['data'] = $res;
        }else{
          $response['status'] = false;
         // $response['data_status'] = false;
          $response['message'] = "OTP doesn't match..Please try again!";
        }
        $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
      }else{
       // print_r("expression123");die;
      $res = $this->MasterModel->fetchOtpDetails($phone);
     // $table1 = "master_saloon";
      $day = date('l');
      $res1 = $this->ClientModel->fetchFullShopDetails1($phone);
      $id = $this->ClientModel->getchShopId($phone);
      $opening_hours = $this->ClientModel->fetchSlotTime($id,$day);
      $shop_location = $this->ClientModel->fetchSaloonLocation($id);
     // print_r($shop_location);die;
      $response = array();
      if($res['otp'] == $otp_received){
          if(!empty($res1[0]['shop_banner'])){
               $shopbanner = base_url().'image/banner/'.$res1[0]['shop_banner'];
                 if(! @ file_get_contents($shopbanner)){
                     $shopbanner = "";
                    }else{
                       $shopbanner = $shopbanner; 
                }
          }else{
              $shopbanner = "";
          }
          
          if(!empty($res1[0]['profile_image'])){
              $shopprofileimage = base_url().'image/profile/'.$res1[0]['profile_image'];
                 if(! @ file_get_contents($shopprofileimage)){
                     $shopprofileimage = "";
                    }else{
                       $shopprofileimage = $shopprofileimage; 
                }
          }else{
              $shopprofileimage = "";
          }
         $dataStatus[] = array(
                'id' => $res1[0]['id'],
                'premium_shop' => $res1[0]['premium_shop'],
                'shop_banner' => $shopbanner,
                'country_code' => $res1[0]['country_code'],
                'shop_phone_number' => $res1[0]['shop_phone_number'],
                'shop_name' => $res1[0]['shop_name'],
                'gender' => $res1[0]['gender'],
                'email' => $res1[0]['email'],
                'uid' => $res1[0]['uid'],
                'profile_image' => $shopprofileimage,
                'shop_area' => $res1[0]['shop_area'],
                'shop_owner_name' => $res1[0]['shop_owner_name'],
                'shop_address' => $res1[0]['shop_address'],
                'shop_city' => $res1[0]['shop_city'],
                'brand' => $res1[0]['brand'],
                'shop_service_for' => $res1[0]['shop_service_for'],
                'user_type' => $res1[0]['user_type'],
                'select_service' => $res1[0]['select_service'],
                'home_appointment' => $res1[0]['home_appointment'],
                'saloon_appointment' => $res1[0]['saloon_appointment'],
                'created_at' => $res1[0]['created_at']
             );
         // print_r($dataStatus);die;
         $master_id = $res1[0]['id'];
         $table = "master_saloon";
         $tokendata = $this->MasterModel->saveFCMToken($master_id,$fcm_token,$table);
        $response['status'] = true;
        $response['data_status'] = $dataStatus;
        $response['opening_hours'] = $opening_hours;
        $response['shop_location'] = $shop_location;
        $response['message'] = "OTP matched";
        $response['data'] = $res;
      }else{
        $response['status'] = false;
       // $response['data_status'] = false;
        $response['message'] = "OTP doesn't match..Please try again!";
      }
      $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
      }      
    }

    /* function for adding shop service
    * Vikash Rai
   */
    public function addShopService(){
      $service_name = $this->input->post('service_name');
      $service_cost = $this->input->post('service_cost');
      $service_duration = $this->input->post('service_duration');
      
             $config['upload_path'] = './image/services';
            // print_r($config['upload_path']);die;
             $config['allowed_types'] = 'gif|jpg|png|jpeg';
           //  $config['max_size'] = 2500;
             $this->load->library('upload', $config);
             $this->upload->do_upload('image');
             $this->upload->initialize($config);
              if (!$this->upload->do_upload('image')) {
                 $error = array('error' => $this->upload->display_errors());
                  //echo $error['error'];
              }
             $data_upload_files = $this->upload->data();

             $image = $data_upload_files['file_name']; 

      if(!empty($service_name) && !empty($service_cost) && !empty($service_duration)){

        $data = array(
            'shop_id' => $this->input->post('shop_id'),
            'service_name' => $service_name,
            'service_duration' => $service_duration,
            'service_cost' => $service_cost,
            'service_image' => $image
          );
       // print_r($data);die;
        $table = "services";
        $response = array();
        $res = $this->MasterModel->saveData($data,$table);
        if($res){
          $result = $this->MasterModel->fetchData($res,$table);
            $response['status'] = true;
            $response['message'] = "Service added successfully!!";
            $response['service_id'] = $res;
           $response['data'] = $result;
        }else{
          $response['status'] = false;
          $response['message'] = "Something Wrong!";
        } 
    }else{
        $response['status'] = false;
        $response['message'] = "Please fill all the mandatory fields";
      }
      $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   } 

   /* function for editing shop services
    * Vikash Rai
   */
   public function editShopService(){
    $service_id = $this->input->post('service_id');
    $service_name = $this->input->post('service_name');
    $service_cost = $this->input->post('service_cost');
    $service_duration = $this->input->post('service_duration');

     $config['upload_path'] = './image/services';
     // print_r($config['upload_path']);die;
     $config['allowed_types'] = 'gif|jpg|png|jpeg';
    // $config['max_size'] = 2500;
     $this->load->library('upload', $config);
     $this->upload->do_upload('image');
     $this->upload->initialize($config);
      if (!$this->upload->do_upload('image')) {
         $error = array('error' => $this->upload->display_errors());
         // echo $error['error'];
      }
     $data_upload_files = $this->upload->data();

     $image = $data_upload_files['file_name']; 

      if(!empty($service_name) && !empty($service_cost) && !empty($service_duration)){
            $data = array(
            //'shop_id' => $this->input->post('shop_id'),
            'service_name' => $service_name,
            'service_duration' => $service_duration,
            'service_cost' => $service_cost,
            'service_image' => $image
          );
          // print_r($data);die; 
           $table = "services";
           $response = array();
           $id = $service_id;
        $res = $this->MasterModel->updateData1($id,$data,$table);
        if($res){
          //$result = $this->MasterModel->fetchData($res,$table);
            $message = array(
              'message' => "Service updated successfully!!"
              );
            $response['status'] = true;
            $response['data'] = $message;
         // $response['data'] = $result;
        }else{
          $response['status'] = false;
          $response['message'] = "Something Wrong!";
        } 

       }else{
           $response['status'] = false;
           $response['message'] = "Please fill all the mandatory fields";
      }
      $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   } 

     /* function for fetching all shop services
      * Vikash Rai
      */

     public function fetchAllServices(){
      $res = $this->MasterModel->fetchAllShopServices();
      $response = array();
      if($res){
        $response['status'] = true;
        $response['message'] = "Data found!!";
      $response['data'] = $res;
      }else{
        $response['status'] = false;
      $response['message'] = "No Data Found!";
      }
       $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
     }


     /* function for fetching all shop services by shop id
      * Vikash Rai
      */

     public function fetchShopServices(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchShopServicesByShop($id);
      //print_r($res);die;
      $response = array();
      if($res){
        foreach($res as $value){
            if(!empty($value['service_image'])){
                $path = base_url().'image/services/'.$value['service_image'];
                 if(! @ file_get_contents($path)){
                     $path = "";
                    }else{
                       $path = $path; 
                }
            }else{
                $path = "";
            }
          $data[] = array(
              'id' => $value['id'],
              'shop_id' => $value['shop_id'],
              'service_name' => $value['service_name'],
              'service_duration' => $value['service_duration'],
              'service_cost' => $value['service_cost'],
              'service_image' => $path,
              'created_at' => $value['created_at'],
            );
        }
        $response['status'] = true;
        $response['message'] = "Data found!!";
      $response['data'] = $data;
      }else{
        $response['status'] = false;
      $response['message'] = "No Data Found!";
      }
       $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
     }
     
     /* function for fetching all shop subscriptions
      * Vikash Rai
      */

     public function fetchAllSubscription(){
       $res = $this->MasterModel->fetchAllShopSubscription();
        $response = array();
        if($res){
          $response['status'] = true;
          $response['message'] = "Data found!!";
        $response['data'] = $res;
        }else{
          $response['status'] = false;
        $response['message'] = "No Data Found!";
        }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
     }

     /* function for fetching all shop opening hours
      * Vikash Rai
      */
     public function addShopHours(){
      header('Access-Control-Allow-Origin: *');
      header("Content-type: application/json; charset=utf-8");
     $content = file_get_contents("php://input");
     $decoded = json_decode($content, true);
   // print_r($decoded['daycount']);die;
    if($decoded['daycount'] == $decoded['hourcount']){
        $shop_id = $decoded['shop_id'];
        $count = $decoded['daycount'];
        $day = $decoded['day'];
        $slot_time = $decoded['slot_time'];
        
        $checkData = $this->MasterModel->checkData($shop_id);
        if(!empty($checkData[0])){
          $deleteData = $this->MasterModel->deleteOpeningClosingData($shop_id);  
        }
      // print_r($deleteData);die;
        
        for($i = 0; $i<$count; $i++) {
              $data = array(
                    'shop_id' => $shop_id,
                    'day' => $day[$i],
                    'slot_time' => $slot_time[$i]
                  );
                
            
        // print_r($data); die;
         $table = "shop_opening_hours";
          $res = $this->MasterModel->saveData($data,$table);
        }  
          $response = array();
          if($res){
           // $result = $this->MasterModel->fetchData($res,$table);
           
           $response['status'] = true;
           $response['message'] = "Shop opening hours added successfully!";
          // $response['data'] = $result;

          }else{
             $response['status'] = false;
             $response['message'] = "Something Wrong!";
          }
       
       
    }else{
       $response['status'] = false;
       $response['message'] = "Please check your input selection";

    }
    $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
       
      
     }

     /* function for adding shop location
      * Vikash Rai
      */

     public function addShopLocation(){
      $shop_id = $this->input->post('shop_id');
      $location = $this->input->post('location');
      $latitude = $this->input->post('latitude');
      $longitude = $this->input->post('longitude');
      if(!empty($location)){
        $data = array(
            'shop_id' => $shop_id,
            'location' => $location,
            'latitude' => $latitude,
            'longitude' => $longitude
          );
        $table = "shop_location";
        $response = array();
        $res = $this->MasterModel->saveData($data,$table);
        if($res){
          //$result = $this->MasterModel->fetchData($res,$table);
            $message = array(
              'message' => "Shop location added successfully!"
              );
            $response['status'] = true;
            $response['data_status'] = true;
            $response['data'] = $message;
         // $response['data'] = $result;
        }else{
          $response['status'] = false;
          $response['data_status'] = false;
          $response['message'] = "Something Wrong!";
        } 


      }else{
           $response['status'] = false;
           $response['data_status'] = false;
           $response['message'] = "Please fill all the mandatory fields";
      }
       $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
       
     }

     /* function for adding shop products
      * Vikash Rai
      */

     public function addShopProducts(){
      $shop_id = $this->input->post('shop_id');
      $name = $this->input->post('product_name');
      $price = $this->input->post('product_price');
      $description = $this->input->post('product_description');
       
       $config['upload_path'] = './image/products';
       // print_r($config['upload_path']);die;
       $config['allowed_types'] = 'gif|jpg|png|jpeg';
      // $config['max_size'] = 2500;
       $this->load->library('upload', $config);
       $this->upload->do_upload('image');
       $this->upload->initialize($config);
       if (!$this->upload->do_upload('image')) {
         $error = array('error' => $this->upload->display_errors());
         // echo $error['error'];
       }
       $data_upload_files = $this->upload->data();

       $image = $data_upload_files['file_name']; 
       if(!empty($name) && !empty($price)){
        $data = array(
            'shop_id' => $shop_id,
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'images' => $image
          );
        $table = "shop_products";
        $response = array();
        $res = $this->MasterModel->saveData($data,$table);
        if($res){
          //$result = $this->MasterModel->fetchData($res,$table);
            $message = array(
              'message' => "Product added successfully!!"
              );
            $response['status'] = true;
            $response['data'] = $message;
         // $response['data'] = $result;
        }else{
          $response['status'] = false;
          $response['message'] = "Something Wrong!";
        } 

       }else{
           $response['status'] = false;
           $response['message'] = "Please fill all the mandatory fields";
      }
       $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));

     }

     /* function for fetch shop details
      * Vikash Rai
     */ 

     public function  fetchShopDetails(){
      $id = $this->input->post('shop_id');
      $table = "master_saloon";
      $res = $this->MasterModel->fetchData($id,$table);
     // print_r($res);die;
      $response = array();
      if($res){
           if(!empty($res[0]['shop_portfolio_image'])){
                $path = base_url().'image/portfolio/'.$res[0]['shop_portfolio_image'];
                   if(! @ file_get_contents($path)){
                     $path = "";
                    }else{
                       $path = $path; 
                }
            }else{
                $path = "";
            }
        $data = array(
          'id' => $res[0]['id'],
          'shop_banner' => $res[0]['shop_banner'],
          'country_code' => $res[0]['country_code'],
          'shop_phone_number' => $res[0]['shop_phone_number'],
          'shop_name' => $res[0]['shop_name'],
          'gender' => $res[0]['gender'],
          'shop_area' => $res[0]['shop_area'],
          'shop_owner_name' => $res[0]['shop_owner_name'],
          'shop_address' => $res[0]['shop_address'],
          'shop_city' => $res[0]['shop_city'],
          'brand' => $res[0]['brand'],
          'shop_portfolio_image' => $path,
          'shop_service_for' => $res[0]['shop_service_for'],
          'user_type' => $res[0]['user_type'],
          'select_service' => $res[0]['select_service'],
          'created_at' => $res[0]['created_at']
         );
        $response['status'] = true;
        $response['message'] = "Data found!!";
        $response['data'] = $data;
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

     }

     /* function for fetch shop profile
      * Vikash Rai
     */ 

     public function fetchProfile(){
      $id = $this->input->post('shop_id');
     // print_r($id);die;
      $table = "master_saloon";
      $res = $this->MasterModel->fetchData($id,$table);
      //print_r($res);die;
      $response = array();
      if($res){
      if(!empty($res[0]['profile_image'])){
          $profileimage = base_url().'image/profile/'.$res[0]['profile_image'];
             if(! @ file_get_contents($profileimage)){
                     $profileimage = "";
                    }else{
                       $profileimage = $profileimage; 
                }
      }else{
          $profileimage = "";
      }
          
          $data = array(
            'id' => $res[0]['id'],
            'premium_shop' => $res[0]['premium_shop'],
            'country_code' => $res[0]['country_code'],
            'shop_phone_number' => $res[0]['shop_phone_number'],
            'shop_name' => $res[0]['shop_name'],
            'gender' => $res[0]['gender'],
            'email' => $res[0]['email'],
            'profile_image' => $profileimage,
            'shop_area' => $res[0]['shop_area'],
            'shop_owner_name' => $res[0]['shop_owner_name'],
            'shop_address' => $res[0]['shop_address'],
            'shop_city' => $res[0]['shop_city'],
            'brand' => $res[0]['brand'],
            'shop_service_for' => $res[0]['shop_service_for'],
            'user_type' => $res[0]['user_type'],
            'select_service' => $res[0]['select_service'],
            'home_appointment' => $res[0]['home_appointment'],
            'created_at' => $res[0]['created_at']
              );
        $response['status'] = true;
        $response['message'] = "Data found!!";
        $response['data'] = $data;
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
     }


     /* function for edit shop profile
      * Vikash Rai
     */ 

     public function updateShopProfile(){
      $shop_id = $this->input->post('shop_id');
      $table = "master_saloon_profile";
      $result = $this->MasterModel->fetchShopProfileData($shop_id,$table);
      if($result){
      $config['upload_path'] = './image/profile';
       // print_r($config['upload_path']);die;
       $config['allowed_types'] = 'gif|jpg|png|jpeg';
       //$config['max_size'] = 2500;
       $this->load->library('upload', $config);
       $this->upload->do_upload('image');
       $this->upload->initialize($config);
       if (!$this->upload->do_upload('image')) {
         $error = array('error' => $this->upload->display_errors());
         // echo $error['error'];
       }
       $data_upload_files = $this->upload->data();

       $image = $data_upload_files['file_name']; 

      $data = array(
          'full_name' => $this->input->post('full_name'),
          'email' => $this->input->post('email'),
          'profile_image' => $image,
          'mobile_number' => $this->input->post('mobile_number'),
          'gender' => $this->input->post('gender')
        );
    // print_r($data);die;
      $table = "master_saloon_profile";
      $res = $this->MasterModel->updateData($shop_id,$data,$table);
      print_r($res);die;
      $response = array();
      if($res){
        $message = array(
              'message' => "Profile updated successfully!!"
              );
        $response['status'] = true;
        $response['data'] = $message;
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
     }else{
        $response['status'] = false;
        $response['message'] = "Shop profile does not exist!";
     }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

     }

     /* function for fetch master notification
      * Vikash Rai
     */ 

     public function fetchMasterNotification(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchNotification($id);
     // print_r($res);die;
      $response = array();
      if($res){
        $response['status'] = true;
        $response['message'] = "Data found!!";
        $response['data'] = $res;
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
     }

     

      // public function demo(){
      //  // print_r("expression");die;
      //   $sendername = 'GURUJI';
      //   $country_code = '+'.$this->input->post('country_code');
      //   $mobile = $this->input->post('receiver');

      //   $otp =  $this->input->post('otp');
      //  // print_r($mobile);die;
      //   $message = 'Your%20Barbar%20App%20OTP%20is:%20'.$otp; 
      //   // print_r($message);die;
      //   $url = 'http://sms.hspsms.com/sendSMS?username=gurujinow&message='.$message.'&sendername='.$sendername.'&smstype=PROMO&numbers='.$mobile.'&apikey=23474ed5-0301-45a9-9020-4d15af3aedfc'; 

      //         $curl = curl_init(); 
      //         curl_setopt_array($curl, array(
      //         CURLOPT_URL => $url,
      //         CURLOPT_RETURNTRANSFER => true,
      //         CURLOPT_ENCODING => "",
      //         CURLOPT_MAXREDIRS => 10,
      //         CURLOPT_TIMEOUT => 0,
      //         CURLOPT_FOLLOWLOCATION => true,
      //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //         CURLOPT_CUSTOMREQUEST => "GET",
      //         ));

      //         $response = curl_exec($curl);

      //         curl_close($curl); 
      //         //echo $response;die();
      //         $res = json_decode($response,1);
      //         $msgid = $res[1]['msgid'];
      //         echo print_r($res);die();
      // }   


      public function fetchServicesById(){
        $id = $this->input->post('service_id');
        $table = "services";
        $res = $this->MasterModel->fetchData($id,$table);
       // print_r($res);die;
        $response = array();
        if($res){
             foreach($res as $val){
              if(!empty($val['service_image'])){
                  $service_image = base_url().'image/services/'.$val['service_image'];
                   if(! @ file_get_contents($service_image)){
                     $service_image = "";
                    }else{
                       $service_image = $service_image; 
                }
              }else{
                  $service_image = "";
              }
              $data1[] = array(
                    'id' => $val['id'],
                    'shop_id' => $val['shop_id'],
                    'service_name' => $val['service_name'],
                    'service_duration' => $val['service_duration'],
                    'service_cost' => $val['service_cost'],
                    'service_image' =>$service_image,
                    'created_at' => $val['created_at']
                  );
          }
          $response['status'] = true;
          $response['message'] = "Data found!!";
          $response['data'] = $data1;
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }

      public function fetchShopHours(){
        // $id = $this->input->post('shop_id');
        $table = "shop_hours";
        $res = $this->MasterModel->fetchShopHoursData1($table);
       // print_r($res);die;
        foreach($res as $hours){
         // print_r($hours['slot_time']);
          $slot_timing = explode("-",$hours['slot_time']);
          $opening_hours = $slot_timing[0];
          $closing_hours = $slot_timing[1];
         // $open[] = $opening_hours;
         // $close[] = $closing_hours;
           $res1[] = array(
                'id' => $hours['id'],
                //'shop_id' => $hours['shop_id'],
                'day' =>  $hours['day'],
                'slot_time' => $hours['slot_time'],
                'created_at' => $hours['created_at'],
                'opening_hours' => $opening_hours,
                'closing_hours' => $closing_hours

             );   
         
        }//print_r($open);echo('</pre>');print_r($close);die;
        $response = array();
        if($res){
          $opening = array(
               '1' => "06:00 AM",
               '2' => "06:30 AM",
               '3' => "07:00 AM",
               '4' => "07:30 AM",
               '5' => "08:00 AM"
            );
        foreach($opening as $open){
            $opening1[] = array(
                 'opentime' => $open
                );
        }    

           $closing = array(
               "1" => "12:00 PM",
               "2" => "12:30 PM",
               "3" => "01:00 PM",
               "4" => "01:30 PM",
               "5" => "02:00 PM",
            );
            
         foreach($closing as $close){
            $closing1[] = array(
                 'closetime' => $close
                );
        } 
        
          $new = array_merge( $res,   array( "opening_hours" => $opening_hours, "closing_hours" => $closing_hours ) );
         // $new = array_merge( $result[0], array( "otp" => $otp ) );
          $response['status'] = true;
          $response['message'] = "Data found!!";
          $response['opening_hours'] = $opening1;
          $response['closing_hours'] = $closing1;
          $response['data'] = $res1;
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }

      public function deleteServicesById(){
         $id = $this->input->post('service_id');
        $table = "services";
        $res = $this->MasterModel->deleteServices($id,$table);
       // print_r($res);die;
        $response = array();
        if($res){
          $response['status'] = true;
          $response['message'] = "Service Deleted Successfully!!";
         }else{
          $response['status'] = false;
          $response['message'] = "Something wrong!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }

      public function fetchShopCategories(){
        $id = $this->input->post('shop_id');
        $table = "category";
        $res = $this->MasterModel->fetchShopHoursData($id,$table);
       // print_r($res);die;
        $response = array();
        if($res){
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $res;
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }


       public function deleteShopCategories(){
        $id = $this->input->post('category_id');
        $table = "category";
        $res = $this->MasterModel->deleteServices($id,$table);
       // print_r($res);die;
        $response = array();
        if($res){
          $response['status'] = true;
          $response['message'] = "Category Deleted Successfully!!";
         }else{
          $response['status'] = false;
          $response['message'] = "Something wrong!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }

      public function addStoreProducts(){
       // print_r("expression");die;
        $shop_name = $this->input->post('shop_name');
        $storeexist = $this->MasterModel->checkStoreExist($shop_name);
       // print_r($storeexist[0]);die;
       if(empty($storeexist[0])){
        if(!empty($shop_name)){
           $config['upload_path'] = './image/banner';
          // print_r($config['upload_path']);die;
           $config['allowed_types'] = 'gif|jpg|png|jpeg';
          // $config['max_size'] = 2500;
           $this->load->library('upload', $config);
           $this->upload->do_upload('store_banner');
           $this->upload->initialize($config);
             if (!$this->upload->do_upload('store_banner')) {
                $error = array('error' => $this->upload->display_errors());
                 //echo $error['error'];
             }
           $data_upload_files = $this->upload->data();

           $store_banner = $data_upload_files['file_name']; 

           $storedata = array(
               'shop_name' => $shop_name,
               'store_banner' =>$store_banner
            );
          // print_r($storedata);die;
           $table = "store";
           $storeresult = $this->MasterModel->saveData($storedata,$table);
           if($storeresult){
            $store_id = $storeresult;
            $category = $this->input->post('category');
            if(!empty($category)){
               $product_name = $this->input->post('product_name');
               if(!empty($product_name)){
                $amount = $this->input->post('amount');
                if(!empty($amount)){
                   $storeproductsdetail = array(
                    'store_id' => $store_id,
                    'category' => $category,
                    'product_name' => $product_name,
                    'amount' => $amount,
                    'description' => $this->input->post('description')
                  );
                 $table1 = "store_products";
       $storeproductresult = $this->MasterModel->saveData($storeproductsdetail,$table1);
        // print_r($storeproductresult);die;
         if(!empty($storeproductresult)){
          $store_product_id = $storeproductresult;
          //print_r("expression :".$store_product_id);die;
          $files = $_FILES;
          $cpt = count($_FILES['product_image']['name']);
          for($i=0; $i<$cpt; $i++)
          {           
              $_FILES['product_image']['name']= $files['product_image']['name'][$i];
              $_FILES['product_image']['type']= $files['product_image']['type'][$i];
              $_FILES['product_image']['tmp_name']= $files['product_image']['tmp_name'][$i];
              $_FILES['product_image']['error']= $files['product_image']['error'][$i];
              $_FILES['product_image']['size']= $files['product_image']['size'][$i];    
             
              $config1['upload_path'] = './image/products';
              // print_r($config['upload_path']);die;
               $config1['allowed_types'] = 'gif|jpg|png|jpeg';
              // $config1['max_size'] = 2500;
                $this->load->library('upload', $config1);
                $this->upload->initialize($config1);

            // Upload file to server
            if($this->upload->do_upload('product_image')){
                // Uploaded file data
                $imageData = $this->upload->data();
                 $uploadImgData[$i]['product_image'] = $imageData['file_name'];
              //$this->upload->initialize($this->set_upload_options());
             // $this->upload->do_upload();
             // $dataInfo[] = $this->upload->data();
          }
          
    }
          // $store_product_image = $dataInfo[$i]['file_name'];
         foreach($uploadImgData as $val1){
           $storeproductimage = array(
              'store_product_id' => $store_product_id,
              'image' => $val1['product_image']
            ); 
                   
         $table2 = "store_products_images";
       $storeproductresult = $this->MasterModel->saveData($storeproductimage,$table2);
     }
     // print_r($storeproductimage);die;
       if(!empty($storeproductresult)){
            $resdata = array(
               'store_id' => $store_id,
               'store_product_id' => $store_product_id
              );
            $response['status'] = true;
            $response['message'] = "Store product added successfully!!";
            $response['data'] = $resdata;
       }else{
        $res = $this->MasterModel->deleteServices($store_id,$table);
            $res1 = $this->MasterModel->deleteServices($store_product_id,$table1);
            $response['status'] = false;
            $response['message'] = "Something wrong!!";
       }
         
         }else{
            $res = $this->MasterModel->deleteServices($store_id,$table);
            $res1 = $this->MasterModel->deleteServices($store_product_id,$table1);
            $response['status'] = false;
            $response['message'] = "Something wrong!!";
         }
                
            
         }else{
                   $res = $this->MasterModel->deleteServices($store_id,$table);
                  $response['status'] = false;
                  $response['message'] = "Please insert product amount!!";
                }
               }else{
                 $res = $this->MasterModel->deleteServices($store_id,$table);
                 $response['status'] = false;
                 $response['message'] = "Please insert product name!!";
               } 
            }else{
               $res = $this->MasterModel->deleteServices($store_id,$table);
              $response['status'] = false;
             $response['message'] = "Please insert category!!";
            }
           }else{
             $response['status'] = false;
             $response['message'] = "Something wrong!";
           } 
        }else{
          $response['status'] = false;
          $response['message'] = "Please insert shop name!";
        }
       }else{
           $response['status'] = false;
           $response['message'] = "Store already exist!"; 
           $response['store_id'] = $storeexist[0]['id'];
       }    
        $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }



      public function addMoreStoreProducts(){
        $store_id = $this->input->post('store_id');
        $category = $this->input->post('category');
            if(!empty($category)){
               $product_name = $this->input->post('product_name');
               if(!empty($product_name)){
                $amount = $this->input->post('amount');
                if(!empty($amount)){

                  $storeproductsdetail = array(
                    'store_id' => $store_id,
                    'category' => $category,
                    'product_name' => $product_name,
                    'amount' => $amount,
                    'description' => $this->input->post('description')
                  );
                 $table = "store_products";
       $storeproductresult = $this->MasterModel->saveData($storeproductsdetail,$table);
       if(!empty($storeproductresult)){
           $store_product_id = $storeproductresult;
          //print_r("expression :".$store_product_id);die;
          
          $files = $_FILES;
          $cpt = count($_FILES['product_image']['name']);
          for($i=0; $i<$cpt; $i++)
          {           
              $_FILES['product_image']['name']= $files['product_image']['name'][$i];
              $_FILES['product_image']['type']= $files['product_image']['type'][$i];
              $_FILES['product_image']['tmp_name']= $files['product_image']['tmp_name'][$i];
              $_FILES['product_image']['error']= $files['product_image']['error'][$i];
              $_FILES['product_image']['size']= $files['product_image']['size'][$i];    
             
              $config1['upload_path'] = './image/products';
              // print_r($config['upload_path']);die;
               $config1['allowed_types'] = 'gif|jpg|png|jpeg';
              // $config1['max_size'] = 2500;
                $this->load->library('upload', $config1);
                $this->upload->initialize($config1);

            // Upload file to server
            if($this->upload->do_upload('product_image')){
                // Uploaded file data
                $imageData = $this->upload->data();
                 $uploadImgData[$i]['product_image'] = $imageData['file_name'];
              //$this->upload->initialize($this->set_upload_options());
             // $this->upload->do_upload();
             // $dataInfo[] = $this->upload->data();
          }
          
    }
            // $store_product_image = $dataInfo[$i]['file_name'];
         foreach($uploadImgData as $val1){
           $storeproductimage = array(
              'store_product_id' => $store_product_id,
              'image' => $val1['product_image']
            ); 
                   
         $table2 = "store_products_images";
       $storeproductresult = $this->MasterModel->saveData($storeproductimage,$table2);
     }

       if(!empty($storeproductresult)){
            $resdata = array(
               'store_id' => $store_id,
               'store_product_id' => $store_product_id
              );
            $response['status'] = true;
            $response['message'] = "Store product added successfully!!";
            $response['data'] = $resdata;
       }else{
            $res = $this->MasterModel->deleteServices($store_product_id,$table);
            $response['status'] = false;
            $response['message'] = "Something wrong!!";
       }
         
         }else{
            $res = $this->MasterModel->deleteServices($store_id,$table);
            $res1 = $this->MasterModel->deleteServices($store_product_id,$table1);
            $response['status'] = false;
            $response['message'] = "Something wrong!!";
         }

       

                  }else{
                  $response['status'] = false;
                  $response['message'] = "Please insert product amount!!";
                }
               }else{
                 $response['status'] = false;
                 $response['message'] = "Please insert product name!!";
               } 
           }else{
              $response['status'] = false;
             $response['message'] = "Please insert category!!";
            }
            $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     } 


/*     public function homePageData(){
      $shop_id = $this->input->post('shop_id');
      $booking = $this->MasterModel->fetchShopTotalBookingCount($shop_id);
      $earning = $this->MasterModel->fetchShopTotalEarning($shop_id);
     // print_r($booking);die;
      if(!empty($booking)){
          $book = $booking;
      }else{
          $book = 0;
      }
      if(!empty($earning)){
          $earn = $earning;
      }else{
          $earn = 0;
      }
      //$rating = 101;
      $rating = $this->MasterModel->fetchShopRatingCount($shop_id);
      if(!empty($rating)){
          $rate = $rating;
      }else{
          $rate = 0;
      }
      $response = array();
      if($booking){
          
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = array('total_booking' => $book, 'total_earning' => $earn, 'total_rating' => $rate);
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }*/
     
     
     public function homePageData(){
         
         $shopdetails = $this->MasterModel->fetchShopDetailsForMaxOrders();
          if(!empty($shopdetails)){
              foreach($shopdetails as $val){
                   if(!empty($val['profile_image'])){
                      $image = base_url().'image/profile/'.$val['profile_image'];
                      if(! @ file_get_contents($image)){
                         $image = "";
                        }else{
                           $image = $image; 
                        }
                  }else{
                      $image = "";
                  }
                  
                  if(!empty($val['shop_banner'])){
                      $banner = base_url().'image/banner/'.$val['shop_banner'];
                      if(! @ file_get_contents($banner)){
                         $banner = "";
                        }else{
                           $banner = $banner; 
                        }
                  }else{
                      $banner = "";
                  }
              $data = array(
                    'id' => $val['id'],
                    'premium_shop' => $val['premium_shop'],
                    'shop_banner' => $banner,
                    'country_code' => $val['country_code'],
                    'shop_phone_number' => $val['shop_phone_number'],
                    'shop_name' => $val['shop_name'],
                    'gender' => $val['gender'],
                    'email' => $val['email'],
                    'uid' => $val['uid'],
                    'fcm_token' => $val['fcm_token'],
                    'profile_image' => $image,
                    'shop_area' => $val['shop_area'], 
                    'shop_owner_name' => $val['shop_owner_name'],
                    'shop_address' => $val['shop_address'],
                    'shop_city' => $val['shop_city'],
                    'brand' => $val['brand'],
                    'shop_service_for' => $val['shop_service_for'],
                    'user_type' => $val['user_type'],
                    'select_service' => $val['select_service'],
                    'home_appointment' => $val['home_appointment'],
                    'saloon_appointment' => $val['saloon_appointment'],
                   
                  );
                 $earning = $this->MasterModel->fetchShopTotalEarning($val['id']); 
                 if(!empty($earning)){
                      $earn = $earning;
                  }else{
                      $earn = 0;
                  }
                  
                 $rating = $this->MasterModel->fetchShopRatingCount($val['id']);
                  if(!empty($rating)){
                      $rate = $rating;
                  }else{
                      $rate = 0;
                  }
                $new[] = array_merge( $data, array( "earning" => $earn, "rating" => $rate ) );  
              }
            //   $arraySingle = call_user_func_array('array_merge', $data);
              $response['status'] = true;
              $response['message'] = "Data Found!!";
              $response['data'] = $new;
          }else{
              $response['status'] = false;
              $response['message'] = "No Data Found!";
          }
           
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }


     public function myBookings(){
       $shop_id = $this->input->post('shop_id');
      // $res = $this->MasterModel->fetchShopsAllBookings($shop_id);
      // $res = $this->MasterModel->fetchShopsAllBookingsDetails($shop_id);
       $res = $this->MasterModel->fetchShopsAllBookingsClientDetails($shop_id);
       $unique_arr = array_unique(array_column($res , 'client_id'));
       $newarray = array_intersect_key($res , $unique_arr);
       foreach($newarray as $val){
           $client_id = $val['client_id'];
           $result[] = $this->MasterModel->fetchClientDetailsForShopBooking($client_id);
       }
       
       foreach($result as $number => $number_array)
    {
    foreach($number_array as $data => $user_data)
        {
             if(!empty($user_data['profile_image'])){
                  $image = base_url().'image/profile/'.$user_data['profile_image'];
              }else{
                  $image = "";
              }
             $data1[] = array(
                'id' => $user_data['id'],
                'shop_id' => $user_data['shop_id'],
                'client_id' => $user_data['client_id'],
                'date' => $user_data['date'],
                'time' => $user_data['time'],
                'comment' => $user_data['comment'],
                'created_at' => $user_data['created_at'],
                'full_name' => $user_data['full_name'],
                'profile_image' => $image,
                'mobile_number' => $user_data['mobile_number']
               );
        }
    }
   
      // print_r($data1);die;
       $response = array();
      if($data1){
    
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $data1;
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }
     
     public function myBookingServicesDetails(){
         $booking_id = $this->input->post('booking_id');
             if(!empty($booking_id)){
                 $res = $this->ClientModel->fetchClientBookingDetails($booking_id);
                // print_r($res);die;
                if($res){
                foreach($res as $val){
                  if(!empty($val['service_image'])){
                      $service_image = base_url().'image/services/'.$val['service_image'];
                    }else{
                       $service_image = "";
                   }
                    $data[] = array(
                        'id'=> $val['id'],
                        'shop_id'=> $val['shop_id'],
                        'client_id'=> $val['client_id'],
                        'date'=> $val['date'],
                        'time'=> $val['time'],
                        'comment'=> $val['comment'],
                        'created_at'=> $val['created_at'],
                        'service'=> $val['service'],
                        'service_duration'=> $val['service_duration'],
                        'service_cost'=> $val['service_cost'],
                        'service_image'=> $service_image
                        );
                }
                 $response['status'] = true;
                 $response['message'] = "Data found!!";
                 $response['data'] = $data;
            }else{
              $response['status'] = false;
              $response['message'] = "No Data Found!";  
            }
             }else{
               $response['status'] = false;
               $response['message'] = "Please insert client id!";    
             }
             
         $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
         
     }


     public function totalEarning(){
      $shop_id = $this->input->post('shop_id');
    //  $earning = $this->MasterModel->fetchShopTotalEarning($shop_id);
      $earning = $this->MasterModel->fetchShopTotalBookingEarning($shop_id);
      if(!empty($earning)){
        $earn = $earning;
      }else{
        $earn = null;
      }
    
    //  print_r($earning);die;
      $response = array();
      if($earning){
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['total_earning'] = $earn;
          
         }else{
          $response['status'] = false;
          $response['message'] = "No Data Found!";
         }
           $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }


     public function fetchCategories(){
      $res = $this->MasterModel->fetchAllCategories();
      $response = array();
      if($res){
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $res;
      }else{
        $response['status'] = false;
          $response['message'] = "No Data Found!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }


     public function fetchAllServicesByShopId(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchShopServicesByShop($id);
     // print_r($res);die;
     
      $response = array();
      if($res){
          foreach($res as $val){
              if(!empty($val['service_image'])){
                  $service_image = base_url().'image/services/'.$val['service_image'];
                  if(! @ file_get_contents($service_image)){
                     $service_image = "";
                    }else{
                       $service_image = $service_image; 
                    }
              }else{
                  $service_image = "";
              }
              $data1[] = array(
                    'id' => $val['id'],
                    'shop_id' => $val['shop_id'],
                    'service_name' => $val['service_name'],
                    'service_duration' => $val['service_duration'],
                    'service_cost' => $val['service_cost'],
                    'service_image' =>$service_image,
                    'created_at' => $val['created_at']
                  );
          }
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $data1;
      }else{
        $response['status'] = false;
          $response['message'] = "No Data Found!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }


     public function fetchShopTimeAndPlace(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchShopTimePlace($id);
     // print_r($res);die;
      $response = array();
       if($res){
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $res;
      }else{
        $response['status'] = false;
          $response['message'] = "No Data Found!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }

     public function myStoreProducts(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchStoreProducts($id);
      $result = $this->MasterModel->checkStoreExist($id);
      if($result){
          $storeexist = "true";
      }else{
         $storeexist = "false"; 
      }
      //print_r($result);die;
      $response = array();
       if($res){
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['storeCreated'] = $storeexist;
          $response['store_id'] = $result[0]['id'];
          $response['data'] = $res;
      }else{
        $response['status'] = false;
          $response['message'] = "No Data Found!";
          $response['storeCreated'] = "$storeexist";
           $response['store_id'] = $result[0]['id'];
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }

     public function myStoreOrder(){
      $id = $this->input->post('shop_id');
      $res = $this->MasterModel->fetchMyShopOrder($id);
      //print_r($res);die;
       $arraySingle = call_user_func_array('array_merge', $res);
     // print_r($arraySingle);die;
      $response = array();
       if($arraySingle){
           foreach($arraySingle as $val){
            if(!empty($val['image'])){
                  $image = base_url().'image/products/'.$val['image'];
              }else{
                  $image = "";
              }
              
              if(!empty($val['profile_image'])){
                  $profile_image = base_url().'image/profile/'.$val['profile_image'];
              }else{
                  $profile_image = "";
              }
           $data[] = array(
                    'id'=> $val['id'],
                    'client_id'=> $val['client_id'],
                    'product_id'=> $val['product_id'],
                    'name'=> $val['name'],
                    'image'=> $image,
                    'category'=> $val['category'],
                    'description'=> $val['description'],
                    'price'=> $val['price'],
                    'quantity'=> $val['quantity'],
                    'delivery_status'=> $val['delivery_status'],
                    'created_at'=> $val['created_at'],
                    'mobile_number'=> $val['mobile_number'],
                    'client_name'=> $val['client_name'],
                    'delivery_address'=> $val['address'],
                    'client_profile_image'=> $profile_image
               );
           }  
          $response['status'] = true;
          $response['message'] = "Data Found!!";
          $response['data'] = $data;
      }else{
        $response['status'] = false;
          $response['message'] = "No Data Found!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
     }
     
     public function updateMasterProfile(){
         $id = $this->input->post('shop_id');
         
          $config['upload_path'] = './image/profile';
          // print_r($config['upload_path']);die;
           $config['allowed_types'] = 'gif|jpg|png|jpeg';
          // $config['max_size'] = 2500;
           $this->load->library('upload', $config);
           $this->upload->do_upload('profile_image');
           $this->upload->initialize($config);
             if (!$this->upload->do_upload('profile_image')) {
               $error = array('error' => $this->upload->display_errors());
                // echo $error['error'];
             }
           $data_upload_files = $this->upload->data();
    
           $profile_image = $data_upload_files['file_name']; 
           
           $data = array(
                'shop_owner_name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'gender' => $this->input->post('gender'),
                'profile_image' => $profile_image
               );
            // dd($data);die;  
               $table = "master_saloon";
         $res = $this->MasterModel->updateData1($id,$data,$table);
         $response = array();
           if($res){
              $response['status'] = true;
              $response['message'] = "Profile updated successfully!!";
          }else{
            $response['status'] = false;
              $response['message'] = "Something wrong!!";
          }
          $this->output
                      ->set_content_type('application/json')
                      ->set_output(json_encode($response));
         }
         
         
         public function fetchShopReviews(){
             $shop_id = $this->input->post('shop_id');
              $res = $this->MasterModel->fetchShopAllReviews($shop_id);
            //  print_r($res);die;
              $response = array();
               if($res){
                   foreach($res as $val){
                        if(!empty($val['profile_image'])){
                              $image = base_url().'image/profile/'.$val['profile_image'];
                              if(! @ file_get_contents($image)){
                                 $image = "";
                                }else{
                                   $image = $image; 
                                }
                          }else{
                              $image = "";
                          }
                       $data[] = array(
                            'client_name' => $val['full_name'],
                            'profile_image' => $image,
                            'remarks' => $val['remarks'],
                            'remarks_text' => $val['remarks_text']
                           );
                   }
                  $response['status'] = true;
                  $response['message'] = "Data Found!!";
                  $response['data'] = $data;
              }else{
                $response['status'] = false;
                  $response['message'] = "No Data Found!";
              }
              $this->output
                          ->set_content_type('application/json')
                          ->set_output(json_encode($response));
         }
         
         
          public function sendSmsByApi(){
           //header ("content-type: application/json");
           header('Access-Control-Allow-Origin: *');
           header("Content-type: application/json; charset=utf-8");
           $content = file_get_contents("php://input");
           $decoded = json_decode($content, true);
        
           $txnid = $decoded['txnid'];
           $mobile = $decoded['mobile'];
           $sms = $decoded['sms'];
          
          $apikey = "DB81129B-316B-4996-8C8D-C44C33EF7983";
          $source_address = base_url()."libraries/Savdo.tj";
          $encoding = 1;
         // die(json_encode($mobile));
         
          if (!preg_match("/^(90|91|92|93|94|98|50|55|88|70|77|99|11|00)[\d]{7}$/", $mobile)) {
           $out = array(
            "result" => 37,
            "msg" => "Invalid phone number"
           );
           die(json_encode($out));
          }

          $headers = array(
           "Content-Type: application/json",
           "Api-Key: ".$apikey,
           "Locale: EN"
          );

          $sms1 = array(
              "source-address" => $source_address,
              "destination-address" => $mobile,
              "data-encoding" => $encoding,
              "txn-id" => $txnid,
              "message" => $msg
          );

          $messages = array(
           $sms1
          );

          $json = json_encode($messages);

          $ch = curl_init();
          $url = "http://api.mdo.payvand.tj/payments/SendMessage";
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
          curl_setopt($ch, CURLOPT_FORBID_REUSE, true); 
          curl_setopt($ch, CURLOPT_TIMEOUT, 60);

          if($data = curl_exec($ch)){
           curl_close($ch);
           die($data);
          } else { 
           $out = array(
            "result" => 1,
            "msg" => "Undefined error, please try again later."
           );
           die(json_encode($out));
          }

          echo json_encode($out);
       }     
       
       
       public function uploadShopPortfolioImages(){
           $id = $this->input->post('shop_id');
        // print_r($id);die;      
        
         $config['upload_path'] = './image/portfolio';
      // print_r($config['upload_path']);die;
       $config['allowed_types'] = 'gif|jpg|png|jpeg';
      // $config['max_size'] = 2500;
       $this->load->library('upload', $config);
       $this->upload->do_upload('shop_portfolio_image');
       $this->upload->initialize($config);
         if (!$this->upload->do_upload('shop_portfolio_image')) {
            $error = array('error' => $this->upload->display_errors());
        //     echo $error['error'];
         }
       $data_upload_files = $this->upload->data();

       $shop_portfolio_image = $data_upload_files['file_name']; 
       
  
           $storeproductimage = array(
              'shop_id' => $id,
              'portfolio_image' => $shop_portfolio_image
            ); 
                   
         $table2 = "master_saloon_portfolio_image";
       $storeproductresult = $this->MasterModel->saveData($storeproductimage,$table2);
     $response = array();
        if($storeproductresult){
          $response['status'] = true;
          $response['message'] = "Data Saved Successfully!!";
        }else{
          $response['status'] = false;
        $response['message'] = "Something wrong!";
        }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
       }
       
       
         public function uploadShopProductsImages(){
           $id = $this->input->post('store_product_id');
        // print_r($id);die;      
        
         $config['upload_path'] = './image/products';
      // print_r($config['upload_path']);die;
       $config['allowed_types'] = 'gif|jpg|png|jpeg';
      // $config['max_size'] = 2500;
       $this->load->library('upload', $config);
       $this->upload->do_upload('shop_product_image');
       $this->upload->initialize($config);
         if (!$this->upload->do_upload('shop_product_image')) {
            $error = array('error' => $this->upload->display_errors());
        //     echo $error['error'];
         }
       $data_upload_files = $this->upload->data();

       $shop_product_image = $data_upload_files['file_name']; 
       
  
           $storeproductimage = array(
              'store_product_id' => $id,
              'image' => $shop_product_image
            ); 
                   
         $table2 = "store_products_images";
       $storeproductresult = $this->MasterModel->saveData($storeproductimage,$table2);
     $response = array();
        if($storeproductresult){
          $response['status'] = true;
          $response['message'] = "Data Saved Successfully!!";
        }else{
          $response['status'] = false;
        $response['message'] = "Something wrong!";
        }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
       }

   

}      