var d = document;
var t = d.selection ? d.selection.createRange().text : d.getSelection();
var n = 'Wp Blockquote Shortcode';
var p = '[bq uri="'+location.href+'"]';
var s = '[/bq]';
window.prompt(n, p+t+s);
void(0);
