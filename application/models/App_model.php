<?php

class App_Model extends CI_Model {
    
    public function checkUserName($username) {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $query_result = $this->db->get();
        $result       = $query_result->num_rows();
        return $result;
    }

    public function checkEmail($email) {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('email', $email);
        $query_result = $this->db->get();
        $result       = $query_result->num_rows();
        return $result;
    }

    public function save_user($data) {
        $this->db->insert('tbl_user', $data);
        $user_id = $this->db->insert_id();
        return $user_id;
    }

    public function insertChatRoom($insert_room) {
        $this->db->insert('tbl_chat_online', $insert_room);
    }

    public function outFromAllRoom($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('tbl_chat_online');
    }

    public function checkUser($username, $password) {
        $this->db->select('*');
        $this->db->from('tbl_user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        $query_result = $this->db->get();
        $count        = $query_result->num_rows();
        if ($count == 1) {
            $result = $query_result->row();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function getActiveUsers($chat_room_id) {
        $this->db->select('tbl_chat_online.*, tbl_user.user_id, tbl_user.username');
        $this->db->from('tbl_chat_online');
        $this->db->where('tbl_chat_online.chat_room_id', $chat_room_id);
        $this->db->order_by('tbl_chat_online.updated_at', 'desc');
        $this->db->join('tbl_user', 'tbl_chat_online.user_id=tbl_user.user_id', 'left');
        $qr = $this->db->get();
        $r  = $qr->result();
        return $r;
    }
    
    public function send_message($data) {
        $this->db->insert('tbl_chat', $data);
        $chat_id = $this->db->insert_id();
        return $chat_id;
    }
    
    public function getChatMessages($chat_room_id) {
        $this->db->select('tbl_chat.*, tbl_user.user_id, tbl_user.username');
        $this->db->from('tbl_chat');
        $this->db->where('tbl_chat.room_id', $chat_room_id);
        $this->db->order_by('tbl_chat.chat_id', 'desc');
        $this->db->join('tbl_user', 'tbl_chat.user_id=tbl_user.user_id', 'left');
        $this->db->limit(4);
        $qr = $this->db->get();
        $r  = $qr->result();
        return $r;
    }
    
    public function getPreviousMessages($chat_room_id, $chat_id) {
        $this->db->select('tbl_chat.*, tbl_user.user_id, tbl_user.username');
        $this->db->from('tbl_chat');
        $this->db->where('tbl_chat.room_id', $chat_room_id);
        $this->db->where('tbl_chat.chat_id < ', $chat_id);
        $this->db->order_by('tbl_chat.chat_id', 'desc');
        $this->db->join('tbl_user', 'tbl_chat.user_id=tbl_user.user_id', 'left');
        $this->db->limit(4);
        $qr = $this->db->get();
        $r  = $qr->result();
        return $r;
    }


}
