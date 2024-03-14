<?php
class seting_model extends CI_Model
{


    public function getNilaipph()
    {
        // Sesuaikan query dan kolom dengan struktur database Anda
        $query = $this->db->query("SELECT * FROM seting_pajak WHERE id = '2';");

        // Ambil nilai dari hasil query
        $row = $query->row();

        // Jika data ditemukan, kembalikan nilainya, jika tidak, kembalikan default
        return ($row) ? (float)$row->angka : 0.0;
    }

    public function getNilaipph22()
    {
        // Sesuaikan query dan kolom dengan struktur database Anda
        $query = $this->db->query("SELECT * FROM seting_pajak WHERE id = '3';");

        // Ambil nilai dari hasil query
        $row = $query->row();

        // Jika data ditemukan, kembalikan nilainya, jika tidak, kembalikan default
        return ($row) ? (float)$row->angka : 0.0;
    }

    public function updateDatas($id, $pph)
    {
        // Sesuaikan query dengan struktur database Anda
        $data = array(
            'angka' => $pph
        );

        $this->db->where('id', $id); // Sesuaikan kondisi WHERE dengan kebutuhan Anda
        $this->db->update('seting_pajak', $data);
    }
}
