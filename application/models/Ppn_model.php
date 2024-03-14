<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ppn_model extends CI_Model
{
    public function getPpn()
    {
        return $this->db->get('set_ppn')->result_array();
    }

    public function updatePpn($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('set_ppn', $data);
    }
}
