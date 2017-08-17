<?php
class Interest_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_interests($wheres = array(),$total = false, $limit = 10, $offset = 0)
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
		$query = $this->db->get('historical_interest');
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
	

	function get_interests__()
	{
		$this->db->select('*')->from('historical_interest');
		$query = $this->db->get();
        return $query->result_array();

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

	function add_interest($interest,$capitalise=0)
	{
		$data = array( 'title' => $interest,'interest_capitalise' => $capitalise);
		$this->db->insert('historical_interest', $data); 	
	}
	
	function remove_interest($interest_id)
	{
		$this->db->where('interest_id', $interest_id);
		$this->db->delete('historical_interest'); 
		$this->remove_interest_media($site_id);
	} 

	function remove_interest_media($interest_id)
	{
		$this->db->where('interest_id', $interest_id);
		$this->db->delete('interest_media');	
	}
	
	function update_interest($interest_id,$interest)
	{
		$data = array( 'title' => $interest );
		$this->db->where('interest_id', $interest_id);
		$this->db->update('historical_interest', $data); 
	}

}

?>
