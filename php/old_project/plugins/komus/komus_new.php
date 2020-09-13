<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
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

/*========================================*/
$max_calls = 4;
/*========================================*/

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('users', 'w');

$call_groups = array(4);
$edit_groups = array();

$sql_user_string = "SELECT gru_groupid group_id FROM {$db_x}groups_users WHERE gru_userid = {$usr['id']}";
$sql_user = $db->query($sql_user_string);

$call_access = false;
$edit_access = false;
foreach ($sql_user as $groups) {
    if (in_array($groups['group_id'], $call_groups)) {
        $call_access = true;
    }
    if (in_array($groups['group_id'], $edit_groups)) {
        $edit_access = true;
    }
}
require_once cot_incfile('forms');

$mode = cot_import('mode', 'G', 'ALP');

///Массив полей для синхронизации
$FieldSinhrArray = array( 
                    'kod',
                    'famaly',
                    'name',
                    'otchestvo',
                    'bethday',
                    'address',
                    'phone1', 
                    'phone2',
                    'quantity',
                    'question1',
                    'question2',
                    'question3',
                    'question4',
                    'question5'
                        );
////////////////////////////////

switch ($mode) {
		
case 'continue':
    if (cot_import('submit', 'P', 'INT') == 1) {
        header('Location: ' . cot_url('users', 'm=logout&' . cot_xg(), '', true));
        exit;
    } else {
        $update = array(
            'user_status' => 1
        );
        $sql_pause = $db->update($db_x . 'users', $update, 'user_id = ' . $usr['id']);

        $sql_select_pause_string = "SELECT id, MAX(begin_time) FROM {$db_x}komus_calls WHERE end_time IS NULL AND call_status = 16 AND user_id = {$usr['id']}";
        $sql_select_pause = $db->query($sql_select_pause_string);
        $select_pause = $sql_select_pause->fetch();

        $pause_time = date('Y-m-d H:i', $sys['now_offset'] + 3 * 3600);
        $update = array(
            'end_time'  => $pause_time
        );
        $sql_pause = $db->update($db_x . 'komus_calls', $update, 'id = ' . $select_pause['id']);
        header('Location: index.php');
        exit;
    }
    break;

case 'edit':
    require_once cot_incfile('komus', 'plug', 'edit');
    break;

case 'end':
    $contact_id = cot_import('id', 'G', 'INT');
    $submit = cot_import('submit', 'P', 'ALP');
    if ($submit == 'ftp') {
        $sql_rep_string = "SELECT project_id FROM {$db_x}komus_contacts WHERE id = $contact_id";
        $sql_rep = $db->query($sql_rep_string);
        $rep = $sql_rep->fetchColumn();

        header('Location:' . cot_url('plug', "e=komus_reports&rep={$rep}&id=$contact_id", '', true));
        exit;
    } else {
        $action = cot_import('action', 'G', 'ALP');
        if (empty($_SESSION['call_id'])) {
            $sql_call_string = "SELECT MAX(id) FROM {$db_x}komus_calls WHERE contact_id = $contact_id";
            $sql_call = $db->query($sql_call_string);
            $_SESSION['call_id'] = $sql_call->fetchColumn();
        }
        
        $update_contact = array(
            'comment'   => cot_import('comment', 'P', 'TXT'),
            'in_work'   => 0,
            'is_finish' => 1
        );
        $update_call = array(
            'end_time' => date('Y-m-d H:i', $sys['now_offset'] + 3 * 3600)
        );
        
        $sql_status_update = $db->update($db_x . 'komus_contacts', $update_contact, 'id = ' . $contact_id);
        $sql_status_update = $db->update($db_x . 'komus_calls', $update_call, 'id = ' . $_SESSION['call_id']);
        
        //Завершение
        $sql_call_string = "
            SELECT contacts.*, calls.call_status
            FROM {$db_x}komus_contacts contacts
            LEFT JOIN {$db_x}komus_calls calls ON calls.contact_id = contacts.id
            WHERE contacts.id = $contact_id
        ";
        $sql_call        = $db->query($sql_call_string);
        $call_data       = $sql_call->fetch();
        
        //Завершение звонка
        $id_base_call = $_SESSION['id_base_call'];
        $update_data['status']    = $call_data['status_call'];
        $update_data['comment']   = $call_data['comment'];
        
        foreach ($FieldSinhrArray as $fieldSinhr) {
            $update_data[$fieldSinhr]   = $call_data[$fieldSinhr];	
        }
        
        $sql_base_string = "
            SELECT *
            FROM {$db_x}komus_base            
            WHERE id = $id_base_call            
        ";
        $sql_base        = $db->query($sql_base_string);
        $base_data       = $sql_base->fetch();
        //перезвоны
        if ($call_data['status_call'] == 4) {
        	$update_data['data_recall'] = $call_data['data_recall'];        	
        }
        //Недозвоны
        if ($call_data['status_call'] == 1 && $base_data['kolvo_call'] < 3) {
        	$update_data['status'] = $call_data['status_call'];        	
        	$update_data['is_block'] = 1; 
        	$base_data['kolvo_call']++;
        	$update_data['kolvo_call'] = $base_data['kolvo_call'];
        } 
         
        $sql_data_update = $db->update($db_x . 'komus_base', $update_data, 'id = ' . $id_base_call);
        
        //Счетчик звонков
        include_once cot_incfile("komus", "plug", "count");
        ////////////////
        
        $_SESSION['call_id'] = '';
        $_SESSION['komus_path'] = '';
        $_SESSION['recall'] = '';
        $_SESSION['id_base_call'] = '';
        
        if ($action == 'edit') {
            header('Location: ' . cot_url('plug', 'e=komus&mode=edit&id=' . $contact_id, '', 'true'));
        } else {
        	header('Location: index.php');
        }
    }
    exit;
    break;
    
case 'ftp_report':
    if (!$edit_access) {
        header('Location: index.php');
        exit;
    }
    break;

case 'next_call':
    if (empty($_POST)) {
        $t->assign(array(
            'KOMUS_HAVE_NEXT_CALL'      => $L['komus_have_next_call'],
            'KOMUS_NEXT_CALL_ACTION'    => cot_url('plug', 'e=komus&mode=next_call')
        ));
        $t->parse('MAIN.NEXT_CALL');
    } else {
        if (cot_import('submit', 'P', 'INT') > 0) {
            header('Location: index.php');
            exit;
        } else {
            $t->assign(array(
                'KOMUS_CONTINUE_MSG'        => $L['komus_continue_calls'],
                'KOMUS_NEXT_CALL_ACTION'    => cot_url('plug', 'e=komus&mode=step&mode=continue')
            ));
            $t->parse('MAIN.CONTINUE_CALLS');
        }
    }
    break;
   
case 'new_call':
    $project_id = cot_import('project', 'G', 'INT');
    $base_id = cot_import('id', 'G', 'INT');
    $is_edit = cot_import('edit', 'G', 'INT');
  
    $sql_project_string = "SELECT first_node_id FROM {$db_x}komus_projects WHERE id = $project_id";
    $sql_project = $db->query($sql_project_string);

    $first_node_id = $sql_project->fetchColumn();

    $begin_time = date('Y-m-d H:i:s', $sys['now_offset'] + 3 * 3600);
    $time_now =  date('H:i:s', $sys['now_offset'] + 3 * 3600);

    $insert_contact['in_work']          = 1;
    $insert_contact['user_id']          = $usr['id'];
    $insert_contact['recall_time']      = '';
    $insert_contact['creation_time']    = $begin_time;
    $insert_contact['project_id']       = $project_id;

    $sql_insert = $db->insert($db_x . 'komus_contacts', $insert_contact);
    $contact_id = $db->lastInsertId();

    $insert_call = array(
        'contact_id'    => $contact_id,
        'begin_time'    => $begin_time,
        'user_id'       => $usr['id']
    );

    $sql_insert = $db->insert($db_x . 'komus_calls', $insert_call);
    $call_id = $db->lastInsertId();
    $_SESSION['call_id'] = $call_id;

    //Выборка записи для обзвона
    //выбираем свободные записи is_block = 1
    if ($base_id == null) {
        $sql_base_string = "
            SELECT *
            FROM {$db_x}komus_base            
            WHERE is_block = 1 AND status = 0
            LIMIT 1
        ";
        $sql_base        = $db->query($sql_base_string);
        $base_data       = $sql_base->fetch();    
        
        //Все записи прошли
        if ($base_data['id'] == NULL) {
    	//Выбираем недозвоны
    	 $sql_base_string = "
            SELECT *
            FROM {$db_x}komus_base            
            WHERE (is_block = 1 AND status = 1 AND kolvo_call < 3)  
            ORDER BY kolvo_call LIMIT 1
         ";
    	 $sql_base        = $db->query($sql_base_string);
         $base_data       = $sql_base->fetch();
       }
    } else {
    	//Перезвоны
    	$sql_base_string = "
            SELECT *
            FROM {$db_x}komus_base            
            WHERE id = {$base_id} 
            
        ";
    $sql_base        = $db->query($sql_base_string);
    $base_data       = $sql_base->fetch();
    }
   
    //Запись использована
    if ($base_data['id'] != NULL) {    	
        $update_data['is_block'] = 0;
        //Для редактирования
        if (!isset($is_edit)) {
        	$update_data['data_call'] = $begin_time;
            $update_data['operator'] = $usr['id'];
        }        
        
        $sql_data_update = $db->update($db_x . 'komus_base', $update_data, 'id = ' . $base_data['id']);
    }   
    
    $_SESSION['id_base_call'] = $base_data['id'];
    
    //Внесем в контакты
     foreach ($FieldSinhrArray as $fieldSinhr) {
     	$update_contacts[$fieldSinhr]    = $base_data[$fieldSinhr];
     }       
    $update_contacts['comment']          = $base_data['comment'];
    $update_contacts['status_call_info'] = getReferenceItem($base_data['status']);
      
    $sql_contacts_update = $db->update($db_x . 'komus_contacts', $update_contacts, 'id = ' . $contact_id);    
    
    header('Location: ' . cot_url('plug', 'e=komus&mode=step&node=' . $first_node_id, '', true));
    exit;
    break;
    
case 'pause':
    $sql_pause_string = "SELECT COUNT(*) FROM {$db_x}users WHERE user_id = {$usr['id']} AND user_status = 2";
    $sql_pause = $db->query($sql_pause_string);
    $has_pause = ($sql_pause->fetchColumn() > 0) ?
        true :
        false;
    if (!$has_pause) {
        $update = array(
            'user_status' => 2
        );
        $sql_pause = $db->update($db_x . 'users', $update, 'user_id = ' . $usr['id']);
        $pause_time = date('Y-m-d H:i:s', $sys['now_offset'] + 3 * 3600);
        $insert = array(
            'begin_time'    => $pause_time,
            'user_id'       => $usr['id'],
            'call_status'   => 16,
            'is_last_call'  => 0
        );
        $sql_pause = $db->insert($db_x . 'komus_calls', $insert);
    }
    $t->assign(array(
        'KOMUS_CONTINUE_MSG'        => $L['komus_continue_calls'],
        'KOMUS_NEXT_CALL_ACTION'    => cot_url('plug', 'e=komus&mode=continue')
    ));
    $t->parse('MAIN.CONTINUE_CALLS');
    break;

case 'recall':
    $contact_id = get_contact_id($_SESSION['call_id']);
    
    $recall_time = date('Y-m-d H:i', cot_import_date('recall_time'));
    $update_contacts = array(
        'comment'       => cot_import('comment', 'P', 'TXT'),
        'recall_time'   => $recall_time
    );
    $update_calls = array(
        'phone_recall'  => cot_import('phone_recall', 'P', 'TXT'),
        'recall_time'   => $recall_time
    );

    $sql_step_update = $db->update($db_x . 'komus_contacts', $update_contacts, 'id = ' . $contact_id);
    $sql_step_update = $db->update($db_x . 'komus_calls', $update_calls, 'id = ' . $_SESSION['call_id']);
    header('Location: ' . cot_url('plug', 'e=komus&mode=end&id=' . $contact_id, '', true));
    exit;
    break;
    
case 'splash':
    $action = cot_import('action', 'P', 'INT');
    if ($action == NULL) {
        $action_ie = $_POST["action"];
        if ($action_ie == "Перерыв") {
        	$action = -1;
        } else {
        	$action = 1;
        }	
    }
    
 //   var_dump($action_ie); die;
    switch ($action) {
    case -1:
        header('Location: ' . cot_url('plug', 'e=komus&mode=pause', '', true));
        exit;
        break;
            
    default:
        if ($action > 0) {
            header('Location: ' . cot_url('plug', 'e=komus&mode=new_call&project=1', '', true));
            exit;
        }
    }
    break;

case 'step':
    $action = ($edit_access) ?
        cot_import('action', 'G', 'ALP') :
        '';
    
    if (empty($_SESSION['call_id']) && !$edit_access) {
        $t->assign(array(
            'KOMUS_STEP_ERROR_NO_SESSION' => $L['komus_error_no_session']
        ));
        $t->parse('MAIN.STEP_ERROR');
    } else {
        $answer_id = cot_import('answer', 'G', 'INT');
        $node_id = cot_import('node', 'G', 'INT');
        $required = cot_import('required', 'G', 'BOL');
        
        $flag_interrup_call = true;
        if($node_id == 1) {
        	$flag_interrup_call = false;
        }
        
        if ($edit_access) {
            $contact_id = cot_import('id', 'G', 'INT');
        } else {
            $sql_contact_string = "SELECT contact_id FROM {$db_x}komus_calls WHERE id = {$_SESSION['call_id']}";
            $sql_contact = $db->query($sql_contact_string);
            $contact_id = $sql_contact->fetchColumn();
        }
        
        if (!empty($_POST)) {
            $form_id = cot_import('form_id', 'P', 'INT');
            $old_node_id = cot_import('old_node', 'G', 'INT');
            $status_id = cot_import('status', 'P', 'INT');
/*            $error_status = (empty($status_id)) ?
                true :
                false;*/

            if (!empty($form_id)) {
                $sql_forms_string = "
                    SELECT ff.*
                    FROM {$db_x}komus_forms_fields ff
                    LEFT JOIN {$db_x}komus_forms f ON f.id = ff.form_id
                    WHERE f.id = $form_id
                ";
                $sql_forms = $db->query($sql_forms_string);

                foreach ($sql_forms->fetchAll() as $form) {
                    $form_fields[$form['name']]['id']       = $form['id'];
                    $form_fields[$form['name']]['required'] = $form['required'];
                    $form_fields[$form['name']]['title']    = $form['title'];
                    $form_fields[$form['name']]['type']     = $form['type'];
                    $form_fields[$form['name']]['save']     = $form['save'];
                }
            }
            $_SESSION['komus_path'] .= '-' . $answer_id;

            $error_fields = array();
            $update_contact = array();
            foreach ($form_fields as $key => $field) {
                if (($key == 'has_work' && $_POST[$key] != 1) || ($key == 'client_age' && ($_POST[$key] < 25 || $_POST[$key] > 71))) {
                    $update_contact[$key] = $_POST[$key];
                    header('Location: ' . cot_url('plug', 'e=komus&mode=step&node=1000&action=' . $action . '&id=' . $contact_id, '', true));
                    exit;
                }

                if ($field['save'] != 1) {
                    continue;
                }
                if ($field['type'] == 9) {
                    $update_contact[$key] = (empty($_POST[$key])) ?
                        2 :
                        1;
                    continue;
                }

                if ($field['required'] > 0 && empty($_POST[$key])) {
                    $error_fields[] = $field['id'];
                    continue;
                }
                
                if (is_array($_POST[$key])) {
                    if (isset($_POST[$key]['day']) && isset($_POST[$key]['month'])) {
                        $update_contact[$key] = (empty($_POST[$key])) ?
                            '' :
                            date('Y-m-d H:i', cot_import_date($key));
                    }
                } else {
                    $update_contact[$key] = $_POST[$key];
                }
            }
                       
 
            $update_contact['comment'] = cot_import('comment', 'P', 'TXT');
            $update_call['call_status'] = (empty($status_id)) ?
                0 :
                $status_id;

            if (empty($error_fields) && empty($error_status)) {
                $sql_step_update = $db->update($db_x . 'komus_contacts', $update_contact, 'id = ' . $contact_id);
                if (!$edit_access) {
                    $sql_step_update = $db->update($db_x . 'komus_calls', $update_call, 'id = ' . $_SESSION['call_id']);
                }
                
                $sql_node_string = "
                    SELECT c.if_visited_node, c.if_field, c.if_field_value, c.to_node, ff.name field_name
                    FROM cot_komus_conditions c
                    LEFT JOIN cot_komus_forms_fields ff ON ff.id = c.if_field
                    WHERE c.node_id = $old_node_id
                    ORDER BY if_field_value
                ";
                $sql_node = $db->query($sql_node_string);

                $cond = array();
                foreach ($sql_node->fetchAll() as $key => $node) {
                    if ($key == 0) {
                        $operator_data = $node;
                    }
                    $data['if_visited_node']  = $node['if_visited_node'];
                    $data['if_field']         = $node['if_field'];
                    $data['if_field_value']   = $node['if_field_value'];
                    $data['to_node']          = $node['to_node'];
                    $data['field_name']       = $node['field_name'];
                    $cond[] = $data;
                }
                $goto = ($cond[0]['to_node'] > 0 && empty($cond[0]['if_field_value'])) ?
                    $cond[0]['to_node'] :
                    0;
                foreach ($cond as $c) {
                    $value = cot_import($c['field_name'], 'P', 'INT');
                    if ($value == $c['if_field_value']) {
                        $goto = $c['to_node'];
                        if (!empty($c['if_field'])) {
                            break;
                        }
                    }
                }
                if ($goto > 0) {
                    header('Location: ' . cot_url('plug', 'e=komus&mode=step&node=' . $goto . '&action=' . $action . '&id=' . $contact_id, '', true));
                } else {
                    header('Location: ' . cot_url('plug', 'e=komus&mode=step&node=' . $old_node_id . '&action=' . $action . '&id=' . $contact_id, '', true));
                }
                exit;
            } else {
                $_SESSION['required_status'] = (int)$error_status;
                $_SESSION['required_fields'] = implode(',', $error_fields);

                header('Location: ' . cot_url('plug', 'e=komus&mode=step&node=' . $old_node_id . '&action=' . $action . '&id=' . $contact_id, '', true));
                exit;
            }
        }
        
        if (empty($node_id)) {
            header('Location: index.php');
            exit;
        }

        $sql_node_string = "SELECT * FROM cot_komus_nodes WHERE id = $node_id";
        $sql_node = $db->query($sql_node_string);
        $node = $sql_node->fetch();
        
        $required_data = array();
        if ($required) {
            if (!empty($_SESSION['required_status'])) {
                $required_data[] = '- ' . $L['komus_status'];
                $_SESSION['required_status'] = '';
            }

            if (!empty($_SESSION['required_fields'])) {
                $sql_required_string = "SELECT title FROM {$db_x}komus_forms_fields WHERE id IN ({$_SESSION['required_fields']})";
                $sql_required = $db->query($sql_required_string);
                $_SESSION['required_fields'] = '';
                foreach ($sql_required->fetchAll() as $field) {
                    $required_data[] = '- ' . $field['title'];
                }
                $required_string = implode('<br />', $required_data);
            }
        } else{ 
            $_SESSION['required_status'] = '';
            $_SESSION['required_fields'] = '';
        }

        $sql_user_string = "SELECT user_firstname FROM {$db_x}users WHERE user_id = {$usr['id']}";
        $sql_user        = $db->query($sql_user_string);
        $operator_name   = $sql_user->fetchColumn();
        

        $sql_call_string = "
            SELECT contacts.*, calls.call_status
            FROM {$db_x}komus_contacts contacts
            LEFT JOIN {$db_x}komus_calls calls ON calls.contact_id = contacts.id
            WHERE contacts.id = $contact_id
        ";
        $sql_call        = $db->query($sql_call_string);
        $call_data       = $sql_call->fetch();
        
        $tags_list = array(
    /*=====================================================*/
            'company'   => $call_data['company'],
            'fio'       => $call_data['fio'],
            'operator'  => $operator_name
    /*=====================================================*/
        );
        
        $path = explode('-', $_SESSION['komus_path']);
        
        $node_text = $node['node_text'];
        if (strpos($node_text, '{komus_') !== false) {
            unset($p_match);
            preg_match_all("/\{komus_(\w+)\}/", $node_text, $p_match);
            $tags = $p_match[1];
            foreach ($tags as $key => $tag) {
                if ($tag == 'recall_time') {
                    $node_text = str_replace($p_match[0][$key], cot_selectbox_date($sys['now_offset'], 'long', 'recall_time'), $node_text);
                } elseif (strpos($tag, 'page_') !== false) {
                    $alias = substr($tag, 5);
                    $sql_page_string = "SELECT page_title, page_text FROM {$db_x}pages WHERE page_alias = '$alias'";
                    $sql_page = $db->query($sql_page_string);
                    $page = $sql_page->fetch();
                    $t->assign('KOMUS_TITLE', $page['page_title']);
                    $node_text = str_replace($p_match[0][$key], $page['page_text'], $node_text);
                } else {
                    $node_text = str_replace($p_match[0][$key], $tags_list[$tag], $node_text);
                }
            }
        }
        
        $sql_form_string = "
            SELECT f.id, f.title, ff.type, ff.title, ff.name, ff.required, c.if_field_value, c.to_node
            FROM cot_komus_forms f
            LEFT JOIN cot_komus_forms_fields ff ON ff.form_id = f.id
            LEFT JOIN cot_komus_references_items ri ON ri.reference_id = ff.reference_id
            LEFT JOIN cot_komus_conditions c ON c.if_field_value = ri.id
            WHERE f.node_id = $node_id
            ORDER BY ff.sort, ri.sort
        ";
        $sql_form = $db->query($sql_form_string);

        if ($sql_form->fetchColumn() > 0) {
            $sql_fields_string = "
                SELECT ff.*, f.title form_title
                FROM cot_komus_forms_fields ff 
                LEFT JOIN cot_komus_forms f ON f.id = ff.form_id 
                WHERE f.node_id = $node_id
                ORDER BY ff.sort
            ";
            $sql_fields = $db->query($sql_fields_string);
            
            if ($sql_fields->rowCount() > 0) {
                $required_case_js = '';
                $required_names_array = array();
                $required_titles_array = array();
                $required_array_js = <<<JS
                 var required = Array(
JS;

                $form_fields = array();
                foreach ($sql_fields->fetchAll() as $field) {
                	
                	$field_title = $field['title'];
                    if (strpos($field_title, '{komus_') !== false) {
                    	unset($p_match);
                        preg_match_all("/\{komus_(\w+)\}/", $field_title, $p_match);
                        $tags = $p_match[1];
                        foreach ($tags as $key => $tag) {
                            $field_title = str_replace($p_match[0][$key], $tags_list[$tag], $field_title);                
                        }
                    }
                	$field['title'] = $field_title;
                    
                    $form_fields[$field['name']]['id'] = $field['id'];
                    $form_fields[$field['name']]['form_id'] = $field['form_id'];
                    $form_fields[$field['name']]['form_title'] = $field['form_title'];
                    $form_fields[$field['name']]['title'] = $field['title'];
                    $form_fields[$field['name']]['type'] = $field['type'];
                    $form_fields[$field['name']]['reference_id'] = $field['reference_id'];
                    $form_fields[$field['name']]['required'] = $field['required'];
                    $form_fields[$field['name']]['empty_string'] = $field['empty_string'];
                    
                    if ($field['required'] > 0) {
                        $required_names_array[] = $field['name'];
                        $required_titles_array[] = $field['title'];
                    }
                }
                $required_names_items = implode("', '", $required_names_array);
                $required_names_js = <<<JS
                var requiredNames = Array('{$required_names_items}');
JS;
                $required_titles_items = implode("', '", $required_titles_array);
                $required_titles_js = <<<JS
                var requiredTitles = Array('{$required_titles_items}');
JS;
                $forms_count = $sql_node->rowCount();
                $fields = $sql_form->fetch();
                foreach ($form_fields as $field_name => $field_data) {
                    $required = ($form_fields[$field_name]['required'] > 0 && !empty($form_fields[$field_name]['title'])) ?
                        '<sup>*</sup>' :
                        '';

                        $hidden_field = 0;

                        switch ($form_fields[$field_name]['type']) {
                        case 1:
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <input type="text" name="{$field_name}" value="{$call_data[$field_name]}" /></label>\n
HTML;
                            break;
                            
                        case 2:
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <input type="text" name="{$field_name}" value="{$call_data[$field_name]}" /></label>\n
HTML;
                        break;

                        case 3:
                            $date_data = explode('-', $call_data[$field_name]);
                            $date_stamp = @mktime(0, 0, 0, $date_data[1], $date_data[2], $date_data[0]);
                            $field = komus_selectbox_date($date_stamp, 'short', $field_name);
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required}</label>{$field}\n
HTML;
                            break;
                            
                        case 4:
                            $field = komus_selectbox_date($sys['now_offset'] + 3 * 3600, 'long', $field_name);
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required}</label>{$field}\n
HTML;
                            break;

                        case 5:
                            $value = htmlspecialchars($call_data[$field_name]);
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <input type="text" name="{$field_name}" value="{$value}" /></label>\n
HTML;
                            break;

                        case 6:
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <textarea name="{$field_name}">{$call_data[$field_name]}</textarea></label>\n
HTML;
                            break;
                            
                        case 7:
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <input type="text" name="{$field_name}" value="{$call_data[$field_name]}" /></label>\n
HTML;
                            break;
                            
                        case 8:
                            $field_html = <<<HTML
    <label><span class="label">{$form_fields[$field_name]['title']}</span>{$required} <input type="text" name="{$field_name}" value="{$call_data[$field_name]}" /></label>\n
HTML;
                            break;

                        case 9:
                            $checked = ($call_data[$field_name] == 1) ?
                                ' checked="true"' :
                                '';
                            $field_html = <<<HTML
    <label><input type="checkbox" name="{$field_name}" value="1"{$checked} /> <span class="label">{$form_fields[$field_name]['title']}{$required}</span></label>\n
HTML;
                            break;
                            
                        case 11:
                            $sql_form_string = "
                                SELECT * 
                                FROM {$db_x}komus_references_items
                                WHERE reference_id = {$form_fields[$field_name]['reference_id']}
                                ORDER BY sort
                            ";
                            $sql_form = $db->query($sql_form_string);
                            $field_html = $form_fields[$field_name]['title'] . $required . '<br />';
                            foreach ($sql_form->fetchAll() as $field) {
                                $checked = ($call_data[$field_name] == $field['id']) ?
                                    ' checked="true"' :
                                    '';
                                $field_html .= <<<HTML
    <input type="radio" name="{$field_name}" value="{$field['id']}"{$checked} /><span class="label">{$field['title']}</span>&nbsp;\n
HTML;
                            }
                            break;
                            
                        case 12:
                            $sql_form_string = "
                                SELECT * 
                                FROM {$db_x}komus_references_items
                                WHERE reference_id = {$form_fields[$field_name]['reference_id']}
                                ORDER BY sort
                            ";
                            $sql_form = $db->query($sql_form_string);
                            $field_html = $form_fields[$field_name]['title'] . $required . "<br /><select name=\"{$field_name}\">\n";
                            if ($form_fields[$field_name]['empty_string'] > 0) {
                                $field_html .= <<<HTML
    <option value="0"></option>\n
HTML;
                            }
                            foreach ($sql_form->fetchAll() as $field) {
                                $selected = ($call_data[$field_name] == $field['id']) ?
                                    ' selected="true"' :
                                    '';
                                $field_html .= <<<HTML
    <option value="{$field['id']}"{$selected}>{$field['title']}</option>\n
HTML;
                            }
                            $field_html .= "</select>\n";
                            break;

                        case 13:
                            $hidden_field = 1;
                            $field_html = <<<HTML
    <input type="hidden" name="{$field_name}" value="{$call_data[$field_name]}" />\n
HTML;
                            break;

                        case 15:
                            $field_html = <<<HTML
    <div class="field_text">{$form_fields[$field_name]['title']}</div>\n
HTML;
                            break;

                       case 16:
                            $hidden_field = 1;
                            $field_html = <<<HTML
    <input type="hidden" id="{$field_name}" name="{$field_name}" value="1" />\n
HTML;
                            break;

                        case -1:
                            $sql_form_string = "
                                SELECT *
                                FROM {$db_x}komus_delivery
                                ORDER BY sort
                            ";
                            $sql_form = $db->query($sql_form_string);
                            $field_html = $form_fields[$field_name]['title'] . $required . "<br /><select name=\"{$field_name}\">\n";
                            if ($form_fields[$field_name]['empty_string'] > 0) {
                                $field_html .= <<<HTML
    <option value="0"></option>\n
HTML;
                            }
                            foreach ($sql_form->fetchAll() as $field) {
                                $selected = ($call_data[$field_name] == $field['id']) ?
                                    ' selected="true"' :
                                    '';
                                $field_html .= <<<HTML
    <option value="{$field['id']}"{$selected}>{$field['title']}</option>\n
HTML;
                            }
                            $field_html .= "</select>\n";
                            break;
                        }

                        if ($field_name == 'recall_time') {
                            $node['answer'] .= str_replace($p_match[0][$key1], cot_selectbox_date($sys['now_offset'] + 3 * 3600, 'long', 'recall_time'), $node['abonent_text']);
                        } else {
                            $node['answer'] .= $field_html;
                        }
                        $t->assign(array(
                            'KOMUS_ROW_ANSWER_FIELD' => $field_html
                        ));
                        $t->parse('MAIN.STEP.ROW_ANSWER.FIELD');
                    }

                    $sql_statuses_string = "SELECT id, title FROM {$db_x}komus_statuses WHERE category = 2 ORDER BY sort";
                    $sql_statuses = $db->query($sql_statuses_string);
                    foreach ($sql_statuses as $status) {
                        $t->assign(array(
                            'KOMUS_STATUS_OPTION_TITLE'     => $status['title'],
                            'KOMUS_STATUS_OPTION_VALUE'     => $status['id'],
                            'KOMUS_STATUS_OPTION_SELECTED'  => ($status['id'] == $call_data['call_status']) ? ' selected="selected"' : ''
                        ));
                        $t->parse('MAIN.STEP.ROW_ANSWER.STATUS_OPTION_ROW');
                    }

                    $t->assign(array(
                        'KOMUS_ROW_ANSWER_ACTION'       => cot_url('plug', 'e=komus&mode=step&old_node=' . $node_id . '&node=' . $node['next_question_id'] . '&answer=' . $node['answer_id'] . '&action=' . $action . '&id=' . $contact_id),
                        'KOMUS_ROW_ANSWER_BLOCK_CLASS'  => ($sql_form->rowCount < 3) ? ' width45' : ' width_col',
                        'KOMUS_ROW_ANSWER_BLOCK_STYLE'  => ($sql_form->rowCount < 3) ? ' style="margin: 0 auto"' : '',
                        'KOMUS_ROW_ANSWER_FORM_ID'      => $form_fields[$field_name]['form_id'],
                        'KOMUS_ROW_ANSWER_NEXT_ID'      => $node['to_node'],
                        'KOMUS_ROW_ANSWER_TITLE'        => $form_fields[$field_name]['form_title'],
                        'KOMUS_ROW_ANSWER_TOOLTIP'      => $L['komus_tooltip'],
                        'KOMUS_ROW_ANSWER_CONFIRM'      => false
                    ));
                    
                    $t->parse('MAIN.STEP.ROW_ANSWER');
            } else {
                $sql_statuses_string = "SELECT id, title FROM {$db_x}komus_statuses WHERE category = 2 ORDER BY sort";
                $sql_statuses = $db->query($sql_statuses_string);
                foreach ($sql_statuses as $status) {
                    $t->assign(array(
                        'KOMUS_STATUS_OPTION_TITLE'     => $status['title'],
                        'KOMUS_STATUS_OPTION_VALUE'     => $status['id'],
                        'KOMUS_STATUS_OPTION_SELECTED'  => ($status['id'] == $call_data['call_status']) ? ' selected="selected"' : ''
                    ));
                    $t->parse('MAIN.STEP.ROW_ANSWER.STATUS_OPTION_ROW');
                }
                $t->assign(array(
                    'KOMUS_ROW_ANSWER_ACTION'       => cot_url('plug', 'e=komus&mode=end&action=' . $action . '&id=' . $contact_id),
                    'KOMUS_ROW_ANSWER_BLOCK_CLASS'  => ' width45',
                    'KOMUS_ROW_ANSWER_BLOCK_STYLE'  => ' style="margin: 0 auto"',
                    'KOMUS_ROW_ANSWER_TITLE'        => $L['komus_hang_up'],
                    'KOMUS_ROW_ANSWER_CONFIRM'      => false
                ));
                $t->parse('MAIN.STEP.ROW_ANSWER');
            }
        } else {
            $sql_statuses_string = "SELECT id, title FROM {$db_x}komus_statuses WHERE category = 2 ORDER BY sort";
            $sql_statuses = $db->query($sql_statuses_string);
            
            foreach ($sql_statuses as $status) {
                $t->assign(array(
                    'KOMUS_STATUS_OPTION_TITLE'     => $status['title'],
                    'KOMUS_STATUS_OPTION_VALUE'     => $status['id'],
                    'KOMUS_STATUS_OPTION_SELECTED'  => ($status['id'] == $call_data['call_status']) ? ' selected="selected"' : ''
                ));
                $t->parse('MAIN.STEP.ROW_ANSWER.STATUS_OPTION_ROW');
            }

            $t->assign(array(
                'KOMUS_ROW_ANSWER_ACTION'       => cot_url('plug', 'e=komus&mode=end&action=' . $action . '&id=' . $contact_id),
                'KOMUS_ROW_ANSWER_BLOCK_CLASS'  => ' width45',
                'KOMUS_ROW_ANSWER_BLOCK_STYLE'  => ' style="margin: 0 auto"',
                'KOMUS_ROW_ANSWER_TITLE'        => $L['komus_hang_up'],
                'KOMUS_ROW_ANSWER_CONFIRM'      => true,
                'KOMUS_ROW_ANSWER_FORM_ID'      => $contact_id,
            ));
            $t->parse('MAIN.STEP.ROW_ANSWER');
        }
        
        $errors = (empty($required_string)) ?
            '' :
            $L['komus_required'] . '<br />' . $required_string;
            
        $sql_statuses_string = "SELECT id, title FROM {$db_x}komus_statuses WHERE category = 2 ORDER BY sort";
        $sql_statuses = $db->query($sql_statuses_string);
        
        foreach ($sql_statuses as $status) {
            $t->assign(array(
                'KOMUS_TOP_FINISH_ID'           => $_SESSION['call_id'],
                'KOMUS_TOP_FINISH_OPTION_TITLE' => $status['title'],
                'KOMUS_TOP_FINISH_VALUE'        => $status['id'],
                'KOMUS_TOP_FINISH_SELECTED'     => ($status['id'] == $call_data['call_status']) ? ' selected="selected"' : ''
            ));
            $t->parse('MAIN.STEP.TOP_FINISH_OPTION_ROW');
        }

        $age_confirm = ($call_data['client_age'] < 25 && $call_data['client_age'] > 71) ?
            $L['komus_refusal'] : 
            $L['komus_confirm'];
        
        if (($call_data['main_work_income'] + $call_data['second_work_income'] + $call_data['spouse_income'] / 2) > $call_data['return_sum'] && $call_data['return_sum'] > 0) {
            $income_confirm = $L['komus_confirm'];
        } elseif ($call_data['return_sum'] > 0) {
            $income_confirm = $L['komus_refusal'];
        } else {
            $income_confirm = '';
        }

        if ($call_data['has_work'] == 1 && $income_confirm == $L['komus_confirm'] && $age_confirm == $L['komus_confirm']) {
            $our_client = $L['Yes'];
        } elseif (!empty($income_confirm) && !empty($age_confirm)) {
            $our_client = $L['No'];
        } else {
            $our_client = '';
        }
        //Данные из базы обзвона
        if (!empty($_SESSION['id_base_call'])) {
            $sql_base_string = "
                SELECT *, DATE_FORMAT(bethday, '%d.%m.%Y') bethday
                FROM {$db_x}komus_base            
                WHERE id = {$_SESSION['id_base_call']}
            ";
    	    $sql_base        = $db->query($sql_base_string);
            $base_data       = $sql_base->fetch();
        }
        ////////////////////////
        $sql_references_string = "SELECT id, title, value FROM {$db_x}komus_references_items";
        $sql_references = $db->query($sql_references_string);
        $references = array();
        foreach ($sql_references as $row) {
            $tmp = array();
            $tmp['title'] = $row['title'];
            $tmp['value'] = $row['value'];
            $references[$row['id']] = $tmp;
        }
        $status_call = getReferenceItem($call_data['status_call']);
        $t->assign(array(
            'KOMUS_CALL_RECALL'             => $_SESSION['recall'],
            'KOMUS_STEP_COMMENT'            => $call_data['comment'],
            'KOMUS_STEP_CITY'               => $call_data['city'],
            'KOMUS_STEP_ID'                 => $contact_id,
            'KOMUS_STEP_OPERATOR_TEXT'      => $node_text,
            
            'KOMUS_STEP_KOD'                => $call_data['kod'],
            'KOMUS_STEP_FAMALY'             => $call_data['famaly'],
            'KOMUS_STEP_NAME'               => $call_data['name'],
            'KOMUS_STEP_ONCHESTVO'          => $call_data['otchestvo'],            
            'KOMUS_STEP_ADDRESS'            => $call_data['address'],
            'KOMUS_STEP_PHONE1'             => $call_data['phone1'],
            'KOMUS_STEP_PHONE2'             => $call_data['phone2'],
            'KOMUS_STEP_QUANTITY'           => $call_data['quantity'],
            'KOMUS_STEP_BITHDAY'            => $base_data['bethday'],
                               
            'KOMUS_STEP_TIMEZONE'           => $call_data['time_zone'],
            'KOMUS_STEP_STATUS'             => $call_data['status_call_info'],
            'KOMUS_STEP_QUANTITY_CALL'      => $base_data['kolvo_call'],
            'KOMUS_STEP_ERRORS'             => $errors,
            'KOMUS_STEP_REQUIRED_NAMES_JS'  => $required_names_js,
            'KOMUS_STEP_REQUIRED_TITLES_JS' => $required_titles_js,
            'KOMUS_STEP_REQUIRED_CASE_JS'   => $required_case_js,
            'KOMUS_PAYMENT'                 => $call_data['sum'],
            'KOMUS_INTERRUP_CALL'           => $flag_interrup_call,
            'KOMUS_TOP_BUTTON_ACTION'       => cot_url('plug', 'e=komus&mode=top_button'),
            'KOMUS_NOQUESTION_URL'          => cot_url('plug', 'o=komus&mode=page&al=noquestion')            
        ));

        $t->parse('MAIN.STEP');
    }
    break;
    
case 'top_button':
    $call_id    = cot_import('id', 'P', 'INT');
    $status_id  = cot_import('status', 'P', 'INT');
    
    $contact_id = get_contact_id($call_id);
    
    $status = empty($status_id) ? 
        0 : 
        $status_id;
        
    $update_contact = array(
        'comment'       => cot_import('comment', 'P', 'TXT'),
        'in_work'       => 0,
        'is_finish'     => 1,
        'status_int' => $status_id
    );
    $update_call = array(
        'call_status'   => $status,
        'end_time'      => date('Y-m-d H:i', $sys['now_offset'] + 3 * 3600)
    );
    
    //Завершение
    $sql_call_string = "
            SELECT contacts.*, calls.call_status
            FROM {$db_x}komus_contacts contacts
            LEFT JOIN {$db_x}komus_calls calls ON calls.contact_id = contacts.id
            WHERE contacts.id = $contact_id
    ";
    $sql_call        = $db->query($sql_call_string);
    $call_data       = $sql_call->fetch();
        
    //Завершение звонка
    $id_base_call = $_SESSION['id_base_call'];
    $update_base['status']    = $call_data['status_call'];
    $update_base['comment']   = $call_data['comment'];
        
    foreach ($FieldSinhrArray as $fieldSinhr) {
       $update_base[$fieldSinhr]   = $call_data[$fieldSinhr];	
    }
    
    $update_base['status_int'] = $status_id;
    
    $sql_update = $db->update($db_x . 'komus_contacts', $update_contact, 'id = ' . $contact_id);
    $sql_update = $db->update($db_x . 'komus_calls', $update_call, 'id = ' . $call_id);
    $sql_update = $db->update($db_x . 'komus_base', $update_base, 'id = ' . $_SESSION['id_base_call']);
    
    $_SESSION['call_id'] = '';
    $_SESSION['komus_path'] = '';
    $_SESSION['recall'] = '';
    
    header('Location: ' . cot_url('plug', 'e=komus&mode=next_call', '', true));
    exit;
    break;

default:
   $t->assign(array(
        'KOMUS_TITLE'   => ''
    ));
}

?>
