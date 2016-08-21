(function(){
	'use strict'
	angular
		.module('BookManagementApp')
		.factory('ModalBoxFactory', function(){
			return {
				load: function(options) {
					var _defaultOpts = {
						title: '',
						body: '',
						footer: [
							'<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> ',
							'<button type="button" class="btn btn-primary">Ok</button>'
							].join("")
					};
					angular.extend(_defaultOpts, options);
					var _modal = angular.element('.modal');
					_modal.find('.modal-header .modal-title').html(_defaultOpts.title);
					_modal.find('.modal-body').html(_defaultOpts.body);
					_modal.find('.modal-footer').html(_defaultOpts.footer);
					return _modal;
				}
			};
		})
})();