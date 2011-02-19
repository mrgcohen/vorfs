<?php

Class Stats_model extends Model {	
	
	function standings($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$username = $data;
		$this->db->select_sum('total_fp');
		$this->db->from('standings_vw');
		$this->db->where('username', $username);
		$this->db->order_by('total_fp asc');
		$query = $this->db->get();
		$record = $query->result();
		
		
		foreach ($record as $row){
			$sum = $row->total_fp;
		}
		return $sum;
		
	}
	
	function get_player_ids(){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$num_players = $this->db->count_all_results('player_lookup');

		return $num_players;
	}

	function get_total_stats($player_num)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$length_of_game = 4;  //4 inning games - to calculate ERA.
		
		$this->db->select_sum('h_atbats');
		$this->db->select_sum('h_hits');
		$this->db->select_sum('h_1b');
		$this->db->select_sum('h_2b');
		$this->db->select_sum('h_3b');
		$this->db->select_sum('h_hr');
		$this->db->select_sum('h_rbi');
		$this->db->select_sum('h_runs');
		$this->db->select_sum('h_bb');
		$this->db->select_sum('h_tb');
		$this->db->select_sum('h_fp');
		$this->db->select_sum('p_ip');
		$this->db->select_sum('p_wins');
		$this->db->select_sum('p_losses');
		$this->db->select_sum('p_saves');
		$this->db->select_sum('p_runs');
		$this->db->select_sum('p_hits');
		$this->db->select_sum('p_bb');
		$this->db->select_sum('p_k');
		$this->db->select_sum('p_ip');
		$this->db->select_sum('p_fp');
		
		$this->db->from('players');
		$this->db->where('player_id', $player_num);
		$this->db->order_by('player_id asc');
		$query = $this->db->get();
		$record = $query->result();
		
		if ($record[0]->h_atbats and $record[0]->h_atbats !==0){
			$record[0]->h_avg = ($record[0]->h_hits)/($record[0]->h_atbats);}
		
		if ($record[0]->p_ip and $record[0]->p_ip !==0){
		$record[0]->p_era = ($record[0]->p_runs)/(($record[0]->p_ip)/$length_of_game);
		$record[0]->p_whip = ($record[0]->p_hits + $record[0]->p_bb)/($record[0]->p_ip);}
		
		else{
		$record[0]->p_era = 999;
		$record[0]->p_whip = 999;}
		
		$record[0]->total_fp = ($record[0]->h_fp+$record[0]->p_fp);
		return $record;
	}
	
	
	function get_player_name($player_num)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->select('lookup_first_name');
		$this->db->select('lookup_last_name');
		$this->db->select('team_name');
		$this->db->from('player_lookup INNER JOIN team_lookup on player_lookup.lookup_team = team_lookup.team_code');
		$this->db->where('lookup_player_id', $player_num);
		$this->db->order_by('lookup_player_id asc');
		$query = $this->db->get();
		$record = $query->result();
		
		return $record;
		
	}
	
	function update_temp_total_table($stuff){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$this->db->set('player_id', $stuff[0]->player_id);
		$this->db->set('first_name', $stuff[0]->first_name);
		$this->db->set('last_name', $stuff[0]->last_name);
		$this->db->set('team_name', $stuff[0]->team_name);
		$this->db->set('h_atbats', $stuff[0]->h_atbats);
		$this->db->set('h_hits', $stuff[0]->h_hits);
		$this->db->set('h_avg', $stuff[0]->h_avg);
		$this->db->set('h_1b', $stuff[0]->h_1b);
		$this->db->set('h_2b', $stuff[0]->h_2b);
		$this->db->set('h_3b', $stuff[0]->h_3b);
		$this->db->set('h_hr', $stuff[0]->h_hr);
		$this->db->set('h_rbi', $stuff[0]->h_rbi);
		$this->db->set('h_runs', $stuff[0]->h_runs);
		$this->db->set('h_bb', $stuff[0]->h_bb);
		$this->db->set('h_tb', $stuff[0]->h_tb);
		$this->db->set('h_fp', $stuff[0]->h_fp);
		
		if ($stuff[0]->p_ip !=0){
		$this->db->set('p_wins', $stuff[0]->p_wins);
		$this->db->set('p_losses', $stuff[0]->p_losses);
		$this->db->set('p_saves', $stuff[0]->p_saves);
		$this->db->set('p_ip', $stuff[0]->p_ip);
		$this->db->set('p_era', $stuff[0]->p_era);
		$this->db->set('p_whip', $stuff[0]->p_whip);
		$this->db->set('p_runs', $stuff[0]->p_runs);
		$this->db->set('p_hits', $stuff[0]->p_hits);
		$this->db->set('p_bb', $stuff[0]->p_bb);
		$this->db->set('p_k', $stuff[0]->p_k);
		$this->db->set('p_fp', $stuff[0]->p_fp);
		}
		
		else
		{
		$this->db->set('p_losses', 999);
		$this->db->set('p_wins', -999);
		$this->db->set('p_saves', -999);
		$this->db->set('p_era', 999);
		$this->db->set('p_whip', 999);
		$this->db->set('p_runs', 999);
		$this->db->set('p_hits', 999);
		$this->db->set('p_bb', 999);
		$this->db->set('p_k', -999);
		$this->db->set('p_fp', 0);
		}
		
		$this->db->set('total_fp', $stuff[0]->total_fp);
		
		$this->db->insert('total_stats');
	}
	
	function get_totaled_stats($limit, $offset, $sort_by, $sort_order, $search = null){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
		$sort_columns = array('team_name', 'h_atbats', 'h_hits', 'h_avg', 'h_1b', 'h_2b', 'h_3b', 'h_hr', 'h_tb', 'h_rbi', 'h_runs', 'h_bb', 'h_fp', 'p_ip', 'p_wins', 'p_losses', 'p_saves', 'p_runs', 'p_hits', 'p_bb', 'p_era', 'p_whip', 'p_k', 'p_fp', 'total_fp');
		$sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'last_name';
		

		$this->db->select('*');
		$this->db->from('total_stats');
		$this->db->like('last_name', $search['player']);
		$this->db->like('team_name', $search['region']);
		$this->db->like('team_name', $search['team']);
		$this->db->limit($limit, $offset);
		$this->db->order_by($sort_by, $sort_order);
		
		$query = $this->db->get();
		$record = $query->result();
		
		return $record;		
	}
	
	function get_totaled_stats_player_view($player_id){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		//Does the same thing, more or less, as the above function, except retrieves only one row because it is for a specific player's stat page.  So also no need for limit, offset, sorting, pagination.
		$this->db->select('*');
		$this->db->from('total_stats');
		$this->db->where('player_id', $player_id);
		
		$query = $this->db->get();
		$record = $query->result();
		
		return $record;		
	}

	function test_duplicate_player($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	
		//Returns 0 if no duplicate, 1 if duplicate (illegal)
		
		//this->db->select('player');
		//$this->db->from('stats');
		
		$duplicates = 0;
		$this->db->where('user_username', $data['username']);
		$this->db->where('player', $data['player_id']);
		$duplicates = $this->db->count_all_results('stats');
		
		//$query = $this->db->get()->result();
		
		$error = 0;
		if ($duplicates){
			$error = 1;
		}
		
		return $error;
	}
	
	function test_roster_size($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		//Returns 0 if less than 5 players (okay), 1 if 5 or more (illegal)
		
		$this->db->where('user_username', $data['username']);
		$this->db->where('week', $data['week']);
		$roster_size = $this->db->count_all_results('stats');
		
		$error = 0;
		if ($roster_size>=5){
			$error = 1;
		}//Best not to hard-code this for later!  Settings->roster_size! (table)
		
		return $error;
	}

	function addplayer($data)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{	
		$this->db->set('user_username', $this->session->userdata('username'));
		$this->db->set('week', $data['week']);
		$this->db->set('active', 1);
		$this->db->set('player', $data['player_id']);
		
		$this->db->insert('stats');
	}
	
	function dropplayer($data)
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	{
		$this->db->where('user_username', $this->session->userdata('username'));
		$this->db->where('week', $data['week']);
		$this->db->where('player', $data['player_id']);
		
		$this->db->delete('stats');
	}
	
	function __add_database($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$this->db->set('player_id', $data['player_id']);
		$this->db->set('game_date_code', $data['game_date_code']);
		$this->db->set('game_time_code', $data['game_time_code']);
		$this->db->set('game_opponent', $data['game_opponent']);
		$this->db->insert('players');
	}
	
	function __add_database_2($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$this->db->set('h_atbats', $data['h_atbats']);
		$this->db->set('h_runs', $data['h_runs']);
		$this->db->set('h_hits', $data['h_hits']);
		$this->db->set('h_1b', $data['h_1b']);
		$this->db->set('h_2b', $data['h_2b']);
		$this->db->set('h_3b', $data['h_3b']);
		$this->db->set('h_hr', $data['h_hr']);
		$this->db->set('h_rbi', $data['h_rbi']);
		$this->db->set('h_bb', $data['h_bb']);
		$this->db->set('h_tb', $data['h_tb']);
		$this->db->set('h_fp', $data['h_fp']);
		$this->db->where('game_id', $data['game_id']);
		
		$this->db->update('players');
	}
	
	function __add_database_3($data){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
	
		$this->db->set('p_wins', $data['p_wins']);
		$this->db->set('p_losses', $data['p_losses']);
		$this->db->set('p_saves', $data['p_saves']);
		$this->db->set('p_ip', $data['p_ip']);
		$this->db->set('p_runs', $data['p_runs']);
		$this->db->set('p_hits', $data['p_hits']);
		$this->db->set('p_bb', $data['p_bb']);
		$this->db->set('p_k', $data['p_k']);
		$this->db->set('p_fp', $data['p_fp']);
	
		$this->db->where('player_id', $data['player_id']);
		$this->db->where('game_date_code', $data['game_date_code']);
		$this->db->where('game_time_code', $data['game_time_code']);
		
		$this->db->update('players');
	}
	
	function get_date($date_code){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		$this->db->select('date');
		$this->db->from('date_lookup');
		$this->db->where('date_id', $date_code);
		$query = $this->db->get()->result();
		
		return $query[0]->date;

	}
	
	function get_team_players($username, $date_code){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		//Called by my team page.  Returns results of a query: $return[row]->'player' is the player id.  Each row is all of the players on your team, given username($username) and week ($date_code).
		
	//	$query = $this->db->query("SELECT active FROM (stats join player_lookup on player_lookup.lookup_player_id = stats.player) where user_username = '$username' and week = '$date_code'");
		
		$this->db->select('player, lookup_first_name, lookup_last_name, team_name, active');
		$this->db->from('stats');
		$this->db->join('player_lookup', 'player_lookup.lookup_player_id = stats.player');
		$this->db->join('team_lookup', 'player_lookup.lookup_team = team_lookup.team_code');
		$this->db->where('user_username', $username);
		$this->db->where('week', $date_code);
		$this->db->order_by('active', 'desc');
		$this->db->order_by('lookup_team', 'asc');
		
		$query = $this->db->get()->result();
		
		return $query;
		
	}
	
	function get_team_stats($username, $date_code, $player){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		//Called by my team page.  Returns results of a query: $return[row]->tons of things, listed below.  For each player on your team(called in a loop), will return their stats, given username and week.
		//To make faster, maybe not select all?  But so many columns.
		
		$query = $this->db->query("SELECT * FROM ((((`stats` join `players` on((`players`.`player_id` = `stats`.`player`))) join `player_lookup` on((`players`.`player_id` = `player_lookup`.`lookup_player_id`))) join `team_lookup` on((`player_lookup`.`lookup_team` = `team_lookup`.`team_code`))) join `opponent_lookup` on((`opponent_lookup`.`opp_code` = `players`.`game_opponent`))) where (`week` = `game_date_code`) and (`user_username` = '$username') and (`week` = '$date_code') and (`player` = '$player') order by `players`.`game_date_code`,`stats`.`active` desc,`players`.`player_id`,`players`.`game_time_code`");
		
		$result = $query->result();
		foreach ($result as $row){
			$row->p_fp = round($row->p_fp, 1);
		}
		
		$query_sum = $this->db->query("SELECT SUM(h_fp) as h_fp, SUM(p_fp) as p_fp, SUM(h_atbats) as h_atbats, SUM(h_hits) as h_hits, SUM(h_runs) as h_runs, SUM(h_rbi) as h_rbi, SUM(h_tb) as h_tb, SUM(p_wins) as p_wins, SUM(p_saves) as p_saves, SUM(p_k) as p_k, SUM(p_ip) as p_ip, SUM(p_runs) as p_runs FROM ((((`stats` join `players` on((`players`.`player_id` = `stats`.`player`))) join `player_lookup` on((`players`.`player_id` = `player_lookup`.`lookup_player_id`))) join `team_lookup` on((`player_lookup`.`lookup_team` = `team_lookup`.`team_code`))) join `opponent_lookup` on((`opponent_lookup`.`opp_code` = `players`.`game_opponent`))) where (`week` = `game_date_code`) and (`user_username` = '$username') and (`week` = '$date_code') and (`player` = '$player') order by `players`.`game_date_code`,`stats`.`active` desc,`players`.`player_id`,`players`.`game_time_code`");
		
		$result_sum = $query_sum->result();
		foreach ($result_sum as $row){
			$row->p_fp = round($row->p_fp, 1);
		}
		
		$results = array(
		'result' => $result,
		'result_sum' => $result_sum	
		);
		
		return $results; 
		/*Returns an array with:
		user_username
		id (this is the move id basically.)
		week = game_date_code (1-8, but only one!)
		player_id = player
		game_id
		game_time_code (1-4)
		opp_nickname
		team_nickname
		lookup_first_name
		lookup_last_name
		stats
		active (0 or 1)
		*/
	}
	
	function get_player_stats($player_id){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/	
		
		//Used by the player stats view method.  Gets game by game player stats for player $player_id, which are sent to view.
		
		$this->db->select('*');
		$this->db->from('players');
		$this->db->join('opponent_lookup', 'opponent_lookup.opp_code = players.game_opponent');
		$this->db->where('player_id', $player_id);
		$this->db->order_by('game_date_code', 'asc');
		$this->db->order_by('game_time_code', 'asc');
		$query = $this->db->get()->result();

		return $query;
	}
	
	function get_standings($username){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		$query = $this->db->query("SELECT SUM(h_fp) as h_fp, SUM(p_fp) as p_fp, SUM(h_tb) as h_tb, SUM(h_rbi) as h_rbi, SUM(h_runs) as h_runs, SUM(h_atbats) as h_atbats, SUM(h_hits) as h_hits, SUM(p_wins) as p_wins, SUM(p_saves) as p_saves, SUM(p_ip) as p_ip, SUM(p_runs) as p_runs, SUM(p_k) as p_k FROM (`stats` join `players` on`players`.`player_id` = `stats`.`player`) where (`user_username` = '$username') and (game_date_code = week)");
		$result = $query->result();
		
		foreach ($result as $row){
			$row->p_fp = round($row->p_fp,1);
			$row->total_fp = $row->h_fp + $row->p_fp;
			if (is_integer($row->p_ip) == false){
				$temp=$row->p_ip;
				while ($temp > 1){
					$temp--;
				}
				if ($temp > 0.3 and $temp < 0.35){//this is supposed to say  = 0.333 but that didn't work
					$temp=$row->p_ip=($row->p_ip - (1/3)) + (0.1);
				}
				else if ($temp > 0.65 and $temp < 0.7){//same, 0.6666
					$temp=$row->p_ip=($row->p_ip - (2/3)) + (0.2);
				}
			}
			$row->p_ip = round($row->p_ip, 1);
		}
		
		return $result[0];
		//$sum_result = $result[0]->h_fp + $result[0]->p_fp;
		//return $sum_result;
	}
	
	function get_standings_week($username, $week){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		$query = $this->db->query("SELECT SUM(h_fp) as h_fp, SUM(p_fp) as p_fp, SUM(h_tb) as h_tb, SUM(h_rbi) as h_rbi, SUM(h_runs) as h_runs, SUM(h_hits) as h_hits, SUM(h_atbats) as h_atbats, SUM(p_wins) as p_wins, SUM(p_saves) as p_saves, SUM(p_ip) as p_ip, SUM(p_runs) as p_runs, SUM(p_k) as p_k FROM (`stats` join `players` on`players`.`player_id` = `stats`.`player`) where (`user_username` = '$username') and (game_date_code = week) and (`week` = '$week')");
		$result = $query->result();
		
		foreach ($result as $row){
			$row->p_fp = round($row->p_fp,1);
			$row->total_fp = $row->h_fp + $row->p_fp;
			if (is_integer($row->p_ip) == false){
				$temp=$row->p_ip;
				while ($temp > 1){
					$temp--;
				}
				if ($temp > 0.3 and $temp < 0.35){//this is supposed to say  = 0.333 but that didn't work
					$temp=$row->p_ip=($row->p_ip - (1/3)) + (0.1);
				}
				else if ($temp > 0.65 and $temp < 0.7){//same, 0.6666
					$temp=$row->p_ip=($row->p_ip - (2/3)) + (0.2);
				}
			}
			if ($row->p_ip){
				$row->p_ip = round($row->p_ip, 1);}
			if (!$row->h_fp){
				$row->h_fp = 0;
			}
		}
		
		return $result[0];
	}
	
	function schedule(){
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		$this->db->select('*');
		$this->db->from('date_lookup');
		$query = $this->db->get()->result();
		
		return $query;
	}
	
	function __fix_the_dates($data){///Used to fix dates, from MA to others.
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		$this->db->select('game_date_code');
		$this->db->from('players');
		$this->db->where('game_id', $data['x']);
		$query = $this->db->get();
		
		$test = $query->result();
		
		foreach ($test as $t){
			$to_change = $t->game_date_code;
			echo $to_change;
			
			if ($to_change == 3){
				$this->db->set('game_date_code', 4);
				$this->db->where('game_id', $data['x']);
				$this->db->update('players');
			}
			
			else if ($to_change == 4){
				$this->db->set('game_date_code', 5);
				$this->db->where('game_id', $data['x']);
				$this->db->update('players');
			}
			
			else if ($to_change == 5){
				$this->db->set('game_date_code', 7);
				$this->db->where('game_id', $data['x']);
				$this->db->update('players');
			}
			
			else if ($to_change == 6){
				$this->db->set('game_date_code', 8);
				$this->db->where('game_id', $data['x']);
				$this->db->update('players');
			}
			
		}
		
		//$this->db->set('game_date_code', $data['x']);
	}
	
	function __fix_pitching_fp($data){///Used to fix dates, from MA to others.
	/***************************************************
	Purpose: 
	Passed:
	Returns: 
	*****************************************************/
		
		$this->db->select('p_ip, p_wins, p_saves, p_runs, p_k');
		$this->db->from('players');
		$this->db->where('game_id', $data['x']);
		$query = $this->db->get();

		$test = $query->result();
		
		foreach ($test as $row){
		
			$newvalue = (7*$row->p_wins) + (5*$row->p_saves) + (3*$row->p_ip) - (3*$row->p_runs) + (0.5*$row->p_k);
			echo $newvalue;
			echo $data['x'];
		}
		
		$this->db->set('p_fp', $newvalue);
		$this->db->where('game_id', $data['x']);
		$this->db->update('players');
		
	}
	
}