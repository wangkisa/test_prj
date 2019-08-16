<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        
        $this->load->model('m_accounts');
        $this->load->helper(array('url', 'alert'));
        $this->load->library(array('session'));
    }

    public function index()
    {
       $this->load->view('login_form');
    }
   
    public function login(){

        $id = $this->input->post('id');
        $password = $this->input->post('password');

        $info = $this->m_accounts->m_get_user_info($id, $password);
                
        if(!empty($info)) {
            // 로그인 성공시 session 생성
            $session_data = array(    
                'user_id'  => $info['user_id'],
                'is_admin' => $info['is_admin']
            );
            // session 등록
            $this->session->set_userdata($session_data);  
            
            if ($info['is_admin'] == 1) {
                // 쿠폰 발행 페이지 이동
                redirect('/admin/publish_coupon_form');
            }
            else {
                // 쿠폰 사용 페이지 이동
                redirect('/admin/use_coupon_form');
            }
        }
        else {
            alert('회원정보가 없습니다.', '/');
        }
        
    }

    // 쿠폰 발행 페이지
    public function publish_coupon_form(){

        $is_admin = $this->session->userdata('is_admin');
        if ($is_admin != 1) {
            alert('어드민 회원이 아닙니다.', '/');   
        }

        $this->load->view('/admin/publish_coupon_form');
    }

    // 쿠폰 데이터 생성하는 함수
    private function get_coupon_data($prefix, $extractStrs, $userList) {
        $str = '';
        $couponCode = '';
        $accountsId = NULL;
        
        $isUsed = 0;
        $usedDatetime = NULL;
        // 추출한 문자열로부터 랜덤하게 한자리씩 추출해서 $str에 저장
        for ($i=0; $i < 13; $i++) {
            $index = rand() % strlen($extractStrs);
            $str .= substr($extractStrs, $index, 1);
        }
        
        // prefix 문자열 $str변수 앞에 더하기
        $sumStings = $prefix . $str;
        $splitStrs = str_split($sumStings, 4);
        
        // 4자리씩 만든 배열로 쿠폰코드 포맷 맞추기
        foreach ($splitStrs as $key => $value) {
            if ($key != 3) {
                $couponCode .= $value .= '-';    
            }
            else {
                $couponCode .= $value;   
            }
            
        }
        // 랜덤으로 임의로 한 유저 뽑기위한 랜덤값 생성
        $randNum = mt_rand(1, 1000);
        
        if ($randNum < 100) {
            $userIndex = rand() % count($userList);
            
            $accountsId = $userList[$userIndex]['id'];
            $usedDatetime = get_current_datetime();
            $isUsed = 1;    
        }

        return array(
            'couponCode' => $couponCode,
            'accountsId' => $accountsId,
            'isUsed' => $isUsed,
            'usedDatetime' => $usedDatetime,
        );
    }

    // 쿠폰 발행하는 함수
    public function publish_coupon() {

        $is_admin = $this->session->userdata('is_admin');
        if ($is_admin != 1) {
            alert('어드민 회원이 아닙니다.', '/admin/');   
        }

        $extractStrs = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $prefix = $this->input->post('prefix');

        if (strlen($prefix) != 3) {
            alert('prefix를 올바르게 입력해주세요.', '/admin/publish_coupon_form');   
        }

        $groupId = $this->m_accounts->m_insert_group_data($prefix);
        $userList = $this->m_accounts->m_get_user_list();
        
        // 최대 스크립트 실행시간 늘려주기
        ini_set('max_execution_time', 10000);

        // 쿠폰 코드 생성 시작
        // 10만건 생성
        for ($j=0; $j < 100000; $j++) {
            
            $couponInfo = $this->get_coupon_data($prefix, $extractStrs, $userList);

            $result = $this->m_accounts->m_insert_coupon_data($couponInfo['couponCode'], $couponInfo['accountsId'], $groupId, $couponInfo['isUsed'], $couponInfo['usedDatetime']);
            
            if ($result == false) {
                $this->m_accounts->m_insert_coupon_data($couponInfo['couponCode'], $couponInfo['accountsId'], $groupId, $couponInfo['isUsed'], $couponInfo['usedDatetime']);
            }
        }

        alert('쿠폰이 발행되었습니다.', '/admin/coupon_list');
    }

    // 쿠폰 목록
    public function coupon_list() {

        $is_admin = $this->session->userdata('is_admin');
        if ($is_admin != 1) {
            alert('어드민 회원이 아닙니다.', '/');   
        }

        $offset = $this->input->get('per_page');
        $searchGroup = $this->input->get('search_group');
        
        if ($offset === NULL){
            $offset = 0;
        }
        $perPage = 100;

        $searchString = NULL;
        if ($searchGroup !== NULL){
            $searchString .= "&search_group={$searchGroup}";
        }

        $couponCount = $this->m_accounts->m_count_coupon($searchGroup);
        $couponList = $this->m_accounts->m_get_coupon_list($perPage, $offset, $searchGroup);
        $groupList = $this->m_accounts->m_get_group_list();

        $data['pagination'] = get_pagination_data($couponCount, $perPage, current_url(), $searchString);
        $data['couponList'] = $couponList;
        $data['groupList'] = $groupList;
        $data['searchGroup'] = $searchGroup;
        
        $this->load->view('/admin/coupon_list', $data);
    }

    // 쿠폰 사용 페이지(쿠폰 유저, 그룹별 사용 내용도 같이 포함)
    public function use_coupon_form(){
        $userId = $this->session->userdata('user_id');
        if ($userId == NULL) {
            alert('로그인한 회원만 사용가능합니다.', '/');   
        }
        // 쿠폰 유저, 그룹별 사용 카운트 가져오기
        $groupCountList = $this->m_accounts->m_count_coupon_group();
        
        $data['groupCountList'] = $groupCountList;

        $this->load->view('/user/use_coupon_form', $data);
    }

    // 쿠폰 사용유무 체크
    public function check_coupon(){
        $userId = $this->session->userdata('user_id');
        if ($userId == NULL) {
            alert('로그인한 회원만 사용가능합니다.', '/');   
        }

        $couponCode = $this->input->post('coupon_code');
        if (strlen($couponCode) != 19) {
            alert('쿠폰 코드를 올바르게 입력해주세요.', '/admin/use_coupon_form');   
        }
        $isCheckedCoupon = $this->m_accounts->m_check_used_coupon($couponCode);
        if ($isCheckedCoupon) {
            alert('사용 불가능한 쿠폰입니다.');
        }
        else {
            alert('사용 가능한 쿠폰입니다.');   
        }
    }

}