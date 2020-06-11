<?php

/*
 * Created by :
 * Rizky Suslianto | rizky@zalora.com.my
 * Copyright © 2014 Zalora IT Malaysia
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function calculate_tbsnews_date($issue) {

    $first_issue  = strtotime('1995-02-16');
    $one_week_sec = 604800;
    $limit_check  = 7;

    return $first_issue + $one_week_sec * $issue;
}

function alert_danger($msg){
	return '<div class="alert alert-danger" role="alert">'.$msg.'</div>';
}

function alert_success($msg){
	return '<div class="alert alert-success" role="alert">'.$msg.'</div>';	
}

function next5years(){
	$year = date('Y');
	$data = array();
	for($i=0;$i<=5;$i++)
		$data[$year + $i] = $year + $i;
	return $data;
}
function getMonth(){
	$data = array();
	$time = strtotime('2015-01-01');
	
	for($i=0;$i<=11;$i++){
		$month_loop = strtotime('+'.$i.' month',$time);
		$data[date('m',$month_loop)] = date('M',$month_loop);
	}
	return $data;
}
function load_chapter_list($session){
	
	$CI = get_instance();
    $CI->load->model('backend_model');

    $data_raw = $CI->backend_model->get_all_chapter_url(json_decode($session->userdata('chapter'),1));
    $data = array();
    foreach($data_raw as $v){
    	$data[$v['url_name']] = $v['name_chinese'];
    }

    $js = 'id="select_chapter" class="form-control"';

    $return = form_dropdown('chapter_load', $data, $session->userdata('chapter_used'), $js);
    return $return;
}

function load_view_data($session){
	$data = array(
		'chapter_dropdown' => load_chapter_list($session),
		'chapter_allowed'  => json_decode($session->userdata('chapter'),1),
		'google_name'      => $session->userdata('name'),
		'avatar'           => $session->userdata('avatar'),
		'google_email'     => $session->userdata('email'),
	);
	return $data;
}

function load_common_view_data($session){
	$data = array(
		'google_name'      => $session->userdata('name'),
		'avatar'           => $session->userdata('avatar'),
		'google_email'     => $session->userdata('email'),
	);
	return $data;
}

function check_return_empty($array,$col,$return=''){
	return (isset($array[$col])) ? $array[$col] : $return;
}

function print_pre($data) {
	echo '<pre>';
	print_r($data);
	echo '</pre>';
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */