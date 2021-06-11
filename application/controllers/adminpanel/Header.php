<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Header extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!$this->session->userdata("admin_session"))
			redirect(base_url().'adminpanel/signin');

	}

	public function getMenu()
	{
		$data['logo'] = $this->Database_Model->getContent('logo');
		$data['menu'] = $this->Database_Model->getContent('nav_menu');
		$data['numberMsg'] = $this->db->query('SELECT id FROM message')->num_rows();
		$data['numberUnMsg'] = $this->db->query('SELECT id FROM message WHERE status=1')->num_rows();
		$data['unreadMessage'] = $this->Database_Model->getContentId(0,'message','status');
		$this->load->view('admin/menu', $data);
	}

	public function getLogo()
	{
		$data['logo'] = $this->Database_Model->getContent('logo');
		$data['numberMsg'] = $this->db->query('SELECT id FROM message')->num_rows();
		$data['numberUnMsg'] = $this->db->query('SELECT id FROM message WHERE status=0')->num_rows();
		$data['unreadMessage'] = $this->Database_Model->getContentId(0,'message','status');
		$this->load->view('admin/logo', $data);
	}

	public function updateLogo($id)
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|webp|jpeg';
		$config['max_size'] = 2000;
		$config['max_width'] = 2048;
		$config['max_height'] = 2048;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('image')) {
			//	$error = $this->upload->display_errors();
			//	$this->session->set_flashdata("mesaj", "Yüklemede Hata Oluştu :" . $error);
			redirect(base_url() . 'adminpanel/header/getLogo/');
		} else {
			$upload_data = $this->upload->data();
			$data = array(
				'logo_name' => $upload_data["file_name"]
			);
			$this->Database_Model->updateWithId('logo', $data, 'id', $id);
			//$this->session->set_flashdata("sonuc", "Kaydetme İşlemi Başarı İle Gerçekleştirildi");
			redirect(base_url() . 'adminpanel/header/getLogo/');
		}
	}

	public function updateMenu($lang_id)
	{
		$data = array(
			'tab_1' => $this->input->post('tab_1'),
			'tab_2' => $this->input->post('tab_2'),
			'tab_3' => $this->input->post('tab_3'),
			'tab_4' => $this->input->post('tab_4'),
			'tab_5' => $this->input->post('tab_5'),
			'tab_6' => $this->input->post('tab_6')
		);
		$this->Database_Model->updateWithId('nav_menu', $data, 'lang', $lang_id);
		redirect(base_url() . 'adminpanel/header/getMenu');
	}
}
