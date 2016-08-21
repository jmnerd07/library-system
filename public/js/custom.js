/**
 * Resize function without multiple trigger
 * 
 * Usage:
 * $(window).smartresize(function(){  
 *     // code here
 * });
 */
(function($,sr){
    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
      var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args); 
                timeout = null; 
            }

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100); 
        };
    };

    // smartresize 
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');
/**
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var CURRENT_URL = window.location.href.split('?')[0],
    $BODY = $('body'),
    $MENU_TOGGLE = $('#menu_toggle'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $SIDEBAR_FOOTER = $('.sidebar-footer'),
    $LEFT_COL = $('.left_col'),
    $RIGHT_COL = $('.right_col'),
    $NAV_MENU = $('.nav_menu'),
    $FOOTER = $('footer');

// Sidebar
$(document).ready(function() {
    // TODO: This is some kind of easy fix, maybe we can improve this
    var setContentHeight = function () {
        // reset height
        $RIGHT_COL.css('min-height', $(window).height());

        var bodyHeight = $BODY.outerHeight(),
            footerHeight = $BODY.hasClass('footer_fixed') ? -10 : $FOOTER.height(),
            leftColHeight = $LEFT_COL.eq(1).height() + $SIDEBAR_FOOTER.height(),
            contentHeight = bodyHeight < leftColHeight ? leftColHeight : bodyHeight;

        // normalize content
        contentHeight -= $NAV_MENU.height() + footerHeight;

        $RIGHT_COL.css('min-height', contentHeight);
    };

    $SIDEBAR_MENU.find('a').on('click', function(ev) {
        var $li = $(this).parent();

        if ($li.is('.active')) {
            $li.removeClass('active active-sm');
            $('ul:first', $li).slideUp(function() {
                setContentHeight();
            });
        } else {
            // prevent closing menu if we are on child menu
            if (!$li.parent().is('.child_menu')) {
                $SIDEBAR_MENU.find('li').removeClass('active active-sm');
                $SIDEBAR_MENU.find('li ul').slideUp();
            }
            
            $li.addClass('active');

            $('ul:first', $li).slideDown(function() {
                setContentHeight();
            });
        }
    });

    // toggle small or large menu
    $MENU_TOGGLE.on('click', function() {
        if ($BODY.hasClass('nav-md')) {
            $SIDEBAR_MENU.find('li.active ul').hide();
            $SIDEBAR_MENU.find('li.active').addClass('active-sm').removeClass('active');
        } else {
            $SIDEBAR_MENU.find('li.active-sm ul').show();
            $SIDEBAR_MENU.find('li.active-sm').addClass('active').removeClass('active-sm');
        }

        $BODY.toggleClass('nav-md nav-sm');

        setContentHeight();
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown(function() {
        setContentHeight();
    }).parent().addClass('active');

    // recompute content when resizing
    $(window).smartresize(function(){  
        setContentHeight();
    });

    setContentHeight();

    // fixed sidebar
    if ($.fn.mCustomScrollbar) {
        $('.menu_fixed').mCustomScrollbar({
            autoHideScrollbar: true,
            theme: 'minimal',
            mouseWheel:{ preventDefault: true }
        });
    }
});
// /Sidebar

// Panel toolbox
$(document).ready(function() {
    $('.collapse-link').on('click', function() {
        var $BOX_PANEL = $(this).closest('.x_panel'),
            $ICON = $(this).find('i'),
            $BOX_CONTENT = $BOX_PANEL.find('.x_content');
        
        // fix for some div with hardcoded fix class
        if ($BOX_PANEL.attr('style')) {
            $BOX_CONTENT.slideToggle(200, function(){
                $BOX_PANEL.removeAttr('style');
            });
        } else {
            $BOX_CONTENT.slideToggle(200); 
            $BOX_PANEL.css('height', 'auto');  
        }

        $ICON.toggleClass('fa-chevron-up fa-chevron-down');
    });

    $('.close-link').click(function () {
        var $BOX_PANEL = $(this).closest('.x_panel');

        $BOX_PANEL.remove();
    });
});
// /Panel toolbox

// Tooltip
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });
});
// /Tooltip

// Progressbar
if ($(".progress .progress-bar")[0]) {
    $('.progress .progress-bar').progressbar();
}
// /Progressbar

// Switchery
$(document).ready(function() {
    if ($(".js-switch")[0]) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function (html) {
            var switchery = new Switchery(html, {
                color: '#26B99A'
            });
        });
    }
});
// /Switchery

// iCheck
$(document).ready(function() {
    if ($("input.flat")[0]) {
        $(document).ready(function () {
            $('input.flat').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass: 'iradio_flat-green'
            });
        });
    }
});
// /iCheck

// Table
$('table input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('table input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});

var checkState = '';

$('.bulk_action input').on('ifChecked', function () {
    checkState = '';
    $(this).parent().parent().parent().addClass('selected');
    countChecked();
});
$('.bulk_action input').on('ifUnchecked', function () {
    checkState = '';
    $(this).parent().parent().parent().removeClass('selected');
    countChecked();
});
$('.bulk_action input#check-all').on('ifChecked', function () {
    checkState = 'all';
    countChecked();
});
$('.bulk_action input#check-all').on('ifUnchecked', function () {
    checkState = 'none';
    countChecked();
});

function countChecked() {
    if (checkState === 'all') {
        $(".bulk_action input[name='table_records']").iCheck('check');
    }
    if (checkState === 'none') {
        $(".bulk_action input[name='table_records']").iCheck('uncheck');
    }

    var checkCount = $(".bulk_action input[name='table_records']:checked").length;

    if (checkCount) {
        $('.column-title').hide();
        $('.bulk-actions').show();
        $('.action-cnt').html(checkCount + ' Records Selected');
    } else {
        $('.column-title').show();
        $('.bulk-actions').hide();
    }
}

// Accordion
$(document).ready(function() {
    $(".expand").on("click", function () {
        $(this).next().slideToggle(200);
        $expand = $(this).find(">:first-child");

        if ($expand.text() == "+") {
            $expand.text("-");
        } else {
            $expand.text("+");
        }
    });
});

// NProgress
if (typeof NProgress != 'undefined') {
    /*$(document).ready(function () {
        NProgress.start();
    });

    $(window).on('load',function () {
        NProgress.done();
    });*/
    /*
    This line was removed because jQuery 3.* doesn't support .load() 
    $(window).load(function () {
        NProgress.done();
    });*/
}
(function(){
	'use strict';
	angular
		.module('BookManagementApp', [])
})();
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
(function(){
	'use strict';
	angular
		.module('BookManagementApp')
		.controller('BooksController', function($scope, $http){

		})
});
(function() { 
	angular
		.module('BookManagementApp')
		.controller('GenresController', ['$scope', function($scope){
			$('[data-toggle="tooltip"]').tooltip()
		}])
})();
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
(function(){
	'use strict';
	angular
	.module('BookManagementApp')
	.factory('AuthorsListSuggestFactory', () => {
		return AuthorsList(data);
		})
	/*.service('AuthorsListSuggestService', function(){
	 return {
	 showList: React.createFactory({
	 render: function() {
	 return (React.DOM.div);
	 }
	 })
	 }
	 })*/
})()
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
var AuthorsList = React.createFactory(React.createClass({
	getInitialState:function() {
		return {
			selectedID: -1
		}
	},
	onSelectAuthor: function(e) {
		//this.setState.selectedID = e.currentTarget.dataset.id;
		ReactDOM.findDOMNode(this.refs.id).value = e.currentTarget.dataset.id;
		$('#book-author').val(e.currentTarget.dataset.author);
		this.props.scope.$parent.toggleAuthorsSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	closeSuggestionBox: function() {
		this.props.scope.$parent.toggleAuthorsSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	render: function() {
		var keyword = (this.props.keyword ? this.props.keyword : '');
		var onSelectAuthor = this.onSelectAuthor;

		var _data = this.props.list.map(function(o){
			if(keyword !== '') {
				if(o.author_name.toLowerCase().match(keyword.toLowerCase())) {
					return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id, 'data-author':o.author_name, 'onClick': onSelectAuthor}, o.author_name);
				}
			}
			else {
				return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id,'data-author':o.author_name, 'onClick': onSelectAuthor}, o.author_name);
			}
		});
		return  React.DOM.div({className: 'author-suggest'},
			React.DOM.div(
				{className: 'autosuggest-author-body'},
				React.DOM.div(
					{className: "list-group"},
					_data, /*(this.state.showSuggestion ? _data : null),*/
					React.DOM.input({name: 'author_id', type: 'hidden', ref: 'id', value: this.state.selectedID})
				),
				React.DOM.a({className: 'list-group-item close-box-suggestion text-danger', 'onClick': this.closeSuggestionBox }, 'Close')
			)
		);
	}
}));
var PublishersList = React.createFactory(React.createClass({
	getInitialState:function() {
		return {
			selectedID: -1
		}
	},
	onSelectPublisher: function(e) {
		//this.setState.selectedID = e.currentTarget.dataset.id;
		ReactDOM.findDOMNode(this.refs.id).value = e.currentTarget.dataset.id;
		$('#book-publisher').val(e.currentTarget.dataset.publisher);
		this.props.scope.$parent.togglePublisherSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	closeSuggestionBox: function() {
		this.props.scope.$parent.togglePublisherSuggestion(true);
		this.props.scope.$parent.$apply()
	},
	render: function() {
		var keyword = (this.props.keyword ? this.props.keyword : '');
		var onSelectPublisher = this.onSelectPublisher;

		var _data = this.props.list.map(function(o){
			if(keyword !== '') {
				if(o.name.toLowerCase().match(keyword.toLowerCase())) {
					return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id, 'data-author':o.name, 'onClick': onSelectPublisher}, o.name);
				}
			}
			else {
				return React.DOM.a({className: 'list-group-item', href: '#','data-id': o.id,'data-author':o.name, 'onClick': onSelectPublisher}, o.name);
			}
		});
		return  React.DOM.div({className: 'publisher-suggest'},
		React.DOM.div(
		{className: 'autosuggest-publisher-body'},
		React.DOM.div(
		{className: "list-group"},
		_data, /*(this.state.showSuggestion ? _data : null),*/
		React.DOM.input({name: 'publisher_id', type: 'hidden', ref: 'id', value: this.state.selectedID})
		),
		React.DOM.a({className: 'list-group-item close-box-suggestion text-danger', 'onClick': this.closeSuggestionBox }, 'Close')
		)
		);
	}
}));
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
//# sourceMappingURL=custom.js.map
