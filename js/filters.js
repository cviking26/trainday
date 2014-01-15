'use strict';

/* Filters */

angular.module('trainDay.filters', [])
	.filter('interpolate', ['version', function(version) {
		return function(text) {
			return String(text).replace(/\%VERSION\%/mg, version);
		}
	}])

	.filter('allowHTML', function($sce) {
		return function(val) {
			return $sce.trustAsHtml(val);
		};
	})



;
