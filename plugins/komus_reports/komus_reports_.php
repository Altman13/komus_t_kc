<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * Komus Reports Plugin for Cotonti CMF
 *
 * @package komus_reports
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

/*========================================*/
$max_calls = 4;
$processing_duration_short  = 20;
$processing_duration_normal = 60;
/*========================================*/

require_once cot_incfile('forms');
require_once cot_langfile('komus_reports');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('users', 'w');

$gr_operator_groups = array(5, 6);
$operator_groups = array();

$gr_operator_access = false;
$operator_access = false;

$sql_user_string = "SELECT gru_groupid FROM {$db_x}groups_users WHERE gru_userid = {$usr['id']}";
$sql_user = $db->query($sql_user_string);

foreach ($sql_user->fetchAll() as $group) {
    if (in_array($group['gru_groupid'], $gr_operator_groups)) {
        $gr_operator_access = true;
    }
    if (in_array($group['gru_groupid'], $operator_groups)) {
        $operator_access = true;
    }
}

$plugin_title = $L['komus_reports_title'];

$mode = cot_import('mode', 'G', 'ALP');

switch ($mode) {
case 'report':
    $rep = cot_import('rep', 'G', 'INT');
    $select_date = cot_import('date', 'G', 'BOL');
    
    if (empty($_POST) && $select_date) {
        $t->assign(array(
            'KOMUS_CREATE_ACTION'     => cot_url('plug', 'e=komus_reports&mode=report&date=1&rep=' . $rep),
            'KOMUS_CREATE_FROM_DATE'  => cot_selectbox_date($sys['now_offset'], 'short', 'from'),
            'KOMUS_CREATE_TO_DATE'    => cot_selectbox_date($sys['now_offset'], 'short', 'to')
        ));
        $t->parse('MAIN.REPORT');
    } else {
        require_once('plugins/komus_reports/inc/komus_reports.xls_tpl.php');

        if ($select_date) {
            $from = cot_import('from', 'P', 'ARR');
            $from_sql = $from['year'] . '-' . $from['month'] . '-' . $from['day'] . ' 00:00:00';
            $to = cot_import('to', 'P', 'ARR');
            $to_sql = $to['year'] . '-' . $to['month'] . '-' . $to['day'] . ' 23:59:59';
        }

        $current_user_sql = ($operator_access) ?
            ' AND d.user_id = ' . $usr['id'] : 
            '';

        $sql_references_string = "SELECT id, title FROM {$db_x}komus_references_items";
        $sql_references = $db->query($sql_references_string);
        $references = array();
        foreach ($sql_references as $row) {
            $references[$row['id']] = $row['title'];
        }
            
        $sql_statuses_string = "SELECT id, title FROM {$db_x}komus_statuses";
        $sql_statuses = $db->query($sql_statuses_string);
        $statuses = array();
        foreach ($sql_statuses->fetchAll() as $status) {
            $statuses[$status['id']] = $status['title'];
        }

/*===========================================================*/
/*=============== Отчеты ====================================*/
/*===========================================================*/
        
        switch ($rep) {
/*---------------------------------------------*/
/*---------- Основной отчет -------------------*/
/*---------------------------------------------*/
        case 1:
            $contact_id = cot_import('id', 'G', 'INT');
            $rep_name = 'ftp';
            $select_date = false;
            
            $sql_report_string = "
                SELECT c.*, DATE_FORMAT(c.creation_time, '%d.%m.%Y') fill_date, DATE_FORMAT(c.birthday, '%d.%m.%Y') birthday, DATE_FORMAT(c.passport_date, '%d.%m.%Y') passport_date, DATE_FORMAT(c.child1_birth, '%d.%m.%Y') child1_birth, DATE_FORMAT(c.child2_birth, '%d.%m.%Y') child2_birth, DATE_FORMAT(c.child3_birth, '%d.%m.%Y') child3_birth, DATE_FORMAT(c.doc1_date, '%d.%m.%Y') doc1_date, DATE_FORMAT(c.doc2_date, '%d.%m.%Y') doc2_date, DATE_FORMAT(c.spouse_birthday, '%d.%m.%Y') spouse_birthday
                FROM {$db_x}komus_contacts c
                WHERE c.id = $contact_id
            ";
            $sql_report = $db->query($sql_report_string);
            $row = $sql_report->fetch();

            $has_work               = getReferenceItem($row['has_work']);
            $martial_status         = getReferenceItem($row['martial_status_id']);
            $residing               = getReferenceItem($row['residing_id']);
            $child1_gender          = getReferenceItem($row['child1_gender']);
            $child1_kipper          = getReferenceItem($row['child1_kipper']);
            $child1_residing        = getReferenceItem($row['child1_residing']);
            $child2_gender          = getReferenceItem($row['child2_gender']);
            $child2_kipper          = getReferenceItem($row['child2_kipper']);
            $child2_residing        = getReferenceItem($row['child2_residing']);   
            $child3_gender          = getReferenceItem($row['child3_gender']);
            $child3_kipper          = getReferenceItem($row['child3_kipper']);
            $child3_residing        = getReferenceItem($row['child3_residing']);
            $residence_property     = getReferenceItem($row['has_live_place']);
            $residence_property     = getReferenceItem($row['residence_property_id']);
            $certificate_granting   = getReferenceItem($row['certificate_granting_id']);
            $residence_rent_type    = getReferenceItem($row['residence_rent_type_id']);
            $education              = getReferenceItem($row['education']);
            $diploma                = getReferenceItem($row['diploma']);
            $current_work           = getReferenceItem($row['current_work_id']);
            $main_work_position     = getReferenceItem($row['main_work_position_id']);
            $main_work_company_type = getReferenceItem($row['main_work_company_type_id']);
            $temporary_work         = getReferenceItem($row['temporary_work']);
            $spouse_position        = getReferenceItem($row['spouse_position_id']);
            $father_working         = getReferenceItem($row['father_working']);
            $father_pensioner       = getReferenceItem($row['father_pensioner']);
            $mother_working         = getReferenceItem($row['mother_working']);
            $mother_pensioner       = getReferenceItem($row['mother_pensioner']);
            $relativies1_type       = getReferenceItem($row['relativies1_type']);
            $relativies1_working    = getReferenceItem($row['relativies1_working']);
            $relativies2_type       = getReferenceItem($row['relativies2_type']);
            $relativies2_working    = getReferenceItem($row['relativies2_working']);
            $relativies3_type       = getReferenceItem($row['relativies3_type']);
            $relativies3_working    = getReferenceItem($row['relativies3_working']);
            $has_car                = getReferenceItem($row['has_car']);
            $car_given_pts          = getReferenceItem($row['car_given_pts']);
            $rest_russia_home       = getReferenceItem($row['rest_russia_home']);
            $rest_russia_residence  = getReferenceItem($row['rest_russia_residence']);
            $rest_residence_property = getReferenceItem($row['rest_residence_property']);
            $rest_tourism           = getReferenceItem($row['rest_tourism']);
            $rest_abroad            = getReferenceItem($row['rest_abroad']);               
            $info_source            = getReferenceItem($row['info_source']);                

            $t->assign(array(
                'KOMUS_REPORTS_FILL_DATE'               => $row['fill_date'],
                'KOMUS_REPORTS_REGION_ID'               => $row['region_id'],
                'KOMUS_REPORTS_HAS_WORK'                => $has_work,
                'KOMUS_REPORTS_BIRTHDAY'                => $row['birthday'],
                'KOMUS_REPORTS_AGE_CONFIRM'             => $age_confirm,
                'KOMUS_REPORTS_INCOME_CONFIRM'          => $income_confirm,
                'KOMUS_REPORTS_OUR_CLIENT'              => $our_client,
                'KOMUS_REPORTS_SUM'                     => $row['sum'],
                'KOMUS_REPORTS_RETURN_SUM'              => $row['return_sum'],
                'KOMUS_REPORTS_RETURN_TERM'             => $row['return_term'],
                'KOMUS_REPORTS_LASTNAME'                => $row['lastname'],
                'KOMUS_REPORTS_FIRSTNAME'               => $row['firstname'],
                'KOMUS_REPORTS_SURNAME'                 => $row['surname'],
                'KOMUS_REPORTS_PHONE_MOB'               => $row['phone_mob'],
                'KOMUS_REPORTS_PASSPORT_SERIES'         => $row['passport_series'],
                'KOMUS_REPORTS_PASSPORT_NUMBER'         => $row['passport_number'],
                'KOMUS_REPORTS_PASSPORT_ESTABLISHMENT'  => $row['passport_establishment'],
                'KOMUS_REPORTS_PASSPORT_DATE'           => $row['passport_date'],
                'KOMUS_REPORTS_CLIENT_AGE'              => $row['client_age'],
                'KOMUS_REPORTS_MARTIAL_STATUS'          => $martial_status,
                'KOMUS_REPORTS_RESIDING'                => $residing,
                'KOMUS_REPORTS_CHILD1_GENDER'           => $child1_gender,
                'KOMUS_REPORTS_CHILD1_BIRTH'            => $row['child1_birth'],
                'KOMUS_REPORTS_CHILD1_KIPPER'           => $child1_kipper,
                'KOMUS_REPORTS_CHILD1_RESIDING'         => $child1_residing,
                'KOMUS_REPORTS_CHILD2_GENDER'           => $child2_gender,
                'KOMUS_REPORTS_CHILD2_BIRTH'            => $row['child2_birth'],
                'KOMUS_REPORTS_CHILD2_KIPPER'           => $child2_kipper,
                'KOMUS_REPORTS_CHILD2_RESIDING'         => $child2_residing,
                'KOMUS_REPORTS_CHILD3_GENDER'           => $child3_gender,
                'KOMUS_REPORTS_CHILD3_BIRTH'            => $row['child3_birth'],
                'KOMUS_REPORTS_CHILD3_KIPPER'           => $child3_kipper,
                'KOMUS_REPORTS_CHILD3_RESIDING'         => $child3_residing,
                'KOMUS_REPORTS_DOC1_SERIES'             => $row['doc1_series'],
                'KOMUS_REPORTS_DOC1_NUMBER'             => $row['doc1_number'],
                'KOMUS_REPORTS_DOC1_ESTABLISHMENT'      => $row['doc1_establishment'],
                'KOMUS_REPORTS_DOC1_DATE'               => $row['doc1_date'],
                'KOMUS_REPORTS_DOC2_SERIES'             => $row['doc2_series'],
                'KOMUS_REPORTS_DOC2_NUMBER'             => $row['doc2_number'],
                'KOMUS_REPORTS_DOC2_ESTABLISHMENT'      => $row['doc2_establishment'],
                'KOMUS_REPORTS_DOC2_DATE'               => $row['doc2_date'],
                'KOMUS_REPORTS_REG_CITY'                => $row['reg_city'],
                'KOMUS_REPORTS_REG_STREET'              => $row['reg_street'],
                'KOMUS_REPORTS_REG_HOUSE'               => $row['reg_house'],
                'KOMUS_REPORTS_REG_APARTMENT'           => $row['reg_apartment'],
                'KOMUS_REPORTS_REG_FLOOR'               => $row['reg_floor'],
                'KOMUS_REPORTS_REG_PHONE_HOUSE'         => $row['reg_phone_house'],
                'KOMUS_REPORTS_LIVE_PLACE'              => $row['has_live_place'],
                'KOMUS_REPORTS_LIVE_CITY'               => $row['live_city'],
                'KOMUS_REPORTS_LIVE_STREET'             => $row['live_street'],
                'KOMUS_REPORTS_LIVE_HOUSE'              => $row['live_house'],
                'KOMUS_REPORTS_LIVE_APARTMENT'          => $row['live_apartment'],
                'KOMUS_REPORTS_LIVE_FLOOR'              => $row['live_floor'],
                'KOMUS_REPORTS_LIVE_PHONE_HOUSE'        => $row['live_phone_house'],
                'KOMUS_REPORTS_RESIDENCE_PROPERTY'      => $residence_property,
                'KOMUS_REPORTS_CERTIFICATE_GRANTING'    => $certificate_granting,
                'KOMUS_REPORTS_RENT_TYPE'               => $residence_rent_type,
                'KOMUS_REPORTS_EMAIL'                   => $row['email'],
                'KOMUS_REPORTS_SKYPE'                   => $row['skype'],
                'KOMUS_REPORTS_ICQ'                     => $row['icq'],
                'KOMUS_REPORTS_EDUCATION'               => $education,
                'KOMUS_REPORTS_DIPLOMA'                 => $diploma,
                'KOMUS_REPORTS_CURRENT_WORK'            => $current_work,
                'KOMUS_REPORTS_MAIN_WORK_COMPANY'       => $row['main_work_company'],
                'KOMUS_REPORTS_MAIN_WORK_ADDRESS'       => $row['main_work_address'],
                'KOMUS_REPORTS_MAIN_WORK_CHIEF_NAME'    => $row['main_work_chief_name'],
                'KOMUS_REPORTS_MAIN_WORK_PHONE'         => $row['main_work_phone'],
                'KOMUS_REPORTS_MAIN_WORK_REMOTENESS'    => $row['main_work_remoteness'],
                'KOMUS_REPORTS_MAIN_WORK_TIME'          => $row['main_work_time'],
                'KOMUS_REPORTS_MAIN_WORK_SALARY'        => $row['main_work_salary'],
                'KOMUS_REPORTS_MAIN_WORK_INCOME'        => $row['main_work_income'],
                'KOMUS_REPORTS_MAIN_WORK_POSITION'      => $main_work_position,
                'KOMUS_REPORTS_MAIN_WORK_COMPANY_TYPE'  => $main_work_company_type,
                'KOMUS_REPORTS_MAIN_WORK_QUANTITY'      => $row['main_work_quantity'],
                'KOMUS_REPORTS_SECOND_WORK_COMPANY'      => $row['second_work_company'],
                'KOMUS_REPORTS_SECOND_WORK_ADDRESS'      => $row['second_work_address'],
                'KOMUS_REPORTS_SECOND_WORK_CHIEF_NAME'   => $row['second_work_chief_name'],
                'KOMUS_REPORTS_SECOND_WORK_PHONE'        => $row['second_work_phone'],
                'KOMUS_REPORTS_SECOND_WORK_REMOTENESS'   => $row['second_work_remoteness'],
                'KOMUS_REPORTS_SECOND_WORK_TIME'         => $row['second_work_time'],
                'KOMUS_REPORTS_SECOND_WORK_SALARY'       => $row['second_work_salary'],
                'KOMUS_REPORTS_SECOND_WORK_INCOME'       => $row['second_work_income'],
                'KOMUS_REPORTS_LAST_WORK_SENIORITY'     => $row['last_work_seniority'],
                'KOMUS_REPORTS_PREV_WORK_SENIORITY'     => $row['prev_work_seniority'],
                'KOMUS_REPORTS_WORKS_QUANTITY'          => $row['works_quantity'],
                'KOMUS_REPORTS_TEMPORARY_WORK'          => $temporary_work,
                'KOMUS_REPORTS_SPOUSE_LASTNAME'         => $row['spouse_lastname'],
                'KOMUS_REPORTS_SPOUSE_FIRSTNAME'        => $row['spouse_firstname'],
                'KOMUS_REPORTS_SPOUSE_PATRONYMIC'       => $row['spouse_patronymic'],
                'KOMUS_REPORTS_SPOUSE_BIRTHDAY'         => $row['spouse_birthday'],
                'KOMUS_REPORTS_SPOUSE_PHONE'            => $row['spouse_phone'],
                'KOMUS_REPORTS_SPOUSE_COMPANY'          => $row['spouse_company'],
                'KOMUS_REPORTS_SPOUSE_COMPANY_TYPE'     => $row['spouse_company_type'],
                'KOMUS_REPORTS_SPOUSE_POSITION'         => $spouse_position,
                'KOMUS_REPORTS_SPOUSE_INCOME'           => $row['spouse_income'],
                'KOMUS_REPORTS_FATHER_NAME'             => $row['father_name'],
                'KOMUS_REPORTS_FATHER_PHONE'            => $row['father_phone'],
                'KOMUS_REPORTS_FATHER_AGE'              => $row['father_age'],
                'KOMUS_REPORTS_FATHER_WORKING'          => $father_working,
                'KOMUS_REPORTS_FATHER_PENSIONER'        => $father_pensioner,
                'KOMUS_REPORTS_MOTHER_NAME'             => $row['mother_name'],
                'KOMUS_REPORTS_MOTHER_PHONE'            => $row['mother_phone'],
                'KOMUS_REPORTS_MOTHER_AGE'              => $row['mother_age'],
                'KOMUS_REPORTS_MOTHER_WORKING'          => $mother_working,
                'KOMUS_REPORTS_MOTHER_PENSIONER'        => $row['mother_pensioner'],
                'KOMUS_REPORTS_RELATIVIES1_TYPE'        => $relativies1_type,
                'KOMUS_REPORTS_RELATIVIES1_AGE'         => $relativies1_age,
                'KOMUS_REPORTS_RELATIVIES1_WORKING'     => $relativies1_working,
                'KOMUS_REPORTS_RELATIVIES1_PHONE'       => $row['relativies1_phone'],
                'KOMUS_REPORTS_RELATIVIES2_TYPE'        => $relativies2_type,
                'KOMUS_REPORTS_RELATIVIES2_AGE'         => $row['relativies2_age'],
                'KOMUS_REPORTS_RELATIVIES2_WORKING'     => $relativies2_working,
                'KOMUS_REPORTS_RELATIVIES2_PHONE'       => $row['relativies2_phone'],
                'KOMUS_REPORTS_RELATIVIES3_TYPE'        => $relativies3_type,
                'KOMUS_REPORTS_RELATIVIES3_AGE'         => $row['relativies3_age'],
                'KOMUS_REPORTS_RELATIVIES3_WORKING'     => $relativies3_working,
                'KOMUS_REPORTS_RELATIVIES3_PHONE'       => $row['relativies3_phone'],
                'KOMUS_REPORTS_HAS_CAR'                 => $has_car,
                'KOMUS_REPORTS_CAR_COUNTRY'             => $row['car_country'],
                'KOMUS_REPORTS_CAR_YEAR'                => $row['car_year'],
                'KOMUS_REPORTS_CAR_AGE'                 => $row['car_age'],
                'KOMUS_REPORTS_CAR_GIVEN_PTS'           => $car_given_pts,
                'KOMUS_REPORTS_CAR_STORAGE'             => $row['car_storage'],
                'KOMUS_REPORTS_REST_RUSSIA_HOME'        => $rest_russia_home,
                'KOMUS_REPORTS_REST_RUSSIA_RESIDENCE'   => $rest_russia_residence,
                'KOMUS_REPORTS_REST_RESIDENCE_PROPERTY' => $rest_residence_property,
                'KOMUS_REPORTS_REST_TOURISM'            => $rest_tourism,
                'KOMUS_REPORTS_REST_ABROAD'             => $rest_abroad,
                'KOMUS_REPORTS_INFO_SOURCE'             => $info_source
            ));
            $t->parse('MAIN.FTP_PHYSICAL_REPORT');

            $xls_report = <<<HTML
              <tr> <td>Дата заполнения</td><td>{$row['fill_date']}</td> </tr>
              <tr> <td>Регион</td><td>{$row['region_id']}</td> </tr>
              <tr> <td>Наличие постоянной работы</td><td>{$has_work}</td> </tr>
              <tr> <td>Дата рождения</td><td>{$row['birthday']}</td> </tr>
              <tr> <td>ОТКАЗ / СОГЛАСОВАНО по возрасту</td><td>{KOMUS_REPORTS_AGE_CONFIRM}</td><td>ОТКАЗ / СОГЛАСОВАНО по доходу</td><td>{KOMUS_REPORTS_INCOME_CONFIRM}</td> </tr>
              <tr> <td>НАШ КЛИЕНТ</td><td>{KOMUS_REPORTS_OUR_CLIENT}</td> </tr>
              <tr> <td>Запрашиваемая сумма</td><td>{$row['sum']}</td> </tr>
              <tr> <td>Срок</td><td>{$row['return_term']}</td> </tr>
              <tr> <td>Ставка</td><td>1,5%</td> </tr>
              <tr> <td>Сумма возврата</td><td>{$row['return_sum']}</td> </tr>
              <tr> <td>Фамилия</td><td>{$row['lastname']}</td> </tr>
              <tr> <td>Имя</td><td>{$row['firstname']}</td> </tr>
              <tr> <td>Отчество</td><td>{$row['surname']}</td> </tr>
              <tr> <td>Мобильный телефон</td><td>{$row['phone_mob']}</td> </tr>
              <tr> <td class="section" colspan="2">Данные паспорта</td> </tr>
              <tr> <td>серия</td><td>{$row['passport_series']}</td> </tr>
              <tr> <td>номер</td><td>{$row['passport_number']}</td> </tr>
              <tr> <td>кем выдан</td><td>{$row['passport_establishment']}</td> </tr>
              <tr> <td>дата выдачи</td><td>{$row['passport_date']}</td> </tr>
              <tr> <td>Кол-во полных лет</td><td>{$row['client_age']}</td> </tr>
              <tr> <td>Семейное положение</td><td>{$martial_status}</td> </tr>
              <tr> <td>Проживание</td><td>{$residing}</td> </tr>
              <tr> <td class="section" colspan="2">Дети</td> </tr>
              <tr> <td>Пол</td><td>{$child1_gender}</td> </tr>
              <tr> <td>Год рождения</td><td>{$row['child1_birth']}</td> </tr>
              <tr> <td>Иждевенец</td><td>{$child1_kipper}</td> </tr>
              <tr> <td>Проживание по отношению к детям</td><td>{$child1_residing}</td> </tr>
              <tr> <td>Пол</td><td>{$child2_gender}</td> </tr>
              <tr> <td>Год рождения</td><td>{$row['child2_birth']}</td> </tr>
              <tr> <td>Иждевенец</td><td>{$child2_kipper}</td> </tr>
              <tr> <td>Проживание по отношению к детям</td><td>{$child2_residing}</td> </tr>
              <tr> <td>Пол</td><td>{$child3_gender}</td> </tr>
              <tr> <td>Год рождения</td><td>{$row['child3_birth']}</td> </tr>
              <tr> <td>Иждевенец</td><td>{$child3_kipper}</td> </tr>
              <tr> <td>Проживание по отношению к детям</td><td>{$child3_residing}</td> </tr>
              <tr> <td>Иной документ 1</td><td></td> </tr>
              <tr> <td>серия</td><td>{$row['doc1_series']}</td> </tr>
              <tr> <td>номер</td><td>{$row['doc1_number']}</td> </tr>
              <tr> <td>кем выдан</td><td>{$row['doc1_establishment']}</td> </tr>
              <tr> <td>дата выдачи</td><td>{$row['doc1_date']}</td> </tr>
              <tr> <td>Иной документ 2</td><td></td> </tr>
              <tr> <td>серия</td><td>{$row['doc2_series']}</td> </tr>
              <tr> <td>номер</td><td>{$row['doc2_number']}</td> </tr>
              <tr> <td>кем выдан</td><td>{$row['doc2_establishment']}</td> </tr>
              <tr> <td>дата выдачи</td><td>{$row['doc2_date']}</td> </tr>
              <tr> <td class="section" colspan="2">Место регистрации</td> </tr>
              <tr> <td>Населенный пункт</td><td>{$row['reg_city']}</td> </tr>
              <tr> <td>Улица</td><td>{$row['reg_street']}</td> </tr>
              <tr> <td>Дом</td><td>{$row['reg_house']}</td> </tr>
              <tr> <td>Квартира</td><td>{$row['reg_apartment']}</td> </tr>
              <tr> <td>Этаж</td><td>{$row['reg_floor']}</td> </tr>
              <tr> <td>Домашний телефон</td><td>{$row['reg_phone_house']}</td> </tr>
              <tr> <td>Место фактического проживания</td><td>{$row['has_live_place']}</td> </tr>
              <tr> <td>Населенный пункт</td><td>{$row['live_city']}</td> </tr>
              <tr> <td>Улица</td><td>{$row['live_street']}</td> </tr>
              <tr> <td>Дом</td><td>{$row['live_house']}</td> </tr>
              <tr> <td>Квартира</td><td>{$row['live_apartment']}</td> </tr>
              <tr> <td>Этаж</td><td>{$row['live_floor']}</td> </tr>
              <tr> <td>Домашний телефон</td><td>{$row['live_phone_house']}</td> </tr>
              <tr> <td>Данные о месте проживания</td><td>{$residence_property}</td> </tr>
              <tr> <td>Готов предоставить свид-во?</td><td>{$certificate_granting}</td> </tr>
              <tr> <td>С кем?</td><td>{$residence_rent_type}</td> </tr>
              <tr> <td>Адрес электронной почты</td><td>{$row['email']}</td> </tr>
              <tr> <td class="section" colspan="2">Дополнительная связь</td> </tr>
              <tr> <td>Skype</td><td>{$row['skype']}</td> </tr>
              <tr> <td>ICQ</td><td>{$row['icq']}</td> </tr>
              <tr> <td>Образование</td><td>{$education}</td> </tr>
              <tr> <td>Диплом</td><td>{$diploma}</td> </tr>
              <tr> <td>Текущая работа</td><td>{$current_work}</td> </tr>
              <tr> <td class="section" colspan="2">Место основной работы</td> </tr>
              <tr> <td>Наименование</td><td>{$row['main_work_company']}</td> </tr>
              <tr> <td>Адрес</td><td>{$row['main_work_address']}</td> </tr>
              <tr> <td>Ф.И.О. руководителя</td><td>{$row['main_work_chief_name']}</td> </tr>
              <tr> <td>Телефон</td><td>{$row['main_work_phone']}</td> </tr>
              <tr> <td>Удаленность</td><td>{$row['main_work_remoteness']}</td> </tr>
              <tr> <td>График работы</td><td>{$row['main_work_time']}</td> </tr>
              <tr> <td>Зарплата</td><td>{$row['main_work_salary']}</td> </tr>
              <tr> <td>Доход</td><td>{$row['main_work_income']}</td> </tr>
              <tr> <td>Должность</td><td>{$main_work_position}</td> </tr>
              <tr> <td>Тип организации</td><td>{$main_work_company_type}</td> </tr>
              <tr> <td>Кол-во сотрудников</td><td>{$row['main_work_quantity']}</td> </tr>
              <tr> <td class="section" colspan="2">Место дополнительной работы</td> </tr>
              <tr> <td>Наименование</td><td>{$row['second_work_company']}</td> </tr>
              <tr> <td>Адрес</td><td>{$row['second_work_address']}</td> </tr>
              <tr> <td>Ф.И.О. руководителя</td><td>{$row['second_work_chief_name']}</td> </tr>
              <tr> <td>Телефон</td><td>{$row['second_work_phone']}</td> </tr>
              <tr> <td>Удаленность</td><td>{$row['second_work_remoteness']}</td> </tr>
              <tr> <td>График работы</td><td>{$row['second_work_time']}</td> </tr>
              <tr> <td>Зарплата</td><td>{$row['second_work_salary']}</td> </tr>
              <tr> <td>Доход</td><td>{$row['second_work_income']}</td> </tr>
              <tr> <td class="section" colspan="2">Трудовой стаж</td> </tr>
              <tr> <td>на последнем месте</td><td>{$row['last_work_seniority']}</td> </tr>
              <tr> <td>на предыдущем месте</td><td>{$row['prev_work_seniority']}</td> </tr>
              <tr> <td>кол-во мест за последние 3 года</td><td>{$row['works_quantity']}</td> </tr>
              <tr> <td>временная работа</td><td>{$temporary_work}</td> </tr>
              <tr> <td class="section" colspan="2">Данные супруги/супруга</td> </tr>
              <tr> <td>Фамилия</td><td>{$row['spouse_lastname']}</td> </tr>
              <tr> <td>Имя</td><td>{$row['spouse_firstname']}</td> </tr>
              <tr> <td>Отчество</td><td>{$row['spouse_patronymic']}</td> </tr>
              <tr> <td>Год рождения</td><td>{$row['spouse_birthday']}</td> </tr>
              <tr> <td>Телефон</td><td>{$row['spouse_phone']}</td> </tr>
              <tr> <td>Наименование организации</td><td>{$row['spouse_company']}</td> </tr>
              <tr> <td>Вид организации</td><td>{$row['spouse_company_type']}</td> </tr>
              <tr> <td>Должность</td><td>{$spouse_position}</td> </tr>
              <tr> <td>Доход</td><td>{$row['spouse_income']}</td> </tr>
              <tr> <td class="section" colspan="2">Родственники</td> </tr>
              <tr> <td class="section" colspan="2">Отец</td> </tr>
              <tr> <td>Ф.И.О.</td><td>{$row['father_name']}</td> </tr>
              <tr> <td>Телефон</td><td>{$row['father_phone']}</td> </tr>
              <tr> <td>Возраст</td><td>{$row['father_age']}</td> </tr>
              <tr> <td>Работает</td><td>{$father_working}</td> </tr>
              <tr> <td>Пенсионер</td><td>{$father_pensioner}</td> </tr>
              <tr> <td class="section" colspan="2">Мать</td> </tr>
              <tr> <td>Ф.И.О.</td><td>{$row['mother_name']}</td> </tr>
              <tr> <td>Телефон</td><td>{$row['mother_phone']}</td> </tr>
              <tr> <td>Возраст</td><td>{$row['mother_age']}</td> </tr>
              <tr> <td>Работает</td><td>{$mother_working}</td> </tr>
              <tr> <td>Пенсионер</td><td>{$row['mother_pensioner']}</td> </tr>
              <tr> <td>Брат/сестра</td><td>{$relativies1_type}</td> </tr>
              <tr> <td>возраст</td><td>{$relativies1_age}</td> </tr>
              <tr> <td>работает </td><td>{$relativies1_working}</td> </tr>
              <tr> <td>телефон</td><td>{$row['relativies1_phone']}</td> </tr>
              <tr> <td>Брат/сестра</td><td>{$relativies2_type}</td> </tr>
              <tr> <td>возраст</td><td>{$row['relativies2_age']}</td> </tr>
              <tr> <td>работает </td><td>{$relativies2_working}</td> </tr>
              <tr> <td>телефон</td><td>{$row['relativies2_phone']}</td> </tr>
              <tr> <td>Брат/сестра</td><td>{$relativies3_type}</td> </tr>
              <tr> <td>возраст</td><td>{$row['relativies3_age']}</td> </tr>
              <tr> <td>работает </td><td>{$relativies3_working}</td> </tr>
              <tr> <td>телефон</td><td>{$row['relativies3_phone']}</td> </tr>
              <tr> <td>Наличие автомобиля</td><td>{$has_car}</td> </tr>
              <tr> <td>Страна</td><td>{$row['car_country']}</td> </tr>
              <tr> <td>Год выпуска</td><td>{$row['car_year']}</td> </tr>
              <tr> <td>Полных лет авто</td><td>{$row['car_age']}</td> </tr>
              <tr> <td>ПТС предоставил</td><td>{$car_given_pts}</td> </tr>
              <tr> <td>Хранение</td><td>{$row['car_storage']}</td> </tr>
              <tr> <td class="section" colspan="2">Отдых</td> </tr>
              <tr> <td>Россия Дома</td><td>{$rest_russia_home}</TD> </tr>
              <tr> <td>Россия Дача</td><td>{$rest_russia_residence}</td> </tr>
              <tr> <td>Принадлежность дачи</td><td>{$rest_residence_property}</td> </tr>
              <tr> <td>Россия туризм </td><td>{$rest_tourism}</td> </tr>
              <tr> <td>Заграница</td><td>{$rest_abroad}</td> </tr>
              <tr> <td>Откуда узнали о нас</td><td>{$info_source}</td> </tr>
HTML;
            break;
        }
        
        $xls_out = <<<HTML
        {$xls_report_header}
        <h2>{$xls_title}</h2>
        <table class="xls_table">
          {$xls_report_th}
HTML;
          $xls_out .= $xls_report;
        $xls_out .= <<<HTML
          {$xls_report_footer}\n
HTML;
       
        if ($gr_operator_access) {
            $xls_filename = 'report_' . $rep_name . '_' . $contact_id  . '.xls';
            $xls_file = fopen('reports/' . $xls_filename, 'w');
            fwrite($xls_file, $xls_out);
            fclose($xls_file);
            $t->assign(array(
                'KOMUS_REPORTS_XLS_FILENAME' => $xls_filename
            ));
        }
        
        $t->assign(array(
            'KOMUS_REPORTS_SELECT_DATE' => $select_date,
            'KOMUS_REPORTS_XLS_OUT'     => $xls_out,
            'KOMUS_REPORTS_TITLE'       => $L['komus_reports_title']
        ));
        $t->parse('MAIN.XLS_OUT');

        $conn_id = ftp_connect('10.0.16.202');
        $login_result = ftp_login($conn_id, 'NK', 'NKazFKom');
        if (ftp_put($conn_id, $xls_filename, $_SERVER['DOCUMENT_ROOT'] . '/reports/' . $xls_filename, FTP_ASCII)) {
             echo "Файл <strong>$xls_filename</strong> успешно загружен.\n";
        } else {
             echo "Невозможно загрузить файл. Сообщите об этом разработчику программы.\n";
        }
        ftp_close($conn_id);

    }
    break;
        
default:
    if ($gr_operator_access) {
        $contact_id = cot_import('id', 'G', 'INT');
        $t->assign(array(
            'KOMUS_REPORTS_FTP_URL' => cot_url('plug', 'e=komus_reports&mode=report&select_date=0&rep=1&id=' . $contact_id)
        ));
        $t->parse('MAIN.HOME.GRAND_OPERATOR');
    }

    if ($operator_access) {
        $t->assign(array(
        ));
        $t->parse('MAIN.HOME.OPERATOR');
    }
    $t->assign(array(
        'KOMUS_REPORTS_TITLE' => $L['komus_reports_title']
    ));
    $t->parse('MAIN.HOME');
}


?>
