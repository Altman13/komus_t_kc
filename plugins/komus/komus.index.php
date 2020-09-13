<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=index.tags
[END_COT_EXT]
==================== */

/**
 * Komus Plugin for Cotonti CMF
 *
 * @package komus
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');
 
require_once cot_langfile('komus', 'plug');
require_once cot_incfile('komus', 'plug');

$_SESSION['call_id'] = '';
$call_access = false;
$call_groups = array(4);

$sql_user_string = "SELECT gru_groupid FROM {$db_x}groups_users WHERE gru_userid = {$usr['id']}";
$sql_user = $db->query($sql_user_string);

if ($sql_user->rowCount($sql_user) > 0) {
    foreach ($sql_user->fetch() as $group) {
        if (in_array($group, $call_groups)) {
            $call_access = true;
        }
    }
}

if ($call_access == true) {
  if (CF_TYPE_PROJECT) {	
  //Фильтр отбора записей]
  if (CF_FILTER) {
  	  include_once cot_incfile("komus", "plug", "filter");
  	  Filter();
  }    	 
  /////////////////////////////////
  
  //Кол-во звонков
  if (CF_COUNT) {
  	  include_once cot_incfile("komus", "plug", "count");
  	  ShowCount($usr['id']);
  }
  ////////////////////////////////

  //Записи для перезвонов и недозвонов
  if (CF_CALLS) {
    if (CF_CALLS_TYPE1) {	
  	   include_once cot_incfile("komus", "plug", "calls");
  	   Calls();
    }   
    if (CF_CALLS_TYPE2){
  	   include_once cot_incfile("komus", "plug", "calls2");	
  	   Calls2();
    }  
  } 
  
  ///////////////////////////////  
  }
  
  $t->assign(array(
        'KOMUS_SPLASH_USER_ID'          => $usr['id'],
        'KOMUS_SPLASH_ACTION'           => cot_url('plug', 'e=komus&mode=splash'),
        'KOMUS_SPLASH_NEW_CALL_ACTION'  => cot_url('plug', 'e=komus&part=web&mode=new_call'),
        'KOMUS_CF_CALLS'                => CF_CALLS,
        'KOMUS_CF_CALLS_TYPE1'          => CF_CALLS_TYPE1,
        'KOMUS_CF_CALLS_TYPE2'          => CF_CALLS_TYPE2,
        'KOMUS_CF_TYPE_PROJECT'         => CF_TYPE_PROJECT
    ));
    
     
    $t->parse('MAIN.OPERATOR');
} else {
    $t->assign(array(
        'KOMUS_TITLE'           => $L['komus_logon'],
        'KOMUS_AUTH_SEND'       => cot_url('users', 'm=auth&a=check'),
        'KOMUS_AUTH_USER'       => '<input type="text" name="rusername" value="" maxlength="32" />',
        'KOMUS_AUTH_PASSWORD'   => '<input type="password" name="rpassword" value="" maxlength="32" />'
    ));
    $t->parse('MAIN.GUEST');
}
?>
