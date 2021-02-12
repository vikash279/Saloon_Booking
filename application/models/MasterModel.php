<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MasterModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
   
  }

  public function saveData($data,$table){
  	$res = $this->db->insert($table,$data);
  	if($res){
  		return $this->db->insert_id();
  	}else{
  		return FALSE;
  	}
  }

  public function fetchData($res,$table){
  	$where = array(
          'id' => $res
  		);
  //  print_r($table);die;		
  	$this->db->select('*');
  	$this->db->where($where);
  	$this->db->from($table);
  	$res = $this->db->get()->result_array();
  	if($res){
  		return $res;
  	}else{
  		return FALSE;
  	}
  }

   public function fetchShopProfileData($res,$table){
    $where = array(
          'shop_id' => $res
      );
   // print_r($where);die;
    $this->db->select('*');
    $this->db->where($where);
    $this->db->from($table);
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
  }

  public function fetchShopServicesByShop($id){
    $where = array(
          'shop_id' => $id
      );
    $this->db->select('*');
    $this->db->where($where);
    $this->db->from('services');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
  }



  public function fetchAllGenderTypes(){
  	  $this->db->select('gender_name');
  	  $this->db->from('gender');
  	  $res = $this->db->get()->result_array();
  	  if($res){
  	  return $res;
  	}else{
  		return FALSE;
  	}

  }

  public function fetchAllUserType(){
  	  $this->db->select('user_type_name');
  	  $this->db->from('user_type');
  	  $res = $this->db->get()->result_array();
  	  if($res){
  	  return $res;
	  	}else{
	  		return FALSE;
	  	}

  }


  public function fetchAllSaloonServices(){
  	 $this->db->select('saloon_service_name');
  	  $this->db->from('saloon_service');
  	  $res = $this->db->get()->result_array();
  	  if($res){
  	  return $res;
	  	}else{
	  		return FALSE;
	  	}
  }

  
  public function fetchUserLoginData($phone,$user_type){
    $where = array(
       'shop_phone_number' => $phone,
       'user_type' => $user_type
      );
    $this->db->select('id as master_id,country_code,shop_phone_number,shop_owner_name,gender,user_type');
      $this->db->where($where);
      $this->db->from('master_saloon');
      $res = $this->db->get()->result_array();
      if($res){
      return $res;
      }else{
        return FALSE;
      }
   
  }

     public function fetchOtpDetails($data){
        $where = array(
       'user_phone' => $data
      );
        $this->db->select('*');
        $this->db->order_by('id','DESC');
        $this->db->limit('1');
        $this->db->where($where);
        $this->db->from('login_otp_verification');
        $res = $this->db->get()->result_array();
        if($res){
        return $res[0];
        }else{
          return FALSE;
        }
     }

     public function updateData($id,$data,$table){
     // print_r($id);die;
      $where = array(
          'shop_id' => $id
        );
       $this->db->where($where);
      $result= $this->db->update($table, $data);
     if($result){
      return TRUE;
     } else{
      return FALSE;
     }
     }

     public function updateData1($id,$data,$table){
     // print_r($id);die;
      $where = array(
          'id' => $id
        );
   // print_r($where);die;    
       $this->db->where($where);
      $result= $this->db->update($table, $data);
     if($result){
      return TRUE;
     } else{
      return FALSE;
     }
     }
     
      public function updateData11($id,$data,$table){
     // print_r($id);die;
      $where = array(
          'store_product_id' => $id
        );
   // print_r($where);die;    
       $this->db->where($where);
      $result= $this->db->update($table, $data);
     if($result){
      return TRUE;
     } else{
      return FALSE;
     }
     }

     public function fetchAllShopServices(){
        $this->db->select('*');
        $this->db->from('services');
        $res = $this->db->get()->result_array();
        if($res){
        return $res;
        }else{
          return FALSE;
        }
     }

     public function fetchAllShopSubscription(){
        $this->db->select('*');
        $this->db->from('subscription');
        $res = $this->db->get()->result_array();
        if($res){
        return $res;
        }else{
          return FALSE;
        }
     }

     public function verifyPhone($phone,$user_type){
       $where = array(
          'shop_phone_number' => $phone,
          'user_type' => $user_type
        );
       $this->db->select('*');
       $this->db->where($where);
       $this->db->from('master_saloon');
       $res = $this->db->get()->result_array();
       if($res){
        return TRUE;
       }else{
        return FALSE;
       }
     }

     public function fetchNotification($id){
      $where = array(
         'shop_id' => $id
        );
      $this->db->select('*');
      $this->db->where($where);
      $this->db->from('master_saloon_notification');
      $res = $this->db->get()->result_array();
       if($res){
        return $res;
       }else{
        return FALSE;
       }
     }


     public function fetchShopHoursData($res,$table){
    $where = array(
          'shop_id' => $res
      );
    $this->db->select('*');
    $this->db->where($where);
    $this->db->from($table);
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
  }

    public function fetchShopHoursData1($table){
    $this->db->select('*');
    //$this->db->where($where);
    $this->db->from($table);
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
  }


  public function deleteServices($id,$table){
    $where = array(
       'id' => $id
      );
     $this->db->where($where);
     $res = $this->db->delete($table);
     if($res){
      return TRUE;
     }else{
      return FALSE;
     }
  }

  public function fetchShopTotalBooking($shop_id){
    $where = array(
        'shop_name' => $shop_id
      );
    $this->db->select('id as store_id');
    $this->db->where($where);
    $res = $this->db->get('store')->result_array();
    //print_r($res[0]['store_id']);die;
    if($res[0]){
     // return $res[0]['store_id'];
      $where1 = array(
          'store_id' => $res[0]['store_id']
        );
     // print_r($where1);die;
      $this->db->select('id as product_id');
      $this->db->where($where1);
      $query = $this->db->get('store_products')->result_array();
      foreach($query as $key => $value) {
        foreach($value as $key1 => $value1) {
            $data[$key1][] = $value1;
        }
    }
     $shop_products = $data['product_id'];
     $this->db->select('product_id');
     $res1 = $this->db->get('client_order')->result_array();
     foreach($res1 as $key2 => $value2) {
        foreach($value as $key3 => $value3) {
            $data1[$key3][] = $value3;
        }
    }
     $ordered_products = $data1['product_id'];
     $total_booking = count(array_intersect($ordered_products, $shop_products));
     // print_r($total_booking);die;
     return $total_booking;
     }else{
      return FALSE;
     }

    
  }

  public function fetchShopsAllBookings($shop_id){
     $where = array(
        'shop_name' => $shop_id
      );
    $this->db->select('id as store_id');
    $this->db->where($where);
    $res = $this->db->get('store')->result_array();
   // print_r($res[0]['store_id']);die;
    if($res[0]){
     // return $res[0]['store_id'];
      $where1 = array(
          'store_products.store_id' => $res[0]['store_id']
        );
     // print_r($where1);die;
      $this->db->select('store_products.*,store_products_images.image');
      $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
      $this->db->where($where1);
      $this->db->from('store_products','store_products_images');
      $query = $this->db->get()->result_array();
     // print_r($query);die;
      if($query){
       // $query = array_unique($query);
     $unique_arr = array_unique(array_column($query , 'id'));
    $newarray = array_intersect_key($query , $unique_arr);
        foreach($newarray as $val){
            if(!empty($val['image'])){
                $path = base_url().'image/products/'.$val['image'];
            }else{
                $path = "";
            }
          $data[] = array(
              'id' =>  $val['id'],
              'store_id' => $val['store_id'],
              'category' => $val['category'],
              'product_name' => $val['product_name'],
              'amount' => $val['amount'],
              'description' => $val['description'],
              'created_at' => $val['created_at'],
              'image' => $path
            );
        }
        return $data;
      }else{
        return FALSE;
      }
     }else{
       return FALSE;
     } 
  }
  
  public function fetchShopsAllBookingsDetails($id){
      $where = array(
            'client_booking.shop_id' => $id
           );
      $this->db->select('client_booking.*,client_booking_services.service,client.full_name,client.profile_image');
      $this->db->join('client_booking_services','client_booking_services.booking_id=client_booking.id','LEFT');
      $this->db->join('client','client.id=client_booking.client_id','LEFT');
      $this->db->where($where);
      $this->db->from('client_booking','client_booking_services','client');
      $res = $this->db->get()->result_array();
      if($res){
          return $res;
      }else{
          return FALSE;
      }
  }
  
  public function fetchShopsAllBookingsDetails111($shop_id,$client_id){
       $where = array(
            'client_booking.shop_id' => $shop_id,
            'client_booking.client_id' => $client_id
           );
      $this->db->select('client_booking.*,client_booking_services.service,client.full_name,client.profile_image');
      $this->db->join('client_booking_services','client_booking_services.booking_id=client_booking.id','LEFT');
      $this->db->join('client','client.id=client_booking.client_id','LEFT');
      $this->db->where($where);
      $this->db->from('client_booking','client_booking_services','client');
      $res = $this->db->get()->result_array();
      if($res){
          return $res;
      }else{
          return FALSE;
      }
  }
  
  public function fetchShopsAllBookingsClientDetails($id){
      $where = array(
            'shop_id' => $id
           );
        $this->db->select('client_id');
        $this->db->where($where);
        $this->db->from('client_booking');
        $res = $this->db->get()->result_array();
        return $res;
  }
  
  public function fetchClientDetailsForShopBooking($id){
      $where = array(
           'client_booking.client_id' => $id
          );
        $this->db->select('client_booking.*,client.full_name,client.profile_image,client.mobile_number');
        $this->db->join('client','client.id=client_booking.client_id','LEFT');
        $this->db->where($where);
        $this->db->from('client_booking','client');
        $res = $this->db->get()->result_array();
         if($res){
            return $res;
         }else{
            return FALSE;
        }  
             
  }


  public function fetchShopTotalEarning($shop_id){
    $where = array(
        'shop_name' => $shop_id
      );
    $this->db->select('id as store_id');
    $this->db->where($where);
    $res = $this->db->get('store')->result_array();
    if($res[0]){
     // print_r($res[0]['store_id']);die;
      $where1 = array(
          'store_id' => $res[0]['store_id']
        );
     // print_r($where1);die;
      $this->db->select('id as product_id');
      $this->db->where($where1);
      $query = $this->db->get('store_products')->result_array();
      foreach($query as $key => $value) {
        foreach($value as $key1 => $value1) {
            $data[$key1][] = $value1;
        }
    }
     $shop_products = $data['product_id'];
   // print_r($shop_products);die;
     foreach($shop_products as $number){
        $where2 = array(
           'product_id' => $number
          );
        $this->db->select('price');
        $this->db->where($where2);
        $result1[] = $this->db->get('client_order')->result_array();
     }
     $val1 = $result1;
   // print_r($val1);die;
    
    $sumArray = array();
        
        foreach ($val1 as $k=>$subArray) {
          foreach ($subArray as $id1=>$value6) {
            $sumArray[$id1]+=$value6['price'];
          }
        }
   // print_r($sumArray[0]);die;    
    $total_earning =$sumArray[0];
     return $total_earning;
  }else{
    return FALSE;
  }

 }

 public function fetchShopBanner($id){
  $where = array(
        'shop_id' => $id
    );
   $this->db->select('portfolio_image');
   $this->db->where($where);
   $res = $this->db->get('master_saloon_portfolio_image')->result_array();
   if($res){
    return $res;
   }else{
    return FALSE;
   }
 }
 
 public function fetchShopTotalBookingEarning($shop_id){
     $where = array(
           'client_booking.shop_id' => $shop_id,
           'client_booking.date' => date('Y-m-d')
         );
       //  print_r($where);die;
     $this->db->select('client_booking.id');
     $this->db->where($where);
     $this->db->from('client_booking');
     $res = $this->db->get()->result_array();
   //  print_r($res);die;
     if($res){
         foreach($res as $val){
             $booking_id = $val['id'];
             $where = array(
                  'client_booking_services.booking_id' => $booking_id
                 );
            $this->db->select('client_booking_services.service');
            $this->db->where($where);
            $this->db->from('client_booking_services');
            $result = $this->db->get()->result_array();
             
         }
       // print_r($result);die; 
         foreach($result as $value){
             $service = $value['service'];
             $where = array(
                  'shop_id' => $shop_id,
                  'service_name' => $service
                 );
            $this->db->select('service_cost');
            $this->db->where($where);
            $this->db->from('services');
            $query[] = $this->db->get()->result_array();
             
         }
         
          $sumArray = array();
        
        foreach ($query as $k=>$subArray) {
          foreach ($subArray as $id1=>$value6) {
            $sumArray[$id1]+=$value6['service_cost'];
          }
        }
   // print_r($sumArray[0]);die;    
    $total_earning =$sumArray[0];
     return $total_earning;
     }else{
         return FALSE;
     }
          
 }

   public function fetchAllCategories(){
    $this->db->select('*');
    $res = $this->db->get('category')->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }

   public function fetchShopTimePlace($id){
    $where = array(
        'shop_location.shop_id' => $id
      );
    $this->db->select('shop_location.*,shop_opening_hours.day,shop_opening_hours.slot_time');
    $this->db->join('shop_opening_hours','shop_opening_hours.shop_id=shop_location.shop_id','LEFT');
    $this->db->where($where);
    $this->db->from('shop_location','shop_opening_hours');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }

   public function fetchStoreProducts($id){
     $where = array(
        'shop_name' => $id
      );
      $this->db->select('id as store_id');
      $this->db->where($where);
      $result = $this->db->get('store')->result_array();
      foreach($result as $res1){
      $store_id = $res1['store_id'];
     // print_r($store_id);die;
      $this->db->select('store_products.*, store_products_images.image');
      $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
      $this->db->where('store_products.store_id',$store_id);
      $this->db->group_by('store_products.id');
      $this->db->from('store_products','store_products_images');
      $res[] = $this->db->get()->result_array();
      }
      $arraySingle = call_user_func_array('array_merge', $res);
     // print_r($arraySingle);die;
       $unique_arr = array_unique(array_column($arraySingle , 'id'));
    $newarray = array_intersect_key($arraySingle , $unique_arr);
   // print_r($newarray);die;
     foreach($newarray as $val){
         if($val['image']){
             $path = base_url().'image/products/'.$val['image'];
         }else{
             $path = "";
         }
         $data[] = array(
                'id'=> $val['id'],
                'store_id'=> $val['store_id'],
                'category'=> $val['category'],
                'product_name'=> $val['product_name'],
                'amount'=> $val['amount'],
                'description'=> $val['description'],
                'created_at'=> $val['created_at'],
                'image'=> $path,
             );
       // $this->db->select('*');
       // $this->db->where('store_product_id',$val['store_id']);
      //  $productimages[] = $this->db->get('store_products_images')->result_array();
     }
       $result1 = $data;
    //   foreach($productimages[0] as $value1){
    //      if(!empty($value1['image'])){
    //          $path1 = base_url().'image/products/'.$value1['image'];
    //      }else{
    //          $path1 = "";
    //      }
    //      $data2[] = array(
    //           'id' => $value1['store_product_id'],
    //           'image' => $path1
    //          );
    //   } 
       
    // $newresponsedata = array_push($result1,$data2[0]);
    // $newresponsedata = array_push($result1, ['imagelist' => $data2]);
      //print_r($result1);die;
      if($res){
        return $result1;
      }else{
        return FALSE;
      }
   }

   public function fetchMyShopOrder($id){
      $where = array(
        'shop_name' => $id
      );
      $this->db->select('id as store_id');
      $this->db->where($where);
      $res = $this->db->get('store')->result_array();
      $store_id = $res[0]['store_id'];
     // print_r($store_id);die;
       $where1 = array(
          'store_id' => $res[0]['store_id']
        );
     // print_r($where1);die;
      $this->db->select('id as product_id');
      $this->db->where($where1);
      $query = $this->db->get('store_products')->result_array();
      foreach($query as $key => $value) {
        foreach($value as $key1 => $value1) {
            $data[$key1][] = $value1;
        }
    }
     $shop_products = $data['product_id'];
    // print_r($shop_products);die;
     foreach($shop_products as $number){
        $where2 = array(
           'client_order.product_id' => $number
          );
        $this->db->select('client_order.*,client_order_delivery_address.mobile_number,client_order_delivery_address.name as client_name,client_order_delivery_address.address,client.profile_image');
        $this->db->join('client_order_delivery_address','client_order_delivery_address.client_id=client_order.client_id','LEFT');
        $this->db->join('client','client.id=client_order.client_id','LEFT');
        $this->db->where($where2);
        $this->db->from('client_order','client_order_delivery_address','client');
        $result1[] = $this->db->get()->result_array();
     }
     //$val1[] = $result1[0];
    //   foreach($result1 as $key => $value) {
    //     foreach($value as $key1 => $value1) {
    //         $data1[$key1][] = $value1;
    //     }
    // }
    // print_r($result1[0]);die;
     if($result1){
      return $result1;
     }else{
      return FALSE;
     }
   }
   
   public function fetchShopAllReviews($id){
       $where = array(
             'client_remarks.shop_id' => $id
           );
          $this->db->select('client_remarks.remarks,client_remarks.remarks_text,client.full_name,client.profile_image');
          $this->db->join('client','client.id=client_remarks.client_id','LEFT');
          $this->db->where($where);
          $this->db->from('client_remarks','client');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }
           
   }
   
   public function checkStoreExist($shop_name){
       $where = array(
             'shop_name' => $shop_name
           );
        $this->db->select('id');
        $this->db->where($where);
        $this->db->from('store');
        $res = $this->db->get()->result_array();
        if($res){
            return $res;
        }else{
            return FALSE;
        }
   }
   
   public function fetchShopTotalBookingCount($shop_id){
       $where = array(
             'shop_id' => $shop_id
           );
         $this->db->select('*');
         $this->db->where($where);
         $this->db->from('client_booking');
         $res = $this->db->count_all_results();
        // print_r($res);die;
        if($res){
            return $res;
        }else{
            return FALSE;
        }
   }
   
   public function fetchShopRatingCount($shop_id){
        $where = array(
             'shop_id' => $shop_id
           );
         $this->db->select('*');
         $this->db->where($where);
         $this->db->from('client_remarks');
         $res = $this->db->count_all_results();
        // print_r($res);die;
        if($res){
            return $res;
        }else{
            return FALSE;
        }
   }

   public function saveFCMToken($id,$fcm_token,$table){
      $where = array(
             'id' => $id
           ); 
       $data = array(
             'fcm_token' => $fcm_token
           ); 
       // print_r($where);die;   
       $this->db->where($where);
       $result= $this->db->update($table, $data);
         if($result){
          return TRUE;
         } else{
          return FALSE;
         }       
   }
     
     
   public function checkDeliveryAddess($id){
       $where = array(
             'client_id' => $id
           );
        $this->db->select('*');
        $this->db->where($where);
        $this->db->from('client_order_delivery_address');
        $res = $this->db->get()->result_array();
        if($res){
            return $res;
        }else{
            return FALSE;
        }
   }  
   
   public function updateClientDeliveryAddress($data,$table){
       $where = array(
             'client_id' => $data['client_id']
           );
        $this->db->where($where);
       $result= $this->db->update($table, $data);
         if($result){
          return TRUE;
         } else{
          return FALSE;
         }          
           
   }
   
   public function fetchMasterDetails($id){
       $where = array(
             'client_id' => $id
           );
           
         $this->db->select('product_id');
         $this->db->where($where);
         $this->db->order_by('id','DESC');
         $this->db->limit('1');
         $query = $this->db->get('client_order')->result_array();
      // print_r($query[0]['product_id']);die;
       if(!empty($query[0]['product_id'])){
           $product_id = $query[0]['product_id'];
           $this->db->select('store_id');
           $this->db->where('id',$product_id);
           $res = $this->db->get('store_products')->result_array();
          // print_r($res[0]['store_id']);die;
           if(!empty($res[0]['store_id'])){
           $store_id = $res[0]['store_id'];
           $this->db->select('shop_name');
           $this->db->where('id',$store_id);
           $res1 = $this->db->get('store')->result_array();
          // print_r($res1[0]['shop_name']);die;
           if(!empty($res1[0]['shop_name'])){
           $shop_id = $res1[0]['shop_name'];
           $this->db->select('id,fcm_token');
           $this->db->where('id',$shop_id);
           $res2 = $this->db->get('master_saloon')->result_array();
          // print_r($res2[0]['fcm_token']);die;
          return $res2;
       }else{
           return FALSE;
       }
       }else{
           return FALSE;
       }
       }else{
           return FALSE;
       }
   }
   
   public function fetchMasterIdDetails($id){
        $this->db->select('id,fcm_token');
           $this->db->where('id',$id);
           $res = $this->db->get('master_saloon')->result_array();
          // print_r($res);die
           if($res){
               return $res;
           }else{
               return FALSE;
           }
   }
   
   public function fetchClientIdData($client){
        $this->db->select('fcm_token');
           $this->db->where('id',$client);
           $res = $this->db->get('client')->result_array();
          // print_r($res);die
           if($res){
               return $res;
           }else{
               return FALSE;
           }
   }
   
   public function checkData($shop_id){
      $where = array(
       'shop_id' => $shop_id
      );
      $this->db->select('*');
     $this->db->where($where);
     $this->db->from('shop_opening_hours');
     $res = $this->db->get()->result_array();
     if($res){
      return $res;
     }else{
      return FALSE;
     } 
   }
   
   public function deleteOpeningClosingData($shop_id){
       $where = array(
       'shop_id' => $shop_id
       );
      $this->db->where($where);
      $res = $this->db->delete('shop_opening_hours');
      if($res){
       return TRUE;
      }else{
       return FALSE;
      } 
   }
   
   
   
   public function fetchShopDetailsForMaxOrders(){
      $data = $this->db->select('*, SUM(client_order.price) as total_qty')
                 ->from('client_order')
                 ->order_by('total_qty','desc')
                 ->group_by('product_id')
                 ->get()->result_array();
       if($data){
           foreach($data as $val){
               $prod_id = $val['product_id'];
               $where = ['id' => $prod_id];
               $this->db->select('store_id');
               $this->db->where($where);
               $this->db->from('store_products');
               $res[] = $this->db->get()->result_array();
           }
            $arraySingle = call_user_func_array('array_merge', $res);
            $unique_arr = array_unique(array_column($arraySingle , 'store_id'));
            $newarray = array_intersect_key($arraySingle , $unique_arr);
            if(!empty($newarray)){
                foreach($newarray as $value){
                    $store = $value['store_id'];
                    $where = ['id' => $store];
                    $this->db->select('shop_name');
                    $this->db->where($where);
                    $this->db->from('store');
                    $res1[] = $this->db->get()->result_array();
                }
                $arraySingle1 = call_user_func_array('array_merge', $res1);
                $unique_arr1 = array_unique(array_column($arraySingle1 , 'shop_name'));
                $newarray1 = array_intersect_key($arraySingle1 , $unique_arr1);
                foreach($newarray1 as $val1){
                    $id = $val1['shop_name'];
                    $where = ['id' => $id];
                    $this->db->select('*');
                    $this->db->where($where);
                    $this->db->from('master_saloon');
                    $res2[] = $this->db->get()->result_array();
                }
                $arraySingle2 = call_user_func_array('array_merge', $res2);
                $unique_arr2 = array_unique(array_column($arraySingle2 , 'shop_name'));
                $newarray2 = array_intersect_key($arraySingle2 , $unique_arr2);
                if(!empty($newarray2)){
                    return $newarray2;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE; 
            }
       }else{
           return FALSE;
       }
   }

}  