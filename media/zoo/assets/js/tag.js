/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(t){var a=function(){};t.extend(a.prototype,{name:"BrowseTags",options:{msgSave:"Save",msgCancel:"Cancel"},initialize:function(i,n){this.options=t.extend({},this.options,n);var e=this;this.input=i,i.find("span.edit-tag a").on("click",function(){var o=t(this);e.removePanel(),o.hide();var p=t("<span>").addClass("edit-tag-panel").insertAfter(o);t('<input class="text" type="text" name="new">').val(o.text()).appendTo(p).focus().on("keydown",function(s){s.which==13&&(s.stopPropagation(),e.submit()),s.which==27&&(s.stopPropagation(),e.removePanel())}),t('<input type="hidden" name="old">').val(o.text()).appendTo(p),t('<button class="btn btn-small">').addClass("save").text(e.options.msgSave).appendTo(p).on("click",function(){e.submit()}),t("<a>").addClass("cancel").text(e.options.msgCancel).appendTo(p).on("click",function(){e.removePanel()})})},removePanel:function(){this.input.find("span.edit-tag-panel").each(function(){t(this).parent().find("a").show(),t(this).remove()})},submit:function(){this.input.find('input[name="task"]').val("update"),this.input.submit()}}),t.fn[a.prototype.name]=function(){var i=arguments,n=i[0]?i[0]:null;return this.each(function(){var e=t(this);if(a.prototype[n]&&e.data(a.prototype.name)&&n!="initialize")e.data(a.prototype.name)[n].apply(e.data(a.prototype.name),Array.prototype.slice.call(i,1));else if(!n||t.isPlainObject(n)){var o=new a;a.prototype.initialize&&o.initialize.apply(o,t.merge([e],i)),e.data(a.prototype.name,o)}else t.error("Method "+n+" does not exist on jQuery."+a.name)})}})(jQuery),function(t){var a=function(){};t.extend(a.prototype,{name:"Tag",options:{url:"",addButtonText:"Add Tag"},initialize:function(i,n){this.options=t.extend({},this.options,n);var e=this,o={},p;this.tagArea=i,this.tagInput=i.find('input[type="text"]'),this.tagInput.autosuggest(t.extend({allowDuplicates:!1,inputName:"tags[]",prefill:this.tagInput.val()!=this.tagInput.attr("placeholder")?this.tagInput.val():"",source:function(s,l){var r=s.term;if(r in o){l(o[r]);return}p=t.getJSON(e.options.url,{tag:r},function(u,d,c){o[r]=u,c===p&&l(u)})}},this.options)).on("keydown",function(s){switch(s.which){case 13:s.preventDefault(),e.tagInput.autosuggest("addItem",e.tagInput.val());break}}).placeholder(),i.on("click","div.tag-cloud a",function(){e.tagInput.autosuggest("addItem",t(this).text()),e.tagInput.trigger("blur.placeholder")})}}),t.fn[a.prototype.name]=function(){var i=arguments,n=i[0]?i[0]:null;return this.each(function(){var e=t(this);if(a.prototype[n]&&e.data(a.prototype.name)&&n!="initialize")e.data(a.prototype.name)[n].apply(e.data(a.prototype.name),Array.prototype.slice.call(i,1));else if(!n||t.isPlainObject(n)){var o=new a;a.prototype.initialize&&o.initialize.apply(o,t.merge([e],i)),e.data(a.prototype.name,o)}else t.error("Method "+n+" does not exist on jQuery."+a.name)})}}(jQuery);
