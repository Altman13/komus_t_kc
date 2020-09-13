<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=popup
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

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('users', 'w');
//cot_block($usr['auth_write']);

$mode = cot_import('mode', 'G', 'ALP');
switch ($mode) {
case 'page':
    $alias = cot_import('al', 'G', 'ALP');
    $sql_page_string = "SELECT page_title, page_text FROM {$db_x}pages WHERE page_alias = '$alias'";
    $sql_page = $db->query($sql_page_string);
    $page = $sql_page->fetch();
    $popup_body = $page['page_text'];
    $t->assign('POPUP_TITLE', $page['page_title']);
    break;
}

?>