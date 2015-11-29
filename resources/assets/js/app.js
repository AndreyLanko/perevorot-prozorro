/*!
 *
 * Prozorro v1.0.0
 *
 * Author: Lanko Andrey (lanko@perevorot.com)
 *
 * © 2015
 *
 */
var APP;
var SEARCH_QUERY;

(function (window, undefined) {
	'use strict';

	APP = (function(){

		return {
			common: function () {
				$('html').removeClass('no-js');
			},

			js: {
				form: function(_self) {
					var input=_self,
						selectize;

					input.selectize({
						plugins: [
							'remove_button',
							'restore_on_backspace'
						],
						//hideSelected: true,
						//closeAfterSelect: true,
					    labelField: 'view',
					    valueField: 'id',
						render: {
							item: function(item, escape) {
								return item.view;
							},
							option_create: function(){
								return '';
							}
						},
						onItemAdd: function(value, $item){
							if(typeof value.type.init === 'function'){
								value.type.init(value, $item);
							}
						},
						onItemRemove: function(){
							APP.utils.query();	
						},
						create: function(input) {
							return input;
						},
						createFilter: function(input) {
							return true;
						},
						createOnBlur: function(){
							//APP.utils.suggest.clear();
						},
						onInitialize: function(){
							APP.utils.callback.after_init(this);
						}
					});

					APP.utils.block.preload();
				}
			},
			utils: {
				selectize: {
					main: function(){
						return $('#query')[0].selectize;
					}
				},
				query: function(){
					SEARCH_QUERY=[];

					$('.block').each(function(){
						var self=$(this),
							type=self.data('value').split('-')[0];
						
						for(var i=0; i<window.query_types.length; i++){
							var t=window.query_types[i];
							
							if(typeof t.result === 'function' && t.prefix==type){
								SEARCH_QUERY.push(type+'='+t.result());
							}
						}
					});

					$('#server_query').val(SEARCH_QUERY.join('&'));

					$.ajax({
						url: "//aws3.tk/search",
						dataType: "json",
						data: {
							q: SEARCH_QUERY.join('&'),
						},
						success: function(response){
							var out=[];

							for(var ii=0;ii<response.res.length;ii++){
								var item=response.res[ii];
								var it=[];

								if(item._source.items && item._source.items.length){
									for(var i=0;i<item._source.items.length;i++){
										it.push('<div>'+item._source.items[i].classification.description+' #'+item._source.items[i].classification.id+'</div>')
									};
								}

								out.push('<h4>'+item._source.title+'</h4>'+it.join('')+(item._source.tenderPeriod?'<div>'+item._source.tenderPeriod.startDate+'—'+item._source.tenderPeriod.endDate+'</div>':''));
							};
							
							$('#result').html(out.join(''));							
						}
					});

				},
				block: {
					add: function(e){
						e.preventDefault();

						var self=$(this),
							input=self.data('input'),
							type=self.data('type'),
							template=type.template?type.template.clone().html():null,
							is_exact=false,//(type.pattern_exact && type.pattern_exact.test(input)),
							item;

						if(self.is('.disabled')){
							return false;
						}
						
						if(template){
							type.value=input;
							template=APP.utils.parse_template(template, type);
						}else{
							template=input;
						}

						APP.utils.suggest.clear();

						APP.utils.selectize.main().createItem({
							id: type.prefix+'-'+makeid(),
							view: template,
							is_exact: is_exact,
							type: type,
							input: input
						});

						if(!type.init){
							APP.utils.selectize.main().focus();
						}
					},
					preload: function(){
						for(var i=0; i<window.query_types.length; i++){
							if(typeof window.query_types[i].load === 'function'){
								window.query_types[i].load();
							}
						}						
					}
				},
				callback: {
					check: function(suggest){
						return function(response, textStatus, jqXHR) {
							suggest.find('.loader').remove();

							if(response){
								suggest.removeClass('disabled');
							}
						}
					},
					after_init: function(_self){
						var timeout,
							input_query='';

						$('.selectize-dropdown.multi').remove();
						
						var control_input=_self.$control_input;
	
						setInterval(function(){
							if(input_query!=control_input.val()){
								input_query=control_input.val();
	
								if(input_query){
									clearTimeout(timeout);
	
									timeout=setTimeout(function(){
										APP.utils.suggest.show(input_query);
									}, 200);
								}

								if(control_input.val()==''){
									console.log('clear suggest');
									setTimeout(APP.utils.suggest.clear, 300);
								}
							}
						}, 100);
						
						setTimeout(function(){
							//control_input.val('03000000-1');
							control_input.focus();
						}, 500);						
					}
				},
				suggest: {
					array: function(input_query){
						var types=APP.utils.detect_query_type(input_query),
							array=[];

						if(types.length){
							$.each(types, function(index, type){
								array.push({
									type: type,
									name: type.name+': '+input_query
								});
							});
						}

						return array;				
					},
					show: function(input_query){
						var types=APP.utils.detect_query_type(input_query),
							row, suggest;
	
						APP.utils.suggest.clear();
	
						if(types.length){
							$.each(types, function(index, type){
								row=$('#block-suggest').clone().html();

								row=row.replace(/\{name\}/, type.name);
								row=row.replace(/\{value\}/, input_query);
	
								suggest=$(row);

								if(input_query && type.json && type.json.check){
									$.ajax({
										method: 'POST',
										url: type.json.check,
										dataType: 'json',
										headers: APP.utils.csrf(),
										data: {
											query: input_query
										},
										success: APP.utils.callback.check(suggest)
									});
								}else{
									suggest.find('.loader').remove();
									suggest.removeClass('disabled');
								}

								suggest.data('input', input_query);
								suggest.data('type', type);

								suggest.click(APP.utils.block.add);
								
								$('#suggest').append(suggest);
							});

							$('#suggest').show();
						}
					},
					clear: function(){
						$('#suggest').hide().empty();
					}
				},
				detect_query_type: function(query){
					var types=[];

					for(var i=0; i<window.query_types.length; i++){
						if(window.query_types[i].pattern_search.test(query)){
							types.push(window.query_types[i]);
						}
					}

					types.sort(function(a, b) {
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
					
					return template;
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

	$(function () {
		$('[data-js]').each(function () {
			var self = $(this);

			if (typeof APP.js[self.data('js')] === 'function') {
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

function makeid(){
    var text="";
    var possible="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for(var i=0;i<8;i++){
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
}