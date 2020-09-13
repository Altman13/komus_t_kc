<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin interface for Komus Reports plugin
 *
 * @package komus_reports
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('komus', 'plug');

$tuman = new XTemplate(cot_tplfile('komus_reports.tools', 'plug'));

$mode = cot_import('mode', 'G', 'ALP');

switch ($mode) {
case 'create':
    $from = cot_import('from', 'P', 'ARR');
    $from_month = (strlen($from['month']) == 1) ? 
        '0' . $from['month'] : 
        $from['month'];
    $from_day = (strlen($from['day']) == 1) ? 
        '0' . $from['day'] : 
        $from['day'];
    $from_sql = $from['year'] . '-' . $from_month . '-' . $from_day . ' 00:00:00';
    
    $to = cot_import('to', 'P', 'ARR');
    $to_month = (strlen($to['month']) == 1) ? 
        '0' . $to['month'] : 
        $to['month'];
    $to_day = (strlen($to['day']) == 1) ? 
        '0' . $to['day'] : 
        $to['day'];
    $to_sql = $to['year'] . '-' . $to_month . '-' . $to_day . ' 23:59:59';
    
    $out_report_header = <<<HTML
    <html>
    <head>
      <style>
        .xls_table, .xls_table th, .xls_table td { border-collapse: collapse; border: 1px solid #000000; }
        .xls_table th { background-color: #cccccc; vertical-align: middle; }
        .xls_table td { vertical-align: top; }
      </style>
    </head>
    <body>
    <h2>Отчет по заявкам</h2>
    <table class="xls_table">
      <tr>
        <th>Район</th>
        <th>№ заявления</th>
        <th>Дата оформления</th>
        <th>Время начала разговора</th>
        <th>Фамилия, имя, отчество</th>
        <th>Дата рождения</th>
        <th>Город</th>
        <th>Улица</th>
        <th>Дом</th>
        <th>Корпус/строение</th>
        <th>Подъезд</th>
        <th>Этаж</th>
        <th>Квартира</th>
        <th>Домофон / код</th>
        <th>Дом. телефон</th>
        <th>Моб. телефон</th>
        <th>Раб. телефон</th>
        <th>Тариф интернет</th>
        <th>Тариф ЦТВ</th>
        <th>Абон. оборудование</th>
        <th>Способ подключения</th>
        <th>Наличие тех. отверстия</th>
        <th>Желаемая дата подключ.</th>
        <th>Является клиентом</th>
        <th>Комментарий</th>
      </tr>\n
HTML;

    $sql_report_string = "
        SELECT d.*, int_tar.title internet_tar, ctv_tar.title ctv_tar, abon_oborud.title ab_oborud, sposob_podkluch.title sposob_podkl, teh_otverstie.title teh_otverst
        FROM {$db_x}komus_data d
        LEFT JOIN {$db_x}komus_references_items int_tar ON int_tar.id = d.internet_tarif
        LEFT JOIN {$db_x}komus_references_items ctv_tar ON ctv_tar.id = d.ctv_tarif
        LEFT JOIN {$db_x}komus_references_items abon_oborud ON abon_oborud.id = d.abon_oborud
        LEFT JOIN {$db_x}komus_references_items sposob_podkluch ON sposob_podkluch.id = d.sposob_podkluch
        LEFT JOIN {$db_x}komus_references_items teh_otverstie ON teh_otverstie.id = d.teh_otverstie
        WHERE d.last_call >= '$from_sql' AND d.last_call <= '$to_sql' AND d.finish_order = 1
    ";
    $sql_report = $db->query($sql_report_string);

    $out_report_1 = '';
    foreach ($sql_report->fetchAll() as $row) {
        if (in_array($row['klient_onlime'], array(0, 1))) {
            $has_foreign_string = ($row['klient_onlime'] == 0) ?
                $L['No'] :
                $L['Yes'];
        }
        $klient_onlime_string = ($row['klient_onlime'] > 0) ?
            $L['Yes'] :
            $L['No'];
            
        $date_podkl = explode(' ', $row['data_podkluch']);
            
        $out_report_1 .= <<<HTML
          <tr>
            <td>{$row['raion']}</td>
            <td>{$row['id']}</td>
            <td>{$row['last_call']}</td>
            <td>&nbsp;</td>
            <td>{$row['lastname']} {$row['firstname']} {$row['patronymic']}</td>
            <td>{$row['data_rozhd']}</td>
            <td>{$row['gorod']}</td>
            <td>{$row['ulitsa']}</td>
            <td>{$row['dom']}</td>
            <td>{$row['korpus']}</td>
            <td>{$row['podezd']}</td>
            <td>{$row['etazh']}</td>
            <td>{$row['kvartira']}</td>
            <td>{$row['domofon_kod']}</td>
            <td>{$row['phone_dom']}</td>
            <td>{$row['phone_mob']}</td>
            <td>{$row['phone_rab']}</td>
            <td>{$row['internet_tar']}</td>
            <td>{$row['ctv_tar']}</td>
            <td>{$row['ab_oborud']}</td>
            <td>{$row['sposob_podkl']}</td>
            <td>{$row['teh_otverst']}</td>
            <td>{$date_podkl[0]}</td>
            <td>{$klient_onlime_string}</td>
            <td>{$row['comment']}</td>
          </tr>\n
HTML;
    }
    $out_report_footer = <<<HTML
    </table>
    </body>
    </html>\n
HTML;

    $out_report_1 = <<<HTML
    {$out_report_header}
    {$out_report_1}
    {$out_report_footer}\n
HTML;
  
    $xls1 = fopen('reports/report1.xls', 'w');
    fwrite($xls1, $out_report_1);
    fclose($xls1);
    
    $tuman->parse('MAIN.OUT');

    break;
    
default:
    $tuman->assign(array(
        'KOMUS_ADMIN_CREATE_ACTION'     => cot_url('admin', 'm=other&p=komus_reports&mode=create'),
        'KOMUS_ADMIN_CREATE_FROM_DATE'  => cot_selectbox_date($sys['now_offset'], 'short', 'from'),
        'KOMUS_ADMIN_CREATE_TO_DATE'    => cot_selectbox_date($sys['now_offset'], 'short', 'to')
    ));
    $tuman->parse('MAIN.HOME');
}

$title = $L['komus_title'];

$tuman->assign(array(
    'KOMUS_ADMIN_TITLE' => $title,
));

$tuman->parse('MAIN');
$plugin_body .= $tuman->text('MAIN');


?>