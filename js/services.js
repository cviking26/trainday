'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('trainDay.services', [])
	.value('version', '0.1')

	.factory('planFactory', function($rootScope, $http, requestService){
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
	})


	.service('pageService', function($rootScope)
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
	})


	.service('requestService', function($http, $rootScope)
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

	})


;