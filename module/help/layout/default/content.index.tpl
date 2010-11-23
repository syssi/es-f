<!--
/**
 *
 */
-->

<div class="tabber">

  <div class="tabbertab">

  <h2>About</h2>

  <div style="width:35em;margin:1em auto 0;text-align:center">

  <h1><tt>{CONST.ESF.LONG_TITLE}</tt></h1>
  <h2><tt>{CONST.ESF.FULL_VERSION}</tt></h2>
  <h3>A web based HTML frontend for esniper, the lightweight console
      application for sniping eBay auctions</h3>

  <p>
    <span style="font-size:110%">&copy;</span> 2006-{CONST.YEAR} by
    <em><a href="http://knutkohl.users.sourceforge.net/">{CONST.ESF.AUTHOR} &lt;{CONST.ESF.EMAIL}&gt;</a></em>
  </p>

  </div>

  <div style="width:60em;margin:0 auto 1em;text-align:center">

  <p>
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
  </p>
  <p>
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
  </p>
  <p>
    You should have received a copy of the GNU General Public License
    along with this program.
    If not, see <a href="http://www.gnu.org/licenses/">http://www.gnu.org/licenses/</a>
  </p>

  </div>

  </div>

<!-- BEGIN SCOPE -->

  <div class="tabbertab">

  <h2>{NAME}</h2>

  {cycle:"CLASS"}

  <table class="list">

  <!-- BEGIN DATA -->
  <tr class="{cycle:"CLASS","tr1","tr2"}">
    <td><a href="{SHOWURL}">{NAME}</a></td>
    <td>{DESC}</td>
  <tr>
  <!-- END DATA -->

  </table>

  </div>

<!-- END SCOPE -->

</div>
