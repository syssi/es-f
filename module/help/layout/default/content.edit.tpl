<!--
/**
 *
 */
-->

<div id="content">

<a name="editor"></a>

{form:}
{fh:"scope",SCOPE}
{fh:"name",NAME}
{fh:"helpfile",HELPFILE}

<textarea style="width:100%;height:500px" id="helptext" name="helptext">
{html:HELPTEXT}
</textarea>

<script type="text/javascript">
  var oFCKeditor = new FCKeditor('helptext') ;
  oFCKeditor.BasePath = 'DEVELOP/fckeditor/';
  oFCKeditor.Height = 500;
  oFCKeditor.ReplaceTextarea();
</script>

<p>
  <input class="button" type="submit" value="[[Help.Save]]">
</p>

</form>

</div>