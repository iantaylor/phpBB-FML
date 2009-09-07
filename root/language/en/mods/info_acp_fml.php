<?php
/**
* DO NOT CHANGE
*/
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
    'ACP_FML_INDEX_TITLE'                       => 'phpbb FML',
    'ACP_FML'									=> 'phpbb FML',
    'ENABLE_FML'								=> 'Enable FML mod',
    'ALLOW_ANON'								=> 'Allow users to hide there username when posting',
    'ALLOW_COMMENTS'							=> 'Enable commenting',	
    'FML_AV_SIZE'								=> 'Avatar size for the comment system',
    'MIN_THRESH'								=> 'Comment rating minimum (bad) rating',
    'MIN_THRESH_EXPLAIN'						=> 'If a comments rating goes below the set number it will be hidden in a javascript box, but will be visible by clicking a link. number should be a negative (-10 etc..)',	
    'MAX_THRESH'								=> 'Comment rating max (good) rating',
    'MAX_THRESH_EXPLAIN'						=> 'If a comments rating goes above the set number the comment will change to the colour set.',	
    'GOOD_COLOUR'								=> 'Colour for good comments',							
));
?>