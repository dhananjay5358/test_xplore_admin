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
	
	function getPublicPlaces($wheres = array() , $where_ins = array() , $like = array() , $select='',$is_like_in_or = false)
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Itineraries_store');
		$this->db->join('Itineraries_Likes','Itineraries_store.SrNo = Itineraries_Likes.SrNo','left outer');
                $this->db->group_by('Itineraries_store.SrNo');
		//$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		
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
			
			foreach($like_values as $key => $value) 
			{
				if($is_like_in_or){
					if($key == 0){
						$this->db->like('LOWER('.$like_key.')', strtolower($value));
					}else{
						$this->db->or_like('LOWER('.$like_key.')', strtolower($value));
					}
				}else{
					$this->db->like('LOWER('.$like_key.')', strtolower($value));
				}
				
				
			}
		}
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];
                                $condition1 = $wherein['2'];
                                $where_in1 = $wherein['3'];

				if($condition=='historical_sites.state')
				{
					$this->db->or_where_in($condition , $where_in);
				}else
				{	
					$this->db->where_in($condition , $where_in);
                                        $this->db->or_where_in($condition1 , $where_in1);
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
	
	function getTags($like = array() , $select='',$is_like_in_or = false)
	{
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Itineraries_store');
		$this->db->join('Itineraries_Likes','Itineraries_store.SrNo = Itineraries_Likes.SrNo','left outer');
                $this->db->group_by('Itineraries_store.SrNo');
                
                if(!empty($like))
		{
			$like_key = $like['0'];
			$like_values = $like['1'];
			
			foreach($like_values as $key => $value) 
			{
				if($is_like_in_or){
					if($key == 0){
						$this->db->like('LOWER('.$like_key.')', strtolower($value));
					}else{
						$this->db->or_like('LOWER('.$like_key.')', strtolower($value));
					}
				}else{
					$this->db->like('LOWER('.$like_key.')', strtolower($value));
				}
				
				
			}
		}
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function getPlacesTags($like = array() , $select='',$is_like_in_or = false)
	{
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Place_detail');
                
        	if(!empty($like))
		{
			$like_key = $like['0'];
			$like_values = $like['1'];
			
			foreach($like_values as $key => $value) 
			{
				if($is_like_in_or){
					if($key == 0){
						$this->db->like('LOWER('.$like_key.')', strtolower($value));
					}else{
						$this->db->or_like('LOWER('.$like_key.')', strtolower($value));
					}
				}else{
					$this->db->like('LOWER('.$like_key.')', strtolower($value));
				}
				
				
			}
		}
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function getPlaces($wheres = array() , $where_ins = array() , $like = array() , $select='',$is_like_in_or = false)
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Place_detail');
		//$this->db->join('historical_interest','historical_sites.interest_id = historical_interest.interest_id');
		//$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		
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
			
			foreach($like_values as $key => $value) 
			{
				if($is_like_in_or){
					if($key == 0){
						$this->db->like('LOWER('.$like_key.')', strtolower($value));
					}else{
						$this->db->or_like('LOWER('.$like_key.')', strtolower($value));
					}
				}else{
					$this->db->like('LOWER('.$like_key.')', strtolower($value));
				}
				
				
			}
		}
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];
                                $condition1 = $wherein['2'];
                                $where_in1 = $wherein['3'];

				if($condition=='historical_sites.state')
				{
					$this->db->or_where_in($condition , $where_in);
				}else
				{	
					$this->db->where_in($condition , $where_in);
                                        $this->db->or_where_in($condition1 , $where_in1);
				}
			}
			
		}
		$this->db->distinct();
		//$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function getEvents($wheres = array() , $where_ins = array() , $like = array() , $select='',$is_like_in_or = false)
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Events_detail');
		
		if(!empty($like))
		{
			$like_key = $like['0'];
			$like_values = $like['1'];
			
			foreach($like_values as $key => $value) 
			{
				if($is_like_in_or){
					if($key == 0){
						$this->db->like('LOWER('.$like_key.')', strtolower($value));
					}else{
						$this->db->or_like('LOWER('.$like_key.')', strtolower($value));
					}
				}else{
					$this->db->like('LOWER('.$like_key.')', strtolower($value));
				}
				
				
			}
		}
		$this->db->distinct();
		//$this->db->limit($limit,$offset);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function addEvents($tag,$detail,$LatLng,$placeId,$status,$path='')
	{
		$data = array(
			    	'PlaceIds' => $placeId,
				'LatLng' => $LatLng,
				'Category' => $tag,
				'ImagePath' => $path,
                		'Detail' => $detail
				);
		

		if($status ==  "false")
		{
		$this->db->set($data);
		$this->db->insert('Events_detail', $data); 
		}
		else
		{
		$this->db->set($data);
		$this->db->where('PlaceIds', $placeId);
		$this->db->update('Events_detail', $data); 
		}
		
		//$insert_id = $this->db->insert_id();
		$check=$this->db->affected_rows();
		
		if($check == 1)
		{
		return  $check;
		}
		else
		{
		return  $check;
		}
	}
	
        function addEventsPlaces($tag,$detail,$LatLng,$placeId,$status)
	{
		$data = array();
		$data = array(
			    	'PlaceIds' => $placeId,
				'LatLng' => $LatLng,
				'Category' => $tag,
                		'Detail' => $detail
			);
		if($status ==  "false")
		{
		$this->db->set($data);
		$this->db->insert('Place_detail', $data); 
		}
		else
		{
		$this->db->set($data);
		$this->db->where('PlaceIds', $placeId);
		$this->db->update('Place_detail', $data); 
		}
		
		//$insert_id = $this->db->insert_id();
		$check=$this->db->affected_rows();
		
		if($check == 1)
		{
		return  $check;
		}
		else
		{
		return  $check;
		}
	}
	
	
	function getLikeDetail($where_ins = array() , $select='',$srno,$userId)
	{
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Itineraries_Likes');
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];

				$this->db->where_in($condition , $where_in);
			}
		}
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$this->db->where('SrNo' , $srno);
			$this->db->where('FbUserId' , $userId);
			$query = $this->db->delete('Itineraries_Likes');
	
			return $query;
		}
		else
		{
			$data = array(
			    	'FbUserId' => $userId,
					'SrNo' => $srno
			);
			
			$this->db->trans_start();
			$this->db->insert('Itineraries_Likes', $data); 
			$insert_id = $this->db->insert_id();
			$check=$this->db->affected_rows();
			$this->db->trans_complete();	
		
			if($check == 1)
			{
				return  $insert_id;
			}
			else
			{
				return  $check;
			}
		}
	}
	
	function getPlacesDetail($where_ins = array() , $select='')
        {
        	if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Place_detail');
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein['0'];
				$where_in = $wherein['1'];

				$this->db->where_in($condition , $where_in);
			}
			
		}
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		
		return false;
        }
        
        function getTour($wheres = array() , $where_ins = array() , $like = array() , $select='')
	{ 
		
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Itineraries_store');
		//$this->db->join('historical_interest','historical_sites.interest_id = historical_interest.interest_id');
		//$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		
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
	
	function getTourList($where_ins = array())
	{ 
		$select='*';
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		
		$this->db->from('Place_detail');
		
		if(!empty($where_ins))
		{
			$condition = $where_ins['0'];
			$where_in = $where_ins['1'];
			foreach($where_in as $wherein)
			{
				$this->db->or_where_in($condition , $wherein);
			}
			
		}
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}

        function del_Tour($srno)
	{ 
		//$this->db->from('Itineraries_store');
	
		$this->db->where('SrNo' , $srno);
			
		$query = $this->db->delete('Itineraries_store');
	
		return $query;
	}

        function Edit_Tour($update = array(),$srno)
	{ 
		$this->db->where('SrNo', $srno);
		$query = $this->db->update('Itineraries_store', $update); 
	
		return $query;
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