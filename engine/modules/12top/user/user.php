<?php
/*
=====================================================
 Powered by Twelvee. 
-----------------------------------------------------
 VK: /id150454169
=====================================================
*/

class UserView
{
	private $tpl;
	private $db;
	private $widget;
	private $config;
	function __construct($db, $widget, $config)
	{
		require_once(ENGINE_DIR."/modules/12top/lib/fenom/Fenom.php");
		Fenom::registerAutoload(ENGINE_DIR."/modules/12top/lib/fenom");
		$this->db = $db;
		$this->tpl = new Fenom(new Fenom\Provider("templates/".$config['skin']."/12top/"));
		$this->tpl->setOptions(array(
		    "disable_cache" => true,
		    "force_verify" => false
		));
		$this->widget = intval($widget);
	}

	public function result(){
		$id = $this->widget;
		$check = $this->db->super_query("SELECT * FROM ".PREFIX."_top_widgets WHERE id='$id'");
		if($check){
			$get_type_info = $this->db->super_query("SELECT * FROM ".PREFIX."_top_types WHERE id='$check[type]'");
			if($check['sortby']=="valdesc"){
				$sort = "DESC";
			}else{
				$sort = "ASC";
			}
			$query = $this->db->query("SELECT * FROM ".$get_type_info[sel_table]." ORDER by ".$get_type_info[sel_value]. " ".$sort." LIMIT ".$check[count]);
			while($tt = $this->db->get_row($query)){
				$toTempl['info'][$tt[id]] = $tt;
			}
			return $this->tpl->display("top.tpl", $toTempl);
		}else{
			return "Топ не найден.";
		}
	}
}
?>