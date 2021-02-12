<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;
class Client extends CI_Controller {
    
    
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


   /* function for client signup
    * Vikash Rai
    */ 

   public function index(){
     $name = $this->input->post('full_name');
     $country_code = $this->input->post('country_code');
     $phone = $this->input->post('mobile_number');
     $gender = $this->input->post('gender');

      $phonenoexist = $this->ClientModel->verifyClientPhone($phone);
       if($phonenoexist){
            $response['status'] = false;
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
                   // 'otp' => $otp
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
                 'otp' => $otp
              );
               // print_r($data1);die;
             $res1 = $this->MasterModel->saveData($data1,$table1);
               if($res){
                $result = $this->MasterModel->fetchData($res,$table);
                $new = array_merge( $result[0], array( "otp" => $otp ) );
                 $response['status'] = true;
                 $response['data'] = $new;
 
               }else{
                 $response['status'] = false;
                 $response['message'] = "Something wrong!!";
               }
              }else{
                $response['status'] = false;
                $response['message'] = "Please fill the gender!!";
              }

            }else{
              $response['status'] = false;
              $response['message'] = "Please fill the mobile number!!";
            }

          }else{
            $response['status'] = false;
            $response['message'] = "Please fill the country code!!";
          }

         }else{
            $response['status'] = false;
            $response['message'] = "Please fill the full name!!";
         }
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

    /* function for otp verification for client
    * Vikash Rai
    */
    public function otpVerification(){
      $phone = $this->input->post('mobile_number');
      $otp_received = $this->input->post('otp');
      $res = $this->ClientModel->fetchOtpDetails($phone);
      $response = array();
      if($res['otp'] == $otp_received){
        $response['status'] = true;
        $response['message'] = "OTP matched";
        $response['data'] = $res;
      }else{
        $response['status'] = false;
        $response['message'] = "OTP doesn't match..Please try again!";
      }
      $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    /* function for client login
    * Vikash Rai
   */ 
   public function login(){
    $country_code = $this->input->post('country_code');
    $phone = $this->input->post('mobile_number');
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
      $data11 = array(
          'client_id' => $result[0]['client_id'],
          'country_code' => $result[0]['country_code'],
          'mobile_number' => $result[0]['mobile_number'],
          'client_name' => $result[0]['full_name'],
          'gender' => $result[0]['gender'],
          'otp' => $otp
        );
      $response['status'] = true;
      $response['data'] = $data11;
     }else{
      $response['status'] = false;
      $response['message'] = "No Data Found!";
     }
     $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
   }

   
   /* function for edit client account details
    * Vikash Rai
   */ 

   public function editClientAccount(){
     $id = $this->input->post('client_id');
     $email = $this->input->post('email');

     $config['upload_path'] = './image/profile';
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
     
         if(!empty($id)){
          if(!empty($email)){
            
            $data = array(
               'full_name' => $this->input->post('name'),
               'email' => $email,
               'profile_image' => $image,
               
              );
              $table = "client";
              $res = $this->MasterModel->updateData1($id,$data,$table);
             // print_r($res);die;
              $response = array();
                if($res){
                  $result = $this->ClientModel->fetchClientDetailsById($id);
                  $response['status'] = true;
                  $response['message'] = "Data Saved Successfully!!";
                  $response['data'] = $result;
                }else{
                  $response['status'] = false;
                $response['message'] = "Something wrong!";
                }

          }else{
            $response['status'] = false;
            $response['message'] = "Please fill email!!";
          }

         }else{
          $response['status'] = false;
            $response['message'] = "Please fill client id!!";
         }
        
      $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 
   }
  
   /* function for fetching client account details for updation
    * Vikash Rai
   */ 
  
   public function fetchClientDetails(){
    $client_id = $this->input->post('client_id');
      $res = $this->ClientModel->fetchClientDetailsById($client_id);
     // print_r($res);die;
      $response = array();
      if($res[0]){
           if(!empty($res[0]['profile_image'])){
              $product_image = base_url().'image/profile/'.$res[0]['profile_image'];
          }else{
              $product_image = "";  
        }     
          $data = array(
                'id'=> $res[0]['id'],
                'full_name'=> $res[0]['full_name'],
                'country_code'=> $res[0]['country_code'],
                'mobile_number'=> $res[0]['mobile_number'],
                'email'=> $res[0]['email'],
                'uid'=> $res[0]['uid'],
                'profile_image'=> $product_image,
                'gender'=> $res[0]['gender'],
                'created_at'=> $res[0]['created_at'],
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

   /* function for adding client card details
    * Vikash Rai
   */ 

   public function saveCardDetails(){

    $client_id = $this->input->post('client_id');
    $name = $this->input->post('full_name');
    $card_number = $this->input->post('card_number');
    $expiry = $this->input->post('expiry');
    $cvv = $this->input->post('cvv');

    if(!empty($client_id)){
      if(!empty($name)){
        if(!empty($card_number)){
          if(!empty($expiry)){
            if(!empty($cvv)){
              $data = array(
                  'client_id' => $client_id,
                  'name' => $name,
                  'card_number' => $card_number,
                  'expiry' => $expiry,
                  'cvv' => $cvv
                );
             // print_r($data);die;
              $table = "client_card_details";
              $res = $this->MasterModel->saveData($data,$table); 
              if($res){
                 $response['status'] = true;
                 $response['message'] = "Your card has been added successfully!!";
              }else{
                $response['status'] = false;
                $response['message'] = "Something wrong!!";
              }

            }else{
              $response['status'] = false;
              $response['message'] = "Please fill cvv!!";
            }

          }else{
            $response['status'] = false;
            $response['message'] = "Please fill expiry!!";
          }

        }else{
          $response['status'] = false;
          $response['message'] = "Please fill card number!!";
        }

      }else{
        $response['status'] = false;
        $response['message'] = "Please fill full name!!";
      }

    }else{
      $response['status'] = false;
      $response['message'] = "Please fill client id!!";
    }
       $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
   }

   /* function for fetching client cart details
    * Vikash Rai
   */

   public function fetchCartDetails(){
    $id = $this->input->post('client_id');
     $res = $this->ClientModel->fetchClientCartDetails($id);
    // print_r($res);die;
     foreach($res as $val){
          if(!empty($val['image'])){
              $product_image = base_url().'image/products/'.$val['image'];
          }else{
              $product_image = "";  
        }     
        $result[] = array(
                'id' => $val['id'],
                'client_id' => $val['client_id'],
                'product_id' => $val['product_id'],
                'name' => $val['name'],
                'image' => $product_image,
                'category' => $val['category'],
                'description' => $val['description'],
                'price' => $val['price'],
                'quantity' => $val['quantity'],
                'created_at' => $val['created_at']
            ); 
     }
    // print_r($result);die;
      $response = array();
      if($res){
        $response['status'] = true;
        $response['message'] = "Data found!!";
      $response['data'] = $result;
      }else{
        $response['status'] = false;
      $response['message'] = "No Data Found!";
      }
       $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
   }
  
   // this function fetches total amount from cart for particular client id
   //integrate this in above "fetchCartDetails function"
   public function demo(){
    $id = $this->input->post('client_id');
    $res = $this->ClientModel->totalCartAmountOfClient($id);
    print_r($res);die;
   }

   /* function for fetch client notification
      * Vikash Rai
     */ 

     public function fetchClientNotification(){
      $id = $this->input->post('client_id');
      $res = $this->ClientModel->fetchNotification($id);
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


    /* function for adding products to user cart
    *Vikash Rai
    */

    public function addCartData(){
      $client_id = $this->input->post('client_id');
      $product_id = $this->input->post('product_id');
      $product_name = $this->input->post('product_name');
      $product_image = $this->input->post('product_image');
      $product_category = $this->input->post('product_category');
      $product_description = $this->input->post('product_description');
      $product_price = $this->input->post('product_price');
      $product_quantity = $this->input->post('product_quantity');
    //   header('Access-Control-Allow-Origin: *');
    //   header("Content-type: application/json; charset=utf-8");
    //   $content = file_get_contents("php://input");
    //   $decoded = json_decode($content, true);
      
    //   $client_id = $decoded['client_id'];
    //   $product_id = $decoded['product_id'];
    //   $product_name = $decoded['product_name'];
    //   $product_image = $decoded['product_image'];
    //   $product_category = $decoded['product_category'];
    //   $product_description = $decoded['product_description'];
    //   $product_price = $decoded['product_price'];
    //   $product_quantity = $decoded['product_quantity'];
      $result = $this->ClientModel->getProductsFromClientCart($product_id,$client_id);
    // print_r($result);die;
     if(empty($result)){
      $cartdata = array(
          'client_id' => $client_id,
          'product_id' => $product_id,
          'name' => $product_name,
          'image' => $product_image,
          'category' => $product_category,
          'description' => $product_description,
          'price' => $product_price,
          'quantity' => $product_quantity
        );
       // print_r($cartdata);die;
      $table = "cart";
      $res = $this->MasterModel->saveData($cartdata,$table);
      if($res){
        $new = array(
            'cart_id' => $res
          );
        $response['status'] = true;
        $response['message'] = "Product added to cart successfully!"; 
        $response['data'] = $new;

      }else{
        $response['status'] = false;
        $response['message'] = "Something wrong!"; 
      }
      }else{
          $response['status'] = false;
        $response['message'] = "Product already in cart!";  
      }
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    } 


    /* function for placing product order
    *Vikash Rai
    */

    public function clientOrder(){
      header('Access-Control-Allow-Origin: *');
      header("Content-type: application/json; charset=utf-8");
      $content = file_get_contents("php://input");
      $decoded = json_decode($content, true);
      
      $client_id = $decoded['client_id'];
    //   $count = $decoded['product_count'];
    //   $product_id1 = $decoded['product_id'];
    //  $id = $decoded['shop_id'];
    // foreach($product_id1 as $val){
    //     $product_id = $val;
    //     $res[] = $this->ClientModel->productAlreadyInCart($client_id,$product_id);
        
    // }
    $res = $this->ClientModel->productAlreadyInCart($client_id);
   // print_r($res);die;
    
      if(!empty($res)){
      foreach($res as $value){
             $orderdata = array(
                   'client_id' => $value['client_id'],
                   'product_id' => $value['product_id'],
                   'name' => $value['name'],
                   'image' => $value['image'],
                   'category' => $value['category'],
                   'description' => $value['description'],
                   'price' => $value['price']*$value['quantity'],
                   'quantity' => $value['quantity'],
                   'delivery_status' => "Pending"
               );
            $table = "client_order";
          $res1[] = $this->MasterModel->saveData($orderdata,$table); 
          //print_r($orderdata);
      }
    // die;
 
      if($res1){
            foreach($res as $value) {
                // $data = ['product_id' => $value['product_id']];
                 $product_id = $value['product_id'];
                 $storedetails[] = $this->ClientModel->fetchStoreIdByProductId($product_id);
             }
             $arraySingle = call_user_func_array('array_merge', $storedetails);
             foreach($arraySingle as $value1) {
                 $store_id = $value1['store_id'];
                 $shopdetails[] = $this->ClientModel->fetchShopIdByStoreId($store_id);
             }
             $arraySingle11 = call_user_func_array('array_merge', $shopdetails);
             foreach($arraySingle11 as $value2) {
                 $id = $value2['shop_name'];
                $masterdetails[] = $this->MasterModel->fetchMasterIdDetails($id);
             }
             $arraySingle22 = call_user_func_array('array_merge', $masterdetails);
              foreach($arraySingle22 as $value3) {
                 $master_id = $value3['id'];
                 $master_device_id = $value3['fcm_token'];
                 
                  $message = "An order has been received";
                 $message1 = "Order saved successfully";
                 //$masterdetails = $this->MasterModel->fetchMasterIdDetails($id);
                 $clientdetails = $this->MasterModel->fetchClientIdData($client);
               // print_r($clientdetails);die;
             
                $device_id = $clientdetails[0]['fcm_token'];
                $client_id = $client_id;
                
               $notification = $this->push_notification($device_id,$message,$type,$client_id,$master_id);
               $notification1 = $this->push_notification1($master_device_id,$message1,$type,$client_id,$master_id);
               $data11 = ['shop_id' => $master_id, 'message' => $message];
               $a1 = $this->saveNotification1($data11);
               $data12 = ['client_id' => $client_id, 'shop_id' => $master_id, 'message' => $message1];
               $a1 = $this->saveNotification($data12);    
               
             }
           
            
            
        $deletecart = $this->ClientModel->deleteCartData($client_id);
        $new = array(
            'order_id' => $res1
          );
          
                
                   
        $response['status'] = true;
        $response['message'] = "Order saved successfully, Please fill delivery address!"; 
        $response['data'] = $new;

      }else{
        $response['status'] = false;
        $response['message'] = "Something wrong!"; 
      }
      }else{
          $response['status'] = "false";
          $response['message'] = "Cart data not found for client!";
      }
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    private function saveNotification($data){
        $res = $this->ClientModel->captureNotification($data);
        return $res;
    }
    
     private function saveNotification1($data){
        $res = $this->ClientModel->captureMasterNotification11($data);
        return $res;
    }

    
    /* function for fetching all orders of a client
    *Vikash Rai
    */

    public function myOrder(){
      $client_id = $this->input->post('client_id');
    //  $table = "client_order";
      $res = $this->ClientModel->fetchClientOrderData1($client_id);
      //print_r($res);die;
      $response = array();
      if($res){
          foreach($res as $val){
              $result[] = array(
                    'id'=> $val['id'],
                    'client_id'=> $val['client_id'],
                    'product_id'=> $val['product_id'],
                    'name'=> $val['name'],
                    'image'=> base_url().'image/products/'.$val['image'],
                    'category'=> $val['category'],
                    'description'=> $val['description'],
                    'price'=> $val['price'],
                    'quantity'=> $val['quantity'],
                    'delivery_status'=> $val['delivery_status'],
                    'created_at'=> $val['created_at'],
                    'store_id'=> $val['store_id'],
                    'shop_id'=> $val['shop_id']
                  );
          }
        $response['status'] = true;
        $response['message'] = "Data found!!";
        $response['data'] = $result;
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }
    
    
    public function myBookings(){
         $client_id = $this->input->post('client_id');
    //  $table = "client_order";
     // $res = $this->ClientModel->fetchClientBookingDetails($client_id);
      $res = $this->ClientModel->fetchClientBookings($client_id);
     // print_r($res);die;
     if(!empty($res[0])){
        foreach($res as $value){
             if(!empty($value['profile_image'])){
            $profile_image = base_url().'image/profile/'.$value['profile_image'];
             }else{
                 $profile_image = "";
             }
             
             if(!empty($value['shop_banner'])){
            $shop_banner = base_url().'image/banner/'.$value['shop_banner'];
             }else{
                 $shop_banner = "";
             }
            $data[] = array(
                'booking_id' => $value['booking_id'],
                'shop_id' => $value['shop_id'],
                'date' => $value['date'],
                'time' => $value['time'],
                'comment' => $value['comment'],
                'premium_shop' => $value['premium_shop'],
                'shop_banner' => $shop_banner,
                'country_code' => $value['country_code'],
                'shop_phone_number' => $value['shop_phone_number'],
                'shop_name' => $value['shop_name'],
                'gender' => $value['gender'],
                'email' => $value['email'],
                'uid' => $value['uid'],
                'profile_image' => $profile_image,
                'shop_area' => $value['shop_area'],
                'shop_owner_name' => $value['shop_owner_name'],
                'shop_address' => $value['shop_address'],
                'shop_city' => $value['shop_city'],
                'location' => $value['location'],
                'latitude' => $value['latitude'],
                'longitude' => $value['longitude'],
                
        );    
        }   
      
      $response = array();
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
    
    public function myBookingDetails(){
        $booking_id = $this->input->post('booking_id');
        if(!empty($booking_id)){
            $res = $this->ClientModel->fetchClientBookingDetails($booking_id);
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
        $response['message'] = "Please Insert Booking Id!";  
        }
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    /* function for fetching home page data
    *Vikash Rai
    */

    public function fetchHomePageData(){
      $table = "shop_brand";
      $table1 = "master_saloon";
      $brand = $this->ClientModel->fetchData($table);
      $shops = $this->ClientModel->fetchShopStoreData($table1);
      $table2 = "offer";
      $offer_banner1 = $this->ClientModel->fetchData($table2);
    //  print_r($best_seller_products1);die;
      
     // print_r($shops);die;
      $response = array();
      if($shops){
        foreach($brand as $val){
          if(!empty($val['image'])){
            $image = base_url().'image/banner/'.$val['image'];
            if(! @ file_get_contents($image)){
                     $image = "";
                    }else{
                       $image = $image; 
                }
          }else{
            $image = null;
          }
          $branddata[] = array(
            'id' => $val['id'],
            'name' => $val['name'],
            'image' => $image,
            'created_at' => $val['created_at']
         );
        }
        //print_r($image);die;
        foreach($offer_banner1 as $offerval){
          $offer_banner[] = array(
                'id'=> $offerval['id'],
                'name'=> $offerval['name'],
                'banner'=> base_url().'image/banner/'.$offerval['banner'],
                'created_at'=> $offerval['created_at']
              );
      }
      
        if(!empty($offer_banner[0])){
              $offer_banner = $offer_banner; 
           }else{
               $offer_banner = null;
           }
        foreach($shops as $value){
          if(!empty($value['store_banner'])){
            $image_banner = base_url().'image/banner/'.$value['store_banner'];
             if(! @ file_get_contents($image_banner)){
                     $image_banner = "";
                    }else{
                       $image_banner = $image_banner; 
                }
          }else{
            $image_banner = null;
          }
           if(!empty($value['shop_banner'])){
            $image_shop_banner = base_url().'image/banner/'.$value['shop_banner'];
            if(! @ file_get_contents($image_shop_banner)){
                     $image_shop_banner = "";
                    }else{
                       $image_shop_banner = $image_shop_banner; 
                }
          }else{
            $image_shop_banner = null;
          }
          if(!empty($value['shop_portfolio_image'])){
            $image_shop_portfolio = base_url().'image/banner/'.$value['shop_portfolio_image'];
             if(! @ file_get_contents($image_shop_portfolio)){
                     $image_shop_portfolio = "";
                    }else{
                       $image_shop_portfolio = $image_shop_portfolio; 
                }
          }else{
            $image_shop_portfolio = null;
          }
        $main_data[] = array(
            'id' => $value['id'],
            'premium_shop' => $value['premium_shop'],
            'store_banner' => $image_banner,
            'shop_banner' => $image_shop_banner,
            'country_code' => $value['country_code'],
            'shop_phone_number' => $value['shop_phone_number'],
            'shop_name' => $value['shop_name'],
            'gender' => $value['gender'],
            'shop_area' => $value['shop_area'],
            'shop_owner_name' => $value['shop_owner_name'], 
            'shop_address' => $value['shop_address'],
            'shop_city' => $value['shop_city'],
            'brand' => $value['brand'],
            'shop_portfolio_image' => $image_shop_portfolio,
            'shop_service_for' => $value['shop_service_for'],
            'user_type' => $value['user_type'],
            'select_service' => $value['select_service'],
            'created_at' => $value['created_at']
          );
         }
          
         
        /*$main_data1 = array_slice($main_data, 0, 2, true) +
            array("offer_banner" => $offer_banner) +
            array_slice($main_data, 2, count($main_data) - 1, true) ; */
            
            
        $response['status'] = true;
        $response['message'] = "Data found!!";
        $response['data'] = array('offer_banner' => $offer_banner, 'top_brands' => $branddata, 'main_data' => $main_data);
       }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
       }
         $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }


     /* function for adding products to clients wishlist
      *Vikash Rai
     */

     public function addToFav(){
       $client_id = $this->input->post('client_id');
      $product_id = $this->input->post('product_id');
      $product_name = $this->input->post('product_name');
      $product_image = $this->input->post('product_image');
      $product_category = $this->input->post('product_category');
      $product_description = $this->input->post('product_description');
      $product_price = $this->input->post('product_price');
      $product_quantity = $this->input->post('product_quantity');

      $cartdata = array(
          'client_id' => $client_id,
          'product_id' => $product_id,
          'name' => $product_name,
          'image' => $product_image,
          'category' => $product_category,
          'description' => $product_description,
          'price' => $product_price,
          'quantity' => $product_quantity
        );
      $table = "wishlist";
      $res = $this->MasterModel->saveData($cartdata,$table);
      if($res){
        $new = array(
            'cart_id' => $res
          );
        $response['status'] = true;
        $response['message'] = "Product successfully added to wishlist!"; 
        $response['data'] = $new;

      }else{
        $response['status'] = false;
        $response['message'] = "Something wrong!"; 
      }
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
     }


     /* function for fetching products as per their categories
      *Vikash Rai
     */

     public function fetchProductsByCategory(){
      $category = $this->input->post('category');

      if(!empty($category)){
        $res = $this->ClientModel->fetchProductsByCategory($category);
        if($res){
        $unique_arr = array_unique(array_column($res , 'id'));
        $newarray[] = array_intersect_key($res , $unique_arr);
        $arraySingle = call_user_func_array('array_merge', $newarray);
        foreach($arraySingle as $val){
             if(!empty($val['image'])){
            $product_image = base_url().'image/products/'.$val['image'];
             if(! @ file_get_contents($product_image)){
                     $product_image = "";
                    }else{
                       $product_image = $product_image; 
                }
          }else{
            $product_image = null;
          }
            $data[] = array(
                    'id'=> $val['id'],
                    'store_id'=> $val['store_id'],
                    'category'=> $val['category'],
                    'product_name'=> $val['product_name'],
                    'amount'=> $val['amount'],
                    'description'=> $val['description'],
                    'created_at'=> $val['created_at'],
                    'image'=> $product_image
                );
        }
        $response['status'] = true;
        $response['message'] = "Data Found!";
        $response['data'] = $data; 
      }else{
         $response['status'] = false;
        $response['message'] = "No Data Found!"; 
      }
    }else{
       $res = $this->ClientModel->fetchProductsByCategory11();
        if($res){
        $unique_arr = array_unique(array_column($res , 'id'));
        $newarray[] = array_intersect_key($res , $unique_arr);  
         $arraySingle = call_user_func_array('array_merge', $newarray);
        foreach($arraySingle as $val){
             if(!empty($val['image'])){
            $product_image = base_url().'image/products/'.$val['image'];
             if(! @ file_get_contents($product_image)){
                     $product_image = "";
                    }else{
                       $product_image = $product_image; 
                }
          }else{
            $product_image = null;
          }
            $data[] = array(
                    'id'=> $val['id'],
                    'store_id'=> $val['store_id'],
                    'category'=> $val['category'],
                    'product_name'=> $val['product_name'],
                    'amount'=> $val['amount'],
                    'description'=> $val['description'],
                    'created_at'=> $val['created_at'],
                    'image'=> $product_image
                );
        }
        $response['status'] = true;
        $response['message'] = "Data Found!";
        $response['data'] = $data; 
      }else{
         $response['status'] = false;
        $response['message'] = "No Data Found!"; 
      }
    }
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));

     }
     
     
     public function fetchProductsByCategoryShopId(){
       $shopid = $this->input->post('shop_id');   
       $category = $this->input->post('category');
       if(!empty($shopid)){
           if(!empty($category)){
                $res = $this->ClientModel->fetchProductsByCategoryShopId($shopid,$category);
              //  print_r($res);die;
                if($res[0]){
                     $unique_arr = array_unique(array_column($res , 'id'));
                    $newarray[] = array_intersect_key($res , $unique_arr);  
                     $arraySingle = call_user_func_array('array_merge', $newarray);
                    foreach($arraySingle as $val){
                         if(!empty($val['image'])){
                        $product_image = base_url().'image/products/'.$val['image'];
                         if(! @ file_get_contents($product_image)){
                                 $product_image = "";
                                }else{
                                   $product_image = $product_image; 
                            }
                      }else{
                        $product_image = null;
                      }
            $data[] = array(
                    'id'=> $val['id'],
                    'store_id'=> $val['store_id'],
                    'category'=> $val['category'],
                    'product_name'=> $val['product_name'],
                    'amount'=> $val['amount'],
                    'description'=> $val['description'],
                    'created_at'=> $val['created_at'],
                    'image'=> $product_image
                );
        }
                    
                    
                     $response['status'] = true;
                     $response['message'] = "Data Found!";
                     $response['data'] = $data; 
                    
                }else{
                   $response['status'] = false;
                   $response['message'] = "No Data Found!";   
                }
               
           }else{
             $res = $this->ClientModel->fetchProductsByCategoryShopId11($shopid);   
            // print_r($res);die;
               if($res[0]){
                     $unique_arr = array_unique(array_column($res , 'id'));
                    $newarray[] = array_intersect_key($res , $unique_arr);  
                     $arraySingle = call_user_func_array('array_merge', $newarray);
                    foreach($arraySingle as $val){
                         if(!empty($val['image'])){
                        $product_image = base_url().'image/products/'.$val['image'];
                         if(! @ file_get_contents($product_image)){
                                 $product_image = "";
                                }else{
                                   $product_image = $product_image; 
                            }
                      }else{
                        $product_image = null;
                      }
                            $data[] = array(
                                    'id'=> $val['id'],
                                    'store_id'=> $val['store_id'],
                                    'category'=> $val['category'],
                                    'product_name'=> $val['product_name'],
                                    'amount'=> $val['amount'],
                                    'description'=> $val['description'],
                                    'created_at'=> $val['created_at'],
                                    'image'=> $product_image
                                );
                        }
                                    
                    
                     $response['status'] = true;
                     $response['message'] = "Data Found!";
                     $response['data'] = $data; 
                    
                }else{
                   $response['status'] = false;
                   $response['message'] = "No Data Found!";   
                }
           }
       }else{
            $response['status'] = false;
            $response['message'] = "Please insert shop id!"; 
       }
       $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
       
     }


    /* function for fetching shop details
      *Vikash Rai
     */ 

    public function fetchShopDetails1(){
      $id = $this->input->post('shop_id');
      $day = date('l');
     // print_r($day);die;
      $res = $this->ClientModel->fetchDetails($id,$day);
      $banner = $this->MasterModel->fetchShopBanner($id);
     // print_r($res);die;
      if(!empty($banner[0]['portfolio_image'])){
         foreach($banner as $val1){
          $image_banner[] = base_url().'image/portfolio/'.$val1['portfolio_image'];
        
            
        }
      }else{
        $image_banner[] = "null";
      }
      if(!empty($res[0]['profile_image'])){
           $shop_owner_profile_image = base_url().'image/profile/'.$res[0]['profile_image'];
          if(! @ file_get_contents($shop_owner_profile_image)){
                     $shop_owner_profile_image = "";
                    }else{
                       $shop_owner_profile_image = $shop_owner_profile_image; 
                }
          
      }else{
         $shop_owner_profile_image = ""; 
      }
        if(!empty($res[0]['shop_banner'])){
           $shop_banner = base_url().'image/banner/'.$res[0]['shop_banner'];
          if(! @ file_get_contents($shop_banner)){
                     $shop_banner = "";
                    }else{
                       $shop_banner = $shop_banner; 
                }
          
      }else{
         $shop_banner = ""; 
      }
      
      foreach($image_banner as $i){
          $image_banner1[] = array(
                 'banner_image' => $i
              );
      }
    //  print_r($image_banner1);die;
      if($res){
        $data = array(
            'id' => $res[0]['id'],
            'premium_shop' => $res[0]['premium_shop'],
            'shop_banner' => $shop_banner,
            'country_code' => $res[0]['country_code'],
            'shop_phone_number' => $res[0]['shop_phone_number'],
            'shop_name' => $res[0]['shop_name'],
            'gender' => $res[0]['gender'],
            'shop_area' => $res[0]['shop_area'],
            'shop_owner_name' => $res[0]['shop_owner_name'],
            'shop_address' => $res[0]['shop_address'].','.$res[0]['shop_city'],
            'opening_closing_hours' => $res[0]['opening_closing_hours'],
            'brand' => $res[0]['brand'],
            'shop_owner_profile_image' => $shop_owner_profile_image,
            'shop_service_for' => $res[0]['shop_service_for'],
            'user_type' => $res[0]['user_type'],
            'select_service' => $res[0]['select_service'],
            'uid' => $res[0]['uid'],
            'created_at' => $res[0]['created_at']
          );

         $response['status'] = true;
         $response['message'] = "Data Found!";
        // $response['portfolio_image_banner'] = $image_banner;
         $new[] = array_merge( $data, array( "portfolio_image_banner" => $image_banner1 ) );
         $response['data'] = $new;
      }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!";
      }
      $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response));
    }


    public function fetchProducts(){
       // $client_id = $this->input->post('client_id');
       // if(!empty($client_id)){
      $best_seller_products1 = $this->ClientModel->fetchBestSellerProducts();
      $skin_care_products1 = $this->ClientModel->fetchBestSkinCareProducts();
      $hair_care_products1 = $this->ClientModel->fetchBestHairCareProducts();
     // $client_cart_data = $this->ClientModel->productAlreadyInCart($client_id);
      //$client_cart_data = call_user_func_array('array_merge', $client_cart_data);
      $table = "offer";
      $offer_banner1 = $this->ClientModel->fetchData($table);
    //  print_r($best_seller_products1);die;
      foreach($offer_banner1 as $offerval){
          $offer_banner[] = array(
                'id'=> $offerval['id'],
                'name'=> $offerval['name'],
                'banner'=> base_url().'image/banner/'.$offerval['banner'],
                'created_at'=> $offerval['created_at']
              );
      }
      
        foreach($best_seller_products1 as $bestval){
          $best_seller_products[] = array(
                'product_id' => $bestval['product_id'],
                'id' => $bestval['id'],
                'client_id' => $bestval['client_id'],
                'name' => $bestval['name'],
                'image' => base_url().'image/products/'.$bestval['image'],
                'category' => $bestval['category'],
                'description' => $bestval['description'],
                'price' => $bestval['price'],
                'quantity' => $bestval['quantity'],
                'delivery_status' => $bestval['delivery_status'],
                'created_at' => $bestval['created_at'],
                'TotalQuantitySold' => $bestval['TotalQuantitySold']
             );
      }
      
       foreach($skin_care_products1 as $skinval){
          $skin_care_products[] = array(
                'product_id' => $skinval['product_id'],
                'id' => $skinval['id'],
                'client_id' => $skinval['client_id'],
                'name' => $skinval['name'],
                'image' => base_url().'image/products/'.$skinval['image'],
                'category' => $skinval['category'],
                'description' => $skinval['description'],
                'price' => $skinval['price'],
                'quantity' => $skinval['quantity'],
                'delivery_status' => $skinval['delivery_status'],
                'created_at' => $skinval['created_at'],
                'TotalQuantitySold' => $skinval['TotalQuantitySold']
             );
      }
      
       foreach($hair_care_products1 as $hairval){
          $hair_care_products[] = array(
                'product_id' => $hairval['product_id'],
                'id' => $hairval['id'],
                'client_id' => $hairval['client_id'],
                'name' => $hairval['name'],
                'image' => base_url().'image/products/'.$hairval['image'],
                'category' => $hairval['category'],
                'description' => $hairval['description'],
                'price' => $hairval['price'],
                'quantity' => $hairval['quantity'],
                'delivery_status' => $hairval['delivery_status'],
                'created_at' => $hairval['created_at'],
                'TotalQuantitySold' => $hairval['TotalQuantitySold']
             );
      }
         
       //print_r($best_seller_products);die;
       if($best_seller_products or $skin_care_products or $hair_care_products){
           if(!empty($best_seller_products[0])){
              $best_seller_products = $best_seller_products; 
           }else{
               $best_seller_products = null;
           }
         if(!empty($skin_care_products[0])){
              $skin_care_products = $skin_care_products; 
           }else{
               $skin_care_products = null;
           }
         if(!empty($hair_care_products[0])){
              $hair_care_products = $hair_care_products; 
           }else{
               $hair_care_products = null;
           }
          if(!empty($offer_banner[0])){
              $offer_banner = $offer_banner; 
           }else{
               $offer_banner = null;
           }
           
        //  $best_seller_products = call_user_func_array('array_merge', $best_seller_products);
          // $skin_care_products = call_user_func_array('array_merge', $skin_care_products);
           //$hair_care_products = call_user_func_array('array_merge', $hair_care_products);
        //  print_r($best_seller_products);die; 
           
         //  print_r($best_seller_products);die;
           
        //  foreach ($client_cart_data as $d2)
        //     {
        //           $user_barcode = $d2['product_id'];
        //           //print_r($user_barcode);die;
        //           foreach ($best_seller_products as $d)
        //           {
        //             $merchant_barcode = $d['product_id']; 
        //           // print_r($merchant_barcode);die;
        //             if ($merchant_barcode == $user_barcode)
        //             {
        //                 // print_r('abc:'.$merchant_barcode); print_r($user_barcode);die;
        //                 $best_seller_products = array_merge($best_seller_products , ['cart_check'=>TRUE]);
        //             }
        //             else
        //             {
        //               // print_r($merchant_barcode); print_r($user_barcode);die;
        //                 $best_seller_products = array_merge($best_seller_products , ['cart_check'=>FALSE]);
        //             }
        //         }
        //     }  
            
            //  foreach ($client_cart_data as $d2)
            // {
            //       $user_barcode = $d2 ['product_id'];
            //       foreach ($skin_care_products as $d)
            //       {
            //         $merchant_barcode = $d ['product_id']; 
            //         if ($merchant_barcode == $user_barcode)
            //         {
            //             $skin_care_products = array_merge($best_seller_products , ['cart_check'=>TRUE]);
            //         }
            //         else
            //         {
            //           // print_r($merchant_barcode); print_r($user_barcode);die;
            //             $skin_care_products = array_merge($best_seller_products , ['cart_check'=>FALSE]);
            //         }
            //     }
            // } 
            
            //   foreach ($client_cart_data as $d2)
            // {
            //       $user_barcode = $d2 ['product_id'];
            //       foreach ($hair_care_products as $d)
            //       {
            //         $merchant_barcode = $d ['product_id']; 
            //         if ($merchant_barcode == $user_barcode)
            //         {
            //             $hair_care_products = array_merge($best_seller_products , ['cart_check'=>TRUE]);
            //         }
            //         else
            //         {
            //           // print_r($merchant_barcode); print_r($user_barcode);die;
            //             $hair_care_products = array_merge($best_seller_products , ['cart_check'=>FALSE]);
            //         }
            //     }
            // } 
            
         
           
        
        $response['status'] = true;
        $response['message'] = "Data Found!"; 
        $response['data'] = array('offer_banner' => $offer_banner, 'product_image_slider' => $offer_banner,'best_seller_products' => $best_seller_products, 'hair_care_products' => $hair_care_products, 'skin_care_products' => $skin_care_products,'skin_care_banner' => $offer_banner);

      }else{
        $response['status'] = false;
        $response['message'] = "No Data Found!"; 
      }
     //   }else{
      //      $response['status'] = false;
      //  $response['message'] = "Please enter client Id!";
      //  } 
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
    }

    public function fetchProductById(){
       // print_r("hello");die;
      $id = $this->input->post('product_id');
      $cid = $this->input->post('client_id');
      $res = $this->ClientModel->fetchProductDetailsById1($id);
     // print_r($res);die;
      $result = $this->ClientModel->fetchProductImagesById1($id);
      $cartdata = $this->ClientModel->getProductsFromClientCart($id,$cid);
      $wishlistdata = $this->ClientModel->getProductsFromClientWishlist($id,$cid);
     // print_r($wishlistdata);die;
      $response = array();
       if($res){
             foreach($result as $val){
                 if(!empty($val['image'])){
          $product_image[] = base_url().'image/products/'.$val['image'];
                 }else{
                   $product_image[] = "";  
                 }        
        
      }
      
       if(!empty($cartdata)){
           $isalreadyAddedtocart = true;
           $cartdata1 = array(
                   'cart_id' => $cartdata['id']
               );
       }else{
           $isalreadyAddedtocart = false;
           $cartdata1 = null;
       }
       
       if(!empty($wishlistdata)){
           $iswishlisted = true;
           $wishlistdata1 = array(
                   'wishlist_id' => $wishlistdata['id']
               );
       }else{
           $iswishlisted = false;
           $wishlistdata1 = null;
       }
      // print_r($iswishlisted);die;    
        $data = array(
            'id' => $res[0]['id'],
            'store_id'=> $res[0]['store_id'],
            'category'=> $res[0]['category'],
            'product_name'=> $res[0]['product_name'],
            'amount'=> $res[0]['amount'],
            'description'=> $res[0]['description'],
            'created_at'=> $res[0]['created_at'],
            'shop_phone_number'=> $res[0]['shop_phone_number'],
            'shop_id'=> $res[0]['shop_id'],
          );
         $new = array_merge( $data, array( "isalreadyAddedtocart" => $isalreadyAddedtocart, "iswishlisted" => $iswishlisted, "image_data" => $product_image, "wishlistdata" => $wishlistdata1, "cartdata" => $cartdata1 ) ); 
        $response['status'] = true;
        $response['message'] = "Data Found!";
        $response['data'] = $new; 
       // $response['data'] = array('product_data' => $data, 'image_data' => $product_image); 
      }else{
         $response['status'] = false;
        $response['message'] = "No Data Found!"; 
      }
    
      $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
      
    }


    /* function for edit client profile details
    * Vikash Rai
   */ 

   public function editClientProfile(){
     $id = $this->input->post('client_id');
     $email = $this->input->post('email');

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

     $image = $data_upload_files['file_name']; 
     
         if(!empty($id)){
          if(!empty($email)){
            
            $data = array(
               'full_name' => $this->input->post('full_name'),
               'country_code' => $this->input->post('country_code'),
               'mobile_number' => $this->input->post('mobile_number'),
               'email' => $email,
               'profile_image' => $image,
               'gender' => $this->input->post('gender')
              );
              $table = "client";
              $res = $this->MasterModel->updateData1($id,$data,$table);
              $response = array();
                if($res){
                  $result = $this->ClientModel->fetchClientDetailsById($id);
                  $response['status'] = true;
                  $response['message'] = "Data Saved Successfully!!";
                  $response['data'] = $result;
                }else{
                  $response['status'] = false;
                $response['message'] = "Something wrong!";
                }

          }else{
            $response['status'] = false;
            $response['message'] = "Please fill email!!";
          }

         }else{
          $response['status'] = false;
            $response['message'] = "Please fill client id!!";
         }
        
      $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 
   }
  

    public function test(){
     // print_r("expression");die;
      $num_files = count($_FILES['myFile']['name']);
      foreach($_FILES as $field_name => $file){
         $name[] = $file;
      }
     // print_r($name[0]['name']);die;
      print_r($num_files);die;
    }

    public function filterProducts(){
      $category = $this->input->post('category');
      $cost = $this->input->post('cost');
      $location = $this->input->post('location');

      if(!empty($category) and empty($cost) and empty($location)){
         $res = $this->ClientModel->fetchProductsByCategory($category);
        if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }
       
      }elseif(!empty($category) and empty($cost) and !empty($location)){
        $res = $this->ClientModel->fetchProductsByCategoryAndLocation($category,$location);
       // print_r($res);die;
        if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }

      }elseif(!empty($category) and !empty($cost) and empty($location)){
         if($cost == "50"){
           $res = $this->ClientModel->filterByCost1($category,$cost);
          // print_r($res);die;
           if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }

         }elseif($cost == "100-300"){
              $res = $this->ClientModel->filterByCost2($category,$cost);
          // print_r($res);die;
           if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }

         }else{
             $res = $this->ClientModel->filterByCost3($category,$cost);
          // print_r($res);die;
           if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }

         }

      }elseif(!empty($category) and !empty($cost) and !empty($location)){
         $res = $this->ClientModel->filterByCostCategoryLocation($category,$cost,$location);
         //print_r($res);die;
         if($res){
            $response['status'] = true;
            $response['message'] = "Data Found!";
            $response['data'] = $res; 
              }else{
                 $response['status'] = false;
                $response['message'] = "No Data Found!"; 
              }
      }else{
        $response['status'] = false;
        $response['message'] = "Please select filters!!";
      }
        
      $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 

    }
    
    public function clientBooking(){
      header('Access-Control-Allow-Origin: *');
      header("Content-type: application/json; charset=utf-8");
      $content = file_get_contents("php://input");
      $decoded = json_decode($content, true);
        $id = $decoded['shop_id'];
        $client = $decoded['client_id'];
        $service[] = $decoded['service'];
        $date = $decoded['date'];
        $time = $decoded['time'];
        $comment = $decoded['comment'];
       // print_r($service);die;
        if(!empty($date)){
            if(!empty($time)){
                if(!empty($service[0])){
                    $dataset = array(
                          'shop_id' => $id,
                          'client_id' => $client,
                          'date' => $date,
                          'time' => $time,
                          'comment' => $comment
                        );
                        $table = 'client_booking';
                    $res = $this->ClientModel->captureClientBooking($dataset,$table);
                    if($res){
                    foreach($service[0] as $val){
                        $data = array(
                              'booking_id' => $res,
                              'service' => $val
                            );
                            $table1 = 'client_booking_services';
                      $result = $this->ClientModel->captureClientBooking($data,$table1);        
                    }
                    
                     $message = "Your service booking has been placed successfully";
                     $message1 = "Your have received service booking order";
                     $masterdetails = $this->MasterModel->fetchMasterIdDetails($id);
                     $clientdetails = $this->MasterModel->fetchClientIdData($client);
                   // print_r($clientdetails);die;
                 
                    $device_id = $clientdetails[0]['fcm_token'];
                    $type = "1";
                    $client_id = $client;
                    $master_id = $id;
                    $master_device_id = $masterdetails[0]['fcm_token'];
                   $notification = $this->push_notification($device_id,$message,$type,$client_id,$master_id);
                   $notification1 = $this->push_notification1($master_device_id,$message1,$type,$client_id,$master_id);
                   $data11 = ['client_id' => $client_id, 'shop_id' => $master_id, 'message' => $message];
                   $a1 = $this->saveNotification($data11);
                   $data12 = ['shop_id' => $master_id, 'message' => $message1];
                   $a1 = $this->saveNotification1($data12);
                     $response['status'] = true;
                     $response['message'] = "Booking done successfully!";
                     $response['booking_id'] = $res; 
                  }else{
                     $response['status'] = false;
                    $response['message'] = "Something wrong!!"; 
                  }
                }else{
                    $response['status'] = false;
                    $response['message'] = "Please select services!!";
                }
            }else{
                $response['status'] = false;
                $response['message'] = "Please select time!!";
            }
        }else{
           $response['status'] = false;
           $response['message'] = "Please select date!!"; 
        }
        $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 
        
    }
    
    public function clientRemarks(){
        $shop_id = $this->input->post('shop_id');
        $client_id = $this->input->post('client_id');
        $remark = $this->input->post('remarks');
        $remarks_text = $this->input->post('remarks_text');
        if(!empty($remark)){
            if(!empty($remarks_text)){
            $data = array(
                  'shop_id' => $shop_id,
                  'client_id' => $client_id,
                  'remarks' => $remark,
                  'remarks_text' => $remarks_text
                );
                $table = 'client_remarks';
            $res = $this->MasterModel->saveData($data,$table);    
            if($res){
                $response['status'] = true;
                $response['message'] = "Remarks saves successfully!";
            }else{
              $response['status'] = false;
              $response['message'] = "Something wrong!!";   
            }
        }else{
           $response['status'] = false;
           $response['message'] = "Please enter remarks text!!"; 
        }
        }else{
           $response['status'] = false;
           $response['message'] = "Please enter remarks!!";  
        }    
        $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 
        
    }
    
    
     public function deleteProductById(){
      $id = $this->input->post('product_id');
      $res = $this->ClientModel->deleteProduct($id);
      if($res){
         $response['status'] = true;
         $response['message'] = "Product deleted successfully!";
      }else{
        $response['status'] = false;
        $response['message'] = "Something wrong!!"; 
      }
      $this->output
              ->set_content_type('application/json')
              ->set_output(json_encode($response)); 
    }
   
      

    public function updateProductById(){
      $id = $this->input->post('product_id');
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
                 $table = 'store_products';
       $storeproductresult = $this->MasterModel->updateData1($id,$storeproductsdetail,$table);
      // print_r($storeproductresult);die;
       if(!empty($storeproductresult)){
           $store_product_id = $id;
         // print_r("expression :".$store_product_id);die;
          
          $files = $_FILES;
          $cpt = count($_FILES['product_image']['name']);
         // print_r($cpt);die;
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
   // print_r($uploadImgData);die;
            // $store_product_image = $dataInfo[$i]['file_name'];
         foreach($uploadImgData as $val1){
           $storeproductimage = array(
              'store_product_id' => $store_product_id,
              'image' => $val1['product_image']
            ); 
                   
         $table2 = "store_products_images";
              $store_product_id = $store_product_id;
            //  'store_product_id' = $store_product_id;
       $storeproductresult = $this->MasterModel->updateData11($store_product_id,$storeproductimage,$table2);
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
    
    public function captureDeliveryAddress(){
        $id = $this->input->post('client_id');
        $name = $this->input->post('name');
        $address = $this->input->post('address');
        $mobile_number = $this->input->post('mobile_number');
        $payment_method = $this->input->post('payment_method');
        
        if(!empty($name)){
            if(!empty($address)){
                if(!empty($mobile_number)){
                    if(!empty($payment_method)){
                        $data = array(
                             'client_id' => $id,
                             'name' => $name,
                             'address' => $address,
                             'mobile_number' => $mobile_number,
                             'payment_mode' => $payment_method
                            );
                            $table = 'client_order_delivery_address';
                            $checkValue = $this->MasterModel->checkDeliveryAddess($id);
                       // print_r($checkValue);die;
                            if(empty($checkValue[0])){
                               $res = $this->MasterModel->saveData($data,$table);
                            }else{
                              $res = $this->MasterModel->updateClientDeliveryAddress($data,$table);
                       }
                       if($res){
                             $message = "Your order has been placed successfully";
                             $message1 = "Your shop product is ordered";
                             $table1 = "client";
                             $clientdetails = $this->MasterModel->fetchData($id,$table1);
                             $masterdetails = $this->MasterModel->fetchMasterDetails($id);
                           // print_r($masterdetails);die;
                            if(!empty($clientdetails[0]['fcm_token'])){
                            $device_id = $clientdetails[0]['fcm_token'];
                            $type = "2";
                            $client_id = $id;
                            $master_id = $masterdetails[0]['id'];
                            $master_device_id = $masterdetails[0]['fcm_token'];
                           $notification = $this->push_notification($device_id,$message,$type,$client_id,$master_id);
                           $notification1 = $this->push_notification1($master_device_id,$message1,$type,$client_id,$master_id);
                           $data11 = ['client_id' => $client_id, 'shop_id' => $master_id, 'message' => $message];
                           $a1 = $this->saveNotification($data11);
                           $data12 = ['shop_id' => $master_id, 'message' => $message1];
                           $a1 = $this->saveNotification1($data12);
                            }
                             $response['status'] = true;
                             $response['message'] = "Order placed successfully!!"; 
                       }else{
                            $response['status'] = false;
                            $response['message'] = "Something wrong!!"; 
                       }
                        
                    }else{
                         $response['status'] = false;
                        $response['message'] = "Please insert payment method!!"; 
                    }
                }else{
                     $response['status'] = false;
                     $response['message'] = "Please insert mobile number!!"; 
                }
            }else{
                 $response['status'] = false;
                 $response['message'] = "Please insert address!!"; 
            }
        }else{
          $response['status'] = false;
         $response['message'] = "Please insert name!!";  
        }
          $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));    
    }
    
    
      public function multipleProductsFilter(){
     // print_r("expression");die;
         header('Access-Control-Allow-Origin: *');
         header("Content-type: application/json; charset=utf-8");
         $content = file_get_contents("php://input");
         $decoded = json_decode($content, true);
        // print_r($decoded);die;
         $category = $decoded['category'];
         $cost = $decoded['cost'];
         $location = $decoded['location'];
        // print_r($category);die;
          if(!empty($category) and !empty($cost) and !empty($location)){
            foreach($cost as $val){
               if($val == "50"){
               // $category = "";
                  $res = $this->ClientModel->filterByCost11($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "100-300"){
                  $res = $this->ClientModel->filterByCost22($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "300"){
                  $res = $this->ClientModel->filterByCost33($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }else{
                  $newarray[] = "";
               }
            }

               foreach($category as $val){
              $res1 = $this->ClientModel->fetchProductsByCategory($val);
              $unique_arr1 = array_unique(array_column($res1 , 'id'));
             $newarray1[] = array_intersect_key($res1 , $unique_arr);
            }

             $res2 = $this->ClientModel->fetchProductsByLocation($location);
           // print_r($res);die;
           foreach($res2 as $val2){
            $unique_arr2 = array_unique(array_column($val2 , 'id'));
            $newarray2[] = array_intersect_key($val2 , $unique_arr);
          }
             $arr[] = $newarray + $newarray1 + $newarray2;
             $arraySingle = call_user_func_array('array_merge', $arr);
             $arraySingle1 = call_user_func_array('array_merge', $arraySingle);
            // print_r($arraySingle);die;
             $response['status'] = true;
              $response['message'] = "Data found!!";
              $response['data'] = $arraySingle1;

          }elseif(!empty($category) and !empty($cost) and empty($location)){
             foreach($cost as $val){
               if($val == "50"){
               // $category = "";
                  $res = $this->ClientModel->filterByCost11($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "100-300"){
                  $res = $this->ClientModel->filterByCost22($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "300"){
                  $res = $this->ClientModel->filterByCost33($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }else{
                  $newarray[] = "";
               }
            }

               foreach($category as $val){
              $res1 = $this->ClientModel->fetchProductsByCategory($val);
              $unique_arr1 = array_unique(array_column($res1 , 'id'));
             $newarray1[] = array_intersect_key($res1 , $unique_arr);
            }
             $arr[] = $newarray + $newarray1;
             $arraySingle = call_user_func_array('array_merge', $arr);
             $arraySingle1 = call_user_func_array('array_merge', $arraySingle);
            // print_r($arr);die;
             $response['status'] = true;
              $response['message'] = "Data found!!";
              $response['data'] = $arraySingle1;

          }elseif(empty($category) and !empty($cost) and !empty($location)){
              foreach($cost as $val){
               if($val == "50"){
               // $category = "";
                  $res = $this->ClientModel->filterByCost11($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "100-300"){
                  $res = $this->ClientModel->filterByCost22($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }elseif($val == "300"){
                  $res = $this->ClientModel->filterByCost33($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  
               }else{
                  $newarray[] = "";
               }
            }
            $arraySingle = call_user_func_array('array_merge', $newarray);
            
         // print_r($data);die;
              $response['status'] = true;
              $response['message'] = "Data found!!";
              $response['data'] = $arraySingle;  //add location here

          }elseif(!empty($category) and empty($cost) and !empty($location)){
             foreach($category as $val){
              $res = $this->ClientModel->fetchProductsByCategory($val);
              $unique_arr = array_unique(array_column($res , 'id'));
             $newarray[] = array_intersect_key($res , $unique_arr);
            }
             $res1 = $this->ClientModel->fetchProductsByLocation($location);
             foreach($res1 as $val1){
            $unique_arr1 = array_unique(array_column($val1 , 'id'));
            $newarray1[] = array_intersect_key($val1 , $unique_arr1);
          }
            
            $arr = $newarray + $newarray1;
            $storeid = $this->ClientModel->fetchStoreId($location);
            foreach($storeid as $ids){
              $filterBy = $ids[0]['id'];

              $new[] = $this->filter_array($arr,$filterBy);
         }
         $arraySingle = call_user_func_array('array_merge', $new);
         $arraySingle1 = call_user_func_array('array_merge', $arraySingle);
         // print_r($new);die;
              $response['status'] = true;
              $response['message'] = "Data found!!";
              $response['data'] = $arraySingle1;

          }elseif(!empty($category) and empty($cost) and empty($location)){
            //  print_r($category);die;
            foreach($category as $val){
              $res = $this->ClientModel->fetchProductsByCategory($val);
              $unique_arr = array_unique(array_column($res , 'id'));
             $newarray[] = array_intersect_key($res , $unique_arr);
            }
           // print_r($res);die;
             $arraySingle = call_user_func_array('array_merge', $newarray);
             //$arraySingle1 = call_user_func_array('array_merge', $arraySingle);
              $response['status'] = true;
              $response['message'] = "Data found!!";
              $response['data'] = $arraySingle;
             
             //print_r($newarray);die;

          }elseif(empty($category) and !empty($cost) and empty($location)){
            foreach($cost as $val){
               if($val == "50"){
               // $category = "";
                  $res = $this->ClientModel->filterByCost11($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  $response['status'] = true;
                  $response['message'] = "Data found!!";
                  $response['data'] = $newarray;
               }elseif($val == "100-300"){
                  $res = $this->ClientModel->filterByCost22($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  $response['status'] = true;
                  $response['message'] = "Data found!!";
                  $response['data'] = $newarray;
               }elseif($val == "300"){
                  $res = $this->ClientModel->filterByCost33($val);
                 // print_r($res);die;
                  $unique_arr = array_unique(array_column($res , 'id'));
                  $newarray[] = array_intersect_key($res , $unique_arr);
                  $arraySingle = call_user_func_array('array_merge', $newarray);
                  $response['status'] = true;
                  $response['message'] = "Data found!!";
                  $response['data'] = $arraySingle;
               }else{
                $response['status'] = false;
                $response['message'] = "Please select correct cost!!";
               }
            }

          }elseif(empty($category) and empty($cost) and !empty($location)){
            $res = $this->ClientModel->fetchProductsByLocation($location);
           // print_r($res);die;
           foreach($res as $val1){
            $unique_arr = array_unique(array_column($val1 , 'id'));
            $newarray[] = array_intersect_key($val1 , $unique_arr);
          }
             $arraySingle = call_user_func_array('array_merge', $newarray);
            $response['status'] = true;
            $response['message'] = "Data found!!";
            $response['data'] = $arraySingle;
          

          }else{
             $response['status'] = false;
             $response['message'] = "Please select filters!!";
          }

       $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
    }

    public function filter_array($array,$filterBy){
        $matches = array();
        foreach($array as $a){
            $new[] = array_filter($a, function ($var) use ($filterBy) {
                  return ($var['store_id'] == $filterBy);
              });
        }
        return $new;
        
    }

     public function incrementQunatity(){
      $cart_id = $this->input->post('cart_id');
      $quantity = $this->input->post('quantity');
      $res = $this->ClientModel->updateCart($cart_id,$quantity);
      if($res){
        $response['status'] = true;
        $response['message'] = "Quantity Updated!!";
      }else{
          $response['status'] = false;
          $response['message'] = "Something wrong!!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));

     }

     public function decrementQuantity(){
       $cart_id = $this->input->post('cart_id');
      $quantity = $this->input->post('quantity');
      if($quantity == "0"){
         $res = $this->ClientModel->deleteCart($cart_id);
      if($res){
        $response['status'] = true;
        $response['message'] = "Product deleted from cart!!";
      }else{
          $response['status'] = false;
          $response['message'] = "Something wrong!!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));

      }else{
        $res = $this->ClientModel->updateCart($cart_id,$quantity);
      if($res){
        $response['status'] = true;
        $response['message'] = "Quantity Updated!!";
      }else{
          $response['status'] = false;
          $response['message'] = "Something wrong!!";
      }
      $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));
      }
     }



     public function push_notification($device_id,$message,$type,$client_id,$master_id){
    //  public function push_notification(){
          
      //        $device_id = $this->input->post('device_id');
        //      $message = $this->input->post('message');
          // API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
        
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/   
            $api_key = 'AAAAQWHaA3o:APA91bG7BlN7rlLzPlKe3dlOi-b3fw1qYKAT0cCRQmesLFx3WNGshwrjC-f3eFYw7Hp0rk8QOEI5jio0VfC2yU_I0XSdGT3I2wqfJe5Vsm2kt7y-U0Agyx9g77YA-Isfmn-qZ-6V51gH';
                        
            // $fields = array (
            //     'registration_ids' => array (
            //             $device_id
            //     ),
            //     'data' => array (
            //             "message" => $message
            //     )
            // );
            
            $fields = array(
                 'to' => $device_id,
                 'data' => array(
                     "body" => $message,
                     "title"=> "Barber App",
                     "type" => $type,
                     "clientId" => $client_id,
                     "masterId" => $master_id,
                     )
                  );
        
            //header includes Content type and api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
           // print_r($result);die;
            return $result;
        }
        
        public function push_notification1($master_device_id,$message1,$type,$client_id,$master_id){
                  // API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
        
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/   
            $api_key = 'AAAAQWHaA3o:APA91bG7BlN7rlLzPlKe3dlOi-b3fw1qYKAT0cCRQmesLFx3WNGshwrjC-f3eFYw7Hp0rk8QOEI5jio0VfC2yU_I0XSdGT3I2wqfJe5Vsm2kt7y-U0Agyx9g77YA-Isfmn-qZ-6V51gH';
                        
            // $fields = array (
            //     'registration_ids' => array (
            //             $device_id
            //     ),
            //     'data' => array (
            //             "message" => $message
            //     )
            // );
            
            $fields = array(
                 'to' => $master_device_id,
                 'data' => array(
                     "body" => $message1,
                     "title"=> "Barber App",
                     "type" => $type,
                     "clientId" => $client_id,
                     "masterId" => $master_id,
                     )
                  );
        
            //header includes Content type and api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
           // print_r($result);die;
            return $result; 
        }
        
        
        public function fetchMasterCategories(){
            $table = "master_category";
            $res = $this->MasterModel->fetchShopHoursData1($table);
            $response = array();
            if($res){
                $response['status'] = true;
                $response['message'] = "Data Found!!";
                $response['data'] = $res;
              }else{
                  $response['status'] = false;
                  $response['message'] = "No Data Found!!";
              }
              $this->output
                          ->set_content_type('application/json')
                          ->set_output(json_encode($response));
         }
         
         
         public function deleteWishlistData(){
             $id = $this->input->post('wishlist_id');
             if(!empty($id)){
                 $res = $this->ClientModel->deleteWishlistData($id);
                 if($res){
                   $response['status'] = true;
                   $response['message'] = "Wishlist Data Deleted Successfully!!";
              }else{
                  $response['status'] = false;
                  $response['message'] = "Something Wrong!!";
              }
             }else{
                 $response['status'] = false;
                  $response['message'] = "Please insert wishlist id!!"; 
             }
              $this->output
                          ->set_content_type('application/json')
                          ->set_output(json_encode($response));
         }
         
         
         
          
          public function push_notification_demo(){
          
                 $device_id = $this->input->post('device_id');
              $message = $this->input->post('message');
              $type = $this->input->post('type');
              $client_id = $this->input->post('client_id');
              $master_id = $this->input->post('master_id');
          // API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
        
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/   
            $api_key = 'AAAAQWHaA3o:APA91bG7BlN7rlLzPlKe3dlOi-b3fw1qYKAT0cCRQmesLFx3WNGshwrjC-f3eFYw7Hp0rk8QOEI5jio0VfC2yU_I0XSdGT3I2wqfJe5Vsm2kt7y-U0Agyx9g77YA-Isfmn-qZ-6V51gH';
                        
            
            $fields = array(
                 'to' => $device_id,
                 'data' => array(
                     "body" => $message,
                     "title"=> "Barber App",
                     "type" => $type,
                     "clientId" => $client_id,
                     "masterId" => $master_id,
                     )
                  );
        
            //header includes Content type and api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
           // print_r($result);die;
            return $result;
        }
    
   
      public function fetchShopByLocation(){
         $location = $this->input->post('location');
             if(!empty($location)){
                 $res = $this->ClientModel->fetchShopByLocation($location);
                 if($res){
                     foreach($res as $val){
                       if(!empty($val['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$val['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                    }
                     if(!empty($val['shop_banner'])){
                          $shop_banner = base_url().'image/banner/'.$val['shop_banner'];
                           if(! @ file_get_contents($shop_banner)){
                               $shop_banner = "";
                              }else{
                               $shop_banner = $shop_banner; 
                           }
                  
                       }else{
                           $shop_banner = ""; 
                         }
                         
                       $data[] = array(
                            'id' => $val['id'],
                            'premium_shop' => $val['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $val['country_code'],
                            'shop_phone_number' => $val['shop_phone_number'],
                            'shop_name' => $val['shop_name'],
                            'gender' => $val['gender'],
                            'shop_area' => $val['shop_area'],
                            'shop_owner_name' => $val['shop_owner_name'],
                            'shop_address' => $val['shop_address'].','.$val['shop_city'],
                            'brand' => $val['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $val['shop_service_for'],
                            'user_type' => $val['user_type'],
                            'select_service' => $val['select_service'],
                            'uid' => $val['uid'],
                            'created_at' => $val['created_at']
                          );
             
                      }
                           $response['status'] = true;
                           $response['message'] = "Shop list fetched successfully!!";
                           $response['data'] = $data;
                      }else{
                          $response['status'] = false;
                          $response['message'] = "No shop found!!";
                      }
                     }else{
                         $response['status'] = false;
                          $response['message'] = "Please insert location!!"; 
                     }
                      $this->output
                                  ->set_content_type('application/json')
                                  ->set_output(json_encode($response));  
              }
              
              
       public function fetchProductsByNameCategory(){
           $search = $this->input->post('search');
           if(!empty($search)){
               $res = $this->ClientModel->fetchProductsByNameCategory111($search);
              // print_r($res);die;
                foreach($res as $val){
                    if(!empty($val['image'])){
                                  $product_image = base_url().'image/products/'.$val['image'];
                                   if(! @ file_get_contents($product_image)){
                                       $product_image = "";
                                      }else{
                                       $product_image = $product_image; 
                                   }
                          
                               }else{
                                   $product_image = ""; 
                             }
                      $data[] = array(
                                    'id' => $val['id'],
                                    'store_id' => $val['store_id'],
                                    'category' => $val['category'],
                                    'product_name' => $val['product_name'],
                                    'amount' => $val['amount'],
                                    'description' => $val['description'],
                                    'created_at' => $val['created_at'],
                                    'image' => $product_image
                          );
                    //      $id = $val['id'];
                    // $productimages = $this->ClientModel->fetchProductsImages($id); 
                    // $newdata[] = array_merge( $data, array( "product_images" => $productimages ) );
               }
              $unique_arr1 = array_unique(array_column($data, 'id'));
               $newarray1[] = array_intersect_key($data, $unique_arr1);
               $arraySingle1 = call_user_func_array('array_merge', $newarray1);
                $response['status'] = true;
                $response['message'] = "Shop list fetched successfully!!";
                $response['data'] = $arraySingle1;
               
           }else{
                $response['status'] = false;
                $response['message'] = "Please insert search keyword!!"; 
           }
           
            $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response));  
       }
       
       
       public function productsFilter(){
         // print_r("expression");die;
             header('Access-Control-Allow-Origin: *');
             header("Content-type: application/json; charset=utf-8");
             $content = file_get_contents("php://input");
             $decoded = json_decode($content, true);
            // print_r($decoded);die;
             $category = $decoded['category'];
             $cost = $decoded['cost'];
             $location = $decoded['location'];
            // print_r($category);die;
            
            if(!empty($category) and empty($cost) and empty($location)){
                foreach($category as $val){
                $res[] = $this->ClientModel->fetchShopByCategory($val);
                }
                  $arraySingle = call_user_func_array('array_merge', $res);
                 if(!empty($arraySingle)){
                     foreach($arraySingle as $value){
                         $id = $value['shop_id'];
                         $result[] = $this->ClientModel->fetchShopById($id);
                     }
                     $arraySingle1 = call_user_func_array('array_merge', $result);
                     foreach($arraySingle1 as $det){
                            if(!empty($det['profile_image'])){
                                $shop_owner_profile_image = base_url().'image/profile/'.$det['profile_image'];
                              if(! @ file_get_contents($shop_owner_profile_image)){
                                  $shop_owner_profile_image = "";
                               }else{
                                  $shop_owner_profile_image = $shop_owner_profile_image; 
                                    }
                          
                              }else{
                                 $shop_owner_profile_image = ""; 
                            }
                             if(!empty($det['shop_banner'])){
                                  $shop_banner = base_url().'image/banner/'.$det['shop_banner'];
                                   if(! @ file_get_contents($shop_banner)){
                                       $shop_banner = "";
                                      }else{
                                       $shop_banner = $shop_banner; 
                                   }
                          
                               }else{
                                   $shop_banner = ""; 
                             }
                         $details[] = array(
                                    'id' =>  $det['id'],
                                    'premium_shop'=> $det['premium_shop'],
                                    'shop_banner'=> $shop_banner,
                                    'country_code'=> $det['country_code'],
                                    'shop_phone_number'=> $det['shop_phone_number'],
                                    'shop_name'=> $det['shop_name'],
                                    'gender'=> $det['gender'],
                                    'email'=> $det['email'],
                                    'uid'=> $det['uid'],
                                    'fcm_token'=> $det['fcm_token'],
                                    'profile_image'=> $shop_owner_profile_image,
                                    'shop_area'=> $det['shop_area'],
                                    'shop_owner_name'=> $det['shop_owner_name'],
                                    'shop_address'=> $det['shop_address'],
                                    'shop_city'=> $det['shop_city'],
                                    'brand'=> $det['brand'],
                                    'shop_service_for'=> $det['shop_service_for'],
                                    'user_type'=> $det['user_type'],
                                    'select_service'=> $det['select_service'],
                                    'home_appointment'=> $det['home_appointment'],
                                    'saloon_appointment'=> $det['saloon_appointment'],
                                    'created_at'=> $det['created_at']
                             );
                     }
                      $response['status'] = false;
                     $response['message'] = "Data found successfully!!"; 
                     $response['data'] = $details;
                     
                 }else{
                     $response['status'] = false;
                     $response['message'] = "No data found!!";  
                 }
            }elseif(empty($category) and empty($cost) and !empty($location)){

                 $res = $this->ClientModel->fetchShopByLocation($location);
                 if($res){
                     foreach($res as $val){
                       if(!empty($val['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$val['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                    }
                     if(!empty($val['shop_banner'])){
                          $shop_banner = base_url().'image/banner/'.$val['shop_banner'];
                           if(! @ file_get_contents($shop_banner)){
                               $shop_banner = "";
                              }else{
                               $shop_banner = $shop_banner; 
                           }
                  
                       }else{
                           $shop_banner = ""; 
                         }
                         
                       $data[] = array(
                            'id' => $val['id'],
                            'premium_shop' => $val['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $val['country_code'],
                            'shop_phone_number' => $val['shop_phone_number'],
                            'shop_name' => $val['shop_name'],
                            'gender' => $val['gender'],
                            'shop_area' => $val['shop_area'],
                            'shop_owner_name' => $val['shop_owner_name'],
                            'shop_address' => $val['shop_address'].','.$val['shop_city'],
                            'brand' => $val['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $val['shop_service_for'],
                            'user_type' => $val['user_type'],
                            'select_service' => $val['select_service'],
                            'uid' => $val['uid'],
                            'created_at' => $val['created_at']
                          );
             
                      }
                           $response['status'] = true;
                           $response['message'] = "Shop list fetched successfully!!";
                           $response['data'] = $data;
                      }else{
                          $response['status'] = false;
                          $response['message'] = "No data found!!";
                      }
           
              }elseif(empty($category) and !empty($cost) and empty($location)){
                  $services = $this->ClientModel->fetchShopServicesByCost($cost);
                 // print_r($services);die;
                  foreach($services as $val){
                      $shop_id = $val['shop_id'];
                      $shopdetails[] = $this->ClientModel->fetchShopDetailsById($shop_id);
                      
                  }
                   $arraySingle = call_user_func_array('array_merge', $shopdetails);
                  // print_r($arraySingle);die;
                  if(!empty($arraySingle)){
                  foreach($arraySingle as $value){
                       if(!empty($value['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$value['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                        }
                         if(!empty($value['shop_banner'])){
                              $shop_banner = base_url().'image/banner/'.$value['shop_banner'];
                               if(! @ file_get_contents($shop_banner)){
                                   $shop_banner = "";
                                  }else{
                                   $shop_banner = $shop_banner; 
                               }
                      
                           }else{
                               $shop_banner = ""; 
                         }
                      
                       $data[] = array(
                            'id' => $value['id'],
                            'premium_shop' => $value['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $value['country_code'],
                            'shop_phone_number' => $value['shop_phone_number'],
                            'shop_name' => $value['shop_name'],
                            'gender' => $value['gender'],
                            'shop_area' => $value['shop_area'],
                            'shop_owner_name' => $value['shop_owner_name'],
                            'shop_address' => $value['shop_address'].','.$value['shop_city'],
                            'brand' => $value['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $value['shop_service_for'],
                            'user_type' => $value['user_type'],
                            'select_service' => $value['select_service'],
                            'uid' => $value['uid'],
                            'created_at' => $value['created_at']
                          );
                      
                  }
                    $response['status'] = true;
                    $response['message'] = "Shop list fetched successfully!!";
                    $response['data'] = $data;
              }else{
                    $response['status'] = false;
                    $response['message'] = "No data found!!";
                }
              }elseif(!empty($category) and empty($cost) and !empty($location)){
                    foreach($category as $val){
                $res[] = $this->ClientModel->fetchShopByCategory($val);
                }
                  $arraySingle = call_user_func_array('array_merge', $res);
                 // print_r($arraySingle);die;
                 if(!empty($arraySingle)){
                     foreach($arraySingle as $value){
                         $id = $value['shop_id'];
                          //print_r($id);die;
                         $result = $this->ClientModel->fetchShopByIdLocation($id,$location);
                          $arraySingle1[] = call_user_func_array('array_merge', $result);
                     }
                    // print_r($result);die;
                    
                    // print_r($arraySingle1);die;
                    if(!empty($arraySingle1)){
                        foreach($arraySingle1 as $value1){
                             if(!empty($value1['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$value1['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                    }
                     if(!empty($value1['shop_banner'])){
                          $shop_banner = base_url().'image/banner/'.$value1['shop_banner'];
                           if(! @ file_get_contents($shop_banner)){
                               $shop_banner = "";
                              }else{
                               $shop_banner = $shop_banner; 
                           }
                  
                       }else{
                           $shop_banner = ""; 
                         }
                         
                       $data[] = array(
                            'id' => $value1['id'],
                            'premium_shop' => $value1['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $value1['country_code'],
                            'shop_phone_number' => $value1['shop_phone_number'],
                            'shop_name' => $value1['shop_name'],
                            'gender' => $value1['gender'],
                            'shop_area' => $value1['shop_area'],
                            'shop_owner_name' => $value1['shop_owner_name'],
                            'shop_address' => $value1['shop_address'].','.$value1['shop_city'],
                            'brand' => $value1['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $value1['shop_service_for'],
                            'user_type' => $value1['user_type'],
                            'select_service' => $value1['select_service'],
                            'uid' => $value1['uid'],
                            'created_at' => $value1['created_at']
                          );
                        }
                        $response['status'] = true;
                    $response['message'] = "Shop list fetched successfully!!";
                    $response['data'] = $data;
                    }else{
                       $response['status'] = false;
                       $response['message'] = "No data found!!"; 
                    }
                 }else{
                     $response['status'] = false;
                     $response['message'] = "No data found!!";
                 }
                  }elseif(!empty($category) and !empty($cost) and empty($location)){
                    $services = $this->ClientModel->fetchShopServicesByCost($cost);
                 // print_r($services);die;
                  foreach($services as $val){
                      $shop_id = $val['shop_id'];
                      $shopdetails1 = $this->ClientModel->fetchShopDetailsByIdCategory($shop_id,$category);
                      $shopdetails[] = $shopdetails1;
                  }
                   foreach($shopdetails[0] as $dsata){
                       if(!empty($dsata['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$dsata['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                        }
                         if(!empty($dsata['shop_banner'])){
                              $shop_banner = base_url().'image/banner/'.$dsata['shop_banner'];
                               if(! @ file_get_contents($shop_banner)){
                                   $shop_banner = "";
                                  }else{
                                   $shop_banner = $shop_banner; 
                               }
                      
                           }else{
                               $shop_banner = ""; 
                         }
                      
                       $data[] = array(
                            'id' => $dsata['id'],
                            'premium_shop' => $dsata['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $dsata['country_code'],
                            'shop_phone_number' => $dsata['shop_phone_number'],
                            'shop_name' => $dsata['shop_name'],
                            'gender' => $dsata['gender'],
                            'shop_area' => $dsata['shop_area'],
                            'shop_owner_name' => $dsata['shop_owner_name'],
                            'shop_address' => $dsata['shop_address'].','.$dsata['shop_city'],
                            'brand' => $dsata['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $dsata['shop_service_for'],
                            'user_type' => $dsata['user_type'],
                            'select_service' => $dsata['select_service'],
                            'uid' => $dsata['uid'],
                            'created_at' => $dsata['created_at']
                          );
                      
                   }
                
                $response['status'] = true;
                    $response['message'] = "Shop list fetched successfully!!";
                    $response['data'] = $data;
                  
             }elseif(empty($category) and !empty($cost) and !empty($location)){
                  $services = $this->ClientModel->fetchShopServicesByCost($cost);
                 // print_r($services);die;
                  foreach($services as $val){
                      $shop_id = $val['shop_id'];
                      $shopdetails[] = $this->ClientModel->fetchShopDetailsByIdLocation($shop_id,$location);
                      
                  }
                  //print_r($shopdetails);die;
                  if(!empty($shopdetails)){
                      foreach($shopdetails[0] as $dsata){
                       if(!empty($dsata['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$dsata['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                        }
                         if(!empty($dsata['shop_banner'])){
                              $shop_banner = base_url().'image/banner/'.$dsata['shop_banner'];
                               if(! @ file_get_contents($shop_banner)){
                                   $shop_banner = "";
                                  }else{
                                   $shop_banner = $shop_banner; 
                               }
                      
                           }else{
                               $shop_banner = ""; 
                         }
                      
                       $data[] = array(
                            'id' => $dsata['id'],
                            'premium_shop' => $dsata['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $dsata['country_code'],
                            'shop_phone_number' => $dsata['shop_phone_number'],
                            'shop_name' => $dsata['shop_name'],
                            'gender' => $dsata['gender'],
                            'shop_area' => $dsata['shop_area'],
                            'shop_owner_name' => $dsata['shop_owner_name'],
                            'shop_address' => $dsata['shop_address'].','.$dsata['shop_city'],
                            'brand' => $dsata['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $dsata['shop_service_for'],
                            'user_type' => $dsata['user_type'],
                            'select_service' => $dsata['select_service'],
                            'uid' => $dsata['uid'],
                            'created_at' => $dsata['created_at']
                          );
                      
                   }
                
                $response['status'] = true;
                    $response['message'] = "Shop list fetched successfully!!";
                    $response['data'] = $data;
                  
                  }else{
                     $response['status'] = false;
                     $response['message'] = "No data found!!"; 
                  }
                }elseif(!empty($category) and !empty($cost) and !empty($location)){
                    $services = $this->ClientModel->fetchShopServicesByCost($cost);
                 // print_r($services);die;
                  foreach($services as $val){
                      $shop_id = $val['shop_id'];
                      $shopdetails = $this->ClientModel->fetchShopDetailsByIdCategory($shop_id,$category);
                       $arraySingle1[] = call_user_func_array('array_merge', $shopdetails);
                  }
                // print_r($arraySingle1);die;  
                   foreach($arraySingle1 as $value){
                      $id = $value['id'];
                      $details = $this->ClientModel->fetchShopByIdLocation($id,$location);
                      $arraySingle[] = call_user_func_array('array_merge', $details);
                   } 
               // print_r($arraySingle);die;
               if(!empty($arraySingle)){
                    foreach($arraySingle as $dsata){
                       if(!empty($dsata['profile_image'])){
                        $shop_owner_profile_image = base_url().'image/profile/'.$dsata['profile_image'];
                      if(! @ file_get_contents($shop_owner_profile_image)){
                          $shop_owner_profile_image = "";
                       }else{
                          $shop_owner_profile_image = $shop_owner_profile_image; 
                            }
                  
                      }else{
                         $shop_owner_profile_image = ""; 
                        }
                         if(!empty($dsata['shop_banner'])){
                              $shop_banner = base_url().'image/banner/'.$dsata['shop_banner'];
                               if(! @ file_get_contents($shop_banner)){
                                   $shop_banner = "";
                                  }else{
                                   $shop_banner = $shop_banner; 
                               }
                      
                           }else{
                               $shop_banner = ""; 
                         }
                      
                       $data[] = array(
                            'id' => $dsata['id'],
                            'premium_shop' => $dsata['premium_shop'],
                            'shop_banner' => $shop_banner,
                            'country_code' => $dsata['country_code'],
                            'shop_phone_number' => $dsata['shop_phone_number'],
                            'shop_name' => $dsata['shop_name'],
                            'gender' => $dsata['gender'],
                            'shop_area' => $dsata['shop_area'],
                            'shop_owner_name' => $dsata['shop_owner_name'],
                            'shop_address' => $dsata['shop_address'].','.$dsata['shop_city'],
                            'brand' => $dsata['brand'],
                            'shop_owner_profile_image' => $shop_owner_profile_image,
                            'shop_service_for' => $dsata['shop_service_for'],
                            'user_type' => $dsata['user_type'],
                            'select_service' => $dsata['select_service'],
                            'uid' => $dsata['uid'],
                            'created_at' => $dsata['created_at']
                          );
                      
                   }
                
                $response['status'] = true;
                    $response['message'] = "Shop list fetched successfully!!";
                    $response['data'] = $data;
               }else{
                     $response['status'] = false;
                     $response['message'] = "No data found!!";   
               }
            
            }else{
                $response['status'] = false;
                $response['message'] = "Please insert product filter keyword!!";   
            }
            
             $this->output
                  ->set_content_type('application/json')
                  ->set_output(json_encode($response)); 
        
    
       }
       
          public function push_notificationdemo(){
                  // API URL of FCM
            $url = 'https://fcm.googleapis.com/fcm/send';
        
            /*api_key available in:
            Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/   
            $api_key = 'AAAAQWHaA3o:APA91bG7BlN7rlLzPlKe3dlOi-b3fw1qYKAT0cCRQmesLFx3WNGshwrjC-f3eFYw7Hp0rk8QOEI5jio0VfC2yU_I0XSdGT3I2wqfJe5Vsm2kt7y-U0Agyx9g77YA-Isfmn-qZ-6V51gH';
                        
           $master_device_id = $this->input->post('master_device_id');
           $message1 = $this->input->post('message');
           $type = $this->input->post('type');
           $client_id = $this->input->post('client_id');
           $master_id = $this->input->post('master_id');
            $fields = array(
                 'to' => $master_device_id,
                 'data' => array(
                     "body" => $message1,
                     "title"=> "Barber App",
                     "type" => $type,
                     "clientId" => $client_id,
                     "masterId" => $master_id,
                     )
                  );
          // print_r($fields);die;
            //header includes Content type and api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$api_key
            );
                        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            print_r($result);die;
            return $result; 
        }

}      