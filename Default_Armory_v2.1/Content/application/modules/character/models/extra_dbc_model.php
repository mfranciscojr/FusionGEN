<?php

class extra_dbc_model extends CI_Model
{
	private $runtimeCache = array();
	
	public function __construct()
	{
		parent::__construct();
	}

	public function getTalentTabs($class)
	{
		$query = $this->db->query("	SELECT `extra_data_talenttab`.`id`, `extra_data_talenttab`.`name`, `extra_data_talenttab`.`classes`, `extra_data_talenttab`.`order`, `extra_data_icons`.`iconname` AS icon 
									FROM `extra_data_talenttab` 
									INNER JOIN `extra_data_icons` ON `extra_data_talenttab`.`spellicon` = `extra_data_icons`.`id`
									WHERE `extra_data_talenttab`.`classes` = ? 
									ORDER BY `order` ASC;", 
									array($class));
		
		if($query && $query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		
		unset($query);
		
		return false;
	}
	
	public function getTalentsForTab($tab)
	{
		$this->db->select('*')->from('extra_data_talent')->where(array('tab' => $tab))->order_by('id', 'ASC');
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result;
		}
		
		unset($query);
		
		return false;
	}
	
	public function getSpellIcon($spell)
	{
		$query = $this->db->query("SELECT `iconname` AS icon FROM `extra_data_icons` WHERE `id` = (SELECT `extra_data_spell`.`spellicon` FROM `extra_data_spell` WHERE `extra_data_spell`.`spellID` = ?) LIMIT 1;", array($spell));
		
		if($query && $query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0];
		}
		
		unset($query);
		
		return false;
	}
	
	public function getGlyphInfo($id)
	{
		$query = $this->db->query("	SELECT `extra_data_glyphproperties`.`id`, `extra_data_glyphproperties`.`spellid`, `extra_data_glyphproperties`.`typeflags`, `extra_data_spell`.`spellname` AS name
									FROM `extra_data_glyphproperties` 
									INNER JOIN `extra_data_spell` on `extra_data_glyphproperties`.`spellid` = `extra_data_spell`.`spellID`  
									WHERE `extra_data_glyphproperties`.`id` = ? 
									LIMIT 1;", array($id));
		
		if($query->num_rows() > 0)
		{
			$result = $query->result_array();
			return $result[0];
		}
		
		unset($query);
		
		return false;
	}
	
	public function getEnchantmentInfo($id)
	{
		if (isset($this->runtimeCache[$id]))
		{
			return $this->runtimeCache[$id][0];
		}
		
		$query = $this->db->query("	SELECT 
										`extra_data_spellitemenchantment`.`id`, 
										`extra_data_spellitemenchantment`.`description`, 
										`extra_data_spellitemenchantment`.`GemID`, 
										`extra_data_spellitemenchantment`.`EnchantmentCondition`, 
										`extra_data_gemproperties`.`color` 
									FROM `extra_data_spellitemenchantment` 
									LEFT JOIN `extra_data_gemproperties` ON `extra_data_gemproperties`.`SpellItemEnchantement` = `extra_data_spellitemenchantment`.`id` 
									WHERE `extra_data_spellitemenchantment`.`id` = ? 
									LIMIT 1;", array($id));
									
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			//save to cache
			$this->runtimeCache[$id] = $result;
			
			return $result[0];
		}
		
		unset($query);
		
		return false;
	}
	
	public function getEnchantmentConditions($ConditionEntry)
	{
		$query = $this->db->query("	SELECT *
									FROM `extra_data_spellitemenchantmentcondition` 
									WHERE `id` = ? 
									LIMIT 1;", array($ConditionEntry));
									
		if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
			
			return $result[0];
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
}