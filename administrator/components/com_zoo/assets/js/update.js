/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(i){var o=function(){};o.prototype=i.extend(o.prototype,{name:"Update",options:{msgPerformingUpdate:"Performing Update...",msgFinished:"Update successfull...Reload page to continue working.",msgError:"Error during update. Please visit the YOOtheme support forums."},initialize:function(t,s){this.options=i.extend({},this.options,s);var e=this;this.form=t,t.find("button.update").on("click",function(){i(this).parent().remove(),e.step()})},step:function(){var t=this.form,s=this;i.ajax({url:t.attr("action"),type:"post",datatype:"json",data:t.serialize(),beforeSend:function(){s.addMessage(s.options.msgPerformingUpdate,"loading")},success:function(e){try{e=i.parseJSON(e)}catch(a){e={error:!0,message:e}}t.find("div.message-box").find("div.message").last().removeClass("loading"),e.error?s.addMessage(e.message,"error"):(s.addMessage(e.message),e.continue?s.step():s.addMessage(s.options.msgFinished,"success"))},error:function(e,a){t.find("div.message-box").find("div.message").last().removeClass("loading"),s.addMessage(a,"error")}})},addMessage:function(e,s){var e=i('<div class="message">').text(e).appendTo(this.form.find("div.message-box"));s&&e.addClass(s)}}),i.fn[o.prototype.name]=function(){var t=arguments,s=t[0]?t[0]:null;return this.each(function(){var e=i(this);if(o.prototype[s]&&e.data(o.prototype.name)&&s!="initialize")e.data(o.prototype.name)[s].apply(e.data(o.prototype.name),Array.prototype.slice.call(t,1));else if(!s||i.isPlainObject(s)){var a=new o;o.prototype.initialize&&a.initialize.apply(a,i.merge([e],t)),e.data(o.prototype.name,a)}else i.error("Method "+s+" does not exist on jQuery."+o.name)})}})(jQuery);
