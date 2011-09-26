/**
 * Extends prototype version 1.6.0
 */

Element.addMethods({

  // redefine toggle() to accept defined visibility state
  toggle: function(element, visible) {
    element = $(element);
    if (visible === undefined) {
      visible = !element.visible();
    } else if (typeof visible === 'string') {
      // can be a string (e.g. from cookie)!
      visible = (visible.toLowerCase() !== 'false');
    }
    if (visible) element.show(); else element.hide();
    return element;
  },

  // redefine toggleClassName() to accept defined class assignment state
  toggleClassName: function(element, className, add) {
    element = $(element);
    if (add === undefined) {
      add = !element.hasClassName(className);
    }
    if (add)
      element.addClassName(className);
    else
      element.removeClassName(className);
    return element;
  },

  // move element as new child to dest
  move: function(element, dest) {
    El = $(dest);
    if (El) El.appendChild(element.remove());
    return element;
  }

});
