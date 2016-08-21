(function(){
	'use strict';
	angular
	.module('BookManagementApp')
	.directive('ngPublishersList', function(){
		return {
			restrict: 'A',
			scope: {
				publishers: '=ngPublishersList',
				ngPublisherKeyword: '=',
				selectedPublisherId: '=',
				ngHide: '='
			},
			template:  [
				'<div class="autosuggest-publisher-body" >',
				'<div class="list-group" >',
				'<a class="list-group-item" data-is-hidden="ngHide" data-ng-filter-keyword-publisher="ngPublisherKeyword" data-selected-publisher-id="selectedPublisherId" href="#" data-record-id="{{ publisher.id }}" data-publisher-name="{{ publisher.name }}" data-ng-repeat="publisher in items = ( publishers | filter: ngPublisherKeyword) ">{{ publisher.name }}</a>',
				'<a class="list-group-item text-success save-new-publisher-suggest" data-ng-selected-publisher-id="selectedPublisherId" data-ng-publisher-keyword="ngPublisherKeyword" href="#" data-ng-if="items.length === 0 && ngPublisherKeyword != \'\'" data-publisher-list="publishers" >Click here to save <b>{{ ngPublisherKeyword }}</b></a>',
				'</div>',
				'</div>'
			].join(""),
			link: function(scope, elem, attrs) {
				scope.$on('selectedPublisher', function(evt, data){
					/*scope.$apply('selectedPublisherId = '+ data.id);
					 scope.$apply('ngPublisherKeyword = "' + data.name + '"');
					 scope.$apply('ngHide = true');*/
					scope.selectedPublisherId = data.id;
					scope.ngPublisherKeyword = data.name;
					scope.ngHide = true;
					//scope.$apply();
				});
				scope.$on('hidePublishers', function(evt) {
					scope.$apply('ngHide = true');
				})
			}
		}
	})
	.directive('saveNewPublisherSuggest', function($http, $rootScope) {
		return {
			restrict: 'C',
			scope: {
				publisherName: '=ngPublisherKeyword',
				publishers: '=publisherList',
				selectedPublisherId: '='
			},
			link: function(scope, elem, attrs) {
				elem.on('click', function() {
					elem.html('<small class="text-muted"><i>Please wait while saving new publisher...</i></small>');
					$http({
						url: '/management/publisher/async/new-publisher',
						data: {publisher_name: scope.publisherName, type: 'async'},
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'/* 'application/x-www-form-urlencoded; charset=UTF-8'*/
						}
					}).then(function(e){
						if(Object.keys(e.data.error).length > 0)
						{
							if( ! e.data.error.hasOwnProperty('publisher_name'))
							{
								return ;
							}
							var errorMsg = '';
							angular.forEach(e.data.error.publisher_name, function(v, i){
								errorMsg += '<small class="text-danger">'+ v + '</small>';
							});
							elem.html(errorMsg);
							return;
						}
						elem.html('<small class="text-primary">New publisher created!</small>');
						$rootScope.$broadcast('reloadPublisher');
						//scope.$apply('selectedPublisherId = '+ e.data.model.id + '; publisherName = "' + e.data.model.publisher_name + '";')
						scope.$emit("selectedPublisher", {name: e.data.model.publisher_name, id: e.data.model.id });
					}, function(x) {
						console.log(x);
						elem.html('<small class="text-danger">Publisher not save. Unknown error.</small>');
					})
				})

			}
		}
	})
	.directive('publisherNameSearch', function($rootScope){
		return {
			restrict: 'C',
			scope: {
				hideSuggestions: '=hide_publisher_suggestions'
			},
			link: function(scope, elem, attrs) {
				elem.on('keydown', function(e) {
					if(e.keyCode == 27)
					{
						$rootScope.$broadcast('hidePublishers');
					}
				})
			}
		}
	})
	.directive('ngFilterKeywordPublisher', function() {
		return {
			restrict: 'A',
			scope: {
				publisherName: '@publisherName',
				publisherId: '@recordId',
				ngFilterKeywordPublisher: '=',
				isHidden: '='
			},
			link: function(scope, elem, attrs) {
				elem.on('click', function(e){
					scope.$emit("selectedPublisher", {name: scope.publisherName, id: scope.publisherId });
					scope.$apply();
					//scope.$apply('ngFilterKeywordPublisher = "'+ scope.publisherName +'"; ');
				})
			}
		}
	})
})();