/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(i){var a=function(){};i.extend(a.prototype,{name:"ZooItemOrder",initialize:function(e){var t=this;this.input=e,this.application=i("body").find(".zoo-application select.application"),this.application.length&&(e.find("select.element, input:checkbox").each(function(){i(this).data("_name",i(this).attr("name"))}),this.application.on("change",function(){t.refresh()}),this.refresh())},refresh:function(){var e=this.application.val();e?this.input.find(".select-message").hide().nextAll().show():this.input.find(".select-message").show().nextAll().hide(),this.input.find(".apps .app").each(function(){var t=i(this);t.find("select.element, input:checkbox").attr("name",function(){return e&&t.hasClass(e)?i(this).data("_name"):""}),e&&t.hasClass(e)?t.show():t.hide()})}}),i.fn[a.prototype.name]=function(){var e=arguments,t=e[0]?e[0]:null;return this.each(function(){var n=i(this);if(a.prototype[t]&&n.data(a.prototype.name)&&t!="initialize")n.data(a.prototype.name)[t].apply(n.data(a.prototype.name),Array.prototype.slice.call(e,1));else if(!t||i.isPlainObject(t)){var o=new a;a.prototype.initialize&&o.initialize.apply(o,i.merge([n],e)),n.data(a.prototype.name,o)}else i.error("Method "+t+" does not exist on jQuery."+a.name)})}})(jQuery);
