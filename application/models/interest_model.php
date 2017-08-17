<?php
class Interest_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_interests($wheres = array())
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		$this->db->order_by("title", "asc"); 
		//$this->db->limit($limit,$offset);
		$query = $this->db->get('historical_interest');
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
}

?>
