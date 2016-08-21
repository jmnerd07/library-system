(function() {
	'use strict'
	angular
		.module('BookManagementApp')
		// get the data type of a value
		.filter('typeOf', function() {
			return function(value){ 
				return (typeof value);
			};
		})
})();