/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl.html GNU/GPL */

(function(e){var o=function(){};e.extend(o.prototype,{name:"EditElements",options:{url:"index.php?option=com_zoo&controller=manager",msgNoElements:"No elements defined for this type",msgDeletelog:"Are you sure you want to delete the element?"},initialize:function(a,i){this.options=e.extend({},this.options,i);var t=this;this.list=a,this.count=0,this.noElements(),this.list.on("click","div.edit-event",function(){e(this).parent("li.element").toggleClass("hideconfig")}).on("click","div.delete-event",function(){confirm(t.options.msgDeletelog)&&e(this).parent("li.element").fadeOut(200,function(){e(this).remove(),t.noElements()})}).sortable({handle:"div.sort-event",placeholder:"element dragging",axis:"y",delay:100,tolerance:"pointer",scroll:!1,start:function(n,s){t.list.children("li.element").each(function(){e(this).data("showconfig",!e(this).hasClass("hideconfig")),e(this).addClass("hideconfig")});var l=e("div.name",s.item).height();s.helper.height(l),s.placeholder.height(l),e(this).sortable("refreshPositions")},stop:function(n,s){t.list.children("li.element").each(function(){e(this).data("showconfig")&&e(this).removeClass("hideconfig")})}}),e("#add-element ul.elements li").on("click",function(){var n=e("<li>").addClass("element loading").prependTo(t.list);e.ajax({url:t.options.url,data:{format:"raw",task:"addelement",element:e(this).attr("class"),count:t.count++},dataType:"html",success:function(s){n.removeClass("loading").html(s);var l=n.find(".hasTip").get();window.Tips?new Tips(l,{maxTitleChars:50,fixed:!1}):window.bootstrap&&window.bootstrap.Tooltip&&(l.forEach(function(r){new window.bootstrap.Tooltip(r,{html:!0})}),e("[data-bs-original-title]",n).each(function(){var r=this.dataset.bsOriginalTitle,c=r.indexOf("::");c!==-1&&(this.dataset.bsOriginalTitle="<em>"+r.substring(0,c)+"</em><p>"+r.substring(c+2)+"</p>")})),t.noElements(),t.list.trigger("element.added",n),n.slideDown(200).effect("highlight",{},1e3)}})}),e(".core-element-configuration > ul").hide(),e(".core-element-configuration > .toggler").on("click",function(n){n.preventDefault(),e(".core-element-configuration > ul").toggle()}),e(".core-element-configuration").on("click","div.edit-event",function(){e(this).parent("li.element").toggleClass("hideconfig")})},noElements:function(){this.list.find("li.no-elements").remove(),this.list.children("li.element").length==0&&e("<li>").addClass("no-elements").text(this.options.msgNoElements).appendTo(this.list)}}),e.fn[o.prototype.name]=function(){var a=arguments,i=a[0]?a[0]:null;return this.each(function(){var t=e(this);if(o.prototype[i]&&t.data(o.prototype.name)&&i!="initialize")t.data(o.prototype.name)[i].apply(t.data(o.prototype.name),Array.prototype.slice.call(a,1));else if(!i||e.isPlainObject(i)){var n=new o;o.prototype.initialize&&n.initialize.apply(n,e.merge([t],a)),t.data(o.prototype.name,n)}else e.error("Method "+i+" does not exist on jQuery."+o.name)})}})(jQuery),function(e){var o=function(){};e.extend(o.prototype,{name:"AssignElements",initialize:function(a){var i=this;e("ul.element-list").on("mousedown","div.sort-event",function(){e("li.element").not(".hideconfig").addClass("hideconfig")}),e("ul.element-list:not(.unassigned)").sortable({handle:"div.sort-event",connectWith:"ul.element-list:not(.unassigned)",placeholder:"element hideconfig dragging",forcePlaceholderSize:!0,cursorAt:{top:16},tolerance:"pointer",scroll:!1,change:function(t,n){i.emptyList()},update:function(t,n){n.item.hasClass("assigning")&&(a.find("li.assigning").each(function(){if(e(this).data("config")){var s=e(this).data("config").clone();s.find("input:radio").each(function(){e(this).attr("name",e(this).attr("name").replace(/^elements\[[\w_-]+\]/,"elements[_temp]"))}),n.item.append(s)}}),n.item.removeClass("assigning")),i.emptyList()},start:function(t,n){n.helper.addClass("ghost")},stop:function(t,n){n.item.removeClass("ghost"),i.emptyList().sanatizeList()}}),e("ul.element-list.unassigned li.element").draggable({handle:"div.sort-event",scroll:!1,zIndex:1e3,helper:function(){var t=e(this).clone();return t.find("div.config").remove(),t},connectToSortable:"ul.element-list:not(.unassigned)",drag:function(t,n){i.emptyList()},start:function(t,n){e(this).addClass("assigning"),e(this).data("config",e(this).find("div.config").remove()),n.helper.addClass("ghost")},stop:function(t,n){e(this).removeClass("assigning"),n.helper.removeClass("ghost"),e(this).append(e(this).data("config")),i.emptyList().sanatizeList()}}),this.emptyList().sanatizeList(),a.on("click","div.edit-event",function(){e(this).closest("li").toggleClass("hideconfig")}).on("click","div.delete-event",function(){e(this).closest("li").fadeOut(200,function(){e(this).remove(),i.emptyList().sanatizeList()})})},emptyList:function(){return e("ul.element-list:not(.unassigned)").each(function(){var a=e(this).hasClass("empty-list"),i=e(this).children(":not(.ui-sortable-helper)").length;(a&&i||!a&&!i)&&e(this).toggleClass("empty-list")}),this},sanatizeList:function(){var a=new RegExp(/(elements\[[a-z0-9_-]+\])|(positions\[[a-z0-9_-]+\]\[[0-9]+\])/);return e("ul.element-list:not(.unassigned)").each(function(){var i="positions["+e(this).data("position")+"]";e(this).children().each(function(t){e(this).find("[name^=positions], [name^=elements]").each(function(){e(this).attr("name","tmp"+e(this).attr("name").replace(a,i+"["+t+"]"))})})}),a=new RegExp(/^tmp/),e("ul.element-list").find("[name^=tmp]").each(function(){e(this).attr("name",e(this).attr("name").replace(a,""))}),this}}),e.fn[o.prototype.name]=function(){var a=arguments,i=a[0]?a[0]:null;return this.each(function(){var t=e(this);if(o.prototype[i]&&t.data(o.prototype.name)&&i!="initialize")t.data(o.prototype.name)[i].apply(t.data(o.prototype.name),Array.prototype.slice.call(a,1));else if(!i||e.isPlainObject(i)){var n=new o;o.prototype.initialize&&n.initialize.apply(n,e.merge([t],a)),t.data(o.prototype.name,n)}else e.error("Method "+i+" does not exist on jQuery."+o.name)})}}(jQuery),function(e){var o=function(){};e.extend(o.prototype,{name:"AssignSubmission",initialize:function(a){var i=this;e("ul.element-list").on("mousedown","div.sort-event",function(){e("li.element").not(".hideconfig").addClass("hideconfig")}).sortable({handle:"div.sort-event",connectWith:'ul.element-list:not([data-position="unassigned"])',placeholder:"element hideconfig dragging",forcePlaceholderSize:!0,cursorAt:{top:16},tolerance:"pointer",scroll:!1,start:function(t,n){n.helper.addClass("ghost")},stop:function(t,n){n.item.removeClass("ghost")},change:function(t,n){i.emptyList()},update:function(t,n){i.emptyList()}}),this.emptyList(),a.on("click","div.edit-event",function(){e(this).closest("li").toggleClass("hideconfig")}).on("click","div.delete-event",function(){e(this).closest("li").appendTo(e("ul.element-list[data-position=unassigned]")).addClass("hideconfig").effect("highlight",{},1e3),i.emptyList()}),e("#adminForm").on("validate.adminForm",function(t){e('ul.element-list:not([data-position="unassigned"])').each(function(){var n=e(this).data("position");e(this).children().each(function(s){var l=e(this).data("element");e(this).find('[name^="'+l+'"]').each(function(){e(this).attr("name","positions["+n+"]["+s+"]"+e(this).attr("name").replace(new RegExp(l),""))})})})})},emptyList:function(){return e("ul.element-list").each(function(){e(this).removeClass("empty-list"),e(this).children(":not(.ui-sortable-helper)").length==0&&e(this).addClass("empty-list")}),this}}),e.fn[o.prototype.name]=function(){var a=arguments,i=a[0]?a[0]:null;return this.each(function(){var t=e(this);if(o.prototype[i]&&t.data(o.prototype.name)&&i!="initialize")t.data(o.prototype.name)[i].apply(t.data(o.prototype.name),Array.prototype.slice.call(a,1));else if(!i||e.isPlainObject(i)){var n=new o;o.prototype.initialize&&n.initialize.apply(n,e.merge([t],a)),t.data(o.prototype.name,n)}else e.error("Method "+i+" does not exist on jQuery."+o.name)})}}(jQuery),function(e){var o=function(){};e.extend(o.prototype,{name:"EditType",initialize:function(a){e("#adminForm").on("validate.adminForm",function(i){a.find('input[name="name"]').val()==""&&(a.find("span.message-name").css("display","block"),i.preventDefault())})}}),e.fn[o.prototype.name]=function(){var a=arguments,i=a[0]?a[0]:null;return this.each(function(){var t=e(this);if(o.prototype[i]&&t.data(o.prototype.name)&&i!="initialize")t.data(o.prototype.name)[i].apply(t.data(o.prototype.name),Array.prototype.slice.call(a,1));else if(!i||e.isPlainObject(i)){var n=new o;o.prototype.initialize&&n.initialize.apply(n,e.merge([t],a)),t.data(o.prototype.name,n)}else e.error("Method "+i+" does not exist on jQuery."+o.name)})}}(jQuery);