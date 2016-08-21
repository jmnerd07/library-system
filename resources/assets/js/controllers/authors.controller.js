/**
 * Created by JM on 1/24/2016.
 */
/** @jsx React.DOM */
(function(){
	'use strict';
	angular
		.module('BookManagementApp')
		.controller('AuthorsController', function($scope, $http){
			$scope.authors = [];
			$scope.reloadAuthors = function() {
				$http.get('/management/author/async/all').success(function (data) {
					$scope.authors = data;
				})
			};
			$scope.reloadAuthors();
			$scope.$on('reloadAuthor', function() {
				$scope.reloadAuthors();
			})
		});
})();