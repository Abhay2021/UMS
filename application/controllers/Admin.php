<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller { 

    public function __construct()	
	{
        parent::__construct();	
        
	}	

public function dashboard(){
    if($this->session->admin && $this->session->logged_in && $this->session->id){
        $status = true;
    }
    else{ $status = false;
        echo "Access Denied !unauthorized access"; exit;
    }
    if($status){
        $user =  $this->db->query("SELECT * FROM `users`");
        $this->data['user'] = $user->result();
        $this->load->view('header');
        $this->load->view('dashboard',$this->data);
        $this->load->view('footer');
    }else{
        echo "Access Denied !unauthorized access"; exit;
    }
}

public function logout(){
    $this->session->sess_destroy();
    $message='logout successfully';
    $message=urlencode($message);
    redirect('admin/login_user?message='."$message");
}

 public function login_user(){
     if(isset($_GET['message'])){$this->data['message'] = $_GET['message'];}
     else{$this->data['message'] = '';}
    $this->load->view('header');
    $this->load->view('login',$this->data);
    $this->load->view('footer');

 }

 public function login(){
    $this->form_validation->set_rules('email', 'email', 'required');
    $this->form_validation->set_rules('password', 'Password', 'required');
    
    if ($this->form_validation->run() == FALSE)
    {
        $this->session->set_flashdata('error', validation_errors());
        redirect('admin/login_user');
    }
    else
    {  
        $email = $this->input->post('email');
        $password = $this->input->post('password');
       $user = $this->db->query("SELECT * FROM `users` WHERE `email`='$email'");
       $user_info = $user->row(0);
       if($user_info->active=='0'){
        $this->session->set_flashdata('error', 'Sorry!! Your account is temporarily deactivated. Please contact website admin');
        redirect('admin/login_user');
       }
       if(password_verify($password,$user_info->password)){
            if($user_info->admin==1){
                $admin = array(
                    'admin'  => TRUE,
                    'id'     => $user_info->id,
                    'city'   => $user_info->city,
                    'username'   => $user_info->username,
                    'image'=> $user_info->image,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($admin);
                redirect('admin/dashboard');
            }else{
                $user0 = array(
                    'user'  => TRUE,
                    'id'     => $user_info->id,
                    'city'   => $user_info->city,
                    'username'   => $user_info->username,
                    'image'=> $user_info->image,
                    'logged_in' => TRUE
                );
                $this->session->set_userdata($user0);
                redirect('user/dashboard');
            }
        
       }else{
        $this->session->set_flashdata('error', 'Wrong Username or Password');
        redirect('admin/login_user');
       }

    }
 }

 public function user_status(){
    $uid = $this->input->post('id');
    $status = $this->input->post('status');
   // print_r($_POST); exit;
    if($uid){
        if($status=='0'){
            $this->db->query("UPDATE `users` SET `active`='0' WHERE id='$uid'");
            $data = array('msg'=>'Activate');
        }else{
            $this->db->query("UPDATE `users` SET `active`='1' WHERE id='$uid'");
            $data = array('msg'=>'Deactivate'); 
        }
        echo json_encode($data);
    }else{
        echo "something went wrong";
    }
 }

 public function weatherReport(){
    if($this->session->admin && $this->session->logged_in && $this->session->id){
        $status = true;
        $city = $this->session->city;
        $response = fetchWeatherReport($city);
        if($response)
        {
            // echo "<pre>";
            // print_r($response);exit;
            $this->data['response'] = $response;
            $this->data['city'] = $city;
        }else{
            $this->data['response'] = '';
        }
            $this->load->view('header');
            $this->load->view('user_dashboard',$this->data);
            $this->load->view('footer');
    }else{
        echo "Access Denied !unauthorized access"; exit;
    }
 }
    
}


