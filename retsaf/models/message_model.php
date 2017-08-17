<?php
class Message_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_messages($wheres = array())
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		//$this->db->limit($limit,$offset);
		$query = $this->db->get('welcome');
		if($query->num_rows() > 0)
		{
			
			$data = $query->result_array();
			
			return $data;
		}
		return false;
	}
	
	function get_app_instruction()
	 {
	   $query = $this->db->get('how_to_use_app_instructions');
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return $data;
		}
		return false;
	 }
	
}

?>
