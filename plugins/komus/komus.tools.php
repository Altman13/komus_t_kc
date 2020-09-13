<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * Admin interface for Komus plugin
 *
 * @package komus
 * @version 1.0.0
 * @author Larion Lushnikov
 * @copyright (c) Komus
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('komus', 'plug');
require_once cot_langfile('komus', 'plug');

$mode = cot_import('mode', 'G', 'ALP');

$t = new XTemplate(cot_tplfile('komus.tools', 'plug'));

switch ($mode) {
case 'load_report':
    $title = $L['komus_load_report'];

    if (empty($_FILES['file'])) {
        $t->assign(array(
           'KOMUS_ADMIN_LOAD_REPORT_ACTION' => cot_url('admin', 'm=other&p=komus&mode=load_report')
        ));
        $t->parse('MAIN.LOAD_REPORT');
    } else {
        $data = file($_FILES['file']['tmp_name']);
        $quantity = count($data);
        $filename = 'reports/converted_report.xls';

        require_once 'Spreadsheet/Excel/Writer.php';

        $xls = new Spreadsheet_Excel_Writer($filename);

        $xls->setCustomColor(10, 204, 255, 204);

        $formatHeader =& $xls->addFormat();
        $formatHeader->setBorder(1);
        $formatHeader->setHAlign('center');
        $formatHeader->setVAlign('vcenter');
        $formatHeader->setTextWrap();
        $formatHeader->setFgColor(10);

        $sheet =& $xls->addWorksheet(iconv('utf-8', 'windows-1251', 'База'));

        $sheet->setColumn(0, 4, 13);

        $sheet->write(0, 0, iconv('utf-8', 'windows-1251', 'Номер агента'), $formatHeader);
        $sheet->write(0, 1, iconv('utf-8', 'windows-1251', 'Дата'), $formatHeader);
        $sheet->write(0, 2, iconv('utf-8', 'windows-1251', 'Время звонка'), $formatHeader);
        $sheet->write(0, 3, iconv('utf-8', 'windows-1251', 'Время разговора, сек.'), $formatHeader);
        $sheet->write(0, 4, iconv('utf-8', 'windows-1251', 'Вызывающая сторона'), $formatHeader);

        $i = 0;
        $keys = array();
        $xls_count = 0;
        while ($i < $quantity) {
            $row1 = explode(';', $data[$i]);
            if (empty($row1[0])) {
                $i++;
                continue;
            }
            $date1 = explode('.', $row1[3]);
            $time1 = explode(':', $row1[4]);

            $j = $i + 1;
            $change = false;
            $duration = $row1[7];

            while ($j < $quantity) {
                $row2 = explode(';', $data[$j]);
                $date2 = explode('.', $row2[3]);
                $time2 = explode(':', $row2[4]);

                if ($row1[0] == $row2[0]) {
                    $change = true;
                    $keys[] = $j;
                    $duration += $row2[7];
                }

                $j++;
            }

            if (!in_array($i, $keys)) {
                $xls_count++;

                $sheet->write($xls_count, 0, $row1[2]);
                $sheet->write($xls_count, 1, $row1[3]);
                $sheet->write($xls_count, 2, $row1[4]);
                $sheet->write($xls_count, 3, date('H:i:s', $duration));
                $sheet->write($xls_count, 4, $row1[8]);
            }

            $i++;
        }

        $xls->close(); 

        $t->assign(array(
            'KOMUS_GET_REPORT_URL' => 'reports/converted_report.xls'
        ));

        $t->parse('MAIN.GET_REPORT');

    }
    break;

default:
    $title = $L['komus_title'];
    $t->assign(array(
        'KOMUS_ADMIN_LOAD_REPORT_URL' => cot_url('admin', 'm=other&p=komus&mode=load_report')
    ));
    $t->parse('MAIN.HOME');
}

$t->assign(array(
    'KOMUS_ADMIN_TITLE' => $title,
));

$t->parse('MAIN');
$plugin_body .= $t->text('MAIN');


?>
