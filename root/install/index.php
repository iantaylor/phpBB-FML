<?php
/**
*
* @author platinum_2007 (Ian Taylor) iantaylor603@gmail.com
* @package umil
* @version $Id index.php 0.0.1 2009-03-22 16:56:28GMT platinum_2007 $
* @copyright (c) 2009 ian taylor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : '../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();
define('FML_TABLE',					$table_prefix . 'fml');
define('FML_RATE_TABLE',			$table_prefix . 'fml_rate');
define('FML_COMMENT_TABLE',			$table_prefix . 'fml_comment');
define('FML_COMMENT_RATE_TABLE',	$table_prefix . 'fml_comment_rates');
if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'FML';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'fml_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/fml';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/
$versions = array(
	// Version 0.0.1
	'0.0.1' => array(
		'table_add' => array(
			array(FML_TABLE, array(
				'COLUMNS'			=> array(
				'id'				=> array('UINT', NULL, 'auto_increment'),
				'fml_poster'		=> array('VCHAR', ''),
				'fml_text'			=> array('TEXT', ''),
				'fml_anon'			=> array('UINT', 0),
				'fml_date'			=> array('VCHAR', ''),
				'category'			=> array('VCHAR', ''),

				),
				'PRIMARY_KEY' => array('id'),
			)),
			
		array(FML_RATE_TABLE, array(
			'COLUMNS'	=> array(
				'fml_rate_id'		=> array('UINT', NULL, 'auto_increment'),
				'fml_id'			=> array('UINT', 0),
				'fml_rater_id'		=> array('UINT', 0),
				'fml_agree'			=> array('UINT', 0),
				'fml_deserve'		=> array('UINT', 0),

				),
				'PRIMARY_KEY' => array('fml_rate_id'),
			)),
			
		array(FML_COMMENT_TABLE, array(
			'COLUMNS'	=> array(
				'fml_comment_id'	=> array('UINT', NULL, 'auto_increment'),
				'fml_comment_text'			=> array('TEXT', ''),
				'fml_commenter'		=> array('UINT', 0),
				'fml_comment_to'	=> array('UINT', 0),
				'fml_comment_date'	=> array('VCHAR', 0),
				'fml_comment_rate'	=> array('VCHAR', 0),

				),
				'PRIMARY_KEY' => array('fml_comment_id'),
			)),
			
		array(FML_COMMENT_RATE_TABLE, array(
			'COLUMNS'	=> array(
				'fml_comment_id'	=> array('UINT', 0),
				'fml_user_id'		=> array('UINT', 0),

				),
			)),

	
		),
		

		 'module_add' => array(
		 		 array('acp', 'ACP_CAT_DOT_MODS', 'ACP_FML'),
		 		 
		           array('acp', 'ACP_FML', array(
                                        'module_basename'                => 'fml',
                                        'modes'                          => array('index'),
                                ),
                        ),
                  
				),

		'config_add' => array(
			array('enable_fml', true),
			array('allow_anon', '1', '0'),
			array('allow_comments', '1', '0'),
			array('fml_av_size', '50', '0'),
			array('max_thresh', '10', '0'),
			array('min_thresh', '-10', '0'),
			array('good_colour', '#F0E68C', '0'),
		),
		

		

),

	'0.0.2' => array(
	
		'table_column_add' => array(
				array(FML_TABLE, 'bbcode_uid', array('STEXT_UNI', '0')),
				array(FML_TABLE, 'bbcode_bitfield', array('VCHAR', '0')),
				array(FML_TABLE, 'bbcode_options', array('USINT', 1)),
				array(FML_TABLE, 'enable_bbcode', array('USINT', 1)),
				array(FML_TABLE, 'enable_magic_url', array('USINT', 1)),
				array(FML_TABLE, 'enable_smilies', array('USINT', 1)),
				
	)),


);

// Include the UMIF Auto file and everything else will be handled automatically.
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);

?>