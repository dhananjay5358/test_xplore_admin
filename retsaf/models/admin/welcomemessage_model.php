<?php
class WelcomeMessage_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_messages($wheres = array(),$total = false, $limit = 10, $offset = 0)
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
		$this->db->order_by("message_id", "desc");
		$query = $this->db->get('welcome');
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

	function add_message($message)
	{
		$data = array( 'message' => $message );
		$this->db->insert('welcome', $data); 	
	}
	
	function remove_message($message_id)
	{
		$this->db->where('message_id', $message_id);
		$this->db->delete('welcome'); 
	}
	
	function update_message($message_id,$message)
	{
		$data = array( 'message' => $message );
		$this->db->where('message_id', $message_id);
		$this->db->update('welcome', $data); 
	}

}

?>
