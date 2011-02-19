<?php

class Membership_model extends Model {

	function validate()
	{
	/***************************************************
	Purpose: tests whether correct login info
	Passed: N
	Returns: TRUE if correct login info
	*****************************************************/
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return true;
		}
		
	}
	
	function get_my_teams($username)
	{
	/***************************************************
	Purpose: 
	Passed: username
	Returns: 
	*****************************************************/	
		$this->db->select('league_description, league_url, commish, logo_filename, team_name');
		$this->db->from('league_teams');
		$this->db->join('league_info', 'league_teams.league_id_league_teams = league_info.league_id');
		$this->db->where("username_league_teams = '$username'");
		$query = $this->db->get()->result();
		
		return $query;
	}
	
	function commish($username){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$this->db->select('commish');
		$this->db->from('league_info');
		$this->db->where('league_id', 1);
		$query = $this->db->get()->result();
		
		if ($query[0]->commish == $username){
			return 1;
		}
		
		else{
			return 0;
		}
		
	}
	
	function test_tutorial($username)
	/***************************************************
	Purpose: Tests whether the user has already viewed the tutorial (no if first time logging in).  
	Passed: username
	Returns: 0 (if they've already seen it), 1 (if they haven't)
	*****************************************************/
	{
		$this->db->select('tutorial');
		$this->db->from('membership');
		$this->db->where('username', $username);
		$query = $this->db->get()->result();
		$tutorial = $query[0]->tutorial;

		return $tutorial;
		
	}
	
	function update_tutorial()
	/***************************************************
	Purpose: Changes tutorial from '1' to '0' after the first log in.
	Passed: N
	Returns: N
	*****************************************************/
	{
		$this->db->set('tutorial', 0);
		$this->db->where('username', $this->session->userdata('username'));
		$this->db->update('membership');
	}
	
	function create_member()
	/***************************************************
	Purpose: Creates a new member.
	Passed: N
	Returns: The inserted data, used only to confirm that function was run.
	*****************************************************/
	{
		
		$new_member_insert_data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			//'team_name' => $this->input->post('team_name'),
			'email_address' => $this->input->post('email_address'),			
			'username' => $this->input->post('username'),
			'password' => md5($this->input->post('password')),		
			'tutorial' => 1				
		);
		
		$insert = $this->db->insert('membership', $new_member_insert_data);
		return $insert;
	}
	
	function initialize_stats()
	/***************************************************
	Purpose: Creates a row in 'stats' with no actual data when a username is created.  (IS THIS NECESSARY?)
	Passed: N
	Returns: N
	*****************************************************/
	{
		$new_member_insert_data = array(
			'user_username' => $this->input->post('username')
		);
		
		$this->db->insert('stats', $new_member_insert_data);
	}
	
	function test_username()
	/***************************************************
	Purpose: Tests if a potential username is a repeat.
	Passed: N
	Returns: FALSE (if not okay), TRUE (if okay)
	*****************************************************/
	{
		$potential_new_member = array(
			'username' =>$this->input->post('username')
		);
		
		$this->db->where('username', $potential_new_member['username']);
		$query = $this->db->get('membership');
		
		if($query->num_rows > 0)
		{
			return FALSE;
		}
		
		else
		{
			return TRUE;
		}

	}
	
	
	function update_record($data)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->where('username_league_teams', $this->session->userdata('username'));
		$this->db->where('league_id_league_teams', 1);
		$this->db->update('league_teams', $data);
	}
	
	function update_trash($data)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->where('username_league_teams', $this->session->userdata('username'));
		$this->db->where('league_id_league_teams', 1);
		$this->db->update('league_teams', $data);
	}
	
	function check_team_duplicate($data)
	/***************************************************
	Purpose: Tests whether the user already has a team in the league (1).
	Passed: $data[team_name, username]
	Returns: 0 (if they don't own a team), 1 (if they do)
	Notes: Used by login/create_team controller
	*****************************************************/
	{
		$duplicates = 0;
		$this->db->where('username_league_teams', $data['username']);
		$this->db->where('league_id_league_teams', 1);
		$duplicates = $this->db->count_all_results('league_teams');
		
		$error = 0;
		if ($duplicates>0){
			$error = 1;
		}
		return $error;
	}
	
	function update_team($data)
	/***************************************************
	Purpose: Inserts a team into a given league (1).
	Passed: $data[team_name, username]
	Returns: N
	*****************************************************/
	{
		$this->db->set('league_id_league_teams', 1);//1 is GSWL
		$this->db->set('username_league_teams', $data['username']);
		$this->db->set('team_name', $data['team_name']);
		$this->db->insert('league_teams');
	}
	
	function team_info()
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{	
		$this->db->where('username', $this->session->userdata('username'));
		$query = $this->db->get('membership');
		if($query->num_rows > 0)
		{
			return $query->result();
		}
	}
	
	function standings_get_teams()
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->select('username, first_name, last_name, team_name');
		$this->db->from('membership');
		$this->db->join('league_teams', 'league_teams.username_league_teams = membership.username');
		$this->db->where('league_id_league_teams', 1);
		$query = $this->db->get();
		if($query->num_rows > 0)
		{
			return $query->result();
		}
	}
	
	
	function array_sort($array, $on, $order=SORT_ASC)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }

	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }
	    return $new_array;
	}

	
	function get_team_and_name($data)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->select('first_name, last_name, team_name, trash_talk');
		$this->db->from('membership');
		$this->db->join('league_teams', 'membership.username = league_teams.username_league_teams');
		$this->db->where('username', $data);
		$this->db->where('league_id_league_teams', 1);
		$query = $this->db->get();
		
		if($query->num_rows > 0)
		{
			return $query->result();
		}
	}
	
	
	function get_commish()
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->select('first_name, last_name, email_address');
		$this->db->from('league_info');
		$this->db->join('membership', 'league_info.commish = membership.username');
		$this->db->where('league_id', 1);
		$query = $this->db->get()->result();

		return $query;		
	}
	
	
}