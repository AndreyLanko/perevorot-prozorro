(function(){
	'use strict';

	var BLOCK = function(){
		var _block;
	
		var query_types={
			order: 500,
			prefix: 'edrpou',
			name: 'ЄДРПОУ замовника',
			button_name: 'ЄДРПОУ замовника',
			pattern_search: /^(.*?)$/,
			pattern_exact: /^\d{1,9}$/,
			template: $('#block-edrpou'),
			json: {
				check: '/form/check/edrpou'
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
							url: '/form/search/edrpou',
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
				return _block.find('[data-value]').data('value');
			}
		}
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();