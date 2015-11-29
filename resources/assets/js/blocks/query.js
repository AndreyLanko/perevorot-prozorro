(function(){
	'use strict';

	window.query_types=window.query_types||[];
	
	var input;
	
	window.query_types.push({
		order: 0,
		prefix: 'query',
		name: 'Ключове слово (назва товару, опис або назва замовника)',
		pattern_search: /^(.*?)$/,
		template: $('#block-query'),
		init: function(data, block){
			input=data.input;
			APP.utils.query();
		},
		result: function(){
			return input;
		}
	});
})();