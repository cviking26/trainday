'use strict';

/* Directives */

angular.module('trainDay.directives', [])
	.directive('appVersion', ['version', function(version) {
		return function(scope, elm, attrs) {
			elm.text(version);
		};
	}])

	.directive('keyFocus', function() {
		return {
			restrict: 'A',
			// erstellt den event listener für das input field
			link: function(scope, elem, attrs) {
				elem.bind('keyup', function (e) {
					if (e.keyCode == 13) {
						scope.savePlan(elem.val());
					}
				});
			}
		};
	})


;
