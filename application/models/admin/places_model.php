<?php
class places_model extends CI_Model
{

	public function __construct()
	{
		$this->load->database();
	}

	function get_custom_places($wheres = array(),$total = false, $limit = 10, $offset = 0,$all = false)
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}

		if(!$total && !$all)
		{
			$this->db->limit($limit,$offset); 
		}
		
		$query = $this->db->get('custom_places');
		
		if($query->num_rows() > 0)
		{
			if($total)
			{
				$data = $query->num_rows();
			}
			else
			{
				$data = $query->result_array();
			}
			return $data;
		}
		return false;
	}

	function get_places_kendo()
	{
		$this->db->select('*')->from('custom_places');
		$this->db->order_by('sr_no','desc');
		$query = $this->db->get();
        return $query->result_array();

	}

	function remove_custom_place_updated($id)
	{

		$this->db->where('sr_no', $id);
		$this->db->delete('custom_places');
	}

	function remove_custom_place($id)
	{
		$this->db->where('experince_id', $id);
		$this->db->delete('experiance');
	}

		
	function get_places_info($wheres = array(),$total = false, $limit = 10, $offset = 0,$all = false)
	{
		
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		if(!$total && !$all)
		{
			$this->db->limit($limit,$offset); 
		}

		$query = $this->db->get('Place_detail');
		
		if($query->num_rows() > 0)
		{
			if($total)
			{
				$data = $query->num_rows();
			}
			else
			{
				$data = $query->result_array();
			}

			return $data;
		}
		return false;
	}

	function get_places_info_for_exp()
	{
		$this->db->where('SrNumber !=' , '25');
		$this->db->or_where('Place_Type !=' , 'Deals');
		$this->db->or_where('Place_Type !=' , 'Events');
		$query = $this->db->get('Place_detail');
		$data = $query->result_array();
		return $data;
	}

	function get_user_info_for_exp()
	{
		$this->db->distinct();
		$this->db->select('FbUserId');
		$query = $this->db->get('Itineraries_store');
		$data = $query->result_array();
		return $data;
	}

	
	function remove_experiences($id)
	{
		$this->db->where('sr_no', $id);
		$this->db->delete('custom_places');
	}

	function edit_custom_place($data,$id)
	{
		$this->db->where('sr_no', $id);
	    $this->db->update('custom_places', $data);
	}


	function accept_custom_place($data)
	{
		$this->db->set($data);
	    $this->db->insert('Place_detail',$data);    
	    return $this->db->insert_id();    
	}

	function save_custom_place($data)
	{
		$this->db->set($data);
	    $this->db->insert('custom_places',$data);    
	    return $this->db->insert_id(); 
	}

	function save_custom_news($data)
	{
		$this->db->set($data);
	    $this->db->insert('custom_news',$data);    
	    return $this->db->insert_id(); 
	}

	function get_custom_news($wheres = array(),$total = false, $limit = 10, $offset = 0,$all = false)
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}

		if(!$total && !$all)
		{
			$this->db->limit($limit,$offset); 
		}
		
		$query = $this->db->get('custom_news');
		
		if($query->num_rows() > 0)
		{
			if($total)
			{
				$data = $query->num_rows();
			}
			else
			{
				$data = $query->result_array();
			}
			return $data;
		}
		return false;
	}

	function get_news_kendo()
	{
		$this->db->select('*')->from('custom_news');
		$query = $this->db->get();
        return $query->result_array();
    }

	function update_custom_news($data,$id)
	{
		$this->db->where('sr_no', $id);
		$this->db->update('custom_news', $data); 
	}

	function remove_custom_news($id)
	{
		$this->db->where('sr_no', $id);
		$this->db->delete('custom_news');
	}

	function get_news()
	{
		$query = $this->db->get('custom_news');
		
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return $data;
		}
		return false;
	}

	function add_experinces($data)
	{ 	
		$this->db->insert('experiance',$data); 
		return $this->db->insert_id();   
	}

	function getExperiences($wheres = array(),$total = false, $limit = 20, $offset = 0)
	{
		
		$this->db->select('*');
		$this->db->from('experiance');
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		$this->db->order_by("experiance.experince_id", "asc");
		if(!$total)
			{
				$this->db->limit($limit,$offset);
			}
		$query = $this->db->get();
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

	function get_experience_kendo()
	{
		$this->db->select('*')->from('experiance');
		$query = $this->db->get();
        return $query->result_array();
    }

	function get_places($wheres = array(),$total = false, $limit = 10, $offset = 0)
	{

		$this->db->select('experiance.*,Itineraries_store.FbUserId');
		$this->db->from('experiance');
		$this->db->join('Itineraries_store','Itineraries_store.Shared_id = experiance.experince_id');
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
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			if($total)
			{
				$data = $query->num_rows();
			}
			else
			{
				$data = $query->result_array();
			}
			return $data;
		}
		return false;
	}

	function get_place_info($wheres = array())
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		$this->db->order_by("Detail", "asc"); 
		//$this->db->limit($limit,$offset);
		$query = $this->db->get('Place_detail');
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}

	function update_places($data,$id)
	{
		// echo "<pre>";
		// print_r($data);
		// print_r($id);
		// exit();
		$this->db->where('experince_id', $id);
		$this->db->update('experiance', $data); 
		return $this->db->insert_id();
	}
	
	function update_experinces_to_user($data,$id)
	{
		// echo "<pre>";
		// print_r($data);
		// print_r($id);
		// exit();
		$this->db->where('Shared_id', $id);
		$this->db->insert('Itineraries_store',$data); 
		return $this->db->insert_id();  
	}

	function add_experinces_to_user($data)
	{
		$this->db->insert('Itineraries_store',$data); 
		return $this->db->insert_id();  
	}

	public function Multiple_remove_File($p_id)
    {
    	$this->db->where('experince_id', $p_id);
        $this->db->delete('experiance');
        $data = $this->db->affected_rows();
		return $data;
    }

    public function Multiple_remove_File_news($p_id)
    {
    	$this->db->where('sr_no', $p_id);
        $this->db->delete('custom_news');
        $data = $this->db->affected_rows();
		return $data;
    }

    public function Multiple_remove_Custom_Places($p_id)
    {
    	$this->db->where('sr_no', $p_id);
        $this->db->delete('custom_places');
        $data = $this->db->affected_rows();
		return $data;
    }

    public function Multiple_remove_Deals($p_id)
    {
    	$this->db->where('SrNumber', $p_id);
        $this->db->delete('Place_detail');
        $data = $this->db->affected_rows();
		return $data;
    }
	
	
             
}

?>
