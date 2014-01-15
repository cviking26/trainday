//$.ajax({
//	url: "data.php",
//	context: document.body
//}).done(function() {
//		$( this ).addClass( "done" );
//});

/*Adding an new Content-Element, data-id is increasing by 1*/
/*
function addElement(id, value){
//	$("#content").append("<div class='content-el' data-id='" + ($(".content-el").last().data("id")+1) +"' onclick='swipe(this)'><h1>" + data + "</h1></div>");
	$("#content").append("<div class='content-el' data-id='"+ id +"' onclick='swipe(this)'><h1>" + value + "</h1></div>");
}

function addNewElement(data){
	$("#content").append("<div class='content-el' data-id='" + ($(".content-el").last().data("id")+1) +"'><input class='content-input' type='text' name='newElement'></div>");
}


function swipe(element){
	$('.content-el').find('h1').addClass('swipeAway');
	getElement(element);
}
*/

/*ready function*/
//$(function(){
//	getPlanOverview();

	/*live*/
//	$("body").on("keypress", ".content-input", function(e) {
//		if(e.keyCode==13){
//			insertValue(this.value, this);
//		}
//	})
//});



String.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };
Array.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };
Object.prototype.contains = function(property) {
	return typeof this[property] !== 'undefined'; };

// document ready
$(function(){

});




// Declare app level module which depends on filters, and services
var trainDay = angular.module('trainDay', [
		'trainDay.filters',
		'trainDay.services',
		'trainDay.directives',
		'trainDay.controllers'
	]);
