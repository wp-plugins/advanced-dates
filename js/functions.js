/*
	JavaScript Functions - Studio Hyperset Advanced Dates WordPress Plugin
    Plugin URI: http://studiohyperset.com/wordpress-advanced-dates-plugin/4016
    Description: Extending the literary, documentary, and archival potential of WordPress, this plugin allows publishers to easily customize the publication year of posts and pages. <em>w/ special thanks to <a href="http://www.ryanajarrett.com">Ryan Jarrett</a></em>
	Version: 1.0 
    Author: Studio Hyperset, Inc. 
    Author URI: http://studiohyperset.com/posts
	License: GPL3
*/
//
// admin functions
function uncheck(){
var a = document.getElementById('advanceddates_global');
var b = document.getElementById('advanceddates_freeze_global');
if (a.checked == false)
{
b.checked = false;
}
}
//
// meta box functions
function uncheck2(){
var c = document.getElementById('advanceddates_meta_post_enable');
var d = document.getElementById('advanceddates_meta_post_freeze');
if (c.checked == false)
{
	d.checked = false;
}
}
function uncheck3() {
	var enable = document.getElementById("advanceddates_meta_post_enable");
	var freeze = document.getElementById("advanceddates_meta_post_freeze");
	var label = document.getElementById('label-advanceddates_meta_post_freeze');
	var spacer = document.getElementById('spacer');
	//
	if(enable.checked == false) {
    		freeze.style.display = "none";
			label.style.display = "none";
			spacer.style.display = "none";

	  	}
	else if(enable.checked == true) {
			freeze.style.display = "block";
			label.style.display = "block";
			spacer.style.display = "block";
	}
} 
//
window.onload=function(){
	var enable = document.getElementById("advanceddates_meta_post_enable");
	var freeze = document.getElementById("advanceddates_meta_post_freeze");
	var label = document.getElementById('label-advanceddates_meta_post_freeze');
	var spacer = document.getElementById('spacer');
	if (freeze.checked == true) {
			freeze.style.display = "block";
			label.style.display = "block";
			spacer.style.display = "block";
	}
}
window.onload=function(){
	var enable = document.getElementById("advanceddates_meta_post_enable");
	var freeze = document.getElementById("advanceddates_meta_post_freeze");
	var label = document.getElementById('label-advanceddates_meta_post_freeze');
	var spacer = document.getElementById('spacer');
	if (enable.checked == true) {
			freeze.style.display = "block";
			label.style.display = "block";
			spacer.style.display = "block";
	}
}
//
function toggle() {
	var ele = document.getElementById("meta-instructions");
	var text = document.getElementById("instructions");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "Instructions &amp; Links";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "Hide Instructions &amp; Links";
	}
} 