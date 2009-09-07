<?php
if (!defined('IN_PHPBB'))
{
	exit;
}

function get_total_agree($id)
{

		global $db;
		  
		$sql	= 'SELECT COUNT(fml_agree) AS total_agree FROM '.FML_RATE_TABLE.' WHERE fml_agree = 1 AND fml_id ='.$id;
		$result	 	= $db->sql_query($sql);
		$total_agree = $db->sql_fetchfield('total_agree');
		
		return $total_agree;

}
function get_total_deserve($id)
{

		global $db;
		  
		$sql	= 'SELECT COUNT(fml_deserve) AS total_deserve FROM '.FML_RATE_TABLE.' WHERE fml_deserve = 1 AND fml_id ='.$id;
		$result	 	= $db->sql_query($sql);
		$total_deserve = $db->sql_fetchfield('total_deserve');
		
		return $total_deserve;

}
function get_total_comments($id)
{

		global $db;
		  
		$sql	= 'SELECT COUNT(fml_comment_id) AS total_comments FROM '.FML_COMMENT_TABLE.' WHERE fml_comment_to ='.$id;
		$result	 	= $db->sql_query($sql);
		$total_comments = $db->sql_fetchfield('total_comments');
		
		return $total_comments;

}

function get_who_rated($current_user, $id)
{

		global $db;

		$sql = 'SELECT * FROM '.FML_RATE_TABLE.' WHERE fml_rater_id = '.$current_user.' AND fml_id ='.$id;
		$result	 	= $db->sql_query($sql);
		$has_rated = ($db->sql_fetchfield('fml_rate_id')) ? true:false;
		
		return $has_rated;

}

function get_who_rated_comment($current_user, $id)
{

		global $db;

		$sql = 'SELECT * FROM '.FML_COMMENT_RATE_TABLE.' WHERE fml_user_id = '.$current_user.' AND fml_comment_id ='.$id;
		$result	 	= $db->sql_query($sql);
		$has_rated_comment = ($db->sql_fetchfield('fml_comment_id')) ? true:false;
		
		return $has_rated_comment;

}

function delete_fml($id)
{
				global $db;

				$sql = 'DELETE FROM '.FML_TABLE.' WHERE id='.$id;
				$db->sql_query($sql);

}

function delete_rates($id)
{
				global $db;

				$sql = 'DELETE FROM '.FML_RATE_TABLE.' WHERE fml_id='.$id;
				$db->sql_query($sql);

}

function delete_all_comments($id)
{
				global $db;
	
				$sql = 'DELETE FROM '.FML_COMMENT_TABLE.' WHERE fml_comment_to='.$id;
				$db->sql_query($sql);


}

function delete_comment($id)
{
				global $db;
	
				$sql = 'DELETE FROM '.FML_COMMENT_TABLE.' WHERE fml_comment_id='.$id;
				$db->sql_query($sql);
}

function rate_comment($id)
{

		global $db, $phpbb_root_path, $user;

		$sql = 'SELECT * FROM '.FML_COMMENT_TABLE.' WHERE fml_comment_id = '.$id;
		$result	 	= $db->sql_query($sql);
		$rated = $db->sql_fetchfield('fml_comment_rate');
		
		$rate = request_var('rate', '');
		if($rate == 'pos')
		{
			$rating = '+ 1';

		}
		else
		{
			$rating = '- 1';

		}

		$sql = 'UPDATE ' . FML_COMMENT_TABLE . ' SET fml_comment_rate = fml_comment_rate '.$db->sql_escape($rating).' WHERE fml_comment_id ='.$id;
		$db->sql_query($sql);
		
		$sql_ary = (array(
			'fml_comment_id'        	=> $id,
			'fml_user_id'        		=> $user->data['user_id'],

		));

		$sql = 'INSERT INTO  '.FML_COMMENT_RATE_TABLE .$db->sql_build_array('INSERT', $sql_ary);
		$db->sql_query($sql);
			

} 

?>