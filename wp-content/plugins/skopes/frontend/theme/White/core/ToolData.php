<?php

require_once ABSPATH.WPINC.'/pluggable.php';
require_once ABSPATH.WPINC.'/registration.php';

class ToolData
{
	public function saveSettings($settings)
	{
		if(!isset($_SESSION['ID']))
			return $this->fail("NOT_LOGGED_IN");

		$user_id = $_SESSION["ID"];
		$this->addupdate_user_meta($user_id, "lc_tool_settings", $settings);
		return $this->succeed();
	}

	public function loadSettings()
	{
		if(!isset($_SESSION['ID']))
			return $this->fail("NOT_LOGGED_IN");

		$user_id = $_SESSION["ID"];
		$result = $this->succeed();
		$tmp = get_user_meta($user_id, "lc_tool_settings");
		$result['settings'] = $tmp[0];
		return $result;
	}

	public function saveToolData($settings)
	{
		if(!isset($_SESSION['ID']))
			return $this->fail("NOT_LOGGED_IN");

		$user_id = $_SESSION["ID"];
		$this->addupdate_user_meta($user_id, "lc_tool_data", $settings);
		return $this->succeed();
	}

	public function loadToolData()
	{
		if(!isset($_SESSION['ID']))
			return $this->fail("NOT_LOGGED_IN");

		$user_id = $_SESSION["ID"];
		$tmp = get_user_meta($user_id, "lc_tool_data");
		$result = $this->succeed();
		if(!$tmp || count($tmp)==0)
		{
			$result['defaults'] = $this->getDefaultSettings();
			$result['tooldata'] = $this->getDefaultArray();
		}
		else
			$result['tooldata'] = $tmp[0];
		return $result;
	}

	public function addupdate_user_meta($user_id, $metaname, $metadata)
	{
		if(!add_user_meta($user_id, $metaname, $metadata, true))
			update_user_meta($user_id, $metaname, $metadata);
	}

	public function fail($message)
	{
		$result['success'] = false;
		$result['message'] = $message;
		return $result;
	}

	public function succeed()
	{
		$result['success'] = true;
		$result['message'] = 'OK';
		return $result;
	}

	public function getDefaultNames()
	{
		return array("Objective", "Territory", "Base_Type", "Number_of_members", "Database", "Happiness_with_Database");
	}

	public function getUnorderedDefaults()
	{
		global $wpdb;
		$return_value = array();

		$table_name = $wpdb->prefix."specdoc_defaults";

		$sql = "SELECT * FROM ".$table_name." ORDER BY name";
		$results = $wpdb->get_results($sql, ARRAY_A);
		foreach ($results as $result)
		{
			$return_value[$result['name']] = unserialize($result['text']);
		}
		return $return_value;
	}

	public function getOrderedDefaults()
	{
		$result = array();
		$defaults = $this->getUnorderedDefaults();
		foreach($this->getDefaultNames() as $defaultname)
		{
			$tmp = $defaults[$defaultname];
			if($tmp == null) $tmp = array();
			array_push($result, $tmp);
		}
		return $result;
	}

	public function getTextField($name)
	{
		global $wpdb;
		$return_value = array();

		$table_name = $wpdb->prefix."specdoc_defaults";

		$sql = 'SELECT * FROM '.$table_name.' where name="'.$name.'"';
		$results = $wpdb->get_results($sql, ARRAY_A);
		foreach ($results as $result)
		{
			return unserialize($result['text']);
		}
		return "";
	}

	public function getDefaultSettings()
	{
		$tmp = $this->getOrderedDefaults();
		array_push($tmp,
			array($this->getTextField("Line_Item_About_Title"),
				  $this->getTextField("Line_Item_About_Bodytext")));
		array_push($tmp,
			array(0xB9E6BD, 0x5E96C5, 0x999999, 0x4B8553, 0x757575, 0x000000, 0xFFFFFF));

		$cat = Array();

		global $wpdb;

		$table_name1 = $wpdb->prefix."specdoc_categories";
		$table_name2 = $wpdb->prefix."specdoc_line_items";

		$sql1 = 'SELECT * FROM '.$table_name1
			.' WHERE enabled=1 AND (foruser IS NULL OR foruser='.$_SESSION['ID']
			.') ORDER BY category_id';

		$results = $wpdb->get_results($sql1, ARRAY_A);
		$outer_array = array();
		foreach($results as $result)
		{
			$inner_array = array($result['category_id'], $result['name'], $result['text'], $result['comment']);
			$line_array = array();
			$sql2 = 'SELECT * FROM '.$table_name2.' WHERE enabled=1 AND (foruser IS NULL OR foruser='
				.$_SESSION['ID'].') AND category_id='.$result['category_id'].' ORDER BY line_item_id';
			$line_results = $wpdb->get_results($sql2, ARRAY_A);
			foreach($line_results as $line_result)
			{
				array_push($line_array, array($line_result['line_item_id'], $line_result['name'], $line_result['text'], $line_result['comment']));
			}
			array_push($inner_array, $line_array);
			array_push($outer_array, $inner_array);
		}

		array_push($tmp, $outer_array);
		array_push($tmp, array($this->getTextField("Help_Title"), $this->getTextField("Help_Bodytext")));
		array_push($tmp, array("INSTRUCTIONS_TITLE", $this->getTextField("INSTRUCTIONS_TITLE")));
		array_push($tmp, array("INSTRUCTIONS_BODY", $this->getTextField("INSTRUCTIONS_BODY")));
		array_push($tmp, array("INSTRUCTIONS_VIDEO_URL", $this->getTextField("INSTRUCTIONS_VIDEO_URL")));
		array_push($tmp, array("INSTRUCTIONS_LEGEND_TITLE", $this->getTextField("INSTRUCTIONS_LEGEND_TITLE")));
		array_push($tmp, array("INSTRUCTIONS_LEGEND_BLUE", $this->getTextField("INSTRUCTIONS_LEGEND_BLUE")));
		array_push($tmp, array("INSTRUCTIONS_LEGEND_GREEN", $this->getTextField("INSTRUCTIONS_LEGEND_GREEN")));
		array_push($tmp, array("INSTRUCTIONS_LEGEND_GREY", $this->getTextField("INSTRUCTIONS_LEGEND_GREY")));
		return $tmp;
	}

	public function getDefaultArray()
	{
		return array(
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
			array(0, 0, array(0, 0, 0, 0, 0), array($r,$r,$r,$r,$r), array($r,$r,$r,$r,$r), "", "", "", 0, 0, 0),
		);
	}

}

