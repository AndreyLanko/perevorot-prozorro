(function(){
	'use strict';

	var json,
		_block;
	
	window.query_types=window.query_types||[];
	
	window.query_types.push({
		order: 100,
		prefix: 'cpv',
		name: 'CPV-код',
		pattern_search: /^(.*?)$/,
		pattern_exact: /^\d{1,8}-\d{1}$/,
		template: $('#block-cpv'),
		json: {
			check: '/form/check/cpv'
		},
		load: function(){
			$.ajax({
				method: 'POST',
				url: '/form/data/cpv',
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