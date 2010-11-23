// ---------------------------------------------------------------------------
// if add to group, change category select to "- from group -"
// ---------------------------------------------------------------------------
function SetGroupCategory ( _group, _select ) {
  if (_select === undefined) {
    _select = 'CategorySelect';
  }
  var El = $(_select);
  if (El) {
    El.value = _group ? GetCategoryFromGroup : '';
  }
}
