(function(){
	'use strict';

	var BLOCK = function(){
		var _input_query;
		
		var query_types={
			order: 0,
			prefix: 'query',
			name: 'Ключове слово (назва товару, опис або назва замовника)',
			pattern_search: /^(.*?)$/,
			template: $('#block-query'),
			init: function(input_query, block){
				_input_query=input_query;

				INPUT.focus();
				APP.utils.query();

				return this;	
			},
			result: function(){
				return _input_query;
			}
		};

		return query_types;
	}
	
	window.query_types=window.query_types||[];
	window.query_types.push(BLOCK);
})();