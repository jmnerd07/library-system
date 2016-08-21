(function(){
	'use strict';
	angular
		.module('BookManagementApp')
		.directive('ngAuthorsList', function(){
			return {
				restrict: 'A',
				scope: {
					authors: '=ngAuthorsList',
					ngAuthorKeyword: '=',
					selectedAuthorId: '=',
					ngHide: '='
				},
				template:  [
					'<div class="autosuggest-author-body" >',
						'<div class="list-group" >',
							'<a class="list-group-item" data-is-hidden="ngHide" data-ng-filter-keyword-author="ngAuthorKeyword" data-selected-author-id="selectedAuthorId" href="#" data-record-id="{{ author.id }}" data-author-name="{{ author.author_name }}" data-ng-repeat="author in items = ( authors | filter: ngAuthorKeyword) ">{{ author.author_name }}</a>',
							'<a class="list-group-item text-success save-new-author-suggest" data-ng-selected-author-id="selectedAuthorId" data-ng-author-keyword="ngAuthorKeyword" href="#" data-ng-if="items.length === 0 && ngAuthorKeyword != \'\'" data-author-list="authors" >Click here to save <b>{{ ngAuthorKeyword }}</b></a>',
					'</div>',
					'</div>'
				].join(""),
				link: function(scope, elem, attrs) {
					scope.$on('selectedAuthor', function(evt, data){
						/*scope.$apply('selectedAuthorId = '+ data.id);
						scope.$apply('ngAuthorKeyword = "' + data.name + '"');
						scope.$apply('ngHide = true');*/
						scope.selectedAuthorId = data.id;
						scope.ngAuthorKeyword = data.name;
						scope.ngHide = true;
						//scope.$apply();
					});
					scope.$on('hideAuthors', function(evt) {
						scope.$apply('ngHide = true');
					})
				}
			}
		})
	.directive('saveNewAuthorSuggest', function($http, $rootScope) {
		return {
			restrict: 'C',
			scope: {
				authorName: '=ngAuthorKeyword',
				authors: '=authorList',
				selectedAuthorId: '='
			},
			link: function(scope, elem, attrs) {
				elem.on('click', function() {
					elem.html('<small class="text-muted"><i>Please wait while saving new author...</i></small>');
					$http({
						url: '/management/author/async/new-author',
						data: {author_name: scope.authorName, type: 'async'},
						method: 'POST',
						headers: {
							'Content-Type': 'application/json'/* 'application/x-www-form-urlencoded; charset=UTF-8'*/
						}
					}).then(function(e){
						if(Object.keys(e.data.error).length > 0)
						{
							if( ! e.data.error.hasOwnProperty('author_name'))
							{
								return ;
							}
							var errorMsg = '';
							angular.forEach(e.data.error.author_name, function(v, i){
								errorMsg += '<small class="text-danger">'+ v + '</small>';
							});
							elem.html(errorMsg);
							return;
						}
						elem.html('<small class="text-primary">New author created!</small>');
						$rootScope.$broadcast('reloadAuthor');
						//scope.$apply('selectedAuthorId = '+ e.data.model.id + '; authorName = "' + e.data.model.author_name + '";')
						scope.$emit("selectedAuthor", {name: e.data.model.author_name, id: e.data.model.id });
					}, function(x) {
						console.log(x);
						elem.html('<small class="text-danger">Author not save. Unknown error.</small>');
					})
				})

			}
		}
	})
	.directive('authorNameSearch', function($rootScope){
		return {
			restrict: 'C',
			scope: {
				hideSuggestions: '=hide_author_suggestions'
			},
			link: function(scope, elem, attrs) {
				elem.on('keydown', function(e) {
					if(e.keyCode == 27)
					{
						$rootScope.$broadcast('hideAuthors');
					}
				})
			}
		}
	})
	.directive('ngFilterKeywordAuthor', function() {
		return {
			restrict: 'A',
			scope: {
				authorName: '@authorName',
				authorId: '@recordId',
				ngFilterKeywordAuthor: '=',
				isHidden: '='
			},
			link: function(scope, elem, attrs) {
				elem.on('click', function(e){
					scope.$emit("selectedAuthor", {name: scope.authorName, id: scope.authorId });
					scope.$apply();
					//scope.$apply('ngFilterKeywordAuthor = "'+ scope.authorName +'"; ');
				})
			}
		}
	})
})();