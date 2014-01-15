/**
 * Global function for adding the function "contains"
 * Example:
 *  if("Lorem ipsum dolor sit amet".contains("dolor"))
 *      //true
 */
String.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };
Array.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };
Object.prototype.contains = function(property) {
	return typeof this[property] !== 'undefined'; };

/**
 * Document ready
 */
$(function(){

});


/**
 * Angular root application
 */
var trainDay = angular.module('trainDay', [
		'trainDay.filters',
		'trainDay.services',
		'trainDay.directives',
		'trainDay.controllers'
	]);
