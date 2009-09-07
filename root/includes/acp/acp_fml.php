<?php

/**
*
* @author Ian Taylor, Platinum2007 iantaylor603@gmail.com - http://street-steeze.com
*
* @package Simple Profile comments
* @version 1.6.1-RC
* @copyright (c) Street Steeze, Ian-Taylor.ca street-steeze.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
// php extension using
if (!defined('IN_PHPBB'))
{
	exit;
}
class acp_fml
{
   var $u_action;
   var $new_config;
   function main($id, $mode)
   {
      global $db, $user, $auth, $template;
      global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
      switch($mode)
      {
         case 'index':
            $this->page_title = 'ACP_FML';
            $this->tpl_name = 'acp_fml';
            break;
            
       }
       if(isset($_POST['submit']))
       {
			set_config('enable_fml', request_var('enable_fml', 1));
			set_config('allow_anon', request_var('allow_anon', 1));
			set_config('allow_comments', request_var('allow_comments', 1));
			set_config('fml_av_size', request_var('fml_av_size', 1));
			set_config('max_thresh', request_var('max_thresh', 10));
			set_config('min_thresh', request_var('min_thresh', -10));
			set_config('good_colour', request_var('good_colour', ''));			
		}
		
	$template->assign_vars(array(
			
			'ENABLE_FML'		=> $config['enable_fml'],
			'ALLOW_ANON'		=> $config['allow_anon'],
			'ALLOW_COMMENTS'	=> $config['allow_comments'],
			'FML_AV_SIZE'		=> $config['fml_av_size'],
			'MIN_THRESH'		=> $config['min_thresh'],
			'MAX_THRESH'		=> $config['max_thresh'],
			'GOOD_COLOUR'		=> $config['good_colour'],


			));
					
	}

}

?>