<?php
class images_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_images($wheres = array())
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		//$this->db->limit($limit,$offset);
		$query = $this->db->get('images');
		if($query->num_rows() > 0)
		{
			
			$data = $query->result_array();
			
			return $data;
		}
		return false;
	}
	
}

?>
