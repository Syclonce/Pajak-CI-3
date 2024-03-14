<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Transaksi_model', 'transaksi');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->db->select_sum('ppn', 'total_ppn');
        $query = $this->db->get('data_transaksi');
        $result = $query->row();
        $data['ppn'] = $result->total_ppn;


        $this->db->select_sum('harga', 'total_harga');
        $query1 = $this->db->get('data_transaksi');
        $result1 = $query1->row();
        $data['harga'] = $result1->total_harga;

        $this->load->model('User_model'); // Pastikan model telah dimuat

        $data['total_users'] = $this->User_model->countUsers(); // Mengambil jumlah pengguna dari model

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/footer');
    }


    public function role()
    {
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get('user_role')->result_array();

        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/role', $data);
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">New Role added!</div>');
            redirect('admin/role');
        }
    }


    public function roleAccess($role_id)
    {
        $data['title'] = 'Role Access';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/role-access', $data);
        $this->load->view('templates/footer');
    }


    public function changeAccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Access Changed!</div>');
    }

    public function deleterole($id)
    {
        $this->db->delete('user_role', ['id' => $id]);
        $this->db->delete('user_access_menu', ['role_id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">The menu has ben deleted!</div>');
        redirect('admin/role');
    }

    public function user()
    {
        $data['title'] = 'user Managemen';

        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->db->select('user.*, user_role.role');
        $this->db->from('user');
        $this->db->join('user_role', 'user.role_id = user_role.id', 'inner');
        $this->db->where('user.role_id !=', 1);
        $query = $this->db->get();

        $data['users'] = $query->result_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar', $data);
        $this->load->view('templates/topbar', $data);
        $this->load->view('admin/user-namagen', $data);
        $this->load->view('templates/footer');
    }

    public function toggleStatus($id)
    {
        // Ambil data pengguna berdasarkan ID
        $user = $this->db->get_where('user', ['id' => $id])->row();

        if ($user) {
            // Periksa status is_active saat ini
            if ($user->is_active == 1) {
                // Jika saat ini aktif (1), maka ubah menjadi non-aktif (0)
                $new_status = 0;
            } else {
                // Jika saat ini non-aktif (0), maka ubah menjadi aktif (1)
                $new_status = 1;
            }

            // Update status is_active dalam database
            $this->db->where('id', $id);
            $this->db->update('user', ['is_active' => $new_status]);

            // Redirect atau lakukan tindakan lain setelah pembaruan
            redirect('admin/user');
        } else {
            // Pengguna dengan ID tertentu tidak ditemukan
            show_404();
        }
    }

    public function userdel($id)
    {
        // Periksa apakah ID pengguna valid
        $user = $this->db->get_where('user', ['id' => $id])->row();

        if ($user) {
            // Hapus pengguna dari database
            $this->db->delete('user', ['id' => $id]);

            // Redirect atau lakukan tindakan lain setelah penghapusan
            redirect('admin/user');
        } else {
            // Pengguna dengan ID tertentu tidak ditemukan
            show_404();
        }
    }

    public function add_user()
    {
        // Pastikan Anda memiliki autentikasi atau otorisasi untuk mengakses halaman ini

        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'This email has already registered!'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'Password dont match!',
            'min_length' => 'Password too short!'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');

        if ($this->form_validation->run() == false) {
            redirect('admin/users');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'default.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => $this->input->post('role'), // Sesuaikan dengan role yang sesuai dengan admin panel Anda
                'is_active' => 1, // Sesuaikan sesuai kebutuhan, 1 mungkin berarti akun sudah aktif
                'date_created' => time()
            ];


            $this->db->insert('user', $data);

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">User has been added successfully!</div>');
            redirect('admin/users'); // Sesuaikan dengan halaman yang sesuai di admin panel Anda
        }
    }



    public function edit()
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/edit', $data);
            $this->load->view('templates/footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');

            // cek jika ada gambar yang akan diupload
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']      = '2048';
                $config['upload_path'] = './assets/img/profile/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . 'assets/img/profile/' . $old_image);
                    }
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    $this->upload->dispay_errors();
                }
            }

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Your profile has been updated!</div>');
            redirect('admin');
        }
    }


    public function changePassword()
    {
        $data['title'] = 'Change Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Current Password', 'required|trim');
        $this->form_validation->set_rules('new_password1', 'New Password', 'required|trim|min_length[3]|matches[new_password2]');
        $this->form_validation->set_rules('new_password2', 'Confirm New Password', 'required|trim|min_length[3]|matches[new_password1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/changepassword', $data);
            $this->load->view('templates/footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');
            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Wrong current password!</div>');
                redirect('admin/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">New password cannot be the same as current password!</div>');
                    redirect('admin/changepassword');
                } else {
                    // password sudah ok
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password changed!</div>');
                    redirect('admin/changepassword');
                }
            }
        }
    }

    public function adminadd($id)
    {
        // Ambil data pengguna berdasarkan ID
        $user = $this->db->get_where('user', ['id' => $id])->row();

        if ($user) {
            // Periksa status role_id saat ini
            if ($user->role_id == 2) {
                // Jika saat ini aktif (1), maka ubah menjadi non-aktif (0)
                $new_status = 1;
            } else {
                // Jika saat ini non-aktif (0), maka ubah menjadi aktif (1)
                $new_status = 2;
            }

            // Update status role_id dalam database
            $this->db->where('id', $id);
            $this->db->update('user', ['role_id' => $new_status]);

            // Redirect atau lakukan tindakan lain setelah pembaruan
            redirect('admin/user');
        } else {
            // Pengguna dengan ID tertentu tidak ditemukan
            show_404();
        }
    }
}
