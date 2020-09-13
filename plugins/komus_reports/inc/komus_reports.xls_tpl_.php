<?php

defined('COT_CODE') or die('Wrong URL');

$xls_report_header = <<<HTML
    <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <style>
        .xls_table, .xls_table th, .xls_table td { border-collapse: collapse; border: 1px solid #000000; }
        .xls_table th { background-color: #cccccc; vertical-align: middle; }
        .xls_table td { vertical-align: top; }
        .textleft     { text-align:left; }
        .textcenter   { text-align:center; }
        .textright    { text-align:right; }
      </style>
    </head>
    <body>\n
HTML;

$xls_main_report_th = <<<HTML
        <th>Дата заявки</th>
        <th>№ заявки</th>
        <th>Дата создания</th>
        <th>Дата начала звонка</th>
        <th>Дата окончания звонка</th>
        <th>Имя</th>
        <th>Город</th>
        <th>Улица</th>
        <th>Дом</th>
        <th>Корпус</th>
        <th>Мобильный телефон</th>
        <th>Домашний телефон</th>
        <th>Наличие ТВП</th>
        <th>Статус</th>
        <th>Дата присвоения<br />статуса</th>
        <th>Комментарий</th>
        <th>Время обработки</th>
        <th>Время начала</th>
        <th>Время окончания</th>
        <th>Итого</th>
        <th>Телемаркетолог</th>
      </tr>\n
HTML;

$xls_report_footer = <<<HTML
        </table>
        </body>
        </html>\n
HTML;

?>
