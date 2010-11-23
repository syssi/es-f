/*
 *
 */

// ---------------------------------------------------------------------------
// center popup window
function centerWindow() {
  var x = (screen.width - self.innerWidth) / 2;
  var y = (screen.height - self.innerHeight) / 2;
  self.moveTo(x,y);
}

var StartButton = false;

// ---------------------------------------------------------------------------
// inform about progress
function SnipeAuction() {
  $('form').hide();
  $('SnipeMsg').show();
/*
  $('snipeform').request({
    parameters: { api:'add', start:StartButton },
    onSuccess: function(transport) {
      var json = transport.responseText.evalJSON(true);
      $('SnipeMsg').update(json.msg);
    },
    onComplete: function() {
//      alert('Form data saved!')
    }
  });
  return false;
*/
  return true;
}
