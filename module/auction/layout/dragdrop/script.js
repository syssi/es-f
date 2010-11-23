/*
 * Copyright (c) 2006-2008 Knut Kohl <knutkohl@users.sourceforge.net>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * GPL: http://www.gnu.org/licenses/gpl.txt
 *
 */

var HoverTarget = false;

// ---------------------------------------------------------------------------
function ItemHover( item, target ) {
  if (!target.src.match(/-hover/)) {
    target.src = target.src.replace(/\.gif$/,'-hover.gif');
    window.status = 'Item: '+item.alt;
    HoverTarget = target;
  }
}

// ---------------------------------------------------------------------------
function ItemHoverReset() {
  if (HoverTarget) {
    HoverTarget.src = HoverTarget.src.replace(/-hover/,'');
    window.status = '';
    HoverTarget = false;
  }
}

// ---------------------------------------------------------------------------
function ItemDropCategory( source, target ) {
  $(source).hide();
  ItemDrop(source.alt, 'category', target.alt);
}

// ---------------------------------------------------------------------------
function ItemDropGroup( source, target ) {
  $(source).hide();
  ItemDrop(source.alt, 'group', target.alt);
}

// ---------------------------------------------------------------------------
function ItemDropNoGroup( source ) {
  $(source).hide();
  ItemDrop(source.alt, 'group', '');
}

// ---------------------------------------------------------------------------
function ItemDrop( item, mode, value ) {
  var form = new Element('form', { method:'post', action:'index.php' }).hide();
  form.appendChild(new Element('input', {name:'module', value:'auction' }));
  form.appendChild(new Element('input', {name:'action', value:'m'+mode }));
  form.appendChild(new Element('input', {name:'auctions[]', value:item }));
  form.appendChild(new Element('input', {name:mode, value:value }));
  document.body.appendChild(form);
  CreateStandByWindow();
  form.submit();
}
