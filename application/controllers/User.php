<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller { 

    public function __construct()	
	{
        parent::__construct();	
	}	


public function dashboard(){
    if($this->session->user && $this->session->logged_in && $this->session->id){
        $id = $this->session->id;
        $city = $this->session->city;
        
        $status = true;
    }
    else{ $status = false;
        echo "Access Denied !unauthorized access";
    }
    if($status && $id && $city){
        $curl = curl_init();
        $API = '161dd4f51faf20d6cfd1c2f86c6a7079';
        curl_setopt_array($curl, array(
        CURLOPT_URL => "api.openweathermap.org/data/2.5/weather?q=".$city."&APPID=".$API."",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
        ),
        ));

    $response = curl_exec($curl);
    
    $err = curl_error($curl);
    curl_close($curl);
    if($response)
    {
        $response = json_decode($response, true);
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
    }

   
}

public function save_user(){
    // print_r($_POST);exit;
     $this->form_validation->set_rules('username', 'Username', 'required');
     $this->form_validation->set_rules('email', 'email', 'required');
     $this->form_validation->set_rules('password', 'Password', 'required');
     $this->form_validation->set_rules('city', 'city', 'required');
     
     if ($this->form_validation->run() == FALSE)
     {
         $this->session->set_flashdata('error', validation_errors());
         redirect('user/register_user');
     }
     else
     { 
             $username = $this->input->post('username');
             $email = $this->input->post('email');
             $password = $this->input->post('password');
             $password = password_hash($password, PASSWORD_BCRYPT,[12]);
             $city=$this->input->post('city');
             $config['upload_path'] = './uploads/user';
             $config['allowed_types'] = 'gif|jpg|png';
             $config['max_size'] = '2097152';
             $this->load->library('upload', $config);
            // $this->upload->initialize($config);
                 if (!is_dir('uploads/user'))
                 {   $old_mask = umask(0);
                     mkdir('uploads/user', 0777, true);
                     umask($old_mask);
                 }
                 if ( ! $this->upload->do_upload('image'))
                 {  
                 $this->session->set_flashdata('error', 'required images are missing');
                 redirect('user/register_user');
                 }
                 else
                 {
                     $img = array('upload_data' => $this->upload->data());
                     $image = $img['upload_data']['file_name']; 
                             
                 }
 
                 $data = array('table'=>'users',
                             'val'=>array(
                                 'username'=>$username,
                                 'email'=>$email,
                                 'password'=>$password,
                                 'city'=>$city,
                                 'image'=>$image,
                                 'active'=>'1'
                             ));
                 $this->db->insert($data['table'],$data['val']);
                 $user_id = $this->db->insert_id();
             if($user_id){
                 redirect('admin/login_user','refresh');
             }else{
                 $this->session->set_flashdata('error', 'Oops! something went wrong please try again');
                 redirect('user/register_user');
             }
     }
 }

 public function register_user(){
    $this->load->view('header');
    $this->load->view('register');
    $this->load->view('footer');
 }


public function logout(){
    $this->session->sess_destroy();
    $message='logout successfully';
    $message=urlencode($message);
    redirect('admin/login_user?message='."$message");
}
    
   
}


