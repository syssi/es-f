/*

Javascript Dialog box based on Prototype and Scriptaculous

This makes it super simple to create a modal dialog box.
The page will be dimmed into the background and the dialog box popped up
over top of it. The overlay can be clicked to close out of the box.

Use it thusly:
new Dialog.Box([element ID]);
$([element ID]).show();
$([element ID]).hide();

Simple right?

http://snippets.dzone.com/posts/show/3411

*/

var DialogOverlayId = 'DialogOverlay';

if (typeof DialogJsPath === 'undefined') {
  var DialogJsPath = './';
}

/** **************************************************************************
 *
 */
function CreatePopupWindow( _ID, _y ) {
  if (_y === undefined) _y = 20;
  if (_y < 0) _y = (window.innerHeight/2) + _y;
  new Dialog.Box(_ID, (window.pageYOffset + _y) + 'px');
  $(_ID).show();
  return false;
}

/** **************************************************************************
 *
 */
function RemovePopupWindow( _ID, _removeOverlay ) {
  $(_ID).hide(_removeOverlay);
  return false;
}

/** **************************************************************************
 *
 */
var DialogStandbyId = false;

function CreateStandByWindow() {
  if (!$(DialogStandbyId)) {
    // create dummy "dialog"
    var div = new Element("div").hide();
    // get an id
    DialogStandbyId = div.identify();
    document.body.insertBefore(div, document.body.childNodes[0]);
  }
  CreatePopupWindow(DialogStandbyId);
  RemovePopupWindow(DialogStandbyId, false);
  return DialogStandbyId;
}

var Dialog = {};

Dialog.Box = Class.create();

Object.extend(Dialog.Box.prototype, {
  initialize: function(id, y) {
    this.createOverlay();

    this.dialog_box = $(id);
    this.dialog_box.style.position = 'absolute';
    this.dialog_box.show = this.show.bind(this);
    this.dialog_box.hide = this.hide.bind(this);

    this.parent_element = this.dialog_box.parentNode;

    var e_dims = Element.getDimensions(this.dialog_box);
    var b_dims = Element.getDimensions(this.overlay);
    this.dialog_box.style.left = ((b_dims.width/2) - (e_dims.width/2)) + 'px';
    if (y !== undefined) {
      this.dialog_box.style.top = y;
    }
    this.dialog_box.style.zIndex = this.overlay.style.zIndex + 10;
  },

  createOverlay: function() {
    if (!(this.overlay = $(DialogOverlayId))) {
      this.overlay = document.createElement('div');
      this.overlay.id = DialogOverlayId;
      Object.extend(this.overlay.style, {
        position: 'absolute', top: 0, left: 0, zIndex: 90,
        width: '100%', backgroundColor: '#000', display: 'none'
      });
      this.overlay.hide = this.hideOverlay.bind(this);
      document.body.insertBefore(this.overlay, document.body.childNodes[0]);

      var img = document.createElement('img');
      img.src = DialogJsPath + '/dialog.gif';
      img._size = 54;
      img.style.position = 'absolute';
      img.style.display = 'none';
      this.overlay.appendChild(img);
    }
  },

  moveDialogBox: function(where) {
    Element.remove(this.dialog_box);
    if (where == 'back') {
      this.dialog_box = this.parent_element.appendChild(this.dialog_box);
    } else {
      this.dialog_box = this.overlay.parentNode.insertBefore(this.dialog_box, this.overlay);
    }
  },

  show: function() {
    this.overlay.style.height = Math.max(window.innerHeight, document.body.getHeight()) + 'px';
    new Effect.Appear(this.overlay, {from: 0.0, to: 0.75, duration: 0.1});
    this.moveDialogBox('out');
    this.overlay.onclick = this.hide.bind(this);
    this.selectBoxes('hide');
    this.dialog_box.style.display = '';
  },

  hideOverlay: function() {
    this.overlay.childNodes[0].hide();
    this.overlay.style.display = 'none';
  },

  hide: function(_removeOverlay) {
    this.selectBoxes('show');
    if (_removeOverlay !== false) {
      this.hideOverlay();
    } else {
      with (this.overlay.childNodes[0]) { // the waiting image
        style.left = ((window.innerWidth-_size)/2) + 'px';
        style.top = (window.pageYOffset + (window.innerHeight-_size)/2) + 'px';
        style.display = 'block';
      }
    }
    this.dialog_box.style.display = 'none';
    this.moveDialogBox('back');
    //$A(this.dialog_box.getElementsByTagName('input')).each(function(e){if(e.type!='submit')e.value=''});
  },

  selectBoxes: function(what) {
/*
    $A(document.getElementsByTagName('select')).each(function(select) {
      Element[what](select);
    });
*/
//  if (what == 'hide')
//    $A(this.dialog_box.getElementsByTagName('select')).each(function(select){Element.show(select)})
  }
});