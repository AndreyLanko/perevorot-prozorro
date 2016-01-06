var DATE_SELECTED=[];

(function(){
	'use strict';

	var BLOCK = function(){
		var _block,
			pattern=/^(0[1-9]|1\d|2\d|3[01])\.(0[1-9]|1[0-2])\.(19|20)\d{2}$/,
			date_start,
			date_end,
			format='dd.mm.yyyy',
			date_types={
				tender: 'Дата приймання пропозиц',
				enquiry: 'Дата периоду уточнень',
				auction: 'Дата аукціону',
				award: 'Дата кваліфікації'				
			},
			current_date_type;

		var check_disabled=function(){
			DATE_SELECTED=[];
					
			$('.block-date-tooltip div').each(function(){
				var self=$(this);
				if(self.is('.active')){
					DATE_SELECTED.push(self.data('date_type'));
				}
			});
			
			$('.block-date-tooltip div:not(.active)').removeClass('disabled').each(function(){
				var self=$(this);
				if(DATE_SELECTED.indexOf(self.data('date_type'))>=0){
					self.addClass('disabled');
				}
			});
		}

		var query_types={
			order: 400,
			self: this,
			prefix: 'date',
			name: 'Дата начала приема предложений',
			button_name: 'Дати',
			pattern_search: pattern,
			pattern_exact: pattern,
			template: $('#block-date'),
			init: function(input_query, block){
				_block=block;

				var dates=block.find('.block-date-picker .date'),
					ever=false,
					datepair=block.find('.block-date-picker'),
					tooltip=block.find('.block-date-tooltip'),
					preselected_value=block.data('preselected_value');

				date_start=$(dates[0]);
				date_end=$(dates[1]);

				dates.datepicker({
					autoclose: true,
					format: format,
					orientation: 'bottom'
				});

				dates.inputmask({
					alias: 'dd.mm.yyyy',
					placeholder: 'дд.мм.рррр'
				});

				if(input_query){
					date_start.val(input_query);
					setCaretPosition(date_start[0], 3);

					date_start.datepicker('update');
				}
				
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

				$(document).click(function(e){
					if(!$(e.target).closest('.block-date').length){
						$('.block-date-tooltip').hide();
					}
				});
				
				block.find('.block-date-arrow').click(function(e){
					e.preventDefault();

					$('.block-date-tooltip').hide();
					tooltip.show();
				});

				tooltip.mouseleave(function(){
					tooltip.hide();
				});

				$.each(date_types, function(date_type, date_name){
					var div=$('<div/>');

					if(DATE_SELECTED.indexOf(date_type)>=0){
						div.addClass('disabled');
					}

					div.data('date_type', date_type);
					div.html(date_name);

					tooltip.append(div);
				});
				
				tooltip.find('div').click(function(){
					var self=$(this),
						date_type=self.data('date_type');

					if(self.is('.disabled') || self.is('.active')){
						return;
					}

					block.find('.block-key').html(self.html());

					tooltip.find('div').removeClass('active');
					self.addClass('active');

					check_disabled();
					tooltip.hide();
					
					current_date_type=date_type;
					
					APP.utils.query();
				});
				
				if(preselected_value){
					tooltip.find('div').each(function(){
						var self=$(this);
						
						if(self.data('date_type')==preselected_value.type && !self.is('.disabled')){
							self.click();
						}
					});

					date_start.val(preselected_value.value[0]);
					date_end.val(preselected_value.value[1]);
					INPUT.focus();
				}else{
					tooltip.find('div:not(.disabled):first').click();
					date_start.focus();
				}

				return this;
			},
			after_remove: function(){
				if($('.block.block-date').length<Object.size(date_types)){
					$('#buttons button').each(function(){
						if($(this).data('block_type')==query_types.prefix){
							$(this).removeAttr('disabled');
						}
					});
				}

				check_disabled();
			},
			after_add: function(){
				if($('.block.block-date').length==Object.size(date_types)){
					$('#buttons button').each(function(){
						if($(this).data('block_type')==query_types.prefix){
							$(this).attr('disabled', 'disabled');
						}
					})					
				}
			},
			suggest_item: function(row, input_query){
				var suggest_name;

				$.each(date_types, function(type, name){
					if(!suggest_name && DATE_SELECTED.indexOf(type)==-1){
						suggest_name=name;
					}
				});

				if(suggest_name){
					row=row.replace(/\{name\}/, suggest_name);
					row=row.replace(/\{value\}/, input_query);
					
					return row;
				}

				return false;
			},
			result: function(){
				var out=false;

				if(pattern.test(date_start.val()) && pattern.test(date_end.val())){
					out=[query_types.prefix+'['+current_date_type+']='+date_start.val()+'—'+date_end.val()];
				}

				return out;
			},
			validate: function(query){
				var valid='01.01.2016';

				return pattern.test(query+valid.substr(query.length));
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

	Object.size = function(obj) {
	    var size = 0, key;
	    for (key in obj) {
	        if (obj.hasOwnProperty(key)) size++;
	    }
	    return size;
	};
})();

