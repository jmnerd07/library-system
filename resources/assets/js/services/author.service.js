(function(){
	'use strict';
	angular
		.module('BookManagementApp')
		.service('AuthorsListSuggestService', function(){
			return {
				showList: function($data,$keyword, scope, $el) {
					ReactDOM.render(AuthorsList({list: $data, keyword: $keyword, scope: scope }), $el)
				}
			}
		})
})();