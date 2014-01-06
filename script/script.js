/**
 * Created with JetBrains PhpStorm.
 * User: mruescher
 * Date: 06.01.14
 * Time: 10:33
 * To change this template use File | Settings | File Templates.
 */


// contains function edit
String.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };

// document ready
$(function(){


});


var trainDay = angular.module('trainDay', []);
//trainDay.controller('PlanDetailCtrl', function($scope, $http){
//
//	$scope.plans = [{
//		name:'marlon',
//		desc:'agslkjahsglsh'}];
//
////	var_dump(json_decode(file_get_contents('php://input')));
////	    else
//	$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
//
////	$scope.(function(){
//		$http.post('php/data.php', $.param({'param': 'plan'}))
//			.success(function(data, status, headers, config) {
//				$scope.plans = data;
//			})
//			.error(function(data, status, headers, config) {
//				console.log(data);
//				console.log(status);
//			});
////	});
//});
