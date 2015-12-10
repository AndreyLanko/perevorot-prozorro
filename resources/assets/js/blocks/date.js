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
			init: function(input_query, block){
				_block=block;

				var dates=block.find('.block-date-picker .date'),
					ever=false,
					datepair=block.find('.block-date-picker'),
					tooltip=block.find('.block-date-tooltip');

				date_start=$(dates[0]);
				date_end=$(dates[1]);

				if(input_query){
					date_start.val(input_query);
					setCaretPosition(date_start[0], 3);
				}

				dates.datepicker({
					'autoclose': true,
					'format': format
				});
				
				dates.inputmask({
					alias: 'dd.mm.yyyy',
					placeholder: 'дд.мм.рррр'
				});

				dates.on('blur', function(){
					dates.each(function(){
						if($(this).val()!='' && !$(this).inputmask("isComplete")){
							$(this).datepicker('update', '');
						}
					});

					APP.utils.query();
				})

				date_start.on('changeDate', function(date){
					if(!pattern.test(date_start.val())){
						return;
					}

					date_end.datepicker("setStartDate", date_start.datepicker("getDate"));

					if(!pattern.test(date_end.val())){
						$('.datepicker').hide();
						date_end.focus();
					}
					
					if(pattern.test(date_start.val()) && pattern.test(date_end.val())){
						$('.datepicker').hide();
						INPUT.focus();
					}
				});

				date_end.on('changeDate', function(date){
					if(!pattern.test(date_end.val())){
						return;
					}

					date_start.datepicker("setEndDate", date_end.datepicker("getDate"));

					if(!pattern.test(date_start.val())){
						$('.datepicker').hide();
						date_start.focus();

						return;
					}

					if(pattern.test(date_start.val()) && pattern.test(date_end.val())){
						$('.datepicker').hide();
						INPUT.focus();
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
	
	function setCaretPosition(elem, caretPos) {
	    if(elem != null) {
	        if(elem.createTextRange) {
	            var range = elem.createTextRange();
	            range.move('character', caretPos);
	            range.select();
	        }
	        else {
	            if(elem.selectionStart) {
	                elem.focus();
	                elem.setSelectionRange(caretPos, caretPos);
	            }
	            else
	                elem.focus();
	        }
	    }
	}

	window.query_types=window.query_types||[];	
	window.query_types.push(BLOCK);
})();

