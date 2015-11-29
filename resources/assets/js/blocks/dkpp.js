(function(){
	'use strict';

	var _block;

	window.query_types=window.query_types||[];
	
	window.query_types.push({
		order: 200,
		prefix: 'dkpp',
		name: 'ДКПП-код',
		pattern_search: /^(.*?)$/,
		pattern_exact: /^\d{1,9}$/,
		template: $('#block-dkpp'),
		json: {
			check: '/form/check/dkpp'
		},
		init: function(data, block){
			var input=block.find('.subinput');

			_block=block;

			if(data.is_exact){
				block.find('.subinput').remove();
				block.addClass('exact');				
			}else{
				input.selectize({
					options: [],
					openOnFocus: true,
					closeAfterSelect: true,
					maxItems: 1,
					maxOptions: 50,
				    labelField: 'name',
				    valueField: 'id',
				    searchField: ['name', 'id'],
					load: function(query, callback) {
				        if (!query.length) return callback();

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
						//if(!this.getValue()) {
						//	APP.utils.selectize.main().removeItem(data.id);
						//}
					},
					onInitialize: function(){
						this.$control_input.val(data.input);
	
						this.open();
						this.focus();
					},
					onChange: function(value){
						APP.utils.selectize.main().focus();
						APP.utils.query();
					}
				});
			}
		},
		result: function(){
			return _block.find('[data-value]').data('value');
		}
	});
})();