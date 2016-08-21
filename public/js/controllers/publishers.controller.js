(function(){
	'use strict';
	angular
	.module('BookManagementApp')
	.controller('PublishersController', function($scope, $http){
		$scope.publishers = [];
		$scope.reloadPublishers = function() {
			$http.get('/management/publisher/async/all').success(function (data) {
				$scope.publishers = data;
			})
		}
		$scope.reloadPublishers();
		$scope.$on('reloadPublisher', function() {
			$scope.reloadPublishers();
		})
	});
})();