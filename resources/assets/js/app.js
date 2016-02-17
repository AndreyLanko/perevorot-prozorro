/*!
 *
 * Prozorro v1.0.0
 *
 * Author: Lanko Andrey (lanko@perevorot.com)
 *
 * © 2015
 *
 */
var APP,
	INPUT,
	SEARCH_BUTTON,
	BLOCKS,
	INITED=false,
	SEARCH_QUERY=[],
	SEARCH_QUERY_TIMEOUT,

	IS_MAC = /Mac/.test(navigator.userAgent),
	IS_HISTORY = (window.History ? window.History.enabled : false),

	KEY_BACKSPACE = 8,
	KEY_UP = 38,
	KEY_DOWN = 40,
	KEY_ESC = 27,
	KEY_RETURN = 13,
	KEY_CMD = IS_MAC ? 91 : 17,
	
	spin_options={
		color:'#6dc8eb',
		lines: 15,
		width: 2
	},
	
	spin_options_light={
		color:'#fff',
		lines: 15,
		width: 2
	};

(function(window, undefined){
	'use strict';

	var suggest_opened,
		suggest_current;

	APP = (function(){

		var viewport = function () {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return {width: e[a + 'Width'], height: e[a + 'Height']};
		};

		return {
			common: function(){
				$('html').removeClass('no-js');
			},

			js: {
    			    go_up_down: function(){
                    var offset = 220,
                        duration = 500,
                        goto_up=$('.back-to-top'),
                        goto_down=$('.go-down');
                    
                    $(window).scroll(function() {
                        if ($(this).scrollTop() > offset) {
                            goto_up.fadeIn(duration);
                        } else {
                           goto_up.fadeOut(duration);
                        }
                    });

                    goto_up.click(function(event) {
                        event.preventDefault();

                        $('html, body').animate({
                            scrollTop: 0
                        }, duration);
                    });

                    var topset = $(document).height()- 2*($(window).height());

                    $(window).scroll(function() {
                        if ($(this).scrollTop() < (topset+200)) {
                            goto_down.fadeIn(duration);
                        } else {
                            goto_down.fadeOut(duration);
                        }
                    });

                    var do_action = false;

                    goto_down.click(function(event) {
                        if(do_action){
                            return;
                        }

                        do_action=true;

                        event.preventDefault();

                        $('html, body').animate({
                            scrollTop: ($(document).scrollTop() + $(window).height())
                        }, duration, function(){
                            do_action=false;
                        });
                    });
    			    },
    			    home_more: function(_self){
        			    var text_height=0,
        			        text=$('.description .text'),
        			        check_height=function(){
                			    text_height=$('.text-itself').height()+20;
                        },
                        opened=false;

        			    $(window).resize(check_height);

                    check_height();
                    
        			    _self.click(function(e){
            			    e.preventDefault();
            			    $(this).closest('.description').toggleClass('opened');

            			    text.animate({
                			    height: !opened ? text_height : 0
                        }, 400);

                        opened=!opened;
        			    });
    			    },
				home_equal_height: function(_self){
					var max_height=0,
						blocks=_self.find('[block]');

					blocks.each(function(i){
						max_height=Math.max($(this).height(), max_height);
					});

					blocks.height(max_height);
				},
				tender_menu_fixed: function(_self){
					var offset=$('.wide-table:first').offset().top-50;
					
					_self.sticky({
						topSpacing: _self.position().top-80,
						responsiveWidth: true,
						bottomSpacing: $(document).height()-offset+_self.find('.tender--menu').height()+70
					});
				},
				tender: function(_self){
					_self.on('click', '.question--open', function(e){
						e.preventDefault();

						$(this).closest('.questions-block').find('.none').toggle();
						$(this).toggleClass('open');

						$('html, body').animate({
							scrollTop: $('.row.questions').offset().top-50
						}, 500);
					});

					_self.on('click', '.search-form--open', function(e){
						e.preventDefault();

						$(this).closest('.description-wr').toggleClass('open');							
					});					

					_self.find('.blue-btn').click(function(e){
						e.preventDefault();

						$('html, body').animate({
							scrollTop: $('.tender--platforms').position().top
						}, 500);
					});

					_self.find('.tender--offers--ancor').click(function(e){
						e.preventDefault();

						$('html, body').animate({
							scrollTop: $('.tender--offers.margin-bottom-xl').position().top-30
						}, 500);
					});

					$('a.documents-all').click(function(e){
						e.preventDefault();

						$('.overlay-documents-all').addClass('open');
					});

					$('a.info-all').click(function(e){
						e.preventDefault();

						$('.overlay-info-all').addClass('open');
					});

					$('a.document-link').click(function(e){
						e.preventDefault();

						$('.tender--offers.documents').hide();
						$('.tender--offers.documents[data-id='+$(this).data('id')+']').show();

						$('.overlay-documents').addClass('open');
					});

					$('.overlay-close').click(function(e){
						e.preventDefault();

						$('.overlay').removeClass('open');
					});
					
					$(document).keydown(function(e){
						if($('.overlay').is('.open')){
							switch (e.keyCode){
								case KEY_ESC:
									$('.overlay-close').click();
									return;
								break;
							}
						}
					});
				},
				search_result: function(_self){
					_self.on('click', '.search-form--open', function(e){
						e.preventDefault();
						$(this).closest('.description-wr').toggleClass('open');
					});

					_self.on('click', '.show-more', function(e){
						e.preventDefault();

						$('.show-more').addClass('loading').spin(spin_options_light);

						$.ajax({
							url: '/form/search',
							data: {
								query: APP.utils.get_query(),
								start: $('.show-more').data('start')
							},
							method: 'post',
							headers: APP.utils.csrf(),
							dataType: "json",
							success: function(response){
								$('.show-more').remove();

								if(response.html){
									$('#result').append(response.html);

									APP.utils.result_highlight(response.highlight);
								}
							}
						});
					});
				},
				form: function(_self){
					var timeout,
						input_query='',
						$document=$(document);

					APP.utils.totals.init();
					
					INPUT=_self;
					BLOCKS=$('#blocks');
					SEARCH_BUTTON=$('#search_button');

					setInterval(function(){
						if(input_query!=INPUT.val()){
							input_query=INPUT.val();

							if(input_query){
								clearTimeout(timeout);

								timeout=setTimeout(function(){
									APP.utils.suggest.show(input_query);
								}, 200);
							}

							if(INPUT.val()==''){
								APP.utils.suggest.clear();
							}
						}
					}, 100);

					setTimeout(function(){
						INPUT.val('');

						if(!INPUT.data('preselected')){
							INPUT.attr('placeholder', INPUT.data('placeholder'));
						}

						INPUT.focus();
					}, 500);

					SEARCH_BUTTON.click(function(){
						APP.utils.query();
					});
					
					BLOCKS.click(function(e){
						if($(e.target).closest('.block').length){
							return;
						}

						if(INPUT.val()!=''){
							$('#suggest').show();
						}
						
						INPUT.focus();
					});

					INPUT.focus(function(){
						if(INPUT.val()!=''){
							$('#suggest').show();
						}
					});
					
					$document.on('keydown', function(e) {
						_self.isCmdDown = e[IS_MAC ? 'metaKey' : 'ctrlKey'];
					});

					$document.on('keyup', function(e) {
						if (e.keyCode === KEY_CMD){
							_self.isCmdDown = false;
						}
					});
					
					INPUT.keydown(function(e){

						switch (e.keyCode){
							case 90://z
								if(_self.isCmdDown && INPUT.val()==''){
									//undelete
									return false;
								}
							break;
							
							case KEY_ESC:
								APP.utils.suggest.clear();
								return;
							break;

							case KEY_RETURN:
								$('#suggest a:eq('+suggest_current+')').click();

								return;
							break;

							case KEY_UP:
								if(APP.utils.suggest.opened()){
									if(suggest_current>0){
										suggest_current--;

										$('#suggest a').removeClass('selected');
										$('#suggest a:eq('+suggest_current+')').addClass('selected');

										return;
									}
								}
							break;

							case KEY_DOWN:
								if(APP.utils.suggest.opened()){
									if(suggest_current<$('#suggest a').length-1){
										suggest_current++;

										$('#suggest a').removeClass('selected');
										$('#suggest a:eq('+suggest_current+')').addClass('selected');

										return;
									}
								}							
							break;
							
							case KEY_BACKSPACE:
								if (INPUT.val()=='' && BLOCKS.find('.block').length){
									BLOCKS.find('.block:last').find('a.delete').click();

									return;
								}
							break;
						}
					});
					
					APP.utils.block.preload();
					APP.utils.block.buttons();

					$document.click(function(e){
						if(APP.utils.suggest.opened() && !$(e.target).closest('#blocks').length){
							$('#suggest').hide();
						}
					});

					$document.on('click', '#blocks a.delete', function(e){
						e.preventDefault();

						var block=$(this).closest('.block'),
							after_remove;

						if(typeof block.data('block').remove === 'function'){
							block.data('block').remove();
						}
						
						if(typeof block.data('block').after_remove === 'function'){
							after_remove=block.data('block').after_remove;
						}
						
						block.remove();

						if(after_remove){
							after_remove();
						}

						APP.utils.callback.remove();

						INPUT.focus();
						APP.utils.query();
					});

					APP.utils.history.bind();
					APP.utils.history.init();
				}
			},
			utils: {
    			    totals: {
        			    init: function(){
            			    var items_list=$('.items-list');

                        $('[mobile-totals]').click(function(e){
                            e.preventDefault();
    
                            $('html, body').animate({
                                scrollTop: items_list.position().top
                            }, 400);
                        });

                        APP.utils.totals.show();
                    },
        			    show: function(){
            			    var container=$('[mobile-totals]'),
            			        header_totals=$('[header-totals]'),
            			        total=header_totals.text();
                        
                        if(total){
                            container.removeClass('none-important').find('.result-all-link span').text(total);
                        }else{
                            container.addClass('none-important');
                        }
                    },
                    reset: function(){
                        $('[mobile-totals]').addClass('none-important');
                    }
    			    },
				history: {
					bind: function(){
						if (IS_HISTORY){
							window.History.Adapter.bind(window, 'statechange', function(){
								var state = window.History.getState();
							});
						}
					},
					init: function(){
						var search=location.search;

						if(search && search.indexOf('=')>0){
							search=search.substring(1).split('&');

							for(var i=0;i<search.length;i++){
								var param=search[i].split('=');
								if(param[0] && param[1]){

									if(param[0].indexOf('date[')>=0){
										param[1]={
											type: param[0].match(/\[(.*?)\]/)[1],
											value: decodeURI(param[1]).split('—')
										};

										param[0]='date';
									}

									var button=$('<div/>');

									button.data('input_query', '');
									button.data('block_type', param[0]);
									button.data('preselected_value', param[1]);

									APP.utils.block.add(button);
								}
							}
						}

						APP.utils.result_highlight(INPUT.data('highlight'));

						INITED=true;
					},
					push: function(){
						if (IS_HISTORY){
							window.History.pushState(null, document.title, '/search/'+(SEARCH_QUERY.length ? '?'+SEARCH_QUERY.join('&') : ''));
						}
					}
				},
				get_query: function(){
					var out=[];
						
					$('.block').each(function(){
						var self=$(this),
							block=self.data('block'),
							type=block.prefix;

						if(typeof block.result === 'function'){
							var result=block.result();

							if(typeof result === 'object'){
								out.push(result.join('&'));
							}else if(result){
								out.push(type+'='+result);
							}
						}
					});					
					
					return out;
				},
				query: function(){
					if(!INITED){
						return false;
					}

					clearTimeout(SEARCH_QUERY_TIMEOUT);

					SEARCH_QUERY_TIMEOUT=setTimeout(function(){
						SEARCH_QUERY=APP.utils.get_query();

						$('#server_query').val(SEARCH_QUERY.join('&'));
						SEARCH_BUTTON.prop('disabled', SEARCH_QUERY.length?'':'disabled')

						APP.utils.history.push();

						if(!SEARCH_QUERY.length){
							$('#result').html('');

							return;
						}

						$('#search_button').addClass('loading').spin(spin_options);

						$.ajax({
							url: '/form/search',
							data: {
								query: SEARCH_QUERY
							},
							method: 'post',
							headers: APP.utils.csrf(),
							dataType: "json",
							success: function(response){
								$('#search_button').removeClass('loading').spin(false);
								$('[homepage]').remove();
								
								if(response.html){
									$('#result').html(response.html);

									APP.utils.totals.show();
									APP.utils.result_highlight(response.highlight);
								}else{
									$('#result').html('Жодних результатiв');
								}
							}
						});
					}, 300);
				},
				result_highlight: function(words){
					if(words){
						$.each(words, function(key, value){
							$('#result').highlight(value, {
								element: 'i',
								className: 'select'
							});
						});
					}
				},
				block: {
					remove: function(e){
						e.preventDefault();
						
					},
					create: function(block_type){
						for(var i=0; i<window.query_types.length; i++){
							if(typeof window.query_types[i] === 'function'){
								var type=window.query_types[i]();
								
								if(type.prefix==block_type){
									return type;
								}
							}
						}						
					},
					add: function(self){
						var input_query=self.data('input_query'),
							block_type=self.data('block_type'),
							block=APP.utils.block.create(block_type),
							template=block.template ? block.template.clone().html() : null,
							is_exact=false//(type.pattern_exact && type.pattern_exact.test(input))

						if(template){
							block.value=input_query;
							template=APP.utils.parse_template(template, block);
						}else{
							template=input;
						}

						INPUT.removeClass('no_blocks').removeAttr('placeholder');

						if(self.data('preselected_value')){
							template.data('preselected_value', self.data('preselected_value'));
						}

						if(self.data('multi_value')){
							template.data('multi_value', self.data('multi_value'));
						}

						template.append('<a href="" class="delete">×</a>');

						BLOCKS.append(template);
						BLOCKS.append(INPUT);

						if(typeof block.init === 'function'){
							block=block.init(input_query, template);
						}else{
							INPUT.focus();
						}
						
						if(typeof block.after_add === 'function'){
							block.after_add();
						}						
						
						template.data('block', block);

						INPUT.val('');
					},
					preload: function(){
						for(var i=0; i<window.query_types.length; i++){
							if(typeof window.query_types[i] === 'function'){
								var type=window.query_types[i]();

								if(typeof type.load === 'function'){
									type.load();
								}
							}
						}						
					},
					buttons: function(){
						var button_blocks=[];
						
						for(var i=0; i<window.query_types.length; i++){
							if(typeof window.query_types[i] === 'function'){
								var type=window.query_types[i]();

								if(type.button_name){
									button_blocks.push(type);
								}
							}
						}
	
						button_blocks.sort(function(a, b){
							if (a.order < b.order)
								return -1;
	
							if (a.order > b.order)
								return 1;
	
							return 0;
						});
						
						for(var i=0; i<button_blocks.length; i++){
							APP.utils.button.add(button_blocks[i]);
						}
					}
				},
				callback: {
					remove: function(){
						if(!BLOCKS.find('.block').length){
							INPUT.addClass('no_blocks');
							INPUT.attr('placeholder', INPUT.data('placeholder'));
							APP.utils.totals.reset();
						}
					},
					check: function(suggest){
						return function(response, textStatus, jqXHR){
							if(response){
								suggest.removeClass('none');
							}else{
								suggest.remove();
							}
						}
					}
				},
				button: {
					add: function(block){
						var button=$('#helper-button').clone().html();

						button=$(button.replace(/\{name\}/, block.button_name));

						button.data('input_query', '');
						button.data('block_type', block.prefix);

						button.click(function(e){
							e.preventDefault();

							APP.utils.block.add($(this));
						});
						
						$('#buttons').append(button);
					}
				},
				suggest: {
					show: function(input_query){
						var blocks=APP.utils.detect_query_block(input_query),
							row,
							item;

						APP.utils.suggest.clear();

						if(blocks.length){
							$.each(blocks, function(index, block){
								row=$('#helper-suggest').clone().html();

								if(typeof block.suggest_item=='function'){
									row=block.suggest_item(row, input_query);
								}else{
									row=row.replace(/\{name\}/, block.name);
									row=row.replace(/\{value\}/, input_query);
								}

								if(row){
									item=$(row);
	
									if(input_query && block.json && block.json.check){
										$.ajax({
											method: 'POST',
											url: block.json.check,
											dataType: 'json',
											headers: APP.utils.csrf(),
											data: {
												query: input_query
											},
											success: APP.utils.callback.check(item)
										});
									}else{
										item.removeClass('none');
									}
	
									item.data('input_query', input_query);
									item.data('block_type', block.prefix);
	
									item.click(function(e){
										e.preventDefault();
	
										APP.utils.block.add($(this));
									});
	
									$('#suggest').append(item);
								}
							});

							$('#suggest a:first').addClass('selected');

							$('#suggest').show();
							
							suggest_opened=true;
						}
					},
					clear: function(){
						$('#suggest').hide().empty();
						suggest_current=0;
						suggest_opened=false;
					},
					opened: function(){
						return suggest_opened;
					}
				},
				detect_query_block: function(query){
					var types=[];

					for(var i=0; i<window.query_types.length; i++){
						if(typeof window.query_types[i] === 'function'){
							var type=window.query_types[i]();
							
							if(typeof type.validate === 'function' && type.validate(query)){
								types.push(type);
							} else if(type.pattern_search.test(query)){
								types.push(type);
							}
						}
					}

					types.sort(function(a, b){
						if (a.order < b.order)
							return -1;

						if (a.order > b.order)
							return 1;

						return 0;
					});
		
					return types;
				},
				parse_template: function(template, data){
					for(var i in data){
						template=template.replace(new RegExp('{' + i + '}', 'g'), data[i]);
					}
					
					return $(template);
				},
				csrf: function(){
					return {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					};
				}
			}
		};
	}());

	APP.common();

	$(function (){
		$('[data-js]').each(function(){
			var self = $(this);

			if (typeof APP.js[self.data('js')] === 'function'){
				APP.js[self.data('js')](self, self.data());
			} else {
				console.log('No `' + self.data('js') + '` function in app.js');
			}
		});
	});

})(window);

String.prototype.trunc = String.prototype.trunc || function(n){
	return (this.length > n) ? this.substr(0, n-1)+'&hellip;' : this;
};
/*
$.ajaxSetup({
   headers : {'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')}
});
*/