(function(){
	'use strict';

	window.query_types=window.query_types||[];
	
	window.query_types.push({
		order: 500,
		prefix: 'date',
		name: 'Період прийому пропозицій',
		pattern_search: /^(\d*?)$/,
		pattern_exact: /^\d{1,2}.\d{1,2}-\d{2,4}$/,
		template: $('#block-date'),
		init: function(data, block){
			
		}
	});
})();