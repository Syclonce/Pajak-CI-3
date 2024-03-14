<?php
defined('BASEPATH') or exit('No direct script access allowed');



class pph22 extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Transaksi_model_pph', 'transaksi');
        $this->load->model('seting_model', 'set');
    }

    public function index()
    {

        $data['title'] = "Rekap Data PPH 22";

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
            $query = "SELECT * FROM pph22 WHERE pph22.tanggal_pembelian BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
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
        $this->load->view('pph22/index', $data);
        $this->load->view('templates/footer');
    }

    public function cetakLaporanscr()
    {
        $data['title'] = "Cetak Laporan PPN";

        $search = $this->input->Get('search');
        $bulan = date('m');

        if (!empty($search)) {
            // Jika terdapat parameter pencarian
            $query = "SELECT * FROM pph22 WHERE pph22.no_faktur = '$search'";
            $data['catakLaporan'] = $this->db->query($query)->result();
        } else {
            $data['catakLaporan'] = "null";
        }



        $this->load->helper('dompdf_helper');

        // Contoh tampilan HTML yang ingin Anda konversi ke PDF
        $html = $this->load->view('pdf_template_scr_pph', $data, true);

        // Panggil fungsi generate_pdf() dari helper Dompdf
        $pdf_content = generate_pdf($html, 'laporan.pdf');
        $this->output->set_output($pdf_content);
    }



    public function cetakLaporan()
    {
        $data['title'] = "Cetak Laporan PPH22";


        $tanggal_mulai = $this->input->Get('tanggal_mulai');
        $tanggal_selesai = $this->input->Get('tanggal_selesai');
        $bulan = date('m');

        if (!empty($tanggal_mulai) && !empty($tanggal_selesai)) {


            // Konversi tanggal string ke objek DateTime
            $startDate = new DateTime($tanggal_mulai);
            $endDate = new DateTime($tanggal_selesai);

            $formattedStartDate = $startDate->format('Y-m-d');
            $formattedEndDate = $endDate->format('Y-m-d');
            $query = "SELECT * FROM pph22 WHERE pph22.tanggal_pembelian BETWEEN '$formattedStartDate' AND '$formattedEndDate'";
            $data['catakLaporan'] = $this->db->query($query)->result();
        } else {
            $data['catakLaporan'] = "null";
        }


        $this->load->helper('dompdf_helper');

        // Contoh tampilan HTML yang ingin Anda konversi ke PDF
        $html = $this->load->view('pdf_template_pph', $data, true);

        // Panggil fungsi generate_pdf() dari helper Dompdf
        $pdf_content = generate_pdf($html, 'laporan' . '-' . $bulan . '.pdf');
        $this->output->set_output($pdf_content);
    }

    public function datalis()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = "Data Pajak";
        $data['transaksi'] = $this->transaksi->get_data('pph22')->result();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pph22/Transaksi.php', $data);
        $this->load->view('templates/footer');
    }

    public function tambahData()
    {
        $data['title'] = "Add Data Pajak";
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pph22/add.php', $data);
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

    public function tambah()
    {
        $this->form_validation->set_rules('no_faktur', 'no Faktur', 'required|callback_no_faktur_exists');
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

        $nilai_dari_databasepph = $this->set->getNilaipph();
        $nilai_dari_database = $this->set->getNilaipph22();

        if ($this->form_validation->run() == FALSE) {
            $this->tambahData();
        } else {
            $no_faktur = $this->input->post('no_faktur');
            $tanggal_pembelian = $this->input->post('tanggal_pembelian');
            $supplier      = $this->input->post('supplier');
            $nama_barang = $this->input->post('nama_barang');
            $harga = $this->input->post('harga');
            $npwp = $this->input->post('npwp');

            if ($harga < 2000000) {
                $pph = 0;
                $npwps = 0;
            } else {
                if ($npwp == 1) {
                    $pph = $harga * 100 / 110 * $nilai_dari_databasepph;
                    $npwps = 0;
                } else {
                    $pph = 0;
                    $npwps = $harga * 100 / 110 * $nilai_dari_database;
                }
            }

            $data = array(

                'no_faktur'          => $no_faktur,
                'tanggal_pembelian'  => $tanggal_pembelian,
                'supplier'           => $supplier,
                'nama_barang'        => $nama_barang,
                'harga'              => $harga,
                'pph'                => $pph,
                'npwp'               => $npwps,
            );

            $this->transaksi->insert_data($data, 'pph22');
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
			  Data berhasil ditambahkan!
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
			    <span aria-hidden="true">&times;</span>
			  </button>
			</div>');
            redirect('pph22/datalis');
        }
    }

    public function settings()
    {
        $data['title'] = "Edit PPH";
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        // Load model
        $this->load->model('seting_model');

        // Ambil nilai dari database
        $data['nilai_dari_databasepph'] = $this->set->getNilaipph();
        $data['nilai_dari_database'] = $this->set->getNilaipph22();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('pph22/edit.php', $data);
        $this->load->view('templates/footer');
    }

    public function updateSettings()
    {
        // Validasi form
        $this->form_validation->set_rules('pph', 'pph', 'required|numeric');
        $this->form_validation->set_rules('pph22', 'pph22', 'required|numeric');

        if ($this->form_validation->run() == false) {
            // Validasi gagal, tampilkan kembali halaman edit dengan pesan kesalahan
            $this->settings();
        } else {
            // Validasi berhasil, lakukan pembaruan data
            $pph = $this->input->post('pph');
            $pph22 = $this->input->post('pph22');

            $this->load->model('seting_model');
            // Panggil fungsi untuk melakukan pembaruan data
            $this->seting_model->updateDatas(2, $pph);
            $this->seting_model->updateDatas(3, $pph22);

            // Redirect ke halaman settings setelah pembaruan data
            redirect('pph22/settings');
        }
    }
}
