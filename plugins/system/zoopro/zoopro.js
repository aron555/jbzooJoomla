/* Copyright (C) YOOtheme GmbH, https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only */

(function(r){r.events.on("openItemPicker",function(t,e){if(e.module!=="zoo")return;t.stopPropagation();const n=t.origin.$modal({render:function(o){return o("div",{attrs:{"uk-overflow-auto":"expand: true"},on:{resize:function(i){i.target.firstElementChild.style.height=i.target.style.maxHeight}}},[o("iframe",{attrs:{src:r.url("index.php?option=com_zoo&controller=item&task=element&tmpl=component",{func:"pickZOOItem",app_id:t.origin.values.appid,type_filter:e.item_type})},on:{load:function(i){i.target.contentDocument.body.style.padding="30px"}},style:{width:"100%",height:"100%"}})])}});return window.pickZOOItem=function(o){n.resolve(o),delete window.pickZOOItem},n.show({container:!0})},5),r.events.on("resolveItemTitle",function(t,e){if(e.module==="zoo")return t.stopPropagation(),t.origin.$http.get("zoo/items",{params:{ids:[e.id]}}).then(function(n){return n.body[e.id]})},5)})(window.Vue);