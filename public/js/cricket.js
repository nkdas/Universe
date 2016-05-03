function Load_external_content()
{
      $('#liveRefresh').load().hide().fadeIn(3000);
}
setInterval('Load_external_content()', 20000);
