<?php
class Site_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database(); 
	}

	function get_sites($wheres = array(),$total = false, $limit = 10, $offset = 0)
	{
		$this->db->select();
		$this->db->from('historical_sites');
		$this->db->join('feature_images','historical_sites.feature_image_id = feature_images.image_id');
		$this->db->order_by("historical_sites.site_name", "asc");
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
		$query=$this->db->get();
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

	function get_select_sites()
	{
		$this->db->select('site_id,site_name');
		$this->db->from('historical_sites');
		$this->db->order_by("historical_sites.site_name", "asc");
		$query=$this->db->get();
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function add_feature_image($path)
	{
		$data = array( 'path' => $path );
		if($this->db->insert('feature_images', $data))
			{
				$this->db->order_by('image_id', 'DESC');
				$query = $this->db->get('feature_images');
				$id = $query->row('image_id');
				return $id;
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

	function update_feature_image($image_id,$path)
	{
		$data = array('path' => $path);
		$this->db->where('image_id', $image_id);
		$this->db->update('feature_images', $data); 
	}
	
	function add_site($data)
	{
		$this->db->insert('historical_sites', $data); 	
	}
	
	function remove_feature_image($image_id)
	{
		$this->db->where('image_id', $image_id);
		$this->db->delete('feature_images'); 
	}
	function remove_site($site_id)
	{
		$this->db->where('site_id', $site_id);
		$this->db->delete('historical_sites');

		//$this->remove_site_media($site_id);	
	}
	
	/*function remove_site_media($site_id)
	{
		$this->db->where('site_id', $site_id);
		$this->db->delete('site_media');	
	}*/
	
	function update_site($site_id,$data)
	{
		$this->db->where('site_id', $site_id);
		$this->db->update('historical_sites', $data); 
	}

}

?>
