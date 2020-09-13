<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=ajax
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

$mode   = cot_import('mode', 'G', 'ALP');
$search = urldecode (cot_import('search', 'G', 'TXT'));
$how_search = cot_import('how_search', 'G', 'ALP');

switch ($mode) {
case 'search':
	if ($how_search == "on") {
		$sql_search_string = "
                SELECT page_alias, page_title, page_text FROM cot_pages
                WHERE MATCH(page_text,page_title) AGAINST ('\"{$search}\"' IN BOOLEAN MODE)
            ";
	} else {
	    $sql_search_string = "
                SELECT page_alias, page_title, page_text FROM cot_pages
                WHERE MATCH(page_text,page_title) AGAINST ('{$search}')
            ";
	}
    $sql_search = $db->query($sql_search_string);
    $result = '';
    if ($sql_search->rowCount()) {
        $result = '<table>';
        foreach ($sql_search->fetchAll() as $item) {
        	$url = cot_url('plug', 'o=komus&mode=page&al='.$item['page_alias']);   	
    	    $result .= '<tr><td>';
    	    $result .= '<a href="'.$url.'" rel="shadowbox" class="search">'.$item['page_title'].'</a>';
    	    $result .= '</td></tr>';
        }
        $result .= '</table>';    	
    } else {
       $result = '<b>Ничего не найдено</b>';	
    }
    
    echo $result;      
    break;
}
?>
