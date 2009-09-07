<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'FML_TITLE'		=>	'F*** My Life',
	'ANON'			=>  'Show username?',
	'FML_TEXT'		=>  'Describe your FML',
	'BY'			=>	'posted by:',
	'ANONYMOUS'		=>  'Anonymous',
	'DELETE_CONFIRM' => 'Are you sure you want to delete this FMl?',
	'DELETE_CONFIRM_RATES'	=> 'Are you sure you want to delete all the rates for this FML?',
	'DELETE_CONFIRM_COMMENTS'	=> 'Are you sure you want to delete all comments on this FML?',
	'POST_FML'		 =>  'Post a FML',
	'AGREE'			 =>  'I agree, your life is f***ed',
	'DESERVE'		 =>  'you deserved that one',
	'RANDOM'		=>  'Random',
	'REFRESH'		=>  'Refresh Random FML',
	'ON'			=>	'on',
	'NOT_FOUND'		=>  'The requested FML could not be found!',
	'COMMENTS'		=>  'Comments',
	'SUBMIT_COMMENT' =>  'Submit Comment',
	'TODAY'			=>  'Today',
	'FML'			=>	'FML',
	'ALREADY_RATED'	=>  'You can only rate each FML one time!',	
	'PEOPLE_AGREE' => 'People agree',
	'CLICK_VIEW'	=> 'click to view',
	'CLICK_CLOSE'	=> 'click to close',
	'BELOW_THRESH'	=>  'This comment is below the rating threshold',
	'DELETE_RATES'  =>   'Delete all rates',
	'DELETE_COMMENTS'	=> 'Delete all comments',
	'ADMIN_TOOLS'	=>  'Admin tools :',
	'CATS'			=> 'Categories',
	'OR'			=> 'or',
	'OPTION'		=> 'Display FMLs from',
	'TOP_DAY'		=> 'Top FMLs of the day',
	'TOP_WEEK'		=> 'Top FMLs of the week',
	'TOP_MONTH'		=> 'Top FMLS of the month',
	'TOP_FML'		=> 'All time top FMLs',
	
	// Categories
	'LOVE'			=> 'Love',
	'MONEY'			=> 'Money',
	'MISC'			=> 'Miscellaneous',
	'HEALTH'		=> 'Health',
	'INTAMICY'		=> 'Intamicy',
	'KIDS'			=> 'Kids',
	'WORK'			=> 'Work', 
	
	// errors
	'MUST_LOGIN_COMMENT'	=> 'You must be logged in before you can post a comment!',
	'FML_CLOSED'			=> 'The FML mod is closed!',

	
));

?>