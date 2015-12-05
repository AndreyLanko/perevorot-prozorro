(function(){
	'use strict';

	var BLOCK = function(){
		var _block,
			datepair,
			pattern=/^(0[1-9]|1\d|2\d|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d{2}$/,
			format='dd.mm.yyyy';
	
		var is_valid=function(){
			var out=0;

			_block.find('.date').each(function(){
				if(pattern.test($(this).val())){
					out++;
				}
			});
			
			return out==2;
		};

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
				//INPUT.focus();
				_block=block;
				
				block.find('.block-date-picker .date').datepicker({
					'autoclose': true,
					'format': format
				});
				
				block.find('.block-date-picker .date').inputmask({
					alias: 'dd.mm.yyyy',
					placeholder: 'дд.мм.рррр'
				});

				block.find('.block-date-picker').on('rangeSelected', function(date){
					if(is_valid()) {
						$('.datepicker').hide();
						APP.utils.query();
						INPUT.focus();
					}
				});

                var datepair = new Datepair(block.find('.block-date-picker')[0], {
					'defaultDateDelta': 1,
				});

				var tooltip=block.find('.block-date-tooltip');

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
				var out=[];

				_block.find('.date').each(function(){
					out.push($(this).attr('class').replace(' ', '-')+'[]='+$(this).val());
				});
				
				return out.join('&');
			}
		}
		
		return query_types;
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();

