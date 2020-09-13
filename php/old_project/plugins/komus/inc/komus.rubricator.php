<?php
function rubricator() {
	global $db, $db_x;
	 $sql_structure_string = "
            SELECT *
            FROM {$db_x}structure            
            WHERE structure_code NOT LIKE 'articles%' AND structure_area = 'page'
            ORDER BY structure_title
        ";
	 $sql_structure = $db->query($sql_structure_string); 
	 $rubricator = "<ul>";
	 foreach ($sql_structure->fetchAll() as $categ) {
	 	$title_patt = '/(\d\.)[\w]*/i';
		$title_str = preg_replace($title_patt,'',$categ['structure_title']);
		//var_dump($title_str);die;
	 	$rubricator .= '<li class="list">'.$title_str;
	 	
	 	$sql_pades_string = "
            SELECT *
            FROM {$db_x}pages            
            WHERE page_cat = '{$categ['structure_code']}'
            ORDER BY page_id
        ";
	 	$rubricator .= "<ul>";
	 	$sql_pages = $db->query($sql_pades_string);
	 	foreach ($sql_pages->fetchAll() as $page) {
	 		$url = cot_url('plug', 'o=komus&mode=page&al='.$page['page_alias']);
	 		$rubricator .= '<li><a href="'.$url.'" rel="shadowbox">'.$page['page_title'].'</a></li>';
	 	}
	 	$rubricator .= "</ul>";
	 	$rubricator .= "</li>";
	 }
	 $rubricator .= "</ul>";
	return $rubricator;
}
?>