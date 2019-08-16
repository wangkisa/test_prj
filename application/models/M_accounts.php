<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_accounts extends CI_Model {

    public function __construct()
    {
        parent::__construct();

        $this->load->database();
    }

	public function m_get_user_info($id = NULL, $password = NULL)
	{
	    // $this->db->select('user_id', 'password', 'is_admin');
		$this->db->from('tbl_accounts');
		$this->db->where('user_id', $id);
		$this->db->where('password', $password);
		$query = $this->db->get();

		return $query->row_array();
	}

	public function m_get_user_list()
	{
	    
		$this->db->from('tbl_accounts');
		$this->db->where('is_admin', 0);

		$query = $this->db->get();

		return $query->result_array();
	}

	public function m_insert_group_data($prefix = NULL)
	{
		$insert_data = array(
            'prefix'   => $prefix
        );

		$this->db->insert('tbl_group', $insert_data);
		return $this->db->insert_id();
	}

	public function m_insert_coupon_data($coupon_code = NULL, $accounts_id= NULL, $group_id= NULL, $is_used=NULL, $used_datetime = NULL)
	{

		$this->db->from('tbl_coupon_code');
		$this->db->where('coupon_code', $coupon_code);
		$query = $this->db->get();		

		$result = $query->row_array();
		
		if(isset($result['id'])) {
			return false;
		}

		$insert_data = array(
            'coupon_code'   => $coupon_code,
            'accounts_id'   => $accounts_id,
            'group_id'   => $group_id,
            'is_used'   => $is_used,
            'used_datetime'   => $used_datetime,
        );

		$this->db->insert('tbl_coupon_code', $insert_data);
		if($this->db->affected_rows() > 0){
			return $this->db->insert_id();
		}
        else {
        	return false;
        }
        
	}

	public function m_count_coupon($search_group)
	{
		$this->db->from('tbl_coupon_code');
		if ($search_group !== NULL) {
        	$this->db->where('group_id', $search_group);
        }
        
        return $this->db->count_all_results();
	}

	public function m_get_coupon_list($per_page, $offset, $search_group)
    {
        $this->db->select('tcc.id, tcc.coupon_code, tcc.is_used, tcc.used_datetime, ta.user_id, tcc.group_id, tcc.created_datetime');
        $this->db->from('tbl_coupon_code AS tcc');
        if ($search_group !== NULL) {
        	$this->db->where('group_id', $search_group);
        }
        $this->db->join('tbl_accounts AS ta', 'tcc.accounts_id = ta.id', 'left outer');
        $this->db->order_by('tcc.created_datetime', 'DESC');
        $this->db->limit($per_page, $offset);
        
        $query = $this->db->get();
        
        return $query->result_array();
    }

    public function m_get_group_list()
    {
    	
        $this->db->from('tbl_group');
        
        $query = $this->db->get();
        
        return $query->result_array();	
    }

    public function m_check_used_coupon($coupon_code)
    {
    	$this->db->from('tbl_coupon_code');
		$this->db->where('coupon_code', $coupon_code);
		$query = $this->db->get();		

		$result = $query->row_array();
		
		if($result['is_used'] == 1) {
			return true;
		}
		else {
			return false;
		}
    }

    public function m_count_coupon_group()
	{
		$this->db->select('ta.user_id, tcc.group_id, COUNT(tcc.group_id) as use_count');
		$this->db->from('tbl_coupon_code AS tcc');
		$this->db->where('tcc.is_used', 1);
		$this->db->join('tbl_accounts AS ta', 'tcc.accounts_id = ta.id', 'left outer');
		
		$this->db->group_by('tcc.accounts_id'); 
		$this->db->group_by('tcc.group_id'); 
		
    	$query = $this->db->get();
        
        return $query->result_array();
	}
}