// ____app_js_file_executor_header
function ____app_js_file_executor_header() {
for(i = 0; i < js_array_head.length; i++) {

var js = document.createElement("script");
js.src = js_array_head[i];

var app_head = document.getElementsByTagName("head")[0];
app_head.appendChild(js);

}
}
// end ____app_js_file_executor_header


// ____app_js_file_executor_footer
function ____app_js_file_executor_footer() {
for(j = 0; j < js_array_footer.length; j++) {

var js = document.createElement("script");
js.src = js_array_footer[j];

var app_body = document.getElementsByTagName("body")[0];
app_body.appendChild(js);

}
}
// end ____app_js_file_executor_footer

 ____app_js_file_executor_header();
 ____app_js_file_executor_footer();