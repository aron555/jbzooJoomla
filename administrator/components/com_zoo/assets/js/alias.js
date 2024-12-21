/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(n){var a=function(){};n.extend(a.prototype,{name:"AliasEdit",options:{url:"index.php?option=com_zoo&controller=manager&format=raw&task=getalias",force_safe:!1,edit:!1},initialize:function(i,e){this.options=n.extend({},this.options,e);var t=this;this.input=i,this.trigger=i.find("a.trigger"),this.panel=i.find("div.panel"),this.text=this.panel.find("input:text"),this.name=i.find('input[name="name"]'),this.options.edit||this.name.on("blur.name",function(){t.name.val().length&&!t.text.val().length&&t.setAlias(t.name.val())}),this.trigger.on("click",function(s){s.preventDefault(),n(this).hide(),t.panel.addClass("active"),t.text.focus(),t.text.on("keydown",function(o){o.stopPropagation(),o.which==13&&t.setAlias(t.text.val()),o.which==27&&t.remove()}),t.input.find("input.accept").on("click",function(o){o.preventDefault(),t.setAlias(t.text.val())}),t.input.find("a.cancel").on("click",function(o){o.preventDefault(),t.remove()})})},setAlias:function(i){var e=this;i.length||(i=e.name.val()),n.getJSON(this.options.url,{name:i,force_safe:this.options.force_safe?1:0},function(t){if(!t.length)if(!e.options.force_safe)t="42";else{t="",e.panel.addClass("active"),e.text.focus(),alert("You cannot use non-latin characters for type aliases!");return}e.text.val(t),e.trigger.text(t),n(e).unbind("blur.name"),e.remove()})},remove:function(){this.trigger.show(),this.panel.removeClass("active")}}),n.fn[a.prototype.name]=function(){var i=arguments,e=i[0]?i[0]:null;return this.each(function(){var t=n(this);if(a.prototype[e]&&t.data(a.prototype.name)&&e!="initialize")t.data(a.prototype.name)[e].apply(t.data(a.prototype.name),Array.prototype.slice.call(i,1));else if(!e||n.isPlainObject(e)){var s=new a;a.prototype.initialize&&s.initialize.apply(s,n.merge([t],i)),t.data(a.prototype.name,s)}else n.error("Method "+e+" does not exist on jQuery."+a.name)})}})(jQuery);