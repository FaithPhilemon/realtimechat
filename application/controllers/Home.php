<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('user_id')) {
            redirect('login');
        }
        $this->load->model('app_model');
    }

    public function previous_msg() {
        $chat_room_id = 1;
        $chat_id      = $this->input->post('chat_id');
        $messages     = array_reverse($this->app_model->getPreviousMessages($chat_room_id, $chat_id));
        $response['output']       = null;
        if (!empty($messages)) {
            foreach ($messages as $m) {
                $right    = '';
                $chat_img = '';
                if ($m->user_id == $this->session->userdata('user_id')) {
                    $right = 'right';
                }
                if ($m->chat_image != '') {
                    $chat_img = '<span class="chat_img"><img src="' . base_url() . $m->chat_image . '" /></span>';
                }
                $response['output'] .= '<div data-chat_id="' . $m->chat_id . '" class="direct-chat-msg ' . $right . '">' .
                        '<div class="direct-chat-info clearfix">' .
                        '<span class="direct-chat-name pull-left">' . $m->username . '</span>' .
                        '<span class="direct-chat-timestamp pull-right">' . return_datetime($m->created_at) . '</span>' .
                        '</div>' .
                        '<img class="direct-chat-img" src="'.base_url().'media/images/user/user_1.jpg" alt="' . $m->username . '">' .
                        '<div class="direct-chat-text">' .
                        $m->chat_message . $chat_img .
                        '</div>' .
                        '</div>';
            }
            $response['status'] = 'success';
        } else {
            $response['status'] = 'not_found';
        }
        echo json_encode($response);
    }

    public function index() {
        $chat_room_id      = 1;
        $d['title']        = 'Public Chat';
        $d['messages']     = array_reverse($this->app_model->getChatMessages($chat_room_id));
        $d['active_users'] = $this->app_model->getActiveUsers($chat_room_id);
        $this->load->view('home', $d);
    }

    public function send_msg() {
        $room_id      = 1;
        $user_id      = $this->session->userdata('user_id');
        $path         = 'media/images/chat/';
        $chat_message = $this->input->post("message");
        $t            = date('Y-m-d H:i:s');
        $chat_id      = '';

        if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {

            $result = $this->fileUpload('image', $path);
            if ($result) {
                if ($result['file_name']) {
                    $data['chat_image']     = $img                    = $result['file_name'];
                    $data['chat_message']   = $chat_message;
                    $data['user_id']        = $user_id;
                    $data['created_at']     = $t;
                    $data['room_id']        = $room_id;
                    $chat_id                = $this->app_model->send_message($data);
                    $response['chat_image'] = base_url() . $img;
                    $response['status']     = 'success';
                } else {
                    $response['status'] = 'path_error';
                }
            } else {
                $response['status'] = 'size_error';
            }
        } else {
            $data                   = array();
            $data['chat_message']   = $chat_message;
            $data['user_id']        = $user_id;
            $data['created_at']     = $t;
            $data['room_id']        = $room_id;
            $chat_id                = $this->app_model->send_message($data);
            $response['chat_image'] = '';
            $response['status']     = 'success';
        }

        $response['username']     = $this->session->userdata('username');
        $response['chat_message'] = $chat_message;
        $response['created_at']   = return_datetime($t);
        $response['chat_id']      = $chat_id;
        $response['user_id']      = $user_id;

        echo json_encode($response);
    }

    public function fileUpload($file, $path) {
        $CI                      = & get_instance();
        $config['upload_path']   = $path;
        $config['allowed_types'] = 'gif|png|jpeg|jpg';
        $config['max_size']      = '2048';
        $config['max_width']     = '6000';
        $config['max_height']    = '4000';

        $CI->load->library('upload', $config);
        if ($CI->upload->do_upload($file)) {
            $data     = $CI->upload->data();
            $fileName = $config['upload_path'] . $data['file_name'];
            $return   = array('file_name' => $fileName, 'error' => '');
            return $return;
        } else {
            $err    = $CI->upload->display_errors();
            $return = array('file_name' => '', 'error' => $err);
            return $return;
        }
    }

}
