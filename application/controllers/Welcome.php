<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

       public function __construct()
        {
                parent::__construct();
                $this->load->helper('url');
                $this->load->library('form_validation');
                $this->load->helper('form');
                $this->load->library('session');
                $this->load->model('AdminModel');
                error_reporting(0); 

        }

 
    	public function index()
    	{
    		//$this->load->view('welcome_message');
    		$this->load->view('login');
    	}
    	
    	public function dashboard(){
    	     $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	    $totalmaster = $this->AdminModel->fetchTotalMasterShop();
    	    $totalmastershop = count($totalmaster);
    	    $totalsaloon = $this->AdminModel->fetchTotalSaloonShop();
    	    $totalsaloonshop = count($totalsaloon);
    	    $totalclients = $this->AdminModel->fetchTotalClients();
    	    $totalorders = $this->AdminModel->fetchTotalOrderPlaced();
    	    $totalearning = $this->AdminModel->fetchTotalEarning();
    	   // print_r($totalearning[0]['total']);die;
    	    if(!empty($totalearning[0]['total'])){
    	        $total = $totalearning[0]['total'];
    	    }else{
    	        $total = '0';
    	    }
    	    $data['totalmastershop'] = $totalmastershop;
    	    $data['totalsaloonshop'] = $totalsaloonshop;
    	    $data['clients'] = count($totalclients);
    	    $data['totalorders'] = count($totalorders);
    	    $data['totalearning'] = $total;
    	    $this->load->view('dashboard_new',$data);
    	    }else{
    	         redirect('Welcome');
    	    }
    	}
    	
    	public function login(){
    	    
    	     $user = $this->input->post('username');
    	     $pass = $this->input->post('password');
    	     $res = $this->AdminModel->verifyLogin($user);
    	    //print_r($res[0]['password']);die;
    	    if($res[0]){
    	    if($res[0]['password'] == $pass){
    	        $check= $this->session->set_userdata('userinfo',$res[0]);
    	        
                $check=$this->session->all_userdata('userinfo');
               // print_r($check);die;
                redirect('Welcome/dashboard');
    	        
    	    }else{
    	        $this->session->set_flashdata('message', 'Wrong Password!!');
               redirect('Welcome'); 

    	    }
    	    }else{
    	       $this->session->set_flashdata('message', 'User Does Not Exist!!');
               redirect('Welcome');  
    	    }  
    	}
    	
    	public function category(){
    	    $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	       $table = "master_saloon"; 
    	       $res['category'] = $this->AdminModel->fetchData($table); 
    	      // print_r($res);die;
    	       $this->load->view('category', $res);
    	    }else{
    	        redirect('Welcome');
    	    }
    	}
    	
    	
    	public function services(){
    	    $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	        $res['services'] = $this->AdminModel->fetchServicesData();
    	       // print_r($res);die;
    	        $this->load->view('services',$res);
    	    }else{
    	        redirect('Welcome');
    	    }
    	}
    	
    	public function shopCategories($encryptid){
    	    $base_64 = $encryptid . str_repeat('=', strlen($encryptid) % 4);
            $id = base64_decode($base_64); 
           
            $data['shopcategory'] = $this->AdminModel->fetchShopCategories($id);
           // print_r($data);die;
            $this->load->view('shop_categories', $data);
            
    	}   
    	
    	
    	public function shopBookings(){
    	     $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	        $table = "master_saloon"; 
    	       $res['shopdetails'] = $this->AdminModel->fetchData($table); 
    	      // print_r($res);die;
    	        $this->load->view('shopbookings',$res);
    	    }else{
    	        redirect('Welcome');
    	    }
    	}
    	
    	public function shopBookingDetails($encryptid){
    	     $base_64 = $encryptid . str_repeat('=', strlen($encryptid) % 4);
            $id = base64_decode($base_64); 
            //print_r($id);die;
            $data['shopbookingdetails'] = $this->AdminModel->fetchShopBookingDetails($id);
           // print_r($data);die;
            $this->load->view('shop_booking_details', $data);
    	}
    	
    	public function shopOrders(){
    	   $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	        $table = "master_saloon"; 
    	       $res['shoporders'] = $this->AdminModel->fetchData($table); 
    	      // print_r($res);die;
    	        $this->load->view('shop_orders',$res);
    	    }else{
    	        redirect('Welcome');
    	    } 
    	}
    	
    	public function shopOrderDetails($encryptid){
    	     $base_64 = $encryptid . str_repeat('=', strlen($encryptid) % 4);
            $id = base64_decode($base_64); 
           // print_r($id);die;
            $data['shoporderdetails'] = $this->AdminModel->fetchShopOrderDetails($id);
           // print_r($data);die;
            $this->load->view('shop_order_details', $data);
    	}
    	
    	public function clientDetails(){
    	    $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	        $table = "client"; 
    	       $res['clientdetails'] = $this->AdminModel->fetchData($table); 
    	      // print_r($res);die;
    	        $this->load->view('client_details',$res);
    	    }else{
    	        redirect('Welcome');
    	    } 
    	}
    	
    	public function clientBookingDetails($encryptid){
    	     $base_64 = $encryptid . str_repeat('=', strlen($encryptid) % 4);
            $id = base64_decode($base_64); 
           // print_r($id);die;
            $data['clientorderdetails'] = $this->AdminModel->fetchClientBookingDetails($id);
           // print_r($data);die;
            $this->load->view('client_booking_details', $data);
    	}
    	
    	public function clientOrders(){
    	     $check=$this->session->all_userdata();
    	    //print_r($check['userinfo']);die;
    	    if(!empty($check['userinfo'])){
    	        $table = "client"; 
    	       $res['clientdetails'] = $this->AdminModel->fetchData($table); 
    	      // print_r($res);die;
    	        $this->load->view('client_orders',$res);
    	    }else{
    	        redirect('Welcome');
    	    } 
    	}
    	
    	public function clientOrderDetails($encryptid){
    	     $base_64 = $encryptid . str_repeat('=', strlen($encryptid) % 4);
            $id = base64_decode($base_64); 
           // print_r($id);die;
            $data['clientorderdetails'] = $this->AdminModel->fetchClientOrderDetails($id);
           // print_r($data);die;
            $this->load->view('client_order_details', $data);
    	}
    	
    	
    	public function addShopCategory(){
    	    $table = "master_category";
    	    $res['category'] = $this->AdminModel->fetchData($table);
    	    $table1 = "master_saloon";
    	    $res['shop'] = $this->AdminModel->fetchData($table1);
    	   // print_r($res['shop']);die;
    	    $this->load->view('add_shop_category',$res);
    	}
    	
    	public function submitShopCategory(){
    	    $shopid = $this->input->post('shop_name');
    	    $cat = $this->input->post('cat_name');
    	    $data = ['shop_id' => $shopid, 'cat_name' => $cat];
    	   // print_r($data);die;
    	    $res = $this->AdminModel->insertShopCategory($data);
    	    $this->session->set_flashdata('success',"Shop category has been successfully registered");
            redirect(base_url('Welcome/addShopCategory'));

    	}
    	
    	
    	public function logout(){
    	    
              $this->load->library('session');
              $this->session->unset_userdata('userinfo');
	          $this->session->sess_destroy();
              redirect('Welcome');
            }

	
}
