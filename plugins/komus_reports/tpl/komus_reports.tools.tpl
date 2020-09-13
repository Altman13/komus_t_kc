<!-- BEGIN: MAIN -->

<h2 class="message">{KOMUS_ADMIN_TITLE}</h2>
{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}

  <!-- BEGIN: HOME -->
  <form action="{KOMUS_ADMIN_CREATE_ACTION}" method="post">
    С: {KOMUS_ADMIN_CREATE_FROM_DATE}<br />
    По: {KOMUS_ADMIN_CREATE_TO_DATE}<br />
    <button type="submit">Выгрузить отчет</button>
  </form>
  <!-- END: HOME -->
  
  <!-- BEGIN: OUT -->
  <p><a href="/reports/report1.xls">Отчет по заявкам</a></p>
  <!-- END: OUT -->
  
<!-- END: MAIN -->