(function(){
	'use strict';

	var json;

	var BLOCK = function(){
		var _block;
	
		var query_types={
			order: 200,
			prefix: 'cpv',
			name: 'CPV-код',
			button_name: 'CPV-код',
			pattern_search: /^(.*?)$/,
			pattern_exact: /^\d{1,8}-\d{1}$/,
			template: $('#block-cpv'),
			json: {
				check: '/form/check/cpv'
			},
			load: function(){
				if(!json){
					$.ajax({
						method: 'POST',
						url: '/form/data/cpv',
						dataType: 'json',
						headers: APP.utils.csrf(),
						success: function(response){
							json=response;
						}
					});
				}
			},
			remove: function(){
				//clearInterval(interval);
			},
			init: function(input_query, block){
				var input=block.find('select'),
					button=block.find('button'),
					preselected_value=block.data('preselected_value'),
					checked_options;

				var dropdown,
					offset,
					interval,
					is_select=false;

				_block=block;
				
				input.selectize({
					options: json,
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
					render:{
						option: function(item, escape) {
							return '<div><input type="checkbox" data-selectable value="'+item.id+'">'+item.name+' #'+item.id+'</div>';
						},
						item: function(item, escape) {
							return '<div>'+item.id+' — '+item.name.trunc(40)+'</div>';
						}
					},
					onInitialize: function(){
						if(preselected_value){
							this.setValue(preselected_value);
						}else{
							this.open();
		
							this.$control_input.val(input_query);
							this.$control_input.trigger('update');
							this.$control_input.focus();
						}
					},
					onChange: function(value){
						INPUT.focus();
						APP.utils.query();
					},
					onType: function(text){
						_block[!this.currentResults.items.length?'addClass':'removeClass']('no-results');
					},
      				onDropdownOpen: function(){
						interval=setInterval(function(){
							var checked_options=_block.find('input[type="checkbox"]:checked');
			
							button[checked_options.length?'show':'hide']();
	
							if(checked_options.length){
								offset=dropdown.offset();

								button.css({
									top: offset.top+dropdown.height()-16,
									left: 37,
									position: 'absolute'
								});
								
								button.find('span').html(checked_options.length);
							}
						}, 200);
					},
					onDropdownClose: function(){
						if(is_select){
							checked_options=_block.find('input[type="checkbox"]:checked');
		
							if(checked_options[0]){
								this.setValue(checked_options[0].value);
		
								checked_options=checked_options.slice(1);
							}
		
							$.each(checked_options, function(i){
								var self=$(this);
			
								self.data('input_query', '');
								self.data('block_type', query_types.prefix);

								self.data('preselected_value', self.val());
			
								APP.utils.block.add(self);
							});
	
							setTimeout(function(){
								INPUT.focus();
							}, 300);
						}
	
						clearInterval(interval);
					},
					onBlur: function(){
						_block.removeClass('no-results');
						button.hide();
					}
				});
				
				dropdown=_block.find('.selectize-dropdown');
	
				button.mousedown(function(e){
					is_select=true;
				});

				return this;	
			},
			result: function(){
				return _block.find('[data-value]').data('value');
			}
		};
		
		return query_types;
	}
		
	window.query_types=window.query_types||[];
	window.query_types.push(BLOCK);
})();