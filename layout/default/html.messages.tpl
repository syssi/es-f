<!--
/*
 *
 */
-->

<!-- IF ESF_MESSAGES -->
<div id="messages" class="messages">
  <div id="msginner" class="msginner">
    <img id="msginnerx" style="float:right;cursor:pointer;display:none"
         alt="[X]" title="Hide messages" src="layout/default/images/close.jpg"
         onclick="$('msginner').remove()" onmouseover="Tip('Hide messages')">
    <script type="text/javascript">
      // <![CDATA[
      addLoadEvent(function(){
        $('msginnerx').show();
        setTimeout('Effect.BlindUp(\'messages\')', 5000);
      });
      // ]]>
    </script>
    {ESF_MESSAGES}
  </div>
</div>
<!-- ENDIF -->
