<!-- BEGIN: MAIN -->

<h2 class="message">{KOMUS_ADMIN_TITLE}</h2>
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

<!-- BEGIN: HOME -->
<p><a href="{KOMUS_ADMIN_LOAD_REPORT_URL}">Загрузка отчета</a></p>
<!-- END: HOME -->
 
<!-- BEGIN: CONTACTS_LIST -->  
<table class="cells">
  <tr>
    <th></th>
    <th>ID</th>
    <th>Оператор</th>
    <th>Время создания</th>
    <th>Отправлено на FTP</th>
  </tr>
  <!-- BEGIN: ROW_CONTACT -->
  <tr>
    <td><a href="{KOMUS_EDIT_CONTACT_URL}">ред.</a></td>
    <td>{KOMUS_CONTACT_ID}</td>
    <td>{KOMUS_OPERATOR_NAME}</td>
    <td>{KOMUS_CREATION_TIME}</td>
    <td class="textcenter">{KOMUS_SEND_TO_FTP}</td>
  </tr>
  <!-- END: ROW_CONTACT -->
</table>
<!-- END: CONTACTS_LIST -->  

<!-- BEGIN: EDIT_CONTACT -->
<table class="cells">
  <!-- BEGIN: ROW_FIELD -->
  <tr>
    <td class="label"></td>
    <td></td>
  </tr>
  <!-- END: ROW_FIELD -->
</table>
<!-- END: EDIT_CONTACT -->

<!-- BEGIN: LOAD_REPORT -->
  <p><strong>ВНИМАНИЕ!</strong></p>
  <p>Перед загрузкой отчета для оптимизации необходимо выполнить следующие действия:</p>
  <ol>
    <li>Убедиться, что исходный отчет содержит 9 столбцов. В противном случае оптимизация будет невозможна.</li>
    <li>Убедиться, что длительность разговоров указана в секундах (одним числом), а не в формате времени (например, "00:45").
    <li>Если в таблице имеется "шапка", удалить ее.</li>
    <li>Преобразовать столбец 1 из формата "Экспоненциальный" в формат "Числовой" с количеством знаков после запятой "0".</li>
    <li>Сохранить файл в формате "CSV (разделители - запятые)".</li>
    <li>Для загрузки выбрать созданный CSV-файл.</li>
  </ol>
  <p>&nbsp;</p>
  <form action="{KOMUS_ADMIN_LOAD_REPORT_ACTION}" method="post" enctype="multipart/form-data">
    <div>Файл: <input name="file" type="file" /></div>
    <div><button type="submit">Загрузить</button></div>
  </form>
<!-- END: LOAD_REPORT -->

<!-- BEGIN: GET_REPORT -->
  <div><a href="{KOMUS_GET_REPORT_URL}">Сохранить/открыть отчет</a></div>
<!-- END: GET_REPORT -->

<!-- END: MAIN -->
