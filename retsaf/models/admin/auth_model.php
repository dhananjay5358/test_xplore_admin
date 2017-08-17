<?php
class Auth_model extends CI_Model{
//put your code here

public function __construct()
{
$this->load->database();
}

function isAuth($username,$pass)
{
	$this->db->select('*');
	$this->db->where('username', $username);
	$this->db->where('password', $pass); 
	$query = $this->db->get('admin');
	if($query->num_rows() > 0)
	{
		$row = $query->row_array();
		return $row;
	}
	return false;
}

}

?>
