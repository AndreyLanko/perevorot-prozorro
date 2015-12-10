(function(){
	'use strict';

	var BLOCK = function(){
		var _block,
			pattern=/^(0[1-9]|1\d|2\d|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d{2}$/,
			date_start,
			date_end,
			format='dd.mm.yyyy';

		var query_types={
			order: 400,
			self: this,
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
			pattern_exact: pattern,
			template: $('#block-date'),
			init: function(data, block){
				_block=block;
				
				var dates=block.find('.block-date-picker .date'),
					ever=false,
					datepair=block.find('.block-date-picker'),
					tooltip=block.find('.block-date-tooltip');

				date_start=$(dates[0]);
				date_end=$(dates[1]);

                new Datepair(datepair[0]);

				dates.datepicker({
					'autoclose': true,
					'format': format
				});
				
				dates.inputmask({
					alias: 'dd.mm.yyyy',
					placeholder: 'дд.мм.рррр'
				});

				date_end.on('focus', function(){
					ever=true;
				});

				dates.on('blur', function(){
					dates.each(function(){
						if($(this).val()!='' && !$(this).inputmask("isComplete")){
							$(this).datepicker('update', '');
						}
					});

					APP.utils.query();
				})

				datepair.on('rangeSelected', function(date){
					if(pattern.test(date_start.val()) && pattern.test(date_end.val()) && ever){
						INPUT.focus();
					}else if(pattern.test(date_start.val())){
						$('.datepicker').hide();
						date_end.val('');
						date_end.focus();
					}else if(pattern.test(date_end.val())){
						$('.datepicker').hide();
						date_start.focus();
					}
				});

				block.find('.block-key').html(tooltip.find('div:first').html());

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

				block.find('.date:first').focus();

				return this;
			},
			result: function(){
				var out=false;

				if(pattern.test(date_start.val()) && pattern.test(date_end.val())){
					out=[
						'date_start='+date_start.val(),
						'date_end='+date_end.val()
					];
				}
				
				return out;
			}
		}
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();

