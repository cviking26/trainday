'use strict';

/* Controllers */

angular.module('trainDay.controllers', [])

	.controller('Navi', function($scope, planFactory, pageService){
		/* Attributes */
		$scope.headerTitle          = pageService.getCurrentPageTitle();
		$scope.historyIsAvailable   = false;

		/* Event Listener */
		$scope.$on('pageChange', function() {
			$scope.headerTitle = pageService.getCurrentPageTitle();
			$scope.historyIsAvailable = pageService.historyIsAvailable();
		});


		/* Methods */
		$scope.back = function() {
			if($scope.historyIsAvailable)
				pageService.backInHistory();
		};
		$scope.add  = function() {
			if(pageService.getCurrentPage() == 'PlansOverview')
				planFactory.addNewPlan();
			else if(pageService.getCurrentPage() == 'PlanDetail')
				console.log('add new übung');
		};
	})


/* Page controllers */

	.controller('PlansOverviewCtrl', function($scope, $http, planFactory, requestService, pageService, $rootScope){
		/* Attributes */
		var pageName    = 'PlansOverview';
		$scope.active   = true;
		$scope.plans    = [];
		$scope.newPlan  = planFactory.newPlan;


		/* Event listener */
		$scope.$on('handleNewPlan', function() {
			$scope.newPlan = planFactory.newPlan;
		});
		$scope.$on('pageChange', function() {
			var curPage = pageService.getCurrentPage();
			if(curPage == pageName)
				$scope.active = true;
			else
				$scope.active = false;
		});


		/* Methods */
		$scope.seeDetail = function(g){
			// g = angular-DOM object
			$rootScope.focusedPlan = g.plan;
			pageService.setFocusedPlan(g.plan.name);
			pageService.changePage('PlanDetail', false);
		}
		$scope.savePlan = function(value){
			planFactory.addNewPlanToDb($scope, value);
		}


		requestService.doAngularAjax('php/data.php', {'param': 'plan'}, function(data) {
			$scope.plans = data;
		});
	})

	.controller('PlanDetailCtrl', function($scope, pageService, $rootScope) {
		var pageName    = 'PlanDetail';
		$scope.active   = false;
		$scope.$on('pageChange', function() {
			var curPage = pageService.getCurrentPage();
			if(curPage == pageName){
				$scope.active = true;
				$scope.plan   = $rootScope.focusedPlan;
				$scope.plan.desc = 'Das ist eine Beschreibung zu einem ' +
					'Plan mit der id: "<b>'+ $scope.plan.id +'</b>" !!<br />'+
					'Lorem Ipsume dolor<br />';
				console.log($scope.plan);
			} else
				$scope.active = false;
		})
	})

/* Page controllers End */
;