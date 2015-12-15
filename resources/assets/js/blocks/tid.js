(function(){
	'use strict';

	var BLOCK = function(){
		var _input,
			_value;
	
		var query_types={
			order: 300,
			prefix: 'tid',
			name: '№ закупівлі',
			button_name: '№ закупівлі',
			pattern_search: /^(.*?)$/, //UA-2015-09-04-000137
			pattern_exact: /^\d{1,9}$/,
			template: $('#block-tid'),
			init: function(input_query, block){
				_input=block.find('input');
				_value=input_query;

				_input.autoGrowInput({
					minWidth: 20,
					comfortZone: 0
				});

				_input.keyup(function(){
					_value=_input.val();

					APP.utils.query();
				});

				if(_value){
					INPUT.focus();
				}else{
					_input.focus();
				}
				APP.utils.query();

				return this;	
			},
			result: function(){
				return _value;
			}
		};
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();