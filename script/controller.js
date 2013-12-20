/**
 * Created with JetBrains PhpStorm.
 * User: dbartuschat
 * Date: 20.12.13
 * Time: 09:46
 * To change this template use File | Settings | File Templates.
 */
function controller($scope, $http) {
	$scope.users = [
		{"name": "Max", alter: 20},
		{"name": "Anna", alter: 21},
		{"name": "Lisa", alter: 25}
	];
}