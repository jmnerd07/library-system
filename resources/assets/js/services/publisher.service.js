(function(){
	'use strict';
	angular
	.module('BookManagementApp')
	.service('PublishersListSuggestService', function(){
		return {
			showList: function($data,$keyword, scope, $el) {
				ReactDOM.render(PublishersList({list: $data, keyword: $keyword, scope: scope }), $el)
			}
		}
	})
})();