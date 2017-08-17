<?php
class Gallery_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function get_galleries($wheres = array(),$total = false, $limit = 10, $offset = 0,$all = false)
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
		$query = $this->db->get('image_gallery');
		
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

	function add_gallery($gallery)
	{
		$data = array( 'gallery_name' => $gallery );
		$this->db->insert('image_gallery', $data); 	
	}
	
	function add_tour($userId,$name,$Detail,$Profile,$tag,$identifier)
	{
		$data = array(
			    	'FbUserId' => $userId,
					'TourName' => $name,
					'Detail' => $Detail,
                	'Profile' => $Profile,
                    'Tags' => $tag,
                    'Identifier' =>$identifier
			);
		print_r($data);
		$this->db->trans_start();
		$this->db->insert('Itineraries_store', $data); 
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
	
	function remove_gallery($gallery_id)
	{
		$this->db->where('gallery_id', $gallery_id);
		$this->db->delete('image_gallery');
		
		$this->db->where('gallery_id', $gallery_id);
		$this->db->delete('images');
		
		$this->update_sites($gallery_id);	
	}
	
	function update_sites($gallery_id)
	{
		$this->db->select('site_id');
		$this->db->where('gallery_id', $gallery_id);
		$query = $this->db->get('historical_sites');
		if($query->num_rows() > 0)
		{
			$sites = $query->result_array();
			
			foreach($sites as $site)
			{
				$data1[] = $site['site_id'];
			}
			
			$data = array( 'gallery_id'=> 0 );
			
			$this->db->where_in('site_id', $data1);
			$this->db->update('historical_sites', $data);
		}
		
	}
	
	function update_gallery($gallery_id,$gallery)
	{
		$data = array( 'gallery_name' => $gallery );
		$this->db->where('gallery_id', $gallery_id);
		$this->db->update('image_gallery', $data); 
	}
	
	function get_images($where = array(),$total = false, $limit = 10, $offset = 0)
	{
		if(!empty($where))
		{
			foreach($where as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		if(!$total)
		{
			$this->db->limit($limit,$offset);
		}
		$query = $this->db->get('images');
		
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
	
	function add_image($image_name,$gallery_id,$path)
	{
		$data = array(
						'name' => $image_name,
						'gallery_id' => $gallery_id,
						'path' => $path	
					  );
		$this->db->insert('images', $data);
	}
	
	function update_image($image_id,$arr)
	{
		$this->db->where('image_id', $image_id);
		$this->db->update('images', $arr); 
	}
	
	function remove_image($image_id)
	{
		$this->db->where('image_id', $image_id);
		$this->db->delete('images'); 
	}

}

?>