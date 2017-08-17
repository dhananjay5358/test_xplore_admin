<?php
class Site_model extends CI_Model{
	//put your code here

	public function __construct()
	{
		$this->load->database();
	}

	function getSites($wheres = array() , $where_ins = array() , $like = array() , $select='')
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('historical_sites');
		$this->db->join('historical_interest','historical_sites.interest_id = historical_interest.interest_id');
		$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		
		if(!empty($like))
		{
			$like_key = $like['0'];
			$like_values = $like['1'];
			foreach($like_values as $value) 
			{
				$this->db->like('LOWER('.$like_key.')', strtolower($value));
			}
		}
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];
				if($condition=='historical_sites.state')
				{
					$this->db->or_where_in($condition , $where_in);
				}else
				{	
					$this->db->where_in($condition , $where_in);
				}
			}
			
		}
		
		//$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function getSitesForFreeApp($wheres = array() , $where_ins = array() , $like = array() , $select='')
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('historical_sites');
		$this->db->join('historical_interest','historical_sites.interest_id = historical_interest.interest_id');
		$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		
		$query_str = "";
		$where_str = "";
		$where_in_str = "";
		if(!empty($wheres))
		{
			$where_str = $where_str."( ";
			foreach($wheres as $key => $val)
			{
				//$this->db->where($key , $val);
				if($where_str == "( ")
				{
					$where_str = $where_str.$key." = ".$val;
				}else{
					$where_str = $where_str." AND ".$key." = ".$val;
				}
			}
			$where_str = $where_str." )";
		}
		
		if($query_str!='')
		{
			$query_str = $query_str." AND ".$where_str;
		}else{
			$query_str = $where_str;
		}
		
		
		if(!empty($where_ins))
		{
			$where_in_str = $where_in_str."( ";		
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = implode("','", $wherein['1']);
				$where_in = "'".$where_in."'";
				//$where_in = $wherein['1'];
				if($where_in_str=="( ")
				{
					$where_in_str = $where_in_str.$condition." IN (".$where_in.")";
				}else{
					$where_in_str = $where_in_str." OR ".$condition." IN (".$where_in.")";
				}
				/*if($condition=='historical_sites.state')
				{
					$this->db->or_where_in($condition , $where_in);
				}else
				{	
					$this->db->where_in($condition , $where_in);
				}*/
			}
			$where_in_str = $where_in_str." )";
			
		}
		
		if($query_str!='')
		{
			$query_str = $query_str." AND ".$where_in_str;
		}else{
			$query_str = $where_in_str;
		}
		
		//$query_str = $query_str.$where_in_str;
		
		if($query_str!='')
		{
			$this->db->where($query_str,null,false);
		}
		
		if(!empty($like))
		{
			$like_key = $like['0'];
			$like_values = $like['1'];
			foreach($like_values as $value) 
			{
				$this->db->like('LOWER('.$like_key.')', strtolower($value));
			}
		}
		
		$this->db->order_by("historical_sites.site_name", "asc"); 
		//$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function getCityOrState($select,$where_ins=array())
	{
		$this->db->select($select);
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];
				$this->db->where_in($condition , $where_in);
			}
			
		}
		
		$this->db->group_by($select);
		$query = $this->db->get('historical_sites');		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
        
        function getCityOfSelectedState($select,$where_ins1=array(),$where_ins2=array())
        {
            $this->db->select($select);
		
		if(!empty($where_ins1))
		{
			foreach($where_ins1 as $wherein1)
			{
				$condition = $wherein1['0'];
				$where_in1 = $wherein1['1'];                            
				$this->db->where_in($condition , $where_in1);
                                
			}
			
		}
                
                if(!empty($where_ins2))
		{
                        foreach($where_ins2 as $wherein2)
			{
				$condition1 = $wherein2['0'];
				$where_in2 = $wherein2['1']; 
				$this->db->where_in($condition1 , $where_in2);
                                
			}
                }
                
                $this->db->group_by($select);
		$query = $this->db->get('historical_sites');		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
        }
	
}

?>
