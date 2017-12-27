<?php
/**
* Admin panel
*/

class AdminPanel
{
	private $db;
	private $tpl;
	private $template_dir = ENGINE_DIR."/modules/12top/admin/theme/";
	function __construct($db)
	{
		require_once(ENGINE_DIR."/modules/12top/lib/fenom/Fenom.php");
		Fenom::registerAutoload(ENGINE_DIR."/modules/12top/lib/fenom");
		$this->db = $db;
		$this->tpl = new Fenom(new Fenom\Provider($this->template_dir));
		$this->tpl->setOptions(array(
		    "disable_cache" => true,
		    "force_verify" => false
		));
	}
	public function echoheader($header_title, $header_subtitle) {
		global $skin_header, $skin_footer, $member_id, $user_group, $js_array, $css_array, $config, $lang, $is_loged_in, $mod, $action, $langdate, $db, $dle_login_hash;

		if( !is_array( $header_subtitle )) $header_subtitle = array ( '' => $header_subtitle);
		
		$breadcrumb = array( "<li><a href=\"?mod=main\"><i class=\"fa fa-home position-left\"></i>{$lang['skin_main']}</a></li>" );

		foreach ($header_subtitle as $key => $value) {
			
			if($key) {
				$breadcrumb[] = "<li><a href=\"{$key}\">{$value}</a></li>";
			} else {
				$breadcrumb[] = "<li class=\"active\">{$value}</li>";
			}
		}

		$breadcrumb = implode('', $breadcrumb);

		include_once (ENGINE_DIR . '/skins/default.skin.php');
		
		$js = build_js($js_array);
		$css = build_css($css_array);
		
		$skin_header = str_replace( "{js_files}", $js, $skin_header );
		$skin_header = str_replace( "{css_files}", $css, $skin_header );
		$skin_not_logged_header = str_replace( "{js_files}", $js, $skin_not_logged_header );
		$skin_not_logged_header = str_replace( "{css_files}", $css, $skin_not_logged_header );
		
		if( $is_loged_in ) echo $skin_header;
		else echo $skin_not_logged_header;
	}

	public function echofooter() {
		global $is_loged_in, $skin_footer;

		if( $is_loged_in ) echo $skin_footer;
		else echo $skin_not_logged_footer;

	}

	public function show() {
		if(isset($_GET['action']) && $_GET['action']=='widget') { 
			if(isset($_POST['addwidget'])){
				$title = $this->db->safesql($_POST['title']);
				$sortby = $this->db->safesql($_POST['sortby']);
				$type = $this->db->safesql($_POST['type']);
				$count = intval($_POST['count']);
				if(!empty($title) && !empty($sortby) && !empty($type) && !empty($count)){
					$this->addWidget($title, $sortby, $type, $count);
				}else{
					header("Location: ?mod=12top&failed");
				}
			}else{
				if(!isset($_GET['edit']) && !isset($_GET['delete'])){
					$get_types = $this->db->query("SELECT id, name FROM ".PREFIX."_top_types");
					$data = array();
					while ($row = $this->db->get_row($get_types)) {
						$data['type_options'][$row['id']] = $row;
					}
					$get_widgets = $this->db->query("SELECT * FROM ".PREFIX."_top_widgets");
					while ($row = $this->db->get_row($get_widgets)) {
						$row['tag'] = '{include file="engine/modules/12top.php?id='.$row[id].'"}';
						$data['tableval'][$row[id]] = $row;
					}

					$this->tpl->display("widget.tpl", $data);
				}elseif(isset($_GET['edit'])){
					$id = intval($_GET['edit']);
					$check = $this->db->super_query("SELECT * FROM ".PREFIX."_top_widgets WHERE id='$id'");
					if($check){
						if(isset($_POST['editwidget'])){
							$title = $this->db->safesql($_POST['title']);
							$sortby = $this->db->safesql($_POST['sortby']);
							$type = $this->db->safesql($_POST['type']);
							$count = intval($_POST['count']);
							if(!empty($title) && !empty($sortby) && !empty($type) && !empty($count)){
								$this->editWidget($id, $title, $sortby, $type, $count);
							}else{
								header("Location: ?mod=12top&failed");
							}
						}
						$get_types = $this->db->query("SELECT id, name FROM ".PREFIX."_top_types");
						$data = array();
						while ($row = $this->db->get_row($get_types)) {
							$check['type_options'][$row['id']] = $row;
						}
						$this->tpl->display("widget_edit.tpl", $check);
					}else{
						header("Location: ?mod=12top&failed");
					}
				}elseif(isset($_GET['delete'])){
					$id = intval($_GET['delete']);
					$check = $this->db->super_query("SELECT id FROM ".PREFIX."_top_widgets WHERE id='$id'");
					if($check){
						$this->db->query("DELETE FROM ".PREFIX."_top_widgets WHERE id='$id'");
						header("Location: ?mod=12top&success");
					}else{
						header("Location: ?mod=12top&failed");
					}
				}
			}
		}
		else if(isset($_GET['action']) && $_GET['action']=='settings') $this->tpl->display("settings.tpl");
		else if(isset($_GET['action']) && $_GET['action']=='custom') {
			if(isset($_POST['addcustom'])){
				$title = $this->db->safesql($_POST['title']);
				$table = $this->db->safesql($_POST['table']);
				$type = $this->db->safesql($_POST['type']);
				$sel_value = $this->db->safesql($_POST['sel_value']);

				if(!empty($title) && !empty($table) && !empty($type) && !empty($sel_value)){
					$this->addCusomType($title, $table, $type, $sel_value);
				}else{
					header("Location: ?mod=12top&failed");
				}
			}else{
				if(!isset($_GET['edit']) && !isset($_GET['delete'])){
					$get_types = $this->db->query("SELECT * FROM ".PREFIX."_top_types");
					while($row = $this->db->get_row($get_types)){
						$data['tableval'][$row['id']] = $row;
					}
					$data['prefix'] = PREFIX;
					$this->tpl->display("custom.tpl", $data);
				}else if(isset($_GET['edit'])){
					$id = intval($_GET['edit']);
					$check = $this->db->super_query("SELECT * FROM ".PREFIX."_top_types WHERE id='$id'");
					if($check){
						if(isset($_POST['editcustom'])){
							$title = $this->db->safesql($_POST['title']);
							$table = $this->db->safesql($_POST['table']);
							$type = $this->db->safesql($_POST['type']);
							$sel_value = $this->db->safesql($_POST['sel_value']);
							if(!empty($title) && !empty($table) && !empty($type) && !empty($sel_value)){
								$this->editCusomType($id, $title, $table, $type, $sel_value);
							}else{
								header("Location: ?mod=12top&failed");
							}
						}

						$this->tpl->display("custom_edit.tpl", $check);
					}else{
						header("Location: ?mod=12top&failed");
					}
				}else if(isset($_GET['delete'])){
					$id = intval($_GET['delete']);
					$check = $this->db->super_query("SELECT id FROM ".PREFIX."_top_types WHERE id='$id'");
					if($check){
						$this->db->query("DELETE FROM ".PREFIX."_top_types WHERE id='$id'");
						header("Location: ?mod=12top&success");
					}else{
						header("Location: ?mod=12top&failed");
					}
				}
			}
		}
		else if(isset($_GET['action']) && $_GET['action']=='info') $this->tpl->display("info.tpl");
		else if(isset($_GET['success'])) $this->tpl->display("success.tpl");
		else if(isset($_GET['failed'])) $this->tpl->display("failed.tpl");
		else $this->tpl->display("main.tpl");
	}

	private function addCusomType($title, $table, $type, $sel_value){
		$query = $this->db->query("INSERT INTO ".PREFIX."_top_types (`name`,`sel_table`,`sel_value`,`type`) VALUES ('$title','$table','$sel_value','$type')");
		if($query){
			header("Location: ?mod=12top&success");
		}else{
			header("Location: ?mod=12top&failed");
		}
	}

	private function editCusomType($id, $title, $table, $type, $sel_value){
		$query = $this->db->query("UPDATE ".PREFIX."_top_types SET `name`='$title',`sel_table`='$table',`sel_value`='$sel_value',`type`='$type' WHERE id='$id'");
		if($query){
			header("Location: ?mod=12top&success");
		}else{
			header("Location: ?mod=12top&failed");
		}
	}

	private function addWidget($title, $sortby, $type, $count){
		$query = $this->db->query("INSERT INTO ".PREFIX."_top_widgets (`name`,`type`,`sortby`,`count`) VALUES ('$title','$type','$sortby','$count')");
		if($query){
			header("Location: ?mod=12top&success");
		}else{
			header("Location: ?mod=12top&failed");
		}
	}

	private function editWidget($id, $title, $sortby, $type, $count){
		$query = $this->db->query("UPDATE ".PREFIX."_top_widgets SET `name`='$title',`type`='$type',`sortby`='$sortby',`count`='$count' WHERE id='$id'");
		if($query){
			header("Location: ?mod=12top&success");
		}else{
			header("Location: ?mod=12top&failed");
		}
	}
}
?>