<?php
class Media_model extends CI_Model{
	//put your code here

	public function __construct()
	{
	$this->load->database();
	}

	function getmedia( $wheres = array() , $where_ins = array() , $select = '')
	{
		if($select != '')
		{
			$this->db->select($select);
		}else
		{
			$this->db->select();
		}
		 
		$this->db->from('media');
		$this->db->join('media_types','media.mediaType_id = media_types.mediaType_id');
		$this->db->join('feature_images','media.feature_image_id = feature_images.image_id');
		$this->db->join('interest_media','media.media_id = interest_media.media_id');
		//$this->db->join('historical_interest','historical_interest.interest_id = interest_media.interest_id');
		
		if(!empty($wheres))
		{
			foreach($wheres as $key => $val)
			{
				$this->db->where($key , $val);
			}
		}
		
		if(!empty($where_ins))
		{
			foreach($where_ins as $wherein)
			{
				$condition = $wherein[0];
				$where_in = $wherein[1];
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
	
	function getmediaTypes()
	{
		$query = $this->db->get('media_types');
		if($query->num_rows() > 0)
		{			
			$data = $query->result_array();
			return $data;
		}
		return false;
	}
	
}

?>
