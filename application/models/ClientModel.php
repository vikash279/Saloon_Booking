<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientModel extends CI_Model{

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
   
  }

  public function verifyClientPhone($mobile){
      $where = array(
          'mobile_number' => $mobile
        );
       $this->db->where($where);
       $this->db->from('client');
       $res = $this->db->get()->result_array();
       if($res){
        return TRUE;
       }else{
        return FALSE;
       }
     }

      public function fetchOtpDetails($data){
        $where = array(
       'client_number' => $data
      );
        $this->db->select('*');
        $this->db->order_by('id','DESC');
        $this->db->limit('1');
        $this->db->where($where);
        $this->db->from('client_otp_verification');
        $res = $this->db->get()->result_array();
        if($res){
        return $res[0];
        }else{
          return FALSE;
        }
     }

     public function fetchClientLoginData($data){
    $where = array(
       'mobile_number' => $data
      );
   // print_r($where);die;
    $this->db->select('id as client_id,full_name,country_code,mobile_number,gender');
      $this->db->where($where);
      $this->db->from('client');
      $res = $this->db->get()->result_array();
      if($res){
      return $res;
      }else{
        return FALSE;
      }
   
  }

   public function fetchClientDetailsById($id){
   	$where = array(
          'id' => $id
   		);
   	 $this->db->select('*');
      $this->db->where($where);
      $this->db->from('client');
      $res = $this->db->get()->result_array();
      if($res){
      return $res;
      }else{
        return FALSE;
      }
   }

   public function fetchClientCartDetails($id){
   	$where = array(
          'client_id' => $id
   		);
   	 $this->db->select('*');
      $this->db->where($where);
      $this->db->from('cart');
      $res = $this->db->get()->result_array();
      if($res){
      return $res;
      }else{
        return FALSE;
      }
   }

   public function totalCartAmountOfClient($id){
     $where = array(
          'client_id' => $id
     	);
     $this->db->select_sum('price');
     $this->db->where($where);
     $this->db->from('cart');
     $res = $this->db->get()->result_array();
     if($res){
     	return $res;
     }else{
     	return FALSE;
     }
   }

   public function fetchNotification($id){
   	$where = array(
         'client_id' => $id
        );
      $this->db->select('*');
      $this->db->where($where);
      $this->db->from('client_notification');
      $res = $this->db->get()->result_array();
       if($res){
        return $res;
       }else{
        return FALSE;
       }
   }

   public function fetchClientOrderData($id,$table){
    $where = array(
       'client_id' => $id
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
   
   public function fetchClientOrderData1($id){
        $where = array(
       'client_order.client_id' => $id
      );
          $this->db->select('client_order.*,store_products.store_id,store.shop_name as shop_id');
          $this->db->join('store_products','store_products.id=client_order.product_id','LEFT');
          $this->db->join('store','store.id=store_products.store_id','LEFT');
          $this->db->where($where);
          $this->db->from('client_order','store_products','store');
          $res = $this->db->get()->result_array();
           if($res){
            return $res;
           }else{
            return FALSE;
           }
   }
   
   public function fetchClientBookingDetails($id){
        $where = array(
         'client_booking.id' => $id
        );
       // print_r($where);die;
        $this->db->select('client_booking.*,client_booking_services.service,services.service_duration,services.service_cost,services.service_image');
        $this->db->join('client_booking_services','client_booking_services.booking_id=client_booking.id','LEFT');
        $this->db->join('services','services.service_name=client_booking_services.service','LEFT');
        $this->db->where($where);
        $this->db->from('client_booking','client_booking_services','services');
        $query = $this->db->get()->result_array();
        if($query){
            return $query;
        }else{
            return FALSE;
        }
   }
   
    public function fetchClientBookings($client_id){
         $where = array(
         'client_booking.client_id' => $client_id
        );
       // print_r($where);die;
        $this->db->select('client_booking.id as booking_id,client_booking.shop_id,client_booking.date,client_booking.time,client_booking.comment,master_saloon.*,shop_location.latitude,shop_location.longitude,shop_location.location
');
        $this->db->join('master_saloon','master_saloon.id=client_booking.shop_id','LEFT');
        $this->db->join('shop_location','shop_location.shop_id=client_booking.shop_id','LEFT');
        $this->db->where($where);
        $this->db->from('client_booking','master_saloon','shop_location');
        $query = $this->db->get()->result_array();
        if($query){
            return $query;
        }else{
            return FALSE;
        }
    }  
      
    // public function fetchClientBookingDetailImage($where){
    //      $this->db->select('service_duration,service_cost,service_image');
    //      $this->db->where($where);
    //      $this->db->from('services');
    //      $res = $this->db->get()->result_array();
            
    //       if($res[0]){
    //         return $res;
    //     }else{
    //         return FALSE;
    //     }
    // }

   public function deleteCartData($client_id){
    $where = array(
        'client_id' => $client_id,
        //'product_id' => $product_id
      );

     $this->db->where($where);
     $res = $this->db->delete('cart');
     if($res){
      return TRUE;
     }else{
      return FALSE;
     }
   }

   public function fetchData($table){
    $this->db->select('*');
    $this->db->from($table);
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }

   public function fetchShopStoreData(){
    $this->db->select('master_saloon.*,store.store_banner');
    $this->db->join('store','store.shop_name=master_saloon.id','LEFT');
    $this->db->from('master_saloon','store');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }

   public function fetchProductsByCategory($category){
    $where = array(
       'category' => $category
      );
      // print_r($where);die;
    $this->db->select('store_products.*,store_products_images.image');
    $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
    $this->db->where($where);
    $this->db->from('store_products','store_products_images');
    $res = $this->db->get()->result_array();
    // print_r($res);die;
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }
   
   public function fetchProductsByCategory11(){
  
    $this->db->select('store_products.*,store_products_images.image');
    $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
    $this->db->from('store_products','store_products_images');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }
   
   public function fetchProductsByCategoryShopId($shopid,$category){
       $this->db->select('id');
       $this->db->where('shop_name',$shopid);
       $query = $this->db->get('store')->result_array();
      // print_r($query);die;
       $storeid = $query[0]['id'];
       $where = array(
       'store_id' => $storeid,       
       'category' => $category
      );
        $this->db->select('store_products.*,store_products_images.image');
        $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
        $this->db->where($where);
        $this->db->from('store_products','store_products_images');
        $res = $this->db->get()->result_array();
        if($res){
          return $res;
        }else{
          return FALSE;
        }
   }
   
   public function fetchProductsByCategoryShopId11($shopid){
        $this->db->select('id');
       $this->db->where('shop_name',$shopid);
       $query = $this->db->get('store')->result_array();
      // print_r($query);die;
       $storeid = $query[0]['id'];
       $where = array(
       'store_id' => $storeid,       
      // 'category' => $category
      );
        $this->db->select('store_products.*,store_products_images.image');
        $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
        $this->db->where($where);
        $this->db->from('store_products','store_products_images');
        $res = $this->db->get()->result_array();
        if($res){
          return $res;
        }else{
          return FALSE;
        }
   }

   public function fetchDetails($id,$day){
    $where = array(
       'master_saloon.id' => $id,
       'shop_opening_hours.day' => $day
      );
   // print_r($where);die;
     $this->db->select('master_saloon.*,shop_opening_hours.slot_time as opening_closing_hours');
     $this->db->join('shop_opening_hours','shop_opening_hours.shop_id=master_saloon.id','LEFT');
     $this->db->where($where);
     $this->db->from('master_saloon','shop_opening_hours');
     $res = $this->db->get()->result_array();
    // print_r($res);die;
     if($res){
      return $res;
     }else{
      return FALSE;
     }
   }

 

   public function fetchProductDetailsById($id){
    $where = array(
       'store_products.id' => $id
      );
    $this->db->select('store_products.*,store_products_images.image as product_image');
    $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
    $this->db->where($where);
    $this->db->from('store_products','store_products_images');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }
   }
   
   public function fetchProductDetailsById1($id){
      
      $where = array(
       'store_products.id' => $id
      );
     
    $this->db->select('store_products.*,store.shop_name as shop_id,master_saloon.shop_phone_number');
    $this->db->join('store','store.id=store_products.store_id','LEFT');
    $this->db->join('master_saloon','master_saloon.id=store.shop_name','LEFT');
    $this->db->where($where);
    $this->db->from('store_products','store','master_saloon');
    $res = $this->db->get()->result_array();
    if($res){
      return $res;
    }else{
      return FALSE;
    }  
   }
   
   public function fetchProductImagesById1($id){
         $where = array(
       'store_products_images.store_product_id' => $id
      );
        $this->db->select('store_products_images.*');
       
        $this->db->where($where);
        $this->db->from('store_products_images');
        $res = $this->db->get()->result_array();
        if($res){
          return $res;
        }else{
          return FALSE;
        }  
   }


   public function fetchBestSellerProducts(){
     $SQL = "SELECT product_id,client_order.*, SUM(quantity) AS TotalQuantitySold
              FROM client_order
              GROUP BY product_id
              ORDER BY SUM(quantity) DESC
              LIMIT 5";

       $query = $this->db->query($SQL);
        $res = $query->result_array(); 
        if($res){
          return $res; 
        }else{
          return FALSE; 
        } 
          
   }

   public function fetchBestSkinCareProducts(){
    $SQL = "SELECT product_id,client_order.*, SUM(quantity) AS TotalQuantitySold 
            FROM client_order 
            WHERE category = 'Skin' 
            GROUP BY product_id 
            ORDER BY SUM(quantity) DESC 
            LIMIT 5";

       $query = $this->db->query($SQL);
        $res = $query->result_array();  
        if($res){
          return $res; 
        }else{
          return FALSE; 
        }   
   }

   public function fetchBestHairCareProducts(){
    $SQL = "SELECT product_id,client_order.*, SUM(quantity) AS TotalQuantitySold 
            FROM client_order 
            WHERE category = 'Hair' 
            GROUP BY product_id 
            ORDER BY SUM(quantity) DESC 
            LIMIT 5";

       $query = $this->db->query($SQL);
        $res = $query->result_array();  
        if($res){
          return $res; 
        }else{
          return FALSE; 
        }   
   }

   public function fetchFullShopDetails($phone,$table){
    $where = array(
        'mobile_number' => $phone
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

   public function fetchFullShopDetails1($phone){
    $where = array(
        'master_saloon.shop_phone_number' => $phone
      );
     $this->db->select('master_saloon.*');
    // $this->db->join('shop_opening_hours','shop_opening_hours.shop_id=master_saloon.id','LEFT');
    // $this->db->join('shop_location','shop_location.shop_id=master_saloon.id','LEFT');
       $this->db->where($where);
       $this->db->from('master_saloon');
       $res = $this->db->get()->result_array();
         if($res){
          return $res;
         }else{
          return FALSE;
         }
   }

   public function getchShopId($phone){
    $where = array(
         'shop_phone_number' => $phone
      );
    $this->db->select('id');
    $this->db->where($where);
    $res = $this->db->get('master_saloon')->result_array();
    if($res){
      return $res[0]['id'];
    }else{
      return FALSE;
    }
   }

   public function fetchSlotTime($id,$day){
     $where = array(
         'shop_id' => $id,
         'day' => $day
      );
    $this->db->select('*');
    $this->db->where($where);
    $res = $this->db->get('shop_opening_hours')->result_array();
    if($res){
      return $res;
    }else{
      return $res = null;
    }
   }

   public function fetchSaloonLocation($id){
    $where = array(
        'shop_id' => $id
      );
      $this->db->select('*');
      $this->db->where($where);
      $res = $this->db->get('shop_location')->result_array();
      if($res){
        return $res;
      }else{
        return $res = null;
      }
   }

   public function fetchProductsByCategoryAndLocation($category,$location){
    $where = array(
        'location' => $location
      );
      $this->db->select('shop_id');
      $this->db->where($where);
      $res = $this->db->get('shop_location')->result_array();
      if($res){
        foreach($res as $val){
          $shop_id = $val['shop_id'];
          $this->db->select('id');
          $this->db->where('shop_name',$val['shop_id']);
          $this->db->from('store');
          $result[] = $this->db->get()->result_array();
        }
        foreach($result as $val){
          $id1 = $val[0]['id'];
          $where = array(
              'store_products.store_id' => $id1
            );
          $this->db->select('store_products.*,store_products_images.image');
          $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
          $this->db->where($where);
          $this->db->from('store_products','store_products_images');
          $query[] = $this->db->get()->result_array();
          
        }//print_r($id1);die;
         if($query){
          return $query;
         }else{
          return FALSE;
         }
      }else{
        return FALSE;
      }
   }

    public function filterByCost1($category,$cost){
      $where = array(
          'category' => $category,
          'amount<=' => $cost
        );
     // print_r($where);die;
      $this->db->select('*');
      $this->db->where($where);
      $this->db->from('store_products');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }


    public function filterByCost2($category,$cost){
      $where = array(
          'category' => $category,
          'amount<=' => '300',
          'amount>=' => '100',
        );
     // print_r($where);die;
      $this->db->select('*');
      $this->db->where($where);
      $this->db->from('store_products');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }


    public function filterByCost3($category,$cost){
      $where = array(
          'category' => $category,
          'amount>=' => '300'
        );
     // print_r($where);die;
      $this->db->select('*');
      $this->db->where($where);
      $this->db->from('store_products');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }

    public function filterByCostCategoryLocation($category,$cost,$location){
        $where = array(
        'location' => $location
      );
      $this->db->select('shop_id');
      $this->db->where($where);
      $res = $this->db->get('shop_location')->result_array();
      if($res){
        foreach($res as $val){
          $shop_id = $val['shop_id'];
          $this->db->select('id');
          $this->db->where('shop_name',$val['shop_id']);
          $this->db->from('store');
          $result[] = $this->db->get()->result_array();
        }
        //print_r($result);die;
        foreach($result as $val){
          $store_id = $val[0]['id'];
          if($cost == "50"){
            $where = array(
                'store_id' => $store_id,
                'category' => $category,
                'amount<=' => $cost
              );
           // print_r($where);die;
            $this->db->select('*');
            $this->db->where($where);
            $this->db->from('store_products');
            $res = $this->db->get()->result_array();
            if($res){
                return $res;
              }else{
                return FALSE;
              }
            
          }elseif($cost == "100-300"){
             $where = array(
                'category' => $category,
                'store_id' => $store_id,
                'amount<=' => '300',
                'amount>=' => '100',
              );
             // print_r($where);die;
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('store_products');
              $res = $this->db->get()->result_array();
              if($res){
                return $res;
              }else{
                return FALSE;
              }
          }else{
                $where = array(
                    'category' => $category,
                    'store_id' => $store_id,
                    'amount>=' => '300'
                  );
               // print_r($where);die;
                $this->db->select('*');
                $this->db->where($where);
                $this->db->from('store_products');
                $res = $this->db->get()->result_array();
                if($res){
                  return $res;
                }else{
                  return FALSE;
                }
          }
        
          
         }//print_r($store_id);die;
    }else{
      return FALSE;
    }
   } 
   
   public function captureClientBooking($data,$table){
       	$res = $this->db->insert($table,$data);
          	if($res){
          		return $this->db->insert_id();
          	}else{
          		return FALSE;
          	}
           }
           
           
         public function deleteProduct($id){
       $where = array(
       'id' => $id
      );
         $this->db->where($where);
         $res = $this->db->delete('store_products');
         if($res){
          return TRUE;
         }else{
          return FALSE;
         }
   }   
   
    public function updateCart($cart_id,$quantity){
        
    $table = "cart";
     $where = array(
          'id' => $cart_id
        );
     $update = array(
          'quantity' => $quantity
         ); 
   // print_r($where);die;     
      $this->db->where($where);
      $result= $this->db->update($table,$update);
     if($result){
      return TRUE;
     } else{
      return FALSE;
     }
   }

   public function deleteCart($cart_id){
    $table = "cart";
    $where = array(
       'id' => $cart_id
      );
     $this->db->where($where);
     $res = $this->db->delete($table);
     if($res){
      return TRUE;
     }else{
      return FALSE;
     }
   }


   /*----------------------------------------------*/

    public function filterByCost11($cost){
      $where = array(
          'store_products.amount<=' => $cost
        );
     // print_r($where);die;
      $this->db->select('store_products.*,store_products_images.image');
      $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
      $this->db->where($where);
      $this->db->from('store_products','store_products_images');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }

     public function filterByCost1111($cost,$location){
           $where = array(
        'location' => $location
      );
         $this->db->select('shop_id');
         $this->db->where($where);
         $res = $this->db->get('shop_location')->result_array();
         //print_r($res);die;
         if($res){
          foreach($res as $val){
          $shop_id = $val['shop_id'];
          $this->db->select('id');
          $this->db->where('shop_name',$val['shop_id']);
          $this->db->from('store');
          $result[] = $this->db->get()->result_array();
    }
       print_r($result);die;
      foreach ($result as $value) {
           $store_id = $value[0]['id'];
            $where = array(
              'store_products.store_id' => $store_id,
              'store_products.amount<=' => $cost
            );
           // print_r($where);die;
            $this->db->select('store_products.*,store_products_images.image');
            $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
            $this->db->where($where);
            $this->db->from('store_products','store_products_images');
            $result1[] = $this->db->get()->result_array();
     }
      print_r($result1);die;
      if($result1){
        return $result1;
      }else{
        return FALSE;
      }
    }else{
      return FALSE;
     }
    }  


     public function filterByCost22($cost){
      $where = array(
          'store_products.amount<=' => '300',
          'store_products.amount>=' => '100',
        );
     // print_r($where);die;
       $this->db->select('store_products.*,store_products_images.image');
      $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
      $this->db->where($where);
      $this->db->from('store_products','store_products_images');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }

     public function filterByCost33($cost){
      $where = array(
          'store_products.amount>=' => '300'
        );
     // print_r($where);die;
      $this->db->select('store_products.*,store_products_images.image');
      $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
      $this->db->where($where);
      $this->db->from('store_products','store_products_images');
      $res = $this->db->get()->result_array();
      if($res){
        return $res;
      }else{
        return FALSE;
      }
    }

    public function fetchProductsByLocation($location){
        if($location == "home"){
            $where = array(
            'home_appointment' => "Yes"
           );
            $this->db->select('id as shop_id');
            $this->db->where($where);
            $res = $this->db->get('master_saloon')->result_array();
        }elseif($location == "saloon"){
            $where = array(
            'saloon_appointment' => "Yes"
           );
           $this->db->select('id as shop_id');
            $this->db->where($where);
            $res = $this->db->get('master_saloon')->result_array();
        }else{
          $where = array(
            'location' => $location
           ); 
           
            $this->db->select('shop_id');
            $this->db->where($where);
            $res = $this->db->get('shop_location')->result_array();
        }
        
        
         //print_r($res);die;
         if($res){
          foreach($res as $val){
          $shop_id = $val['shop_id'];
          $this->db->select('id');
          $this->db->where('shop_name',$val['shop_id']);
          $this->db->from('store');
          $result[] = $this->db->get()->result_array();
    }
        foreach ($result as $value) {
           $store_id = $value[0]['id'];
            $where = array(
              'store_products.store_id' => $store_id
            );
           // print_r($where);die;
            $this->db->select('store_products.*,store_products_images.image');
            $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
            $this->db->where($where);
            $this->db->from('store_products','store_products_images');
            $result1[] = $this->db->get()->result_array();
            
        }
        if($result1){
              return $result1;
            }else{
              return FALSE;
            }
       
   }else{
    return FALSE;
   } 


  }

     public function fetchStoreId($location){
       $where = array(
          'location' => $location
        );
         $this->db->select('shop_id');
         $this->db->where($where);
         $res = $this->db->get('shop_location')->result_array();
         //print_r($res);die;
          foreach($res as $val){
          $shop_id = $val['shop_id'];
          $this->db->select('id');
          $this->db->where('shop_name',$val['shop_id']);
          $this->db->from('store');
          $result[] = $this->db->get()->result_array();
         
         }
           return $result;
      } 
      
      public function productAlreadyInCart($client_id){
          $where = array(
                'client_id' => $client_id,
              );
            //  print_r($where);die;
          $this->db->select('*');
          $this->db->where($where);
          $this->db->from('cart');
          $res = $this->db->get()->result_array();
         // print_r($res);die;
          if($res){
              return $res;
          }else{
              return FALSE;
          }
      }
      
      public function getProductsFromClientCart($id,$cid){
           $where = array(
                'client_id' => $cid,
                'product_id' => $id
              );
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('cart');
              $res = $this->db->get()->result_array();
              if($res[0]){
                  return $res[0];
              }else{
                  return FALSE;
              }  
      }
      
      public function getProductsFromClientWishlist($id,$cid){
          $where = array(
                'client_id' => $cid,
                'product_id' => $id
              );
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('wishlist');
              $res = $this->db->get()->result_array();
              //return $res;
              if($res[0]){
                  return $res[0];
              }else{
                  return FALSE;
              }  
      }
      
      
      public function deleteWishlistData($id){
          $table = "wishlist";
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
      
      
      public function fetchShopByLocation($location){
           $where = array(
                'shop_area' => $location,
              );
            //  print_r($where);die;
          $this->db->select('*');
          $this->db->where($where);
          $this->db->from('master_saloon');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          } 
      }
      
      
      public function fetchProductsByNameCategory($search){
        
          $this->db->select('store_products.*');
          $this->db->where('store_products.category',$search);
          $this->db->or_where('store_products.product_name',$search);
          $this->db->from('store_products');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }  
      }
      
      
       public function fetchProductsByNameCategory111($search){
        
          $this->db->select('store_products.*,store_products_images.image');
          $this->db->join('store_products_images','store_products_images.store_product_id=store_products.id','LEFT');
          $this->db->where('store_products.category',$search);
          $this->db->or_where('store_products.product_name',$search);
          $this->db->from('store_products','store_products_images');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }  
      }
      
      
      public function fetchProductsImages($id){
          $where = ['store_product_id' => $id];
          $this->db->select('image');
          $this->db->where($where);
          $this->db->from('store_products_images');
          $res = $this->db->get()->result_array();
              if($res){
                  return $res;
              }else{
                  return FALSE;
              } 
      }
      
      public function fetchShopByCategory($category){
          $where = ['cat_name' => $category];
          $this->db->select('shop_id');
          $this->db->where($where);
          $this->db->from('category');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }   
      }
      
      
      public function fetchShopById($id){
              $where = ['id' => $id];
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('master_saloon');
              $res = $this->db->get()->result_array();
              if($res){
                  return $res;
              }else{
                  return FALSE;
              }   
      }
      
      public function fetchShopServicesByCost($cost){
          if($cost == "50"){
                 
                   $where = array(
                      'services.service_cost<=' => '50'
                    );
                 // print_r($where);die;
                  $this->db->select('*');
                  $this->db->where($where);
                  $this->db->from('services');
                  $res = $this->db->get()->result_array();
                  //print_r($res);die;
                  if($res){
                      return $res;
                  }else{
                      return FALSE;
                  }   
          }elseif($cost == "100-300"){
               $where = array(
                       'services.service_cost<=' => '300',
                       'services.service_cost>=' => '100',
                    );
                 // print_r($where);die;
                  $this->db->select('*');
                  $this->db->where($where);
                  $this->db->from('services');
                  $res = $this->db->get()->result_array();
                  //print_r($res);die;
                  if($res){
                      return $res;
                  }else{
                      return FALSE;
                  } 
          }elseif($cost == "300"){
                $where = array(
                      'services.service_cost>=' => '300'
                    );
                 // print_r($where);die;
                  $this->db->select('*');
                  $this->db->where($where);
                  $this->db->from('services');
                  $res = $this->db->get()->result_array();
                  //print_r($res);die;
                  if($res){
                      return $res;
                  }else{
                      return FALSE;
                  }   
          }else{
             return FALSE;
          }
          
      }
      
      
      public function fetchShopDetailsById($shop_id){
          $where = ['id' => $shop_id];
          $this->db->select('*');
          $this->db->where($where);
          $this->db->from('master_saloon');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }   
      }
      
      public function fetchShopByIdLocation($id,$location){
           $where = ['id' => $id];
              $this->db->select('*');
              $this->db->where($where);
              $this->db->where("shop_area LIKE '%$location%'");
              $this->db->from('master_saloon');
              $res = $this->db->get()->result_array();
             // print_r($res);die;
              if($res){
                  return $res;
              }else{
                  return FALSE;
              } 
      }

   
     public function fetchShopDetailsByIdCategory($shop_id,$category){
         foreach($category as $val){
         $this->db->select('*');
          $this->db->where('shop_id',$shop_id);
          $this->db->where("cat_name", $val);
          $this->db->from('category');
          $res[] = $this->db->get()->result_array();
         } 
         $arraySingle = call_user_func_array('array_merge', $res);
          if(!empty($arraySingle)){
             foreach($arraySingle as $value){
                 $shop_id = $value['shop_id'];
                 $details[] = $this->fetchShopDetailsById($shop_id);
             }
             $arraySingle1 = call_user_func_array('array_merge', $details);
             //print_r($arraySingle1);die;
             if($arraySingle1){
                  return $arraySingle1;
              }else{
                  return FALSE;
              } 
          }else{
              return FALSE;
          }
          
     }
     
     
     public function fetchShopDetailsByIdLocation($shop_id,$location){
          $where = ['id' => $shop_id];
              $this->db->select('*');
              $this->db->where($where);
              $this->db->where("shop_area LIKE '%$location%'");
              $this->db->from('master_saloon');
              $res = $this->db->get()->result_array();
             // print_r($res);die;
              if($res){
                  return $res;
              }else{
                  return FALSE;
              } 
     }
     
     public function captureNotification($data){
            $table = "client_notification";
         	$res = $this->db->insert($table,$data);
          	if($res){
          		return $this->db->insert_id();
          	}else{
          		return FALSE;
          	}
     }
     
      public function captureMasterNotification11($data){
            $table = "master_saloon_notification";
         	$res = $this->db->insert($table,$data);
          	if($res){
          		return $this->db->insert_id();
          	}else{
          		return FALSE;
          	}
     }
     
     public function fetchStoreIdByProductId($product_id){
              $where = ['id' => $product_id];
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('store_products');
              $res = $this->db->get()->result_array();
             // print_r($res);die;
              if($res){
                  return $res;
              }else{
                  return FALSE;
              } 
     }
     
     public function fetchShopIdByStoreId($store_id){
              $where = ['id' => $store_id];
              $this->db->select('*');
              $this->db->where($where);
              $this->db->from('store');
              $res = $this->db->get()->result_array();
             // print_r($res);die;
              if($res){
                  return $res;
              }else{
                  return FALSE;
              } 
     }
}  