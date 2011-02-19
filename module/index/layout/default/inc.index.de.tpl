<h2>Konfiguration</h2>

<p>
  Um die <tt>|es|f|</tt> Konfiguration zu ändern,
  führe einfach nochmals das <a href="setup/">Setup</a> aus.
</p>

<p>
  Um eine Modul- oder Plugin-Konfiguration zu ändern, installiere und aktiviere im
  <a href="index.php?module=backend&action=info&ext=module-configuration">Backend</a>
  das Modul <em>Configuration</em>.
</p>

<h2>Auktionen hinzufügen</h2>

<p>Es gibt 3 Möglichkeiten, Auktionen zu <tt>|es|f|</tt> hinzuzufügen:</p>

<ul>
  <li>Manuell auf der Seite <a href="?module=auction">Auktionen</a>.<br />
      Gib dafür eine oder mehrere Auktionsnummern (durch Leerzeichen oder
      Komma getrennt) ein.</li>
  <li>Per Bookmarklets, siehe unten.</li>
</ul>

<h2>Bookmarklet</h2>

<p>
  Für diese Funktionalität muß <tt>Javascript</tt> im Browser aktiviert sein!
</p>

<p>
  Um Auktionen direkt aus eBay zu <tt>|es|f|</tt> hinzuzufügen,
  gehe folgendermaßen vor:
<p>

<ul>
  <li>Füge das Bookmarklet zu Deinen Lesezeichen hinzu:
      <tt><!-- INCLUDE inc.snipe --></tt></li>
  <li>Oder dieses Bookmarklet für direktes Hinzufügen:
      <tt><!-- INCLUDE inc.add2es-f --></tt><br>
      <strong>Achtung: Das Plugin "AddFromEbayURL" muß aktiviert sein!</strong></li>
  <li>Wenn Du auf einer Auktionsseite bist, die Dich interessiert,
      klicke auf eins der Lesezeichen.</li>
</ul>

<h2>Fehlersuche</h2>

<p>
  <tt>|es|f|</tt> verfügt über ein Debugger-Plugin, das alle System-Kommandos
  und deren Ausgaben protokollieren kann.
</p>

<p>
  Gestartet wird der Debugger durch den URL-Parameter DEBUG, also <a href="?DEBUG">so</a>.
</p>

<p>
  Das Erstellen einer Trace-Datei wird durch den URL-Parameter TRACE gestartet, also <a href="?TRACE">so</a>.
</p>

<p>
  Gestoppt werden kann der Debugger durch den URL-Parameter STOP, also <a href="?STOP">so</a>.
</p>

<p>
  Wenn Du einen Fehler gefunden hast, befolge bitte die Schritte auf der
  <a href="?module=support">Support-Seite</a>.
</p>
