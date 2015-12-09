(function(){
	'use strict';

	var BLOCK = function(){
		var _input,
			_value;
		
		var query_types={
			order: 0,
			prefix: 'query',
			name: 'Ключове слово (назва товару, опис або назва замовника)',
			pattern_search: /^(.*?)$/,
			template: $('#block-query'),
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

				INPUT.focus();
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