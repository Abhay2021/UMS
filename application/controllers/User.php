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
    }

   
}

public function save_user($id=null){
    // print_r($_POST);exit;
     $this->form_validation->set_rules('username', 'Username', 'required');
     $this->form_validation->set_rules('email', 'email', 'required');
     if(!$id){
        $this->form_validation->set_rules('password', 'Password', 'required');   
     }
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
             
             $city=$this->input->post('city');
             $image =null;
             if($_FILES['image']['name'])
             {
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
            }
                 $data = array('table'=>'users',
                             'val'=>array(
                                 'username'=>$username,
                                 'email'=>$email,
                                 'city'=>$city
                             ));
            if($password){
                $password = password_hash($password, PASSWORD_BCRYPT,[12]);
                 $data['val']['password']=$password;
                }
            if($image){
                $data['val']['image']=$image;
            }
            if($id){
                $this->db->where('id',$id);
                $this->db->update($data['table'],$data['val']);   
                $this->session->set_flashdata('error', 'user updated successfully');
                    redirect('admin/dashboard','refresh'); 
                }else{
                    $data['val']['active']='1';
                    $this->db->insert($data['table'],$data['val']);
                    $user_id = $this->db->insert_id();
                    if($user_id){
                    $this->session->set_flashdata('error', 'user created successfully');
                        redirect('admin/login_user','refresh');
                    }else{
                    $this->session->set_flashdata('error', 'Oops! something went wrong please try again');
                        }
                }
                 
     }
 }

 public function register_user($uid=null){
    //uid = user id
    if($uid){
        if($this->session->admin && $this->session->logged_in && $this->session->id)
        {
         $user =  $this->db->query("SELECT * FROM `users` WHERE id='$uid'");
        $info = $user->row(0);
        $this->data['user'] = $info;
        }else{
            $this->session->set_flashdata('error', 'Access Denied !unauthorized access');
            $this->logout();
              redirect('admin/login_user','refresh');
        }
        
    }else{ $this->data['a'] = ''; }
    $this->load->view('header');
    $this->load->view('register',$this->data);
    $this->load->view('footer');
 }


public function logout(){
    $this->session->sess_destroy();
    $message='logout successfully';
    $message=urlencode($message);
    redirect('admin/login_user?message='."$message");
}
    
   
}


