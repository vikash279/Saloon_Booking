<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminModel extends CI_Model{

      public function __construct()
      {
        parent::__construct();
        $this->load->database();
       
      }
      
      
      public function verifyLogin($user){
          $where = array(
                'email' => $user
              );
          $this->db->select('*');
          $this->db->where($where);
          $this->db->from('admin');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }
      }
      
      public function fetchData($table){
          $this->db->select('*');
          $query = $this->db->get($table)->result_array();
          if($query){
              return $query;
          }else{
              return FALSE;
          }
      }
      
      public function fetchShopCategories($id){
          $where = array(
               'shop_id' => $id
              );
          $this->db->select('*');
          $this->db->where($where);
          $this->db->from('category');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }      
      }
      
      public function fetchServicesData(){
          $this->db->select('services.*,master_saloon.shop_name');
          $this->db->join('master_saloon','master_saloon.id=services.shop_id');
          $this->db->order_by('services.id','DESC');
          $this->db->from('services','master_saloon');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }   
      }
      
      public function fetchShopBookingDetails($id){
          $where = array(
               'client_booking.shop_id' => $id
              );
          $this->db->select('client_booking.*,client.full_name,,client_booking_services.service');
          $this->db->join('client','client.id=client_booking.client_id');
          $this->db->join('client_booking_services','client_booking_services.booking_id=client_booking.id');
          $this->db->where($where);
          $this->db->from('client_booking','client','client_booking_services');
           $this->db->order_by('client_booking.id','DESC');
          $res = $this->db->get()->result_array();
          if($res){
              return $res;
          }else{
              return FALSE;
          }       
      }
      
      public function fetchShopOrderDetails($id){
           $where = array(
               'shop_name' => $id
              );
           $this->db->select('*');
           $this->db->where($where);
           $this->db->from('store');
           $res = $this->db->get()->result_array();
          // print_r($res[0]['id']);die;
           if(!empty($res[0]['id'])){
               $where = ['store_id' => $res[0]['id']];
               $this->db->select('*');
               $this->db->where($where);
               $this->db->from('store_products');
               $result = $this->db->get()->result_array();
              // print_r($result);die;
                if(!empty($result[0])){
                    foreach($result as $val){
                        $prodid = $val['id'];
                        $where = ['client_order.product_id' => $prodid];
                        $this->db->select('client_order.*,client.full_name');
                        $this->db->join('client','client.id=client_order.client_id');
                        $this->db->where($where);
                        $this->db->from('client_order','client');
                        $this->db->order_by('client_order.id','DESC');
                        $query[] = $this->db->get()->result_array();
                        
                        
                    }
                    $arraySingle = call_user_func_array('array_merge', $query);
                  //  print_r($arraySingle);die;
                    return $arraySingle;
                }else{
                   return FALSE; 
                }    
           }else{
               return FALSE;
           }
      }
      
      public function fetchClientBookingDetails($id){
            $where = ['client_booking.client_id' => $id];
            $this->db->select('client_booking.*,client.full_name,master_saloon.shop_name,client_booking_services.service');
            $this->db->join('client','client.id=client_booking.client_id');
            $this->db->join('client_booking_services','client_booking_services.booking_id=client_booking.id');
            $this->db->join('master_saloon','master_saloon.id=client_booking.shop_id');
            $this->db->where($where);
            $this->db->order_by('client_booking.id','DESC');
            $this->db->from('client_booking','client','master_saloon','client_booking_services');
            $res = $this->db->get()->result_array();
              if($res){
                  return $res;
              }else{
                  return FALSE;
              }    
      }
      
      
      public function fetchClientOrderDetails($id){
            $where = ['client_order.client_id' => $id];
            $this->db->select('client_order.*,client.full_name');
            $this->db->join('client','client.id=client_order.client_id');
            $this->db->where($where);
            $this->db->from('client_order','client');
            $this->db->order_by('client_order.id','DESC');
            $query = $this->db->get()->result_array();
            if($query){
                  return $query;
              }else{
                  return FALSE;
              }   
      }
      
      public function insertShopCategory($data){
          $result=$this->db->insert('category', $data);
          if($query){
            return $query;
          }else{
            return FALSE;
         }   
      }
      
      
      public function fetchTotalMasterShop(){
            $where = ['user_type' => 'Master'];
            $this->db->select('*');
            $this->db->where($where);
            $this->db->from('master_saloon');
            $query = $this->db->get()->result_array();
             if($query){
                return $query;
              }else{
                return FALSE;
             }   
      }
      
      
      public function fetchTotalSaloonShop(){
          $where = ['user_type' => 'Saloon'];
            $this->db->select('*');
            $this->db->where($where);
            $this->db->from('master_saloon');
            $query = $this->db->get()->result_array();
             if($query){
                return $query;
              }else{
                return FALSE;
             }   
      }
      
      
      public function fetchTotalClients(){
          $this->db->select('*');
            $this->db->from('client');
            $query = $this->db->get()->result_array();
             if($query){
                return $query;
              }else{
                return FALSE;
             }   
      }
      
      
      public function fetchTotalOrderPlaced(){
         $this->db->select('*');
            $this->db->from('client_order');
            $query = $this->db->get()->result_array();
             if($query){
                return $query;
              }else{
                return FALSE;
             }   
      }
      
      
      public function fetchTotalEarning(){
          $this->db->select('sum(price) as total');
            $this->db->from('client_order');
            $query = $this->db->get()->result_array();
           // print_r($query[0]);die;
             if($query){
                return $query;
              }else{
                return FALSE;
             } 
      }
  
  
 
}  