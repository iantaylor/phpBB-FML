<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/functions_fml.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup('mods/fml');

page_header($user->lang['FML_TITLE']);

$template->set_filenames(array(
    'body' => 'fml_body.html',
));

$mode = request_var('mode', '');
$id   = request_var('id', 0);
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
$comment_to = request_var('comment_to', 0);
$category = request_var('cat', '');

define('FML_TABLE',					$table_prefix . 'fml');
define('FML_RATE_TABLE',			$table_prefix . 'fml_rate');
define('FML_COMMENT_TABLE',			$table_prefix . 'fml_comment');
define('FML_COMMENT_RATE_TABLE',	$table_prefix . 'fml_comment_rates');

if(!$config['enable_fml'])
{
	trigger_error($user->lang['FML_CLOSED']);
}

// Some links , some settings and some other needed stuff.
$template->assign_vars(array(
	'MODE'				=> $mode,
	'U_MAIN' 			=> append_sid("{$phpbb_root_path}fml.$phpEx"),
	'U_POST_MODE' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=post"),
	'U_FML_RANDOM' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife&amp;type=random"),
	'U_TOP_WEEK' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife&amp;type=top_week"),
	'U_TOP_DAY' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife&amp;type=top_day"),
	'U_TOP_MONTH' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife&amp;type=top_month"),
	'U_TOP_FML' 		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife&amp;type=top_fml"),
	'U_POST_COMMENT' 	=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=post_comment"),
	'U_DELETE_RATES' 	=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=delete_rates&amp;id=".$id),
	'U_DELETE_COMMENTS' => append_sid("{$phpbb_root_path}fml.$phpEx", "mode=delete_all_comments&amp;id=".$id),
	'U_CAT'				=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=cat&amp;cat="),
	'U_RANDOM' 			=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=random"),
	'U_FML_ORIG'		=> append_sid("{$phpbb_root_path}fml.$phpEx", "mode=fmylife"),
	
	'DELETE_IMG' 		=> $user->img('icon_post_delete', 'DELETE_POST'),
	'FML_ID'			=> $id,
	'ENABLE_COMMENT'	=> ($config['allow_comments'] ? true : false), 
	'GOOD_COLOUR'		=>  $config['good_colour'],
	'ALLOW_ANON'		=> ($config['allow_anon']) ? true : false,

));


switch($mode)
{

	
	case 'post' :
	
		include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
		generate_smilies('inline', 2);
		display_custom_bbcodes();

		$user->add_lang('posting');
		
		$template->assign_var('S_BBCODE_ALLOWED',true);
		if(!empty($_POST) && $user->data['user_id'] != ANONYMOUS)
		{
		
			$fml_text = utf8_normalize_nfc(request_var('fml_text', '', true));
			$uid = $bitfield = $options = ''; 
			$allow_bbcode = $allow_urls = $allow_smilies = true;
			generate_text_for_storage($fml_text, $uid, $bitfield, $options, $allow_bbcode, $allow_urls, $allow_smilies);
		
			$sql_ary = (array(
			'fml_text'        			=> $fml_text,
			'fml_poster'        		=> utf8_normalize_nfc($user->data['username']),
			'fml_anon'         			=> request_var('fml_anon', 1),
			'fml_date'					=> time(),
			'category'					=> request_var('cat', ''),
   			'bbcode_uid'        		=> $uid,
    		'bbcode_bitfield'   		=> $bitfield,
    		'bbcode_options'    		=> $options,
    		
			));

			$sql = 'INSERT INTO  '.FML_TABLE .$db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
			
			//all done lets go back to the main page
			redirect(append_sid("{$phpbb_root_path}fml.$phpEx"));

		}

	break;
	
	case 'post_comment' :
	
		if(!empty($_POST) && $user->data['user_id'] != ANONYMOUS)
		{
			$sql_ary = (array(
			'fml_comment_text'        	=> utf8_normalize_nfc(request_var('fml_comment_text', '', true)),
			'fml_commenter'        		=> $user->data['user_id'],
			'fml_comment_to'         	=> request_var('comment_to_id', ''),
			'fml_comment_date'			=> time(),
			'fml_comment_rate'			=> 0,

			));

			$sql = 'INSERT INTO  '.FML_COMMENT_TABLE .$db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
			
			//all done lets go back to the main page
			redirect($referer);

		}
		else
		{
			trigger_error($user->lang['MUST_LOGIN_COMMENT']);
		}
	break;
	// delete of FML's
	case 'delete' :
		
		if($auth->acl_get('a_'))
		{
		
			if (confirm_box(true))
			{
				// handle the FML delete
				delete_fml($id);
				// No need for the rates on this FML any more
				delete_rates($id);
				// No need for the comments on the FML any more
				delete_all_comments($id);
							
				redirect(append_sid("{$phpbb_root_path}/fml.php"));
			}
			else
			{
				
				confirm_box(false, $user->lang['DELETE_CONFIRM']);
				redirect(append_sid("{$phpbb_root_path}/fml.php"));
			}
		}
		else
		{
	 		trigger_error('NOT_AUTHORISED');
		}
	
	
	break;
	
	case 'delete_rates' :

		if($auth->acl_get('a_'))
		{
		
			if (confirm_box(true))
			{
				// handle the delete of all rates
				delete_rates($id);	
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$id));
		
			}
			else
			{
				confirm_box(false, $user->lang['DELETE_CONFIRM_RATES']);
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$id));
			}
		}
		else
		{
	 		trigger_error('NOT_AUTHORISED');
		}
		
	
	break;
	
	case 'delete_all_comments' :

		if($auth->acl_get('a_'))
		{
		
			if (confirm_box(true))
			{
				// handle the delete of all rates
				delete_all_comments($id);	
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$id));
		
			}
			else
			{
				confirm_box(false, $user->lang['DELETE_CONFIRM_COMMENTS']);
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$id));
			}
		}
		else
		{
	 		trigger_error('NOT_AUTHORISED');
		}
		
	
	break;
		
	case 'delete_comment' :
		if($auth->acl_get('a_'))
		{
		
			if (confirm_box(true))
			{
				// handle the delete
				delete_comment($id);				
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$comment_to));
			}
			else
			{
				
				confirm_box(false, $user->lang['DELETE_CONFIRM_COMMENTS']);
				redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$comment_to));
			}
		}
		else
		{
	 		trigger_error('NOT_AUTHORISED');
		}
	break;
	
	case 'rate' :
	
	if(!get_who_rated($user->data['user_id'], $id))
	{
	
		$verd = request_var('verd', '');
		
		if($verd == 'deserve')
		{
			$agree = 0;
			$deserve = 1;
		}
		else
		{
			$agree = 1;
			$deserve = 0;
		}
	
		$sql_ary = (array(
			'fml_id'        			=> request_var('id', 0),
			'fml_rater_id'        		=> $user->data['user_id'],
			'fml_agree'         		=> $agree,
			'fml_deserve'				=> $deserve,

		));

			$sql = 'INSERT INTO  '.FML_RATE_TABLE .$db->sql_build_array('INSERT', $sql_ary);
			$db->sql_query($sql);
			
			redirect($referer);
		}
		else
		{
			trigger_error($user->lang['ALREADY_RATED']);
		}	
	break;
	
	case 'rate_comment' :
	
		if(!get_who_rated_comment($user->data['user_id'], $id))
		{
			rate_comment($id);
			redirect(append_sid("{$phpbb_root_path}/fml.php?mode=comment&amp;id=".$comment_to));
		}
		else
		{
			trigger_error($user->lang['ALREADY_RATED']);

		}	

	break;
	
	case 'comment' :
	
	
			// get zee FML
		$sql = $db->sql_build_query('SELECT', array(
			'SELECT'	=> ' f.*, u.user_id, u.username, u.user_colour, user_avatar, user_avatar_height, user_avatar_type, user_avatar_width',
			'FROM'		=> array(
				FML_TABLE				=> 'f',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'u.username = f.fml_poster',		
				)
			),
			'WHERE'		=> 'u.username = f.fml_poster and id='.$id,
		));
		
		$result	 = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);

		// check if there is actually a FML under the ID requested, if not throw a error.
		if(!$row['id'])
		{
			trigger_error($user->lang['NOT_FOUND']);
		}
			$av_size = 50;
			$avatar_thumb = ($row['user_avatar']) ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? $av_size : ($av_size / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? $av_size : ($av_size / $row['user_avatar_width']) * $row['user_avatar_height']) : '';
		
		
			$username = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
			$username_full = ($row['fml_anon']) ? $username : $user->lang['ANONYMOUS'];
			$template->assign_vars(array(
				'FML_TEXT'		=>  generate_text_for_display($row['fml_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
				'FML_POSTER'	=> 	$username_full,
				'TOTAL_AGREE'	=>  get_total_agree($row['id']),
				'TOTAL_DESERVE' =>  get_total_deserve($row['id']),
				'FML_DATE'		=>  $user->format_date($row['fml_date']),
				'U_FML_DELETE'  =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=delete&amp;id=".$row['id']),
				'U_FML_DESERVE' =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate&amp;verd=deserve&amp;id=".$row['id']),	
				'U_FML_AGREE'  	=>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate&amp;verd=agree&amp;id=".$row['id']),
				'HAS_RATED'		=>  get_who_rated($user->data['user_id'], $row['id']),	
				'IS_POSTER'		=>  ($row['user_id'] == $user->data['user_id']) ? true:false,	
				'TOTAL_COMMENTS' => get_total_comments($row['id']),	
				'AVATAR_THUMB'	=>  ($row['fml_anon'] || !$config['allow_anon']) ? $avatar_thumb : '',

	
			));
			
		$db->sql_freeresult($result);
		
		// get the comments
		$sql = $db->sql_build_query('SELECT', array(
			'SELECT'	=> ' c.*, u.user_id, u.username, u.user_colour, u.user_avatar, u.user_avatar_width, u.user_avatar_type, u.user_avatar_height',
			'FROM'		=> array(
				FML_COMMENT_TABLE		=> 'c',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'u.user_id = c.fml_commenter',		
				)
			),
			'WHERE'		=> 'u.user_id = c.fml_commenter AND c.fml_comment_to ='.$id,
			'ORDER_BY'	=> 'c.fml_comment_id DESC'
		));
		$result	 = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{

			$username = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
			$av_size = $config['fml_av_size'];
			$avatar =  ($row['user_avatar']) ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? $av_size : ($av_size / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? $av_size : ($av_size / $row['user_avatar_width']) * $row['user_avatar_height']) : '';


			$template->assign_block_vars('comment', array(
				'FML_COMMENT_TEXT'		=>  $row['fml_comment_text'],
				'FML_COMMENT_POSTER'	=> 	$username,
				'FML_COMMENT_DATE'		=>  $user->format_date($row['fml_comment_date']),
				'U_COMMENT_DELETE'  	=>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=delete_comment&amp;id=".$row['fml_comment_id']."&amp;comment_to=".$row['fml_comment_to']),
				'U_RATE_COMMENT_POS' =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate_comment&amp;rate=pos&amp;id=".$row['fml_comment_id']."&amp;comment_to=".$row['fml_comment_to']),	
				'U_RATE_COMMENT_NEG' =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate_comment&amp;rate=neg&amp;id=".$row['fml_comment_id']."&amp;comment_to=".$row['fml_comment_to']),	
				'FML_AVATAR'			=>  $avatar,
				'AGREES'				=> $row['fml_comment_rate'],
				'COMMENT_ID'			=> $row['fml_comment_id'],
				'BELOW_MAX'				=> ($row['fml_comment_rate'] < $config['min_thresh']) ? true : false,
				'GOOD_COMMENT'				=> ($row['fml_comment_rate'] > $config['max_thresh']) ? true : false,
				'HAS_RATED_COMMENT'		=> get_who_rated_comment($user->data['user_id'], $row['fml_comment_id']), 
	
			));
		}

		$db->sql_freeresult($result);


	break;
	
	case 'fmylife' :
	
	$type = request_var('type', '');
	$lang = request_var('lang', 'en');
	
	if($type == 'random')
	{
		$xml = file_get_contents('http://api.betacie.com/view/random?language='.$lang);
	}
	elseif($type == 'top_fml')
	{
		$xml = file_get_contents('http://api.betacie.com/view/top?language='.$lang);
	}
	elseif($type == 'top_week')
	{
		$xml = file_get_contents('http://api.betacie.com/view/top_week?language='.$lang);
	}
	elseif($type == 'top_day')
	{
		$xml = file_get_contents('http://api.betacie.com/view/top_day?language='.$lang);
	}
	elseif($type == 'top_month')
	{
		$xml = file_get_contents('http://api.betacie.com/view/top_month?language='.$lang);
	}
	else
	{
		$xml = file_get_contents('http://api.betacie.com/view/last?language='.$lang);
	}
	$xml = simplexml_load_string($xml);
	foreach ( $xml->items->item as $fml )
	{


			$template->assign_block_vars('fml', array(
				'FML_TEXT'		=>  $fml->text,
				'FML_POSTER'	=> 	$fml->author,
				'FML_DATE'		=>  $user->format_date(strtotime($fml->date)),
				'TOTAL_DESERVE' =>  $fml->deserved,
				'TOTAL_AGREE'	=>  $fml->agree,
				
				));

	}
	
	break;
	

	default:
		// get zee FML's
		if($mode == 'cat')
		{
			$where =  "u.username = f.fml_poster AND category = '$category'";
			$limit 	= 15; 
			$order = ' f.id DESC';


		}
		elseif($mode == 'random')
		{
			$where = 'u.username = f.fml_poster ';
			$order = ' RAND()';
			$limit = 1;
		}
		else
		{
			$where = 'u.username = f.fml_poster ';
			$order = ' f.id DESC';
			$limit 	= 15; 


		}
			$sql_array =  array(
			'SELECT'	=> ' f.*, u.user_id, u.username, u.user_colour, user_avatar, user_avatar_height, user_avatar_type, user_avatar_width',
			'FROM'		=> array(
				FML_TABLE				=> 'f',
			),
			'LEFT_JOIN'	=> array(
				array(
					'FROM'	=> array(USERS_TABLE => 'u'),
					'ON'	=> 'u.username = f.fml_poster',		
				)
			),
			'WHERE'		=> $where,
			'ORDER_BY'	=> $order
		);

		$sql = $db->sql_build_query('SELECT', $sql_array);
		$start	= request_var('start', 0);
		$pagination_url = append_sid($phpbb_root_path . 'fml.' . $phpEx);

		$result	 = $db->sql_query_limit($sql, $limit, $start);
		// Get zee FML ratings

		
		while($row = $db->sql_fetchrow($result))
		{

			$username = get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']);
			$username_full = ($row['fml_anon'] || !$config['allow_anon']) ? $username : $user->lang['ANONYMOUS'];
			$av_size = 50;
			$avatar_thumb = ($row['user_avatar']) ? get_user_avatar($row['user_avatar'], $row['user_avatar_type'], ($row['user_avatar_width'] > $row['user_avatar_height']) ? $av_size : ($av_size / $row['user_avatar_height']) * $row['user_avatar_width'], ($row['user_avatar_height'] > $row['user_avatar_width']) ? $av_size : ($av_size / $row['user_avatar_width']) * $row['user_avatar_height']) : '';
			
			$template->assign_block_vars('fml', array(
				'FML_TEXT'		=>  generate_text_for_display($row['fml_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $row['bbcode_options']),
				'FML_POSTER'	=> 	$username_full,
				'FML_DATE'		=>  $user->format_date($row['fml_date']),
				'U_FML_DELETE'  =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=delete&amp;id=".$row['id']),
				'U_FML_DESERVE' =>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate&amp;verd=deserve&amp;id=".$row['id']),	
				'U_FML_AGREE'  	=>  append_sid("{$phpbb_root_path}fml.$phpEx", "mode=rate&amp;verd=agree&amp;id=".$row['id']),
				'U_COMMENT_PAGE' => append_sid("{$phpbb_root_path}fml.$phpEx", "mode=comment&amp;id=".$row['id']),
				'HAS_RATED'		=>  get_who_rated($user->data['user_id'], $row['id']),	
				'IS_POSTER'		=>  ($row['user_id'] == $user->data['user_id']) ? true:false,
				'TOTAL_DESERVE' =>  get_total_deserve($row['id']),
				'TOTAL_AGREE'	=>  get_total_agree($row['id']),
				'TOTAL_COMMENTS' => get_total_comments($row['id']),	
				'AVATAR_THUMB'	=>  ($row['fml_anon'] || !$config['allow_anon']) ? $avatar_thumb : '',
				
	
			));

		}
		$db->sql_freeresult($result);
		
		$sql_array['SELECT'] = 'COUNT(id) AS total_fml
					FROM '. FML_TABLE; 
		$sql_array['WHERE'] = ' WHERE '.$where;

		$result = $db->sql_query($sql);
		$total_fml = $db->sql_fetchfield('total_fml');
		$db->sql_freeresult($result);
		
		$template->assign_vars(array(
    		'PAGINATION'        => generate_pagination($pagination_url, $total_fml, $limit, $start),
    		'PAGE_NUMBER'       => on_page($total_fml, $limit, $start),
	));
		
}


make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
page_footer();

?>