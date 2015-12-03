(function(){
	'use strict';

	var BLOCK = function(){
		var _block,
			datepair;
	
		var query_types={
			order: 400,
			prefix: 'date',
			name: 'Дата начала приема предложений',/*
				{
				enquiryPeriod_startDate: 'Дата начала периода уточнений',
				enquiryPeriod_endDate: 'Дата завершения периода уточнений',
				tenderPeriod_startDate: 'Дата начала приема предложений',
				tenderPeriod_endDate: 'Дата завершения приема предложений',
				auctionPeriod_startDate: 'Дата начала аукциона',
				auctionPeriod_endDate: 'Дата завершения аукциона',
				awardPeriod_startDate: 'Дата начала квалификации'				
			}
			*/
			button_name: 'Дати',
			pattern_search: /^(\d*?)$/,
			pattern_exact: /^\d{1,2}.\d{1,2}-\d{2,4}$/,
			template: $('#block-date'),
			init: function(data, block){
				INPUT.focus();

				block.find('.block-date-picker .date').datepicker({
					'autoclose': true,
					'format': 'dd.mm.yyyy'
				});

                datepair = new Datepair(block.find('.block-date-picker')[0], {
					'defaultDateDelta': 1
				});
				var tooltip=block.find('.block-date-tooltip');

				block.find('.date:first').focus();
				block.find('.block-key').html(tooltip.find('div:first').html());

				APP.utils.query();

				block.find('.block-date-arrow').click(function(e){
					e.preventDefault();
					tooltip.show();
				});

				tooltip.mouseleave(function(){
					tooltip.hide();
				});

				tooltip.find('div').click(function(){
					block.find('.block-key').html($(this).html());
					tooltip.hide();
				});

				return this;
			},
			result: function(){
				return datepair;
			}
		}
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();

