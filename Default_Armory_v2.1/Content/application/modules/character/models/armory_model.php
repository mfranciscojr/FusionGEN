<?php

class Armory_model extends CI_Model
{
	public $realm;
	private $connection;
	private $id;
	private $realmId;
	private $EmulatorSimpleString = '';
	
	private function getEmulatorString()
	{
		return $this->EmulatorSimpleString;
	}
	

	public function __construct()
	{
		parent::__construct();
	}
	/**
	 * Assign the character ID to the model
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Assign the realm object to the model
	 */
	public function setRealm($id)
	{
		$this->realmId = $id;
		$this->realm = $this->realms->getRealm($id);
		$this->EmulatorSimpleString = str_replace(array('_ra', '_soap', '_rbac'), '', $this->realm->getConfig('emulator'));
	}

	/**
	 * Connect to the character database
	 */
	public function connect()
	{
		$this->realm->getCharacters()->connect();
		$this->connection = $this->realm->getCharacters()->getConnection();
	}

	/**
	 * Check if the current character exists
	 */
	public function characterExists()
	{
		$this->connect();

		$query = $this->connection->query("SELECT COUNT(*) AS total FROM ".table("characters", $this->realmId)." WHERE ".column("characters", "guid", false, $this->realmId)."= ?", array($this->id));
		$row = $query->result_array();

		if($row[0]['total'] > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the character data that belongs to the character
	 */
	public function getCharacter()
	{
		$this->connect();

		$query = $this->connection->query(query('get_character', $this->realmId), array($this->id));
		
		if($query && $query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row[0];
		}
		else
		{
			return array(
						"account" => "",
						"name" => "",
						"race" => "",
						"class" => "",
						"gender" => "",
						"level" => ""
					);
		}
	}

	/**
	 * Get the character stats that belongs to the character
	 */
	public function getStats()
	{
		$this->connect();

		$query = $this->connection->query("SELECT ".allColumns("character_stats", $this->realmId)." FROM ".table("character_stats", $this->realmId)." WHERE ".column("character_stats", "guid", false, $this->realmId)."= ?", array($this->id));

		if($query && $query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row[0];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Load items that belong to the character 
	 */
	public function getItems()
	{
		$this->connect();

		$query = $this->connection->query(query("get_inventory_item", $this->realmId), array($this->id));

		if($query && $query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row;
		}
		else
		{
			return false;
		}
	}
	
	public function getRecentAchievements($count = 5)
	{
		$this->connect();
		
		$statements['trinity'] 		= 
		$statements['trinity_cata'] = 
		$statements['trinity_mop'] = 
		$statements['trinity_wod'] = 
		$statements['trinity_legion'] = 
		$statements['trinity_bfa'] =
		$statements['arkcore'] 		=
		$statements['azerothcore']  =
		$statements['arcemu']		=
		$statements['mangos'] 		=
		$statements['skyfire'] 		= "SELECT `achievement`, `date` FROM `character_achievement` WHERE `guid` = ? ORDER BY date DESC LIMIT ?;";
		
		$statements['mangosr2'] 	= "";
		
		$query = $this->connection->query($statements[$this->getEmulatorString()], array($this->id, $count));

		if ($query && $query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			return $result;
		}
		
		unset($query);
		
		return false;
	}
	
	public function getAchievementInfo($id)
	{
		$query = $this->db->query("	SELECT 
										`extra_data_achievement`.`id`, 
										`extra_data_achievement`.`name`, 
										`extra_data_achievement`.`description`, 
										`extra_data_achievement`.`points`, 
										`extra_data_achievement`.`icon`,  
										`extra_data_icons`.`iconname` 
									FROM `extra_data_achievement` 
									LEFT JOIN `extra_data_icons` ON `extra_data_icons`.`id` = `extra_data_achievement`.`icon` 
									WHERE `extra_data_achievement`.`id` = ? 
									LIMIT 1;", array($id));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0];
		}
		
		unset($query);
		
		return false;
	}
	
	public function getGuild()
	{
		$this->connect();

		$query = $this->connection->query("SELECT ".column("guild_member", "guildid", true, $this->realmId)." FROM ".table("guild_member", $this->realmId)." WHERE ".column("guild_member", "guid", false, $this->realmId)."= ?", array($this->id));

		if($this->connection->_error_message())
		{
			die($this->connection->_error_message());
		}

		if($query && $query->num_rows() > 0)
		{
			$row = $query->result_array();

			return $row[0]['guildid'];
		}
		else
		{
			$query2 = $this->connection->query("SELECT ".column("guild", "guildid", true, $this->realmId)." FROM ".table("guild", $this->realmId)." WHERE ".column("guild", "leaderguid", false, $this->realmId)."= ?", array($this->id));

			if($this->connection->_error_message())
			{
				die($this->connection->_error_message());
			}

			if($query2 && $query2->num_rows() > 0)
			{

				$row2 = $query2->result_array();

				return $row2[0]['guildid'];
			}
			else
			{
				return false;
			}
		}
	}

	public function getGuildName($id)
	{
		if(!$id)
		{
			return '';
		}
		else
		{
			$this->connect();

			$query = $this->connection->query("SELECT ".column("guild", "name", true, $this->realmId)." FROM ".table("guild", $this->realmId)." WHERE ".column("guild", "guildid", false, $this->realmId)."= ?", array($id));

			if($query && $query->num_rows() > 0)
			{
				$row = $query->result_array();

				return $row[0]['name'];
			}
			else
			{
				return false;
			}
		}
	}
	
	########### PROFESSIONS #############################
	
	public function getProfessions()
	{
		//Define the professions (skill ids) we need
		$professionsString = "164,165,171,182,186,197,202,333,393,755,773,129,185,356,794";
		
		//Handle arcemu
		if ($this->getEmulatorString() == 'arcemu')
		{
			$result = $this->getArcemuProfessions();
		}
		else
		{
			$this->connect();
			
			$statements['trinity'] 		= 
			$statements['trinity_cata'] = 
			$statements['trinity_mop'] = 
			$statements['trinity_wod'] = 
			$statements['trinity_legion'] = 
			$statements['trinity_bfa'] =
			$statements['arkcore'] 		=
			$statements['azerothcore']  =
			$statements['mangos'] 		=
			$statements['skyfire'] 		= "SELECT `skill`, `value`, `max` FROM `character_skills` WHERE `guid` = ? AND `skill` IN(".$professionsString.");";
			
			$statements['mangosr2'] 	= "";
			
			$query = $this->connection->query($statements[$this->getEmulatorString()], array($this->id));

			if ($query && $query->num_rows() > 0)
			{
				$result = $query->result_array();
			}
			else
			{
				$result = false;
			}
			
			unset($query);
		}
		
		if ($result)
		{
			//loop trough the records and get some more info
			foreach ($result as $key => $row)
			{
				if ($info = $this->getProfessionInfo((int)$row['skill']))
				{
					$result[$key] = array(
						'skill' 	=> $row['skill'],
						'value' 	=> $row['value'],
						'max' 		=> $row['max'],
						'name' 		=> $info['name'],
						'icon' 		=> $info['icon'],
						'category' 	=> $info['category']
					);
				}
			}
			
			return $result;
		}
		
		return false;
	}
	
	private function getArcemuProfessions()
	{
		//Define the professions (skill ids) we need
		$professions = array(164,165,171,182,186,197,202,333,393,755,773,129,185,356,794);
			
		$this->getArcemuCharacterDataIfNeeded();
			
		if ($this->storedData)
		{
			//get the skills string
			$Skills = $this->storedData['skills'];
			//strip the last ,
			$SkillList = rtrim($Skills, ';');
			//convert to array
			$SkillList = explode(';', $SkillList);
			
			$SkillArr = array();
			//Loop trought the skills and covert them to a nice array
			for ($i = 0; $i < (count($SkillList) / 3); $i++)
			{
				$SkillArr[] = array(
					'skill' => (int)$SkillList[$i * 3],
					'value' => (int)$SkillList[$i * 3 + 1],
					'max'	=> (int)$SkillList[$i * 3 + 2]
				);
			}
			
			//Filter the skills
			$temp = array();
			foreach ($SkillArr as $row)
			{
				if (in_array($row['skill'], $professions))
				{
					$temp[] = $row;
				}
			}
			//override the old SkillArr
			$SkillArr = $temp;
			unset($temp);
			
			return $SkillArr;
		}
		
		return false;
	}
	
	//We can store the information about professions in array
	private function getProfessionInfo($id)
	{
		$data = array(
			//Primary
			164	=> array('name' => 'Blacksmithing', 	'icon' => 'Trade_BlackSmithing',			'category' => 0),
			165	=> array('name' => 'Leatherworking', 	'icon' => 'Trade_LeatherWorking',			'category' => 0),
			171	=> array('name' => 'Alchemy', 			'icon' => 'Trade_Alchemy',					'category' => 0),
			182	=> array('name' => 'Herbalism', 		'icon' => 'Trade_Herbalism',				'category' => 0),
			186	=> array('name' => 'Mining', 			'icon' => 'Trade_Mining',					'category' => 0),
			197	=> array('name' => 'Tailoring', 		'icon' => 'Trade_Tailoring',				'category' => 0),
			202	=> array('name' => 'Engineering', 		'icon' => 'Trade_Engineering',				'category' => 0),
			333	=> array('name' => 'Enchanting', 		'icon' => 'Trade_Engraving',				'category' => 0),
			393	=> array('name' => 'Skinning', 			'icon' => 'INV_Misc_Pelt_Wolf_01',			'category' => 0),
			755	=> array('name' => 'Jewelcrafting', 	'icon' => 'INV_Misc_Gem_01',				'category' => 0),
			773	=> array('name' => 'Inscription', 		'icon' => 'INV_Inscription_Tradeskill01',	'category' => 0),
			//Secondery
			129	=> array('name' => 'First Aid', 		'icon' => 'Spell_Holy_SealOfSacrifice',		'category' => 1),
			185	=> array('name' => 'Cooking', 			'icon' => 'INV_Misc_Food_15',				'category' => 1),
			356	=> array('name'	=> 'Fishing',			'icon' => 'Trade_Fishing',					'category' => 1),
			794 => array('name' => 'Archaeology', 		'icon' => 'trade_archaeology',				'category' => 1),
		);
		
		if (isset($data[(int)$id]))
		{
			return $data[(int)$id];
		}
		
		return false;
	}
}