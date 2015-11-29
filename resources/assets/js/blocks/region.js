(function(){
	'use strict';

	var json,
		_block;
	
	window.query_types=window.query_types||[];
	
	window.query_types.push({
		order: 300,
		prefix: 'region',
		name: 'Регіон',
		pattern_search: /^(.*?)$/,
		//pattern_exact: /^\d{1,8}-\d{1}$/,
		template: $('#block-region'),
		json: {
			check: '/form/check/region'
		},
		load: function(){
			$.ajax({
				method: 'POST',
				url: '/form/data/region',
				dataType: 'json',
				headers: APP.utils.csrf(),
				success: function(response){
					json=response;
				}
			});
		},
		init: function(data, block){
			var input=block.find('.subinput');

			_block=block;

			if(data.is_exact){
				block.find('.subinput').remove();
				block.addClass('exact');				
			}else{
				input.selectize({
					options: json,
					openOnFocus: true,
					closeAfterSelect: true,
					maxItems: 1,
					maxOptions: 50,
				    labelField: 'name',
				    valueField: 'id',
				    searchField: ['name', 'id'],
				    render:{
						option: function(item, escape) {
							return '<div>'+item.name+'</div>';
						},
						item: function(item, escape) {
							return '<div>'+item.name+'</div>';
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