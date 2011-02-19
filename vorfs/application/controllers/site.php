<?php

class Site extends Controller {
	
	function __construct()
	{
		parent::Controller();
		$this->is_logged_in();
	}
	
	function index()
	{
		$data['username'] = $this->session->userdata('username');
		$this->load->model('membership_model');
		$my_teams = $this->membership_model->get_my_teams($data['username']);
		$data['my_teams'] = $my_teams;
		
		$data['main_content'] = 'vorfs_home';
		$this->load->view('includes/template', $data);
	}
	
	function create(){
		$data['main_content'] = 'create_view';
		$this->load->view('includes/basictemplate', $data);
	}
	
	function join(){
		$data['main_content'] = 'join_view';
		$this->load->view('includes/template', $data);
	}
	
	function contact()
	{
		$data['main_content'] = 'contact_view';
		$this->load->view('includes/template', $data);
	}
	
	function about()
	{
		$data['main_content'] = 'about_view';
		$this->load->view('includes/template', $data);
	}
	
	function tutorial()
	{
		$data['main_content'] = 'tutorial_view';
		$this->load->view('includes/template', $data);
	}
	
	function home()
	{
		$this->load->model('stats_model');
		$this->load->model('membership_model');
		
		$data['standings'] = $this->get_standings();
		$data['players'] = $this->stats_model->get_totaled_stats(10, 0, 'total_fp', 'desc');
		$data['commish'] = $this->membership_model->commish($this->session->userdata('username'));
		
		
		$data['main_content'] = 'home_view';
		$this->load->view('includes/template', $data);
	}
	
	function commish()
	{	
		$data['main_content'] = 'commish_view';
		$this->load->view('includes/template', $data);
	}
	
	function graph($team1, $stat, $team2, $othername){
		
		/*
		these are the stats that it works with:
		
		to get other stats, change function get_team_stats method inside stats_model, and change the SQL query called "query_sum".  For example if you also wanted to get homeruns, you'd add ", SUM(h_hr) as h_hr" before FROM
		
		SUM(h_fp) as h_fp, SUM(p_fp) as p_fp, SUM(h_atbats) as h_atbats, SUM(h_runs) as h_runs, SUM(h_rbi) as h_rbi, SUM(h_tb) as h_tb, SUM(p_wins) as p_wins, SUM(p_saves) as p_saves, SUM(p_k) as p_k, SUM(p_ip) as p_ip, SUM(p_runs) as p_runs
		*/
		
		$this->load->model('stats_model');
		//Get all the player ids.
		
	for ($date=1; $date<=8; $date++){	
		$team_roster = $this->stats_model->get_team_players($team1, $date);
		$roster_size = count($team_roster);
		
		if ($roster_size > 0){
			$data['week_stats_sum'] = array();
			
			$data['team_roster'] = $team_roster;
			for ($x=0; $x<$roster_size; $x++){//X is the player.  So it does this loop for each player on the roster.
				$temp = $this->stats_model->get_team_stats($team1, $date, $team_roster[$x]->player);//Will return an array of team stats.  team_roster$x->player parameter is the current player index you want to get stats for.  it loops! (so it is easier to view in separate tables)
				$data['week_stats'][$x] = $temp['result'];
				$data['week_stats_sum'][$x] = $temp['result_sum'];


				$data['team_total_fp'] = 0;
				$data[$stat] = 0;
	
				//This adds up the weekly team total points, only adding points if a player is active.
				
				for ($a=0; $a<count($data['week_stats_sum']); $a++){
					//if ($team_roster[$a]->active == 1){
					if ($stat == 'team_total_fp'){
						$data[$stat] += ($data['week_stats_sum'][$a][0]->h_fp + $data['week_stats_sum'][$a][0]->p_fp);
						}
					else{
						$data[$stat] += $data['week_stats_sum'][$a][0]->$stat;
						}
					//}
				
				}
			}

		$array1[$date][$stat] = $data[$stat];
		}
		else if ($roster_size == 0)
			{
				$data['week_stats_sum'] = array();
				$array1[$date][$stat] = 0;
			}
		
			
	}

	for ($date=1; $date<=8; $date++){	
		$team_roster = $this->stats_model->get_team_players($team2, $date);
		$roster_size = count($team_roster);
		if ($roster_size > 0){
			$data['week_stats_sum'] = array();
			
			$data['team_roster'] = $team_roster;
			for ($x=0; $x<$roster_size; $x++){//X is the player.  So it does this loop for each player on the roster.
				$temp = $this->stats_model->get_team_stats($team2, $date, $team_roster[$x]->player);//Will return an array of team stats.  team_roster$x->player parameter is the current player index you want to get stats for.  it loops! (so it is easier to view in separate tables)
				$data['week_stats'][$x] = $temp['result'];
				$data['week_stats_sum'][$x] = $temp['result_sum'];


				$data['team_total_fp'] = 0;
				$data[$stat] = 0;
	
				//This adds up the weekly team total points, only adding points if a player is active.
				
				for ($a=0; $a<count($data['week_stats_sum']); $a++){
					//if ($team_roster[$a]->active == 1){
					if ($stat == 'team_total_fp'){
						$data[$stat] += ($data['week_stats_sum'][$a][0]->h_fp + $data['week_stats_sum'][$a][0]->p_fp);
						}
					else{
						$data[$stat] += $data['week_stats_sum'][$a][0]->$stat;
						}
					//}
				
				}
			}

		$array2[$date][$stat] = $data[$stat];
		}
		else if ($roster_size == 0)
			{
				$data['week_stats_sum'] = array();
				$array2[$date][$stat] = 0;
			}
		
			
	}


	//echo $array[2][$stat];

	$array1[0][$stat] = 0;
	$array2[0][$stat] = 0;
	$dstring = "";
	for ($x=1; $x<=8; $x++){
		$array1[$x][$stat] = $array1[$x][$stat] + $array1[$x-1][$stat];
		//echo $array1[$x][$stat]. ' ';
		$dstring = $dstring. $x. '='. $array1[$x][$stat].',';
	}
	echo '<br>';
	for ($x=1; $x<=8; $x++){
		$array2[$x][$stat] = $array2[$x][$stat] + $array2[$x-1][$stat];
		//echo $array2[$x][$stat]. ' ';
		$dstring = $dstring. '#'. $x. '='. $array2[$x][$stat].',';
	}
	$title = $stat;
	if($title == 'team_total_fp') $title = 'Total Fantasy Points';
	else if($title == 'p_fp') $title = 'Pitching Fantasy Points';
	else if($title == 'p_k') $title = 'Strikeouts';
	else if($title == 'p_runs') $title = 'Runs Allowed';
	else if($title == 'p_ip') $title = 'Innings Pitched';
	else if($title == 'p_saves') $title = 'Saves';
	else if($title == 'p_wins') $title = 'Wins';
	else if($title == 'h_fp') $title = 'Hitting Fantasy Points';
	else if($title == 'h_runs') $title = 'Runs';
	else if($title == 'h_rbi') $title = 'Runs Batted In';
	else if($title == 'h_tb') $title = 'Total Bases';
	else if($title == 'h_hits') $title = 'Hits';
	else if($title == 'h_atbats') $title = 'At Bats';
	$dstring = $dstring.'@Title='. $title. ',@X=Week,@Y=,@S1Name=You,@S2Name='. $othername;
	$this->load->helper('url');
	?><p><object width="571" height="300"
data="data:application/x-silverlight-2," type="application/x-silverlight-2">
<param name="source" value="<?php echo base_url();?>linegraph.xap"/>
<param name="onError" value="onSilverlightError" />
<param name="background" value="white" />
<param name="minRuntimeVersion" value="4.0.50826.0" />
<param name="autoUpgrade" value="true" />
<param name="initParams" value="<?php echo $dstring ?>" />
<param name="background" value="#00FFFFFF" /> <param name="windowless" value="true" />
<param name="pluginbackground" value="#00FFFFFF" />
<a href="http://go.microsoft.com/fwlink/?LinkID=149156&v=4.0.50826.0" style="text-decoration:none">
<img src="http://go.microsoft.com/fwlink/?LinkId=161376" alt="Get Microsoft Silverlight" style="border-style:none"/>
</a>
</object></p><?php

}
	
	function team($uri3 = null)
	{	
		if ($this->uri->segment(4)==null)
		{
			redirect('/site/team/'. $this->uri->segment(3). '/1');
		}

		$this->load->model('stats_model');
		$this->load->model('membership_model');
		$data['username'] = $this->uri->segment(3); 
		
		//This will make sure that the user actually owns a team in this league and is not just viewing it.
		$data['in_league'] = $this->membership_model->check_team_duplicate($data);

		if (!$data['in_league'])
		{
			$data['main_content'] = 'team_error';
			$this->load->view('includes/template', $data);
		}
	
	else{
		//This goes to the view as the title
		$test = $this->membership_model->get_team_and_name($data['username']);
		foreach ($test as $t){
			$data['first_name'] = $t->first_name;
			$data['last_name'] = $t->last_name;
			$data['team_name'] = $t->team_name;
			$data['trash_talk'] = $t->trash_talk;
		}
		
		$this->load->model('membership_model');
		
		if ($query = $this->membership_model->team_info())
		{
			$data['records2'] = $query;
		}
	
		$this->load->model('stats_model');
		
		$data['date_code'] = $this->uri->segment(4);
		$data['date'] = $this->stats_model->get_date($this->uri->segment(4));
	
		//Get all the player ids.
		$team_roster = $this->stats_model->get_team_players($data['username'], $data['date_code']);
		$roster_size = count($team_roster);
		
		//For each player id.  Customize the get stats thing to only get that one.  Then store it.
		
		//foreach team roster as. ->player_id
		$data['team_roster'] = $team_roster;
		
		
		for ($x=0; $x<$roster_size; $x++){//X is the player.  So it does this loop for each player on the roster.
		
		$temp = $this->stats_model->get_team_stats($data['username'], $data['date_code'], $team_roster[$x]->player);//Will return an array of team stats.  team_roster$x->player parameter is the current player index you want to get stats for.  it loops! (so it is easier to view in separate tables)
		$data['week_stats'][$x] = $temp['result'];
		$data['week_stats_sum'][$x] = $temp['result_sum'];

		$data['team_total_fp'] = 0;
	
	//This adds up the weekly team total points, only adding points if a player is active.
		for ($a=0; $a<count($data['week_stats_sum']); $a++){
			if ($team_roster[$a]->active == 1){
				$data['team_total_fp'] += ($data['week_stats_sum'][$a][0]->h_fp + $data['week_stats_sum'][$a][0]->p_fp);
			}
		}
		
		}
		
		$roster_size = count($team_roster);
		
		$data['roster_size'] = $roster_size;
		
		//Get the active regions:
		$schedule = $this->stats_model->schedule();
		$schedule2['mass'] = $schedule[$data['date_code']-1]->mass;
		$schedule2['new_york'] = $schedule[$data['date_code']-1]->new_york;
		$schedule2['phila'] = $schedule[$data['date_code']-1]->phila;
		$data['schedule'] = $schedule2;
		
		
		//Get the overall team total points:
		$data['team_total'] = $this->stats_model->get_standings($data['username'])->total_fp;		
		
		/////////////GET THE RANK!  SAME AS STANDINGS CODE!//////////////////////////////////////////////////////////
		$records = $this->membership_model->standings_get_teams();
		
		$x=0;
		$records_2 = array();
		foreach ($records as $row){			
			$result = $this->stats_model->get_standings($row->username);
			$records_2 [$x] = array(
				'username' => $row->username,
				'first_name' => $row->first_name,
				'last_name' => $row->last_name,
				'team_name' => $row->team_name,
				'total_fp' => $result->total_fp
				);
			$x++;
		}

		$records_2 = $this->membership_model->array_sort($records_2, 'total_fp', SORT_DESC);//sorts array by total fantasy points

		//This assigns 1 to first, 2 to 2nd, etc. after already sorted:
		$place = 1;
		$records_with_place = array();
		foreach($records_2 as $row){
			$records_with_place[$place] = array(
				'place' => $place,
				'username'=> $row['username'],
				'first_name'=> $row['first_name'],
				'last_name'=> $row['last_name'],
				'team_name'=> $row['team_name'],
				'total_fp'=> $row['total_fp']
			);
			
			$place++;
		}
		
		
		$data['place'] = 0;
		$data['num_teams'] = count($records); 
		foreach ($records_with_place as $row){
			if ($row['username'] == $data['username']){
				$data['place'] = $row['place'];
			}
		}
		//////END GETTING RANK.  This code is just copied from the standings method.///////
		
		
		
		
		/////////////GET THE WEEKLY RANK!  SAME AS STANDINGS CODE!//////////////////////////////////////////////
		$week_records = $this->membership_model->standings_get_teams();
		
		
		foreach ($week_records as $row){			
			$row->total_fp = $this->stats_model->get_standings_week($row->username, $data['date_code'])->total_fp;
		}
		
		$week_records_2 = array();
		
		for ($x=0; $x<count($week_records); $x++){///This transforms into an array of arrays so the sort will work, and not objects.
			$week_records_2 [$x] = array(
				    	'username' => $week_records[$x]->username,
						'first_name' => $week_records[$x]->first_name,
						'last_name' => $week_records[$x]->last_name,
						'team_name' => $week_records[$x]->team_name,
						'total_fp' => $week_records[$x]->total_fp
				    );
		}

		$week_records_2 = $this->membership_model->array_sort($week_records_2, 'total_fp', SORT_DESC);//sorts array by total fantasy points


		//This assigns 1 to first, 2 to 2nd, etc. after already sorted:
		$week_place = 1;
		$week_records_with_place = array();
		foreach($week_records_2 as $row){
			$week_records_with_place[$week_place] = array(
				'place' => $week_place,
				'username'=> $row['username'],
				'first_name'=> $row['first_name'],
				'last_name'=> $row['last_name'],
				'team_name'=> $row['team_name'],
				'total_fp'=> $row['total_fp']
			);
			
			$week_place++;
		}
		$data['week_place'] = 0;
		foreach ($week_records_with_place as $row){
			if ($row['username'] == $data['username']){
				$data['week_place'] = $row['place'];
			}
		}
		//////END GETTING WEEKLY RANK.  This code is just copied from the standings method.///////
		
		
		////GET TRASH TALK!!!
		
		$data['main_content'] = 'team_view';
		$this->load->view('includes/template', $data);
	}//this ends the hugely long else.
	
	}
	
	
	function players($sort_by = 'total_fp', $sort_order = 'desc', $offset = 0, $error = null)
	{
		$this->load->model('stats_model');

		$limit = 20;
		$data['limit'] = $limit;
		
		$data['fields'] = array(
			'last_name' => 'Player',
			'team_name' => 'Team',
			'h_atbats' => 'AB',
			'h_hits' => 'H',
			'h_avg' => 'AVG',
			'h_1b' => '1B',
			'h_2b' => '2B',
			'h_3b' => '3B',
			'h_hr' => 'HR',
			'h_tb' => 'TB',
			'h_rbi' => 'RBI',
			'h_runs' => 'R',
			'h_bb'=> 'BB',
			'h_fp'=> 'FP (Hitting)',
			'p_wins' => 'W',
			'p_losses' =>'L',
			'p_saves' => 'S',
			'p_era' => 'ERA',
			'p_whip' => 'WHIP',
			'p_ip' => 'IP',
			'p_runs' => 'ER',
			'p_hits' => ' H ',
			'p_bb'=> ' BB ', //There is one space before and after H and BB to differentiate from offensive hitting and walks.  This is needed by the sorting since one sorts up and one sorts down.
			'p_k' => 'K',
			'p_fp' => 'FP (Pitching)',
			'total_fp' => 'Total FP'
		);
		
	$search['player'] = $this->input->post('player');
	$search['region'] = $this->input->post('region');
	if ($search['region'] == 'null'){$search['region']='';}
	$search['team'] = $this->input->post('team');
	if ($search['team'] == 'null'){$search['team']='';}
	$reset = $this->input->post('reset');

	
		
	//	$this->db->empty_table('total_stats');  //This empties the temporary table from old data.
		
		if ($query = $this->stats_model->get_player_ids())//Gets the number of players.
		{
			$num_players = $query;
		}
	
		
		for ($x=1; $x<=$num_players; $x++){//For each player, retrieve total stats.
		//x is the player id

	//The stuff commented out below created the total_stats table.  I got rid of it because having to create 
	//this table every single time you refresh the page doesn't make sense and may fail with multiple users.
	//But note that total_stats here is static and doesn't update dynamically if new stats were to be added to
	//the players table.
	/*	
		$stuff[$x] = $this->stats_model->get_total_stats($x);
		
			if ($stuff[$x][0]->p_era == 999){//These two loops reset ERA and WHIP to nothing.  Division by 0 problem.
				$stuff[$x][0]->p_era = '';
			}
			if ($stuff[$x][0]->p_whip == 999){
				$stuff[$x][0]->p_whip = '';
			}
			
		//Getting player name from player id. $x is player id.
		
		$name_info = $this->stats_model->get_player_name($x);
		$stuff[$x][0]->first_name = $name_info[0]->lookup_first_name;
		$stuff[$x][0]->last_name = $name_info[0]->lookup_last_name;
		$stuff[$x][0]->team_name = $name_info[0]->team_name;
		$stuff[$x][0]->player_id = $x;
		
		//Fixing IP back to .1, .2 from 0.333,0.666
			if (is_integer($stuff[$x][0]->p_ip) == false){
				$temp=$stuff[$x][0]->p_ip;
				while ($temp > 1){
					$temp--;
				}
				if ($temp > 0.3 and $temp < 0.35){//this is supposed to say  = 0.333 but that didn't work
					$temp=$stuff[$x][0]->p_ip=($temp=$stuff[$x][0]->p_ip - (1/3)) + (0.1);
				}
				else if ($temp > 0.65 and $temp < 0.7){//same, 0.6666
					$temp=$stuff[$x][0]->p_ip=($temp=$stuff[$x][0]->p_ip - (2/3)) + (0.2);
				}
			}
			
		$this->stats_model->update_temp_total_table($stuff[$x]);
		
		*/
		
		$all_stats = $this->stats_model->get_totaled_stats($limit, $offset, $sort_by, $sort_order, $search);
		}
		
		if (count($all_stats>0)){
			foreach($all_stats as $row){
				$data['player_id'] = $row->player_id;
				$data['username'] = $this->session->userdata('username');
				$row->owned = $this->stats_model->test_duplicate_player($data);
			}
		}
			
		$data['allstats'] = array(
			'stuff' => $all_stats
			);
		
		$data['main_content'] = 'players_view';	
		$data['error'] = $error;
		
		//Stuff for search menus:
		$data['region'] = array(
			'null' => '',
			'(MA)'  => 'MA',
			'(NY)'    => 'NY',
			'(PA)'   => 'PA'
		     );
		
		$data['team'] = array(
			'null' => '',
			'Black Widows' => 'Black Widows',
			'Bombers' => 'Bombers',
			'C-notes' => 'C-Notes',
			'Crusaders' => 'Crusaders',
			'Dead Eyes' => 'Dead Eyes',
			'High Rollers' => 'High Rollers',
			'K-9s' => 'K-9s',
			'Diablos' => 'Diablos',
			'Mustangs' => 'Mustangs',
			'Piranhas' => 'Piranhas',
			'Revolvers' => 'Revolvers',
			'Risers' => 'Risers',
			'Spades' => 'Spades',
			'Venom' => 'Venom'
		);
		
			$this->load->library('pagination');
			$config = array();
			$config['base_url'] = site_url("site/players/$sort_by/$sort_order");
			$config['total_rows'] = $num_players;
			$config['per_page'] = $limit;
			$config['uri_segment'] = 5;
			$this->pagination->initialize($config); 
			$data['pagination'] = $this->pagination->create_links();
			$data['num_players'] = $num_players;
		$this->load->view('includes/template', $data);
		
	}
	
	function player_stats($player_id){
		$this->load->model('stats_model');
		
		
		$data['stats_summed'] = $this->stats_model->get_totaled_stats_player_view($player_id);
		$data['stats'] = $this->stats_model->get_player_stats($player_id);
		
		////Assigning logo file names for the player page.
		$data['logo'] = 0;
		if ($data['stats_summed'][0]->team_name == 'Dead Eyes (MA)'){$data['logo'] = 'dead_eyes';}
		else if ($data['stats_summed'][0]->team_name == 'Piranhas (MA)' OR $data['stats_summed'][0]->team_name == 'Piranhas (NY)'){$data['logo'] = 'piranhas';}
		else if ($data['stats_summed'][0]->team_name == 'Venom (MA)' OR $data['stats_summed'][0]->team_name == 'Venom (NY)' OR $data['stats_summed'][0]->team_name == 'Venom (PA)'){$data['logo'] = 'venom';}
		else if ($data['stats_summed'][0]->team_name == 'Revolvers (MA)' OR $data['stats_summed'][0]->team_name == 'Revolvers (NY)'){$data['logo'] = 'revs';}
		else if ($data['stats_summed'][0]->team_name == 'Diablos (MA)'){$data['logo'] = 'diablos';}
		else if ($data['stats_summed'][0]->team_name == 'Bombers (MA)'){$data['logo'] = 'bombers';}
		else if ($data['stats_summed'][0]->team_name == 'Spades (MA)' OR $data['stats_summed'][0]->team_name == 'Spades (NY)'){$data['logo'] = 'spades';}
		else if ($data['stats_summed'][0]->team_name == 'Black Widows (MA)'){$data['logo'] = 'widows';}
		else if ($data['stats_summed'][0]->team_name == 'C-Notes (MA)' OR $data['stats_summed'][0]->team_name == 'C-Notes (NY)' OR $data['stats_summed'][0]->team_name == 'C-Notes (PA)'){$data['logo'] = 'cnotes';}
		else if ($data['stats_summed'][0]->team_name == 'High Rollers (MA)' OR $data['stats_summed'][0]->team_name == 'High Rollers (PA)'){$data['logo'] = 'rollers';}
		else if ($data['stats_summed'][0]->team_name == 'Mustangs (MA)'){$data['logo'] = 'mustangs';}
		else if ($data['stats_summed'][0]->team_name == 'Risers (MA)' OR $data['stats_summed'][0]->team_name == 'Risers (NY)' OR $data['stats_summed'][0]->team_name == 'Risers (PA)'){$data['logo'] = 'risers';}
		else if ($data['stats_summed'][0]->team_name == 'Crusaders (MA)' OR $data['stats_summed'][0]->team_name == 'C-Notes (NY)'){$data['logo'] = 'crusaders';}
		else if ($data['stats_summed'][0]->team_name == 'K-9s (MA)' OR $data['stats_summed'][0]->team_name == 'K-9s (NY)'){$data['logo'] = 'k9s';}
		/////////////////////////////////
		
		
			
		
		foreach ($data['stats'] as $row){
			if ($row->p_ip){
				if (is_integer($row->p_ip) == false){
					$temp=$row->p_ip;
					while ($temp > 1){
						$temp--;
					}
					if ($temp > 0.3 and $temp < 0.35){//this is supposed to say  = 0.333 but that didn't work
						$temp=$row->p_ip=($row->p_ip - (1/3)) + (0.1);
					}
					else if ($temp > 0.65 and $temp < 0.7){//same, 0.6666
						$temp=$row->p_ip=($temp=$row->p_ip - (2/3)) + (0.2);
					}
				}
			}
		}
		
	
		$data['main_content'] = 'playerstats_view';
		$this->load->view('includes/template', $data);
	}
	
	
	function update()
	{	
		$data = array(
		'team_name' => $this->input->post('new_name')
		);

		$this->load->model('membership_model');
		$this->membership_model->update_record($data);
		redirect('/site/team/'. $this->session->userdata('username'). '/1');
		
	}
	
	function update_trash_talk()
	{	
		$data = array(
		'trash_talk' => $this->input->post('trash_message')
		);
		$week = $this->input->post('week');
		
		
		
		$this->load->model('membership_model');
		$this->membership_model->update_trash($data);
		redirect('/site/team/'. $this->session->userdata('username'). '/1');
	}
	
	function update_password()
	{
		echo "This is for users to change their password from the settings view.  Will send back to home.";
		
	}
	
	function get_standings()
	{
		$this->load->model('stats_model');
		$this->load->model('membership_model');
		
		$records = $this->membership_model->standings_get_teams();
		
		$x=0;
		$records_2 = array();
		foreach ($records as $row){			
			$result = $this->stats_model->get_standings($row->username);
			$records_2 [$x] = array(
				'username' => $row->username,
				'first_name' => $row->first_name,
				'last_name' => $row->last_name,
				'team_name' => $row->team_name,
				'h_fp' => $result->h_fp,
				'p_fp' => $result->p_fp,
				'h_tb'=> $result->h_tb,
				'h_rbi'=> $result->h_rbi,
				'h_runs'=> $result->h_runs,
				'h_atbats' => $result->h_atbats,
				'h_hits' => $result->h_hits,
				'p_wins'=> $result->p_wins,
				'p_saves'=> $result->p_saves,
				'p_ip'=> $result->p_ip,
				'p_runs'=> $result->p_runs,
				'p_k'=> $result->p_k,
				'total_fp' => $result->total_fp
				);
			$x++;
		}
		

		$records_2 = $this->membership_model->array_sort($records_2, 'total_fp', SORT_DESC);//sorts array by total fantasy points

		//This assigns 1 to first, 2 to 2nd, etc. after already sorted:
		$place = 1;
		$records_with_place = array();

		
		foreach($records_2 as $row){
			$records_with_place[$place] = array(
				'place' => $place,
				'username'=> $row['username'],
				'first_name'=> $row['first_name'],
				'last_name'=> $row['last_name'],
				'team_name'=> $row['team_name'],
				'total_fp'=> $row['total_fp'],
				'h_fp'=> $row['h_fp'],
				'p_fp'=> $row['p_fp'],
				'h_tb'=> $row['h_tb'],
				'h_rbi'=> $row['h_rbi'],
				'h_atbats' => $row['h_atbats'],
				'h_hits' => $row['h_hits'],
				'h_runs'=> $row['h_runs'],
				'p_wins'=> $row['p_wins'],
				'p_saves'=> $row['p_saves'],
				'p_ip'=> $row['p_ip'],
				'p_runs'=> $row['p_runs'],
				'p_k'=> $row['p_k']
			);
			
			$place++;
		}
		
		
		$data['records'] = $records_with_place;
		
		return $data;
	}
	
	function get_standings_week($week)
	{
		$this->load->model('stats_model');
		$this->load->model('membership_model');
		
		$records = $this->membership_model->standings_get_teams();
		
		$x=0;
		$records_2 = array();
		foreach ($records as $row){			
			$result = $this->stats_model->get_standings_week($row->username, $week);
			$records_2 [$x] = array(
				'username' => $row->username,
				'first_name' => $row->first_name,
				'last_name' => $row->last_name,
				'team_name' => $row->team_name,
				'h_fp' => $result->h_fp,
				'p_fp' => $result->p_fp,
				'h_tb'=> $result->h_tb,
				'h_rbi'=> $result->h_rbi,
				'h_runs'=> $result->h_runs,
				'h_atbats' => $result->h_atbats,
				'h_hits' => $result->h_hits,
				'p_wins'=> $result->p_wins,
				'p_saves'=> $result->p_saves,
				'p_ip'=> $result->p_ip,
				'p_runs'=> $result->p_runs,
				'p_k'=> $result->p_k,
				'total_fp' => $result->total_fp
				);
			$x++;
		}
		

		$records_2 = $this->membership_model->array_sort($records_2, 'total_fp', SORT_DESC);//sorts array by total fantasy points

		//This assigns 1 to first, 2 to 2nd, etc. after already sorted:
		$place = 1;
		$records_with_place = array();
		foreach($records_2 as $row){
			$records_with_place[$place] = array(
				'place' => $place,
				'username'=> $row['username'],
				'first_name'=> $row['first_name'],
				'last_name'=> $row['last_name'],
				'team_name'=> $row['team_name'],
				'total_fp'=> $row['total_fp'],
				'h_fp'=> $row['h_fp'],
				'p_fp'=> $row['p_fp'],
				'h_tb'=> $row['h_tb'],
				'h_rbi'=> $row['h_rbi'],
				'h_runs'=> $row['h_runs'],
				'h_atbats' => $row['h_atbats'],
				'h_hits' => $row['h_hits'],
				'p_wins'=> $row['p_wins'],
				'p_saves'=> $row['p_saves'],
				'p_ip'=> $row['p_ip'],
				'p_runs'=> $row['p_runs'],
				'p_k'=> $row['p_k']
			);
			
			$place++;
		}
		
		
		$data['records'] = $records_with_place;
		
		return $data;
	}
	
	function standings($week=null){
		if (!isset($week) or ($week == 0)){
			$data = $this->get_standings();
		}
		else {
			$data = $this->get_standings_week($week);
		}
		$data['main_content'] = 'standings_view';
		$this->load->view('includes/template', $data);
		
	}
	
	function schedule()
	{
		$this->load->model('stats_model');
		$data['schedule'] = $this->stats_model->schedule();
		
		$data['main_content'] = 'schedule_view';
		$this->load->view('includes/template', $data);
	}
	
	function addplayer_confirm($player_id){
		$this->load->model('stats_model');
		$id = $this->stats_model->get_player_name($player_id);
		
		$data['username'] = $this->session->userdata('username');
		$data['player_id'] = $player_id;
		$duplicate_player_test = $this->stats_model->test_duplicate_player($data);
		
		$data['first_name'] = $id[0]->lookup_first_name;
		$data['last_name'] = $id[0]->lookup_last_name;
		$data['team_name'] = $id[0]->team_name;
		
		if(strpos($data['team_name'], '(MA)')){$region = 'MA';}
		else if (strpos($data['team_name'], '(NY)')){$region = 'NY';}
		else if(strpos($data['team_name'], '(PA)')){$region = 'PA';}

		for ($x=1; $x<=8; $x++){
			$team_roster = $this->stats_model->get_team_players($this->session->userdata('username'), $x);
			$roster_size[$x] = count($team_roster);
		}
		
		$data['region2'] = array();
		//if ($data['region']){
		

		
		for ($x=1; $x<=8; $x++){
			//if ($duplicate_player_test==1){
			//$marker = 1;
			if ($roster_size[$x] < 5){
				if ($region == 'MA'){
					if ($x == 1 or $x == 2 or $x == 4 or $x == 5 or $x == 7 or $x == 8){
						$data['region2'][$x] = $x;
					}
				}
				else if ($region == 'NY'){
					if ($x == 1 or $x == 3 or $x == 4 or $x == 5 or $x == 6 or $x == 8){
						$data['region2'][$x] = $x;
					}
				}
				else if ($region == 'PA'){
					if ($x == 1 or $x == 3 or $x == 4 or $x == 6 or $x == 7 or $x == 8){
						$data['region2'][$x] = $x;
					}
				}
			}
			//}
		
			//else if ($duplicate_player_test == 1){$data['error'] = "You already own that player.  You can only own a player for one week!";}
		}

		if ($data['region2'] and $duplicate_player_test == 0){
			$data['region'] = $data['region2'];
		}
		
		else if ($duplicate_player_test == 1){$data['error'] = "You already own this player.  You can only own a player for one week!";}
		
		else {$data['error'] = 'Your roster is already set for all 8 weeks!';}
		
		$data['main_content'] = 'add_player_form';
		$this->load->view('includes/basictemplate', $data);
	
	}
	
	function dropplayer_confirm($player_id, $week){
		$this->load->model('stats_model');
		$id = $this->stats_model->get_player_name($player_id);
		
		$data['first_name'] = $id[0]->lookup_first_name;
		$data['last_name'] = $id[0]->lookup_last_name;
		$data['team_name'] = $id[0]->team_name;
		$data['week'] = $week;
		
		$data['main_content'] = 'drop_player_form';

		$this->load->view('includes/basictemplate', $data);
	}
	
	
	function addplayer(){
		$data['week'] = $this->input->post('week');
		//$data['active'] = $this->input->post('active');
		$data['player_id'] = $this->input->post('player_id');
		$data['username'] = $this->session->userdata('username');
		
		
		$this->load->model('stats_model');
		$duplicate_player_test = $this->stats_model->test_duplicate_player($data);
		$roster_size_test = $this->stats_model->test_roster_size($data);
		
		if ($roster_size_test){$error = "Illegal Transaction - only 5 players per week!";}
		if ($duplicate_player_test){$error = "Illegal Transaction - player already owned!";}
		if ($roster_size_test AND $duplicate_player_test){$error = "Illegal Transaction - only 5 players per week and player already owned!";}
		
		if ($roster_size_test OR $duplicate_player_test){
			$this->players($sort_by = 'total_fp', $sort_order = 'desc', $offset = 0, $error);
		}
		
		
		else{
			$this->stats_model->addplayer($data);
			redirect('/site/team/'. $data['username']. '/'. $data['week']);
		}
		//.$this->session->userdata('username'). '/'. $data['week']
	}
	
	function dropplayer(){
		$data['week'] = $this->input->post('week');
		$data['player_id'] = $this->input->post('player_id');
		
		$this->load->model('stats_model');
		$this->stats_model->dropplayer($data);
		
		redirect('/site/team/'. $this->session->userdata('username'). '/'. $data['week']);
	}
	

	
	function rules()
	{
		$this->load->model('membership_model');
		$commish = $this->membership_model->get_commish();
		$data['commish'] = $commish;
		$data['main_content'] = 'rules_view';
		$this->load->view('includes/template', $data);
	}
	
	function settings()
	{
		$data['main_content'] = 'settings_view';
		$this->load->view('includes/basictemplate', $data);
	}
	
	function trash_talk()
	{
		$this->load->model('membership_model');
		$test = $this->membership_model->get_team_and_name($this->session->userdata('username'));
		$data['trash_current'] = $test[0]->trash_talk;
		
		$data['main_content'] = 'trash_talk_view';
		$this->load->view('includes/basictemplate', $data);
	}
	
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if(!isset($is_logged_in) || $is_logged_in != true)
		{
			echo 'You don\'t have permission to access this page. <a href="../login">Login</a>';	
			die();		
			//$this->load->view('login_form');
		}		
	}		
	
	//must make non-private to use!
	function __commish_pitching()  //This inserts pitching stats into the database.
	{
		////////////
			$startingid = 0;
			$gp = 30;
		////////////
		
		
		//$data['main_content'] = 'commish_view';
		//$this->load->view('includes/template', $data);
		//$this->load->helper('file');
		$data = fopen("http://localhost:8888/cilogin/application/controllers/BA_games.txt", 'rb');

		for ($x=0; $x<=30;$x++){
			$testline=fgetcsv($data,999, "\t");//reads a line
			$storage[$x]=$testline;//stores it in the main array
		}
		

		$x=2;
		
		$b = explode (' ', $storage[0][0]);
		$myid = $b[0];
		echo $myid;
		$c = explode (' ', $storage[1][0]);
		$myteam = $c[0];
		echo $myteam;
		
		$insert['player_id'] = $myid;
		
		
		
		
		$num = $gp + 2;
	
		while ($x<$num){

		$a = explode(' ', $storage[$x][0]);
		
		//echo $a[0]. '<br>';
		//$x++;
	
		
		$data2 = fopen("http://localhost:8888/cilogin/application/controllers/BA_stats.txt", 'rb');
		
		
		if ($a[0] == '6/13/2010')
		$code = 1;

		else if ($a[0] == '6/20/2010')
		$code = 2;

		else if ($a[0] == '6/27/2010')
		$code = 3;

		else if ($a[0] == '7/11/2010')
		$code = 4;

		else if ($a[0] == '7/18/2010')
		$code = 5;
		
		else if ($a[0] == '7/25/2010')
		$code = 6;

		else if ($a[0] == '8/1/2010')
		$code = 7;

		else if ($a[0] == '8/6/2010')
		$code = 8;

		else if ($a[0] == '8/8/2010')
		$code = 8;

		else 
		$code = 9;
		
		echo $code;
		echo $a[1];
		if ($a[1] == '8:00')
		$timecode = 1;

		else if ($a[1] == '9:15')
		$timecode = 2;

		else if ($a[1] == '10:30')
		$timecode = 3;

		else if ($a[1] == '11:45')
		$timecode = 4;

		else if ($a[1] == '1:00')
		$timecode = 5;

		else if ($a[1] == '2:15')
		$timecode = 6;
		
		echo $timecode;
		
		
		if ($a[4] == $myteam){
		$oppteam = $a[6];
		}

		else if ($a[6] == $myteam){
		$oppteam = $a[4];
		}
		
		echo $oppteam;
		echo '<br>';
	
		
		$game_code[$x-2] = $code;
		$time_code[$x-2] = $timecode;
		$team_code[$x-2] = $oppteam;
		
		$x++;	
}	
	fclose($data);

	

	for ($x=0; $x<=30;$x++){
		$testline=fgetcsv($data2,999, "\t");//reads a line
		$storage2[$x]=$testline;//stores it in the main array
	}

		// 3 AB, 4 R, 5 H, 6 1B, 7 2B, 8 3B, 9 HR, 10 RBI, 11 SO, 12 BB

		for ($x=0; $x<$gp; $x++){
			echo $storage2[$x][2];
			
			//initialize wins, losses, saves to 0
			$insert['p_wins'] = 0;
			$insert['p_losses'] = 0;
			$insert['p_saves'] = 0;
			
			
			if ($storage2[$x][0]){
				echo 'win';
				$insert['p_wins'] = 1;
			}
			
			if ($storage2[$x][1]){
				echo 'loss';
				$insert['p_losses'] = 1;
			}
			
			if ($storage2[$x][2]){
				echo 'save';
				$insert['p_saves'] = 1;
			}
			
			$insert['p_hits'] = $storage2[$x][5];
			$insert['p_runs'] = $storage2[$x][6];
			$insert['p_bb'] = $storage2[$x][7];
			$insert['p_k'] = $storage2[$x][8];
			
			$insert['p_ip'] = $storage2[$x][4];
			//here switch .1 to .333, etc.
			
			$insert['p_ip'] += 0; //This just turns the string into a number!
			
			if (is_integer($insert['p_ip']) == false){
				$temp=$insert['p_ip'];
				while ($temp > 1){
					$temp--;
				}
				if ($temp > 0.08 and $temp < 0.12){//this is supposed to say  = 0.1 but that didn't work
					$insert['p_ip']=($insert['p_ip'] - 0.1) + (1/3);
				}
				else if ($temp > 0.18 and $temp < 0.22){//same, 0.2
					$insert['p_ip']=($insert['p_ip'] - 0.2) + (2/3);
				}
			}
			
			//$insert['player_id'] = $storage2[0][0];
			$insert['game_date_code'] = $game_code[$x];
			$insert['game_time_code'] = $time_code[$x];
			$insert['game_opponent'] = $team_code[$x];
			$insert['p_fp'] = (3*$insert['p_ip']) - (3*$insert['p_runs']) + (7*$insert['p_wins']) + (5*$insert['p_saves']) + (0.5 * $insert['p_k']);
	
			
			$this->load->model('stats_model');
			$this->stats_model->add_database_3($insert);
			
			/*$insert['h_atbats'] = $storage2[$x][3];
			$insert['h_runs'] = $storage2[$x][4];
			$insert['h_hits'] = $storage2[$x][5];
			$insert['h_2b'] = $storage2[$x][6];
			$insert['h_3b'] = $storage2[$x][7];
			$insert['h_hr'] = $storage2[$x][8];
			$insert['h_rbi'] = $storage2[$x][9];
			$insert['h_bb'] = $storage2[$x][11];
			$insert['h_1b'] = $storage2[$x][5] - $storage2[$x][6] - $storage2[$x][7] - $storage2[$x][8];
			$insert['h_tb'] = $insert['h_1b'] + (2*$storage2[$x][6]) + (3*$storage2[$x][7]) + (4*$storage2[$x][8]);
			$insert['h_fp'] = $insert['h_tb'] + $storage2[$x][9] + $storage2[$x][4];
			$insert['player'] = $storage2[0][0];
			$insert['game_id'] = $startingid;

			*/
			$startingid++;
		}
	
	echo '<br> <br>';
	for ($x=0; $x<count($game_code); $x++){
	
	echo $team_code[$x];
	}



	}



	
	//must make non-private to use!
	function __commish_hiting()  //This inserts hitting stats into the database.
	{
		////////////
			$startingid = 3227;
			$gp = 12;
		////////////
		
		
		//$data['main_content'] = 'commish_view';
		//$this->load->view('includes/template', $data);
		//$this->load->helper('file');
		$data = fopen("http://localhost:8888/cilogin/application/controllers/BA_games.txt", 'rb');

		for ($x=0; $x<=30;$x++){
			$testline=fgetcsv($data,999, "\t");//reads a line
			$storage[$x]=$testline;//stores it in the main array
		}
		

		$x=2;
		
		$b = explode (' ', $storage[0][0]);
		$myid = $b[0];
		echo $myid;
		$c = explode (' ', $storage[1][0]);
		$myteam = $c[0];
		echo $myteam;
		
		$insert['player_id'] = $myid;
		
		
		
		
		$num = $gp + 2;
		
		while ($x<$num){

		$a = explode(' ', $storage[$x][0]);
		
		echo $a[0];
		
		$data2 = fopen("http://localhost:8888/cilogin/application/controllers/BA_stats.txt", 'rb');
		
		
		if ($a[0] == '6/13/2010')
		$code = 1;

		else if ($a[0] == '6/20/2010')
		$code = 2;

		else if ($a[0] == '6/27/2010')
		$code = 3;

		else if ($a[0] == '7/11/2010')
		$code = 4;

		else if ($a[0] == '7/18/2010')
		$code = 5;
		
		else if ($a[0] == '7/25/2010')
		$code = 6;

		else if ($a[0] == '8/1/2010')
		$code = 7;

		else if ($a[0] == '8/6/2010')
		$code = 8;

		else if ($a[0] == '8/8/2010')
		$code = 8;

		else 
		$code = 9;
		
		echo $code;
		echo $a[1];
		if ($a[1] == '8:30')
		$timecode = 1;

		else if ($a[1] == '9:45')
		$timecode = 2;

		else if ($a[1] == '11:00')
		$timecode = 3;

		else if ($a[1] == '12:15')
		$timecode = 4;

		else if ($a[1] == '1:30')
		$timecode = 5;

		else if ($a[1] == '2:45')
		$timecode = 6;
		
		echo $timecode;
		
		
		if ($a[4] == $myteam){
		$oppteam = $a[6];
		}

		else if ($a[6] == $myteam){
		$oppteam = $a[4];
		}
		
		echo $oppteam;
		echo '<br>';
		$insert['game_date_code'] = $code;
		$insert['game_time_code'] = $timecode;
		$insert['game_opponent'] = $oppteam;
		
		$this->load->model('stats_model');
		$this->stats_model->add_database($insert);
		$x++;	
	}
	fclose($data);
	
	

	for ($x=0; $x<=30;$x++){
		$testline=fgetcsv($data2,999, "\t");//reads a line
		$storage2[$x]=$testline;//stores it in the main array
	}

		// 3 AB, 4 R, 5 H, 6 1B, 7 2B, 8 3B, 9 HR, 10 RBI, 11 SO, 12 BB

		for ($x=0; $x<$gp; $x++){
			$insert['h_atbats'] = $storage2[$x][3];
			$insert['h_runs'] = $storage2[$x][4];
			$insert['h_hits'] = $storage2[$x][5];
			$insert['h_2b'] = $storage2[$x][6];
			$insert['h_3b'] = $storage2[$x][7];
			$insert['h_hr'] = $storage2[$x][8];
			$insert['h_rbi'] = $storage2[$x][9];
			$insert['h_bb'] = $storage2[$x][11];
			$insert['h_1b'] = $storage2[$x][5] - $storage2[$x][6] - $storage2[$x][7] - $storage2[$x][8];
			$insert['h_tb'] = $insert['h_1b'] + (2*$storage2[$x][6]) + (3*$storage2[$x][7]) + (4*$storage2[$x][8]);
			$insert['h_fp'] = $insert['h_tb'] + $storage2[$x][9] + $storage2[$x][4];
			$insert['player'] = $storage2[0][0];
			$insert['game_id'] = $startingid;
			
			$this->load->model('stats_model');
			$this->stats_model->add_database_2($insert);
			
			$startingid++;
		}


	}
	
	function __commish2()//This was used to fix the dates.
	{
		
	$this->load->model('stats_model');
	
	for ($x=481; $x<482; $x++)
	{
		$data['x'] = $x;
		$this->stats_model->fix_the_dates($data);
	}
	
		
	}
	
	function __commish_fix_pitching()//This was used to fix an error in the pitching FP points.
	{
		$this->load->model('stats_model');
		for ($x=1; $x<3000; $x++){
			$data['x'] = $x;
			$this->stats_model->fix_pitching_fp($data);
		}
	}
	
	
}