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
Array.prototype.contains = function(needle){
	return this.indexOf(needle) != -1; };
Object.prototype.contains = function(property) {
	return typeof this[property] !== 'undefined'; };

// document ready
$(function(){

});


var trainDay = angular.module('trainDay', []);
//<editor-fold desc="Controlllers">

trainDay.controller('Navi', function($scope, planFactory, pageService, $rootScope){
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
		console.log();
		if(pageService.getCurrentPage() == 'PlansOverview')
			planFactory.addNewPlan();
		else if(pageService.getCurrentPage() == 'PlanDetail')
			console.log('add new übung');
	};
});

trainDay.controller('PlansOverviewCtrl', function($scope, $http, planFactory, requestService, pageService, $rootScope){
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
});

trainDay.controller('PlanDetailCtrl', function($scope, pageService, $rootScope, $sce) {
	var pageName    = 'PlanDetail';
	$scope.active   = false;
//	$sce.trustAsHtml($scope.desc);
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
	});





});
//</editor-fold>




//<editor-fold desc="Factory & Services">

/**
 * Factory für Pläne, d.h. einen Plan speichern, aktualisieren, löschen
 */
trainDay.factory('planFactory', function($rootScope, $http, requestService){
	return {
		// private Attributes
		newPlan    : false,
		_updatePlanStatus : function() {
			// add Event, auf den controller hören sollen
			$rootScope.$broadcast('handleNewPlan');
		},
		_newPlanIsSaved : function() {
			this.newPlan = false;
			this._updatePlanStatus();
		},

		// public Attributes
		addNewPlan : function() {
			this.newPlan = true;
			this._updatePlanStatus();
		},
		addNewPlanToDb : function($thatScope, value) {
			var planFactory = this;
			requestService.doAngularAjax('php/data.php', {'param': 'Plan', 'value': value}, function(data) {
				$thatScope.plans.push({
					id  : data.id,
					name: data.value
				});
				planFactory._newPlanIsSaved();
			});
		}
	};
});


/**
 * Service für die Seiten-Verwaltung, Seiten-Aufrufe der App
 */
trainDay.service('pageService', function($rootScope)
{
	// available pages
	this._pages = {
		'PlansOverview' : {
			title : 'Meine Pläne'
		},
		'PlanDetail' : {
			title : 'Default Plan'
		}
	};
	// History array
	this._pageHistory = [];
	// Start page
	this._currentPage = 'PlansOverview';


	/**
	 * Gibt den Title der momentanen Seite wieder
	 */
	this.getCurrentPageTitle = function() {
		return this._pages[this._currentPage].title;
	}
	/**
	 * Gibt den Title der momentanen Seite wieder
	 */
	this.historyIsAvailable = function() {
		return this._pageHistory.length > 0;
	}
	/**
	 *
	 */
	this.setFocusedPlan = function(newTitle) {
		this._pages.PlanDetail.title = newTitle;
	}
	/**
	 * Getter der currentPage
	 */
	this.getCurrentPage = function() {
		return this._currentPage;
	};
	/**
	 * @param to   : beschreibt welche Seite geladen werden soll
	 * @param back : bestimmt ob der die momentane Seite in die History gespeichert werden soll
	 */
	this.changePage  = function(to, back) {
		if(typeof(back)==='undefined') back = false;
		// schauen ob die gewollte seite überhaupt existiert (_pages - Array)
		if(this._pages.contains(to)){
			if(!back)
				this._pageHistory.push(this._currentPage);
			else
				this._pageHistory.pop();
			this._currentPage = to;
			$rootScope.$broadcast('pageChange');
		} else
			console.error('pageService._page ['+ this._pages +'] does not contains \''+ to +'\'');
	};
	/**
	 * Funktion um die vorherige Seite anzuzeigen
	 */
	this.backInHistory = function() {
		this.changePage(this._pageHistory[this._pageHistory.length-1], true);
	};
});

/**
 * Service für Aufrufe, die an den Server oder an dem LocalStorage gehen
 */
trainDay.service('requestService', function($http, $rootScope)
{
	this.localStorageName = 'TrainDayStorage';

	/* LocalStorage Aufrufe */
		// TODO: localstorage, +


	/* Ajax Aufrufe */
	this.doAngularAjax = function(uri, params, callback) {
		$rootScope.loading = true;
		$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
		$http.post     (uri, $.param(params))
			 .success  (function(data) {
				    callback(data);
					$rootScope.loading = false;
			 });
	};
	this.dojQueryAjax  = function(uri, params, callback) {
		$rootScope.loading = true;
		$.ajax({
			type    : 'POST',
			url     : uri,
			data    : params,
			dataType: 'json',
			success : function(data) {
				callback(data);
				$rootScope.loading = false;
			}
		});
	};

});
//</editor-fold>



/**
 * Fügt dem Element, das das Attribut 'keyFocus' hat, ein event listener hinzu
 */
trainDay.directive('keyFocus', function() {
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
});




/**
 * Um diesen Aufruf machen zu können:
 * <div ng-bind-html="plan.desc | unsafe">
 */
trainDay.filter('allowHTML', function($sce) {
	return function(val) {
		return $sce.trustAsHtml(val);
	};
});




//<editor-fold desc="$http Info">
/*
 $http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

 $http.post('php/data.php', $.param({'param': 'plan'}))
 .success(function(data) {
 $scope.plans = data;
 });
    UND
 $_POST

  ODER

 $http.post('php/data.php', {'param': 'plan'})
 .success(function(data) {
 $scope.plans = data;
 });
    UND
 var_dump(json_decode(file_get_contents('php://input')));

 */
//</editor-fold>