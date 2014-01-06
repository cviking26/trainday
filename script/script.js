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
trainDay.controller('PlanDetailCtrl', function($scope, $http){
	$scope.plans = [{
		name:'marlon',
		desc:'agslkjahsglsh'}];
	var y = [];
//	$scope.$apply(function() {
		$http.post('php/data.php', {param : 'plan'})
		.success(function(data) {
			console.log('-----');
			console.log(data);
			console.log('-----');
			for(var i=0; i < data.length; i++){
				y.push(data[i]);
			}
		})
		.error(function(data, status, headers, config) {
			console.log(data);
			console.log(status);
		});
//
//	});

	console.log(y);
});
