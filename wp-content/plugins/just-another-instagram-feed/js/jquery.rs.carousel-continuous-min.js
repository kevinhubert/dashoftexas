/*! jquery.rs.carousel-min.js | 1.0.0 | 2013-04-27 | http://richardscarrott.github.com/jquery-ui-carousel/ */
(function(t,e){"use strict";var i=t.rs.carousel.prototype;t.widget("rs.carousel",t.rs.carousel,{options:{continuous:!1},_create:function(){this.options.continuous&&(this.options.loop=!0,this.options.whitespace=!0),i._create.apply(this,arguments)},refresh:function(){i.refresh.apply(this,arguments),this.options.continuous&&(this._addClonedItems(),this._setRunnerWidth(),this.enable(),this.goToPage(this.index,!1,e,!0),this._checkDisabled())},_addClonedItems:function(){if(this.options.disabled)return this._removeClonedItems(),e;var t=this.elements,i=this.widgetFullName+"-item-clone",s=this._getVisibleItems(0);this._removeClonedItems(),t.clonedBeginning=s.clone().add(this.elements.items.slice(s.length).first().clone()).addClass(i).appendTo(t.runner),t.clonedEnd=this.getPage(this.getNoOfPages()-1).clone().addClass(i).prependTo(t.runner)},_removeClonedItems:function(){var t=this.elements;t.clonedBeginning&&(t.clonedBeginning.remove(),t.clonedBeginning=e),t.clonedEnd&&(t.clonedEnd.remove(),t.clonedEnd=e)},_setRunnerWidth:function(){var e=this.elements,s=0;if(this.options.continuous){if(e.runner.width(""),!this.isHorizontal)return;e.runner.width(function(){return e.items.add(e.clonedBeginning).add(e.clonedEnd).each(function(){s+=t(this).outerWidth(!0)}),s})}else i._setRunnerWidth.apply(this,arguments)},_slide:function(t){var s;this.options.continuous&&("carousel:next"===t.type&&0===this.index?s=this.elements.clonedEnd.first().position()[this.isHorizontal?"left":"top"]:"carousel:prev"===t.type&&this.index===this.getNoOfPages()-1&&(s=this.elements.clonedBeginning.first().position()[this.isHorizontal?"left":"top"]),s!==e&&(this.options.translate3d?this.elements.runner.css("transform","translate3d("+(this.isHorizontal?-s+"px, 0, 0":"0, "+-s+"px, 0")+")"):this.elements.runner.css(this.isHorizontal?"left":"top",-s))),i._slide.apply(this,arguments)},_recacheItems:function(){var t=this.widgetFullName;this.elements.items=this.elements.runner.find(this.options.items).not("."+t+"-item-clone").addClass(t+"-item")},add:function(t){return this.elements.items.length?(this.elements.items.last().after(t),this.refresh(),e):(i.add.apply(this,arguments),e)},_setOption:function(t,e){i._setOption.apply(this,arguments),"continuous"===t&&(this.options.loop=!0,this.options.whitespace=!0,e||this._removeClonedItems(),this.refresh())},destroy:function(){this._removeClonedItems(),i.destroy.apply(this)}})})(jQuery);