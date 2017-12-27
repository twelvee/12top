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
		$this->tpl->setOptions(Fenom::DISABLE_CACHE);
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
			$get_types = $this->db->query("SELECT id, name FROM ".PREFIX."_top_types");
			$data = array();
			while ($row = $this->db->get_row($get_types)) {
				$data['type_options'][$row['id']] = $row;
			}
			$get_widgets = $this->db->query("SELECT * FROM ".PREFIX."_top_widgets");
			while ($row = $this->db->get_row($get_widgets)) {
				$data['tableval'][$row[id]] = $row;
			}
			$this->tpl->display("widget.tpl", $data);
		}
		else if(isset($_GET['action']) && $_GET['action']=='settings') $this->tpl->display("settings.tpl");
		else if(isset($_GET['action']) && $_GET['action']=='custom') $this->tpl->display("custom.tpl");
		else if(isset($_GET['action']) && $_GET['action']=='info') $this->tpl->display("info.tpl");
		else $this->tpl->display("main.tpl");
	}
}
?>