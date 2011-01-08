<!--
/*
 *
 */
-->

<div id="messages" class="messages">
  <!-- IF ESF_MESSAGES -->
  <div id="msginner" class="msginner">
    <img id="msginnerx" style="float:right;cursor:pointer;display:none"
         alt="[x]" title="Hide messages" src="layout/default/images/close.jpg"
         onclick="$('msginner').remove()" onmouseover="Tip('Hide messages')">
    {ESF_MESSAGES}
    <script type="text/javascript">
      // <![CDATA[
      addLoadEvent(function(){
        $('msginnerx').show();
        if (!'{CONST.DEVELOP}' && {ESF_MESSAGESERRORS} == 0)
          setTimeout('Effect.BlindUp("msginner")', 5000);
      });
      // ]]>
    </script>
  </div>
  <!-- ENDIF -->
</div>
