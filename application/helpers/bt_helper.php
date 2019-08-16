<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('get_current_datetime'))
{
	function get_current_datetime()
	{
        return date('Y-m-d H:i:s');
	}
}

/**
 * 공통 페이지네이션을 처리
 */
if ( ! function_exists('get_pagination_data'))
{
    function get_pagination_data($total_rows, $per_page, $url, $extra_param = NULL)
    {
        $CI =& get_instance();
        
        $CI->load->library('pagination');

		$config['base_url'] = $url;
        
        if ($extra_param !== NULL)
        {
            $config['suffix'] = $extra_param;
        }
        
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
        $config['num_links'] = 4;
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;
        $config['first_url'] = $config['base_url'].'?per_page=0' . $extra_param;
		$config['full_tag_open'] = '<div class="text-center"><ul class="pagination">';
		$config['full_tag_close'] = '</ul></div>';
		$config['cur_tag_open'] = '<li class="active"><a data-original-title="" title="">';
		$config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li><span aria-hidden="true">';
        $config['prev_tag_close'] = '</span></li>';
		$config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li><span aria-hidden="true">';
        $config['next_tag_close'] = '</span></li>';
		$config['page_query_string'] = TRUE;
		$config['enable_query_strings'] = TRUE;

		$CI->pagination->initialize($config);

		return $CI->pagination->create_links();
	}
}

?>