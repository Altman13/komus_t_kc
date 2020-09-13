<!-- BEGIN: MAIN -->

  <!-- BEGIN: HOME -->
  <div class="body">
    <!-- BEGIN: GRAND_OPERATOR -->     
     <div><a href="{KOMUS_REPORTS_BASE_URL}">Отчет</a> (Excel)</div>       	    
    <!-- END: GRAND_OPERATOR -->
  
    <!-- BEGIN: OPERATOR -->
    <!-- END: OPERATOR -->
  </div>
  <!-- END: HOME -->

  <!-- BEGIN: REPORT -->
  <div class="body">
    <form action="{KOMUS_CREATE_ACTION}" method="post">
      С: {KOMUS_CREATE_FROM_DATE}<br />
      По: {KOMUS_CREATE_TO_DATE}<br />
      <button type="submit">Выгрузить отчет</button>
    </form>
  </div>  
  <!-- END: REPORT -->

  <!-- BEGIN: HTML_OUT -->
  <div class="body">
    {KOMUS_REPORTS_HTML_OUT}
  </div>
  <!-- END: HTML_OUT -->

  <!-- BEGIN: XLS_OUT -->
  <div class="body"><a href="/reports/{KOMUS_REPORTS_XLS_FILENAME}">Получить отчет</a></div>
  <!-- END: XLS_OUT -->

<!-- END: MAIN -->
