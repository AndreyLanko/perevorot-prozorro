/*!
 *
 * Prozorro v1.0.0
 *
 * Author: Lanko Andrey (lanko@perevorot.com)
 *
 * © 2015
 *
 */

(function (window, undefined) {
	"use strict";

	var APP = (function () {

		return {
			common: function () {
				$("html").removeClass("no-js");
			},

			js: {
				form: function(_self) {
					var input=_self,
						value="",
						timeout,
						selectize;

					input.selectize({
						openOnFocus: false,
						closeAfterSelect: true,
						maxOptions: 50,
					    plugins: ['remove_button'],
					    labelField: 'name',
					    valueField: 'id',
					    searchField: ['name', 'id'],
					    render:{
						    option_create: function(data, escape) {
								return '<div class="create">Шукати <strong>' + escape(data.input) + '</strong>&hellip;</div>';
							},
							option: function(item, escape) {
								return '<div>'+item.name+' #'+item.id+'</div>';
							},
							item: function(item, escape) {
								return '<div>'+item.name.trunc(40)+(item.id!='noid'?' #'+item.id:'')+'</div>';
							}
						},
					    create: function(input) {
							return {
								id: 'noid',
								name: input
							}
						}
					});

					$.getJSON('json/raw', function(data){
						selectize.addOption(data);
						selectize.focus();
					});

					selectize=input[0].selectize;

					selectize.on('change', function() {
						search(selectize.getValue());
					});					
					
					var search=function(search_query){
						return;
						$.ajax({
							url: "//aws3.tk/search",
							jsonp: "callback",
							dataType: "json",
							data: {
								q: search_query,
							},
							success: function(response){
								console.log(response);
							}
						});
					}

					/*
					setInterval(function(){

						if(value!=input.val()){
							value=input.val();

							if(value){
								clearTimeout(timeout);

								timeout=setTimeout(function(){
									debug(filter(value), true);
								}, 200);
							}
						}
					}, 100);
					*/

					/*
					var filter=function(value){
						var length=value.length-1,
							property=value.toLowerCase();

						if(json.index.length>length){
							if(json.index[length].hasOwnProperty(property)){
								return names(json.index[length][property]);
							}
						}
					}

					var names=function(result){
						var out=[];
						
						for(var i=0;i<result.length;i++){
							out.push({
								id: result[i],
								name: json.raw[result[i]]
							});
						}
						
						return out;
					}

					var debug=function(text, clear){
						$('#debug')[clear?'html':'append']('<br/>'+text);
					}
					*/
				}
			},
		};
	}());

	APP.common();

	$(function () {
		$("[data-js]").each(function () {
			var self = $(this);

			if (typeof APP.js[self.data("js")] === "function") {
				APP.js[self.data("js")](self, self.data());
			} else {
				console.log("No `" + self.data("js") + "` function in app.js");
			}
		});
	});

})(window);

String.prototype.trunc = String.prototype.trunc || function(n){
	return (this.length > n) ? this.substr(0, n-1)+'&hellip;' : this;
};
