$(document).ready(function(){
	$(document).on('click', '.short-tags > span', function(){
  	insertTextAtCursor($(this).data('tag'));
  });
  
  var html = '<div class="form-group"><label>Short tags</label><div style="width: 70%;align:center;" class="form-control short-tags"><span data-tag="Company name : ">[Company name]</span><span data-tag="Phone : ">[Phone]</span><span data-tag="Email Address : ">[Email Address]</span><span data-tag="Address : ">[Address]</span></div></div>';
      
	$("#description").parent("div").parent("div").before(html);
  
});

function insertTextAtCursor(text) {
    var el = document.getElementById('description'), val = el.value, endIndex, range, doc = el.ownerDocument;
    
    if (typeof el.selectionStart == "number"
            && typeof el.selectionEnd == "number") {
        endIndex = el.selectionEnd;
        el.value = val.slice(0, endIndex) + text + val.slice(endIndex);
        el.selectionStart = el.selectionEnd = endIndex + text.length;
    } else if (doc.selection != "undefined" && doc.selection.createRange) {
        el.focus();
        range = doc.selection.createRange();
        range.collapse(false);
        range.text = text;
        range.select();
    }
}