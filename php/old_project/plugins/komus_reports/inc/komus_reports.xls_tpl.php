<?php

defined('COT_CODE') or die('Wrong URL');

$xls_report_header = <<<HTML
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <style>
        .xls_table, .xls_table th, .xls_table td { border-collapse: collapse; border: 1px solid #000000; }
        .xls_table th { background-color: #cccccc; vertical-align: middle; }
        .xls_table td { vertical-align: top; font-family: Calibri; }
        .cell_title   { font-size: 16pt; }
        .cell_value   { font-size: 11pt; }
        .cell_list_value { font-size: 12pt; font-weight: bold }
        .textleft     { text-align:left; }
        .textcenter   { text-align:center; }
        .textright    { text-align:right; }
        .section      { color: #ff0000; font-size: 18pt; font-weight: bold; text-align: center; }
      </style>
    </head>
    <body>\n
HTML;

$xls_main_report_th = <<<HTML
      <tr>
        <th>Время вызова</th>
        <th>Ф.И.О.</th>
        <th>Город</th>
        <th>Телефон</th>
        <th>Язык</th>
        <th>Источник</th>
        <th>Комментарий</th>
        <th>Результат</th>
        <th>Телемаркетолог</th>
      </tr>\n
HTML;

$xls_report_footer = <<<HTML

        </table>
        </body>
        </html>\n
HTML;

?>
