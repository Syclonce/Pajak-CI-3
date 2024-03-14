<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transaksi_model_pph extends CI_Model
{
	public function get_data($table)
	{
		return $this->db->get($table);
	}

	public function insert_data($data, $table)
	{
		$this->db->insert($table, $data);
	}

	// Fungsi untuk memeriksa apakah no_faktur sudah ada di database
	public function checkNoFakturExists($no_faktur)
	{
		// Lakukan query ke database untuk memeriksa keberadaan no_faktur
		$this->db->where('no_faktur', $no_faktur);
		$query = $this->db->get('pph22'); // Gantilah 'nama_tabel_transaksi' dengan nama tabel transaksi Anda

		// Periksa apakah ada baris data dengan no_faktur yang sama
		if ($query->num_rows() > 0) {
			return TRUE; // Jika sudah ada, kembalikan TRUE
		} else {
			return FALSE; // Jika belum ada, kembalikan FALSE
		}
	}
	public function cariDataPPN($search)
	{
		// Cek apakah $search adalah null atau tidak
		if ($search === null) {
			// Jika $search adalah null, ambil semua data
			$query = $this->db->get('pph22'); // Gantilah 'nama_tabel' sesuai dengan tabel yang sesuai
		} else {
			// Jika $search tidak null, lanjutkan dengan pencarian
			$this->db->like('no_faktur', $search); // Gantilah 'nama_kolom' sesuai dengan kolom yang sesuai
			$query = $this->db->get('pph22'); // Gantilah 'nama_tabel' sesuai dengan tabel yang sesuai
		}

		return $query->result();
	}
}
