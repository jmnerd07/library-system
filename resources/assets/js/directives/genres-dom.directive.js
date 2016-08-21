(function(){
	angular
		.module('BookManagementApp')
		// On click "Add new genre" button
		.directive('ngCreateNewGenre', function(ModalBoxFactory, typeOfFilter, $compile) {
			return {
				restrict: 'C',
				link: function(scope, elem, attrs) {
					elem.on('click', function() {
						$compile(
							ModalBoxFactory.load({
								title: "Create New Genre",
								body: [
										'<form data-ng-init="genre.name = \'\'; genre.description=\'\'; genre.parent_genre_id=0;">',
								            '<div class="form-group genre-name-group">',
								              '<label for="genre-name" class="form-control-label"><sup class="text-danger">*</sup> Genre Name:</label>',
								              '<input type="text" data-ng-model="genre.name" class="form-control" id="genre-name">',
								              '<div class="notif-genre-name"></div>',
								            '</div>',
								            '<div class="form-group">',
								              '<label for="parent-genre" class="form-control-label">Parent Genre <small class="text-muted">(optional)</small>:</label>',
								              //'<select id="parent-genre" data-ng-options="g.id as g.name for g in genres track by g.id" class="form-control ng-genres-list-options" data-ng-genre-list="genres" data-ng-model="genre.parent_genre_id">',
								              '<select  id="parent-genre" class="form-control ng-genres-list-options" data-ng-genre-list="genres" data-ng-disabled="!genres">',
								              	'<option value="" data-ng-if="(genres | typeOf) == \'array\' || genres.length > 0"">-- Choose genre --</option>',
								              	'<option value="" data-ng-if="(genres | typeOf) == \'null\' || (genres | typeOf) == \'undefined\'">Loading genres...</option>',
								              	'<option value="" data-ng-if="(genres | typeOf) == \'array\' || genres.length == 0">No genres found</option>',
								              	'<option data-ng-repeat="(key, value) in genres" value="{{ value.id }}" data-ng-bind="value.name"></option>',
								              '</select>',
								            '</div>',
								            '<div class="form-group">',
								              '<label for="description-text" class="form-control-label">Description <small class="text-muted">(optional)</small>:</label>',
								              '<textarea class="form-control" data-ng-model="genre.description" id="description-text"></textarea>',
								            '</div>',
								        '</form>'
									].join(''),
								footer: [
										'<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> ',
										'<button type="submit" class="btn btn-primary ng-save-genre" data-ng-genre-data="genre">Save</button>'
									].join("")

							})
						)(scope);
					})
				}
			}
		})
		// On click edit genre button
		.directive('ngButtonEditGenre', function(ModalBoxFactory, $compile){
			return {
				restrict: 'C',
				scope: {
					genreId: '@'
				},
				link: function(scope, elem, attrs) {
					elem.on('click', function(){ 
						$compile(
							ModalBoxFactory.load({
								title: "Edit Genre",
								body: [
										'<div class="ng-load-edit-genre-details" data-genre-id="'+ scope.genreId +'">',
											'<div align="center">',
												'<h3 class="text-muted">Please wait</h3>',
												'<small class="text-muted">(Fetching Genre Details)</small>',
											'</div>',
										'</div>'
									].join(''),
								footer: ""
							})
						)(scope);
					});
				}
			};
		})
		.directive('ngLoadEditGenreDetails', function(ModalBoxFactory, typeOfFilter,  $http, $compile){
			return {
				restrict: 'C',
				scope: {
					genreId: '@'
				},
				link: function(scope, elem, attr) {
					var genreId = scope.genreId;
					
					angular.element(elem).ready(function(i){
						$http({
							method: 'POST',
							headers: {
								'X-Requested-With':'XMLHttpRequest'
							},
							url: 'genres/async/edit-genre',
							data: {id: genreId}
						}).then(
							function(r) {
								var genreDetails = r.data;
								$compile(
									ModalBoxFactory.load({
										title: "Edit Genre",
										body: [
												'<form>',
										            '<div class="form-group genre-name-group" data-ng-init="fielDisabled = false;">',
										              	'<label for="genre-name" class="form-control-label"><sup class="text-danger">*</sup> Genre Name:</label>',
										              	'<input type="text" class="form-control edit-genre-name" data-ng-disabled="fielDisabled" id="genre-name" data-ng-init="genre.name=\''+ genreDetails.name +'\'; genre.parent_genre_id='+ genreDetails.parent_genre_id +'" data-ng-model="genre.name" data-ng-bind="genre.name">',
										              	'<div class="notif-genre-name"></div>',
										            '</div>',
										            '<div class="form-group" data-ng-if="genre.parent_genre_id">',
											            '<label for="parent-genre" class="form-control-label">Parent Genre <small class="text-muted">(optional)</small>:</label>',
											            '<select  id="parent-genre" class="form-control ng-genres-list-options" data-ng-genre-list="genres" data-ng-disabled="!genres">',
											              	'<option value="" data-ng-if="(genres | typeOf) == \'array\' || genres.length > 0"">-- Choose genre --</option>',
											              	'<option value="" data-ng-if="(genres | typeOf) == \'null\' || (genres | typeOf) == \'undefined\'">Loading genres...</option>',
											              	'<option value="" data-ng-if="(genres | typeOf) == \'array\' || genres.length == 0">No genres found</option>',
											            	'<option data-ng-repeat="(key, value) in genres" data-ng-value="value.id" data-ng-bind="value.name" data-ng-selected="{{ (value.id == \''+ genreDetails.parent_genre_id +'\') }}"></option>',
											            '</select>',
											        '</div>',
										            '<div class="form-group">',
										              	'<label for="description-text" class="form-control-label">Description <small class="text-muted">(optional)</small>:</label>',
										              	'<textarea class="form-control" id="description-text" data-ng-disabled="fielDisabled" data-ng-init="genre.description=\''+ (genreDetails.description ? genreDetails.description: "")  +'\'" data-ng-bind="genre.description" data-ng-model="genre.description"></textarea>',
										            '</div>',
										        '</form>'
											].join(''),
										footer: [
											'<button type="button" class="btn btn-secondary" data-dismiss="modal" data-ng-disabled="fielDisabled" >Close</button> ',
											'<button type="submit" class="btn btn-primary ng-save-changes-genre-details" data-ng-disable="fielDisabled" data-genre="genre" data-ng-init="genre.id='+ genreDetails.id +';">Save</button>'
										].join("")
									})
								)(scope.$new());								
							},
							function(r) { 
								console.log(r)
							}
						);

					})
				}
			};
		})
		// On click save button for editing genre details
		.directive('ngSaveChangesGenreDetails', function(ModalBoxFactory, $http) {
			return {
				restrict: 'C',
				scope: {
					genre: '=',
					ngDisabled: '='
				},
				link: function(scope, elem, attr) {

					elem.on('click', function(e) {
						elem[0].disabled = true;
						console.log(scope.genre)
						$http({
							method: 'POST',
							headers: {
								'X-Requested-With':'XMLHttpRequest'
							},
							url: 'genres/async/modify-genre',
							data: scope.genre
						}).then(
							function(r) {
								console.log(r.data)
								elem[0].disabled = false;
							},
							function(r) {
								console.log(r);
								elem[0].disabled = false;
							}
						);
					})
				}
			}
		})
		// Loads all Parent Genre in dropdown of Creating New Genre
		.directive('ngGenresListOptions', function($http, $compile){
			return {
				restrict: 'C',
				scope: {
					ngGenreList: '='
				},
				link: function(scope, elem, attrs) {
					$http({
						method: 'POST',
						headers: {
							'X-Requested-With':'XMLHttpRequest'
						},
						url: 'genres/async/list',
						data: {_requestType: 'LIST_PARENT'}
					}).then(
						function(r) {
							if(r.data.rows > 1)
							{
								scope.ngGenreList = r.data.data;
							}
						},
						function(r) {
							console.log(r)
						}
					);
				}

			}
		})
		.directive('ngSaveGenre', function(ModalBoxFactory, $http){
			return {
				restrict: 'C',
				scope: {
					genreData: "=ngGenreData"
				},
				link: function(scope, elem, attrs) {
					elem.on('click', function(){
						// remove all notification messages
						angular.element('.notif-genre-name').children().remove();

						// remove success/error border color
						angular.element('.genre-name-group').removeClass('has-danger').removeClass('has-success');

						// disable textfield and remove icons
						angular.element('.genre-name-group').find('.form-control').attr('disabled', 'disabled').removeClass('form-control-danger').removeClass('form-control-success');
						
						var postData = scope.genreData;
						postData._requestType = 'VALIDATE';
						$http({
							method: 'POST',
							headers: {
								'X-Requested-With': 'XMLHttpRequest'
							},
							url: 'genres/async/new-genre',
							data: postData
						}).then(
							function(r) { // on success
								// If no error found
								if(typeof r.data !== "object")
								{
									console.info('Unknown error');
									return;
								}
								if(r.data.length === 0)
								{
									// Remove notification messages
									angular.element('.notif-genre-name').children().remove();

									// Add field border color
									angular.element('.genre-name-group').addClass('has-success');

									// Show success icon
									angular.element('.genre-name-group').find('.form-control').removeAttr('disabled', 'disabled').addClass('form-control-success');

									ModalBoxFactory.load({
										title: "Create New Genre - Success",
										body: [
												'<h3 class="text-success">Request successful</h3>',
												'<p class="text-success">New genre successfully created.</p>'
											].join(''),
										footer: ""
									})
									window.location.href = window.location.href;
								}
							},
							function(r) { // on error

								// If validation fails
								if(r.status == 422)
								{
									// if error messages are empty
									if(!r.data)
									{
										return;
									}
									
									// notification messages
									var notificationMessage = "";

									// get all array of notification messages
									for(var key in r.data)
									{
										if(r.data[key])
										{
											r.data[key].forEach(function(v, i){
												notificationMessage += '<p><small class="text-danger">' + v + '</small></p>';
											})
											if(key !== '_requestType')
											{
												// Show notification message
												angular.element('.notif-genre-name').html(notificationMessage);

												// Add field border color
												angular.element('.genre-name-group').addClass('has-danger');

												// Show error icon
												angular.element('.genre-name-group').find('.form-control').removeAttr('disabled', 'disabled').addClass('form-control-danger');
											}
										}
									}
								}
							}
						)
					})
				}
			}
		})
})();