<?php
defined('BASEPATH') or exit('No direct script access allowed');



class PPN extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Transaksi_model', 'transaksi');
        $this->load->model('Ppn_model', 'ppns');
    }

    public function index()
    {

        $data['title'] = "Rekap Data PPN";

        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $search = $this->input->Get('search');
        $tanggal_mulai = $this->input->Get('tanggal_mulai');
        $tanggal_selesai = $this->input->Get('tanggal_selesai');

        if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {


            // Konversi tanggal string ke objek DateTime
            $startDate = new DateTime($tanggal_mulai);
            $endDate = new DateTime($tanggal_selesai);

            $formattedStartDate = $startDate->format('Y-m-d');
            $formattedEndDate = $endDate->format('Y-m-d');
            $query = "SELECT * FROM data_transaksi WHERE data_transaksi.tanggal_pembelian BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
            $queryResult = $this->db->query($query)->result();
        }

        if (!empty($search)) {


            $searchResult = $this->transaksi->cariDataPPN($search);
        }
        // Eksekusi query


        // Gabungkan hasil pencarian dengan hasil query tanggal jika keduanya ada
        if (!empty($search) && !empty($queryResult)) {
            $data['ppn'] = array_merge($searchResult, $queryResult);
        } elseif (!empty($search)) {
            $data['ppn'] = $searchResult;
        } elseif (!empty($queryResult)) {
            $data['ppn'] = $queryResult;
        } else {
            // Tampilkan pesan jika tidak ada hasil pencarian atau hasil query tanggal
            $data['ppn'] = array();
        }
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('ppn/index', $data);
        $this->load->view('templates/footer');
    }
    public function cetakLaporanscr()
    {
        $data['title'] = "Cetak Laporan PPN";

        $search = $this->input->Get('search');
        $bulan = date('m');

        if (!empty($search)) {
            // Jika terdapat parameter pencarian
            $query = "SELECT * FROM data_transaksi WHERE data_transaksi.no_faktur = '$search'";
            $data['catakLaporan'] = $this->db->query($query)->result();
        } else {
            $data['catakLaporan'] = "null";
        }



        $this->load->helper('dompdf_helper');

        // Contoh tampilan HTML yang ingin Anda konversi ke PDF
        $html = $this->load->view('pdf_template_scr', $data, true);

        // Panggil fungsi generate_pdf() dari helper Dompdf
        $pdf_content = generate_pdf($html, 'laporan.pdf');
        $this->output->set_output($pdf_content);
    }



    public function cetakLaporan()
    {
        $data['title'] = "Cetak Laporan PPN";


        $tanggal_mulai = $this->input->Get('tanggal_mulai');
        $tanggal_selesai = $this->input->Get('tanggal_selesai');
        $bulan = date('m');

        if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {


            // Konversi tanggal string ke objek DateTime
            $startDate = new DateTime($tanggal_mulai);
            $endDate = new DateTime($tanggal_selesai);

            $formattedStartDate = $startDate->format('Y-m-d');
            $formattedEndDate = $endDate->format('Y-m-d');
            $query = "SELECT * FROM data_transaksi WHERE data_transaksi.tanggal_pembelian BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
            $data['catakLaporan'] = $this->db->query($query)->result();
        } else {
            $data['catakLaporan'] = "null";
        }


        $this->load->helper('dompdf_helper');

        // Contoh tampilan HTML yang ingin Anda konversi ke PDF
        $html = $this->load->view('pdf_template', $data, true);

        // Panggil fungsi generate_pdf() dari helper Dompdf
        $pdf_content = generate_pdf($html, 'laporan' . '-' . $bulan . '.pdf');
        $this->output->set_output($pdf_content);
    }

    public function datalis()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = "Data PPN";
        $data['transaksi'] = $this->transaksi->get_data('data_transaksi')->result();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('ppn/Transaksi.php', $data);
        $this->load->view('templates/footer');
    }

    public function tambahData()
    {
        $data['title'] = "Add Data PPN";
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('ppn/add.php', $data);
        $this->load->view('templates/footer');
    }


    public function no_faktur_exists($no_faktur)
    {
        // Panggil model atau lakukan query database untuk memeriksa keberadaan no_faktur
        if ($this->transaksi->checkNoFakturExists($no_faktur)) {
            return FALSE; // Jika sudah ada, kembalikan FALSE
        } else {
            return TRUE; // Jika belum ada, kembalikan TRUE
        }
    }
    public function uppercase_check($str)
    {
        // Pemeriksaan apakah ada huruf kapital dalam string
        if (preg_match('/[A-Z]/', $str)) {
            return TRUE;
        } else {
            if (preg_match('/^[0-9]+$/', $str)) {
                return TRUE;
            } else {
                $this->form_validation->set_message('uppercase_check', 'Input harus berisi huruf kapital.');
                return FALSE;
            }
            return FALSE;
        }
    }

    public function tambah()
    {

        $this->form_validation->set_rules('no_faktur', 'no Faktur', 'required|callback_no_faktur_exists|callback_uppercase_check');
        $this->form_validation->set_rules('tanggal_pembelian', 'tanggal pembelian', 'required');
        $this->form_validation->set_rules('supplier', 'supplier', 'required');
        $this->form_validation->set_rules('nama_barang', 'nama barang', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');

        // Memeriksa apakah no_faktur sudah ada di database
        if ($this->transaksi->checkNoFakturExists($this->input->post('no_faktur'))) {
            // Jika sudah ada, set pesan kesalahan validasi
            $this->form_validation->set_message('no_faktur_exists', 'No Faktur ini sudah ada.');
            $this->session->set_flashdata('no_faktur_exists', '<div class="alert alert-success" role="alert">No Faktur ini sudah ada.</div>');
            $valid = FALSE; // Atau gunakan variabel validasi sesuai kebutuhan Anda
        } else {
            $valid = TRUE; // Jika no_faktur belum ada di database, data valid
        }
        $settings = $this->ppns->getPpn();

        $datappn = $settings[0]['ppn'];
        $datadpp = $settings[0]['dpp'];



        if ($this->form_validation->run() == FALSE) {
            $this->tambahData();
        } else {
            $no_faktur = $this->input->post('no_faktur');
            $tanggal_pembelian = $this->input->post('tanggal_pembelian');
            $supplier      = $this->input->post('supplier');
            $nama_barang = $this->input->post('nama_barang');
            $harga = $this->input->post('harga');

            if ($harga < 2000000) {
                $ppn = 0;
            } else {
                $ppn = $harga * 100 / $datadpp * $datappn;
            }

            $data = array(

                'no_faktur'          => $no_faktur,
                'tanggal_pembelian'  => $tanggal_pembelian,
                'supplier'           => $supplier,
                'nama_barang'        => $nama_barang,
                'harga'              => $harga,
                'ppn'                => $ppn,
                't_ppn'              => $datappn,
            );

            $this->transaksi->insert_data($data, 'data_transaksi');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        	  Data berhasil ditambahkan!
        	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        	    <span aria-hidden="true">&times;</span>
        	  </button>
        	</div>');
            redirect('ppn/datalis');
        }
    }

    public function settings()
    {
        $data['title'] = "Edit PPN";
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['ppn'] = $this->ppns->getPpn();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('ppn/edit.php', $data);
        $this->load->view('templates/footer');
    }

    public function check_ppn_limit($ppn)
    {
        $ppn_decimal = floatval(str_replace('%', '', $ppn)) / 100;
        if ($ppn_decimal > 0.20) { // Jika PPN lebih dari 20%
            $this->form_validation->set_message('message', 'Nilai {field} tidak boleh lebih dari 20%.');
            return false;
        }
        return true;
    }

    public function updateSettings()
    {

        // Validasi form input (sesuaikan dengan aturan validasi yang dibutuhkan)
        $this->form_validation->set_rules('ppn', 'PPN', 'required|numeric|callback_check_ppn_limit');

        $this->form_validation->set_rules('dpp', 'DPP', 'required|numeric');


        if ($this->form_validation->run() == false) {
            // Validasi gagal, tampilkan kembali halaman edit dengan pesan error
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data PPN Gagal di Ubah!</div>');
            redirect('PPN/settings');
        } else {
            // Validasi sukses, ambil data dari form
            $ppn = $this->input->post('ppn', true);
            $dpp = $this->input->post('dpp', true);
            $id = 1; // Ganti dengan id yang sesuai

            $ppn_decimal = floatval(str_replace('%', '', $ppn)) / 100;


            $data = array(
                'ppn' => $ppn_decimal, // Ganti dengan nilai PPN yang sesuai
                'dpp' => $dpp, // Ganti dengan nilai PPN yang sesuai
                'id' => $id // Ganti dengan nilai PPN yang sesuai
            );


            // Simpan data yang diedit ke database
            $this->ppns->updatePpn($id, $data);

            // Set pesan sukses dan redirect ke halaman setingppn
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data PPN berhasil diubah!</div>');
            redirect('PPN/settings');
        }
    }
}
