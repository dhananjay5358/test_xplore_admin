<?php
class Help_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_help($wheres = array(),$total = false, $limit = 10, $offset = 0)
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		
		if(!$total)
			{
				$this->db->limit($limit,$offset);
			}
		
		$query = $this->db->get('how_to_use_app_instructions');
		if($query->num_rows() > 0)
		{
			if($total)
			{
				$data = $query->num_rows();
			}else{
			$data = $query->result_array();
			}
			return $data;
		}
		return false;
	}
	
	/*function is_exists($name)
	{
		$this->db->where('title', $name);
		$query = $this->db->get('historical_interest');
		if($query->num_rows() > 0)
		{
			return true;
		}
		return false;
	}*/

	function add_help($message)
	{
		$data = array( 'instruction' => $message );
		$this->db->insert('how_to_use_app_instructions', $data); 	
	}
	
	function remove_help($message_id)
	{
		$this->db->where('instruction_id', $message_id);
		$this->db->delete('how_to_use_app_instructions'); 
	}
	
	function update_help($message_id,$message)
	{
		$data = array( 'instruction' => $message );
		$this->db->where('instruction_id', $message_id);
		$this->db->update('how_to_use_app_instructions', $data); 
	}

}

?>
