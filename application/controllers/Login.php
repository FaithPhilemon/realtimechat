<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
    }

    public function index() {
        if ($this->session->userdata('user_id')) {
            redirect('home');
        }
        $this->load->view('login');
    }

    public function save_user() {
        $data['username']   = $username           = $this->input->post('username');
        $data['email']      = $email              = $this->input->post('email');
        $password           = $this->input->post('password');
        $data['password']   = sha1($password);
        $data['created_at'] = date('Y-m-d H:i:s');

        $result         = array();
        $check_username = $this->app_model->checkUserName($username);
        if ($check_username > 0) {
            $result['status']  = 'failed';
            $result['message'] = '<div class="error_msg">Username already exist</div>';
        } else {
            $check_email = $this->app_model->checkEmail($email);
            if ($check_email > 0) {
                $result['status']  = 'failed';
                $result['message'] = '<div class="error_msg">Email already exist</div>';
            } else {
                $user_id             = $this->app_model->save_user($data);
                $session['user_id']  = $user_id;
                $session['username'] = $username;
                $session['login']    = TRUE;
                $this->session->set_userdata($session);

                $insert_room['user_id']      = $user_id;
                $insert_room['chat_room_id'] = 1; // Your chat room id from database
                $this->app_model->insertChatRoom($insert_room);

                $result['status'] = 'success';
            }
        }
        echo json_encode($result);
    }

    public function logout() {
        $user_id   = $this->session->userdata('user_id');
        $this->app_model->outFromAllRoom($user_id);
        $user_sess = array('user_id', 'login', 'username');
        $this->session->unset_userdata($user_sess);
        redirect('login');
    }

    public function loginCheck() {
        $result   = array();
        $username = $this->input->post('username');
        $password = sha1($this->input->post('password'));
        $get_user = $this->app_model->checkUser($username, $password);
        if ($get_user) {
            $s['user_id'] = $get_user->user_id;
            $s['username'] = $get_user->username;
            $s['email'] = $get_user->email;
            $this->session->set_userdata($s);
            $insert_room['user_id']      = $get_user->user_id;
            $insert_room['chat_room_id'] = 1; // Your chat room id from database
            $this->app_model->insertChatRoom($insert_room);

            $result['status'] = 'success';
            $result['user_id'] = $get_user->user_id;
            $result['username'] = $get_user->username;
            $result['email'] = $get_user->email;
        } else {
            $result['status']  = 'failed';
            $result['message'] = '<div class="error_msg">Invalid username / password</div>';
        }
        echo json_encode($result);
    }

}
