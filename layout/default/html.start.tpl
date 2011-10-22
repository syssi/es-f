<!--
/**
 *
 * @version   $Id$
 * @revision  $Rev$
 */
-->

<body id="{MODULE}">

<script type="text/javascript">
  // <![CDATA[
  efa_bigger[1]  = '<img src="layout/default/images/text+1.gif">';
  efa_bigger[2]  = '[[Core.Efabigger]]';
  efa_bigger[7]  = 'Tip(\'[[Core.EfaBigger]]\')';
  efa_reset[1]   = '<img src="layout/default/images/text0.gif">';
  efa_reset[2]   = '[[Core.Efareset]]';
  efa_reset[7]   = 'Tip(\'[[Core.EfaReset]]\')';
  efa_smaller[1] = '<img src="layout/default/images/text-1.gif">';
  efa_smaller[2] = '[[Core.Efasmaller]]';
  efa_smaller[7] = 'Tip(\'[[Core.EfaSmaller]]\')';
  var efa_fontSize =
    new Efa_Fontsize(efa_increment,
                     efa_bigger, efa_reset, efa_smaller,
                     efa_default);
  if (efa_fontSize) efa_fontSize.efaInit();
  // ]]>
</script>

<!-- INCLUDE tooltip -->
