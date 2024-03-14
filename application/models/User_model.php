<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function countUsers()
    {
        return $this->db->count_all('user');
    }
}
