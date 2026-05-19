<?php
class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('user', 'english');
		$this->load->helper(array('language','form','url','common_helper'));
		$this->load->library(array('session'));
		$this->load->model('backend_model');
		$this->list_column = array();

		if(!$this->session->userdata('access_token') || !$this->session->userdata('email')) redirect('admin/login','refresh');

		// Drop Down Box For Chapter
		$this->data = load_view_data($this->session);
		$this->url_name = $this->session->userdata('chapter_used');
	}

	public function index() {
		$this->list_column = array("email","chapter","last_login","activity","action");

        if ($this->input->is_ajax_request()) {
            $this->get_data_all_user();
        } else {
        	$data = $this->data;
        	$data['total_data']  = $this->backend_model->count_total_user();
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/user_index_view',$data);
			$this->load->view('admin/footer');
        }
    }

	private function get_data_all_user(){
		$data = array();
		$filter_data = $this->get_filter_data(array(),'last_login');

		$users = $this->backend_model->get_all_user($filter_data);
		foreach($users as $k => $v){
			$chap = json_decode($v['chapter'],1);
			$users[$k]['chapter'] = join(',',$chap);

			$res = $this->backend_model->get_latest_activity($v['user_id']);

			$data[] = array(
				'email' => '<a href="'.base_url('admin/user/activity/'.$v['user_id']).'">'.$v['email'] .'</a>',
				'chapter' => join(',',$chap),
				'last_login' => $v['last_login'],
				'activity' => (sizeof($res) > 0) ? $res[0]['create_date'] . ': '. $res[0]['activity'] : '-N/A-',
				'action' => '<a href="'.base_url('admin/user/edit/'.$v['user_id']).'" class="btn btn-xs btn-warning" style="margin-right: 5px;"><i class="fa-solid fa-pen-to-square"></i> 編輯</a>' .
				            '<a href="'.base_url('admin/user/delete/'.$v['user_id']).'" class="btn btn-xs btn-danger" onclick="return confirm(\'確定要刪除此用戶嗎？此操作無法撤銷。\')"><i class="fa-solid fa-trash"></i> 刪除</a>',
			);
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->backend_model->count_total_user(),
            'iTotalRecords' => $this->backend_model->count_total_user(),
            'data' => $data,
        );
        echo json_encode($result);
	}

	public function activity($user_id){
		$this->list_column = array("activity","create_date");

        if ($this->input->is_ajax_request()) {
            $this->get_data_user_activity();
        } else {
        	$data = $this->data;
        	$data['total_data']  = $this->backend_model->count_total_activity($user_id);
        	$data['list_column'] = join("\" },{ \"data\": \"",$this->list_column);

        	$user = $this->backend_model->get_user_details($user_id);
			$data['user_email'] = $user[0]['email'];
			$data['user_id'] = $user_id;

            $this->load->view('admin/header', $data);
			$this->load->view('admin/navigation', $data);
			$this->load->view('admin/user_activity_view',$data);
			$this->load->view('admin/footer');
        }
	}

	private function get_data_user_activity(){
		$data = array();
		$filter_data = $this->get_filter_data(array('user_id'),'create_date DESC');

		$activity = $this->backend_model->get_user_activity($filter_data);
		foreach($activity as $v){
			$data[] = array(
				'activity'    => $v['activity'],
				'create_date' => $v['create_date'],
			);
		}

		$result = array(
            'draw' => $this->input->post('draw'),
            'recordsTotal' => $filter_data['l2'],
            'recordsFiltered' => $this->backend_model->count_total_activity($filter_data['user_id']),
            'iTotalRecords' => $this->backend_model->count_total_activity($filter_data['user_id']),
            'data' => $data,
        );
        echo json_encode($result);
	}


	private function get_filter_data($filter_list=array(), $default_order='') {
        $result = array();

        foreach($filter_list as $name){
            if ($this->input->post($name) != '') {
                $result[$name] = $this->input->post($name);
            }
        }
        
        // Query Limit Default Value
        $result['l1'] = ($this->input->post('start')) ? $this->input->post('start') : '0';
        $result['l2'] = ($this->input->post('length')) ? $this->input->post('length') : '10';

        // Default Ordering
        $order = $this->input->post('order');
        $result['order_by'] = (isset($order[0])) ? $this->list_column[$order[0]['column']] . ' ' . $order[0]['dir'] : $default_order;

        return $result;
    }

	public function add() {
		// Only "all" (Super Admin) can manage users
		$allowed = json_decode($this->session->userdata('chapter'), 1);
		if ($allowed[0] != 'all') {
			redirect('admin/index', 'refresh');
		}

		$data = $this->data;
		$error = '';

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$email = trim($this->input->post('email'));
			$all_chapters = $this->input->post('all_chapters');
			$selected_chapters = $this->input->post('chapters');

			// Validation
			if (empty($email)) {
				$error = lang('msg_invalid_email');
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = lang('msg_invalid_email');
			} else {
				// Check unique email
				$existing = $this->backend_model->check_login($email);
				if (!empty($existing)) {
					$error = lang('msg_email_exists');
				}
			}

			// Validate chapters selection
			if (empty($error)) {
				if ($all_chapters == '1') {
					$chapters_json = json_encode(array('all'));
				} elseif (!empty($selected_chapters) && is_array($selected_chapters)) {
					$chapters_json = json_encode($selected_chapters);
				} else {
					$error = lang('msg_please_select_chapter');
				}
			}

			if (empty($error)) {
				// Insert User
				$new_user = array(
					'email' => $email,
					'chapter' => $chapters_json,
					'last_login' => '0000-00-00 00:00:00'
				);
				$this->backend_model->insert_user($new_user);

				// Log activity
				$current_user_id = $this->session->userdata('user_id');
				$this->backend_model->log_activity($current_user_id, 'Add API user: ' . $email . ' with chapters: ' . $chapters_json);

				// Set flash message
				$this->session->set_flashdata('message', lang('msg_add_success'));
				redirect('admin/user/index', 'refresh');
			}

			// Pass posted values back to view on error
			$data['email'] = $email;
			$data['all_chapters'] = ($all_chapters == '1');
			$data['selected_chapters'] = is_array($selected_chapters) ? $selected_chapters : array();
		}

		// Load chapters list for checkboxes
		$data['chapters'] = $this->backend_model->get_all_chapter_url(array('all'));
		$data['error'] = $error;

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/user_add_view', $data);
		$this->load->view('admin/footer');
	}

	public function edit($user_id) {
		// Only "all" (Super Admin) can manage users
		$allowed = json_decode($this->session->userdata('chapter'), 1);
		if ($allowed[0] != 'all') {
			redirect('admin/index', 'refresh');
		}

		$data = $this->data;
		$error = '';

		// Get user details
		$user = $this->backend_model->get_user_details($user_id);
		if (empty($user)) {
			redirect('admin/user/index', 'refresh');
		}
		$user = $user[0];

		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$email = trim($this->input->post('email'));
			$all_chapters = $this->input->post('all_chapters');
			$selected_chapters = $this->input->post('chapters');

			// Validation
			if (empty($email)) {
				$error = lang('msg_invalid_email');
			} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error = lang('msg_invalid_email');
			} else {
				// Check unique email (ignoring current user_id)
				$this->db = $this->load->database('local', TRUE);
				$query = $this->db->query("SELECT * FROM tbs_api_user WHERE email = ? AND user_id <> ?", array($email, $user_id));
				if ($query->num_rows() > 0) {
					$error = lang('msg_email_exists');
				}
			}

			// Validate chapters selection
			if (empty($error)) {
				if ($all_chapters == '1') {
					$chapters_json = json_encode(array('all'));
				} elseif (!empty($selected_chapters) && is_array($selected_chapters)) {
					$chapters_json = json_encode($selected_chapters);
				} else {
					$error = lang('msg_please_select_chapter');
				}
			}

			if (empty($error)) {
				// Update User
				$update_data = array(
					'email' => $email,
					'chapter' => $chapters_json
				);
				$this->backend_model->update_user($user_id, $update_data);

				// Log activity
				$current_user_id = $this->session->userdata('user_id');
				$this->backend_model->log_activity($current_user_id, 'Modify API user (ID ' . $user_id . '): ' . $email . ' with chapters: ' . $chapters_json);

				// Set flash message
				$this->session->set_flashdata('message', lang('msg_edit_success'));
				redirect('admin/user/index', 'refresh');
			}

			// Pass posted values back to view on error
			$user['email'] = $email;
			$user['chapter'] = $chapters_json;
		}

		// Load chapters list for checkboxes
		$data['chapters'] = $this->backend_model->get_all_chapter_url(array('all'));
		$data['error'] = $error;
		
		// Parse existing selected chapters
		$parsed_chapters = json_decode($user['chapter'], 1);
		$data['user'] = $user;
		$data['all_chapters'] = (isset($parsed_chapters[0]) && $parsed_chapters[0] == 'all');
		$data['selected_chapters'] = is_array($parsed_chapters) ? $parsed_chapters : array();

		$this->load->view('admin/header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/user_edit_view', $data);
		$this->load->view('admin/footer');
	}

	public function delete($user_id) {
		// Only "all" (Super Admin) can manage/delete users
		$allowed = json_decode($this->session->userdata('chapter'), 1);
		if ($allowed[0] != 'all') {
			redirect('admin/index', 'refresh');
		}

		// Prevent deleting themselves
		$current_user_id = $this->session->userdata('user_id');
		if ($user_id == $current_user_id) {
			$this->session->set_flashdata('error_message', '您不能刪除您自己的帳號！');
			redirect('admin/user/index', 'refresh');
		}

		$user = $this->backend_model->get_user_details($user_id);
		if (!empty($user)) {
			$email = $user[0]['email'];
			$this->backend_model->delete_user($user_id);

			// Log activity
			$this->backend_model->log_activity($current_user_id, 'Delete API user (ID ' . $user_id . '): ' . $email);
			$this->session->set_flashdata('message', lang('msg_delete_success'));
		}

		redirect('admin/user/index', 'refresh');
	}
}

?>