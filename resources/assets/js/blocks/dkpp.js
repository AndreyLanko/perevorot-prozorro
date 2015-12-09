(function(){
	'use strict';

	var BLOCK = function(){
		var _block;
	
		var query_types={
			order: 300,
			prefix: 'dkpp',
			name: 'ДКПП-код',
			button_name: 'ДКПП-код (архив)',
			pattern_search: /^(.*?)$/,
			pattern_exact: /^\d{1,9}$/,
			template: $('#block-dkpp'),
			json: {
				check: '/form/check/dkpp'
			},
			init: function(input_query, block){
				var input=block.find('select');
	
				_block=block;
	
				input.selectize({
					options: [],
					openOnFocus: true,
					closeAfterSelect: true,
					maxItems: 1,
					maxOptions: 50,
					labelField: 'name',
					valueField: 'id',
					searchField: [
						'name',
						'id'
					],
					load: function(query, callback) {
						if (!query.length){
							return callback();
						}
	
						$.ajax({
							url: '/form/search/dkpp',
							type: 'POST',
							dataType: 'json',
							headers: APP.utils.csrf(),
							data: {
								query: query
							},
							error: function() {
								callback();
							},
							success: function(res) {
								callback(res);
							}
						});
					},
					render:{
						option: function(item, escape) {
							return '<div>'+item.name+' #'+item.id+'</div>';
						},
						item: function(item, escape) {
							return '<div>'+item.id+' — '+item.name.trunc(40)+'</div>';
						}
					},
					onBlur: function(){
						_block.removeClass('no-results');
					},
					onLoad: function(data){
						_block[data && !data.length?'addClass':'removeClass']('no-results');
					},
					onInitialize: function(){
						this.$control_input.val(input_query);
						this.$control_input.keyup();
	
						this.open();
						this.focus();
					},
					onChange: function(value){
						INPUT.focus();
						APP.utils.query();
					}
				});
				
				return this;
			},
			result: function(){
				var value=_block.find('[data-value]').data('value');

				return value!='' ? value : false;
			}
		};
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();