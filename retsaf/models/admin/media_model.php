<?php
class Media_model extends CI_Model{
	//put your code here

	public function __construct()
	{ 
	$this->load->database();
	}

	function get_media($wheres = array(),$total = false, $limit = 10, $offset = 0)
	{
		$this->db->select('media.*,feature_images.path,media_types.mediaType_title,interest_media.interest_id');
		$this->db->from('media');
		$this->db->join('feature_images','media.feature_image_id = feature_images.image_id');
		$this->db->join('media_types','media.mediaType_id = media_types.mediaType_id');
		$this->db->join('interest_media','media.media_id = interest_media.media_id','left'); 
		
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		$this->db->order_by("media.title", "asc");
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
	
	function get_mediaTypes($where=array())
	{
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		
		$query = $this->db->get('media_types');
		
		if($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
	function is_exists($where)
	{
		if(!empty($where))
		{
			foreach($where as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		$query = $this->db->get('interest_media');
		if($query->num_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function add_media($data)
	{ 	
		if($this->db->insert('media', $data))
			{
				$this->db->order_by('media_id', 'DESC');
				$query = $this->db->get('media');
				$id = $query->row('media_id');
				return $id;
			}
			return false;
	}
	
	function add_interest_media($data)
	{
		if(!$this->is_exists($data))
		{
			$this->db->insert('interest_media', $data);
		}
	}
	
	function remove_interest_media($media_id)
	{
		$this->db->where('media_id', $media_id);
		$this->db->delete('interest_media');
	}
	
	function remove_media($media_id)
	{
		$this->db->where('media_id', $media_id);
		$this->db->delete('media'); 
		
		$this->remove_interest_media($media_id);
	}
	
	function update_media($media_id,$data)
	{
		$this->db->where('media_id', $media_id);
		$this->db->update('media', $data); 
	}
	
	function update_interest_media($media_id,$data)
	{
		$this->remove_interest_media($media_id);
		
		$this->add_interest_media($data);
	}

}

?>
