/* Change to original sources:

    noty
        - added $.noty.closeAll() to show function
    
    Select2
        - Added line to play nice with Uploadify in IE:
            if (target.parent().attr('class') != 'uploadify') {


    Chosen
        - Added pageup/pagedown support with initial/final step check
            case 33:
                // Firefox and other non IE browsers
                if (evt.preventDefault)
                {
                    evt.preventDefault();
                    evt.stopPropagation();
                }
                // Internet Explorer
                else if (evt.keyCode)
                {
                    evt.keyCode = 0;
                    evt.returnValue = false;
                    evt.cancelBubble = true;
                }

                var steps;
                if (this.result_highlight)
                {
                    prev_sibs = this.result_highlight.prevAll("li.active-result");
                    steps = Math.min(5, prev_sibs.length);
                }
                else
                {
                    steps = 0;
                }

                for (iter = _i = 1; _i <= steps; iter = ++_i) 
                {
                    this.keyup_arrow();
                }

                break;
            case 34:
                // Firefox and other non IE browsers
                if (evt.preventDefault)
                {
                    evt.preventDefault();
                    evt.stopPropagation();
                }
                // Internet Explorer
                else if (evt.keyCode)
                {
                    evt.keyCode = 0;
                    evt.returnValue = false;
                    evt.cancelBubble = true;
                }

                var steps;
                if (this.result_highlight)
                {
                    next_sibs = this.result_highlight.nextAll("li.active-result");
                    steps = Math.min(5, next_sibs.length);
                }
                else
                {
                    steps = 0;
                }

                for (iter = _j = 1; _j <= steps; iter = ++_j) 
                {
                    this.keydown_arrow();
                }
              break;
*/

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(window, document, $) {

    var isInputSupported = 'placeholder' in document.createElement('input'),
        isTextareaSupported = 'placeholder' in document.createElement('textarea'),
        prototype = $.fn,
        valHooks = $.valHooks,
        hooks,
        placeholder;

    if (isInputSupported && isTextareaSupported) {

        placeholder = prototype.placeholder = function() {
            return this;
        };

        placeholder.input = placeholder.textarea = true;

    } else {

        placeholder = prototype.placeholder = function() {
            var $this = this;
            $this
                .filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
                .not('.placeholder')
                .bind({
                    'focus.placeholder': clearPlaceholder,
                    'blur.placeholder': setPlaceholder
                })
                .data('placeholder-enabled', true)
                .trigger('blur.placeholder');
            return $this;
        };

        placeholder.input = isInputSupported;
        placeholder.textarea = isTextareaSupported;

        hooks = {
            'get': function(element) {
                var $element = $(element);
                return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
            },
            'set': function(element, value) {
                var $element = $(element);
                if (!$element.data('placeholder-enabled')) {
                    return element.value = value;
                }
                if (value == '') {
                    element.value = value;
                    // Issue #56: Setting the placeholder causes problems if the element continues to have focus.
                    if (element != document.activeElement) {
                        // We can't use `triggerHandler` here because of dummy text/password inputs :(
                        setPlaceholder.call(element);
                    }
                } else if ($element.hasClass('placeholder')) {
                    clearPlaceholder.call(element, true, value) || (element.value = value);
                } else {
                    element.value = value;
                }
                // `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
                return $element;
            }
        };

        isInputSupported || (valHooks.input = hooks);
        isTextareaSupported || (valHooks.textarea = hooks);

        $(function() {
            // Look for forms
            $(document).delegate('form', 'submit.placeholder', function() {
                // Clear the placeholder values so they don't get submitted
                var $inputs = $('.placeholder', this).each(clearPlaceholder);
                setTimeout(function() {
                    $inputs.each(setPlaceholder);
                }, 10);
            });
        });

        // Clear placeholder values upon page reload
        $(window).bind('beforeunload.placeholder', function() {
            $('.placeholder').each(function() {
                this.value = '';
            });
        });

    }

    function args(elem) {
        // Return an object of element attributes
        var newAttrs = {},
            rinlinejQuery = /^jQuery\d+$/;
        $.each(elem.attributes, function(i, attr) {
            if (attr.specified && !rinlinejQuery.test(attr.name)) {
                newAttrs[attr.name] = attr.value;
            }
        });
        return newAttrs;
    }

    function clearPlaceholder(event, value) {
        var input = this,
            $input = $(input);
        if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
            if ($input.data('placeholder-password')) {
                $input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
                // If `clearPlaceholder` was called from `$.valHooks.input.set`
                if (event === true) {
                    return $input[0].value = value;
                }
                $input.focus();
            } else {
                input.value = '';
                $input.removeClass('placeholder');
                input == document.activeElement && input.select();
            }
        }
    }

    function setPlaceholder() {
        var $replacement,
            input = this,
            $input = $(input),
            $origInput = $input,
            id = this.id;
        if (input.value == '') {
            if (input.type == 'password') {
                if (!$input.data('placeholder-textinput')) {
                    try {
                        $replacement = $input.clone().attr({ 'type': 'text' });
                    } catch(e) {
                        $replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
                    }
                    $replacement
                        .removeAttr('name')
                        .data({
                            'placeholder-password': true,
                            'placeholder-id': id
                        })
                        .bind('focus.placeholder', clearPlaceholder);
                    $input
                        .data({
                            'placeholder-textinput': $replacement,
                            'placeholder-id': id
                        })
                        .before($replacement);
                }
                $input = $input.removeAttr('id').hide().prev().attr('id', id).show();
                // Note: `$input[0] != input` now!
            }
            $input.addClass('placeholder');
            $input[0].value = $input.attr('placeholder');
        } else {
            $input.removeClass('placeholder');
        }
    }

}(this, document, jQuery));


/**
 * noty - jQuery Notification Plugin v2.0.3
 * Contributors: https://github.com/needim/noty/graphs/contributors
 *
 * Examples and Documentation - http://needim.github.com/noty/
 *
 * Licensed under the MIT licenses:
 * http://www.opensource.org/licenses/mit-license.php
 *
 **/

if (typeof Object.create !== 'function') {
    Object.create = function (o) {
        function F() {
        }

        F.prototype = o;
        return new F();
    };
}

(function ($) {

    var NotyObject = {

        init:function (options) {

            // Mix in the passed in options with the default options
            this.options = $.extend({}, $.noty.defaults, options);

            this.options.layout = (this.options.custom) ? $.noty.layouts['inline'] : $.noty.layouts[this.options.layout];
            this.options.theme = $.noty.themes[this.options.theme];

            delete options.layout;
            delete options.theme;

            this.options = $.extend({}, this.options, this.options.layout.options);
            this.options.id = 'noty_' + (new Date().getTime() * Math.floor(Math.random() * 1000000));

            this.options = $.extend({}, this.options, options);

            // Build the noty dom initial structure
            this._build();

            // return this so we can chain/use the bridge with less code.
            return this;
        }, // end init

        _build:function () {

            // Generating noty bar
            var $bar = $('<div class="noty_bar"></div>').attr('id', this.options.id);
            $bar.append(this.options.template).find('.noty_text').html(this.options.text);

            this.$bar = (this.options.layout.parent.object !== null) ? $(this.options.layout.parent.object).css(this.options.layout.parent.css).append($bar) : $bar;

            // Set buttons if available
            if (this.options.buttons) {

                // If we have button disable closeWith & timeout options
                this.options.closeWith = [];
                this.options.timeout = false;

                var $buttons = $('<div/>').addClass('noty_buttons');

                (this.options.layout.parent.object !== null) ? this.$bar.find('.noty_bar').append($buttons) : this.$bar.append($buttons);

                var self = this;

                $.each(this.options.buttons, function (i, button) {
                    var $button = $('<button/>').addClass((button.addClass) ? button.addClass : 'gray').html(button.text)
                        .appendTo(self.$bar.find('.noty_buttons'))
                        .bind('click', function () {
                            if ($.isFunction(button.onClick)) {
                                button.onClick.call($button, self);
                            }
                        });
                });
            }

            // For easy access
            this.$message = this.$bar.find('.noty_message');
            this.$closeButton = this.$bar.find('.noty_close');
            this.$buttons = this.$bar.find('.noty_buttons');

            $.noty.store[this.options.id] = this; // store noty for api

        }, // end _build

        show:function () {

            var self = this;

            $.noty.closeAll();

            $(self.options.layout.container.selector).append(self.$bar);

            self.options.theme.style.apply(self);

            ($.type(self.options.layout.css) === 'function') ? this.options.layout.css.apply(self.$bar) : self.$bar.css(this.options.layout.css || {});

            self.$bar.addClass(self.options.layout.addClass);

            self.options.layout.container.style.apply($(self.options.layout.container.selector));

            self.options.theme.callback.onShow.apply(this);

            if ($.inArray('click', self.options.closeWith) > -1)
                self.$bar.css('cursor', 'pointer').one('click', function () {
                    self.close();
                });

            if ($.inArray('hover', self.options.closeWith) > -1)
                self.$bar.one('mouseenter', function () {
                    self.close();
                });

            if ($.inArray('button', self.options.closeWith) > -1)
                self.$closeButton.one('click', function () {
                    self.close();
                });

            if ($.inArray('button', self.options.closeWith) == -1)
                self.$closeButton.remove();

            if (self.options.callback.onShow)
                self.options.callback.onShow.apply(self);

            self.$bar.animate(
                self.options.animation.open,
                self.options.animation.speed,
                self.options.animation.easing,
                function () {
                    if (self.options.callback.afterShow) self.options.callback.afterShow.apply(self);
                    self.shown = true;
                });

            // If noty is have a timeout option
            if (self.options.timeout)
                self.$bar.delay(self.options.timeout).promise().done(function () {
                    self.close();
                });

            return this;

        }, // end show

        close:function () {

            if (this.closed) return;

            var self = this;

            if (!this.shown) { // If we are still waiting in the queue just delete from queue
                var queue = [];
                $.each($.noty.queue, function (i, n) {
                    if (n.options.id != self.options.id) {
                        queue.push(n);
                    }
                });
                $.noty.queue = queue;
                return;
            }

            self.$bar.addClass('i-am-closing-now');

            if (self.options.callback.onClose) {
                self.options.callback.onClose.apply(self);
            }

            self.$bar.clearQueue().stop().animate(
                self.options.animation.close,
                self.options.animation.speed,
                self.options.animation.easing,
                function () {
                    if (self.options.callback.afterClose) self.options.callback.afterClose.apply(self);
                })
                .promise().done(function () {

                    // Modal Cleaning
                    if (self.options.modal) {
                        $.notyRenderer.setModalCount(-1);
                        if ($.notyRenderer.getModalCount() == 0) $('.noty_modal').fadeOut('fast', function () {
                            $(this).remove();
                        });
                    }

                    // Layout Cleaning
//                  $.notyRenderer.setLayoutCountFor(self, -1);
//                  if ($.notyRenderer.getLayoutCountFor(self) == 0) $(self.options.layout.container.selector).remove();

                    // Make sure self.$bar has not been removed before attempting to remove it
                    if (typeof self.$bar !== 'undefined' && self.$bar !== null ) {
                        self.$bar.remove();
                        self.$bar = null;
                        self.closed = true;
                    }

                    delete $.noty.store[self.options.id]; // deleting noty from store

                    self.options.theme.callback.onClose.apply(self);

                    if (!self.options.dismissQueue) {
                        // Queue render
                        $.noty.ontap = true;

                        $.notyRenderer.render();
                    }

                });

        }, // end close

        setText:function (text) {
            if (!this.closed) {
                this.options.text = text;
                this.$bar.find('.noty_text').html(text);
            }
            return this;
        },

        setType:function (type) {
            if (!this.closed) {
                this.options.type = type;
                this.options.theme.style.apply(this);
                this.options.theme.callback.onShow.apply(this);
            }
            return this;
        },

        setTimeout:function (time) {
            if (!this.closed) {
                var self = this;
                this.options.timeout = time;
                self.$bar.delay(self.options.timeout).promise().done(function () {
                    self.close();
                });
            }
            return this;
        },

        closed:false,
        shown:false

    }; // end NotyObject

    $.notyRenderer = {};

    $.notyRenderer.init = function (options) {

        // Renderer creates a new noty
        var notification = Object.create(NotyObject).init(options);

        (notification.options.force) ? $.noty.queue.unshift(notification) : $.noty.queue.push(notification);

        $.notyRenderer.render();

        return ($.noty.returns == 'object') ? notification : notification.options.id;
    };

    $.notyRenderer.render = function () {

        var instance = $.noty.queue[0];

        if ($.type(instance) === 'object') {
            if (instance.options.dismissQueue) {
                $.notyRenderer.show($.noty.queue.shift());
            } else {
                if ($.noty.ontap) {
                    $.notyRenderer.show($.noty.queue.shift());
                    $.noty.ontap = false;
                }
            }
        } else {
            $.noty.ontap = true; // Queue is over
        }

    };

    $.notyRenderer.show = function (notification) {

        if (notification.options.modal) {
            $.notyRenderer.createModalFor(notification);
            $.notyRenderer.setModalCount(+1);
        }

        // Where is the container?
        if ($(notification.options.layout.container.selector).length == 0) {
            if (notification.options.custom) {
                notification.options.custom.append($(notification.options.layout.container.object).addClass('i-am-new'));
            } else {
                $('body').append($(notification.options.layout.container.object).addClass('i-am-new'));
            }
        } else {
            $(notification.options.layout.container.selector).removeClass('i-am-new');
        }

        $.notyRenderer.setLayoutCountFor(notification, +1);

        notification.show();
    };

    $.notyRenderer.createModalFor = function (notification) {
        if ($('.noty_modal').length == 0)
            $('<div/>').addClass('noty_modal').data('noty_modal_count', 0).css(notification.options.theme.modal.css).prependTo($('body')).fadeIn('fast');
    };

    $.notyRenderer.getLayoutCountFor = function (notification) {
        return $(notification.options.layout.container.selector).data('noty_layout_count') || 0;
    };

    $.notyRenderer.setLayoutCountFor = function (notification, arg) {
        return $(notification.options.layout.container.selector).data('noty_layout_count', $.notyRenderer.getLayoutCountFor(notification) + arg);
    };

    $.notyRenderer.getModalCount = function () {
        return $('.noty_modal').data('noty_modal_count') || 0;
    };

    $.notyRenderer.setModalCount = function (arg) {
        return $('.noty_modal').data('noty_modal_count', $.notyRenderer.getModalCount() + arg);
    };

    // This is for custom container
    $.fn.noty = function (options) {
        options.custom = $(this);
        return $.notyRenderer.init(options);
    };

    $.noty = {};
    $.noty.queue = [];
    $.noty.ontap = true;
    $.noty.layouts = {};
    $.noty.themes = {};
    $.noty.returns = 'object';
    $.noty.store = {};

    $.noty.get = function (id) {
        return $.noty.store.hasOwnProperty(id) ? $.noty.store[id] : false;
    };

    $.noty.close = function (id) {
        return $.noty.get(id) ? $.noty.get(id).close() : false;
    };

    $.noty.setText = function (id, text) {
        return $.noty.get(id) ? $.noty.get(id).setText(text) : false;
    };

    $.noty.setType = function (id, type) {
        return $.noty.get(id) ? $.noty.get(id).setType(type) : false;
    };

    $.noty.clearQueue = function () {
        $.noty.queue = [];
    };

    $.noty.closeAll = function () {
        $.noty.clearQueue();
        $.each($.noty.store, function (id, noty) {
            noty.close();
        });
    };

    var windowAlert = window.alert;

    $.noty.consumeAlert = function (options) {
        window.alert = function (text) {
            if (options)
                options.text = text;
            else
                options = {text:text};

            $.notyRenderer.init(options);
        };
    };

    $.noty.stopConsumeAlert = function () {
        window.alert = windowAlert;
    };

    $.noty.defaults = {
        layout:'topCenter',
        theme:'defaultTheme',
        type:'information',
        text:'',
        dismissQueue: true,
        template:'<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
        animation:{
            open:{height:'toggle'},
            close:{height:'toggle'},
            easing:'swing',
            speed:500
        },
        timeout:false,
        force:false,
        modal:false,
        closeWith:['click', 'button'],
        callback:{
            onShow:function () {
            },
            afterShow:function () {
            },
            onClose:function () {
            },
            afterClose:function () {
            }
        },
        buttons: false
    };

    $(window).resize(function () {
        $.each($.noty.layouts, function (index, layout) {
            layout.container.style.apply($(layout.container.selector));
        });
    });

})(jQuery);

// Helpers
function noty(options) {

    // This is for BC  -  Will be deleted on v2.2.0
    var using_old = 0
        , old_to_new = {
            'animateOpen':'animation.open',
            'animateClose':'animation.close',
            'easing':'animation.easing',
            'speed':'animation.speed',
            'onShow':'callback.onShow',
            'onShown':'callback.afterShow',
            'onClose':'callback.onClose',
            'onClosed':'callback.afterClose'
        };

    jQuery.each(options, function (key, value) {
        if (old_to_new[key]) {
            using_old++;
            var _new = old_to_new[key].split('.');

            if (!options[_new[0]]) options[_new[0]] = {};

            options[_new[0]][_new[1]] = (value) ? value : function () {
            };
            delete options[key];
        }
    });

    if (!options.closeWith) {
        options.closeWith = jQuery.noty.defaults.closeWith;
    }

    if (options.hasOwnProperty('closeButton')) {
        using_old++;
        if (options.closeButton) options.closeWith.push('button');
        delete options.closeButton;
    }

    if (options.hasOwnProperty('closeOnSelfClick')) {
        using_old++;
        if (options.closeOnSelfClick) options.closeWith.push('click');
        delete options.closeOnSelfClick;
    }

    if (options.hasOwnProperty('closeOnSelfOver')) {
        using_old++;
        if (options.closeOnSelfOver) options.closeWith.push('hover');
        delete options.closeOnSelfOver;
    }

    if (options.hasOwnProperty('custom')) {
        using_old++;
        if (options.custom.container != 'null') options.custom = options.custom.container;
    }

    if (options.hasOwnProperty('cssPrefix')) {
        using_old++;
        delete options.cssPrefix;
    }

    if (options.theme == 'noty_theme_default') {
        using_old++;
        options.theme = 'defaultTheme';
    }

    if (!options.hasOwnProperty('dismissQueue')) {
/*        if (options.layout == 'topLeft'
            || options.layout == 'topRight'
            || options.layout == 'bottomLeft'
            || options.layout == 'bottomRight') {
            options.dismissQueue = true;
        } else {
            options.dismissQueue = false;
        }
*/    
        options.dismissQueue = jQuery.noty.defaults.dismissQueue;
    }

    if (options.buttons) {
        jQuery.each(options.buttons, function (i, button) {
            if (button.click) {
                using_old++;
                button.onClick = button.click;
                delete button.click;
            }
            if (button.type) {
                using_old++;
                button.addClass = button.type;
                delete button.type;
            }
        });
    }

    if (using_old) {
        if (typeof console !== "undefined" && console.warn) {
            console.warn('You are using noty v2 with v1.x.x options. @deprecated until v2.2.0 - Please update your options.');
        }
    }

    // console.log(options);
    // End of the BC

    return jQuery.notyRenderer.init(options);
}


/*
*   @name                           Show Password
*   @descripton                     
*   @version                        1.3
*   @requires                       Jquery 1.5
*
*   @author                         Jan Jarfalk
*   @author-email                   jan.jarfalk@unwrongest.com
*   @author-website                 http://www.unwrongest.com
*
*   @special-thanks                 Michel Gratton
*
*   @licens                         MIT License - http://www.opensource.org/licenses/mit-license.php
*/
(function($){
     $.fn.extend({
         showPasswordOnToggle: function(c) 
         {  
            
            // Setup callback object
            var callback    = {'fn':null,'args':{}}
                callback.fn = c;
            
            // Clones passwords and turn the clones into text inputs
            var cloneElement = function( element ) 
            {
                
                var $element = $(element);
                    
                $clone = $("<input />");
                    
                // Name added for JQuery Validation compatibility
                // Element name is required to avoid script warning.
                $clone.attr({
                    'type'      :   'text',
                    'class'     :   $element.attr('class') + ' input-clones ',
                    'style'     :   $element.attr('style'),
                    'size'      :   $element.attr('size'),
                    'maxlength' :   $element.attr('maxlength'),
                    'id'        :   $element.attr('id')+'-clone',
                    'name'      :   $element.attr('name')+'-clone',
                    'tabindex'  :   $element.attr('tabindex')
//                  'placeholder' : $element.attr('placeholder')
                });
                    
                return $clone;
            
            };
            
            // Transfers values between two elements
            var update = function(a,b)
            {
                b.val(a.val());
            };
            
            // Shows a or b depending on checkbox
            var setState = function( checkbox, a, b )
            {
            
                if(checkbox.is(':checked')){
                    update(a,b);
                    b.show();
                    a.hide();
                } else {
                    update(b,a);
                    b.hide();
                    a.show();
                }
                
            };
            return this.each(function() 
            {
                var $input                  = $(this),
                    $checkbox               = $($input.data('typetoggle'));
                
                // Create clone
                var $clone = cloneElement($input);
                    $clone.insertAfter($input);
                
                // Set callback arguments
                if(callback.fn){    
                    callback.args.input     = $input;
                    callback.args.checkbox  = $checkbox;
                    callback.args.clone     = $clone;
                }
                

                
                $checkbox.bind('click', function() 
                {
                    setState( $checkbox, $input, $clone );
                });
                
                $input.keyup(function() 
                {
                    update( $input, $clone );
                 
                });
                $clone.bind('keyup', function(e){ 

                    if (!e.ctrlKey)    // 17 = ctrl
                        update( $clone, $input );
                    
                    // Added for JQuery Validation compatibility
                    // This will trigger validation if it's ON for keyup event
                    // $input.trigger('keyup');                 
                });
                
                // Added for JQuery Validation compatibility
                // This will trigger validation if it's ON for blur event
                $clone.bind('blur', function() { $input.trigger('focusout'); });
                
                setState( $checkbox, $input, $clone );
                
                if( callback.fn ){
                    callback.fn( callback.args );
                }

            });
        }
    });
})(jQuery);

 /*
  Copyright 2012 Igor Vaynberg

  Version: 3.2 Timestamp: Mon Sep 10 10:38:04 PDT 2012

  Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in
  compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

  http://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software distributed under the License is
  distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and limitations under the License.
  */
  (function ($) {
    if(typeof $.fn.each2 == "undefined"){
        $.fn.extend({
            /*
            * 4-10 times faster .each replacement
            * use it carefully, as it overrides jQuery context of element on each iteration
            */
            each2 : function (c) {
                var j = $([0]), i = -1, l = this.length;
                while (
                    ++i < l
                    && (j.context = j[0] = this[i])
                    && c.call(j[0], i, j) !== false //"this"=DOM, i=index, j=jQuery object
                );
                return this;
            }
        });
    }
 })(jQuery);

 (function ($, undefined) {
     "use strict";
     /*global document, window, jQuery, console */

     if (window.Select2 !== undefined) {
         return;
     }

     var KEY, AbstractSelect2, SingleSelect2, MultiSelect2, nextUid, sizer;

     KEY = {
         TAB: 9,
         ENTER: 13,
         ESC: 27,
         SPACE: 32,
         LEFT: 37,
         UP: 38,
         RIGHT: 39,
         DOWN: 40,
         SHIFT: 16,
         CTRL: 17,
         ALT: 18,
         PAGE_UP: 33,
         PAGE_DOWN: 34,
         HOME: 36,
         END: 35,
         BACKSPACE: 8,
         DELETE: 46,
         isArrow: function (k) {
             k = k.which ? k.which : k;
             switch (k) {
             case KEY.LEFT:
             case KEY.RIGHT:
             case KEY.UP:
             case KEY.DOWN:
                 return true;
             }
             return false;
         },
         isControl: function (e) {
             var k = e.which;
             switch (k) {
             case KEY.SHIFT:
             case KEY.CTRL:
             case KEY.ALT:
                 return true;
             }

             if (e.metaKey) return true;

             return false;
         },
         isFunctionKey: function (k) {
             k = k.which ? k.which : k;
             return k >= 112 && k <= 123;
         }
     };

     nextUid=(function() { var counter=1; return function() { return counter++; }; }());

     function indexOf(value, array) {
         var i = 0, l = array.length, v;

         if (typeof value === "undefined") {
           return -1;
         }

         if (value.constructor === String) {
             for (; i < l; i = i + 1) if (value.localeCompare(array[i]) === 0) return i;
         } else {
             for (; i < l; i = i + 1) {
                 v = array[i];
                 if (v.constructor === String) {
                     if (v.localeCompare(value) === 0) return i;
                 } else {
                     if (v === value) return i;
                 }
             }
         }
         return -1;
     }

     /**
      * Compares equality of a and b taking into account that a and b may be strings, in which case localeCompare is used
      * @param a
      * @param b
      */
     function equal(a, b) {
         if (a === b) return true;
         if (a === undefined || b === undefined) return false;
         if (a === null || b === null) return false;
         if (a.constructor === String) return a.localeCompare(b) === 0;
         if (b.constructor === String) return b.localeCompare(a) === 0;
         return false;
     }

     /**
      * Splits the string into an array of values, trimming each value. An empty array is returned for nulls or empty
      * strings
      * @param string
      * @param separator
      */
     function splitVal(string, separator) {
         var val, i, l;
         if (string === null || string.length < 1) return [];
         val = string.split(separator);
         for (i = 0, l = val.length; i < l; i = i + 1) val[i] = $.trim(val[i]);
         return val;
     }

     function getSideBorderPadding(element) {
         return element.outerWidth() - element.width();
     }

     function installKeyUpChangeEvent(element) {
         var key="keyup-change-value";
         element.bind("keydown", function () {
             if ($.data(element, key) === undefined) {
                 $.data(element, key, element.val());
             }
         });
         element.bind("keyup", function () {
             var val= $.data(element, key);
             if (val !== undefined && element.val() !== val) {
                 $.removeData(element, key);
                 element.trigger("keyup-change");
             }
         });
     }

     $(document).delegate("body", "mousemove", function (e) {
         $.data(document, "select2-lastpos", {x: e.pageX, y: e.pageY});
     });

     /**
      * filters mouse events so an event is fired only if the mouse moved.
      *
      * filters out mouse events that occur when mouse is stationary but
      * the elements under the pointer are scrolled.
      */
     function installFilteredMouseMove(element) {
        element.bind("mousemove", function (e) {
             var lastpos = $.data(document, "select2-lastpos");
             if (lastpos === undefined || lastpos.x !== e.pageX || lastpos.y !== e.pageY) {
                 $(e.target).trigger("mousemove-filtered", e);
             }
         });
     }

     /**
      * Debounces a function. Returns a function that calls the original fn function only if no invocations have been made
      * within the last quietMillis milliseconds.
      *
      * @param quietMillis number of milliseconds to wait before invoking fn
      * @param fn function to be debounced
      * @param ctx object to be used as this reference within fn
      * @return debounced version of fn
      */
     function debounce(quietMillis, fn, ctx) {
         ctx = ctx || undefined;
         var timeout;
         return function () {
             var args = arguments;
             window.clearTimeout(timeout);
             timeout = window.setTimeout(function() {
                 fn.apply(ctx, args);
             }, quietMillis);
         };
     }

     /**
      * A simple implementation of a thunk
      * @param formula function used to lazily initialize the thunk
      * @return {Function}
      */
     function thunk(formula) {
         var evaluated = false,
             value;
         return function() {
             if (evaluated === false) { value = formula(); evaluated = true; }
             return value;
         };
     };

     function installDebouncedScroll(threshold, element) {
         var notify = debounce(threshold, function (e) { element.trigger("scroll-debounced", e);});
         element.bind("scroll", function (e) {
             if (indexOf(e.target, element.get()) >= 0) notify(e);
         });
     }

     function killEvent(event) {
         event.preventDefault();
         event.stopPropagation();
     }

     function measureTextWidth(e) {
         if (!sizer){
            var style = e[0].currentStyle || window.getComputedStyle(e[0], null);
            sizer = $("<div></div>").css({
                position: "absolute",
                left: "-10000px",
                top: "-10000px",
                display: "none",
                fontSize: style.fontSize,
                fontFamily: style.fontFamily,
                fontStyle: style.fontStyle,
                fontWeight: style.fontWeight,
                letterSpacing: style.letterSpacing,
                textTransform: style.textTransform,
                whiteSpace: "nowrap"
            });
            $("body").append(sizer);
         }
         sizer.text(e.val());
         return sizer.width();
     }

     function markMatch(text, term, markup) {
         var match=text.toUpperCase().indexOf(term.toUpperCase()),
             tl=term.length;

         if (match<0) {
             markup.push(text);
             return;
         }

         markup.push(text.substring(0, match));
         markup.push("<span class='select2-match'>");
         markup.push(text.substring(match, match + tl));
         markup.push("</span>");
         markup.push(text.substring(match + tl, text.length));
     }

     /**
      * Produces an ajax-based query function
      *
      * @param options object containing configuration paramters
      * @param options.transport function that will be used to execute the ajax request. must be compatible with parameters supported by $.ajax
      * @param options.url url for the data
      * @param options.data a function(searchTerm, pageNumber, context) that should return an object containing query string parameters for the above url.
      * @param options.dataType request data type: ajax, jsonp, other datatatypes supported by jQuery's $.ajax function or the transport function if specified
      * @param options.traditional a boolean flag that should be true if you wish to use the traditional style of param serialization for the ajax request
      * @param options.quietMillis (optional) milliseconds to wait before making the ajaxRequest, helps debounce the ajax function if invoked too often
      * @param options.results a function(remoteData, pageNumber) that converts data returned form the remote request to the format expected by Select2.
      *      The expected format is an object containing the following keys:
      *      results array of objects that will be used as choices
      *      more (optional) boolean indicating whether there are more results available
      *      Example: {results:[{id:1, text:'Red'},{id:2, text:'Blue'}], more:true}
      */
     function ajax(options) {
         var timeout, // current scheduled but not yet executed request
             requestSequence = 0, // sequence used to drop out-of-order responses
             handler = null,
             quietMillis = options.quietMillis || 100;

         return function (query) {
             window.clearTimeout(timeout);
             timeout = window.setTimeout(function () {
                 requestSequence += 1; // increment the sequence
                 var requestNumber = requestSequence, // this request's sequence number
                     data = options.data, // ajax data function
                     transport = options.transport || $.ajax,
                     traditional = options.traditional || false,
                     type = options.type || 'GET'; // set type of request (GET or POST)

                 data = data.call(this, query.term, query.page, query.context);

                 if( null !== handler) { handler.abort(); }

                 handler = transport.call(null, {
                     url: options.url,
                     dataType: options.dataType,
                     data: data,
                     type: type,
                     traditional: traditional,
                     success: function (data) {
                         if (requestNumber < requestSequence) {
                             return;
                         }
                         // TODO 3.0 - replace query.page with query so users have access to term, page, etc.
                         var results = options.results(data, query.page);
                         query.callback(results);
                     }
                 });
             }, quietMillis);
         };
     }

     /**
      * Produces a query function that works with a local array
      *
      * @param options object containing configuration parameters. The options parameter can either be an array or an
      * object.
      *
      * If the array form is used it is assumed that it contains objects with 'id' and 'text' keys.
      *
      * If the object form is used ti is assumed that it contains 'data' and 'text' keys. The 'data' key should contain
      * an array of objects that will be used as choices. These objects must contain at least an 'id' key. The 'text'
      * key can either be a String in which case it is expected that each element in the 'data' array has a key with the
      * value of 'text' which will be used to match choices. Alternatively, text can be a function(item) that can extract
      * the text.
      */
     function local(options) {
         var data = options, // data elements
             dataText,
             text = function (item) { return ""+item.text; }; // function used to retrieve the text portion of a data item that is matched against the search

         if (!$.isArray(data)) {
             text = data.text;
             // if text is not a function we assume it to be a key name
             if (!$.isFunction(text)) {
               dataText = data.text; // we need to store this in a separate variable because in the next step data gets reset and data.text is no longer available
               text = function (item) { return item[dataText]; };
             }
             data = data.results;
         }

         return function (query) {
             var t = query.term, filtered = { results: [] }, process;
             if (t === "") {
                 query.callback({results: data});
                 return;
             }

             process = function(datum, collection) {
                 var group, attr;
                 datum = datum[0];
                 if (datum.children) {
                     group = {};
                     for (attr in datum) {
                         if (datum.hasOwnProperty(attr)) group[attr]=datum[attr];
                     }
                     group.children=[];
                     $(datum.children).each2(function(i, childDatum) { process(childDatum, group.children); });
                     if (group.children.length) {
                         collection.push(group);
                     }
                 } else {
                     if (query.matcher(t, text(datum))) {
                         collection.push(datum);
                     }
                 }
             };

             $(data).each2(function(i, datum) { process(datum, filtered.results); });
             query.callback(filtered);
         };
     }

     // TODO javadoc
     function tags(data) {
         // TODO even for a function we should probably return a wrapper that does the same object/string check as
         // the function for arrays. otherwise only functions that return objects are supported.
         if ($.isFunction(data)) {
             return data;
         }

         // if not a function we assume it to be an array

         return function (query) {
             var t = query.term, filtered = {results: []};
             $(data).each(function () {
                 var isObject = this.text !== undefined,
                     text = isObject ? this.text : this;
                 if (t === "" || query.matcher(t, text)) {
                     filtered.results.push(isObject ? this : {id: this, text: this});
                 }
             });
             query.callback(filtered);
         };
     }

     /**
      * Checks if the formatter function should be used.
      *
      * Throws an error if it is not a function. Returns true if it should be used,
      * false if no formatting should be performed.
      *
      * @param formatter
      */
     function checkFormatter(formatter, formatterName) {
         if ($.isFunction(formatter)) return true;
         if (!formatter) return false;
         throw new Error("formatterName must be a function or a falsy value");
     }

     function evaluate(val) {
         return $.isFunction(val) ? val() : val;
     }

     function countResults(results) {
         var count = 0;
         $.each(results, function(i, item) {
             if (item.children) {
                 count += countResults(item.children);
             } else {
                 count++;
             }
         });
         return count;
     }

     /**
      * Default tokenizer. This function uses breaks the input on substring match of any string from the
      * opts.tokenSeparators array and uses opts.createSearchChoice to create the choice object. Both of those
      * two options have to be defined in order for the tokenizer to work.
      *
      * @param input text user has typed so far or pasted into the search field
      * @param selection currently selected choices
      * @param selectCallback function(choice) callback tho add the choice to selection
      * @param opts select2's opts
      * @return undefined/null to leave the current input unchanged, or a string to change the input to the returned value
      */
     function defaultTokenizer(input, selection, selectCallback, opts) {
         var original = input, // store the original so we can compare and know if we need to tell the search to update its text
             dupe = false, // check for whether a token we extracted represents a duplicate selected choice
             token, // token
             index, // position at which the separator was found
             i, l, // looping variables
             separator; // the matched separator

         if (!opts.createSearchChoice || !opts.tokenSeparators || opts.tokenSeparators.length < 1) return undefined;

         while (true) {
             index = -1;

             for (i = 0, l = opts.tokenSeparators.length; i < l; i++) {
                 separator = opts.tokenSeparators[i];
                 index = input.indexOf(separator);
                 if (index >= 0) break;
             }

             if (index < 0) break; // did not find any token separator in the input string, bail

             token = input.substring(0, index);
             input = input.substring(index + separator.length);

             if (token.length > 0) {
                 token = opts.createSearchChoice(token, selection);
                 if (token !== undefined && token !== null && opts.id(token) !== undefined && opts.id(token) !== null) {
                     dupe = false;
                     for (i = 0, l = selection.length; i < l; i++) {
                         if (equal(opts.id(token), opts.id(selection[i]))) {
                             dupe = true; break;
                         }
                     }

                     if (!dupe) selectCallback(token);
                 }
             }
         }

         if (original.localeCompare(input) != 0) return input;
     }

     /**
      * blurs any Select2 container that has focus when an element outside them was clicked or received focus
      *
      * also takes care of clicks on label tags that point to the source element
      */
     $(document).ready(function () {
         $(document).delegate("body", "mousedown touchend", function (e) {
             var target = $(e.target).closest("div.select2-container").get(0), attr;
             if (target) {
                 $(document).find("div.select2-container-active").each(function () {
                     if (this !== target) $(this).data("select2").blur();
                 });
             } else {
                 target = $(e.target).closest("div.select2-drop").get(0);
                 $(document).find("div.select2-drop-active").each(function () {
                     if (this !== target) $(this).data("select2").blur();
                 });
             }

             target=$(e.target);
             if (target.parent().attr('class') != 'uploadify') {
                 attr = target.attr("for");
                 if ("LABEL" === e.target.tagName && attr && attr.length > 0) {
                     target = $("#"+attr);
                     target = target.data("select2");
                     if (target !== undefined) { target.focus(); e.preventDefault();}
                 }
             }
         });
     });

     /**
      * Creates a new class
      *
      * @param superClass
      * @param methods
      */
     function clazz(SuperClass, methods) {
         var constructor = function () {};
         constructor.prototype = new SuperClass;
         constructor.prototype.constructor = constructor;
         constructor.prototype.parent = SuperClass.prototype;
         constructor.prototype = $.extend(constructor.prototype, methods);
         return constructor;
     }

     AbstractSelect2 = clazz(Object, {

         // abstract
         bind: function (func) {
             var self = this;
             return function () {
                 func.apply(self, arguments);
             };
         },

         // abstract
         init: function (opts) {
             var results, search, resultsSelector = ".select2-results";

             // prepare options
             this.opts = opts = this.prepareOpts(opts);

             this.id=opts.id;

             // destroy if called on an existing component
             if (opts.element.data("select2") !== undefined &&
                 opts.element.data("select2") !== null) {
                 this.destroy();
             }

             this.enabled=true;
             this.container = this.createContainer();

             this.containerId="s2id_"+(opts.element.attr("id") || "autogen"+nextUid());
             this.containerSelector="#"+this.containerId.replace(/([;&,\.\+\*\~':"\!\^#$%@\[\]\(\)=>\|])/g, '\\$1');
             this.container.attr("id", this.containerId);

             // cache the body so future lookups are cheap
             this.body = thunk(function() { return opts.element.closest("body"); });

             if (opts.element.attr("class") !== undefined) {
                 this.container.addClass(opts.element.attr("class").replace(/validate\[[\S ]+] ?/, ''));
             }

             this.container.css(evaluate(opts.containerCss));
             this.container.addClass(evaluate(opts.containerCssClass));

             // swap container for the element
             this.opts.element
                 .data("select2", this)
                 .hide()
                 .before(this.container);
             this.container.data("select2", this);

             this.dropdown = this.container.find(".select2-drop");
             this.dropdown.addClass(evaluate(opts.dropdownCssClass));
             this.dropdown.data("select2", this);

             this.results = results = this.container.find(resultsSelector);
             this.search = search = this.container.find("input.select2-input");

             search.attr("tabIndex", this.opts.element.attr("tabIndex"));

             this.resultsPage = 0;
             this.context = null;

             // initialize the container
             this.initContainer();
             this.initContainerWidth();

             installFilteredMouseMove(this.results);
             this.dropdown.delegate(resultsSelector, "mousemove-filtered", this.bind(this.highlightUnderEvent));

             installDebouncedScroll(80, this.results);
             this.dropdown.delegate(resultsSelector, "scroll-debounced", this.bind(this.loadMoreIfNeeded));

             // if jquery.mousewheel plugin is installed we can prevent out-of-bounds scrolling of results via mousewheel
             if ($.fn.mousewheel) {
                 results.mousewheel(function (e, delta, deltaX, deltaY) {
                     var top = results.scrollTop(), height;
                     if (deltaY > 0 && top - deltaY <= 0) {
                         results.scrollTop(0);
                         killEvent(e);
                     } else if (deltaY < 0 && results.get(0).scrollHeight - results.scrollTop() + deltaY <= results.height()) {
                         results.scrollTop(results.get(0).scrollHeight - results.height());
                         killEvent(e);
                     }
                 });
             }

             installKeyUpChangeEvent(search);
             search.bind("keyup-change", this.bind(this.updateResults));
             search.bind("focus", function () { search.addClass("select2-focused"); if (search.val() === " ") search.val(""); });
             search.bind("blur", function () { search.removeClass("select2-focused");});

             this.dropdown.delegate(resultsSelector, "mouseup", this.bind(function (e) {
                 if ($(e.target).closest(".select2-result-selectable:not(.select2-disabled)").length > 0) {
                     this.highlightUnderEvent(e);
                     this.selectHighlighted(e);
                 } else {
                     this.focusSearch();
                 }
                 killEvent(e);
             }));

             // trap all mouse events from leaving the dropdown. sometimes there may be a modal that is listening
             // for mouse events outside of itself so it can close itself. since the dropdown is now outside the select2's
             // dom it will trigger the popup close, which is not what we want
             this.dropdown.bind("click mouseup mousedown", function (e) { e.stopPropagation(); });

             if ($.isFunction(this.opts.initSelection)) {
                 // initialize selection based on the current value of the source element
                 this.initSelection();

                 // if the user has provided a function that can set selection based on the value of the source element
                 // we monitor the change event on the element and trigger it, allowing for two way synchronization
                 this.monitorSource();
             }

             if (opts.element.is(":disabled") || opts.element.is("[readonly='readonly']")) this.disable();
         },

         // abstract
         destroy: function () {
             var select2 = this.opts.element.data("select2");
             if (select2 !== undefined) {
                 select2.container.remove();
                 select2.dropdown.remove();
                 select2.opts.element
                     .removeData("select2")
                     .unbind(".select2")
                     .show();
             }
         },

         // abstract
         prepareOpts: function (opts) {
             var element, select, idKey, ajaxUrl;

             element = opts.element;

             if (element.get(0).tagName.toLowerCase() === "select") {
                 this.select = select = opts.element;
             }

             if (select) {
                 // these options are not allowed when attached to a select because they are picked up off the element itself
                 $.each(["id", "multiple", "ajax", "query", "createSearchChoice", "initSelection", "data", "tags"], function () {
                     if (this in opts) {
                         throw new Error("Option '" + this + "' is not allowed for Select2 when attached to a <select> element.");
                     }
                 });
             }

             opts = $.extend({}, {
                 populateResults: function(container, results, query) {
                     var populate,  data, result, children, id=this.opts.id, self=this;

                     populate=function(results, container, depth) {

                         var i, l, result, selectable, compound, node, label, innerContainer, formatted;
                         for (i = 0, l = results.length; i < l; i = i + 1) {

                             result=results[i];
                             selectable=id(result) !== undefined;
                             compound=result.children && result.children.length > 0;

                             node=$("<li></li>");
                             node.addClass("select2-results-dept-"+depth);
                             node.addClass("select2-result");
                             node.addClass(selectable ? "select2-result-selectable" : "select2-result-unselectable");
                             if (compound) { node.addClass("select2-result-with-children"); }
                             node.addClass(self.opts.formatResultCssClass(result));

                             label=$("<div></div>");
                             label.addClass("select2-result-label");

                             formatted=opts.formatResult(result, label, query);
                             if (formatted!==undefined) {
                                 label.html(self.opts.escapeMarkup(formatted));
                             }

                             node.append(label);

                             if (compound) {

                                 innerContainer=$("<ul></ul>");
                                 innerContainer.addClass("select2-result-sub");
                                 populate(result.children, innerContainer, depth+1);
                                 node.append(innerContainer);
                             }

                             node.data("select2-data", result);
                             container.append(node);
                         }
                     };

                     populate(results, container, 0);
                 }
             }, $.fn.select2.defaults, opts);

             if (typeof(opts.id) !== "function") {
                 idKey = opts.id;
                 opts.id = function (e) { return e[idKey]; };
             }

             if (select) {
                 opts.query = this.bind(function (query) {
                     var data = { results: [], more: false },
                         term = query.term,
                         children, firstChild, process;

                     process=function(element, collection) {
                         var group;
                         if (element.is("option")) {
                             if (query.matcher(term, element.text(), element)) {
                                 collection.push({id:element.attr("value"), text:element.text(), element: element.get(), css: element.attr("class")});
                             }
                         } else if (element.is("optgroup")) {
                             group={text:element.attr("label"), children:[], element: element.get(), css: element.attr("class")};
                             element.children().each2(function(i, elm) { process(elm, group.children); });
                             if (group.children.length>0) {
                                 collection.push(group);
                             }
                         }
                     };

                     children=element.children();

                     // ignore the placeholder option if there is one
                     if (this.getPlaceholder() !== undefined && children.length > 0) {
                         firstChild = children[0];
                         if ($(firstChild).text() === "") {
                             children=children.not(firstChild);
                         }
                     }

                     children.each2(function(i, elm) { process(elm, data.results); });

                     query.callback(data);
                 });
                 // this is needed because inside val() we construct choices from options and there id is hardcoded
                 opts.id=function(e) { return e.id; };
                 opts.formatResultCssClass = function(data) { return data.css; }
             } else {
                 if (!("query" in opts)) {
                     if ("ajax" in opts) {
                         ajaxUrl = opts.element.data("ajax-url");
                         if (ajaxUrl && ajaxUrl.length > 0) {
                             opts.ajax.url = ajaxUrl;
                         }
                         opts.query = ajax(opts.ajax);
                     } else if ("data" in opts) {
                         opts.query = local(opts.data);
                     } else if ("tags" in opts) {
                         opts.query = tags(opts.tags);
                         opts.createSearchChoice = function (term) { return {id: term, text: term}; };
                         opts.initSelection = function (element, callback) {
                             var data = [];
                             $(splitVal(element.val(), opts.separator)).each(function () {
                                 var id = this, text = this, tags=opts.tags;
                                 if ($.isFunction(tags)) tags=tags();
                                 $(tags).each(function() { if (equal(this.id, id)) { text = this.text; return false; } });
                                 data.push({id: id, text: text});
                             });

                             callback(data);
                         };
                     }
                 }
             }
             if (typeof(opts.query) !== "function") {
                 throw "query function not defined for Select2 " + opts.element.attr("id");
             }

             return opts;
         },

         /**
          * Monitor the original element for changes and update select2 accordingly
          */
         // abstract
         monitorSource: function () {
             this.opts.element.bind("change.select2", this.bind(function (e) {
                 if (this.opts.element.data("select2-change-triggered") !== true) {
                     this.initSelection();
                 }
             }));
         },

         /**
          * Triggers the change event on the source element
          */
         // abstract
         triggerChange: function (details) {

             details = details || {};
             details= $.extend({}, details, { type: "change", val: this.val() });
             // prevents recursive triggering
             this.opts.element.data("select2-change-triggered", true);
             this.opts.element.trigger(details);
             this.opts.element.data("select2-change-triggered", false);

             // some validation frameworks ignore the change event and listen instead to keyup, click for selects
             // so here we trigger the click event manually
             this.opts.element.click();

             // ValidationEngine ignorea the change event and listens instead to blur
             // so here we trigger the blur event manually if so desired
             if (this.opts.blurOnChange)
                 this.opts.element.blur();
         },


         // abstract
         enable: function() {
             if (this.enabled) return;

             this.enabled=true;
             this.container.removeClass("select2-container-disabled");
         },

         // abstract
         disable: function() {
             if (!this.enabled) return;

             this.close();

             this.enabled=false;
             this.container.addClass("select2-container-disabled");
         },

         // abstract
         opened: function () {
             return this.container.hasClass("select2-dropdown-open");
         },

         // abstract
         positionDropdown: function() {
             var offset = this.container.offset(),
                 height = this.container.outerHeight(),
                 width = this.container.outerWidth(),
                 dropHeight = this.dropdown.outerHeight(),
                 viewportBottom = $(window).scrollTop() + document.documentElement.clientHeight,
                 dropTop = offset.top + height,
                 dropLeft = offset.left,
                 enoughRoomBelow = dropTop + dropHeight <= viewportBottom,
                 enoughRoomAbove = (offset.top - dropHeight) >= this.body().scrollTop(),
                 aboveNow = this.dropdown.hasClass("select2-drop-above"),
                 bodyOffset,
                 above,
                 css;

             // console.log("below/ droptop:", dropTop, "dropHeight", dropHeight, "sum", (dropTop+dropHeight)+" viewport bottom", viewportBottom, "enough?", enoughRoomBelow);
             // console.log("above/ offset.top", offset.top, "dropHeight", dropHeight, "top", (offset.top-dropHeight), "scrollTop", this.body().scrollTop(), "enough?", enoughRoomAbove);

             // fix positioning when body has an offset and is not position: static

             if (this.body().css('position') !== 'static') {
                 bodyOffset = this.body().offset();
                 dropTop -= bodyOffset.top;
                 dropLeft -= bodyOffset.left;
             }

             // always prefer the current above/below alignment, unless there is not enough room

             if (aboveNow) {
                 above = true;
                 if (!enoughRoomAbove && enoughRoomBelow) above = false;
             } else {
                 above = false;
                 if (!enoughRoomBelow && enoughRoomAbove) above = true;
             }

             if (above) {
                 dropTop = offset.top - dropHeight;
                 this.container.addClass("select2-drop-above");
                 this.dropdown.addClass("select2-drop-above");
             }
             else {
                 this.container.removeClass("select2-drop-above");
                 this.dropdown.removeClass("select2-drop-above");
             }

             css = $.extend({
                 top: dropTop,
                 left: dropLeft,
                 width: width
             }, evaluate(this.opts.dropdownCss));

             this.dropdown.css(css);
         },

         // abstract
         shouldOpen: function() {
             var event;

             if (this.opened()) return false;

             event = $.Event("open");
             this.opts.element.trigger(event);
             return !event.isDefaultPrevented();
         },

         // abstract
         clearDropdownAlignmentPreference: function() {
             // clear the classes used to figure out the preference of where the dropdown should be opened
             this.container.removeClass("select2-drop-above");
             this.dropdown.removeClass("select2-drop-above");
         },

         /**
          * Opens the dropdown
          *
          * @return {Boolean} whether or not dropdown was opened. This method will return false if, for example,
          * the dropdown is already open, or if the 'open' event listener on the element called preventDefault().
          */
         // abstract
         open: function () {

             if (!this.shouldOpen()) return false;

             window.setTimeout(this.bind(this.opening), 1);

             return true;
         },

         /**
          * Performs the opening of the dropdown
          */
         // abstract
         opening: function() {
             var cid = this.containerId, selector = this.containerSelector,
                 scroll = "scroll." + cid, resize = "resize." + cid;

             this.container.parents().each(function() {
                 $(this).bind(scroll, function() {
                     var s2 = $(selector);
                     if (s2.length == 0) {
                         $(this).unbind(scroll);
                     }
                     s2.select2("close");
                 });
             });

             $(window).bind(resize, function() {
                 var s2 = $(selector);
                 if (s2.length == 0) {
                     $(window).unbind(resize);
                 }
                 s2.select2("close");
             });

             this.clearDropdownAlignmentPreference();

             if (this.search.val() === " ") { this.search.val(""); }

             this.container.addClass("select2-dropdown-open").addClass("select2-container-active");

             this.updateResults(true);

             if(this.dropdown[0] !== this.body().children().last()[0]) {
                 this.dropdown.detach().appendTo(this.body());
             }

             this.dropdown.show();

             this.positionDropdown();
             this.dropdown.addClass("select2-drop-active");

             this.ensureHighlightVisible();

             this.focusSearch();
         },

         // abstract
         close: function () {
             if (!this.opened()) return;

             var self = this;

             this.container.parents().each(function() {
                 $(this).unbind("scroll." + self.containerId);
             });
             $(window).unbind("resize." + this.containerId);

             this.clearDropdownAlignmentPreference();

             this.dropdown.hide();
             this.container.removeClass("select2-dropdown-open").removeClass("select2-container-active");
             this.results.empty();
             this.clearSearch();

             this.opts.element.trigger($.Event("close"));
         },

         // abstract
         clearSearch: function () {

         },

         // abstract
         ensureHighlightVisible: function () {
             var results = this.results, children, index, child, hb, rb, y, more;

             index = this.highlight();

             if (index < 0) return;

             if (index == 0) {

                 // if the first element is highlighted scroll all the way to the top,
                 // that way any unselectable headers above it will also be scrolled
                 // into view

                 results.scrollTop(0);
                 return;
             }

             children = results.find(".select2-result-selectable");

             child = $(children[index]);

             hb = child.offset().top + child.outerHeight();

             // if this is the last child lets also make sure select2-more-results is visible
             if (index === children.length - 1) {
                 more = results.find("li.select2-more-results");
                 if (more.length > 0) {
                     hb = more.offset().top + more.outerHeight();
                 }
             }

             rb = results.offset().top + results.outerHeight();
             if (hb > rb) {
                 results.scrollTop(results.scrollTop() + (hb - rb));
             }
             y = child.offset().top - results.offset().top;

             // make sure the top of the element is visible
             if (y < 0) {
                 results.scrollTop(results.scrollTop() + y); // y is negative
             }
         },

         // abstract
         moveHighlight: function (delta) {
             var choices = this.results.find(".select2-result-selectable"),
                 index = this.highlight();

             while (index > -1 && index < choices.length) {
                 index += delta;
                 var choice = $(choices[index]);
                 if (choice.hasClass("select2-result-selectable") && !choice.hasClass("select2-disabled")) {
                     this.highlight(index);
                     break;
                 }
             }
         },

         // abstract
         highlight: function (index) {
             var choices = this.results.find(".select2-result-selectable").not(".select2-disabled");

             if (arguments.length === 0) {
                 return indexOf(choices.filter(".select2-highlighted")[0], choices.get());
             }

             if (index >= choices.length) index = choices.length - 1;
             if (index < 0) index = 0;

             choices.removeClass("select2-highlighted");

             $(choices[index]).addClass("select2-highlighted");
             this.ensureHighlightVisible();

         },

         // abstract
         countSelectableResults: function() {
             return this.results.find(".select2-result-selectable").not(".select2-disabled").length;
         },

         // abstract
         highlightUnderEvent: function (event) {
             var el = $(event.target).closest(".select2-result-selectable");
             if (el.length > 0 && !el.is(".select2-highlighted")) {
                var choices = this.results.find('.select2-result-selectable');
                 this.highlight(choices.index(el));
             } else if (el.length == 0) {
                 // if we are over an unselectable item remove al highlights
                 this.results.find(".select2-highlighted").removeClass("select2-highlighted");
             }
         },

         // abstract
         loadMoreIfNeeded: function () {
             var results = this.results,
                 more = results.find("li.select2-more-results"),
                 below, // pixels the element is below the scroll fold, below==0 is when the element is starting to be visible
                 offset = -1, // index of first element without data
                 page = this.resultsPage + 1,
                 self=this,
                 term=this.search.val(),
                 context=this.context;

             if (more.length === 0) return;
             below = more.offset().top - results.offset().top - results.height();

             if (below <= 0) {
                 more.addClass("select2-active");
                 this.opts.query({
                         term: term,
                         page: page,
                         context: context,
                         matcher: this.opts.matcher,
                         callback: this.bind(function (data) {

                     // ignore a response if the select2 has been closed before it was received
                     if (!self.opened()) return;


                     self.opts.populateResults.call(this, results, data.results, {term: term, page: page, context:context});

                     if (data.more===true) {
                         more.detach().appendTo(results).text(self.opts.formatLoadMore(page+1));
                         window.setTimeout(function() { self.loadMoreIfNeeded(); }, 10);
                     } else {
                         more.remove();
                     }
                     self.positionDropdown();
                     self.resultsPage = page;
                 })});
             }
         },

         /**
          * Default tokenizer function which does nothing
          */
         tokenize: function() {

         },

         /**
          * @param initial whether or not this is the call to this method right after the dropdown has been opened
          */
         // abstract
         updateResults: function (initial) {
             var search = this.search, results = this.results, opts = this.opts, data, self=this, input;

             // if the search is currently hidden we do not alter the results
             if (initial !== true && (this.showSearchInput === false || !this.opened())) {
                 return;
             }

             search.addClass("select2-active");

             function postRender() {
                 results.scrollTop(0);
                 search.removeClass("select2-active");
                 self.positionDropdown();
             }

             function render(html) {
                 results.html(self.opts.escapeMarkup(html));
                 postRender();
             }

             if (opts.maximumSelectionSize >=1) {
                 data = this.data();
                 if ($.isArray(data) && data.length >= opts.maximumSelectionSize && checkFormatter(opts.formatSelectionTooBig, "formatSelectionTooBig")) {
                    render("<li class='select2-selection-limit'>" + opts.formatSelectionTooBig(opts.maximumSelectionSize) + "</li>");
                    return;
                 }
             }

             if (search.val().length < opts.minimumInputLength && checkFormatter(opts.formatInputTooShort, "formatInputTooShort")) {
                 render("<li class='select2-no-results'>" + opts.formatInputTooShort(search.val(), opts.minimumInputLength) + "</li>");
                 return;
             }
             else {
                 render("<li class='select2-searching'>" + opts.formatSearching() + "</li>");
             }

             // give the tokenizer a chance to pre-process the input
             input = this.tokenize();
             if (input != undefined && input != null) {
                 search.val(input);
             }

             this.resultsPage = 1;
             opts.query({
                     term: search.val(),
                     page: this.resultsPage,
                     context: null,
                     matcher: opts.matcher,
                     callback: this.bind(function (data) {
                 var def; // default choice

                 // ignore a response if the select2 has been closed before it was received
                 if (!this.opened()) return;

                 // save context, if any
                 this.context = (data.context===undefined) ? null : data.context;

                 // create a default choice and prepend it to the list
                 if (this.opts.createSearchChoice && search.val() !== "") {
                     def = this.opts.createSearchChoice.call(null, search.val(), data.results);
                     if (def !== undefined && def !== null && self.id(def) !== undefined && self.id(def) !== null) {
                         if ($(data.results).filter(
                             function () {
                                 return equal(self.id(this), self.id(def));
                             }).length === 0) {
                             data.results.unshift(def);
                         }
                     }
                 }

                 if (data.results.length === 0 && checkFormatter(opts.formatNoMatches, "formatNoMatches")) {
                     render("<li class='select2-no-results'>" + opts.formatNoMatches(search.val()) + "</li>");
                     return;
                 }

                 results.empty();
                 self.opts.populateResults.call(this, results, data.results, {term: search.val(), page: this.resultsPage, context:null});

                 if (data.more === true && checkFormatter(opts.formatLoadMore, "formatLoadMore")) {
                     results.append("<li class='select2-more-results'>" + self.opts.escapeMarkup(opts.formatLoadMore(this.resultsPage)) + "</li>");
                     window.setTimeout(function() { self.loadMoreIfNeeded(); }, 10);
                 }

                 this.postprocessResults(data, initial);

                 postRender();
             })});
         },

         // abstract
         cancel: function () {
             this.close();
         },

         // abstract
         blur: function () {
             this.close();
             this.container.removeClass("select2-container-active");
             this.dropdown.removeClass("select2-drop-active");
             // synonymous to .is(':focus'), which is available in jquery >= 1.6
             if (this.search[0] === document.activeElement) { this.search.blur(); }
             this.clearSearch();
             this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus");
         },

         // abstract
         focusSearch: function () {
             // need to do it here as well as in timeout so it works in IE
             this.search.show();
             this.search.focus();

             /* we do this in a timeout so that current event processing can complete before this code is executed.
              this makes sure the search field is focussed even if the current event would blur it */
             window.setTimeout(this.bind(function () {
                 // reset the value so IE places the cursor at the end of the input box
                 this.search.show();
                 this.search.focus();
                 this.search.val(this.search.val());
             }), 10);
         },

         // abstract
         selectHighlighted: function () {
             var index=this.highlight(),
                 highlighted=this.results.find(".select2-highlighted").not(".select2-disabled"),
                 data = highlighted.closest('.select2-result-selectable').data("select2-data");
             if (data) {
                 highlighted.addClass("select2-disabled");
                 this.highlight(index);
                 this.onSelect(data);
             }
         },

         // abstract
         getPlaceholder: function () {
             return this.opts.element.attr("placeholder") ||
                 this.opts.element.attr("data-placeholder") || // jquery 1.4 compat
                 this.opts.element.data("placeholder") ||
                 this.opts.placeholder;
         },

         /**
          * Get the desired width for the container element.  This is
          * derived first from option `width` passed to select2, then
          * the inline 'style' on the original element, and finally
          * falls back to the jQuery calculated element width.
          */
         // abstract
         initContainerWidth: function () {
             function resolveContainerWidth() {
                 var style, attrs, matches, i, l;

                 if (this.opts.width === "off") {
                     return null;
                 } else if (this.opts.width === "element"){
                     return this.opts.element.outerWidth() === 0 ? 'auto' : this.opts.element.outerWidth() + 'px';
                 } else if (this.opts.width === "copy" || this.opts.width === "resolve") {
                     // check if there is inline style on the element that contains width
                     style = this.opts.element.attr('style');
                     if (style !== undefined) {
                         attrs = style.split(';');
                         for (i = 0, l = attrs.length; i < l; i = i + 1) {
                             matches = attrs[i].replace(/\s/g, '')
                                 .match(/width:(([-+]?([0-9]*\.)?[0-9]+)(px|em|ex|%|in|cm|mm|pt|pc))/);
                             if (matches !== null && matches.length >= 1)
                                 return matches[1];
                         }
                     }

                     if (this.opts.width === "resolve") {
                         // next check if css('width') can resolve a width that is percent based, this is sometimes possible
                         // when attached to input type=hidden or elements hidden via css
                         style = this.opts.element.css('width');
                         if (style.indexOf("%") > 0) return style;

                         // finally, fallback on the calculated width of the element
                         return (this.opts.element.outerWidth() === 0 ? 'auto' : this.opts.element.outerWidth() + 'px');
                     }

                     return null;
                 } else if ($.isFunction(this.opts.width)) {
                     return this.opts.width();
                 } else {
                     return this.opts.width;
                }
             };

             var width = resolveContainerWidth.call(this);
             if (width !== null) {
                 this.container.attr("style", "width: "+width);
             }
         }
     });

     SingleSelect2 = clazz(AbstractSelect2, {

         // single

        createContainer: function () {
             var container = $("<div></div>", {
                 "class": "select2-container"
             }).html([
                 "    <a href='#' onclick='return false;' class='select2-choice'>",
                 "   <span></span><abbr class='select2-search-choice-close' style='display:none;'></abbr>",
                 "   <div><b></b></div>" ,
                 "</a>",
                 "    <div class='select2-drop select2-offscreen'>" ,
                 "   <div class='select2-search'>" ,
                 "       <input type='text' autocomplete='off' class='select2-input'/>" ,
                 "   </div>" ,
                 "   <ul class='select2-results'>" ,
                 "   </ul>" ,
                 "</div>"].join(""));
             return container;
         },

         // single
         opening: function () {
             this.search.show();
             this.parent.opening.apply(this, arguments);
             this.dropdown.removeClass("select2-offscreen");
         },

         // single
         close: function () {
             if (!this.opened()) return;
             this.parent.close.apply(this, arguments);
             this.dropdown.removeAttr("style").addClass("select2-offscreen").insertAfter(this.selection).show();
         },

         // single
         focus: function () {
             this.close();
             this.selection.focus();
         },

         // single
         isFocused: function () {
             return this.selection[0] === document.activeElement;
         },

         // single
         cancel: function () {
             this.parent.cancel.apply(this, arguments);
             this.selection.focus();
         },

         // single
         initContainer: function () {

             var selection,
                 container = this.container,
                 dropdown = this.dropdown,
                 clickingInside = false;

             this.selection = selection = container.find(".select2-choice");

             this.search.bind("keydown", this.bind(function (e) {
                 if (!this.enabled) return;

                 if (e.which === KEY.PAGE_UP || e.which === KEY.PAGE_DOWN) {
                     // prevent the page from scrolling
                     killEvent(e);
                     return;
                 }

                 if (this.opened()) {
                     switch (e.which) {
                         case KEY.UP:
                         case KEY.DOWN:
                             this.moveHighlight((e.which === KEY.UP) ? -1 : 1);
                             killEvent(e);
                             return;
                         case KEY.TAB:
                         case KEY.ENTER:
                             this.selectHighlighted();
                             killEvent(e);
                             return;
                         case KEY.ESC:
                             this.cancel(e);
                             killEvent(e);
                             return;
                     }
                 } else {

                     if (e.which === KEY.TAB || KEY.isControl(e) || KEY.isFunctionKey(e) || e.which === KEY.ESC) {
                         return;
                     }

                     if (this.opts.openOnEnter === false && e.which === KEY.ENTER) {
                         return;
                     }

                     this.open();

                     if (e.which === KEY.ENTER) {
                         // do not propagate the event otherwise we open, and propagate enter which closes
                         return;
                     }
                 }
             }));

             this.search.bind("focus", this.bind(function() {
                 this.selection.attr("tabIndex", "-1");
             }));
             this.search.bind("blur", this.bind(function() {
                 if (!this.opened()) this.container.removeClass("select2-container-active");
                 window.setTimeout(this.bind(function() { this.selection.attr("tabIndex", this.opts.element.attr("tabIndex")); }), 10);
             }));

             selection.bind("mousedown", this.bind(function (e) {
                 clickingInside = true;

                 if (this.opened()) {
                     this.close();
                     this.selection.focus();
                 } else if (this.enabled) {
                     this.open();
                 }

                 clickingInside = false;
             }));

             dropdown.bind("mousedown", this.bind(function() { this.search.focus(); }));

             selection.bind("focus", this.bind(function() {
                 this.container.addClass("select2-container-active");
                 // hide the search so the tab key does not focus on it
                 this.search.attr("tabIndex", "-1");
             }));

             selection.bind("blur", this.bind(function() {
                 if (!this.opened()) {
                     this.container.removeClass("select2-container-active");
                 }
                 window.setTimeout(this.bind(function() { this.search.attr("tabIndex", this.opts.element.attr("tabIndex")); }), 10);
             }));

             selection.bind("keydown", this.bind(function(e) {
                 if (!this.enabled) return;

                 if (e.which === KEY.PAGE_UP || e.which === KEY.PAGE_DOWN) {
                     // prevent the page from scrolling
                     killEvent(e);
                     return;
                 }

                 if (e.which === KEY.TAB || KEY.isControl(e) || KEY.isFunctionKey(e)
                  || e.which === KEY.ESC) {
                     return;
                 }

                 if (this.opts.openOnEnter === false && e.which === KEY.ENTER) {
                     return;
                 }

                 if (e.which == KEY.DELETE) {
                     if (this.opts.allowClear) {
                         this.clear();
                     }
                     return;
                 }

                 this.open();

                 if (e.which === KEY.ENTER) {
                     // do not propagate the event otherwise we open, and propagate enter which closes
                     killEvent(e);
                     return;
                 }

                 // do not set the search input value for non-alpha-numeric keys
                 // otherwise pressing down results in a '(' being set in the search field
                 if (e.which < 48 ) { // '0' == 48
                     killEvent(e);
                     return;
                 }

                 var keyWritten = String.fromCharCode(e.which).toLowerCase();

                 if (e.shiftKey) {
                     keyWritten = keyWritten.toUpperCase();
                 }

                 // focus the field before calling val so the cursor ends up after the value instead of before
                 this.search.focus();
                 this.search.val(keyWritten);

                 // prevent event propagation so it doesnt replay on the now focussed search field and result in double key entry
                 killEvent(e);
             }));

             selection.delegate("abbr", "mousedown", this.bind(function (e) {
                 if (!this.enabled) return;
                 this.clear();
                 killEvent(e);
                 this.close();
                 this.triggerChange();
                 this.selection.focus();
             }));

             this.setPlaceholder();

             this.search.bind("focus", this.bind(function() {
                 this.container.addClass("select2-container-active");
             }));
         },

         // single
         clear: function() {
             this.opts.element.val("");
             this.selection.find("span").empty();
             this.selection.removeData("select2-data");
             this.setPlaceholder();
         },

         /**
          * Sets selection based on source element's value
          */
         // single
         initSelection: function () {
             var selected;
             if (this.opts.element.val() === "") {
                 this.close();
                 this.setPlaceholder();
             } else {
                 var self = this;
                 this.opts.initSelection.call(null, this.opts.element, function(selected){
                     if (selected !== undefined && selected !== null) {
                         self.updateSelection(selected);
                         self.close();
                         self.setPlaceholder();
                     }
                 });
             }
         },

         // single
         prepareOpts: function () {
             var opts = this.parent.prepareOpts.apply(this, arguments);

             if (opts.element.get(0).tagName.toLowerCase() === "select") {
                 // install the selection initializer
                 opts.initSelection = function (element, callback) {
                     var selected = element.find(":selected");
                     // a single select box always has a value, no need to null check 'selected'
                     if ($.isFunction(callback))
                         callback({id: selected.attr("value"), text: selected.text()});
                 };
             }

             return opts;
         },

         // single
         setPlaceholder: function () {
             var placeholder = this.getPlaceholder();

             if (this.opts.element.val() === "" && placeholder !== undefined) {

                 // check for a first blank option if attached to a select
                 if (this.select && this.select.find("option:first").text() !== "") return;

                 this.selection.find("span").html(this.opts.escapeMarkup(placeholder));

                 this.selection.addClass("select2-default");

                 this.selection.find("abbr").hide();
             }
         },

         // single
         postprocessResults: function (data, initial) {
             var selected = 0, self = this, showSearchInput = true;

             // find the selected element in the result list

             this.results.find(".select2-result-selectable").each2(function (i, elm) {
                 if (equal(self.id(elm.data("select2-data")), self.opts.element.val())) {
                     selected = i;
                     return false;
                 }
             });

             // and highlight it

             this.highlight(selected);

             // hide the search box if this is the first we got the results and there are a few of them

             if (initial === true) {
                 showSearchInput = this.showSearchInput = countResults(data.results) >= this.opts.minimumResultsForSearch;
                 this.dropdown.find(".select2-search")[showSearchInput ? "removeClass" : "addClass"]("select2-search-hidden");

                 //add "select2-with-searchbox" to the container if search box is shown
                 $(this.dropdown, this.container)[showSearchInput ? "addClass" : "removeClass"]("select2-with-searchbox");
             }

         },

         // single
         onSelect: function (data) {
             var old = this.opts.element.val();

             this.opts.element.val(this.id(data));
             this.updateSelection(data);
             this.close();
             this.selection.focus();

             if (!equal(old, this.id(data))) { this.triggerChange(); }
         },

         // single
         updateSelection: function (data) {

             var container=this.selection.find("span"), formatted;

             this.selection.data("select2-data", data);

             container.empty();
             formatted=this.opts.formatSelection(data, container);
             if (formatted !== undefined) {
                 container.append(this.opts.escapeMarkup(formatted));
             }

             this.selection.removeClass("select2-default");

             if (this.opts.allowClear && this.getPlaceholder() !== undefined) {
                 this.selection.find("abbr").show();
             }
         },

         // single
         val: function () {
             var val, data = null, self = this;

             if (arguments.length === 0) {
                 return this.opts.element.val();
             }

             val = arguments[0];

             if (this.select) {
                 this.select
                     .val(val)
                     .find(":selected").each2(function (i, elm) {
                         data = {id: elm.attr("value"), text: elm.text()};
                         return false;
                     });
                 this.updateSelection(data);
                 this.setPlaceholder();
             } else {
                 if (this.opts.initSelection === undefined) {
                     throw new Error("cannot call val() if initSelection() is not defined");
                 }
                 // val is an id. !val is true for [undefined,null,'']
                 if (!val) {
                     this.clear();
                     return;
                 }
                 this.opts.element.val(val);
                 this.opts.initSelection(this.opts.element, function(data){
                     self.opts.element.val(!data ? "" : self.id(data));
                     self.updateSelection(data);
                     self.setPlaceholder();
                 });
             }
         },

         // single
         clearSearch: function () {
             this.search.val("");
         },

         // single
         data: function(value) {
             var data;

             if (arguments.length === 0) {
                 data = this.selection.data("select2-data");
                 if (data == undefined) data = null;
                 return data;
             } else {
                 if (!value || value === "") {
                     this.clear();
                 } else {
                     this.opts.element.val(!value ? "" : this.id(value));
                     this.updateSelection(value);
                 }
             }
         }
     });

     MultiSelect2 = clazz(AbstractSelect2, {

         // multi
         createContainer: function () {
             var container = $("<div></div>", {
                 "class": "select2-container select2-container-multi"
             }).html([
                 "    <ul class='select2-choices'>",
                 //"<li class='select2-search-choice'><span>California</span><a href="javascript:void(0)" class="select2-search-choice-close"></a></li>" ,
                 "  <li class='select2-search-field'>" ,
                 "    <input type='text' autocomplete='off' class='select2-input'>" ,
                 "  </li>" ,
                 "</ul>" ,
                 "<div class='select2-drop select2-drop-multi' style='display:none;'>" ,
                 "   <ul class='select2-results'>" ,
                 "   </ul>" ,
                 "</div>"].join(""));
            return container;
         },

         // multi
         prepareOpts: function () {
             var opts = this.parent.prepareOpts.apply(this, arguments);

             // TODO validate placeholder is a string if specified

             if (opts.element.get(0).tagName.toLowerCase() === "select") {
                 // install sthe selection initializer
                 opts.initSelection = function (element,callback) {

                     var data = [];
                     element.find(":selected").each2(function (i, elm) {
                         data.push({id: elm.attr("value"), text: elm.text()});
                     });

                     if ($.isFunction(callback))
                         callback(data);
                 };
             }

             return opts;
         },

         // multi
         initContainer: function () {

             var selector = ".select2-choices", selection;

             this.searchContainer = this.container.find(".select2-search-field");
             this.selection = selection = this.container.find(selector);

             this.search.bind("keydown", this.bind(function (e) {
                 if (!this.enabled) return;

                 if (e.which === KEY.BACKSPACE && this.search.val() === "") {
                     this.close();

                     var choices,
                         selected = selection.find(".select2-search-choice-focus");
                     if (selected.length > 0) {
                         this.unselect(selected.first());
                         this.search.width(10);
                         killEvent(e);
                         return;
                     }

                     choices = selection.find(".select2-search-choice");
                     if (choices.length > 0) {
                         choices.last().addClass("select2-search-choice-focus");
                     }
                 } else {
                     selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus");
                 }

                 if (this.opened()) {
                     switch (e.which) {
                     case KEY.UP:
                     case KEY.DOWN:
                         this.moveHighlight((e.which === KEY.UP) ? -1 : 1);
                         killEvent(e);
                         return;
                     case KEY.ENTER:
                     case KEY.TAB:
                         this.selectHighlighted();
                         killEvent(e);
                         return;
                     case KEY.ESC:
                         this.cancel(e);
                         killEvent(e);
                         return;
                     }
                 }

                 if (e.which === KEY.TAB || KEY.isControl(e) || KEY.isFunctionKey(e)
                  || e.which === KEY.BACKSPACE || e.which === KEY.ESC) {
                     return;
                 }

                 if (this.opts.openOnEnter === false && e.which === KEY.ENTER) {
                     return;
                 }

                 this.open();

                 if (e.which === KEY.PAGE_UP || e.which === KEY.PAGE_DOWN) {
                     // prevent the page from scrolling
                     killEvent(e);
                 }
             }));

             this.search.bind("keyup", this.bind(this.resizeSearch));

             this.search.bind("blur", this.bind(function(e) {
                 this.container.removeClass("select2-container-active");
                 this.search.removeClass("select2-focused");
                 this.clearSearch();
                 e.stopImmediatePropagation();
             }));

             this.container.delegate(selector, "mousedown", this.bind(function (e) {
                 if (!this.enabled) return;
                 if ($(e.target).closest(".select2-search-choice").length > 0) {
                     // clicked inside a select2 search choice, do not open
                     return;
                 }
                 this.clearPlaceholder();
                 this.open();
                 this.focusSearch();
                 e.preventDefault();
             }));

             this.container.delegate(selector, "focus", this.bind(function () {
                 if (!this.enabled) return;
                 this.container.addClass("select2-container-active");
                 this.dropdown.addClass("select2-drop-active");
                 this.clearPlaceholder();
             }));

             // set the placeholder if necessary
             this.clearSearch();
         },

         // multi
         enable: function() {
             if (this.enabled) return;

             this.parent.enable.apply(this, arguments);

             this.search.removeAttr("disabled");
         },

         // multi
         disable: function() {
             if (!this.enabled) return;

             this.parent.disable.apply(this, arguments);

             this.search.attr("disabled", true);
         },

         // multi
         initSelection: function () {
             var data;
             if (this.opts.element.val() === "") {
                 this.updateSelection([]);
                 this.close();
                 // set the placeholder if necessary
                 this.clearSearch();
             }
             if (this.select || this.opts.element.val() !== "") {
                 var self = this;
                 this.opts.initSelection.call(null, this.opts.element, function(data){
                     if (data !== undefined && data !== null) {
                         self.updateSelection(data);
                         self.close();
                         // set the placeholder if necessary
                         self.clearSearch();
                     }
                 });
             }
         },

         // multi
         clearSearch: function () {
             var placeholder = this.getPlaceholder();

             if (placeholder !== undefined  && this.getVal().length === 0 && this.search.hasClass("select2-focused") === false) {
                 this.search.val(placeholder).addClass("select2-default");
                 // stretch the search box to full width of the container so as much of the placeholder is visible as possible
                 this.resizeSearch();
             } else {
                 // we set this to " " instead of "" and later clear it on focus() because there is a firefox bug
                 // that does not properly render the caret when the field starts out blank
                 this.search.val(" ").width(10);
             }
         },

         // multi
         clearPlaceholder: function () {
             if (this.search.hasClass("select2-default")) {
                 this.search.val("").removeClass("select2-default");
             } else {
                 // work around for the space character we set to avoid firefox caret bug
                 if (this.search.val() === " ") this.search.val("");
             }
         },

         // multi
         opening: function () {
             this.parent.opening.apply(this, arguments);

             this.clearPlaceholder();
            this.resizeSearch();
             this.focusSearch();
         },

         // multi
         close: function () {
             if (!this.opened()) return;
             this.parent.close.apply(this, arguments);
         },

         // multi
         focus: function () {
             this.close();
             this.search.focus();
         },

         // multi
         isFocused: function () {
             return this.search.hasClass("select2-focused");
         },

         // multi
         updateSelection: function (data) {
             var ids = [], filtered = [], self = this;

             // filter out duplicates
             $(data).each(function () {
                 if (indexOf(self.id(this), ids) < 0) {
                     ids.push(self.id(this));
                     filtered.push(this);
                 }
             });
             data = filtered;

             this.selection.find(".select2-search-choice").remove();
             $(data).each(function () {
                 self.addSelectedChoice(this);
             });
             self.postprocessResults();
         },

         tokenize: function() {
             var input = this.search.val();
             input = this.opts.tokenizer(input, this.data(), this.bind(this.onSelect), this.opts);
             if (input != null && input != undefined) {
                 this.search.val(input);
                 if (input.length > 0) {
                     this.open();
                 }
             }

         },

         // multi
         onSelect: function (data) {
             this.addSelectedChoice(data);
             if (this.select) { this.postprocessResults(); }

             if (this.opts.closeOnSelect) {
                 this.close();
                 this.search.width(10);
             } else {
                 if (this.countSelectableResults()>0) {
                     this.search.width(10);
                     this.resizeSearch();
                     this.positionDropdown();
                 } else {
                     // if nothing left to select close
                     this.close();
                 }
             }

             // since its not possible to select an element that has already been
             // added we do not need to check if this is a new element before firing change
             this.triggerChange({ added: data });

             this.focusSearch();
         },

         // multi
         cancel: function () {
             this.close();
             this.focusSearch();
         },

         // multi
         addSelectedChoice: function (data) {
             var choice=$(
                     "<li class='select2-search-choice'>" +
                     "    <div></div>" +
                     "    <a href='#' onclick='return false;' class='select2-search-choice-close' tabindex='-1'></a>" +
                     "</li>"),
                 id = this.id(data),
                 val = this.getVal(),
                 formatted;

             formatted=this.opts.formatSelection(data, choice);
             choice.find("div").replaceWith("<div>"+this.opts.escapeMarkup(formatted)+"</div>");
             choice.find(".select2-search-choice-close")
                 .bind("mousedown", killEvent)
                 .bind("click dblclick", this.bind(function (e) {
                 if (!this.enabled) return;

                 $(e.target).closest(".select2-search-choice").fadeOut('fast', this.bind(function(){
                     this.unselect($(e.target));
                     this.selection.find(".select2-search-choice-focus").removeClass("select2-search-choice-focus");
                     this.close();
                     this.focusSearch();
                 })).dequeue();
                 killEvent(e);
             })).bind("focus", this.bind(function () {
                 if (!this.enabled) return;
                 this.container.addClass("select2-container-active");
                 this.dropdown.addClass("select2-drop-active");
             }));

             choice.data("select2-data", data);
             choice.insertBefore(this.searchContainer);

             val.push(id);
             this.setVal(val);
         },

         // multi
         unselect: function (selected) {
             var val = this.getVal(),
                 data,
                 index;

             selected = selected.closest(".select2-search-choice");

             if (selected.length === 0) {
                 throw "Invalid argument: " + selected + ". Must be .select2-search-choice";
             }

             data = selected.data("select2-data");

             index = indexOf(this.id(data), val);

             if (index >= 0) {
                 val.splice(index, 1);
                 this.setVal(val);
                 if (this.select) this.postprocessResults();
             }
             selected.remove();
             this.triggerChange({ removed: data });
         },

         // multi
         postprocessResults: function () {
             var val = this.getVal(),
                 choices = this.results.find(".select2-result-selectable"),
                 compound = this.results.find(".select2-result-with-children"),
                 self = this;

             choices.each2(function (i, choice) {
                 var id = self.id(choice.data("select2-data"));
                 if (indexOf(id, val) >= 0) {
                     choice.addClass("select2-disabled").removeClass("select2-result-selectable");
                 } else {
                     choice.removeClass("select2-disabled").addClass("select2-result-selectable");
                 }
             });

             compound.each2(function(i, e) {
                 if (e.find(".select2-result-selectable").length==0) {
                     e.addClass("select2-disabled");
                 } else {
                     e.removeClass("select2-disabled");
                 }
             });

             choices.each2(function (i, choice) {
                 if (!choice.hasClass("select2-disabled") && choice.hasClass("select2-result-selectable")) {
                     self.highlight(0);
                     return false;
                 }
             });

         },

         // multi
         resizeSearch: function () {

             var minimumWidth, left, maxWidth, containerLeft, searchWidth,
                sideBorderPadding = getSideBorderPadding(this.search);

             minimumWidth = measureTextWidth(this.search) + 10;

             left = this.search.offset().left;

             maxWidth = this.selection.width();
             containerLeft = this.selection.offset().left;

             searchWidth = maxWidth - (left - containerLeft) - sideBorderPadding;
             if (searchWidth < minimumWidth) {
                 searchWidth = maxWidth - sideBorderPadding;
             }

             if (searchWidth < 40) {
                 searchWidth = maxWidth - sideBorderPadding;
             }
             this.search.width(searchWidth);
         },

         // multi
         getVal: function () {
             var val;
             if (this.select) {
                 val = this.select.val();
                 return val === null ? [] : val;
             } else {
                 val = this.opts.element.val();
                 return splitVal(val, this.opts.separator);
             }
         },

         // multi
         setVal: function (val) {
             var unique;
             if (this.select) {
                 this.select.val(val);
             } else {
                 unique = [];
                 // filter out duplicates
                 $(val).each(function () {
                     if (indexOf(this, unique) < 0) unique.push(this);
                 });
                 this.opts.element.val(unique.length === 0 ? "" : unique.join(this.opts.separator));
             }
         },

         // multi
         val: function () {
             var val, data = [], self=this;

             if (arguments.length === 0) {
                 return this.getVal();
             }

             val = arguments[0];

             if (!val) {
                 this.opts.element.val("");
                 this.updateSelection([]);
                 this.clearSearch();
                 return;
             }

             // val is a list of ids
             this.setVal(val);

             if (this.select) {
                 this.select.find(":selected").each(function () {
                     data.push({id: $(this).attr("value"), text: $(this).text()});
                 });
                 this.updateSelection(data);
             } else {
                 if (this.opts.initSelection === undefined) {
                     throw new Error("val() cannot be called if initSelection() is not defined")
                 }

                 this.opts.initSelection(this.opts.element, function(data){
                     var ids=$(data).map(self.id);
                     self.setVal(ids);
                     self.updateSelection(data);
                     self.clearSearch();
                 });
             }
             this.clearSearch();
         },

         // multi
         onSortStart: function() {
             if (this.select) {
                 throw new Error("Sorting of elements is not supported when attached to <select>. Attach to <input type='hidden'/> instead.");
             }

             // collapse search field into 0 width so its container can be collapsed as well
             this.search.width(0);
             // hide the container
             this.searchContainer.hide();
         },

         // multi
         onSortEnd:function() {

             var val=[], self=this;

             // show search and move it to the end of the list
             this.searchContainer.show();
             // make sure the search container is the last item in the list
             this.searchContainer.appendTo(this.searchContainer.parent());
             // since we collapsed the width in dragStarted, we resize it here
             this.resizeSearch();

             // update selection

             this.selection.find(".select2-search-choice").each(function() {
                 val.push(self.opts.id($(this).data("select2-data")));
             });
             this.setVal(val);
             this.triggerChange();
         },

         // multi
         data: function(values) {
             var self=this, ids;
             if (arguments.length === 0) {
                  return this.selection
                      .find(".select2-search-choice")
                      .map(function() { return $(this).data("select2-data"); })
                      .get();
             } else {
                 if (!values) { values = []; }
                 ids = $.map(values, function(e) { return self.opts.id(e)});
                 this.setVal(ids);
                 this.updateSelection(values);
                 this.clearSearch();
             }
         }
     });

     $.fn.select2 = function () {

         var args = Array.prototype.slice.call(arguments, 0),
             opts,
             select2,
             value, multiple, allowedMethods = ["val", "destroy", "opened", "open", "close", "focus", "isFocused", "container", "onSortStart", "onSortEnd", "enable", "disable", "positionDropdown", "data"];

         this.each(function () {
             if (args.length === 0 || typeof(args[0]) === "object") {
                 opts = args.length === 0 ? {} : $.extend({}, args[0]);
                 opts.element = $(this);

                 if (opts.element.get(0).tagName.toLowerCase() === "select") {
                     multiple = opts.element.attr("multiple");
                 } else {
                     multiple = opts.multiple || false;
                     if ("tags" in opts) {opts.multiple = multiple = true;}
                 }

                 select2 = multiple ? new MultiSelect2() : new SingleSelect2();
                 select2.init(opts);
             } else if (typeof(args[0]) === "string") {

                 if (indexOf(args[0], allowedMethods) < 0) {
                     throw "Unknown method: " + args[0];
                 }

                 value = undefined;
                 select2 = $(this).data("select2");
                 if (select2 === undefined) return;
                 if (args[0] === "container") {
                     value=select2.container;
                 } else {
                     value = select2[args[0]].apply(select2, args.slice(1));
                 }
                 if (value !== undefined) {return false;}
             } else {
                 throw "Invalid arguments to select2 plugin: " + args;
             }
         });
         return (value === undefined) ? this : value;
     };

     // plugin defaults, accessible to users
     $.fn.select2.defaults = {
         width: "copy",
         closeOnSelect: true,
         openOnEnter: true,
         containerCss: {},
         dropdownCss: {},
         containerCssClass: "",
         dropdownCssClass: "",
         formatResult: function(result, container, query) {
             var markup=[];
             markMatch(result.text, query.term, markup);
             return markup.join("");
         },
         formatSelection: function (data, container) {
             return data ? data.text : undefined;
         },
         formatResultCssClass: function(data) {return undefined;},
         formatNoMatches: function () { return "No matches found"; },
         formatInputTooShort: function (input, min) { return "Please enter " + (min - input.length) + " more characters"; },
         formatSelectionTooBig: function (limit) { return "You can only select " + limit + " item" + (limit == 1 ? "" : "s"); },
         formatLoadMore: function (pageNumber) { return "Loading more results..."; },
         formatSearching: function () { return "Searching..."; },
         minimumResultsForSearch: 0,
         minimumInputLength: 0,
         maximumSelectionSize: 0,
         id: function (e) { return e.id; },
         matcher: function(term, text) {
             return text.toUpperCase().indexOf(term.toUpperCase()) >= 0;
         },
         separator: ",",
         tokenSeparators: [],
         tokenizer: defaultTokenizer,
         escapeMarkup: function (markup) {
             if (markup && typeof(markup) === "string") {
                 return markup.replace(/&/g, "&amp;");
             }
             return markup;
         },
         blurOnChange: false
     };

     // exports
     window.Select2 = {
         query: {
             ajax: ajax,
             local: local,
             tags: tags
         }, util: {
             debounce: debounce,
             markMatch: markMatch
         }, "class": {
             "abstract": AbstractSelect2,
             "single": SingleSelect2,
             "multi": MultiSelect2
         }
     };

 }(jQuery));


/**
 * password_strength_plugin.js
 * Copyright (c) 20010 myPocket technologies (www.mypocket-technologies.com)
 

 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:

 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * @author Darren Mason (djmason9@gmail.com)
 * @date 3/13/2009
 * @projectDescription Password Strength Meter is a jQuery plug-in provide you smart algorithm to detect a password strength. Based on Firas Kassem orginal plugin - http://phiras.wordpress.com/2007/04/08/password-strength-meter-a-jquery-plugin/
 * @version 1.0.1
 * 
 * @requires jquery.js (tested with 1.3.2)
 * @param shortPass:    "shortPass",    //optional
 * @param badPass:              "badPass",              //optional
 * @param goodPass:             "goodPass",             //optional
 * @param strongPass:   "strongPass",   //optional
 * @param baseStyle:    "testresult",   //optional
 * @param userid:               "",                             //required override
 * 
*/

(function($){ 
        $.fn.shortPass = '';
        $.fn.badPass = 'Strength: Weak';
        $.fn.goodPass = 'Strength: Good';
        $.fn.strongPass = 'Strength: Strong like bull';
        $.fn.samePassword = 'Username and Password identical.';
        $.fn.resultStyle = "";
        
         $.fn.passStrength = function(options) {  
          
                 var defaults = {
                                shortPass:              "shortPass",    //optional
                                badPass:                "badPass",              //optional
                                goodPass:               "goodPass",             //optional
                                strongPass:             "strongPass",   //optional
                                baseStyle:              "strengthresult"   //optional
//                                userid:                 "",                             //required override
                        }; 
                        var opts = $.extend(defaults, options);  
                      
                        return this.each(function() { 
                                 var obj = $(this),
                                     objClone = obj.next(),
                                     resultsTag = $('<div class=" form-input-notes '+opts.baseStyle+'"><div>').insertAfter(objClone);
                                
                                obj.add(objClone).keyup(function()
                                {
                                    var $this = $(this),
                                        results = $.fn.teststrength($this.val(), opts);
                                    resultsTag.attr('data-passtype', $this.resultStyle).text(results);
                                    
                                    if ($this.val() == "")
                                    {
                                        resultsTag.hide();
                                    }
                                    else
                                    {
                                        resultsTag.show();
                                    }
                                    
                                })
                                .blur(function()
                                {
                                    resultsTag.hide();
                                })
                                .focus(function()
                                {
                                    if (obj.val() !== "")
                                        resultsTag.show();
                                });
                                 
                                //FUNCTIONS
//                                $.fn.teststrength = function(password,username,option){
                                $.fn.teststrength = function(password, option){
                                                var score = 0; 
                                            
                                            //password < 4
//                                            if (password.length < 4 ) { this.resultStyle =  option.shortPass;return $(this).shortPass; }
                                            
                                            //password == user name
//                                            if (password.toLowerCase()==username.toLowerCase()){this.resultStyle = option.badPass;return $(this).samePassword;}
                                            
                                            //password length
                                            score += password.length * 4;
                                            score += ( $.fn.checkRepetition(1,password).length - password.length ) * 1;
                                            score += ( $.fn.checkRepetition(2,password).length - password.length ) * 1;
                                            score += ( $.fn.checkRepetition(3,password).length - password.length ) * 1;
                                            score += ( $.fn.checkRepetition(4,password).length - password.length ) * 1;
                        
                                            //password has 3 numbers
                                            if (password.match(/(.*[0-9].*[0-9].*[0-9])/)){ score += 5;} 
                                            
                                            //password has 2 symbols
                                            if (password.match(/(.*[!,@,#,$,%,^,&,*,?,_,~].*[!,@,#,$,%,^,&,*,?,_,~])/)){ score += 5 ;}
                                            
                                            //password has Upper and Lower chars
                                            if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)){  score += 10;} 
                                            
                                            //password has number and chars
                                            if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/)){  score += 15;} 
                                            //
                                            //password has number and symbol
                                            if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([0-9])/)){  score += 15;} 
                                            
                                            //password has char and symbol
                                            if (password.match(/([!,@,#,$,%,^,&,*,?,_,~])/) && password.match(/([a-zA-Z])/)){score += 15;}
                                            
                                            //password is just a numbers or chars
                                            if (password.match(/^\w+$/) || password.match(/^\d+$/) ){ score -= 10;}
                                            
                                            //verifying 0 < score < 100
                                            if ( score < 0 ){score = 0;} 
                                            if ( score > 100 ){  score = 100;} 
                                            
                                            if (score < 34 ){ this.resultStyle = option.badPass; return $(this).badPass;} 
                                            if (score < 68 ){ this.resultStyle = option.goodPass;return $(this).goodPass;}
                                            
                                           this.resultStyle= option.strongPass;
                                            return $(this).strongPass;
                                            
                                };
                  
                  });  
         };  
})(jQuery); 


$.fn.checkRepetition = function(pLen,str) {
        var res = "";
     for (var i=0; i<str.length ; i++ ) 
     {
         var repeated=true;
         
         for (var j=0;j < pLen && (j+i+pLen) < str.length;j++){
             repeated=repeated && (str.charAt(j+i)==str.charAt(j+i+pLen));
             }
         if (j<pLen){repeated=false;}
         if (repeated) {
             i+=pLen-1;
             repeated=false;
         }
         else {
             res+=str.charAt(i);
         }
     }
     return res;
        };

;(function($) {

    $.noty.layouts.topLeft = {
        name: 'topLeft',
        options: { // overrides options
            
        },
        container: {
            object: '<ul id="noty_topLeft_layout_container" />',
            selector: 'ul#noty_topLeft_layout_container',
            style: function() {
                $(this).css({
                    top: 20,
                    left: 20,
                    position: 'fixed',
                    width: '310px',
                    height: 'auto',
                    margin: 0,
                    padding: 0,
                    listStyleType: 'none',
                    zIndex: 1000
                });

                if (window.innerWidth < 600) {
                    $(this).css({
                        left: 5
                    });
                }
            }
        },
        parent: {
            object: '<li />',
            selector: 'li',
            css: {}
        },
        css: {
            display: 'none',
            width: '250px'
        },
        addClass: ''
    };

})(jQuery);

;(function($) {

    $.noty.layouts.topCenter = {
        name: 'topCenter',
        options: { // overrides options

        },
        container: {
            object: '<ul id="noty_topCenter_layout_container" />',
            selector: 'ul#noty_topCenter_layout_container',
            style: function() {
                $(this).css({
                    top: 20,
                    left: 0,
                    position: 'fixed',
                    width: '310px',
                    height: 'auto',
                    margin: 0,
                    padding: 0,
                    listStyleType: 'none',
                    zIndex: 20001
                });

                $(this).css({
                    left: ($(window).width() - $(this).outerWidth(false)) / 2 + 'px'
                });
            }
        },
        parent: {
            object: '<li />',
            selector: 'li',
            css: {}
        },
        css: {
            display: 'none',
            width: '310px'
        },
        addClass: ''
    };

})(jQuery);

;(function($) {

    $.noty.layouts.top = {
        name: 'top',
        options: {},
        container: {
            object: '<ul id="noty_top_layout_container" />',
            selector: 'ul#noty_top_layout_container',
            style: function() {
                $(this).css({
                    top: 0,
                    left: '5%',
                    position: 'fixed',
                    width: '90%',
                    height: 'auto',
                    margin: 0,
                    padding: 0,
                    listStyleType: 'none',
                    zIndex: 1000
                });
            }
        },
        parent: {
            object: '<li />',
            selector: 'li',
            css: {}
        },
        css: {
            display: 'none'
        },
        addClass: ''
    };

})(jQuery);

;(function($) {

    $.noty.layouts.inline = {
        name: 'inline',
        options: {},
        container: {
            object: '<ul id="noty_inline_layout_container" />',
            selector: 'ul#noty_inline_layout_container',
            style: function() {
                $(this).css({
                    width: '100%',
                    height: 'auto',
                    margin: '0 0 6px 0',
                    padding: 0,
                    listStyleType: 'none',
                    zIndex: 1000
                });
            }
        },
        parent: {
            object: '<li />',
            selector: 'li',
            css: {}
        },
        css: {
            display: 'none'
        },
        addClass: ''
    };

})(jQuery);

;(function($) {

    $.noty.themes.defaultTheme = {
        name: 'defaultTheme',
        helpers: {
            borderFix: function() {
                if (this.options.dismissQueue) {
                    var selector = this.options.layout.container.selector + ' ' + this.options.layout.parent.selector;
                    switch (this.options.layout.name) {
                        case 'top':
                            $(selector).css({borderRadius: '0px 0px 0px 0px'});
                            $(selector).last().css({borderRadius: '0px 0px 5px 5px'}); break;
                        case 'topCenter': case 'topLeft': case 'topRight':
                        case 'bottomCenter': case 'bottomLeft': case 'bottomRight':
                        case 'center': case 'centerLeft': case 'centerRight': case 'inline':
                            $(selector).css({borderRadius: '0px 0px 0px 0px'});
                            $(selector).first().css({'border-top-left-radius': '5px', 'border-top-right-radius': '5px'});
                            $(selector).last().css({'border-bottom-left-radius': '5px', 'border-bottom-right-radius': '5px'}); break;
                        case 'bottom':
                            $(selector).css({borderRadius: '0px 0px 0px 0px'});
                            $(selector).first().css({borderRadius: '5px 5px 0px 0px'}); break;
                        default: break;
                    }
                }
            }
        },
        modal: {
            css: {
                position: 'fixed',
                width: '100%',
                height: '100%',
                backgroundColor: '#000',
                zIndex: 10000,
                opacity: 0.6,
                display: 'none',
                left: 0,
                top: 0
            }
        },
        style: function() {

            this.$bar.css({
                overflow: 'hidden',
                background: "url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABsAAAAoCAYAAAAPOoFWAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAPZJREFUeNq81tsOgjAMANB2ov7/7ypaN7IlIwi9rGuT8QSc9EIDAsAznxvY4pXPKr05RUE5MEVB+TyWfCEl9LZApYopCmo9C4FKSMtYoI8Bwv79aQJU4l6hXXCZrQbokJEksxHo9KMOgc6w1atHXM8K9DVC7FQnJ0i8iK3QooGgbnyKgMDygBWyYFZoqx4qS27KqLZJjA1D0jK6QJcYEQEiWv9PGkTsbqxQ8oT+ZtZB6AkdsJnQDnMoHXHLGKOgDYuCWmYhEERCI5gaamW0bnHdA3k2ltlIN+2qKRyCND0bhqSYCyTB3CAOc4WusBEIpkeBuPgJMAAX8Hs1NfqHRgAAAABJRU5ErkJggg==') repeat-x scroll left top #fff"
            });

            this.$message.css({
                fontSize: '13px',
                lineHeight: '18px',
                textAlign: 'center',
                padding: '8px 25px 9px',
                width: 'auto',
                position: 'relative'
            });

            this.$closeButton.css({
                position: 'absolute',
                top: 12, right: 12,
                width: 10, height: 10,
                background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAATpJREFUeNoszrFqVFEUheG19zlz7sQ7ijMQBAvfYBqbpJCoZSAQbOwEE1IHGytbLQUJ8SUktW8gCCFJMSGSNxCmFBJO7j5rpXD6n5/P5vM53H3b3T9LOiB5AQDuDjM7BnA7DMPHDGBH0nuSzwHsRcRVRNRSysuU0i6AOwA/02w2+9Fae00SEbEh6SGAR5K+k3zWWptKepCm0+kpyRoRGyRBcpPkDsn1iEBr7drdP2VJZyQXERGSPpiZAViTBACXKaV9kqd5uVzCzO5KKb/d/UZSDwD/eyxqree1VqSu6zKAF2Z2RPJJaw0rAkjOJT0m+SuT/AbgDcmnkmBmfwAsJL1dXQ8lWY6IGwB1ZbrOOb8zs8thGP4COFwx/mE8Ho9Go9ErMzvJOW/1fY/JZIJSypqZfXX3L13X9fcDAKJct1sx3OiuAAAAAElFTkSuQmCC)",
                display: 'none',
                cursor: 'pointer'
            });

            this.$buttons.css({
                padding: 5,
                textAlign: 'right',
                borderTop: '1px solid #ccc',
                backgroundColor: '#fff'
            });

            this.$buttons.find('button').css({
                marginLeft: 5
            });

            this.$buttons.find('button:first').css({
                marginLeft: 0
            });

            this.$bar.bind({
                mouseenter: function() { $(this).find('.noty_close').fadeIn(); },
                mouseleave: function() { $(this).find('.noty_close').fadeOut(); }
            });

            switch (this.options.layout.name) {
                case 'top':
                    this.$bar.css({
                        borderRadius: '0px 0px 5px 5px',
                        borderBottom: '2px solid #eee',
                        borderLeft: '2px solid #eee',
                        borderRight: '2px solid #eee',
                        boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                break;
                case 'topCenter': case 'center': case 'bottomCenter': case 'inline':
                    this.$bar.css({
                        borderRadius: '5px',
                        border: '1px solid #eee',
                        boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                    this.$message.css({fontSize: '13px', textAlign: 'center'});
                break;
                case 'topLeft': case 'topRight':
                case 'bottomLeft': case 'bottomRight':
                case 'centerLeft': case 'centerRight':
                    this.$bar.css({
                        borderRadius: '5px',
                        border: '1px solid #eee',
                        boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                    this.$message.css({fontSize: '13px', textAlign: 'left'});
                break;
                case 'bottom':
                    this.$bar.css({
                        borderRadius: '5px 5px 0px 0px',
                        borderTop: '2px solid #eee',
                        borderLeft: '2px solid #eee',
                        borderRight: '2px solid #eee',
                        boxShadow: "0 -2px 4px rgba(0, 0, 0, 0.1)"
                    });
                break;
                default:
                    this.$bar.css({
                        border: '2px solid #eee',
                        boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                    });
                break;
            }

            switch (this.options.type) {
                case 'alert': case 'notification':
                    this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'}); break;
                case 'warning':
                    this.$bar.css({backgroundColor: '#FFEAA8', borderColor: '#FFC237', color: '#826200'});
                    this.$buttons.css({borderTop: '1px solid #FFC237'}); break;
                case 'error':
                    this.$bar.css({backgroundColor: 'red', borderColor: 'darkred', color: '#FFF'});
                    this.$message.css({fontWeight: 'bold'});
                    this.$buttons.css({borderTop: '1px solid darkred'}); break;
                case 'information':
                    this.$bar.css({backgroundColor: '#57B7E2', borderColor: '#0B90C4', color: '#FFF'});
                    this.$buttons.css({borderTop: '1px solid #0B90C4'}); break;
                case 'success':
                    this.$bar.css({backgroundColor: 'lightgreen', borderColor: '#50C24E', color: 'darkgreen'});
                    this.$buttons.css({borderTop: '1px solid #50C24E'});break;
                default:
                    this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'}); break;
            }
        },
        callback: {
            onShow: function() { $.noty.themes.defaultTheme.helpers.borderFix.apply(this); },
            onClose: function() { $.noty.themes.defaultTheme.helpers.borderFix.apply(this); }
        }
    };

})(jQuery);

/*
 * Mailcheck https://github.com/Kicksend/mailcheck
 * Author
 * Derrick Ko (@derrickko)
 *
 * License
 * Copyright (c) 2012 Receivd, Inc.
 *
 * Licensed under the MIT License.
 *
 * v 1.1
 */

var Kicksend = {
  mailcheck : {
    threshold: 3,

    defaultDomains: ["yahoo.com", "yahoo.ca", "google.com", "hotmail.com", "gmail.com", "me.com", "aol.com", "mac.com", "live.ca", "live.com", "comcast.net", "googlemail.com", "msn.com", "hotmail.co.uk", "yahoo.co.uk", "facebook.com", "verizon.net", "sbcglobal.net", "gmx.com", "mail.com"],

    defaultTopLevelDomains: ["co.uk", "ca", "biz", "fr", "com", "net", "org", "info", "edu", "gov", "mil"],

    run: function(opts) {
      opts.domains = opts.domains || Kicksend.mailcheck.defaultDomains;
      opts.topLevelDomains = opts.topLevelDomains || Kicksend.mailcheck.defaultTopLevelDomains;
      opts.distanceFunction = opts.distanceFunction || Kicksend.sift3Distance;

      var result = Kicksend.mailcheck.suggest(encodeURI(opts.email), opts.domains, opts.topLevelDomains, opts.distanceFunction);

      if (result) {
        if (opts.suggested) {
          opts.suggested(result);
        }
      } else {
        if (opts.empty) {
          opts.empty();
        }
      }
    },

    suggest: function(email, domains, topLevelDomains, distanceFunction) {
      email = email.toLowerCase();

      var emailParts = this.splitEmail(email);

      var closestDomain = this.findClosestDomain(emailParts.domain, domains, distanceFunction);

      if (closestDomain) {
        if (closestDomain != emailParts.domain) {
          // The email address closely matches one of the supplied domains; return a suggestion
          return { address: emailParts.address, domain: closestDomain, full: emailParts.address + "@" + closestDomain };
        }
      } else {
        // The email address does not closely match one of the supplied domains
        var closestTopLevelDomain = this.findClosestDomain(emailParts.topLevelDomain, topLevelDomains);
        if (emailParts.domain && closestTopLevelDomain && closestTopLevelDomain != emailParts.topLevelDomain) {
          // The email address may have a mispelled top-level domain; return a suggestion
          var domain = emailParts.domain;
          closestDomain = domain.substring(0, domain.lastIndexOf(emailParts.topLevelDomain)) + closestTopLevelDomain;
          return { address: emailParts.address, domain: closestDomain, full: emailParts.address + "@" + closestDomain };
        }
      }
      /* The email address exactly matches one of the supplied domains, does not closely
       * match any domain and does not appear to simply have a mispelled top-level domain,
       * or is an invalid email address; do not return a suggestion.
       */
      return false;
    },

    findClosestDomain: function(domain, domains, distanceFunction) {
      var dist;
      var minDist = 99;
      var closestDomain = null;

      if (!domain || !domains) {
        return false;
      }
      if(!distanceFunction) {
        distanceFunction = this.sift3Distance;
      }

      for (var i = 0; i < domains.length; i++) {
        if (domain === domains[i]) {
          return domain;
        }
        dist = distanceFunction(domain, domains[i]);
        if (dist < minDist) {
          minDist = dist;
          closestDomain = domains[i];
        }
      }

      if (minDist <= this.threshold && closestDomain !== null) {
        return closestDomain;
      } else {
        return false;
      }
    },

    sift3Distance: function(s1, s2) {
      // sift3: http://siderite.blogspot.com/2007/04/super-fast-and-accurate-string-distance.html
      if (s1 == null || s1.length === 0) {
        if (s2 == null || s2.length === 0) {
          return 0;
        } else {
          return s2.length;
        }
      }

      if (s2 == null || s2.length === 0) {
        return s1.length;
      }

      var c = 0;
      var offset1 = 0;
      var offset2 = 0;
      var lcs = 0;
      var maxOffset = 5;

      while ((c + offset1 < s1.length) && (c + offset2 < s2.length)) {
        if (s1.charAt(c + offset1) == s2.charAt(c + offset2)) {
          lcs++;
        } else {
          offset1 = 0;
          offset2 = 0;
          for (var i = 0; i < maxOffset; i++) {
            if ((c + i < s1.length) && (s1.charAt(c + i) == s2.charAt(c))) {
              offset1 = i;
              break;
            }
            if ((c + i < s2.length) && (s1.charAt(c) == s2.charAt(c + i))) {
              offset2 = i;
              break;
            }
          }
        }
        c++;
      }
      return (s1.length + s2.length) /2 - lcs;
    },

    splitEmail: function(email) {
      var parts = email.split('@');

      if (parts.length < 2) {
        return false;
      }

      for (var i = 0; i < parts.length; i++) {
        if (parts[i] === '') {
          return false;
        }
      }

      var domain = parts.pop();
      var domainParts = domain.split('.');
      var tld = '';

      if (domainParts.length == 0) {
        // The address does not have a top-level domain
        return false;
      } else if (domainParts.length == 1) {
        // The address has only a top-level domain (valid under RFC)
        tld = domainParts[0];
      } else {
        // The address has a domain and a top-level domain
        for (var i = 1; i < domainParts.length; i++) {
          tld += domainParts[i] + '.';
        }
        if (domainParts.length >= 2) {
          tld = tld.substring(0, tld.length - 1);
        }
      }

      return {
        topLevelDomain: tld,
        domain: domain,
        address: parts.join('@')
      }
    }
  }
};

if (window.jQuery) {
  (function($){
    $.fn.mailcheck = function(opts) {
      var self = this;
      if (opts.suggested) {
        var oldSuggested = opts.suggested;
        opts.suggested = function(result) {
          oldSuggested(self, result);
        };
      }

      if (opts.empty) {
        var oldEmpty = opts.empty;
        opts.empty = function() {
          oldEmpty.call(null, self);
        };
      }

      opts.email = this.val();
      Kicksend.mailcheck.run(opts);
    }
  })(jQuery);
}

/*
    json2.js
    2012-10-08

    Public Domain.

    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.

    See http://www.JSON.org/js.html


    This code should be minified before deployment.
    See http://javascript.crockford.com/jsmin.html

    USE YOUR OWN COPY. IT IS EXTREMELY UNWISE TO LOAD CODE FROM SERVERS YOU DO
    NOT CONTROL.


    This file creates a global JSON object containing two methods: stringify
    and parse.

        JSON.stringify(value, replacer, space)
            value       any JavaScript value, usually an object or array.

            replacer    an optional parameter that determines how object
                        values are stringified for objects. It can be a
                        function or an array of strings.

            space       an optional parameter that specifies the indentation
                        of nested structures. If it is omitted, the text will
                        be packed without extra whitespace. If it is a number,
                        it will specify the number of spaces to indent at each
                        level. If it is a string (such as '\t' or '&nbsp;'),
                        it contains the characters used to indent at each level.

            This method produces a JSON text from a JavaScript value.

            When an object value is found, if the object contains a toJSON
            method, its toJSON method will be called and the result will be
            stringified. A toJSON method does not serialize: it returns the
            value represented by the name/value pair that should be serialized,
            or undefined if nothing should be serialized. The toJSON method
            will be passed the key associated with the value, and this will be
            bound to the value

            For example, this would serialize Dates as ISO strings.

                Date.prototype.toJSON = function (key) {
                    function f(n) {
                        // Format integers to have at least two digits.
                        return n < 10 ? '0' + n : n;
                    }

                    return this.getUTCFullYear()   + '-' +
                         f(this.getUTCMonth() + 1) + '-' +
                         f(this.getUTCDate())      + 'T' +
                         f(this.getUTCHours())     + ':' +
                         f(this.getUTCMinutes())   + ':' +
                         f(this.getUTCSeconds())   + 'Z';
                };

            You can provide an optional replacer method. It will be passed the
            key and value of each member, with this bound to the containing
            object. The value that is returned from your method will be
            serialized. If your method returns undefined, then the member will
            be excluded from the serialization.

            If the replacer parameter is an array of strings, then it will be
            used to select the members to be serialized. It filters the results
            such that only members with keys listed in the replacer array are
            stringified.

            Values that do not have JSON representations, such as undefined or
            functions, will not be serialized. Such values in objects will be
            dropped; in arrays they will be replaced with null. You can use
            a replacer function to replace those with JSON values.
            JSON.stringify(undefined) returns undefined.

            The optional space parameter produces a stringification of the
            value that is filled with line breaks and indentation to make it
            easier to read.

            If the space parameter is a non-empty string, then that string will
            be used for indentation. If the space parameter is a number, then
            the indentation will be that many spaces.

            Example:

            text = JSON.stringify(['e', {pluribus: 'unum'}]);
            // text is '["e",{"pluribus":"unum"}]'


            text = JSON.stringify(['e', {pluribus: 'unum'}], null, '\t');
            // text is '[\n\t"e",\n\t{\n\t\t"pluribus": "unum"\n\t}\n]'

            text = JSON.stringify([new Date()], function (key, value) {
                return this[key] instanceof Date ?
                    'Date(' + this[key] + ')' : value;
            });
            // text is '["Date(---current time---)"]'


        JSON.parse(text, reviver)
            This method parses a JSON text to produce an object or array.
            It can throw a SyntaxError exception.

            The optional reviver parameter is a function that can filter and
            transform the results. It receives each of the keys and values,
            and its return value is used instead of the original value.
            If it returns what it received, then the structure is not modified.
            If it returns undefined then the member is deleted.

            Example:

            // Parse the text. Values that look like ISO date strings will
            // be converted to Date objects.

            myData = JSON.parse(text, function (key, value) {
                var a;
                if (typeof value === 'string') {
                    a =
/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2}(?:\.\d*)?)Z$/.exec(value);
                    if (a) {
                        return new Date(Date.UTC(+a[1], +a[2] - 1, +a[3], +a[4],
                            +a[5], +a[6]));
                    }
                }
                return value;
            });

            myData = JSON.parse('["Date(09/09/2001)"]', function (key, value) {
                var d;
                if (typeof value === 'string' &&
                        value.slice(0, 5) === 'Date(' &&
                        value.slice(-1) === ')') {
                    d = new Date(value.slice(5, -1));
                    if (d) {
                        return d;
                    }
                }
                return value;
            });


    This is a reference implementation. You are free to copy, modify, or
    redistribute.
*/

/*jslint evil: true, regexp: true */

/*members "", "\b", "\t", "\n", "\f", "\r", "\"", JSON, "\\", apply,
    call, charCodeAt, getUTCDate, getUTCFullYear, getUTCHours,
    getUTCMinutes, getUTCMonth, getUTCSeconds, hasOwnProperty, join,
    lastIndex, length, parse, prototype, push, replace, slice, stringify,
    test, toJSON, toString, valueOf
*/


// Create a JSON object only if one does not already exist. We create the
// methods in a closure to avoid creating global variables.

if (typeof JSON !== 'object') {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function (key) {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()     + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())      + 'T' +
                    f(this.getUTCHours())     + ':' +
                    f(this.getUTCMinutes())   + ':' +
                    f(this.getUTCSeconds())   + 'Z'
                : null;
        };

        String.prototype.toJSON      =
            Number.prototype.toJSON  =
            Boolean.prototype.toJSON = function (key) {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {    // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,          // The loop counter.
            k,          // The member key.
            v,          // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());

/*
SWFObject v2.2 <http://code.google.com/p/swfobject/> 
is released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
;var swfobject=function(){var D="undefined",r="object",S="Shockwave Flash",W="ShockwaveFlash.ShockwaveFlash",q="application/x-shockwave-flash",R="SWFObjectExprInst",x="onreadystatechange",O=window,j=document,t=navigator,T=false,U=[h],o=[],N=[],I=[],l,Q,E,B,J=false,a=false,n,G,m=true,M=function(){var aa=typeof j.getElementById!=D&&typeof j.getElementsByTagName!=D&&typeof j.createElement!=D,ah=t.userAgent.toLowerCase(),Y=t.platform.toLowerCase(),ae=Y?/win/.test(Y):/win/.test(ah),ac=Y?/mac/.test(Y):/mac/.test(ah),af=/webkit/.test(ah)?parseFloat(ah.replace(/^.*webkit\/(\d+(\.\d+)?).*$/,"$1")):false,X=!+"\v1",ag=[0,0,0],ab=null;
if(typeof t.plugins!=D&&typeof t.plugins[S]==r){ab=t.plugins[S].description;if(ab&&!(typeof t.mimeTypes!=D&&t.mimeTypes[q]&&!t.mimeTypes[q].enabledPlugin)){T=true;
X=false;ab=ab.replace(/^.*\s+(\S+\s+\S+$)/,"$1");ag[0]=parseInt(ab.replace(/^(.*)\..*$/,"$1"),10);ag[1]=parseInt(ab.replace(/^.*\.(.*)\s.*$/,"$1"),10);
ag[2]=/[a-zA-Z]/.test(ab)?parseInt(ab.replace(/^.*[a-zA-Z]+(.*)$/,"$1"),10):0;}}else{if(typeof O.ActiveXObject!=D){try{var ad=new ActiveXObject(W);if(ad){ab=ad.GetVariable("$version");
if(ab){X=true;ab=ab.split(" ")[1].split(",");ag=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)];}}}catch(Z){}}}return{w3:aa,pv:ag,wk:af,ie:X,win:ae,mac:ac};
}(),k=function(){if(!M.w3){return;}if((typeof j.readyState!=D&&j.readyState=="complete")||(typeof j.readyState==D&&(j.getElementsByTagName("body")[0]||j.body))){f();
}if(!J){if(typeof j.addEventListener!=D){j.addEventListener("DOMContentLoaded",f,false);}if(M.ie&&M.win){j.attachEvent(x,function(){if(j.readyState=="complete"){j.detachEvent(x,arguments.callee);
f();}});if(O==top){(function(){if(J){return;}try{j.documentElement.doScroll("left");}catch(X){setTimeout(arguments.callee,0);return;}f();})();}}if(M.wk){(function(){if(J){return;
}if(!/loaded|complete/.test(j.readyState)){setTimeout(arguments.callee,0);return;}f();})();}s(f);}}();function f(){if(J){return;}try{var Z=j.getElementsByTagName("body")[0].appendChild(C("span"));
Z.parentNode.removeChild(Z);}catch(aa){return;}J=true;var X=U.length;for(var Y=0;Y<X;Y++){U[Y]();}}function K(X){if(J){X();}else{U[U.length]=X;}}function s(Y){if(typeof O.addEventListener!=D){O.addEventListener("load",Y,false);
}else{if(typeof j.addEventListener!=D){j.addEventListener("load",Y,false);}else{if(typeof O.attachEvent!=D){i(O,"onload",Y);}else{if(typeof O.onload=="function"){var X=O.onload;
O.onload=function(){X();Y();};}else{O.onload=Y;}}}}}function h(){if(T){V();}else{H();}}function V(){var X=j.getElementsByTagName("body")[0];var aa=C(r);
aa.setAttribute("type",q);var Z=X.appendChild(aa);if(Z){var Y=0;(function(){if(typeof Z.GetVariable!=D){var ab=Z.GetVariable("$version");if(ab){ab=ab.split(" ")[1].split(",");
M.pv=[parseInt(ab[0],10),parseInt(ab[1],10),parseInt(ab[2],10)];}}else{if(Y<10){Y++;setTimeout(arguments.callee,10);return;}}X.removeChild(aa);Z=null;H();
})();}else{H();}}function H(){var ag=o.length;if(ag>0){for(var af=0;af<ag;af++){var Y=o[af].id;var ab=o[af].callbackFn;var aa={success:false,id:Y};if(M.pv[0]>0){var ae=c(Y);
if(ae){if(F(o[af].swfVersion)&&!(M.wk&&M.wk<312)){w(Y,true);if(ab){aa.success=true;aa.ref=z(Y);ab(aa);}}else{if(o[af].expressInstall&&A()){var ai={};ai.data=o[af].expressInstall;
ai.width=ae.getAttribute("width")||"0";ai.height=ae.getAttribute("height")||"0";if(ae.getAttribute("class")){ai.styleclass=ae.getAttribute("class");}if(ae.getAttribute("align")){ai.align=ae.getAttribute("align");
}var ah={};var X=ae.getElementsByTagName("param");var ac=X.length;for(var ad=0;ad<ac;ad++){if(X[ad].getAttribute("name").toLowerCase()!="movie"){ah[X[ad].getAttribute("name")]=X[ad].getAttribute("value");
}}P(ai,ah,Y,ab);}else{p(ae);if(ab){ab(aa);}}}}}else{w(Y,true);if(ab){var Z=z(Y);if(Z&&typeof Z.SetVariable!=D){aa.success=true;aa.ref=Z;}ab(aa);}}}}}function z(aa){var X=null;
var Y=c(aa);if(Y&&Y.nodeName=="OBJECT"){if(typeof Y.SetVariable!=D){X=Y;}else{var Z=Y.getElementsByTagName(r)[0];if(Z){X=Z;}}}return X;}function A(){return !a&&F("6.0.65")&&(M.win||M.mac)&&!(M.wk&&M.wk<312);
}function P(aa,ab,X,Z){a=true;E=Z||null;B={success:false,id:X};var ae=c(X);if(ae){if(ae.nodeName=="OBJECT"){l=g(ae);Q=null;}else{l=ae;Q=X;}aa.id=R;if(typeof aa.width==D||(!/%$/.test(aa.width)&&parseInt(aa.width,10)<310)){aa.width="310";
}if(typeof aa.height==D||(!/%$/.test(aa.height)&&parseInt(aa.height,10)<137)){aa.height="137";}j.title=j.title.slice(0,47)+" - Flash Player Installation";
var ad=M.ie&&M.win?"ActiveX":"PlugIn",ac="MMredirectURL="+O.location.toString().replace(/&/g,"%26")+"&MMplayerType="+ad+"&MMdoctitle="+j.title;if(typeof ab.flashvars!=D){ab.flashvars+="&"+ac;
}else{ab.flashvars=ac;}if(M.ie&&M.win&&ae.readyState!=4){var Y=C("div");X+="SWFObjectNew";Y.setAttribute("id",X);ae.parentNode.insertBefore(Y,ae);ae.style.display="none";
(function(){if(ae.readyState==4){ae.parentNode.removeChild(ae);}else{setTimeout(arguments.callee,10);}})();}u(aa,ab,X);}}function p(Y){if(M.ie&&M.win&&Y.readyState!=4){var X=C("div");
Y.parentNode.insertBefore(X,Y);X.parentNode.replaceChild(g(Y),X);Y.style.display="none";(function(){if(Y.readyState==4){Y.parentNode.removeChild(Y);}else{setTimeout(arguments.callee,10);
}})();}else{Y.parentNode.replaceChild(g(Y),Y);}}function g(ab){var aa=C("div");if(M.win&&M.ie){aa.innerHTML=ab.innerHTML;}else{var Y=ab.getElementsByTagName(r)[0];
if(Y){var ad=Y.childNodes;if(ad){var X=ad.length;for(var Z=0;Z<X;Z++){if(!(ad[Z].nodeType==1&&ad[Z].nodeName=="PARAM")&&!(ad[Z].nodeType==8)){aa.appendChild(ad[Z].cloneNode(true));
}}}}}return aa;}function u(ai,ag,Y){var X,aa=c(Y);if(M.wk&&M.wk<312){return X;}if(aa){if(typeof ai.id==D){ai.id=Y;}if(M.ie&&M.win){var ah="";for(var ae in ai){if(ai[ae]!=Object.prototype[ae]){if(ae.toLowerCase()=="data"){ag.movie=ai[ae];
}else{if(ae.toLowerCase()=="styleclass"){ah+=' class="'+ai[ae]+'"';}else{if(ae.toLowerCase()!="classid"){ah+=" "+ae+'="'+ai[ae]+'"';}}}}}var af="";for(var ad in ag){if(ag[ad]!=Object.prototype[ad]){af+='<param name="'+ad+'" value="'+ag[ad]+'" />';
}}aa.outerHTML='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"'+ah+">"+af+"</object>";N[N.length]=ai.id;X=c(ai.id);}else{var Z=C(r);Z.setAttribute("type",q);
for(var ac in ai){if(ai[ac]!=Object.prototype[ac]){if(ac.toLowerCase()=="styleclass"){Z.setAttribute("class",ai[ac]);}else{if(ac.toLowerCase()!="classid"){Z.setAttribute(ac,ai[ac]);
}}}}for(var ab in ag){if(ag[ab]!=Object.prototype[ab]&&ab.toLowerCase()!="movie"){e(Z,ab,ag[ab]);}}aa.parentNode.replaceChild(Z,aa);X=Z;}}return X;}function e(Z,X,Y){var aa=C("param");
aa.setAttribute("name",X);aa.setAttribute("value",Y);Z.appendChild(aa);}function y(Y){var X=c(Y);if(X&&X.nodeName=="OBJECT"){if(M.ie&&M.win){X.style.display="none";
(function(){if(X.readyState==4){b(Y);}else{setTimeout(arguments.callee,10);}})();}else{X.parentNode.removeChild(X);}}}function b(Z){var Y=c(Z);if(Y){for(var X in Y){if(typeof Y[X]=="function"){Y[X]=null;
}}Y.parentNode.removeChild(Y);}}function c(Z){var X=null;try{X=j.getElementById(Z);}catch(Y){}return X;}function C(X){return j.createElement(X);}function i(Z,X,Y){Z.attachEvent(X,Y);
I[I.length]=[Z,X,Y];}function F(Z){var Y=M.pv,X=Z.split(".");X[0]=parseInt(X[0],10);X[1]=parseInt(X[1],10)||0;X[2]=parseInt(X[2],10)||0;return(Y[0]>X[0]||(Y[0]==X[0]&&Y[1]>X[1])||(Y[0]==X[0]&&Y[1]==X[1]&&Y[2]>=X[2]))?true:false;
}function v(ac,Y,ad,ab){if(M.ie&&M.mac){return;}var aa=j.getElementsByTagName("head")[0];if(!aa){return;}var X=(ad&&typeof ad=="string")?ad:"screen";if(ab){n=null;
G=null;}if(!n||G!=X){var Z=C("style");Z.setAttribute("type","text/css");Z.setAttribute("media",X);n=aa.appendChild(Z);if(M.ie&&M.win&&typeof j.styleSheets!=D&&j.styleSheets.length>0){n=j.styleSheets[j.styleSheets.length-1];
}G=X;}if(M.ie&&M.win){if(n&&typeof n.addRule==r){n.addRule(ac,Y);}}else{if(n&&typeof j.createTextNode!=D){n.appendChild(j.createTextNode(ac+" {"+Y+"}"));
}}}function w(Z,X){if(!m){return;}var Y=X?"visible":"hidden";if(J&&c(Z)){c(Z).style.visibility=Y;}else{v("#"+Z,"visibility:"+Y);}}function L(Y){var Z=/[\\\"<>\.;]/;
var X=Z.exec(Y)!=null;return X&&typeof encodeURIComponent!=D?encodeURIComponent(Y):Y;}var d=function(){if(M.ie&&M.win){window.attachEvent("onunload",function(){var ac=I.length;
for(var ab=0;ab<ac;ab++){I[ab][0].detachEvent(I[ab][1],I[ab][2]);}var Z=N.length;for(var aa=0;aa<Z;aa++){y(N[aa]);}for(var Y in M){M[Y]=null;}M=null;for(var X in swfobject){swfobject[X]=null;
}swfobject=null;});}}();return{registerObject:function(ab,X,aa,Z){if(M.w3&&ab&&X){var Y={};Y.id=ab;Y.swfVersion=X;Y.expressInstall=aa;Y.callbackFn=Z;o[o.length]=Y;
w(ab,false);}else{if(Z){Z({success:false,id:ab});}}},getObjectById:function(X){if(M.w3){return z(X);}},embedSWF:function(ab,ah,ae,ag,Y,aa,Z,ad,af,ac){var X={success:false,id:ah};
if(M.w3&&!(M.wk&&M.wk<312)&&ab&&ah&&ae&&ag&&Y){w(ah,false);K(function(){ae+="";ag+="";var aj={};if(af&&typeof af===r){for(var al in af){aj[al]=af[al];}}aj.data=ab;
aj.width=ae;aj.height=ag;var am={};if(ad&&typeof ad===r){for(var ak in ad){am[ak]=ad[ak];}}if(Z&&typeof Z===r){for(var ai in Z){if(typeof am.flashvars!=D){am.flashvars+="&"+ai+"="+Z[ai];
}else{am.flashvars=ai+"="+Z[ai];}}}if(F(Y)){var an=u(aj,am,ah);if(aj.id==ah){w(ah,true);}X.success=true;X.ref=an;}else{if(aa&&A()){aj.data=aa;P(aj,am,ah,ac);
return;}else{w(ah,true);}}if(ac){ac(X);}});}else{if(ac){ac(X);}}},switchOffAutoHideShow:function(){m=false;},ua:M,getFlashPlayerVersion:function(){return{major:M.pv[0],minor:M.pv[1],release:M.pv[2]};
},hasFlashPlayerVersion:F,createSWF:function(Z,Y,X){if(M.w3){return u(Z,Y,X);}else{return undefined;}},showExpressInstall:function(Z,aa,X,Y){if(M.w3&&A()){P(Z,aa,X,Y);
}},removeSWF:function(X){if(M.w3){y(X);}},createCSS:function(aa,Z,Y,X){if(M.w3){v(aa,Z,Y,X);}},addDomLoadEvent:K,addLoadEvent:s,getQueryParamValue:function(aa){var Z=j.location.search||j.location.hash;
if(Z){if(/\?/.test(Z)){Z=Z.split("?")[1];}if(aa==null){return L(Z);}var Y=Z.split("&");for(var X=0;X<Y.length;X++){if(Y[X].substring(0,Y[X].indexOf("="))==aa){return L(Y[X].substring((Y[X].indexOf("=")+1)));
}}}return"";},expressInstallCallback:function(){if(a){var X=c(R);if(X&&l){X.parentNode.replaceChild(l,X);if(Q){w(Q,true);if(M.ie&&M.win){l.style.display="block";
}}if(E){E(B);}}a=false;}}};}();

/*
SWFUpload: http://www.swfupload.org, http://swfupload.googlecode.com

mmSWFUpload 1.0: Flash upload dialog - http://profandesign.se/swfupload/,  http://www.vinterwebb.se/

SWFUpload is (c) 2006-2007 Lars Huring, Olov Nilzén and Mammon Media and is released under the MIT License:
http://www.opensource.org/licenses/mit-license.php
 
SWFUpload 2 is (c) 2007-2008 Jake Roberts and is released under the MIT License:
http://www.opensource.org/licenses/mit-license.php
*/

var SWFUpload;if(SWFUpload==undefined){SWFUpload=function(a){this.initSWFUpload(a)}}SWFUpload.prototype.initSWFUpload=function(b){try{this.customSettings={};this.settings=b;this.eventQueue=[];this.movieName="SWFUpload_"+SWFUpload.movieCount++;this.movieElement=null;SWFUpload.instances[this.movieName]=this;this.initSettings();this.loadFlash();this.displayDebugInfo()}catch(a){delete SWFUpload.instances[this.movieName];throw a}};SWFUpload.instances={};SWFUpload.movieCount=0;SWFUpload.version="2.2.0 2009-03-25";SWFUpload.QUEUE_ERROR={QUEUE_LIMIT_EXCEEDED:-100,FILE_EXCEEDS_SIZE_LIMIT:-110,ZERO_BYTE_FILE:-120,INVALID_FILETYPE:-130};SWFUpload.UPLOAD_ERROR={HTTP_ERROR:-200,MISSING_UPLOAD_URL:-210,IO_ERROR:-220,SECURITY_ERROR:-230,UPLOAD_LIMIT_EXCEEDED:-240,UPLOAD_FAILED:-250,SPECIFIED_FILE_ID_NOT_FOUND:-260,FILE_VALIDATION_FAILED:-270,FILE_CANCELLED:-280,UPLOAD_STOPPED:-290};SWFUpload.FILE_STATUS={QUEUED:-1,IN_PROGRESS:-2,ERROR:-3,COMPLETE:-4,CANCELLED:-5};SWFUpload.BUTTON_ACTION={SELECT_FILE:-100,SELECT_FILES:-110,START_UPLOAD:-120};SWFUpload.CURSOR={ARROW:-1,HAND:-2};SWFUpload.WINDOW_MODE={WINDOW:"window",TRANSPARENT:"transparent",OPAQUE:"opaque"};SWFUpload.completeURL=function(a){if(typeof(a)!=="string"||a.match(/^https?:\/\//i)||a.match(/^\//)){return a}var c=window.location.protocol+"//"+window.location.hostname+(window.location.port?":"+window.location.port:"");var b=window.location.pathname.lastIndexOf("/");if(b<=0){path="/"}else{path=window.location.pathname.substr(0,b)+"/"}return path+a};SWFUpload.prototype.initSettings=function(){this.ensureDefault=function(b,a){this.settings[b]=(this.settings[b]==undefined)?a:this.settings[b]};this.ensureDefault("upload_url","");this.ensureDefault("preserve_relative_urls",false);this.ensureDefault("file_post_name","Filedata");this.ensureDefault("post_params",{});this.ensureDefault("use_query_string",false);this.ensureDefault("requeue_on_error",false);this.ensureDefault("http_success",[]);this.ensureDefault("assume_success_timeout",0);this.ensureDefault("file_types","*.*");this.ensureDefault("file_types_description","All Files");this.ensureDefault("file_size_limit",0);this.ensureDefault("file_upload_limit",0);this.ensureDefault("file_queue_limit",0);this.ensureDefault("flash_url","swfupload.swf");this.ensureDefault("prevent_swf_caching",true);this.ensureDefault("button_image_url","");this.ensureDefault("button_width",1);this.ensureDefault("button_height",1);this.ensureDefault("button_text","");this.ensureDefault("button_text_style","color: #000000; font-size: 16pt;");this.ensureDefault("button_text_top_padding",0);this.ensureDefault("button_text_left_padding",0);this.ensureDefault("button_action",SWFUpload.BUTTON_ACTION.SELECT_FILES);this.ensureDefault("button_disabled",false);this.ensureDefault("button_placeholder_id","");this.ensureDefault("button_placeholder",null);this.ensureDefault("button_cursor",SWFUpload.CURSOR.ARROW);this.ensureDefault("button_window_mode",SWFUpload.WINDOW_MODE.WINDOW);this.ensureDefault("debug",false);this.settings.debug_enabled=this.settings.debug;this.settings.return_upload_start_handler=this.returnUploadStart;this.ensureDefault("swfupload_loaded_handler",null);this.ensureDefault("file_dialog_start_handler",null);this.ensureDefault("file_queued_handler",null);this.ensureDefault("file_queue_error_handler",null);this.ensureDefault("file_dialog_complete_handler",null);this.ensureDefault("upload_start_handler",null);this.ensureDefault("upload_progress_handler",null);this.ensureDefault("upload_error_handler",null);this.ensureDefault("upload_success_handler",null);this.ensureDefault("upload_complete_handler",null);this.ensureDefault("debug_handler",this.debugMessage);this.ensureDefault("custom_settings",{});this.customSettings=this.settings.custom_settings;if(!!this.settings.prevent_swf_caching){this.settings.flash_url=this.settings.flash_url+(this.settings.flash_url.indexOf("?")<0?"?":"&")+"preventswfcaching="+new Date().getTime()}if(!this.settings.preserve_relative_urls){this.settings.upload_url=SWFUpload.completeURL(this.settings.upload_url);this.settings.button_image_url=SWFUpload.completeURL(this.settings.button_image_url)}delete this.ensureDefault};SWFUpload.prototype.loadFlash=function(){var a,b;if(document.getElementById(this.movieName)!==null){throw"ID "+this.movieName+" is already in use. The Flash Object could not be added"}a=document.getElementById(this.settings.button_placeholder_id)||this.settings.button_placeholder;if(a==undefined){throw"Could not find the placeholder element: "+this.settings.button_placeholder_id}b=document.createElement("div");b.innerHTML=this.getFlashHTML();a.parentNode.replaceChild(b.firstChild,a);if(window[this.movieName]==undefined){window[this.movieName]=this.getMovieElement()}};SWFUpload.prototype.getFlashHTML=function(){return['<object id="',this.movieName,'" type="application/x-shockwave-flash" data="',this.settings.flash_url,'" width="',this.settings.button_width,'" height="',this.settings.button_height,'" class="swfupload">','<param name="wmode" value="',this.settings.button_window_mode,'" />','<param name="movie" value="',this.settings.flash_url,'" />','<param name="quality" value="high" />','<param name="menu" value="false" />','<param name="allowScriptAccess" value="always" />','<param name="flashvars" value="'+this.getFlashVars()+'" />',"</object>"].join("")};SWFUpload.prototype.getFlashVars=function(){var b=this.buildParamString();var a=this.settings.http_success.join(",");return["movieName=",encodeURIComponent(this.movieName),"&amp;uploadURL=",encodeURIComponent(this.settings.upload_url),"&amp;useQueryString=",encodeURIComponent(this.settings.use_query_string),"&amp;requeueOnError=",encodeURIComponent(this.settings.requeue_on_error),"&amp;httpSuccess=",encodeURIComponent(a),"&amp;assumeSuccessTimeout=",encodeURIComponent(this.settings.assume_success_timeout),"&amp;params=",encodeURIComponent(b),"&amp;filePostName=",encodeURIComponent(this.settings.file_post_name),"&amp;fileTypes=",encodeURIComponent(this.settings.file_types),"&amp;fileTypesDescription=",encodeURIComponent(this.settings.file_types_description),"&amp;fileSizeLimit=",encodeURIComponent(this.settings.file_size_limit),"&amp;fileUploadLimit=",encodeURIComponent(this.settings.file_upload_limit),"&amp;fileQueueLimit=",encodeURIComponent(this.settings.file_queue_limit),"&amp;debugEnabled=",encodeURIComponent(this.settings.debug_enabled),"&amp;buttonImageURL=",encodeURIComponent(this.settings.button_image_url),"&amp;buttonWidth=",encodeURIComponent(this.settings.button_width),"&amp;buttonHeight=",encodeURIComponent(this.settings.button_height),"&amp;buttonText=",encodeURIComponent(this.settings.button_text),"&amp;buttonTextTopPadding=",encodeURIComponent(this.settings.button_text_top_padding),"&amp;buttonTextLeftPadding=",encodeURIComponent(this.settings.button_text_left_padding),"&amp;buttonTextStyle=",encodeURIComponent(this.settings.button_text_style),"&amp;buttonAction=",encodeURIComponent(this.settings.button_action),"&amp;buttonDisabled=",encodeURIComponent(this.settings.button_disabled),"&amp;buttonCursor=",encodeURIComponent(this.settings.button_cursor)].join("")};SWFUpload.prototype.getMovieElement=function(){if(this.movieElement==undefined){this.movieElement=document.getElementById(this.movieName)}if(this.movieElement===null){throw"Could not find Flash element"}return this.movieElement};SWFUpload.prototype.buildParamString=function(){var c=this.settings.post_params;var b=[];if(typeof(c)==="object"){for(var a in c){if(c.hasOwnProperty(a)){b.push(encodeURIComponent(a.toString())+"="+encodeURIComponent(c[a].toString()))}}}return b.join("&amp;")};SWFUpload.prototype.destroy=function(){try{this.cancelUpload(null,false);var a=null;a=this.getMovieElement();if(a&&typeof(a.CallFunction)==="unknown"){for(var c in a){try{if(typeof(a[c])==="function"){a[c]=null}}catch(e){}}try{a.parentNode.removeChild(a)}catch(b){}}window[this.movieName]=null;SWFUpload.instances[this.movieName]=null;delete SWFUpload.instances[this.movieName];this.movieElement=null;this.settings=null;this.customSettings=null;this.eventQueue=null;this.movieName=null;return true}catch(d){return false}};SWFUpload.prototype.displayDebugInfo=function(){this.debug(["---SWFUpload Instance Info---\n","Version: ",SWFUpload.version,"\n","Movie Name: ",this.movieName,"\n","Settings:\n","\t","upload_url:               ",this.settings.upload_url,"\n","\t","flash_url:                ",this.settings.flash_url,"\n","\t","use_query_string:         ",this.settings.use_query_string.toString(),"\n","\t","requeue_on_error:         ",this.settings.requeue_on_error.toString(),"\n","\t","http_success:             ",this.settings.http_success.join(", "),"\n","\t","assume_success_timeout:   ",this.settings.assume_success_timeout,"\n","\t","file_post_name:           ",this.settings.file_post_name,"\n","\t","post_params:              ",this.settings.post_params.toString(),"\n","\t","file_types:               ",this.settings.file_types,"\n","\t","file_types_description:   ",this.settings.file_types_description,"\n","\t","file_size_limit:          ",this.settings.file_size_limit,"\n","\t","file_upload_limit:        ",this.settings.file_upload_limit,"\n","\t","file_queue_limit:         ",this.settings.file_queue_limit,"\n","\t","debug:                    ",this.settings.debug.toString(),"\n","\t","prevent_swf_caching:      ",this.settings.prevent_swf_caching.toString(),"\n","\t","button_placeholder_id:    ",this.settings.button_placeholder_id.toString(),"\n","\t","button_placeholder:       ",(this.settings.button_placeholder?"Set":"Not Set"),"\n","\t","button_image_url:         ",this.settings.button_image_url.toString(),"\n","\t","button_width:             ",this.settings.button_width.toString(),"\n","\t","button_height:            ",this.settings.button_height.toString(),"\n","\t","button_text:              ",this.settings.button_text.toString(),"\n","\t","button_text_style:        ",this.settings.button_text_style.toString(),"\n","\t","button_text_top_padding:  ",this.settings.button_text_top_padding.toString(),"\n","\t","button_text_left_padding: ",this.settings.button_text_left_padding.toString(),"\n","\t","button_action:            ",this.settings.button_action.toString(),"\n","\t","button_disabled:          ",this.settings.button_disabled.toString(),"\n","\t","custom_settings:          ",this.settings.custom_settings.toString(),"\n","Event Handlers:\n","\t","swfupload_loaded_handler assigned:  ",(typeof this.settings.swfupload_loaded_handler==="function").toString(),"\n","\t","file_dialog_start_handler assigned: ",(typeof this.settings.file_dialog_start_handler==="function").toString(),"\n","\t","file_queued_handler assigned:       ",(typeof this.settings.file_queued_handler==="function").toString(),"\n","\t","file_queue_error_handler assigned:  ",(typeof this.settings.file_queue_error_handler==="function").toString(),"\n","\t","upload_start_handler assigned:      ",(typeof this.settings.upload_start_handler==="function").toString(),"\n","\t","upload_progress_handler assigned:   ",(typeof this.settings.upload_progress_handler==="function").toString(),"\n","\t","upload_error_handler assigned:      ",(typeof this.settings.upload_error_handler==="function").toString(),"\n","\t","upload_success_handler assigned:    ",(typeof this.settings.upload_success_handler==="function").toString(),"\n","\t","upload_complete_handler assigned:   ",(typeof this.settings.upload_complete_handler==="function").toString(),"\n","\t","debug_handler assigned:             ",(typeof this.settings.debug_handler==="function").toString(),"\n"].join(""))};SWFUpload.prototype.addSetting=function(b,c,a){if(c==undefined){return(this.settings[b]=a)}else{return(this.settings[b]=c)}};SWFUpload.prototype.getSetting=function(a){if(this.settings[a]!=undefined){return this.settings[a]}return""};SWFUpload.prototype.callFlash=function(functionName,argumentArray){argumentArray=argumentArray||[];var movieElement=this.getMovieElement();var returnValue,returnString;try{returnString=movieElement.CallFunction('<invoke name="'+functionName+'" returntype="javascript">'+__flash__argumentsToXML(argumentArray,0)+"</invoke>");returnValue=eval(returnString)}catch(ex){throw"Call to "+functionName+" failed"}if(returnValue!=undefined&&typeof returnValue.post==="object"){returnValue=this.unescapeFilePostParams(returnValue)}return returnValue};SWFUpload.prototype.selectFile=function(){this.callFlash("SelectFile")};SWFUpload.prototype.selectFiles=function(){this.callFlash("SelectFiles")};SWFUpload.prototype.startUpload=function(a){this.callFlash("StartUpload",[a])};SWFUpload.prototype.cancelUpload=function(a,b){if(b!==false){b=true}this.callFlash("CancelUpload",[a,b])};SWFUpload.prototype.stopUpload=function(){this.callFlash("StopUpload")};SWFUpload.prototype.getStats=function(){return this.callFlash("GetStats")};SWFUpload.prototype.setStats=function(a){this.callFlash("SetStats",[a])};SWFUpload.prototype.getFile=function(a){if(typeof(a)==="number"){return this.callFlash("GetFileByIndex",[a])}else{return this.callFlash("GetFile",[a])}};SWFUpload.prototype.addFileParam=function(a,b,c){return this.callFlash("AddFileParam",[a,b,c])};SWFUpload.prototype.removeFileParam=function(a,b){this.callFlash("RemoveFileParam",[a,b])};SWFUpload.prototype.setUploadURL=function(a){this.settings.upload_url=a.toString();this.callFlash("SetUploadURL",[a])};SWFUpload.prototype.setPostParams=function(a){this.settings.post_params=a;this.callFlash("SetPostParams",[a])};SWFUpload.prototype.addPostParam=function(a,b){this.settings.post_params[a]=b;this.callFlash("SetPostParams",[this.settings.post_params])};SWFUpload.prototype.removePostParam=function(a){delete this.settings.post_params[a];this.callFlash("SetPostParams",[this.settings.post_params])};SWFUpload.prototype.setFileTypes=function(a,b){this.settings.file_types=a;this.settings.file_types_description=b;this.callFlash("SetFileTypes",[a,b])};SWFUpload.prototype.setFileSizeLimit=function(a){this.settings.file_size_limit=a;this.callFlash("SetFileSizeLimit",[a])};SWFUpload.prototype.setFileUploadLimit=function(a){this.settings.file_upload_limit=a;this.callFlash("SetFileUploadLimit",[a])};SWFUpload.prototype.setFileQueueLimit=function(a){this.settings.file_queue_limit=a;this.callFlash("SetFileQueueLimit",[a])};SWFUpload.prototype.setFilePostName=function(a){this.settings.file_post_name=a;this.callFlash("SetFilePostName",[a])};SWFUpload.prototype.setUseQueryString=function(a){this.settings.use_query_string=a;this.callFlash("SetUseQueryString",[a])};SWFUpload.prototype.setRequeueOnError=function(a){this.settings.requeue_on_error=a;this.callFlash("SetRequeueOnError",[a])};SWFUpload.prototype.setHTTPSuccess=function(a){if(typeof a==="string"){a=a.replace(" ","").split(",")}this.settings.http_success=a;this.callFlash("SetHTTPSuccess",[a])};SWFUpload.prototype.setAssumeSuccessTimeout=function(a){this.settings.assume_success_timeout=a;this.callFlash("SetAssumeSuccessTimeout",[a])};SWFUpload.prototype.setDebugEnabled=function(a){this.settings.debug_enabled=a;this.callFlash("SetDebugEnabled",[a])};SWFUpload.prototype.setButtonImageURL=function(a){if(a==undefined){a=""}this.settings.button_image_url=a;this.callFlash("SetButtonImageURL",[a])};SWFUpload.prototype.setButtonDimensions=function(c,a){this.settings.button_width=c;this.settings.button_height=a;var b=this.getMovieElement();if(b!=undefined){b.style.width=c+"px";b.style.height=a+"px"}this.callFlash("SetButtonDimensions",[c,a])};SWFUpload.prototype.setButtonText=function(a){this.settings.button_text=a;this.callFlash("SetButtonText",[a])};SWFUpload.prototype.setButtonTextPadding=function(b,a){this.settings.button_text_top_padding=a;this.settings.button_text_left_padding=b;this.callFlash("SetButtonTextPadding",[b,a])};SWFUpload.prototype.setButtonTextStyle=function(a){this.settings.button_text_style=a;this.callFlash("SetButtonTextStyle",[a])};SWFUpload.prototype.setButtonDisabled=function(a){this.settings.button_disabled=a;this.callFlash("SetButtonDisabled",[a])};SWFUpload.prototype.setButtonAction=function(a){this.settings.button_action=a;this.callFlash("SetButtonAction",[a])};SWFUpload.prototype.setButtonCursor=function(a){this.settings.button_cursor=a;this.callFlash("SetButtonCursor",[a])};SWFUpload.prototype.queueEvent=function(b,c){if(c==undefined){c=[]}else{if(!(c instanceof Array)){c=[c]}}var a=this;if(typeof this.settings[b]==="function"){this.eventQueue.push(function(){this.settings[b].apply(this,c)});setTimeout(function(){a.executeNextEvent()},0)}else{if(this.settings[b]!==null){throw"Event handler "+b+" is unknown or is not a function"}}};SWFUpload.prototype.executeNextEvent=function(){var a=this.eventQueue?this.eventQueue.shift():null;if(typeof(a)==="function"){a.apply(this)}};SWFUpload.prototype.unescapeFilePostParams=function(c){var e=/[$]([0-9a-f]{4})/i;var f={};var d;if(c!=undefined){for(var a in c.post){if(c.post.hasOwnProperty(a)){d=a;var b;while((b=e.exec(d))!==null){d=d.replace(b[0],String.fromCharCode(parseInt("0x"+b[1],16)))}f[d]=c.post[a]}}c.post=f}return c};SWFUpload.prototype.testExternalInterface=function(){try{return this.callFlash("TestExternalInterface")}catch(a){return false}};SWFUpload.prototype.flashReady=function(){var a=this.getMovieElement();if(!a){this.debug("Flash called back ready but the flash movie can't be found.");return}this.cleanUp(a);this.queueEvent("swfupload_loaded_handler")};SWFUpload.prototype.cleanUp=function(a){try{if(this.movieElement&&typeof(a.CallFunction)==="unknown"){this.debug("Removing Flash functions hooks (this should only run in IE and should prevent memory leaks)");for(var c in a){try{if(typeof(a[c])==="function"){a[c]=null}}catch(b){}}}}catch(d){}window.__flash__removeCallback=function(e,f){try{if(e){e[f]=null}}catch(g){}}};SWFUpload.prototype.fileDialogStart=function(){this.queueEvent("file_dialog_start_handler")};SWFUpload.prototype.fileQueued=function(a){a=this.unescapeFilePostParams(a);this.queueEvent("file_queued_handler",a)};SWFUpload.prototype.fileQueueError=function(a,c,b){a=this.unescapeFilePostParams(a);this.queueEvent("file_queue_error_handler",[a,c,b])};SWFUpload.prototype.fileDialogComplete=function(b,c,a){this.queueEvent("file_dialog_complete_handler",[b,c,a])};SWFUpload.prototype.uploadStart=function(a){a=this.unescapeFilePostParams(a);this.queueEvent("return_upload_start_handler",a)};SWFUpload.prototype.returnUploadStart=function(a){var b;if(typeof this.settings.upload_start_handler==="function"){a=this.unescapeFilePostParams(a);b=this.settings.upload_start_handler.call(this,a)}else{if(this.settings.upload_start_handler!=undefined){throw"upload_start_handler must be a function"}}if(b===undefined){b=true}b=!!b;this.callFlash("ReturnUploadStart",[b])};SWFUpload.prototype.uploadProgress=function(a,c,b){a=this.unescapeFilePostParams(a);this.queueEvent("upload_progress_handler",[a,c,b])};SWFUpload.prototype.uploadError=function(a,c,b){a=this.unescapeFilePostParams(a);this.queueEvent("upload_error_handler",[a,c,b])};SWFUpload.prototype.uploadSuccess=function(b,a,c){b=this.unescapeFilePostParams(b);this.queueEvent("upload_success_handler",[b,a,c])};SWFUpload.prototype.uploadComplete=function(a){a=this.unescapeFilePostParams(a);this.queueEvent("upload_complete_handler",a)};SWFUpload.prototype.debug=function(a){this.queueEvent("debug_handler",a)};SWFUpload.prototype.debugMessage=function(c){if(this.settings.debug){var a,d=[];if(typeof c==="object"&&typeof c.name==="string"&&typeof c.message==="string"){for(var b in c){if(c.hasOwnProperty(b)){d.push(b+": "+c[b])}}a=d.join("\n")||"";d=a.split("\n");a="EXCEPTION: "+d.join("\nEXCEPTION: ");SWFUpload.Console.writeLine(a)}else{SWFUpload.Console.writeLine(c)}}};SWFUpload.Console={};SWFUpload.Console.writeLine=function(d){var b,a;try{b=document.getElementById("SWFUpload_Console");if(!b){a=document.createElement("form");document.getElementsByTagName("body")[0].appendChild(a);b=document.createElement("textarea");b.id="SWFUpload_Console";b.style.fontFamily="monospace";b.setAttribute("wrap","off");b.wrap="off";b.style.overflow="auto";b.style.width="700px";b.style.height="350px";b.style.margin="5px";a.appendChild(b)}b.value+=d+"\n";b.scrollTop=b.scrollHeight-b.clientHeight}catch(c){alert("Exception: "+c.name+" Message: "+c.message)}};

/*
Uploadify v3.1.1
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

(function($) {

    // These methods can be called by adding them as the first argument in the uploadify plugin call
    var methods = {

        init : function(options, swfUploadOptions) {
            
            return this.each(function() {

                // Create a reference to the jQuery DOM object
                var $this = $(this);

                // Clone the original DOM object
                var $clone = $this.clone();

                // Setup the default options
                var settings = $.extend({
                    // Required Settings
                    id       : $this.attr('id'), // The ID of the DOM object
                    swf      : 'uploadify.swf',  // The path to the uploadify SWF file
                    uploader : 'uploadify.php',  // The path to the server-side upload script
                    
                    // Options
                    auto            : true,               // Automatically upload files when added to the queue
                    buttonClass     : '',                 // A class name to add to the browse button DOM object
                    buttonCursor    : 'hand',             // The cursor to use with the browse button
                    buttonImage     : null,               // (String or null) The path to an image to use for the Flash browse button if not using CSS to style the button
                    buttonText      : 'SELECT FILES',     // The text to use for the browse button
                    checkExisting   : false,              // The path to a server-side script that checks for existing files on the server
                    debug           : false,              // Turn on swfUpload debugging mode
                    fileObjName     : 'Filedata',         // The name of the file object to use in your server-side script
                    fileSizeLimit   : 0,                  // The maximum size of an uploadable file in KB (Accepts units B KB MB GB if string, 0 for no limit)
                    fileTypeDesc    : 'All Files',        // The description for file types in the browse dialog
                    fileTypeExts    : '*.*',              // Allowed extensions in the browse dialog (server-side validation should also be used)
                    height          : 30,                 // The height of the browse button
                    method          : 'post',             // The method to use when sending files to the server-side upload script
                    multi           : true,               // Allow multiple file selection in the browse dialog
                    formData        : {},                 // An object with additional data to send to the server-side upload script with every file upload
                    preventCaching  : true,               // Adds a random value to the Flash URL to prevent caching of it (conflicts with existing parameters)
                    progressData    : 'percentage',       // ('percentage' or 'speed') Data to show in the queue item during a file upload
                    queueID         : false,              // The ID of the DOM object to use as a file queue (without the #)
                    queueSizeLimit  : 999,                // The maximum number of files that can be in the queue at one time
                    removeCompleted : true,               // Remove queue items from the queue when they are done uploading
                    removeTimeout   : 3,                  // The delay in seconds before removing a queue item if removeCompleted is set to true
                    requeueErrors   : false,              // Keep errored files in the queue and keep trying to upload them
                    successTimeout  : 30,                 // The number of seconds to wait for Flash to detect the server's response after the file has finished uploading
                    uploadLimit     : 0,                  // The maximum number of files you can upload
                    width           : 120,                // The width of the browse button
                    
                    // Events
                    overrideEvents   : []             // (Array) A list of default event handlers to skip
                    /*
                    onCancel         // Triggered when a file is cancelled from the queue
                    onClearQueue     // Triggered during the 'clear queue' method
                    onDestroy        // Triggered when the uploadify object is destroyed
                    onDialogClose    // Triggered when the browse dialog is closed
                    onDialogOpen     // Triggered when the browse dialog is opened
                    onDisable        // Triggered when the browse button gets disabled
                    onEnable         // Triggered when the browse button gets enabled
                    onFallback       // Triggered is Flash is not detected    
                    onInit           // Triggered when Uploadify is initialized
                    onQueueComplete  // Triggered when all files in the queue have been uploaded
                    onSelectError    // Triggered when an error occurs while selecting a file (file size, queue size limit, etc.)
                    onSelect         // Triggered for each file that is selected
                    onSWFReady       // Triggered when the SWF button is loaded
                    onUploadComplete // Triggered when a file upload completes (success or error)
                    onUploadError    // Triggered when a file upload returns an error
                    onUploadSuccess  // Triggered when a file is uploaded successfully
                    onUploadProgress // Triggered every time a file progress is updated
                    onUploadStart    // Triggered immediately before a file upload starts
                    */
                }, options);

                // Prepare settings for SWFUpload
                var swfUploadSettings = {
                    assume_success_timeout   : settings.successTimeout,
                    button_placeholder_id    : settings.id,
                    button_width             : settings.width,
                    button_height            : settings.height,
                    button_text              : null,
                    button_text_style        : null,
                    button_text_top_padding  : 0,
                    button_text_left_padding : 0,
                    button_action            : (settings.multi ? SWFUpload.BUTTON_ACTION.SELECT_FILES : SWFUpload.BUTTON_ACTION.SELECT_FILE),
                    button_disabled          : false,
                    button_cursor            : (settings.buttonCursor == 'arrow' ? SWFUpload.CURSOR.ARROW : SWFUpload.CURSOR.HAND),
                    button_window_mode       : SWFUpload.WINDOW_MODE.TRANSPARENT,
                    debug                    : settings.debug,                      
                    requeue_on_error         : settings.requeueErrors,
                    file_post_name           : settings.fileObjName,
                    file_size_limit          : settings.fileSizeLimit,
                    file_types               : settings.fileTypeExts,
                    file_types_description   : settings.fileTypeDesc,
                    file_queue_limit         : settings.queueSizeLimit,
                    file_upload_limit        : settings.uploadLimit,
                    flash_url                : settings.swf,                    
                    prevent_swf_caching      : settings.preventCaching,
                    post_params              : settings.formData,
                    upload_url               : settings.uploader,
                    use_query_string         : (settings.method == 'get'),
                    
                    // Event Handlers 
                    file_dialog_complete_handler : handlers.onDialogClose,
                    file_dialog_start_handler    : handlers.onDialogOpen,
                    file_queued_handler          : handlers.onSelect,
                    file_queue_error_handler     : handlers.onSelectError,
                    swfupload_loaded_handler     : settings.onSWFReady,
                    upload_complete_handler      : handlers.onUploadComplete,
                    upload_error_handler         : handlers.onUploadError,
                    upload_progress_handler      : handlers.onUploadProgress,
                    upload_start_handler         : handlers.onUploadStart,
                    upload_success_handler       : handlers.onUploadSuccess
                }

                // Merge the user-defined options with the defaults
                if (swfUploadOptions) {
                    swfUploadSettings = $.extend(swfUploadSettings, swfUploadOptions);
                }
                // Add the user-defined settings to the swfupload object
                swfUploadSettings = $.extend(swfUploadSettings, settings);
                
                // Detect if Flash is available
                var playerVersion  = swfobject.getFlashPlayerVersion();
                var flashInstalled = (playerVersion.major >= 9);

                if (flashInstalled) {
                    // Create the swfUpload instance
                    window['uploadify_' + settings.id] = new SWFUpload(swfUploadSettings);
                    var swfuploadify = window['uploadify_' + settings.id];

                    // Add the SWFUpload object to the elements data object
                    $this.data('uploadify', swfuploadify);
                    
                    // Wrap the instance
                    var $wrapper = $('<div />', {
                        'id'    : settings.id,
                        'class' : 'uploadify',
                        'css'   : {
                                    'height'   : settings.height + 'px',
                                    'width'    : settings.width + 'px'
                                  }
                    });
                    $('#' + swfuploadify.movieName).wrap($wrapper);
                    // Recreate the reference to wrapper
                    $wrapper = $('#' + settings.id);
                    // Add the data object to the wrapper 
                    $wrapper.data('uploadify', swfuploadify);

                    // Create the button
                    var $button = $('<div />', {
                        'id'    : settings.id + '-button',
                        'class' : 'uploadify-button ' + settings.buttonClass
                    });
                    if (settings.buttonImage) {
                        $button.css({
                            'background-image' : "url('" + settings.buttonImage + "')",
                            'text-indent'      : '-9999px'
                        });
                    }
                    $button.html('<span class="uploadify-button-text">' + settings.buttonText + '</span>')
                    .css({
                        'height'      : settings.height + 'px',
                        'line-height' : settings.height + 'px',
                        'width'       : settings.width + 'px'
                    });
                    // Append the button to the wrapper
                    $wrapper.append($button);

                    // Adjust the styles of the movie
                    $('#' + swfuploadify.movieName).css({
                        'position' : 'absolute',
                        'z-index'  : 1
                    });
                    
                    // Create the file queue
                    if (!settings.queueID) {
                        var $queue = $('<div />', {
                            'id'    : settings.id + '-queue',
                            'class' : 'uploadify-queue'
                        });
                        $wrapper.after($queue);
                        swfuploadify.settings.queueID      = settings.id + '-queue';
                        swfuploadify.settings.defaultQueue = true;
                    }
                    
                    // Create some queue related objects and variables
                    swfuploadify.queueData = {
                        files              : {}, // The files in the queue
                        filesSelected      : 0, // The number of files selected in the last select operation
                        filesQueued        : 0, // The number of files added to the queue in the last select operation
                        filesReplaced      : 0, // The number of files replaced in the last select operation
                        filesCancelled     : 0, // The number of files that were cancelled instead of replaced
                        filesErrored       : 0, // The number of files that caused error in the last select operation
                        uploadsSuccessful  : 0, // The number of files that were successfully uploaded
                        uploadsErrored     : 0, // The number of files that returned errors during upload
                        averageSpeed       : 0, // The average speed of the uploads in KB
                        queueLength        : 0, // The number of files in the queue
                        queueSize          : 0, // The size in bytes of the entire queue
                        uploadSize         : 0, // The size in bytes of the upload queue
                        queueBytesUploaded : 0, // The size in bytes that have been uploaded for the current upload queue
                        uploadQueue        : [], // The files currently to be uploaded
                        errorMsg           : 'Some files were not added to the queue:'
                    };

                    // Save references to all the objects
                    swfuploadify.original = $clone;
                    swfuploadify.wrapper  = $wrapper;
                    swfuploadify.button   = $button;
                    swfuploadify.queue    = $queue;

                    // Call the user-defined init event handler
                    if (settings.onInit) settings.onInit.call($this, swfuploadify);

                } else {

                    // Call the fallback function
                    if (settings.onFallback) settings.onFallback.call($this);

                }
            });

        },

        // Stop a file upload and remove it from the queue 
        cancel : function(fileID, supressEvent) {

            var args = arguments;

            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify'),
                    settings     = swfuploadify.settings,
                    delay        = -1;

                if (args[0]) {
                    // Clear the queue
                    if (args[0] == '*') {
                        var queueItemCount = swfuploadify.queueData.queueLength;
                        $('#' + settings.queueID).find('.uploadify-queue-item').each(function() {
                            delay++;
                            if (args[1] === true) {
                                swfuploadify.cancelUpload($(this).attr('id'), false);
                            } else {
                                swfuploadify.cancelUpload($(this).attr('id'));
                            }
                            $(this).find('.data').removeClass('data').html(' - Cancelled');
                            $(this).find('.uploadify-progress-bar').remove();
                            $(this).delay(1000 + 100 * delay).fadeOut(500, function() {
                                $(this).remove();
                            });
                        });
                        swfuploadify.queueData.queueSize   = 0;
                        swfuploadify.queueData.queueLength = 0;
                        // Trigger the onClearQueue event
                        if (settings.onClearQueue) settings.onClearQueue.call($this, queueItemCount);
                    } else {
                        for (var n = 0; n < args.length; n++) {
                            swfuploadify.cancelUpload(args[n]);
                            $('#' + args[n]).find('.data').removeClass('data').html(' - Cancelled');
                            $('#' + args[n]).find('.uploadify-progress-bar').remove();
                            $('#' + args[n]).delay(1000 + 100 * n).fadeOut(500, function() {
                                $(this).remove();
                            });
                        }
                    }
                } else {
                    var item = $('#' + settings.queueID).find('.uploadify-queue-item').get(0);
                    $item = $(item);
                    swfuploadify.cancelUpload($item.attr('id'));
                    $item.find('.data').removeClass('data').html(' - Cancelled');
                    $item.find('.uploadify-progress-bar').remove();
                    $item.delay(1000).fadeOut(500, function() {
                        $(this).remove();
                    });
                }
            });

        },

        // Revert the DOM object back to its original state
        destroy : function() {

            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify'),
                    settings     = swfuploadify.settings;

                // Destroy the SWF object and 
                swfuploadify.destroy();
                
                // Destroy the queue
                if (settings.defaultQueue) {
                    $('#' + settings.queueID).remove();
                }
                
                // Reload the original DOM element
                $('#' + settings.id).replaceWith(swfuploadify.original);

                // Call the user-defined event handler
                if (settings.onDestroy) settings.onDestroy.call(this);

                delete swfuploadify;
            });

        },

        // Disable the select button
        disable : function(isDisabled) {
            
            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify'),
                    settings     = swfuploadify.settings;

                // Call the user-defined event handlers
                if (isDisabled) {
                    swfuploadify.button.addClass('disabled');
                    if (settings.onDisable) settings.onDisable.call(this);
                } else {
                    swfuploadify.button.removeClass('disabled');
                    if (settings.onEnable) settings.onEnable.call(this);
                }

                // Enable/disable the browse button
                swfuploadify.setButtonDisabled(isDisabled);
            });

        },

        // Get or set the settings data
        settings : function(name, value, resetObjects) {

            var args        = arguments;
            var returnValue = value;

            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify'),
                    settings     = swfuploadify.settings;

                if (typeof(args[0]) == 'object') {
                    for (var n in value) {
                        setData(n,value[n]);
                    }
                }
                if (args.length === 1) {
                    returnValue =  settings[name];
                } else {
                    switch (name) {
                        case 'uploader':
                            swfuploadify.setUploadURL(value);
                            break;
                        case 'formData':
                            if (!resetObjects) {
                                value = $.extend(settings.formData, value);
                            }
                            swfuploadify.setPostParams(settings.formData);
                            break;
                        case 'method':
                            if (value == 'get') {
                                swfuploadify.setUseQueryString(true);
                            } else {
                                swfuploadify.setUseQueryString(false);
                            }
                            break;
                        case 'fileObjName':
                            swfuploadify.setFilePostName(value);
                            break;
                        case 'fileTypeExts':
                            swfuploadify.setFileTypes(value, settings.fileTypeDesc);
                            break;
                        case 'fileTypeDesc':
                            swfuploadify.setFileTypes(settings.fileTypeExts, value);
                            break;
                        case 'fileSizeLimit':
                            swfuploadify.setFileSizeLimit(value);
                            break;
                        case 'uploadLimit':
                            swfuploadify.setFileUploadLimit(value);
                            break;
                        case 'queueSizeLimit':
                            swfuploadify.setFileQueueLimit(value);
                            break;
                        case 'buttonImage':
                            swfuploadify.button.css('background-image', settingValue);
                            break;
                        case 'buttonCursor':
                            if (value == 'arrow') {
                                swfuploadify.setButtonCursor(SWFUpload.CURSOR.ARROW);
                            } else {
                                swfuploadify.setButtonCursor(SWFUpload.CURSOR.HAND);
                            }
                            break;
                        case 'buttonText':
                            $('#' + settings.id + '-button').find('.uploadify-button-text').html(value);
                            break;
                        case 'width':
                            swfuploadify.setButtonDimensions(value, settings.height);
                            break;
                        case 'height':
                            swfuploadify.setButtonDimensions(settings.width, value);
                            break;
                        case 'multi':
                            if (value) {
                                swfuploadify.setButtonAction(SWFUpload.BUTTON_ACTION.SELECT_FILES);
                            } else {
                                swfuploadify.setButtonAction(SWFUpload.BUTTON_ACTION.SELECT_FILE);
                            }
                            break;
                    }
                    settings[name] = value;
                }
            });
            
            if (args.length === 1) {
                return returnValue;
            }

        },

        // Stop the current uploads and requeue what is in progress
        stop : function() {

            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify');

                // Reset the queue information
                swfuploadify.queueData.averageSpeed  = 0;
                swfuploadify.queueData.uploadSize    = 0;
                swfuploadify.queueData.bytesUploaded = 0;
                swfuploadify.queueData.uploadQueue   = [];

                swfuploadify.stopUpload();
            });

        },

        // Start uploading files in the queue
        upload : function() {

            var args = arguments;

            this.each(function() {
                // Create a reference to the jQuery DOM object
                var $this        = $(this),
                    swfuploadify = $this.data('uploadify');

                // Reset the queue information
                swfuploadify.queueData.averageSpeed  = 0;
                swfuploadify.queueData.uploadSize    = 0;
                swfuploadify.queueData.bytesUploaded = 0;
                swfuploadify.queueData.uploadQueue   = [];
                
                // Upload the files
                if (args[0]) {
                    if (args[0] == '*') {
                        swfuploadify.queueData.uploadSize = swfuploadify.queueData.queueSize;
                        swfuploadify.queueData.uploadQueue.push('*');
                        swfuploadify.startUpload();
                    } else {
                        for (var n = 0; n < args.length; n++) {
                            swfuploadify.queueData.uploadSize += swfuploadify.queueData.files[args[n]].size;
                            swfuploadify.queueData.uploadQueue.push(args[n]);
                        }
                        swfuploadify.startUpload(swfuploadify.queueData.uploadQueue.shift());
                    }
                } else {
                    swfuploadify.startUpload();
                }

            });

        }

    }

    // These functions handle all the events that occur with the file uploader
    var handlers = {

        // Triggered when the file dialog is opened
        onDialogOpen : function() {
            // Load the swfupload settings
            var settings = this.settings;

            // Reset some queue info
            this.queueData.errorMsg       = 'Some files were not added to the queue:';
            this.queueData.filesReplaced  = 0;
            this.queueData.filesCancelled = 0;

            // Call the user-defined event handler
            if (settings.onDialogOpen) settings.onDialogOpen.call(this);
        },

        // Triggered when the browse dialog is closed
        onDialogClose :  function(filesSelected, filesQueued, queueLength) {
            // Load the swfupload settings
            var settings = this.settings;

            // Update the queue information
            this.queueData.filesErrored  = filesSelected - filesQueued;
            this.queueData.filesSelected = filesSelected;
            this.queueData.filesQueued   = filesQueued - this.queueData.filesCancelled;
            this.queueData.queueLength   = queueLength;

            // Run the default event handler
            if ($.inArray('onDialogClose', settings.overrideEvents) < 0) {
                if (this.queueData.filesErrored > 0) {
                    alert(this.queueData.errorMsg);
                }
            }

            // Call the user-defined event handler
            if (settings.onDialogClose) settings.onDialogClose.call(this, this.queueData);

            // Upload the files if auto is true
            if (settings.auto) $('#' + settings.id).uploadify('upload', '*');
        },

        // Triggered once for each file added to the queue
        onSelect : function(file) {
            // Load the swfupload settings
            var settings = this.settings;

            // Check if a file with the same name exists in the queue
            var queuedFile = {};
            for (var n in this.queueData.files) {
                queuedFile = this.queueData.files[n];
                if (queuedFile.uploaded != true && queuedFile.name == file.name) {
                    var replaceQueueItem = confirm('The file named "' + file.name + '" is already in the queue.\nDo you want to replace the existing item in the queue?');
                    if (!replaceQueueItem) {
                        this.cancelUpload(file.id);
                        this.queueData.filesCancelled++;
                        return false;
                    } else {
                        $('#' + queuedFile.id).remove();
                        this.cancelUpload(queuedFile.id);
                        this.queueData.filesReplaced++;
                    }
                }
            }

            // Get the size of the file
            var fileSize = Math.round(file.size / 1024);
            var suffix   = 'KB';
            if (fileSize > 1000) {
                fileSize = Math.round(fileSize / 1000);
                suffix   = 'MB';
            }
            var fileSizeParts = fileSize.toString().split('.');
            fileSize = fileSizeParts[0];
            if (fileSizeParts.length > 1) {
                fileSize += '.' + fileSizeParts[1].substr(0,2);
            }
            fileSize += suffix;
            
            // Truncate the filename if it's too long
            var fileName = file.name;
            if (fileName.length > 25) {
                fileName = fileName.substr(0,25) + '...';
            }

            // Run the default event handler
            if ($.inArray('onSelect', settings.overrideEvents) < 0) {
                
                // Add the file item to the queue
                $('#' + settings.queueID).append('<div id="' + file.id + '" class="uploadify-queue-item">\
                    <div class="cancel">\
                        <a href="javascript:$(\'#' + settings.id + '\').uploadify(\'cancel\', \'' + file.id + '\')">X</a>\
                    </div>\
                    <span class="fileName">' + fileName + ' (' + fileSize + ')</span><span class="data"></span>\
                    <div class="uploadify-progress">\
                        <div class="uploadify-progress-bar"><!--Progress Bar--></div>\
                    </div>\
                </div>');
                
            }

            this.queueData.queueSize += file.size;
            this.queueData.files[file.id] = file;

            // Call the user-defined event handler
            if (settings.onSelect) settings.onSelect.apply(this, arguments);
        },

        // Triggered when a file is not added to the queue
        onSelectError : function(file, errorCode, errorMsg) {
            // Load the swfupload settings
            var settings = this.settings;

            // Run the default event handler
            if ($.inArray('onSelectError', settings.overrideEvents) < 0) {
                switch(errorCode) {
                    case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                        if (settings.queueSizeLimit > errorMsg) {
                            this.queueData.errorMsg += '\nThe number of files selected exceeds the remaining upload limit (' + errorMsg + ').';
                        } else {
                            this.queueData.errorMsg += '\nThe number of files selected exceeds the queue size limit (' + settings.queueSizeLimit + ').';
                        }
                        break;
                    case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                        this.queueData.errorMsg += '\nThe file "' + file.name + '" exceeds the size limit (' + settings.fileSizeLimit + ').';
                        break;
                    case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                        this.queueData.errorMsg += '\nThe file "' + file.name + '" is empty.';
                        break;
                    case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                        this.queueData.errorMsg += '\nThe file "' + file.name + '" is not an accepted file type (' + settings.fileTypeDesc + ').';
                        break;
                }
            }
            if (errorCode != SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
                delete this.queueData.files[file.id];
            }

            // Call the user-defined event handler
            if (settings.onSelectError) settings.onSelectError.apply(this, arguments);
        },

        // Triggered when all the files in the queue have been processed
        onQueueComplete : function() {
            if (this.settings.onQueueComplete) this.settings.onQueueComplete.call(this, this.settings.queueData);
        },

        // Triggered when a file upload successfully completes
        onUploadComplete : function(file) {
            // Load the swfupload settings
            var settings     = this.settings,
                swfuploadify = this;

            // Check if all the files have completed uploading
            var stats = this.getStats();
            this.queueData.queueLength = stats.files_queued;
            if (this.queueData.uploadQueue[0] == '*') {
                if (this.queueData.queueLength > 0) {
                    this.startUpload();
                } else {
                    this.queueData.uploadQueue = [];

                    // Call the user-defined event handler for queue complete
                    if (settings.onQueueComplete) settings.onQueueComplete.call(this, this.queueData);
                }
            } else {
                if (this.queueData.uploadQueue.length > 0) {
                    this.startUpload(this.queueData.uploadQueue.shift());
                } else {
                    this.queueData.uploadQueue = [];

                    // Call the user-defined event handler for queue complete
                    if (settings.onQueueComplete) settings.onQueueComplete.call(this, this.queueData);
                }
            }

            // Call the default event handler
            if ($.inArray('onUploadComplete', settings.overrideEvents) < 0) {
                if (settings.removeCompleted) {
                    switch (file.filestatus) {
                        case SWFUpload.FILE_STATUS.COMPLETE:
                            setTimeout(function() { 
                                if ($('#' + file.id)) {
                                    swfuploadify.queueData.queueSize   -= file.size;
                                    swfuploadify.queueData.queueLength -= 1;
                                    delete swfuploadify.queueData.files[file.id]
                                    $('#' + file.id).fadeOut(500, function() {
                                        $(this).remove();
                                    });
                                }
                            }, settings.removeTimeout * 1000);
                            break;
                        case SWFUpload.FILE_STATUS.ERROR:
                            if (!settings.requeueErrors) {
                                setTimeout(function() {
                                    if ($('#' + file.id)) {
                                        swfuploadify.queueData.queueSize   -= file.size;
                                        swfuploadify.queueData.queueLength -= 1;
                                        delete swfuploadify.queueData.files[file.id];
                                        $('#' + file.id).fadeOut(500, function() {
                                            $(this).remove();
                                        });
                                    }
                                }, settings.removeTimeout * 1000);
                            }
                            break;
                    }
                } else {
                    file.uploaded = true;
                }
            }

            // Call the user-defined event handler
            if (settings.onUploadComplete) settings.onUploadComplete.call(this, file);
        },

        // Triggered when a file upload returns an error
        onUploadError : function(file, errorCode, errorMsg) {
            // Load the swfupload settings
            var settings = this.settings;

            // Set the error string
            var errorString = 'Error';
            switch(errorCode) {
                case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                    errorString = 'HTTP Error (' + errorMsg + ')';
                    break;
                case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                    errorString = 'Missing Upload URL';
                    break;
                case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                    errorString = 'IO Error';
                    break;
                case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                    errorString = 'Security Error';
                    break;
                case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                    alert('The upload limit has been reached (' + errorMsg + ').');
                    errorString = 'Exceeds Upload Limit';
                    break;
                case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                    errorString = 'Failed';
                    break;
                case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
                    break;
                case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                    errorString = 'Validation Error';
                    break;
                case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                    errorString = 'Cancelled';
                    this.queueData.queueSize   -= file.size;
                    this.queueData.queueLength -= 1;
                    if (file.status == SWFUpload.FILE_STATUS.IN_PROGRESS || $.inArray(file.id, this.queueData.uploadQueue) >= 0) {
                        this.queueData.uploadSize -= file.size;
                    }
                    // Trigger the onCancel event
                    if (settings.onCancel) settings.onCancel.call(this, file);
                    delete this.queueData.files[file.id];
                    break;
                case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                    errorString = 'Stopped';
                    break;
            }

            // Call the default event handler
            if ($.inArray('onUploadError', settings.overrideEvents) < 0) {

                if (errorCode != SWFUpload.UPLOAD_ERROR.FILE_CANCELLED && errorCode != SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED) {
                    $('#' + file.id).addClass('uploadify-error');
                }

                // Reset the progress bar
                $('#' + file.id).find('.uploadify-progress-bar').css('width','1px');

                // Add the error message to the queue item
                if (errorCode != SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND && file.status != SWFUpload.FILE_STATUS.COMPLETE) {
                    $('#' + file.id).find('.data').html(' - ' + errorString);
                }
            }

            var stats = this.getStats();
            this.queueData.uploadsErrored = stats.upload_errors;

            // Call the user-defined event handler
            if (settings.onUploadError) settings.onUploadError.call(this, file, errorCode, errorMsg, errorString);
        },

        // Triggered periodically during a file upload
        onUploadProgress : function(file, fileBytesLoaded, fileTotalBytes) {
            // Load the swfupload settings
            var settings = this.settings;

            // Setup all the variables
            var timer            = new Date();
            var newTime          = timer.getTime();
            var lapsedTime       = newTime - this.timer;
            if (lapsedTime > 500) {
                this.timer = newTime;
            }
            var lapsedBytes      = fileBytesLoaded - this.bytesLoaded;
            this.bytesLoaded     = fileBytesLoaded;
            var queueBytesLoaded = this.queueData.queueBytesUploaded + fileBytesLoaded;
            var percentage       = Math.round(fileBytesLoaded / fileTotalBytes * 100);
            
            // Calculate the average speed
            var suffix = 'KB/s';
            var mbs = 0;
            var kbs = (lapsedBytes / 1024) / (lapsedTime / 1000);
                kbs = Math.floor(kbs * 10) / 10;
            if (this.queueData.averageSpeed > 0) {
                this.queueData.averageSpeed = Math.floor((this.queueData.averageSpeed + kbs) / 2);
            } else {
                this.queueData.averageSpeed = Math.floor(kbs);
            }
            if (kbs > 1000) {
                mbs = (kbs * .001);
                this.queueData.averageSpeed = Math.floor(mbs);
                suffix = 'MB/s';
            }
            
            // Call the default event handler
            if ($.inArray('onUploadProgress', settings.overrideEvents) < 0) {
                if (settings.progressData == 'percentage') {
                    $('#' + file.id).find('.data').html(' - ' + percentage + '%');
                } else if (settings.progressData == 'speed' && lapsedTime > 500) {
                    $('#' + file.id).find('.data').html(' - ' + this.queueData.averageSpeed + suffix);
                }
                $('#' + file.id).find('.uploadify-progress-bar').css('width', percentage + '%');
            }

            // Call the user-defined event handler
            if (settings.onUploadProgress) settings.onUploadProgress.call(this, file, fileBytesLoaded, fileTotalBytes, queueBytesLoaded, this.queueData.uploadSize);
        },

        // Triggered right before a file is uploaded
        onUploadStart : function(file) {
            // Load the swfupload settings
            var settings = this.settings;

            var timer        = new Date();
            this.timer       = timer.getTime();
            this.bytesLoaded = 0;
            if (this.queueData.uploadQueue.length == 0) {
                this.queueData.uploadSize = file.size;
            }
            if (settings.checkExisting) {
                $.ajax({
                    type    : 'POST',
                    async   : false,
                    url     : settings.checkExisting,
                    data    : {filename: file.name},
                    success : function(data) {
                        if (data == 1) {
                            var overwrite = confirm('A file with the name "' + file.name + '" already exists on the server.\nWould you like to replace the existing file?');
                            if (!overwrite) {
                                this.cancelUpload(file.id);
                                $('#' + file.id).remove();
                                if (this.queueData.uploadQueue.length > 0 && this.queueData.queueLength > 0) {
                                    if (this.queueData.uploadQueue[0] == '*') {
                                        this.startUpload();
                                    } else {
                                        this.startUpload(this.queueData.uploadQueue.shift());
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Call the user-defined event handler
            if (settings.onUploadStart) settings.onUploadStart.call(this, file); 
        },

        // Triggered when a file upload returns a successful code
        onUploadSuccess : function(file, data, response) {
            // Load the swfupload settings
            var settings = this.settings;
            var stats    = this.getStats();
            this.queueData.uploadsSuccessful = stats.successful_uploads;
            this.queueData.queueBytesUploaded += file.size;

            // Call the default event handler
            if ($.inArray('onUploadSuccess', settings.overrideEvents) < 0) {
                $('#' + file.id).find('.data').html(' - Complete');
            }

            // Call the user-defined event handler
            if (settings.onUploadSuccess) settings.onUploadSuccess.call(this, file, data, response); 
        }

    }

    $.fn.uploadify = function(method) {

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('The method ' + method + ' does not exist in $.uploadify');
        }

    }


})($);

/*
  textfill
 @name      jquery.textfill.js
 @author    Russ Painter
 @author    Yu-Jie Lin
 @version   0.3.2
 @date      2013-02-09
 @copyright (c) 2012-2013 Yu-Jie Lin
 @copyright (c) 2009 Russ Painter
 @license   MIT License
 @homepage  https://github.com/jquery-textfill/jquery-textfill
 @example   http://jquery-textfill.github.com/jquery-textfill/Example.htm
*/
/*
(function(g){g.fn.textfill=function(n){function l(c,a,e,h,f,j){function d(a,b){var c=" / ";a>b?c=" > ":a==b&&(c=" = ");return c}b.debug&&console.debug(c+"font: "+a.css("font-size")+", H: "+a.height()+d(a.height(),e)+e+", W: "+a.width()+d(a.width(),h)+h+", minFontPixels: "+f+", maxFontPixels: "+j)}function m(b,a,e,h,f,j,d,k){for(l(b+": ",a,f,j,d,k);d<k-1;){var g=Math.floor((d+k)/2);a.css("font-size",g);if(e.call(a)<=h){if(d=g,e.call(a)==h)break}else k=g;l(b+": ",a,f,j,d,k)}a.css("font-size",k);e.call(a)<=
h&&(d=k,l(b+"* ",a,f,j,d,k));return d}var b=jQuery.extend({debug:!1,maxFontPixels:40,minFontPixels:4,innerTag:"span",widthOnly:!1,callback:null,complete:null,explicitWidth:null,explicitHeight:null},n);this.each(function(){var c=g(b.innerTag+":visible:first",this),a=b.explicitHeight||g(this).height(),e=b.explicitWidth||g(this).width(),h=c.css("font-size");b.debug&&(console.log("Opts: ",b),console.log("Vars: maxHeight: "+a+", maxWidth: "+e));var f=b.minFontPixels,j=0>=b.maxFontPixels?a:b.maxFontPixels,
d=void 0;b.widthOnly||(d=m("H",c,g.fn.height,a,a,e,f,j));f=m("W",c,g.fn.width,e,a,e,f,j);b.widthOnly?c.css("font-size",f):c.css("font-size",Math.min(d,f));b.debug&&console.debug("Final: "+c.css("font-size"));(c.width()>e||c.height()>a)&&c.css("font-size",h);b.callback&&b.callback(this)});b.complete&&b.complete(this);return this}})(jQuery);
*/

/*!
 * qTip2 - Pretty powerful tooltips - v2.0.1-5-g
 * http://qtip2.com
 *
 * Copyright (c) 2013 Craig Michael Thompson
 * Released under the MIT, GPL licenses
 * http://jquery.org/license
 *
 * Date: Wed Feb 6 2013 08:45 GMT+0000
 * Plugins: svg ajax tips modal viewport imagemap ie6
 * Styles: basic css3
 */

/*jslint browser: true, onevar: true, undef: true, nomen: true, bitwise: true, regexp: true, newcap: true, immed: true, strict: true */
/*global window: false, jQuery: false, console: false, define: false */

/* Cache window, document, undefined */
(function( window, document, undefined ) {

// Uses AMD or browser globals to create a jQuery plugin.
(function( factory ) {
    "use strict";
    if(typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    }
    else if(jQuery && !jQuery.fn.qtip) {
        factory(jQuery);
    }
}
(function($) {
    /* This currently causes issues with Safari 6, so for it's disabled */
    //"use strict"; // (Dis)able ECMAScript "strict" operation for this function. See more: http://ejohn.org/blog/ecmascript-5-strict-mode-json-and-more/

    // Munge the primitives - Paul Irish tip
    var TRUE = true,
        FALSE = false,
        NULL = null,

        // Side names and other stuff
        X = 'x', Y = 'y',
        WIDTH = 'width',
        HEIGHT = 'height',
        TOP = 'top',
        LEFT = 'left',
        BOTTOM = 'bottom',
        RIGHT = 'right',
        CENTER = 'center',
        FLIP = 'flip',
        FLIPINVERT = 'flipinvert',
        SHIFT = 'shift',

        // Shortcut vars
        QTIP, PLUGINS, MOUSE,
        NAMESPACE = 'qtip',
        usedIDs = {},
        widget = ['ui-widget', 'ui-tooltip'],
        selector = 'div.qtip.'+NAMESPACE,
        defaultClass = NAMESPACE + '-default',
        focusClass = NAMESPACE + '-focus',
        hoverClass = NAMESPACE + '-hover',
        replaceSuffix = '_replacedByqTip',
        oldtitle = 'oldtitle',
        trackingBound;

    // Store mouse coordinates
    function storeMouse(event)
    {
        MOUSE = {
            pageX: event.pageX,
            pageY: event.pageY,
            type: 'mousemove',
            scrollX: window.pageXOffset || document.body.scrollLeft || document.documentElement.scrollLeft,
            scrollY: window.pageYOffset || document.body.scrollTop || document.documentElement.scrollTop
        };
    }
// Option object sanitizer
function sanitizeOptions(opts)
{
    var invalid = function(a) { return a === NULL || 'object' !== typeof a; },
        invalidContent = function(c) { return !$.isFunction(c) && ((!c && !c.attr) || c.length < 1 || ('object' === typeof c && !c.jquery && !c.then)); };

    if(!opts || 'object' !== typeof opts) { return FALSE; }

    if(invalid(opts.metadata)) {
        opts.metadata = { type: opts.metadata };
    }

    if('content' in opts) {
        if(invalid(opts.content) || opts.content.jquery) {
            opts.content = { text: opts.content };
        }

        if(invalidContent(opts.content.text || FALSE)) {
            opts.content.text = FALSE;
        }

        if('title' in opts.content) {
            if(invalid(opts.content.title)) {
                opts.content.title = { text: opts.content.title };
            }

            if(invalidContent(opts.content.title.text || FALSE)) {
                opts.content.title.text = FALSE;
            }
        }
    }

    if('position' in opts && invalid(opts.position)) {
        opts.position = { my: opts.position, at: opts.position };
    }

    if('show' in opts && invalid(opts.show)) {
        opts.show = opts.show.jquery ? { target: opts.show } : { event: opts.show };
    }

    if('hide' in opts && invalid(opts.hide)) {
        opts.hide = opts.hide.jquery ? { target: opts.hide } : { event: opts.hide };
    }

    if('style' in opts && invalid(opts.style)) {
        opts.style = { classes: opts.style };
    }

    // Sanitize plugin options
    $.each(PLUGINS, function() {
        if(this.sanitize) { this.sanitize(opts); }
    });

    return opts;
}

/*
* Core plugin implementation
*/
function QTip(target, options, id, attr)
{
    // Declare this reference
    var self = this,
        docBody = document.body,
        tooltipID = NAMESPACE + '-' + id,
        isPositioning = 0,
        isDrawing = 0,
        tooltip = $(),
        namespace = '.qtip-' + id,
        disabledClass = 'qtip-disabled',
        elements, cache;

    // Setup class attributes
    self.id = id;
    self.rendered = FALSE;
    self.destroyed = FALSE;
    self.elements = elements = { target: target };
    self.timers = { img: {} };
    self.options = options;
    self.checks = {};
    self.plugins = {};
    self.cache = cache = {
        event: {},
        target: $(),
        disabled: FALSE,
        attr: attr,
        onTarget: FALSE,
        lastClass: ''
    };

    function convertNotation(notation)
    {
        var i = 0, obj, option = options,

        // Split notation into array
        levels = notation.split('.');

        // Loop through
        while( option = option[ levels[i++] ] ) {
            if(i < levels.length) { obj = option; }
        }

        return [obj || options, levels.pop()];
    }

    function createWidgetClass(cls)
    {
        return widget.concat('').join(cls ? '-'+cls+' ' : ' ');
    }

    function setWidget()
    {
        var on = options.style.widget,
            disabled = tooltip.hasClass(disabledClass);

        tooltip.removeClass(disabledClass);
        disabledClass = on ? 'ui-state-disabled' : 'qtip-disabled';
        tooltip.toggleClass(disabledClass, disabled);

        tooltip.toggleClass('ui-helper-reset '+createWidgetClass(), on).toggleClass(defaultClass, options.style.def && !on);
        
        if(elements.content) {
            elements.content.toggleClass( createWidgetClass('content'), on);
        }
        if(elements.titlebar) {
            elements.titlebar.toggleClass( createWidgetClass('header'), on);
        }
        if(elements.button) {
            elements.button.toggleClass(NAMESPACE+'-icon', !on);
        }
    }

    function removeTitle(reposition)
    {
        if(elements.title) {
            elements.titlebar.remove();
            elements.titlebar = elements.title = elements.button = NULL;

            // Reposition if enabled
            if(reposition !== FALSE) { self.reposition(); }
        }
    }

    function createButton()
    {
        var button = options.content.title.button,
            isString = typeof button === 'string',
            close = isString ? button : 'Close tooltip';

        if(elements.button) { elements.button.remove(); }

        // Use custom button if one was supplied by user, else use default
        if(button.jquery) {
            elements.button = button;
        }
        else {
            elements.button = $('<a />', {
                'class': 'qtip-close ' + (options.style.widget ? '' : NAMESPACE+'-icon'),
                'title': close,
                'aria-label': close
            })
            .prepend(
                $('<span />', {
                    'class': 'ui-icon ui-icon-close',
                    'html': '&times;'
                })
            );
        }

        // Create button and setup attributes
        elements.button.appendTo(elements.titlebar || tooltip)
            .attr('role', 'button')
            .click(function(event) {
                if(!tooltip.hasClass(disabledClass)) { self.hide(event); }
                return FALSE;
            });
    }

    function createTitle()
    {
        var id = tooltipID+'-title';

        // Destroy previous title element, if present
        if(elements.titlebar) { removeTitle(); }

        // Create title bar and title elements
        elements.titlebar = $('<div />', {
            'class': NAMESPACE + '-titlebar ' + (options.style.widget ? createWidgetClass('header') : '')
        })
        .append(
            elements.title = $('<div />', {
                'id': id,
                'class': NAMESPACE + '-title',
                'aria-atomic': TRUE
            })
        )
        .insertBefore(elements.content)

        // Button-specific events
        .delegate('.qtip-close', 'mousedown keydown mouseup keyup mouseout', function(event) {
            $(this).toggleClass('ui-state-active ui-state-focus', event.type.substr(-4) === 'down');
        })
        .delegate('.qtip-close', 'mouseover mouseout', function(event){
            $(this).toggleClass('ui-state-hover', event.type === 'mouseover');
        });

        // Create button if enabled
        if(options.content.title.button) { createButton(); }
    }

    function updateButton(button)
    {
        var elem = elements.button;

        // Make sure tooltip is rendered and if not, return
        if(!self.rendered) { return FALSE; }

        if(!button) {
            elem.remove();
        }
        else {
            createButton();
        }
    }

    function updateTitle(content, reposition)
    {
        var elem = elements.title;

        // Make sure tooltip is rendered and if not, return
        if(!self.rendered || !content) { return FALSE; }

        // Use function to parse content
        if($.isFunction(content)) {
            content = content.call(target, cache.event, self);
        }

        // Remove title if callback returns false or null/undefined (but not '')
        if(content === FALSE || (!content && content !== '')) { return removeTitle(FALSE); }

        // Append new content if its a DOM array and show it if hidden
        else if(content.jquery && content.length > 0) {
            elem.empty().append(content.css({ display: 'block' }));
        }

        // Content is a regular string, insert the new content
        else { elem.html(content); }

        // Reposition if rnedered
        if(reposition !== FALSE && self.rendered && tooltip[0].offsetWidth > 0) {
            self.reposition(cache.event);
        }
    }

    function deferredContent(deferred)
    {
        if(deferred && $.isFunction(deferred.done)) {
            deferred.done(function(c) {
                updateContent(c, null, FALSE);
            });
        }
    }

    function updateContent(content, reposition, checkDeferred)
    {
        var elem = elements.content;

        // Make sure tooltip is rendered and content is defined. If not return
        if(!self.rendered || !content) { return FALSE; }

        // Use function to parse content
        if($.isFunction(content)) {
            content = content.call(target, cache.event, self) || '';
        }

        // Handle deferred content
        if(checkDeferred !== FALSE) {
            deferredContent(options.content.deferred);
        }

        // Append new content if its a DOM array and show it if hidden
        if(content.jquery && content.length > 0) {
            elem.empty().append(content.css({ display: 'block' }));
        }

        // Content is a regular string, insert the new content
        else { elem.html(content); }

        // Image detection
        function detectImages(next) {
            var images, srcs = {};

            function imageLoad(image) {
                // Clear src from object and any timers and events associated with the image
                if(image) {
                    delete srcs[image.src];
                    clearTimeout(self.timers.img[image.src]);
                    $(image).unbind(namespace);
                }

                // If queue is empty after image removal, update tooltip and continue the queue
                if($.isEmptyObject(srcs)) {
                    if(reposition !== FALSE) {
                        self.reposition(cache.event);
                    }

                    next();
                }
            }

            // Find all content images without dimensions, and if no images were found, continue
            if((images = elem.find('img[src]:not([height]):not([width])')).length === 0) { return imageLoad(); }

            // Apply timer to each image to poll for dimensions
            images.each(function(i, elem) {
                // Skip if the src is already present
                if(srcs[elem.src] !== undefined) { return; }

                // Keep track of how many times we poll for image dimensions.
                // If it doesn't return in a reasonable amount of time, it's better
                // to display the tooltip, rather than hold up the queue.
                var iterations = 0, maxIterations = 3;

                (function timer(){
                    // When the dimensions are found, remove the image from the queue
                    if(elem.height || elem.width || (iterations > maxIterations)) { return imageLoad(elem); }

                    // Increase iterations and restart timer
                    iterations += 1;
                    self.timers.img[elem.src] = setTimeout(timer, 700);
                }());

                // Also apply regular load/error event handlers
                $(elem).bind('error'+namespace+' load'+namespace, function(){ imageLoad(this); });

                // Store the src and element in our object
                srcs[elem.src] = elem;
            });
        }

        /*
        * If we're still rendering... insert into 'fx' queue our image dimension
        * checker which will halt the showing of the tooltip until image dimensions
        * can be detected properly.
        */
        if(self.rendered < 0) { tooltip.queue('fx', detectImages); }

        // We're fully rendered, so reset isDrawing flag and proceed without queue delay
        else { isDrawing = 0; detectImages($.noop); }

        return self;
    }

    function assignEvents()
    {
        var posOptions = options.position,
            targets = {
                show: options.show.target,
                hide: options.hide.target,
                viewport: $(posOptions.viewport),
                document: $(document),
                body: $(document.body),
                window: $(window)
            },
            events = {
                show: $.trim('' + options.show.event).split(' '),
                hide: $.trim('' + options.hide.event).split(' ')
            },
            IE6 = PLUGINS.ie === 6;

        // Define show event method
        function showMethod(event)
        {
            if(tooltip.hasClass(disabledClass)) { return FALSE; }

            // Clear hide timers
            clearTimeout(self.timers.show);
            clearTimeout(self.timers.hide);

            // Start show timer
            var callback = function(){ self.toggle(TRUE, event); };
            if(options.show.delay > 0) {
                self.timers.show = setTimeout(callback, options.show.delay);
            }
            else{ callback(); }
        }

        // Define hide method
        function hideMethod(event)
        {
            if(tooltip.hasClass(disabledClass) || isPositioning || isDrawing) { return FALSE; }

            // Check if new target was actually the tooltip element
            var relatedTarget = $(event.relatedTarget || event.target),
                ontoTooltip = relatedTarget.closest(selector)[0] === tooltip[0],
                ontoTarget = relatedTarget[0] === targets.show[0];

            // Clear timers and stop animation queue
            clearTimeout(self.timers.show);
            clearTimeout(self.timers.hide);

            // Prevent hiding if tooltip is fixed and event target is the tooltip. Or if mouse positioning is enabled and cursor momentarily overlaps
            if((posOptions.target === 'mouse' && ontoTooltip) || (options.hide.fixed && ((/mouse(out|leave|move)/).test(event.type) && (ontoTooltip || ontoTarget)))) {
                try { event.preventDefault(); event.stopImmediatePropagation(); } catch(e) {} return;
            }

            // If tooltip has displayed, start hide timer
            if(options.hide.delay > 0) {
                self.timers.hide = setTimeout(function(){ self.hide(event); }, options.hide.delay);
            }
            else{ self.hide(event); }
        }

        // Define inactive method
        function inactiveMethod(event)
        {
            if(tooltip.hasClass(disabledClass)) { return FALSE; }

            // Clear timer
            clearTimeout(self.timers.inactive);
            self.timers.inactive = setTimeout(function(){ self.hide(event); }, options.hide.inactive);
        }

        function repositionMethod(event) {
            if(self.rendered && tooltip[0].offsetWidth > 0) { self.reposition(event); }
        }

        // On mouseenter/mouseleave...
        tooltip.bind('mouseenter'+namespace+' mouseleave'+namespace, function(event) {
            var state = event.type === 'mouseenter';

            // Focus the tooltip on mouseenter (z-index stacking)
            if(state) { self.focus(event); }

            // Add hover class
            tooltip.toggleClass(hoverClass, state);
        });

        // If using mouseout/mouseleave as a hide event...
        if(/mouse(out|leave)/i.test(options.hide.event)) {
            // Hide tooltips when leaving current window/frame (but not select/option elements)
            if(options.hide.leave === 'window') {
                targets.window.bind('mouseout'+namespace+' blur'+namespace, function(event) {
                    if(!/select|option/.test(event.target.nodeName) && !event.relatedTarget) { self.hide(event); }
                });
            }
        }

        // Enable hide.fixed
        if(options.hide.fixed) {
            // Add tooltip as a hide target
            targets.hide = targets.hide.add(tooltip);

            // Clear hide timer on tooltip hover to prevent it from closing
            tooltip.bind('mouseover'+namespace, function() {
                if(!tooltip.hasClass(disabledClass)) { clearTimeout(self.timers.hide); }
            });
        }

        /*
        * Make sure hoverIntent functions properly by using mouseleave to clear show timer if
        * mouseenter/mouseout is used for show.event, even if it isn't in the users options.
        */
        else if(/mouse(over|enter)/i.test(options.show.event)) {
            targets.hide.bind('mouseleave'+namespace, function(event) {
                clearTimeout(self.timers.show);
            });
        }

        // Hide tooltip on document mousedown if unfocus events are enabled
        if(('' + options.hide.event).indexOf('unfocus') > -1) {
            posOptions.container.closest('html').bind('mousedown'+namespace+' touchstart'+namespace, function(event) {
                var elem = $(event.target),
                    enabled = self.rendered && !tooltip.hasClass(disabledClass) && tooltip[0].offsetWidth > 0,
                    isAncestor = elem.parents(selector).filter(tooltip[0]).length > 0;

                if(elem[0] !== target[0] && elem[0] !== tooltip[0] && !isAncestor &&
                    !target.has(elem[0]).length && !elem.attr('disabled')
                ) {
                    self.hide(event);
                }
            });
        }

        // Check if the tooltip hides when inactive
        if('number' === typeof options.hide.inactive) {
            // Bind inactive method to target as a custom event
            targets.show.bind('qtip-'+id+'-inactive', inactiveMethod);

            // Define events which reset the 'inactive' event handler
            $.each(QTIP.inactiveEvents, function(index, type){
                targets.hide.add(elements.tooltip).bind(type+namespace+'-inactive', inactiveMethod);
            });
        }

        // Apply hide events
        $.each(events.hide, function(index, type) {
            var showIndex = $.inArray(type, events.show),
                    targetHide = $(targets.hide);

            // Both events and targets are identical, apply events using a toggle
            if((showIndex > -1 && targetHide.add(targets.show).length === targetHide.length) || type === 'unfocus')
            {
                targets.show.bind(type+namespace, function(event) {
                    if(tooltip[0].offsetWidth > 0) { hideMethod(event); }
                    else { showMethod(event); }
                });

                // Don't bind the event again
                delete events.show[ showIndex ];
            }

            // Events are not identical, bind normally
            else { targets.hide.bind(type+namespace, hideMethod); }
        });

        // Apply show events
        $.each(events.show, function(index, type) {
            targets.show.bind(type+namespace, showMethod);
        });

        // Check if the tooltip hides when mouse is moved a certain distance
        if('number' === typeof options.hide.distance) {
            // Bind mousemove to target to detect distance difference
            targets.show.add(tooltip).bind('mousemove'+namespace, function(event) {
                var origin = cache.origin || {},
                    limit = options.hide.distance,
                    abs = Math.abs;

                // Check if the movement has gone beyond the limit, and hide it if so
                if(abs(event.pageX - origin.pageX) >= limit || abs(event.pageY - origin.pageY) >= limit) {
                    self.hide(event);
                }
            });
        }

        // Mouse positioning events
        if(posOptions.target === 'mouse') {
            // Cache mousemove coords on show targets
            targets.show.bind('mousemove'+namespace, storeMouse);

            // If mouse adjustment is on...
            if(posOptions.adjust.mouse) {
                // Apply a mouseleave event so we don't get problems with overlapping
                if(options.hide.event) {
                    // Hide when we leave the tooltip and not onto the show target
                    tooltip.bind('mouseleave'+namespace, function(event) {
                        if((event.relatedTarget || event.target) !== targets.show[0]) { self.hide(event); }
                    });

                    // Track if we're on the target or not
                    elements.target.bind('mouseenter'+namespace+' mouseleave'+namespace, function(event) {
                        cache.onTarget = event.type === 'mouseenter';
                    });
                }

                // Update tooltip position on mousemove
                targets.document.bind('mousemove'+namespace, function(event) {
                    // Update the tooltip position only if the tooltip is visible and adjustment is enabled
                    if(self.rendered && cache.onTarget && !tooltip.hasClass(disabledClass) && tooltip[0].offsetWidth > 0) {
                        self.reposition(event || MOUSE);
                    }
                });
            }
        }

        // Adjust positions of the tooltip on window resize if enabled
        if(posOptions.adjust.resize || targets.viewport.length) {
            ($.event.special.resize ? targets.viewport : targets.window).bind('resize'+namespace, repositionMethod);
        }

        // Adjust tooltip position on scroll of the window or viewport element if present
        targets.window.add(posOptions.container).bind('scroll'+namespace, repositionMethod);
    }

    function unassignEvents()
    {
        var targets = [
                options.show.target[0],
                options.hide.target[0],
                self.rendered && elements.tooltip[0],
                options.position.container[0],
                options.position.viewport[0],
                options.position.container.closest('html')[0], // unfocus
                window,
                document
            ];

        // Check if tooltip is rendered
        if(self.rendered) {
            $([]).pushStack( $.grep(targets, function(i){ return typeof i === 'object'; }) ).unbind(namespace);
        }

        // Tooltip isn't yet rendered, remove render event
        else { options.show.target.unbind(namespace+'-create'); }
    }

    // Setup builtin .set() option checks
    self.checks.builtin = {
        // Core checks
        '^id$': function(obj, o, v) {
            var id = v === TRUE ? QTIP.nextid : v,
                tooltipID = NAMESPACE + '-' + id;

            if(id !== FALSE && id.length > 0 && !$('#'+tooltipID).length) {
                tooltip[0].id = tooltipID;
                elements.content[0].id = tooltipID + '-content';
                elements.title[0].id = tooltipID + '-title';
            }
        },

        // Content checks
        '^content.text$': function(obj, o, v) { updateContent(options.content.text); },
        '^content.deferred$': function(obj, o, v) { deferredContent(options.content.deferred); },
        '^content.title.text$': function(obj, o, v) {
            // Remove title if content is null
            if(!v) { return removeTitle(); }

            // If title isn't already created, create it now and update
            if(!elements.title && v) { createTitle(); }
            updateTitle(v);
        },
        '^content.title.button$': function(obj, o, v){ updateButton(v); },

        // Position checks
        '^position.(my|at)$': function(obj, o, v){
            // Parse new corner value into Corner objecct
            if('string' === typeof v) {
                obj[o] = new PLUGINS.Corner(v);
            }
        },
        '^position.container$': function(obj, o, v){
            if(self.rendered) { tooltip.appendTo(v); }
        },

        // Show checks
        '^show.ready$': function() {
            if(!self.rendered) { self.render(1); }
            else { self.toggle(TRUE); }
        },

        // Style checks
        '^style.classes$': function(obj, o, v) {
            tooltip.attr('class', NAMESPACE + ' qtip ' + v);
        },
        '^style.width|height': function(obj, o, v) {
            tooltip.css(o, v);
        },
        '^style.widget|content.title': setWidget,

        // Events check
        '^events.(render|show|move|hide|focus|blur)$': function(obj, o, v) {
            tooltip[($.isFunction(v) ? '' : 'un') + 'bind']('tooltip'+o, v);
        },

        // Properties which require event reassignment
        '^(show|hide|position).(event|target|fixed|inactive|leave|distance|viewport|adjust)': function() {
            var posOptions = options.position;

            // Set tracking flag
            tooltip.attr('tracking', posOptions.target === 'mouse' && posOptions.adjust.mouse);

            // Reassign events
            unassignEvents(); assignEvents();
        }
    };

    $.extend(self, {
        /*
        * Psuedo-private API methods
        */
        _triggerEvent: function(type, args, event)
        {
            var callback = $.Event('tooltip'+type);
            callback.originalEvent = (event ? $.extend({}, event) : NULL) || cache.event || NULL;
            tooltip.trigger(callback, [self].concat(args || []));

            return !callback.isDefaultPrevented();
        },

        /*
        * Public API methods
        */
        render: function(show)
        {
            if(self.rendered) { return self; } // If tooltip has already been rendered, exit

            var text = options.content.text,
                title = options.content.title,
                posOptions = options.position;

            // Add ARIA attributes to target
            $.attr(target[0], 'aria-describedby', tooltipID);

            // Create tooltip element
            tooltip = elements.tooltip = $('<div/>', {
                    'id': tooltipID,
                    'class': [ NAMESPACE, defaultClass, options.style.classes, NAMESPACE + '-pos-' + options.position.my.abbrev() ].join(' '),
                    'width': options.style.width || '',
                    'height': options.style.height || '',
                    'tracking': posOptions.target === 'mouse' && posOptions.adjust.mouse,

                    /* ARIA specific attributes */
                    'role': 'alert',
                    'aria-live': 'polite',
                    'aria-atomic': FALSE,
                    'aria-describedby': tooltipID + '-content',
                    'aria-hidden': TRUE
                })
                .toggleClass(disabledClass, cache.disabled)
                .data('qtip', self)
                .appendTo(options.position.container)
                .append(
                    // Create content element
                    elements.content = $('<div />', {
                        'class': NAMESPACE + '-content',
                        'id': tooltipID + '-content',
                        'aria-atomic': TRUE
                    })
                );

            // Set rendered flag and prevent redundant reposition calls for now
            self.rendered = -1;
            isPositioning = 1;

            // Create title...
            if(title.text) {
                createTitle();

                // Update title only if its not a callback (called in toggle if so)
                if(!$.isFunction(title.text)) { updateTitle(title.text, FALSE); }
            }

            // Create button
            else if(title.button) { createButton(); }

            // Set proper rendered flag and update content if not a callback function (called in toggle)
            if(!$.isFunction(text) || text.then) { updateContent(text, FALSE); }
            self.rendered = TRUE;

            // Setup widget classes
            setWidget();

            // Assign passed event callbacks (before plugins!)
            $.each(options.events, function(name, callback) {
                if($.isFunction(callback)) {
                    tooltip.bind(name === 'toggle' ? 'tooltipshow tooltiphide' : 'tooltip'+name, callback);
                }
            });

            // Initialize 'render' plugins
            $.each(PLUGINS, function() {
                if(this.initialize === 'render') { this(self); }
            });

            // Assign events
            assignEvents();

            /* Queue this part of the render process in our fx queue so we can
            * load images before the tooltip renders fully.
            *
            * See: updateContent method
            */
            tooltip.queue('fx', function(next) {
                // tooltiprender event
                self._triggerEvent('render');

                // Reset flags
                isPositioning = 0;

                // Show tooltip if needed
                if(options.show.ready || show) {
                    self.toggle(TRUE, cache.event, FALSE);
                }

                next(); // Move on to next method in queue
            });

            return self;
        },

        get: function(notation)
        {
            var result, o;

            switch(notation.toLowerCase())
            {
                case 'dimensions':
                    result = {
                        height: tooltip.outerHeight(FALSE),
                        width: tooltip.outerWidth(FALSE)
                    };
                break;

                case 'offset':
                    result = PLUGINS.offset(tooltip, options.position.container);
                break;

                default:
                    o = convertNotation(notation.toLowerCase());
                    result = o[0][ o[1] ];
                    result = result.precedance ? result.string() : result;
                break;
            }

            return result;
        },

        set: function(option, value)
        {
            var rmove = /^position\.(my|at|adjust|target|container)|style|content|show\.ready/i,
                rdraw = /^content\.(title|attr)|style/i,
                reposition = FALSE,
                checks = self.checks,
                name;

            function callback(notation, args) {
                var category, rule, match;

                for(category in checks) {
                    for(rule in checks[category]) {
                        if(match = (new RegExp(rule, 'i')).exec(notation)) {
                            args.push(match);
                            checks[category][rule].apply(self, args);
                        }
                    }
                }
            }

            // Convert singular option/value pair into object form
            if('string' === typeof option) {
                name = option; option = {}; option[name] = value;
            }
            else { option = $.extend(TRUE, {}, option); }

            // Set all of the defined options to their new values
            $.each(option, function(notation, value) {
                var obj = convertNotation( notation.toLowerCase() ), previous;

                // Set new obj value
                previous = obj[0][ obj[1] ];
                obj[0][ obj[1] ] = 'object' === typeof value && value.nodeType ? $(value) : value;

                // Set the new params for the callback
                option[notation] = [obj[0], obj[1], value, previous];

                // Also check if we need to reposition
                reposition = rmove.test(notation) || reposition;
            });

            // Re-sanitize options
            sanitizeOptions(options);

            /*
            * Execute any valid callbacks for the set options
            * Also set isPositioning/isDrawing so we don't get loads of redundant repositioning calls.
            */
            isPositioning = 1; $.each(option, callback); isPositioning = 0;

            // Update position if needed
            if(self.rendered && tooltip[0].offsetWidth > 0 && reposition) {
                self.reposition( options.position.target === 'mouse' ? NULL : cache.event );
            }

            return self;
        },

        toggle: function(state, event)
        {
            // Try to prevent flickering when tooltip overlaps show element
            if(event) {
                if((/over|enter/).test(event.type) && (/out|leave/).test(cache.event.type) &&
                    options.show.target.add(event.target).length === options.show.target.length &&
                    tooltip.has(event.relatedTarget).length) {
                    return self;
                }

                // Cache event
                cache.event = $.extend({}, event);
            }
    
            // Render the tooltip if showing and it isn't already
            if(!self.rendered) { return state ? self.render(1) : self; }

            var type = state ? 'show' : 'hide',
                opts = options[type],
                otherOpts = options[ !state ? 'show' : 'hide' ],
                posOptions = options.position,
                contentOptions = options.content,
                visible = tooltip[0].offsetWidth > 0,
                animate = state || opts.target.length === 1,
                sameTarget = !event || opts.target.length < 2 || cache.target[0] === event.target,
                showEvent, delay;

            // Detect state if valid one isn't provided
            if((typeof state).search('boolean|number')) { state = !visible; }

            // Return if element is already in correct state
            if(!tooltip.is(':animated') && visible === state && sameTarget) { return self; }

            // tooltipshow/tooltiphide events
            if(!self._triggerEvent(type, [90])) { return self; }

            // Set ARIA hidden status attribute
            $.attr(tooltip[0], 'aria-hidden', !!!state);

            // Execute state specific properties
            if(state) {
                // Store show origin coordinates
                cache.origin = $.extend({}, MOUSE);

                // Focus the tooltip
                self.focus(event);

                // Update tooltip content & title if it's a dynamic function
                if($.isFunction(contentOptions.text)) { updateContent(contentOptions.text, FALSE); }
                if($.isFunction(contentOptions.title.text)) { updateTitle(contentOptions.title.text, FALSE); }

                // Cache mousemove events for positioning purposes (if not already tracking)
                if(!trackingBound && posOptions.target === 'mouse' && posOptions.adjust.mouse) {
                    $(document).bind('mousemove.qtip', storeMouse);
                    trackingBound = TRUE;
                }

                // Update the tooltip position
                self.reposition(event, arguments[2]);

                // Hide other tooltips if tooltip is solo
                if(!!opts.solo) {
                    (typeof opts.solo === 'string' ? $(opts.solo) : $(selector, opts.solo))
                        .not(tooltip).not(opts.target).qtip('hide', $.Event('tooltipsolo'));
                }
            }
            else {
                // Clear show timer if we're hiding
                clearTimeout(self.timers.show);

                // Remove cached origin on hide
                delete cache.origin;

                // Remove mouse tracking event if not needed (all tracking qTips are hidden)
                if(trackingBound && !$(selector+'[tracking="true"]:visible', opts.solo).not(tooltip).length) {
                    $(document).unbind('mousemove.qtip');
                    trackingBound = FALSE;
                }

                // Blur the tooltip
                self.blur(event);
            }

            // Define post-animation, state specific properties
            function after() {
                if(state) {
                    // Prevent antialias from disappearing in IE by removing filter
                    if(PLUGINS.ie) { tooltip[0].style.removeAttribute('filter'); }

                    // Remove overflow setting to prevent tip bugs
                    tooltip.css('overflow', '');

                    // Autofocus elements if enabled
                    if('string' === typeof opts.autofocus) {
                        $(opts.autofocus, tooltip).focus();
                    }

                    // If set, hide tooltip when inactive for delay period
                    opts.target.trigger('qtip-'+id+'-inactive');
                }
                else {
                    // Reset CSS states
                    tooltip.css({
                        display: '',
                        visibility: '',
                        opacity: '',
                        left: '',
                        top: ''
                    });
                }

                // tooltipvisible/tooltiphidden events
                self._triggerEvent(state ? 'visible' : 'hidden');
            }

            // If no effect type is supplied, use a simple toggle
            if(opts.effect === FALSE || animate === FALSE) {
                tooltip[ type ]();
                after.call(tooltip);
            }

            // Use custom function if provided
            else if($.isFunction(opts.effect)) {
                tooltip.stop(1, 1);
                opts.effect.call(tooltip, self);
                tooltip.queue('fx', function(n){ after(); n(); });
            }

            // Use basic fade function by default
            else { tooltip.fadeTo(90, state ? 1 : 0, after); }

            // If inactive hide method is set, active it
            if(state) { opts.target.trigger('qtip-'+id+'-inactive'); }

            return self;
        },

        show: function(event){ return self.toggle(TRUE, event); },

        hide: function(event){ return self.toggle(FALSE, event); },

        focus: function(event)
        {
            if(!self.rendered) { return self; }

            var qtips = $(selector),
                curIndex = parseInt(tooltip[0].style.zIndex, 10),
                newIndex = QTIP.zindex + qtips.length,
                cachedEvent = $.extend({}, event),
                focusedElem;

            // Only update the z-index if it has changed and tooltip is not already focused
            if(!tooltip.hasClass(focusClass))
            {
                // tooltipfocus event
                if(self._triggerEvent('focus', [newIndex], cachedEvent)) {
                    // Only update z-index's if they've changed
                    if(curIndex !== newIndex) {
                        // Reduce our z-index's and keep them properly ordered
                        qtips.each(function() {
                            if(this.style.zIndex > curIndex) {
                                this.style.zIndex = this.style.zIndex - 1;
                            }
                        });

                        // Fire blur event for focused tooltip
                        qtips.filter('.' + focusClass).qtip('blur', cachedEvent);
                    }

                    // Set the new z-index
                    tooltip.addClass(focusClass)[0].style.zIndex = newIndex;
                }
            }

            return self;
        },

        blur: function(event) {
            // Set focused status to FALSE
            tooltip.removeClass(focusClass);

            // tooltipblur event
            self._triggerEvent('blur', [tooltip.css('zIndex')], event);

            return self;
        },

        reposition: function(event, effect)
        {
            if(!self.rendered || isPositioning) { return self; }

            // Set positioning flag
            isPositioning = 1;

            var target = options.position.target,
                posOptions = options.position,
                my = posOptions.my,
                at = posOptions.at,
                adjust = posOptions.adjust,
                method = adjust.method.split(' '),
                elemWidth = tooltip.outerWidth(FALSE),
                elemHeight = tooltip.outerHeight(FALSE),
                targetWidth = 0,
                targetHeight = 0,
                type = tooltip.css('position'),
                viewport = posOptions.viewport,
                position = { left: 0, top: 0 },
                container = posOptions.container,
                visible = tooltip[0].offsetWidth > 0,
                isScroll = event && event.type === 'scroll',
                win = $(window),
                adjusted, offset;

            // Check if absolute position was passed
            if($.isArray(target) && target.length === 2) {
                // Force left top and set position
                at = { x: LEFT, y: TOP };
                position = { left: target[0], top: target[1] };
            }

            // Check if mouse was the target
            else if(target === 'mouse' && ((event && event.pageX) || cache.event.pageX)) {
                // Force left top to allow flipping
                at = { x: LEFT, y: TOP };

                // Use cached event if one isn't available for positioning
                event = MOUSE && MOUSE.pageX && (adjust.mouse || !event || !event.pageX) ? { pageX: MOUSE.pageX, pageY: MOUSE.pageY } :
                    (event && (event.type === 'resize' || event.type === 'scroll') ? cache.event :
                    event && event.pageX && event.type === 'mousemove' ? event :
                    (!adjust.mouse || options.show.distance) && cache.origin && cache.origin.pageX ? cache.origin :
                    event) || event || cache.event || MOUSE || {};

                // Use event coordinates for position
                if(type !== 'static') { position = container.offset(); }
                position = { left: event.pageX - position.left, top: event.pageY - position.top };

                // Scroll events are a pain, some browsers
                if(adjust.mouse && isScroll) {
                    position.left -= MOUSE.scrollX - win.scrollLeft();
                    position.top -= MOUSE.scrollY - win.scrollTop();
                }
            }

            // Target wasn't mouse or absolute...
            else {
                // Check if event targetting is being used
                if(target === 'event' && event && event.target && event.type !== 'scroll' && event.type !== 'resize') {
                    cache.target = $(event.target);
                }
                else if(target !== 'event'){
                    cache.target = $(target.jquery ? target : elements.target);
                }
                target = cache.target;

                // Parse the target into a jQuery object and make sure there's an element present
                target = $(target).eq(0);
                if(target.length === 0) { return self; }

                // Check if window or document is the target
                else if(target[0] === document || target[0] === window) {
                    targetWidth = PLUGINS.iOS ? window.innerWidth : target.width();
                    targetHeight = PLUGINS.iOS ? window.innerHeight : target.height();

                    if(target[0] === window) {
                        position = {
                            top: (viewport || target).scrollTop(),
                            left: (viewport || target).scrollLeft()
                        };
                    }
                }

                // Use Imagemap/SVG plugins if needed
                else if(PLUGINS.imagemap && target.is('area')) {
                    adjusted = PLUGINS.imagemap(self, target, at, PLUGINS.viewport ? method : FALSE);
                }
                else if(PLUGINS.svg && target[0].ownerSVGElement) {
                    adjusted = PLUGINS.svg(self, target, at, PLUGINS.viewport ? method : FALSE);
                }

                else {
                    targetWidth = target.outerWidth(FALSE);
                    targetHeight = target.outerHeight(FALSE);

                    position = PLUGINS.offset(target, container);
                }

                // Parse returned plugin values into proper variables
                if(adjusted) {
                    targetWidth = adjusted.width;
                    targetHeight = adjusted.height;
                    offset = adjusted.offset;
                    position = adjusted.position;
                }

                // Adjust for position.fixed tooltips (and also iOS scroll bug in v3.2-4.0 & v4.3-4.3.2)
                if((PLUGINS.iOS > 3.1 && PLUGINS.iOS < 4.1) || 
                    (PLUGINS.iOS >= 4.3 && PLUGINS.iOS < 4.33) || 
                    (!PLUGINS.iOS && type === 'fixed')
                ){
                    position.left -= win.scrollLeft();
                    position.top -= win.scrollTop();
                }

                // Adjust position relative to target
                position.left += at.x === RIGHT ? targetWidth : at.x === CENTER ? targetWidth / 2 : 0;
                position.top += at.y === BOTTOM ? targetHeight : at.y === CENTER ? targetHeight / 2 : 0;
            }

            // Adjust position relative to tooltip
            position.left += adjust.x + (my.x === RIGHT ? -elemWidth : my.x === CENTER ? -elemWidth / 2 : 0);
            position.top += adjust.y + (my.y === BOTTOM ? -elemHeight : my.y === CENTER ? -elemHeight / 2 : 0);

            // Use viewport adjustment plugin if enabled
            if(PLUGINS.viewport) {
                position.adjusted = PLUGINS.viewport(
                    self, position, posOptions, targetWidth, targetHeight, elemWidth, elemHeight
                );

                // Apply offsets supplied by positioning plugin (if used)
                if(offset && position.adjusted.left) { position.left += offset.left; }
                if(offset && position.adjusted.top) {  position.top += offset.top; }
            }

            // Viewport adjustment is disabled, set values to zero
            else { position.adjusted = { left: 0, top: 0 }; }

            // tooltipmove event
            if(!self._triggerEvent('move', [position, viewport.elem || viewport], event)) { return self; }
            delete position.adjusted;

            // If effect is disabled, target it mouse, no animation is defined or positioning gives NaN out, set CSS directly
            if(effect === FALSE || !visible || isNaN(position.left) || isNaN(position.top) || target === 'mouse' || !$.isFunction(posOptions.effect)) {
                tooltip.css(position);
            }

            // Use custom function if provided
            else if($.isFunction(posOptions.effect)) {
                posOptions.effect.call(tooltip, self, $.extend({}, position));
                tooltip.queue(function(next) {
                    // Reset attributes to avoid cross-browser rendering bugs
                    $(this).css({ opacity: '', height: '' });
                    if(PLUGINS.ie) { this.style.removeAttribute('filter'); }

                    next();
                });
            }

            // Set positioning flagwtf
            isPositioning = 0;

            return self;
        },

        disable: function(state)
        {
            if('boolean' !== typeof state) {
                state = !(tooltip.hasClass(disabledClass) || cache.disabled);
            }

            if(self.rendered) {
                tooltip.toggleClass(disabledClass, state);
                $.attr(tooltip[0], 'aria-disabled', state);
            }
            else {
                cache.disabled = !!state;
            }

            return self;
        },

        enable: function() { return self.disable(FALSE); },

        destroy: function()
        {
            var t = target[0],
                title = $.attr(t, oldtitle),
                elemAPI = target.data('qtip');

            // Set flag the signify destroy is taking place to plugins
            self.destroyed = TRUE;

            // Destroy tooltip and  any associated plugins if rendered
            if(self.rendered) {
                tooltip.stop(1,0).remove();

                $.each(self.plugins, function() {
                    if(this.destroy) { this.destroy(); }
                });
            }

            // Clear timers and remove bound events
            clearTimeout(self.timers.show);
            clearTimeout(self.timers.hide);
            unassignEvents();

            // If the API if actually this qTip API...
            if(!elemAPI || self === elemAPI) {
                // Remove api object
                $.removeData(t, 'qtip');

                // Reset old title attribute if removed
                if(options.suppress && title) {
                    $.attr(t, 'title', title);
                    target.removeAttr(oldtitle);
                }

                // Remove ARIA attributes
                target.removeAttr('aria-describedby');
            }

            // Remove qTip events associated with this API
            target.unbind('.qtip-'+id);

            // Remove ID from sued id object
            delete usedIDs[self.id];

            return target;
        }
    });
}

// Initialization method
function init(id, opts)
{
    var obj, posOptions, attr, config, title,

    // Setup element references
    elem = $(this),
    docBody = $(document.body),

    // Use document body instead of document element if needed
    newTarget = this === document ? docBody : elem,

    // Grab metadata from element if plugin is present
    metadata = (elem.metadata) ? elem.metadata(opts.metadata) : NULL,

    // If metadata type if HTML5, grab 'name' from the object instead, or use the regular data object otherwise
    metadata5 = opts.metadata.type === 'html5' && metadata ? metadata[opts.metadata.name] : NULL,

    // Grab data from metadata.name (or data-qtipopts as fallback) using .data() method,
    html5 = elem.data(opts.metadata.name || 'qtipopts');

    // If we don't get an object returned attempt to parse it manualyl without parseJSON
    try { html5 = typeof html5 === 'string' ? $.parseJSON(html5) : html5; } catch(e) {}

    // Merge in and sanitize metadata
    config = $.extend(TRUE, {}, QTIP.defaults, opts,
        typeof html5 === 'object' ? sanitizeOptions(html5) : NULL,
        sanitizeOptions(metadata5 || metadata));

    // Re-grab our positioning options now we've merged our metadata and set id to passed value
    posOptions = config.position;
    config.id = id;

    // Setup missing content if none is detected
    if('boolean' === typeof config.content.text) {
        attr = elem.attr(config.content.attr);

        // Grab from supplied attribute if available
        if(config.content.attr !== FALSE && attr) { config.content.text = attr; }

        // No valid content was found, abort render
        else { return FALSE; }
    }

    // Setup target options
    if(!posOptions.container.length) { posOptions.container = docBody; }
    if(posOptions.target === FALSE) { posOptions.target = newTarget; }
    if(config.show.target === FALSE) { config.show.target = newTarget; }
    if(config.show.solo === TRUE) { config.show.solo = posOptions.container.closest('body'); }
    if(config.hide.target === FALSE) { config.hide.target = newTarget; }
    if(config.position.viewport === TRUE) { config.position.viewport = posOptions.container; }

    // Ensure we only use a single container
    posOptions.container = posOptions.container.eq(0);

    // Convert position corner values into x and y strings
    posOptions.at = new PLUGINS.Corner(posOptions.at);
    posOptions.my = new PLUGINS.Corner(posOptions.my);

    // Destroy previous tooltip if overwrite is enabled, or skip element if not
    if($.data(this, 'qtip')) {
        if(config.overwrite) {
            elem.qtip('destroy');
        }
        else if(config.overwrite === FALSE) {
            return FALSE;
        }
    }

    // Remove title attribute and store it if present
    if(config.suppress && (title = $.attr(this, 'title'))) {
        // Final attr call fixes event delegatiom and IE default tooltip showing problem
        $(this).removeAttr('title').attr(oldtitle, title).attr('title', '');
    }

    // Initialize the tooltip and add API reference
    obj = new QTip(elem, config, id, !!attr);
    $.data(this, 'qtip', obj);

    // Catch remove/removeqtip events on target element to destroy redundant tooltip
    elem.bind('remove.qtip-'+id+' removeqtip.qtip-'+id, function(){ obj.destroy(); });

    return obj;
}

// jQuery $.fn extension method
QTIP = $.fn.qtip = function(options, notation, newValue)
{
    var command = ('' + options).toLowerCase(), // Parse command
        returned = NULL,
        args = $.makeArray(arguments).slice(1),
        event = args[args.length - 1],
        opts = this[0] ? $.data(this[0], 'qtip') : NULL;

    // Check for API request
    if((!arguments.length && opts) || command === 'api') {
        return opts;
    }

    // Execute API command if present
    else if('string' === typeof options)
    {
        this.each(function()
        {
            var api = $.data(this, 'qtip');
            if(!api) { return TRUE; }

            // Cache the event if possible
            if(event && event.timeStamp) { api.cache.event = event; }

            // Check for specific API commands
            if((command === 'option' || command === 'options') && notation) {
                if($.isPlainObject(notation) || newValue !== undefined) {
                    api.set(notation, newValue);
                }
                else {
                    returned = api.get(notation);
                    return FALSE;
                }
            }

            // Execute API command
            else if(api[command]) {
                api[command].apply(api[command], args);
            }
        });

        return returned !== NULL ? returned : this;
    }

    // No API commands. validate provided options and setup qTips
    else if('object' === typeof options || !arguments.length)
    {
        opts = sanitizeOptions($.extend(TRUE, {}, options));

        // Bind the qTips
        return QTIP.bind.call(this, opts, event);
    }
};

// $.fn.qtip Bind method
QTIP.bind = function(opts, event)
{
    return this.each(function(i) {
        var options, targets, events, namespace, api, id;

        // Find next available ID, or use custom ID if provided
        id = $.isArray(opts.id) ? opts.id[i] : opts.id;
        id = !id || id === FALSE || id.length < 1 || usedIDs[id] ? QTIP.nextid++ : (usedIDs[id] = id);

        // Setup events namespace
        namespace = '.qtip-'+id+'-create';

        // Initialize the qTip and re-grab newly sanitized options
        api = init.call(this, id, opts);
        if(api === FALSE) { return TRUE; }
        options = api.options;

        // Initialize plugins
        $.each(PLUGINS, function() {
            if(this.initialize === 'initialize') { this(api); }
        });

        // Determine hide and show targets
        targets = { show: options.show.target, hide: options.hide.target };
        events = {
            show: $.trim('' + options.show.event).replace(/ /g, namespace+' ') + namespace,
            hide: $.trim('' + options.hide.event).replace(/ /g, namespace+' ') + namespace
        };

        /*
        * Make sure hoverIntent functions properly by using mouseleave as a hide event if
        * mouseenter/mouseout is used for show.event, even if it isn't in the users options.
        */
        if(/mouse(over|enter)/i.test(events.show) && !/mouse(out|leave)/i.test(events.hide)) {
            events.hide += ' mouseleave' + namespace;
        }

        /*
        * Also make sure initial mouse targetting works correctly by caching mousemove coords
        * on show targets before the tooltip has rendered.
        *
        * Also set onTarget when triggered to keep mouse tracking working
        */
        targets.show.bind('mousemove'+namespace, function(event) {
            storeMouse(event);
            api.cache.onTarget = TRUE;
        });

        // Define hoverIntent function
        function hoverIntent(event) {
            function render() {
                // Cache mouse coords,render and render the tooltip
                api.render(typeof event === 'object' || options.show.ready);

                // Unbind show and hide events
                targets.show.add(targets.hide).unbind(namespace);
            }

            // Only continue if tooltip isn't disabled
            if(api.cache.disabled) { return FALSE; }

            // Cache the event data
            api.cache.event = $.extend({}, event);
            api.cache.target = event ? $(event.target) : [undefined];

            // Start the event sequence
            if(options.show.delay > 0) {
                clearTimeout(api.timers.show);
                api.timers.show = setTimeout(render, options.show.delay);
                if(events.show !== events.hide) {
                    targets.hide.bind(events.hide, function() { clearTimeout(api.timers.show); });
                }
            }
            else { render(); }
        }

        // Bind show events to target
        targets.show.bind(events.show, hoverIntent);

        // Prerendering is enabled, create tooltip now
        if(options.show.ready || options.prerender) { hoverIntent(event); }
    })
    .attr('data-hasqtip', TRUE);
};

// Setup base plugins
PLUGINS = QTIP.plugins = {
    // Corner object parser
    Corner: function(corner) {
        corner = ('' + corner).replace(/([A-Z])/, ' $1').replace(/middle/gi, CENTER).toLowerCase();
        this.x = (corner.match(/left|right/i) || corner.match(/center/) || ['inherit'])[0].toLowerCase();
        this.y = (corner.match(/top|bottom|center/i) || ['inherit'])[0].toLowerCase();

        var f = corner.charAt(0); this.precedance = (f === 't' || f === 'b' ? Y : X);

        this.string = function() { return this.precedance === Y ? this.y+this.x : this.x+this.y; };
        this.abbrev = function() {
            var x = this.x.substr(0,1), y = this.y.substr(0,1);
            return x === y ? x : this.precedance === Y ? y + x : x + y;
        };

        this.invertx = function(center) { this.x = this.x === LEFT ? RIGHT : this.x === RIGHT ? LEFT : center || this.x; };
        this.inverty = function(center) { this.y = this.y === TOP ? BOTTOM : this.y === BOTTOM ? TOP : center || this.y; };

        this.clone = function() {
            return {
                x: this.x, y: this.y, precedance: this.precedance,
                string: this.string, abbrev: this.abbrev, clone: this.clone,
                invertx: this.invertx, inverty: this.inverty
            };
        };
    },

    // Custom (more correct for qTip!) offset calculator
    offset: function(elem, container) {
        var pos = elem.offset(),
            docBody = elem.closest('body'),
            quirks = PLUGINS.ie && document.compatMode !== 'CSS1Compat',
            parent = container, scrolled,
            coffset, overflow;

        function scroll(e, i) {
            pos.left += i * e.scrollLeft();
            pos.top += i * e.scrollTop();
        }

        if(parent) {
            // Compensate for non-static containers offset
            do {
                if(parent.css('position') !== 'static') {
                    coffset = parent.position();

                    // Account for element positioning, borders and margins
                    pos.left -= coffset.left + (parseInt(parent.css('borderLeftWidth'), 10) || 0) + (parseInt(parent.css('marginLeft'), 10) || 0);
                    pos.top -= coffset.top + (parseInt(parent.css('borderTopWidth'), 10) || 0) + (parseInt(parent.css('marginTop'), 10) || 0);

                    // If this is the first parent element with an overflow of "scroll" or "auto", store it
                    if(!scrolled && (overflow = parent.css('overflow')) !== 'hidden' && overflow !== 'visible') { scrolled = parent; }
                }
            }
            while((parent = $(parent[0].offsetParent)).length);

            // Compensate for containers scroll if it also has an offsetParent (or in IE quirks mode)
            if(scrolled && scrolled[0] !== docBody[0] || quirks) {
                scroll( scrolled || docBody, 1 );
            }
        }

        return pos;
    },

    /*
    * IE version detection
    *
    * Adapted from: http://ajaxian.com/archives/attack-of-the-ie-conditional-comment
    * Credit to James Padolsey for the original implemntation!
    */
    ie: (function(){
        var v = 3, div = document.createElement('div');
        while ((div.innerHTML = '<!--[if gt IE '+(++v)+']><i></i><![endif]-->')) {
            if(!div.getElementsByTagName('i')[0]) { break; }
        }
        return v > 4 ? v : FALSE;
    }()),
 
    /*
    * iOS version detection
    */
    iOS: parseFloat( 
        ('' + (/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent) || [0,''])[1])
        .replace('undefined', '3_2').replace('_', '.').replace('_', '')
    ) || FALSE,

    /*
    * jQuery-specific $.fn overrides
    */
    fn: {
        /* Allow other plugins to successfully retrieve the title of an element with a qTip applied */
        attr: function(attr, val) {
            if(this.length) {
                var self = this[0],
                    title = 'title',
                    api = $.data(self, 'qtip');

                if(attr === title && api && 'object' === typeof api && api.options.suppress) {
                    if(arguments.length < 2) {
                        return $.attr(self, oldtitle);
                    }

                    // If qTip is rendered and title was originally used as content, update it
                    if(api && api.options.content.attr === title && api.cache.attr) {
                        api.set('content.text', val);
                    }

                    // Use the regular attr method to set, then cache the result
                    return this.attr(oldtitle, val);
                }
            }

            return $.fn['attr'+replaceSuffix].apply(this, arguments);
        },

        prop: function(attr, val) {
            var old = $.fn['prop'+replaceSuffix];
            return (old ? old : $.fn.attr).apply(this, arguments);
        },

        /* Allow clone to correctly retrieve cached title attributes */
        clone: function(keepData) {
            var titles = $([]), title = 'title',

            // Clone our element using the real clone method
            elems = $.fn['clone'+replaceSuffix].apply(this, arguments);

            // Grab all elements with an oldtitle set, and change it to regular title attribute, if keepData is false
            if(!keepData) {
                elems.filter('['+oldtitle+']').attr('title', function() {
                    return $.attr(this, oldtitle);
                })
                .removeAttr(oldtitle);
            }

            return elems;
        }
    }
};

// Apply the fn overrides above
$.each(PLUGINS.fn, function(name, func) {
    if(!func || $.fn[name+replaceSuffix]) { return TRUE; }

    var old = $.fn[name+replaceSuffix] = $.fn[name];
    $.fn[name] = function() {
        return func.apply(this, arguments) || old.apply(this, arguments);
    };
});

/* Fire off 'removeqtip' handler in $.cleanData if jQuery UI not present (it already does similar).
 * This snippet is taken directly from jQuery UI source code found here:
 *     http://code.jquery.com/ui/jquery-ui-git.js
 */
if(!$.ui) {
    $['cleanData'+replaceSuffix] = $.cleanData;
    $.cleanData = function( elems ) {
        for(var i = 0, elem; (elem = elems[i]) !== undefined; i++) {
            try { $( elem ).triggerHandler('removeqtip'); }
            catch( e ) {}
        }
        $['cleanData'+replaceSuffix]( elems );
    };
}

// Set global qTip properties
QTIP.version = '2.0.1-5-g';
QTIP.nextid = 0;
QTIP.inactiveEvents = 'click dblclick mousedown mouseup mousemove mouseleave mouseenter'.split(' ');
QTIP.zindex = 15000;

// Define configuration defaults
QTIP.defaults = {
    prerender: FALSE,
    id: FALSE,
    overwrite: TRUE,
    suppress: TRUE,
    content: {
        text: TRUE,
        attr: 'title',
        deferred: FALSE,
        title: {
            text: FALSE,
            button: FALSE
        }
    },
    position: {
        my: 'top left',
        at: 'bottom right',
        target: FALSE,
        container: FALSE,
        viewport: FALSE,
        adjust: {
            x: 0, y: 0,
            mouse: TRUE,
            resize: TRUE,
            method: 'flipinvert flipinvert'
        },
        effect: function(api, pos, viewport) {
            $(this).animate(pos, {
                duration: 200,
                queue: FALSE
            });
        }
    },
    show: {
        target: FALSE,
        event: 'mouseenter',
        effect: TRUE,
        delay: 90,
        solo: FALSE,
        ready: FALSE,
        autofocus: FALSE
    },
    hide: {
        target: FALSE,
        event: 'mouseleave',
        effect: TRUE,
        delay: 0,
        fixed: FALSE,
        inactive: FALSE,
        leave: 'window',
        distance: FALSE
    },
    style: {
        classes: '',
        widget: FALSE,
        width: FALSE,
        height: FALSE,
        def: TRUE
    },
    events: {
        render: NULL,
        move: NULL,
        show: NULL,
        hide: NULL,
        toggle: NULL,
        visible: NULL,
        hidden: NULL,
        focus: NULL,
        blur: NULL
    }
};


PLUGINS.svg = function(api, svg, corner, adjustMethod)
{
    var doc = $(document),
        elem = svg[0],
        result = {
            width: 0, height: 0,
            position: { top: 1e10, left: 1e10 }
        },
        box, mtx, root, point, tPoint;

    // Ascend the parentNode chain until we find an element with getBBox()
    while(!elem.getBBox) { elem = elem.parentNode; }

    // Check for a valid bounding box method
    if (elem.getBBox && elem.parentNode) {
        box = elem.getBBox();
        mtx = elem.getScreenCTM();
        root = elem.farthestViewportElement || elem;

        // Return if no method is found
        if(!root.createSVGPoint) { return result; }

        // Create our point var
        point = root.createSVGPoint();

        // Adjust top and left
        point.x = box.x;
        point.y = box.y;
        tPoint = point.matrixTransform(mtx);
        result.position.left = tPoint.x;
        result.position.top = tPoint.y;

        // Adjust width and height
        point.x += box.width;
        point.y += box.height;
        tPoint = point.matrixTransform(mtx);
        result.width = tPoint.x - result.position.left;
        result.height = tPoint.y - result.position.top;

        // Adjust by scroll offset
        result.position.left += doc.scrollLeft();
        result.position.top += doc.scrollTop();
    }

    return result;
};


function Ajax(api)
{
    var self = this,
        tooltip = api.elements.tooltip,
        opts = api.options.content.ajax,
        defaults = QTIP.defaults.content.ajax,
        namespace = '.qtip-ajax',
        rscript = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        first = TRUE,
        stop = FALSE,
        xhr;

    api.checks.ajax = {
        '^content.ajax': function(obj, name, v) {
            // If content.ajax object was reset, set our local var
            if(name === 'ajax') { opts = v; }

            if(name === 'once') {
                self.init();
            }
            else if(opts && opts.url) {
                self.load();
            }
            else {
                tooltip.unbind(namespace);
            }
        }
    };

    $.extend(self, {
        init: function() {
            // Make sure ajax options are enabled and bind event
            if(opts && opts.url) {
                tooltip.unbind(namespace)[ opts.once ? 'one' : 'bind' ]('tooltipshow'+namespace, self.load);
            }

            return self;
        },

        load: function(event) {
            if(stop) {stop = FALSE; return; }

            var hasSelector = opts.url.lastIndexOf(' '),
                url = opts.url,
                selector,
                hideFirst = !opts.loading && first;

            // If loading option is disabled, prevent the tooltip showing until we've completed the request
            if(hideFirst) { try{ event.preventDefault(); } catch(e) {} }

            // Make sure default event hasn't been prevented
            else if(event && event.isDefaultPrevented()) { return self; }

            // Cancel old request
            if(xhr && xhr.abort) { xhr.abort(); }
            
            // Check if user delcared a content selector like in .load()
            if(hasSelector > -1) {
                selector = url.substr(hasSelector);
                url = url.substr(0, hasSelector);
            }

            // Define common after callback for both success/error handlers
            function after() {
                var complete;

                // Don't proceed if tooltip is destroyed
                if(api.destroyed) { return; }

                // Set first flag to false
                first = FALSE;

                // Re-display tip if loading and first time, and reset first flag
                if(hideFirst) { stop = TRUE; api.show(event.originalEvent); }

                // Call users complete method if it was defined
                if((complete = defaults.complete || opts.complete) && $.isFunction(complete)) {
                    complete.apply(opts.context || api, arguments);
                }
            }

            // Define success handler
            function successHandler(content, status, jqXHR) {
                var success;

                // Don't proceed if tooltip is destroyed
                if(api.destroyed) { return; }

                // If URL contains a selector
                if(selector && 'string' === typeof content) {
                    // Create a dummy div to hold the results and grab the selector element
                    content = $('<div/>')
                        // inject the contents of the document in, removing the scripts
                        // to avoid any 'Permission Denied' errors in IE
                        .append(content.replace(rscript, ""))
                        
                        // Locate the specified elements
                        .find(selector);
                }

                // Call the success function if one is defined
                if((success = defaults.success || opts.success) && $.isFunction(success)) {
                    success.call(opts.context || api, content, status, jqXHR);
                }

                // Otherwise set the content
                else { api.set('content.text', content); }
            }

            // Error handler
            function errorHandler(xhr, status, error) {
                if(api.destroyed || xhr.status === 0) { return; }
                api.set('content.text', status + ': ' + error);
            }

            // Setup $.ajax option object and process the request
            xhr = $.ajax(
                $.extend({
                    error: defaults.error || errorHandler,
                    context: api
                },
                opts, { url: url, success: successHandler, complete: after })
            );
        },

        destroy: function() {
            // Cancel ajax request if possible
            if(xhr && xhr.abort) { xhr.abort(); }

            // Set api.destroyed flag
            api.destroyed = TRUE;
        }
    });

    self.init();
}


PLUGINS.ajax = function(api)
{
    var self = api.plugins.ajax;
    
    return 'object' === typeof self ? self : (api.plugins.ajax = new Ajax(api));
};

PLUGINS.ajax.initialize = 'render';

// Setup plugin sanitization
PLUGINS.ajax.sanitize = function(options)
{
    var content = options.content, opts;
    if(content && 'ajax' in content) {
        opts = content.ajax;
        if(typeof opts !== 'object') { opts = options.content.ajax = { url: opts }; }
        if('boolean' !== typeof opts.once && opts.once) { opts.once = !!opts.once; }
    }
};

// Extend original api defaults
$.extend(TRUE, QTIP.defaults, {
    content: {
        ajax: {
            loading: TRUE,
            once: TRUE
        }
    }
});


// Tip coordinates calculator
function calculateTip(corner, width, height)
{   
    var width2 = Math.ceil(width / 2), height2 = Math.ceil(height / 2),

    // Define tip coordinates in terms of height and width values
    tips = {
        bottomright:    [[0,0],             [width,height],     [width,0]],
        bottomleft:     [[0,0],             [width,0],              [0,height]],
        topright:       [[0,height],        [width,0],              [width,height]],
        topleft:            [[0,0],             [0,height],             [width,height]],
        topcenter:      [[0,height],        [width2,0],             [width,height]],
        bottomcenter:   [[0,0],             [width,0],              [width2,height]],
        rightcenter:    [[0,0],             [width,height2],        [0,height]],
        leftcenter:     [[width,0],         [width,height],     [0,height2]]
    };

    // Set common side shapes
    tips.lefttop = tips.bottomright; tips.righttop = tips.bottomleft;
    tips.leftbottom = tips.topright; tips.rightbottom = tips.topleft;

    return tips[ corner.string() ];
}


function Tip(qTip, command)
{
    var self = this,
        opts = qTip.options.style.tip,
        elems = qTip.elements,
        tooltip = elems.tooltip,
        cache = { top: 0, left: 0 },
        size = {
            width: opts.width,
            height: opts.height
        },
        color = { },
        border = opts.border || 0,
        namespace = '.qtip-tip',
        hasCanvas = !!($('<canvas />')[0] || {}).getContext,
        tiphtml;

    self.corner = NULL;
    self.mimic = NULL;
    self.border = border;
    self.offset = opts.offset;
    self.size = size;

    // Add new option checks for the plugin
    qTip.checks.tip = {
        '^position.my|style.tip.(corner|mimic|border)$': function() {
            // Make sure a tip can be drawn
            if(!self.init()) {
                self.destroy();
            }

            // Reposition the tooltip
            qTip.reposition();
        },
        '^style.tip.(height|width)$': function() {
            // Re-set dimensions and redraw the tip
            size = {
                width: opts.width,
                height: opts.height
            };
            self.create();
            self.update();

            // Reposition the tooltip
            qTip.reposition();
        },
        '^content.title.text|style.(classes|widget)$': function() {
            if(elems.tip && elems.tip.length) {
                self.update();
            }
        }
    };

    function whileVisible(callback) {
        var visible = tooltip.is(':visible');
        tooltip.show(); callback(); tooltip.toggle(visible);
    }

    function swapDimensions() {
        size.width = opts.height;
        size.height = opts.width;
    }

    function resetDimensions() {
        size.width = opts.width;
        size.height = opts.height;
    }

    function reposition(event, api, pos, viewport) {
        if(!elems.tip) { return; }

        var newCorner = self.corner.clone(),
            adjust = pos.adjusted,
            method = qTip.options.position.adjust.method.split(' '),
            horizontal = method[0],
            vertical = method[1] || method[0],
            shift = { left: FALSE, top: FALSE, x: 0, y: 0 },
            offset, css = {}, props;

        // If our tip position isn't fixed e.g. doesn't adjust with viewport...
        if(self.corner.fixed !== TRUE) {
            // Horizontal - Shift or flip method
            if(horizontal === SHIFT && newCorner.precedance === X && adjust.left && newCorner.y !== CENTER) {
                newCorner.precedance = newCorner.precedance === X ? Y : X;
            }
            else if(horizontal !== SHIFT && adjust.left){
                newCorner.x = newCorner.x === CENTER ? (adjust.left > 0 ? LEFT : RIGHT) : (newCorner.x === LEFT ? RIGHT : LEFT);
            }

            // Vertical - Shift or flip method
            if(vertical === SHIFT && newCorner.precedance === Y && adjust.top && newCorner.x !== CENTER) {
                newCorner.precedance = newCorner.precedance === Y ? X : Y;
            }
            else if(vertical !== SHIFT && adjust.top) {
                newCorner.y = newCorner.y === CENTER ? (adjust.top > 0 ? TOP : BOTTOM) : (newCorner.y === TOP ? BOTTOM : TOP);
            }

            // Update and redraw the tip if needed (check cached details of last drawn tip)
            if(newCorner.string() !== cache.corner.string() && (cache.top !== adjust.top || cache.left !== adjust.left)) {
                self.update(newCorner, FALSE);
            }
        }

        // Setup tip offset properties
        offset = self.position(newCorner, adjust);
        offset[ newCorner.x ] += parseWidth(newCorner, newCorner.x);
        offset[ newCorner.y ] += parseWidth(newCorner, newCorner.y);

        // Readjust offset object to make it left/top
        if(offset.right !== undefined) { offset.left = -offset.right; }
        if(offset.bottom !== undefined) { offset.top = -offset.bottom; }
        offset.user = Math.max(0, opts.offset);

        // Viewport "shift" specific adjustments
        if(shift.left = (horizontal === SHIFT && !!adjust.left)) {
            if(newCorner.x === CENTER) {
                css['margin-left'] = shift.x = offset['margin-left'];
            }
            else {
                props = offset.right !== undefined ?
                    [ adjust.left, -offset.left ] : [ -adjust.left, offset.left ];

                if( (shift.x = Math.max(props[0], props[1])) > props[0] ) {
                    pos.left -= adjust.left;
                    shift.left = FALSE;
                }
                
                css[ offset.right !== undefined ? RIGHT : LEFT ] = shift.x;
            }
        }
        if(shift.top = (vertical === SHIFT && !!adjust.top)) {
            if(newCorner.y === CENTER) {
                css['margin-top'] = shift.y = offset['margin-top'];
            }
            else {
                props = offset.bottom !== undefined ?
                    [ adjust.top, -offset.top ] : [ -adjust.top, offset.top ];

                if( (shift.y = Math.max(props[0], props[1])) > props[0] ) {
                    pos.top -= adjust.top;
                    shift.top = FALSE;
                }

                css[ offset.bottom !== undefined ? BOTTOM : TOP ] = shift.y;
            }
        }

        /*
        * If the tip is adjusted in both dimensions, or in a
        * direction that would cause it to be anywhere but the
        * outer border, hide it!
        */
        elems.tip.css(css).toggle(
            !((shift.x && shift.y) || (newCorner.x === CENTER && shift.y) || (newCorner.y === CENTER && shift.x))
        );

        // Adjust position to accomodate tip dimensions
        pos.left -= offset.left.charAt ? offset.user : horizontal !== SHIFT || shift.top || !shift.left && !shift.top ? offset.left : 0;
        pos.top -= offset.top.charAt ? offset.user : vertical !== SHIFT || shift.left || !shift.left && !shift.top ? offset.top : 0;

        // Cache details
        cache.left = adjust.left; cache.top = adjust.top;
        cache.corner = newCorner.clone();
    }

    function parseCorner() {
        var corner = opts.corner,
            posOptions = qTip.options.position,
            at = posOptions.at,
            my = posOptions.my.string ? posOptions.my.string() : posOptions.my;

        // Detect corner and mimic properties
        if(corner === FALSE || (my === FALSE && at === FALSE)) {
            return FALSE;
        }
        else {
            if(corner === TRUE) {
                self.corner = new PLUGINS.Corner(my);
            }
            else if(!corner.string) {
                self.corner = new PLUGINS.Corner(corner);
                self.corner.fixed = TRUE;
            }
        }

        // Cache it
        cache.corner = new PLUGINS.Corner( self.corner.string() );

        return self.corner.string() !== 'centercenter';
    }

    /* border width calculator */
    function parseWidth(corner, side, use) {
        side = !side ? corner[corner.precedance] : side;
        
        var isTitleTop = elems.titlebar && corner.y === TOP,
            elem = isTitleTop ? elems.titlebar : tooltip,
            borderSide = 'border-' + side + '-width',
            css = function(elem) { return parseInt(elem.css(borderSide), 10); },
            val;

        // Grab the border-width value (make tooltip visible first)
        whileVisible(function() {
            val = (use ? css(use) : (css(elems.content) || css(elem) || css(tooltip))) || 0;
        });
        return val;
    }

    function parseRadius(corner) {
        var isTitleTop = elems.titlebar && corner.y === TOP,
            elem = isTitleTop ? elems.titlebar : elems.content,
            mozPrefix = '-moz-', webkitPrefix = '-webkit-',
            nonStandard = 'border-radius-' + corner.y + corner.x,
            standard = 'border-' + corner.y + '-' + corner.x + '-radius',
            css = function(c) { return parseInt(elem.css(c), 10) || parseInt(tooltip.css(c), 10); },
            val;

        whileVisible(function() {
            val = css(standard) || css(nonStandard) ||
                css(mozPrefix + standard) || css(mozPrefix + nonStandard) || 
                css(webkitPrefix + standard) || css(webkitPrefix + nonStandard) || 0;
        });
        return val;
    }

    function parseColours(actual) {
        var i, fill, border,
            tip = elems.tip.css('cssText', ''),
            corner = actual || self.corner,
            invalid = /rgba?\(0, 0, 0(, 0)?\)|transparent|#123456/i,
            borderSide = 'border-' + corner[ corner.precedance ] + '-color',
            bgColor = 'background-color',
            transparent = 'transparent',
            important = ' !important',

            titlebar = elems.titlebar,
            useTitle = titlebar && (corner.y === TOP || (corner.y === CENTER && tip.position().top + (size.height / 2) + opts.offset < titlebar.outerHeight(TRUE))),
            colorElem = useTitle ? titlebar : elems.content;

        function css(elem, prop, compare) {
            var val = elem.css(prop) || transparent;
            if(compare && val === elem.css(compare)) { return FALSE; }
            else { return invalid.test(val) ? FALSE : val; }
        }

        // Ensure tooltip is visible then...
        whileVisible(function() {
            // Attempt to detect the background colour from various elements, left-to-right precedance
            color.fill = css(tip, bgColor) || css(colorElem, bgColor) || css(elems.content, bgColor) || 
                css(tooltip, bgColor) || tip.css(bgColor);

            // Attempt to detect the correct border side colour from various elements, left-to-right precedance
            color.border = css(tip, borderSide, 'color') || css(colorElem, borderSide, 'color') || 
                css(elems.content, borderSide, 'color') || css(tooltip, borderSide, 'color') || tooltip.css(borderSide);

            // Reset background and border colours
            $('*', tip).add(tip).css('cssText', bgColor+':'+transparent+important+';border:0'+important+';');
        });
    }

    function calculateSize(corner) {
        var y = corner.precedance === Y,
            width = size [ y ? WIDTH : HEIGHT ],
            height = size [ y ? HEIGHT : WIDTH ],
            isCenter = corner.string().indexOf(CENTER) > -1,
            base = width * (isCenter ? 0.5 : 1),
            pow = Math.pow,
            round = Math.round,
            bigHyp, ratio, result,

        smallHyp = Math.sqrt( pow(base, 2) + pow(height, 2) ),
        
        hyp = [
            (border / base) * smallHyp, (border / height) * smallHyp
        ];
        hyp[2] = Math.sqrt( pow(hyp[0], 2) - pow(border, 2) );
        hyp[3] = Math.sqrt( pow(hyp[1], 2) - pow(border, 2) );

        bigHyp = smallHyp + hyp[2] + hyp[3] + (isCenter ? 0 : hyp[0]);
        ratio = bigHyp / smallHyp;

        result = [ round(ratio * height), round(ratio * width) ];
        return { height: result[ y ? 0 : 1 ], width: result[ y ? 1 : 0 ] };
    }

    function createVML(tag, props, style) {
        return '<qvml:'+tag+' xmlns="urn:schemas-microsoft.com:vml" class="qtip-vml" '+(props||'')+
            ' style="behavior: url(#default#VML); '+(style||'')+ '" />';
    }

    $.extend(self, {
        init: function()
        {
            var enabled = parseCorner() && (hasCanvas || PLUGINS.ie);

            // Determine tip corner and type
            if(enabled) {
                // Create a new tip and draw it
                self.create();
                self.update();

                // Bind update events
                tooltip.unbind(namespace).bind('tooltipmove'+namespace, reposition);
            }
            
            return enabled;
        },

        create: function()
        {
            var width = size.width,
                height = size.height,
                vml;

            // Remove previous tip element if present
            if(elems.tip) { elems.tip.remove(); }

            // Create tip element and prepend to the tooltip
            elems.tip = $('<div />', { 'class': 'qtip-tip' }).css({ width: width, height: height }).prependTo(tooltip);

            // Create tip drawing element(s)
            if(hasCanvas) {
                // save() as soon as we create the canvas element so FF2 doesn't bork on our first restore()!
                $('<canvas />').appendTo(elems.tip)[0].getContext('2d').save();
            }
            else {
                vml = createVML('shape', 'coordorigin="0,0"', 'position:absolute;');
                elems.tip.html(vml + vml);

                // Prevent mousing down on the tip since it causes problems with .live() handling in IE due to VML
                $('*', elems.tip).bind('click mousedown', function(event) { event.stopPropagation(); });
            }
        },

        update: function(corner, position)
        {
            var tip = elems.tip,
                inner = tip.children(),
                width = size.width,
                height = size.height,
                mimic = opts.mimic,
                round = Math.round,
                precedance, context, coords, translate, newSize;

            // Re-determine tip if not already set
            if(!corner) { corner = cache.corner || self.corner; }

            // Use corner property if we detect an invalid mimic value
            if(mimic === FALSE) { mimic = corner; }

            // Otherwise inherit mimic properties from the corner object as necessary
            else {
                mimic = new PLUGINS.Corner(mimic);
                mimic.precedance = corner.precedance;

                if(mimic.x === 'inherit') { mimic.x = corner.x; }
                else if(mimic.y === 'inherit') { mimic.y = corner.y; }
                else if(mimic.x === mimic.y) {
                    mimic[ corner.precedance ] = corner[ corner.precedance ];
                }
            }
            precedance = mimic.precedance;

            // Ensure the tip width.height are relative to the tip position
            if(corner.precedance === X) { swapDimensions(); }
            else { resetDimensions(); }

            // Set the tip dimensions
            elems.tip.css({
                width: (width = size.width),
                height: (height = size.height)
            });

            // Update our colours
            parseColours(corner);

            // Detect border width, taking into account colours
            if(color.border !== 'transparent') {
                // Grab border width
                border = parseWidth(corner, NULL);

                // If border width isn't zero, use border color as fill (1.0 style tips)
                if(opts.border === 0 && border > 0) { color.fill = color.border; }

                // Set border width (use detected border width if opts.border is true)
                self.border = border = opts.border !== TRUE ? opts.border : border;
            }

            // Border colour was invalid, set border to zero
            else { self.border = border = 0; }

            // Calculate coordinates
            coords = calculateTip(mimic, width , height);

            // Determine tip size
            self.size = newSize = calculateSize(corner);
            tip.css(newSize).css('line-height', newSize.height+'px');

            // Calculate tip translation
            if(corner.precedance === Y) {
                translate = [
                    round(mimic.x === LEFT ? border : mimic.x === RIGHT ? newSize.width - width - border : (newSize.width - width) / 2),
                    round(mimic.y === TOP ? newSize.height - height : 0)
                ];
            }
            else {
                translate = [
                    round(mimic.x === LEFT ? newSize.width - width : 0),
                    round(mimic.y === TOP ? border : mimic.y === BOTTOM ? newSize.height - height - border : (newSize.height - height) / 2)
                ];
            }

            // Canvas drawing implementation
            if(hasCanvas) {
                // Set the canvas size using calculated size
                inner.attr(newSize);

                // Grab canvas context and clear/save it
                context = inner[0].getContext('2d');
                context.restore(); context.save();
                context.clearRect(0,0,3000,3000);

                // Set properties
                context.fillStyle = color.fill;
                context.strokeStyle = color.border;
                context.lineWidth = border * 2;
                context.lineJoin = 'miter';
                context.miterLimit = 100;

                // Translate origin
                context.translate(translate[0], translate[1]);

                // Draw the tip
                context.beginPath();
                context.moveTo(coords[0][0], coords[0][1]);
                context.lineTo(coords[1][0], coords[1][1]);
                context.lineTo(coords[2][0], coords[2][1]);
                context.closePath();

                // Apply fill and border
                if(border) {
                    // Make sure transparent borders are supported by doing a stroke
                    // of the background colour before the stroke colour
                    if(tooltip.css('background-clip') === 'border-box') {
                        context.strokeStyle = color.fill;
                        context.stroke();
                    }
                    context.strokeStyle = color.border;
                    context.stroke();
                }
                context.fill();
            }

            // VML (IE Proprietary implementation)
            else {
                // Setup coordinates string
                coords = 'm' + coords[0][0] + ',' + coords[0][1] + ' l' + coords[1][0] +
                    ',' + coords[1][1] + ' ' + coords[2][0] + ',' + coords[2][1] + ' xe';

                // Setup VML-specific offset for pixel-perfection
                translate[2] = border && /^(r|b)/i.test(corner.string()) ? 
                    PLUGINS.ie === 8 ? 2 : 1 : 0;

                // Set initial CSS
                inner.css({
                    coordsize: (width+border) + ' ' + (height+border),
                    antialias: ''+(mimic.string().indexOf(CENTER) > -1),
                    left: translate[0],
                    top: translate[1],
                    width: width + border,
                    height: height + border
                })
                .each(function(i) {
                    var $this = $(this);

                    // Set shape specific attributes
                    $this[ $this.prop ? 'prop' : 'attr' ]({
                        coordsize: (width+border) + ' ' + (height+border),
                        path: coords,
                        fillcolor: color.fill,
                        filled: !!i,
                        stroked: !i
                    })
                    .toggle(!!(border || i));

                    // Check if border is enabled and add stroke element
                    if(!i && $this.html() === '') {
                        $this.html(
                            createVML('stroke', 'weight="'+(border*2)+'px" color="'+color.border+'" miterlimit="1000" joinstyle="miter"')
                        );
                    }
                });
            }

            // Position if needed
            if(position !== FALSE) { self.position(corner); }
        },

        // Tip positioning method
        position: function(corner)
        {
            var tip = elems.tip,
                position = {},
                userOffset = Math.max(0, opts.offset),
                precedance, dimensions, corners;

            // Return if tips are disabled or tip is not yet rendered
            if(opts.corner === FALSE || !tip) { return FALSE; }

            // Inherit corner if not provided
            corner = corner || self.corner;
            precedance = corner.precedance;

            // Determine which tip dimension to use for adjustment
            dimensions = calculateSize(corner);

            // Setup corners and offset array
            corners = [ corner.x, corner.y ];
            if(precedance === X) { corners.reverse(); }

            // Calculate tip position
            $.each(corners, function(i, side) {
                var b, bc, br;

                if(side === CENTER) {
                    b = precedance === Y ? LEFT : TOP;
                    position[ b ] = '50%';
                    position['margin-' + b] = -Math.round(dimensions[ precedance === Y ? WIDTH : HEIGHT ] / 2) + userOffset;
                }
                else {
                    b = parseWidth(corner, side);
                    bc = parseWidth(corner, side, elems.content);
                    br = parseRadius(corner);

                    position[ side ] = i ? bc : (userOffset + (br > b ? br : -b));
                }
            });

            // Adjust for tip dimensions
            position[ corner[precedance] ] -= dimensions[ precedance === X ? WIDTH : HEIGHT ];

            // Set and return new position
            tip.css({ top: '', bottom: '', left: '', right: '', margin: '' }).css(position);
            return position;
        },
        
        destroy: function()
        {
            // Remove the tip element
            if(elems.tip) { elems.tip.remove(); }
            elems.tip = false;

            // Unbind events
            tooltip.unbind(namespace);
        }
    });

    self.init();
}

PLUGINS.tip = function(api)
{
    var self = api.plugins.tip;
    
    return 'object' === typeof self ? self : (api.plugins.tip = new Tip(api));
};

// Initialize tip on render
PLUGINS.tip.initialize = 'render';

// Setup plugin sanitization options
PLUGINS.tip.sanitize = function(options)
{
    var style = options.style, opts;
    if(style && 'tip' in style) {
        opts = options.style.tip;
        if(typeof opts !== 'object'){ options.style.tip = { corner: opts }; }
        if(!(/string|boolean/i).test(typeof opts['corner'])) { opts['corner'] = TRUE; }
        if(typeof opts.width !== 'number'){ delete opts.width; }
        if(typeof opts.height !== 'number'){ delete opts.height; }
        if(typeof opts.border !== 'number' && opts.border !== TRUE){ delete opts.border; }
        if(typeof opts.offset !== 'number'){ delete opts.offset; }
    }
};

// Extend original qTip defaults
$.extend(TRUE, QTIP.defaults, {
    style: {
        tip: {
            corner: TRUE,
            mimic: FALSE,
            width: 6,
            height: 6,
            border: TRUE,
            offset: 0
        }
    }
});


function Modal(api)
{
    var self = this,
        options = api.options.show.modal,
        elems = api.elements,
        tooltip = elems.tooltip,
        overlaySelector = '#qtip-overlay',
        globalNamespace = '.qtipmodal',
        namespace = globalNamespace + api.id,
        attr = 'is-modal-qtip',
        docBody = $(document.body),
        focusableSelector = PLUGINS.modal.focusable.join(','),
        focusableElems = {}, overlay;

    // Setup option set checks
    api.checks.modal = {
        '^show.modal.(on|blur)$': function() {
            // Initialise
            self.init();
            
            // Show the modal if not visible already and tooltip is visible
            elems.overlay.toggle( tooltip.is(':visible') );
        },
        '^content.text$': function() {
            updateFocusable();
        }
    };

    function updateFocusable() {
        focusableElems = $(focusableSelector, tooltip).not('[disabled]').map(function() {
            return typeof this.focus === 'function' ? this : null;
        });
    }

    function focusInputs(blurElems) {
        // Blurring body element in IE causes window.open windows to unfocus!
        if(focusableElems.length < 1 && blurElems.length) { blurElems.not('body').blur(); }

        // Focus the inputs
        else { focusableElems.first().focus(); }
    }

    function stealFocus(event) {
        var target = $(event.target),
            container = target.closest('.qtip'),
            targetOnTop;

        // Determine if input container target is above this
        targetOnTop = container.length < 1 ? FALSE :
            (parseInt(container[0].style.zIndex, 10) > parseInt(tooltip[0].style.zIndex, 10));

        // If we're showing a modal, but focus has landed on an input below
        // this modal, divert focus to the first visible input in this modal
        // or if we can't find one... the tooltip itself
        if(!targetOnTop && ($(event.target).closest(selector)[0] !== tooltip[0])) {
            focusInputs(target);
        }
    }

    $.extend(self, {
        init: function()
        {
            // If modal is disabled... return
            if(!options.on) { return self; }

            // Create the overlay if needed
            overlay = self.create();

            // Add unique attribute so we can grab modal tooltips easily via a selector
            tooltip.attr(attr, TRUE)

            // Set z-index
            .css('z-index', PLUGINS.modal.zindex + $(selector+'['+attr+']').length)
            
            // Remove previous bound events in globalNamespace
            .unbind(globalNamespace).unbind(namespace)

            // Apply our show/hide/focus modal events
            .bind('tooltipshow'+globalNamespace+' tooltiphide'+globalNamespace, function(event, api, duration) {
                var oEvent = event.originalEvent;

                // Make sure mouseout doesn't trigger a hide when showing the modal and mousing onto backdrop
                if(event.target === tooltip[0]) {
                    if(oEvent && event.type === 'tooltiphide' && /mouse(leave|enter)/.test(oEvent.type) && $(oEvent.relatedTarget).closest(overlay[0]).length) {
                        try { event.preventDefault(); } catch(e) {}
                    }
                    else if(!oEvent || (oEvent && !oEvent.solo)) {
                        self[ event.type.replace('tooltip', '') ](event, duration);
                    }
                }
            })

            // Adjust modal z-index on tooltip focus
            .bind('tooltipfocus'+globalNamespace, function(event) {
                // If focus was cancelled before it reearch us, don't do anything
                if(event.isDefaultPrevented() || event.target !== tooltip[0]) { return; }

                var qtips = $(selector).filter('['+attr+']'),

                // Keep the modal's lower than other, regular qtips
                newIndex = PLUGINS.modal.zindex + qtips.length,
                curIndex = parseInt(tooltip[0].style.zIndex, 10);

                // Set overlay z-index
                overlay[0].style.zIndex = newIndex - 2;

                // Reduce modal z-index's and keep them properly ordered
                qtips.each(function() {
                    if(this.style.zIndex > curIndex) {
                        this.style.zIndex -= 1;
                    }
                });

                // Fire blur event for focused tooltip
                qtips.end().filter('.' + focusClass).qtip('blur', event.originalEvent);

                // Set the new z-index
                tooltip.addClass(focusClass)[0].style.zIndex = newIndex;

                // Prevent default handling
                try { event.preventDefault(); } catch(e) {}
            })

            // Focus any other visible modals when this one hides
            .bind('tooltiphide'+globalNamespace, function(event) {
                if(event.target === tooltip[0]) {
                    $('[' + attr + ']').filter(':visible').not(tooltip).last().qtip('focus', event);
                }
            });

            // Apply keyboard "Escape key" close handler
            if(options.escape) {
                $(document).unbind(namespace).bind('keydown'+namespace, function(event) {
                    if(event.keyCode === 27 && tooltip.hasClass(focusClass)) {
                        api.hide(event);
                    }
                });
            }

            // Apply click handler for blur option
            if(options.blur) {
                elems.overlay.unbind(namespace).bind('click'+namespace, function(event) {
                    if(tooltip.hasClass(focusClass)) { api.hide(event); }
                });
            }

            // Update focusable elements
            updateFocusable();

            return self;
        },

        create: function()
        {
            var elem = $(overlaySelector), win = $(window);

            // Return if overlay is already rendered
            if(elem.length) {
                // Modal overlay should always be below all tooltips if possible
                return (elems.overlay = elem.insertAfter( $(selector).last() ));
            }

            // Create document overlay
            overlay = elems.overlay = $('<div />', {
                id: overlaySelector.substr(1),
                html: '<div></div>',
                mousedown: function() { return FALSE; }
            })
            .hide()
            .insertAfter( $(selector).last() );

            // Update position on window resize or scroll
            function resize() {
                overlay.css({
                    height: win.height(),
                    width: win.width()
                });
            }
            win.unbind(globalNamespace).bind('resize'+globalNamespace, resize);
            resize(); // Fire it initially too

            return overlay;
        },

        toggle: function(event, state, duration)
        {
            // Make sure default event hasn't been prevented
            if(event && event.isDefaultPrevented()) { return self; }

            var effect = options.effect,
                type = state ? 'show': 'hide',
                visible = overlay.is(':visible'),
                modals = $('[' + attr + ']').filter(':visible').not(tooltip),
                zindex;

            // Create our overlay if it isn't present already
            if(!overlay) { overlay = self.create(); }

            // Prevent modal from conflicting with show.solo, and don't hide backdrop is other modals are visible
            if((overlay.is(':animated') && visible === state && overlay.data('toggleState') !== FALSE) || (!state && modals.length)) {
                return self;
            }

            // State specific...
            if(state) {
                // Set position
                overlay.css({ left: 0, top: 0 });

                // Toggle backdrop cursor style on show
                overlay.toggleClass('blurs', options.blur);

                // IF the modal can steal the focus
                if(options.stealfocus !== FALSE) {
                    // Make sure we can't focus anything outside the tooltip
                    docBody.bind('focusin'+namespace, stealFocus);

                    // Blur the current item and focus anything in the modal we an
                    focusInputs( $('body :focus') );
                }
            }
            else {
                // Undelegate focus handler
                docBody.unbind('focusin'+namespace);
            }

            // Stop all animations
            overlay.stop(TRUE, FALSE).data('toggleState', state);

            // Use custom function if provided
            if($.isFunction(effect)) {
                effect.call(overlay, state);
            }

            // If no effect type is supplied, use a simple toggle
            else if(effect === FALSE) {
                overlay[ type ]();
            }

            // Use basic fade function
            else {
                overlay.fadeTo( parseInt(duration, 10) || 90, state ? 1 : 0, function() {
                    if(!state) { $(this).hide(); }
                });
            }

            // Reset position on hide
            if(!state) {
                overlay.queue(function(next) {
                    overlay.css({ left: '', top: '' }).removeData('toggleState');
                    next();
                });
            }

            return self;
        },

        show: function(event, duration) { return self.toggle(event, TRUE, duration); },
        hide: function(event, duration) { return self.toggle(event, FALSE, duration); },

        destroy: function()
        {
            var delBlanket = overlay;

            if(delBlanket) {
                // Check if any other modal tooltips are present
                delBlanket = $('[' + attr + ']').not(tooltip).length < 1;

                // Remove overlay if needed
                if(delBlanket) {
                    elems.overlay.remove();
                    $(document).unbind(globalNamespace);
                }
                else {
                    elems.overlay.unbind(globalNamespace+api.id);
                }

                // Undelegate focus handler
                docBody.unbind('focusin'+namespace);
            }

            // Remove bound events
            return tooltip.removeAttr(attr).unbind(globalNamespace);
        }
    });

    self.init();
}

PLUGINS.modal = function(api) {
    var self = api.plugins.modal;

    return 'object' === typeof self ? self : (api.plugins.modal = new Modal(api));
};

// Plugin needs to be initialized on render
PLUGINS.modal.initialize = 'render';

// Setup sanitiztion rules
PLUGINS.modal.sanitize = function(opts) {
    if(opts.show) { 
        if(typeof opts.show.modal !== 'object') { opts.show.modal = { on: !!opts.show.modal }; }
        else if(typeof opts.show.modal.on === 'undefined') { opts.show.modal.on = TRUE; }
    }
};

// Base z-index for all modal tooltips (use qTip core z-index as a base)
PLUGINS.modal.zindex = QTIP.zindex - 200;

// Defines the selector used to select all 'focusable' elements within the modal when using the show.modal.stealfocus option.
// Selectors initially taken from http://stackoverflow.com/questions/7668525/is-there-a-jquery-selector-to-get-all-elements-that-can-get-focus
PLUGINS.modal.focusable = ['a[href]', 'area[href]', 'input', 'select', 'textarea', 'button', 'iframe', 'object', 'embed', '[tabindex]', '[contenteditable]'];

// Extend original api defaults
$.extend(TRUE, QTIP.defaults, {
    show: {
        modal: {
            on: FALSE,
            effect: TRUE,
            blur: TRUE,
            stealfocus: TRUE,
            escape: TRUE
        }
    }
});


PLUGINS.viewport = function(api, position, posOptions, targetWidth, targetHeight, elemWidth, elemHeight)
{
    var target = posOptions.target,
        tooltip = api.elements.tooltip,
        my = posOptions.my,
        at = posOptions.at,
        adjust = posOptions.adjust,
        method = adjust.method.split(' '),
        methodX = method[0],
        methodY = method[1] || method[0],
        viewport = posOptions.viewport,
        container = posOptions.container,
        cache = api.cache,
        tip = api.plugins.tip,
        adjusted = { left: 0, top: 0 },
        fixed, newMy, newClass;

    // If viewport is not a jQuery element, or it's the window/document or no adjustment method is used... return
    if(!viewport.jquery || target[0] === window || target[0] === document.body || adjust.method === 'none') {
        return adjusted;
    }

    // Cache our viewport details
    fixed = tooltip.css('position') === 'fixed';
    viewport = {
        elem: viewport,
        height: viewport[ (viewport[0] === window ? 'h' : 'outerH') + 'eight' ](),
        width: viewport[ (viewport[0] === window ? 'w' : 'outerW') + 'idth' ](),
        scrollleft: fixed ? 0 : viewport.scrollLeft(),
        scrolltop: fixed ? 0 : viewport.scrollTop(),
        offset: viewport.offset() || { left: 0, top: 0 }
    };
    container = {
        elem: container,
        scrollLeft: container.scrollLeft(),
        scrollTop: container.scrollTop(),
        offset: container.offset() || { left: 0, top: 0 }
    };

    // Generic calculation method
    function calculate(side, otherSide, type, adjust, side1, side2, lengthName, targetLength, elemLength) {
        var initialPos = position[side1],
            mySide = my[side], atSide = at[side],
            isShift = type === SHIFT,
            viewportScroll = -container.offset[side1] + viewport.offset[side1] + viewport['scroll'+side1],
            myLength = mySide === side1 ? elemLength : mySide === side2 ? -elemLength : -elemLength / 2,
            atLength = atSide === side1 ? targetLength : atSide === side2 ? -targetLength : -targetLength / 2,
            tipLength = tip && tip.size ? tip.size[lengthName] || 0 : 0,
            tipAdjust = tip && tip.corner && tip.corner.precedance === side && !isShift ? tipLength : 0,
            overflow1 = viewportScroll - initialPos + tipAdjust,
            overflow2 = initialPos + elemLength - viewport[lengthName] - viewportScroll + tipAdjust,
            offset = myLength - (my.precedance === side || mySide === my[otherSide] ? atLength : 0) - (atSide === CENTER ? targetLength / 2 : 0);

        // shift
        if(isShift) {
            tipAdjust = tip && tip.corner && tip.corner.precedance === otherSide ? tipLength : 0;
            offset = (mySide === side1 ? 1 : -1) * myLength - tipAdjust;

            // Adjust position but keep it within viewport dimensions
            position[side1] += overflow1 > 0 ? overflow1 : overflow2 > 0 ? -overflow2 : 0;
            position[side1] = Math.max(
                -container.offset[side1] + viewport.offset[side1] + (tipAdjust && tip.corner[side] === CENTER ? tip.offset : 0),
                initialPos - offset,
                Math.min(
                    Math.max(-container.offset[side1] + viewport.offset[side1] + viewport[lengthName], initialPos + offset),
                    position[side1]
                )
            );
        }

        // flip/flipinvert
        else {
            // Update adjustment amount depending on if using flipinvert or flip
            adjust *= (type === FLIPINVERT ? 2 : 0);

            // Check for overflow on the left/top
            if(overflow1 > 0 && (mySide !== side1 || overflow2 > 0)) {
                position[side1] -= offset + adjust;
                newMy['invert'+side](side1);
            }

            // Check for overflow on the bottom/right
            else if(overflow2 > 0 && (mySide !== side2 || overflow1 > 0)  ) {
                position[side1] -= (mySide === CENTER ? -offset : offset) + adjust;
                newMy['invert'+side](side2);
            }

            // Make sure we haven't made things worse with the adjustment and reset if so
            if(position[side1] < viewportScroll && -position[side1] > overflow2) {
                position[side1] = initialPos; newMy = my.clone();
            }
        }

        return position[side1] - initialPos;
    }

    // Set newMy if using flip or flipinvert methods
    if(methodX !== 'shift' || methodY !== 'shift') { newMy = my.clone(); }

    // Adjust position based onviewport and adjustment options
    adjusted = {
        left: methodX !== 'none' ? calculate( X, Y, methodX, adjust.x, LEFT, RIGHT, WIDTH, targetWidth, elemWidth ) : 0,
        top: methodY !== 'none' ? calculate( Y, X, methodY, adjust.y, TOP, BOTTOM, HEIGHT, targetHeight, elemHeight ) : 0
    };

    // Set tooltip position class if it's changed
    if(newMy && cache.lastClass !== (newClass = NAMESPACE + '-pos-' + newMy.abbrev())) {
        tooltip.removeClass(api.cache.lastClass).addClass( (api.cache.lastClass = newClass) );
    }

    return adjusted;
};
PLUGINS.imagemap = function(api, area, corner, adjustMethod)
{
    if(!area.jquery) { area = $(area); }

    var cache = (api.cache.areas = {}),
        shape = (area[0].shape || area.attr('shape')).toLowerCase(),
        coordsString = area[0].coords || area.attr('coords'),
        baseCoords = coordsString.split(','),
        coords = [],
        image = $('img[usemap="#'+area.parent('map').attr('name')+'"]'),
        imageOffset = image.offset(),
        result = {
            width: 0, height: 0,
            position: {
                top: 1e10, right: 0,
                bottom: 0, left: 1e10
            }
        },
        i = 0, next = 0, dimensions;

    // POLY area coordinate calculator
    //  Special thanks to Ed Cradock for helping out with this.
    //  Uses a binary search algorithm to find suitable coordinates.
    function polyCoordinates(result, coords, corner)
    {
        var i = 0,
            compareX = 1, compareY = 1,
            realX = 0, realY = 0,
            newWidth = result.width,
            newHeight = result.height;

        // Use a binary search algorithm to locate most suitable coordinate (hopefully)
        while(newWidth > 0 && newHeight > 0 && compareX > 0 && compareY > 0)
        {
            newWidth = Math.floor(newWidth / 2);
            newHeight = Math.floor(newHeight / 2);

            if(corner.x === LEFT){ compareX = newWidth; }
            else if(corner.x === RIGHT){ compareX = result.width - newWidth; }
            else{ compareX += Math.floor(newWidth / 2); }

            if(corner.y === TOP){ compareY = newHeight; }
            else if(corner.y === BOTTOM){ compareY = result.height - newHeight; }
            else{ compareY += Math.floor(newHeight / 2); }

            i = coords.length; while(i--)
            {
                if(coords.length < 2){ break; }

                realX = coords[i][0] - result.position.left;
                realY = coords[i][1] - result.position.top;

                if((corner.x === LEFT && realX >= compareX) ||
                (corner.x === RIGHT && realX <= compareX) ||
                (corner.x === CENTER && (realX < compareX || realX > (result.width - compareX))) ||
                (corner.y === TOP && realY >= compareY) ||
                (corner.y === BOTTOM && realY <= compareY) ||
                (corner.y === CENTER && (realY < compareY || realY > (result.height - compareY)))) {
                    coords.splice(i, 1);
                }
            }
        }

        return { left: coords[0][0], top: coords[0][1] };
    }

    // Make sure we account for padding and borders on the image
    imageOffset.left += Math.ceil((image.outerWidth() - image.width()) / 2);
    imageOffset.top += Math.ceil((image.outerHeight() - image.height()) / 2);

    // Parse coordinates into proper array
    if(shape === 'poly') {
        i = baseCoords.length; while(i--)
        {
            next = [ parseInt(baseCoords[--i], 10), parseInt(baseCoords[i+1], 10) ];

            if(next[0] > result.position.right){ result.position.right = next[0]; }
            if(next[0] < result.position.left){ result.position.left = next[0]; }
            if(next[1] > result.position.bottom){ result.position.bottom = next[1]; }
            if(next[1] < result.position.top){ result.position.top = next[1]; }

            coords.push(next);
        }
    }
    else {
        i = -1; while(i++ < baseCoords.length) {
            coords.push( parseInt(baseCoords[i], 10) );
        }
    }

    // Calculate details
    switch(shape)
    {
        case 'rect':
            result = {
                width: Math.abs(coords[2] - coords[0]),
                height: Math.abs(coords[3] - coords[1]),
                position: {
                    left: Math.min(coords[0], coords[2]),
                    top: Math.min(coords[1], coords[3])
                }
            };
        break;

        case 'circle':
            result = {
                width: coords[2] + 2,
                height: coords[2] + 2,
                position: { left: coords[0], top: coords[1] }
            };
        break;

        case 'poly':
            result.width = Math.abs(result.position.right - result.position.left);
            result.height = Math.abs(result.position.bottom - result.position.top);

            if(corner.abbrev() === 'c') {
                result.position = {
                    left: result.position.left + (result.width / 2),
                    top: result.position.top + (result.height / 2)
                };
            }
            else {
                // Calculate if we can't find a cached value
                if(!cache[corner+coordsString]) {
                    result.position = polyCoordinates(result, coords.slice(), corner);

                    // If flip adjustment is enabled, also calculate the closest opposite point
                    if(adjustMethod && (adjustMethod[0] === 'flip' || adjustMethod[1] === 'flip')) {
                        result.offset = polyCoordinates(result, coords.slice(), {
                            x: corner.x === LEFT ? RIGHT : corner.x === RIGHT ? LEFT : CENTER,
                            y: corner.y === TOP ? BOTTOM : corner.y === BOTTOM ? TOP : CENTER
                        });

                        result.offset.left -= result.position.left;
                        result.offset.top -= result.position.top;
                    }

                    // Store the result
                    cache[corner+coordsString] = result;
                }

                // Grab the cached result
                result = cache[corner+coordsString];
            }

            result.width = result.height = 0;
        break;
    }

    // Add image position to offset coordinates
    result.position.left += imageOffset.left;
    result.position.top += imageOffset.top;

    return result;
};


/* 
 * BGIFrame adaption (http://plugins.jquery.com/project/bgiframe)
 * Special thanks to Brandon Aaron
 */
function IE6(api)
{
    var self = this,
        elems = api.elements,
        options = api.options,
        tooltip = elems.tooltip,
        namespace = '.ie6-' + api.id,
        bgiframe = $('select, object').length < 1,
        isDrawing = 0,
        modalProcessed = FALSE,
        redrawContainer;

    api.checks.ie6 = {
        '^content|style$': function(obj, o, v){ redraw(); }
    };

    $.extend(self, {
        init: function()
        {
            var win = $(window), scroll;

            // Create the BGIFrame element if needed
            if(bgiframe) {
                elems.bgiframe = $('<iframe class="qtip-bgiframe" frameborder="0" tabindex="-1" src="javascript:\'\';" ' +
                    ' style="display:block; position:absolute; z-index:-1; filter:alpha(opacity=0); ' +
                        '-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";"></iframe>');

                // Append the new element to the tooltip
                elems.bgiframe.appendTo(tooltip);

                // Update BGIFrame on tooltip move
                tooltip.bind('tooltipmove'+namespace, self.adjustBGIFrame); 
            }

            // redraw() container for width/height calculations
            redrawContainer = $('<div/>', { id: 'qtip-rcontainer' })
                .appendTo(document.body);

            // Set dimensions
            self.redraw();

            // Fixup modal plugin if present too
            if(elems.overlay && !modalProcessed) {
                scroll = function() {
                    elems.overlay[0].style.top = win.scrollTop() + 'px';
                };
                win.bind('scroll.qtip-ie6, resize.qtip-ie6', scroll);
                scroll(); // Fire it initially too

                elems.overlay.addClass('qtipmodal-ie6fix'); // Add fix class

                modalProcessed = TRUE; // Set flag
            }
        },

        adjustBGIFrame: function()
        {
            var dimensions = api.get('dimensions'), // Determine current tooltip dimensions
                plugin = api.plugins.tip,
                tip = elems.tip,
                tipAdjust, offset;

            // Adjust border offset
            offset = parseInt(tooltip.css('border-left-width'), 10) || 0;
            offset = { left: -offset, top: -offset };

            // Adjust for tips plugin
            if(plugin && tip) {
                tipAdjust = (plugin.corner.precedance === 'x') ? ['width', 'left'] : ['height', 'top'];
                offset[ tipAdjust[1] ] -= tip[ tipAdjust[0] ]();
            }

            // Update bgiframe
            elems.bgiframe.css(offset).css(dimensions);
        },

        // Max/min width simulator function
        redraw: function()
        {
            if(api.rendered < 1 || isDrawing) { return self; }

            var style = options.style,
                container = options.position.container,
                perc, width, max, min;

            // Set drawing flag
            isDrawing = 1;

            // If tooltip has a set height/width, just set it... like a boss!
            if(style.height) { tooltip.css(HEIGHT, style.height); }
            if(style.width) { tooltip.css(WIDTH, style.width); }

            // Simulate max/min width if not set width present...
            else {
                // Reset width and add fluid class
                tooltip.css(WIDTH, '').appendTo(redrawContainer);

                // Grab our tooltip width (add 1 if odd so we don't get wrapping problems.. huzzah!)
                width = tooltip.width();
                if(width % 2 < 1) { width += 1; }

                // Grab our max/min properties
                max = tooltip.css('max-width') || '';
                min = tooltip.css('min-width') || '';

                // Parse into proper pixel values
                perc = (max + min).indexOf('%') > -1 ? container.width() / 100 : 0;
                max = ((max.indexOf('%') > -1 ? perc : 1) * parseInt(max, 10)) || width;
                min = ((min.indexOf('%') > -1 ? perc : 1) * parseInt(min, 10)) || 0;

                // Determine new dimension size based on max/min/current values
                width = max + min ? Math.min(Math.max(width, min), max) : width;

                // Set the newly calculated width and remvoe fluid class
                tooltip.css(WIDTH, Math.round(width)).appendTo(container);
            }

            // Set drawing flag
            isDrawing = 0;

            return self;
        },

        destroy: function()
        {
            // Remove iframe
            if(bgiframe) { elems.bgiframe.remove(); }

            // Remove bound events
            tooltip.unbind(namespace);
        }
    });

    self.init();
}

PLUGINS.ie6 = function(api)
{
    var self = api.plugins.ie6;
    
    // Proceed only if the browser is IE6
    if(PLUGINS.ie !== 6) { return FALSE; }

    return 'object' === typeof self ? self : (api.plugins.ie6 = new IE6(api));
};

// Plugin needs to be initialized on render
PLUGINS.ie6.initialize = 'render';


}));
}( window, document ));

/*! http://mths.be/placeholder v2.0.7 by @mathias */
;(function(window, document, $) {

    var isInputSupported = 'placeholder' in document.createElement('input'),
        isTextareaSupported = 'placeholder' in document.createElement('textarea'),
        prototype = $.fn,
        valHooks = $.valHooks,
        hooks,
        placeholder;

    if (isInputSupported && isTextareaSupported) {

        placeholder = prototype.placeholder = function() {
            return this;
        };

        placeholder.input = placeholder.textarea = true;

    } else {

        placeholder = prototype.placeholder = function() {
            var $this = this;
            $this
                .filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
                .not('.placeholder')
                .bind({
                    'focus.placeholder': clearPlaceholder,
                    'blur.placeholder': setPlaceholder
                })
                .data('placeholder-enabled', true)
                .trigger('blur.placeholder');
            return $this;
        };

        placeholder.input = isInputSupported;
        placeholder.textarea = isTextareaSupported;

        hooks = {
            'get': function(element) {
                var $element = $(element);
                return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
            },
            'set': function(element, value) {
                var $element = $(element);
                if (!$element.data('placeholder-enabled')) {
                    return element.value = value;
                }
                if (value == '') {
                    element.value = value;
                    // Issue #56: Setting the placeholder causes problems if the element continues to have focus.
                    if (element != document.activeElement) {
                        // We can't use `triggerHandler` here because of dummy text/password inputs :(
                        setPlaceholder.call(element);
                    }
                } else if ($element.hasClass('placeholder')) {
                    clearPlaceholder.call(element, true, value) || (element.value = value);
                } else {
                    element.value = value;
                }
                // `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
                return $element;
            }
        };

        isInputSupported || (valHooks.input = hooks);
        isTextareaSupported || (valHooks.textarea = hooks);

        $(function() {
            // Look for forms
            $(document).delegate('form', 'submit.placeholder', function() {
                // Clear the placeholder values so they don't get submitted
                var $inputs = $('.placeholder', this).each(clearPlaceholder);
                setTimeout(function() {
                    $inputs.each(setPlaceholder);
                }, 10);
            });
        });

        // Clear placeholder values upon page reload
        $(window).bind('beforeunload.placeholder', function() {
            $('.placeholder').each(function() {
                this.value = '';
            });
        });

    }

    function args(elem) {
        // Return an object of element attributes
        var newAttrs = {},
            rinlinejQuery = /^jQuery\d+$/;
        $.each(elem.attributes, function(i, attr) {
            if (attr.specified && !rinlinejQuery.test(attr.name)) {
                newAttrs[attr.name] = attr.value;
            }
        });
        return newAttrs;
    }

    function clearPlaceholder(event, value) {
        var input = this,
            $input = $(input);
        if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
            if ($input.data('placeholder-password')) {
                $input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
                // If `clearPlaceholder` was called from `$.valHooks.input.set`
                if (event === true) {
                    return $input[0].value = value;
                }
                $input.focus();
            } else {
                input.value = '';
                $input.removeClass('placeholder');
                input == document.activeElement && input.select();
            }
        }
    }

    function setPlaceholder() {
        var $replacement,
            input = this,
            $input = $(input),
            $origInput = $input,
            id = this.id;
        if (input.value == '') {
            if (input.type == 'password') {
                if (!$input.data('placeholder-textinput')) {
                    try {
                        $replacement = $input.clone().attr({ 'type': 'text' });
                    } catch(e) {
                        $replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
                    }
                    $replacement
                        .removeAttr('name')
                        .data({
                            'placeholder-password': true,
                            'placeholder-id': id
                        })
                        .bind('focus.placeholder', clearPlaceholder);
                    $input
                        .data({
                            'placeholder-textinput': $replacement,
                            'placeholder-id': id
                        })
                        .before($replacement);
                }
                $input = $input.removeAttr('id').hide().prev().attr('id', id).show();
                // Note: `$input[0] != input` now!
            }
            $input.addClass('placeholder');
            $input[0].value = $input.attr('placeholder');
        } else {
            $input.removeClass('placeholder');
        }
    }

}(this, document, jQuery));

/*
* $ lightbox_me
* By: Buck Wilson
* Version : 2.3
*
* Licensed under the Apache License, Version 2.0 (the "License");
* you may not use this file except in compliance with the License.
* You may obtain a copy of the License at
*
*     http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in writing, software
* distributed under the License is distributed on an "AS IS" BASIS,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing permissions and
* limitations under the License.
*/


(function($) {

    $.fn.lightbox_me = function(options) {

        return this.each(function() {

            var
                opts = $.extend({}, $.fn.lightbox_me.defaults, options),
                $overlay = $(),
                $self = $(this),
                $iframe = $('<iframe id="foo" style="z-index: ' + (opts.zIndex + 1) + ';border: none; visibility: visible; margin: 0; padding: 0; position: absolute; width: 100%; height: 100%; top: 0; left: 0; filter: mask();"/>'),
                ie6 = false; //($.browser.msie && $.browser.version < 7);

            if (opts.showOverlay) {
                //check if there's an existing overlay, if so, make subequent ones clear
               var $currentOverlays = $(".js_lb_overlay:visible");
                if ($currentOverlays.length > 0){
                    $overlay = $('<div class="lb_overlay_clear js_lb_overlay"/>');
                } else {
                    $overlay = $('<div class="' + opts.classPrefix + '_overlay js_lb_overlay"/>');
                }
            }

            /*----------------------------------------------------
               DOM Building
            ---------------------------------------------------- */
            if (ie6) {
                var src = /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank';
                $iframe.attr('src', src);
                $('body').append($iframe);
            } // iframe shim for ie6, to hide select elements
            $('body').append($self.hide()).append($overlay);


            /*----------------------------------------------------
               Overlay CSS stuffs
            ---------------------------------------------------- */

            // set css of the overlay
            if (opts.showOverlay) {
                setOverlayHeight(); // pulled this into a function because it is called on window resize.
                $overlay.css({ position: 'absolute', width: '100%', top: 0, left: 0, right: 0, bottom: 0, zIndex: (opts.zIndex + 2), display: 'none' });
                if (!$overlay.hasClass('lb_overlay_clear')){
                    $overlay.css(opts.overlayCSS);
                }
            }

            /*----------------------------------------------------
               Animate it in.
            ---------------------------------------------------- */
               //
            if (opts.showOverlay) {
                $overlay.fadeIn(opts.overlaySpeed, function() {
                    setSelfPosition();
                    $self[opts.appearEffect](opts.lightboxSpeed, function() { setOverlayHeight(); setSelfPosition(); opts.onLoad()});
                });
            } else {
                setSelfPosition();
                $self[opts.appearEffect](opts.lightboxSpeed, function() { opts.onLoad()});
            }

            /*----------------------------------------------------
               Hide parent if parent specified (parentLightbox should be jquery reference to any parent lightbox)
            ---------------------------------------------------- */
            if (opts.parentLightbox) {
                opts.parentLightbox.fadeOut(200);
            }


            /*----------------------------------------------------
               Bind Events
            ---------------------------------------------------- */

            $(window).resize(setOverlayHeight)
                     .resize(setSelfPosition)
                     .scroll(setSelfPosition);
                     
            $(window).bind('keyup.lightbox_me', observeKeyPress);
                     
            if (opts.closeClick) {
                $overlay.click(function(e) { closeLightbox(); e.preventDefault; });
            }
            $self.delegate(opts.closeSelector, "click", function(e) {
                closeLightbox(); e.preventDefault();
            });
            $self.bind('close', closeLightbox);
            $self.bind('reposition', setSelfPosition);

            

            /*--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
              -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- */


            /*----------------------------------------------------
               Private Functions
            ---------------------------------------------------- */

            /* Remove or hide all elements */
            function closeLightbox() {
                var s = $self[0].style;
                if (opts.destroyOnClose) {
                    $self.add($overlay).remove();
                } else {
                    $self.add($overlay).hide();
                }

                //show the hidden parent lightbox
                if (opts.parentLightbox) {
                    opts.parentLightbox.fadeIn(200);
                }

                $iframe.remove();
                
                // clean up events.
                $self.undelegate(opts.closeSelector, "click");

                $(window).unbind('reposition', setOverlayHeight);
                $(window).unbind('reposition', setSelfPosition);
                $(window).unbind('scroll', setSelfPosition);
                $(window).unbind('keyup.lightbox_me');
                if (ie6)
                    s.removeExpression('top');
                opts.onClose();
            }


            /* Function to bind to the window to observe the escape/enter key press */
            function observeKeyPress(e) {
                if((e.keyCode == 27 || (e.DOM_VK_ESCAPE == 27 && e.which==0)) && opts.closeEsc) closeLightbox();
            }


            /* Set the height of the overlay
                    : if the document height is taller than the window, then set the overlay height to the document height.
                    : otherwise, just set overlay height: 100%
            */
            function setOverlayHeight() {
                if ($(window).height() < $(document).height()) {
                    $overlay.css({height: $(document).height() + 'px'});
                     $iframe.css({height: $(document).height() + 'px'}); 
                } else {
                    $overlay.css({height: '100%'});
                    if (ie6) {
                        $('html,body').css('height','100%');
                        $iframe.css('height', '100%');
                    } // ie6 hack for height: 100%; TODO: handle this in IE7
                }
            }


            /* Set the position of the modal'd window ($self)
                    : if $self is taller than the window, then make it absolutely positioned
                    : otherwise fixed
            */
            function setSelfPosition() {
                var s = $self[0].style;

                // reset CSS so width is re-calculated for margin-left CSS
                $self.css(
                {
                    left: '50%', 
                    marginLeft: ($self.outerWidth() / 2) * -1,  
                    zIndex: (opts.zIndex + 3),
                    'visibility': 'visible'
                });


                /* we have to get a little fancy when dealing with height, because lightbox_me
                    is just so fancy.
                 */

                // if the height of $self is bigger than the window and self isn't already position absolute
                if (($self.height() + 80  >= $(window).height()) && ($self.css('position') != 'absolute' || ie6)) {

                    // we are going to make it positioned where the user can see it, but they can still scroll
                    // so the top offset is based on the user's scroll position.
                    var topOffset = $(document).scrollTop() + 40;
                    $self.css({position: 'absolute', top: topOffset + 'px', marginTop: 0})
                    if (ie6) {
                        s.removeExpression('top');
                    }
                } else if ($self.height()+ 80  < $(window).height()) {
                    //if the height is less than the window height, then we're gonna make this thing position: fixed.
                    // in ie6 we're gonna fake it.
                    if (ie6) 
                    {
                        s.position = 'absolute';
                        if (opts.centered) {
                            s.setExpression('top', '(document.documentElement.clientHeight || document.body.clientHeight) / 2 - (this.offsetHeight / 2) + (blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"')
                            s.marginTop = 0;
                        } else {
                            var top = (opts.modalCSS && opts.modalCSS.top) ? parseInt(opts.modalCSS.top) : 0;
                            s.setExpression('top', '((blah = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + '+top+') + "px"')
                        }
                    } else {
                        if (opts.centered) {
                            $self.css({ position: 'fixed', top: '50%', marginTop: ($self.outerHeight() / 2) * -1})
                        } else {
                            $self.css({ position: 'fixed'}).css(opts.modalCSS);
                        }

                    }
                }
            }

        });



    };

    $.fn.lightbox_me.defaults = {

        // animation
        appearEffect: "fadeIn",
        appearEase: "",
        overlaySpeed: 250,
        lightboxSpeed: 300,

        // close
        closeSelector: ".close",
        closeClick: true,
        closeEsc: true,

        // behavior
        destroyOnClose: false,
        showOverlay: true,
        parentLightbox: false,

        // callbacks
        onLoad: function() {},
        onClose: function() {},

        // style
        classPrefix: 'lb',
        zIndex: 999,
        centered: false,
        modalCSS: {top: '40px'},
        overlayCSS: {background: 'black', opacity: .3}
    }
})(jQuery);



/**
 * jquery.Jcrop.js v0.9.12
 * jQuery Image Cropping Plugin - released under MIT License 
 * Author: Kelly Hallman <khallman@gmail.com>
 * http://github.com/tapmodo/Jcrop
 * Copyright (c) 2008-2013 Tapmodo Interactive LLC {{{
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 *
 * }}}
 */

(function ($) {

  $.Jcrop = function (obj, opt) {
    var options = $.extend({}, $.Jcrop.defaults),
        docOffset,
        _ua = navigator.userAgent.toLowerCase(),
        is_msie = /msie/.test(_ua),
        ie6mode = /msie [1-6]\./.test(_ua);

    // Internal Methods {{{
    function px(n) {
      return Math.round(n) + 'px';
    }
    function cssClass(cl) {
      return options.baseClass + '-' + cl;
    }
    function supportsColorFade() {
      return $.fx.step.hasOwnProperty('backgroundColor');
    }
    function getPos(obj) //{{{
    {
      var pos = $(obj).offset();
      return [pos.left, pos.top];
    }
    //}}}
    function mouseAbs(e) //{{{
    {
      return [(e.pageX - docOffset[0]), (e.pageY - docOffset[1])];
    }
    //}}}
    function setOptions(opt) //{{{
    {
      if (typeof(opt) !== 'object') opt = {};
      options = $.extend(options, opt);

      $.each(['onChange','onSelect','onRelease','onDblClick'],function(i,e) {
        if (typeof(options[e]) !== 'function') options[e] = function () {};
      });
    }
    //}}}
    function startDragMode(mode, pos, touch) //{{{
    {
      docOffset = getPos($img);
      Tracker.setCursor(mode === 'move' ? mode : mode + '-resize');

      if (mode === 'move') {
        return Tracker.activateHandlers(createMover(pos), doneSelect, touch);
      }

      var fc = Coords.getFixed();
      var opp = oppLockCorner(mode);
      var opc = Coords.getCorner(oppLockCorner(opp));

      Coords.setPressed(Coords.getCorner(opp));
      Coords.setCurrent(opc);

      Tracker.activateHandlers(dragmodeHandler(mode, fc), doneSelect, touch);
    }
    //}}}
    function dragmodeHandler(mode, f) //{{{
    {
      return function (pos) {
        if (!options.aspectRatio) {
          switch (mode) {
          case 'e':
            pos[1] = f.y2;
            break;
          case 'w':
            pos[1] = f.y2;
            break;
          case 'n':
            pos[0] = f.x2;
            break;
          case 's':
            pos[0] = f.x2;
            break;
          }
        } else {
          switch (mode) {
          case 'e':
            pos[1] = f.y + 1;
            break;
          case 'w':
            pos[1] = f.y + 1;
            break;
          case 'n':
            pos[0] = f.x + 1;
            break;
          case 's':
            pos[0] = f.x + 1;
            break;
          }
        }
        Coords.setCurrent(pos);
        Selection.update();
      };
    }
    //}}}
    function createMover(pos) //{{{
    {
      var lloc = pos;
      KeyManager.watchKeys();

      return function (pos) {
        Coords.moveOffset([pos[0] - lloc[0], pos[1] - lloc[1]]);
        lloc = pos;

        Selection.update();
      };
    }
    //}}}
    function oppLockCorner(ord) //{{{
    {
      switch (ord) {
      case 'n':
        return 'sw';
      case 's':
        return 'nw';
      case 'e':
        return 'nw';
      case 'w':
        return 'ne';
      case 'ne':
        return 'sw';
      case 'nw':
        return 'se';
      case 'se':
        return 'nw';
      case 'sw':
        return 'ne';
      }
    }
    //}}}
    function createDragger(ord) //{{{
    {
      return function (e) {
        if (options.disabled) {
          return false;
        }
        if ((ord === 'move') && !options.allowMove) {
          return false;
        }
        
        // Fix position of crop area when dragged the very first time.
        // Necessary when crop image is in a hidden element when page is loaded.
        docOffset = getPos($img);

        btndown = true;
        startDragMode(ord, mouseAbs(e));
        e.stopPropagation();
        e.preventDefault();
        return false;
      };
    }
    //}}}
    function presize($obj, w, h) //{{{
    {
      var nw = $obj.width(),
          nh = $obj.height();
      if ((nw > w) && w > 0) {
        nw = w;
        nh = (w / $obj.width()) * $obj.height();
      }
      if ((nh > h) && h > 0) {
        nh = h;
        nw = (h / $obj.height()) * $obj.width();
      }
      xscale = $obj.width() / nw;
      yscale = $obj.height() / nh;
      $obj.width(nw).height(nh);
    }
    //}}}
    function unscale(c) //{{{
    {
      return {
        x: c.x * xscale,
        y: c.y * yscale,
        x2: c.x2 * xscale,
        y2: c.y2 * yscale,
        w: c.w * xscale,
        h: c.h * yscale
      };
    }
    //}}}
    function doneSelect(pos) //{{{
    {
      var c = Coords.getFixed();
      if ((c.w > options.minSelect[0]) && (c.h > options.minSelect[1])) {
        Selection.enableHandles();
        Selection.done();
      } else {
        Selection.release();
      }
      Tracker.setCursor(options.allowSelect ? 'crosshair' : 'default');
    }
    //}}}
    function newSelection(e) //{{{
    {
      if (options.disabled) {
        return false;
      }
      if (!options.allowSelect) {
        return false;
      }
      btndown = true;
      docOffset = getPos($img);
      Selection.disableHandles();
      Tracker.setCursor('crosshair');
      var pos = mouseAbs(e);
      Coords.setPressed(pos);
      Selection.update();
      Tracker.activateHandlers(selectDrag, doneSelect, e.type.substring(0,5)==='touch');
      KeyManager.watchKeys();

      e.stopPropagation();
      e.preventDefault();
      return false;
    }
    //}}}
    function selectDrag(pos) //{{{
    {
      Coords.setCurrent(pos);
      Selection.update();
    }
    //}}}
    function newTracker() //{{{
    {
      var trk = $('<div></div>').addClass(cssClass('tracker'));
      if (is_msie) {
        trk.css({
          opacity: 0,
          backgroundColor: 'white'
        });
      }
      return trk;
    }
    //}}}

    // }}}
    // Initialization {{{
    // Sanitize some options {{{
    if (typeof(obj) !== 'object') {
      obj = $(obj)[0];
    }
    if (typeof(opt) !== 'object') {
      opt = {};
    }
    // }}}
    setOptions(opt);
    // Initialize some jQuery objects {{{
    // The values are SET on the image(s) for the interface
    // If the original image has any of these set, they will be reset
    // However, if you destroy() the Jcrop instance the original image's
    // character in the DOM will be as you left it.
    var img_css = {
      border: 'none',
      visibility: 'visible',
      margin: 0,
      padding: 0,
      position: 'absolute',
      top: 0,
      left: 0
    };

    var $origimg = $(obj),
      img_mode = true;

    if (obj.tagName == 'IMG') {
      // Fix size of crop image.
      // Necessary when crop image is within a hidden element when page is loaded.
      if ($origimg[0].width != 0 && $origimg[0].height != 0) {
        // Obtain dimensions from contained img element.
        $origimg.width($origimg[0].width);
        $origimg.height($origimg[0].height);
      } else {
        // Obtain dimensions from temporary image in case the original is not loaded yet (e.g. IE 7.0). 
        var tempImage = new Image();
        tempImage.src = $origimg[0].src;
        $origimg.width(tempImage.width);
        $origimg.height(tempImage.height);
      } 

      var $img = $origimg.clone().removeAttr('id').css(img_css).show();

      $img.width($origimg.width());
      $img.height($origimg.height());
      $origimg.after($img).hide();

    } else {
      $img = $origimg.css(img_css).show();
      img_mode = false;
      if (options.shade === null) { options.shade = true; }
    }

    presize($img, options.boxWidth, options.boxHeight);

    var boundx = $img.width(),
        boundy = $img.height(),
        
        
        $div = $('<div />').width(boundx).height(boundy).addClass(cssClass('holder')).css({
        position: 'relative',
        backgroundColor: options.bgColor
      }).insertAfter($origimg).append($img);

    if (options.addClass) {
      $div.addClass(options.addClass);
    }

    var $img2 = $('<div />'),

        $img_holder = $('<div />') 
        .width('100%').height('100%').css({
          zIndex: 310,
          position: 'absolute',
          overflow: 'hidden'
        }),

        $hdl_holder = $('<div />') 
        .width('100%').height('100%').css('zIndex', 320), 

        $sel = $('<div />') 
        .css({
          position: 'absolute',
          zIndex: 600
        }).dblclick(function(){
          var c = Coords.getFixed();
          options.onDblClick.call(api,c);
        }).insertBefore($img).append($img_holder, $hdl_holder); 

    if (img_mode) {

      $img2 = $('<img />')
          .attr('src', $img.attr('src')).css(img_css).width(boundx).height(boundy),

      $img_holder.append($img2);

    }

    if (ie6mode) {
      $sel.css({
        overflowY: 'hidden'
      });
    }

    var bound = options.boundary;
    var $trk = newTracker().width(boundx + (bound * 2)).height(boundy + (bound * 2)).css({
      position: 'absolute',
      top: px(-bound),
      left: px(-bound),
      zIndex: 290
    }).mousedown(newSelection);

    /* }}} */
    // Set more variables {{{
    var bgcolor = options.bgColor,
        bgopacity = options.bgOpacity,
        xlimit, ylimit, xmin, ymin, xscale, yscale, enabled = true,
        btndown, animating, shift_down;

    docOffset = getPos($img);
    // }}}
    // }}}
    // Internal Modules {{{
    // Touch Module {{{ 
    var Touch = (function () {
      // Touch support detection function adapted (under MIT License)
      // from code by Jeffrey Sambells - http://github.com/iamamused/
      function hasTouchSupport() {
        var support = {}, events = ['touchstart', 'touchmove', 'touchend'],
            el = document.createElement('div'), i;

        try {
          for(i=0; i<events.length; i++) {
            var eventName = events[i];
            eventName = 'on' + eventName;
            var isSupported = (eventName in el);
            if (!isSupported) {
              el.setAttribute(eventName, 'return;');
              isSupported = typeof el[eventName] == 'function';
            }
            support[events[i]] = isSupported;
          }
          return support.touchstart && support.touchend && support.touchmove;
        }
        catch(err) {
          return false;
        }
      }

      function detectSupport() {
        if ((options.touchSupport === true) || (options.touchSupport === false)) return options.touchSupport;
          else return hasTouchSupport();
      }
      return {
        createDragger: function (ord) {
          return function (e) {
            if (options.disabled) {
              return false;
            }
            if ((ord === 'move') && !options.allowMove) {
              return false;
            }
            docOffset = getPos($img);
            btndown = true;
            startDragMode(ord, mouseAbs(Touch.cfilter(e)), true);
            e.stopPropagation();
            e.preventDefault();
            return false;
          };
        },
        newSelection: function (e) {
          return newSelection(Touch.cfilter(e));
        },
        cfilter: function (e){
          e.pageX = e.originalEvent.changedTouches[0].pageX;
          e.pageY = e.originalEvent.changedTouches[0].pageY;
          return e;
        },
        isSupported: hasTouchSupport,
        support: detectSupport()
      };
    }());
    // }}}
    // Coords Module {{{
    var Coords = (function () {
      var x1 = 0,
          y1 = 0,
          x2 = 0,
          y2 = 0,
          ox, oy;

      function setPressed(pos) //{{{
      {
        pos = rebound(pos);
        x2 = x1 = pos[0];
        y2 = y1 = pos[1];
      }
      //}}}
      function setCurrent(pos) //{{{
      {
        pos = rebound(pos);
        ox = pos[0] - x2;
        oy = pos[1] - y2;
        x2 = pos[0];
        y2 = pos[1];
      }
      //}}}
      function getOffset() //{{{
      {
        return [ox, oy];
      }
      //}}}
      function moveOffset(offset) //{{{
      {
        var ox = offset[0],
            oy = offset[1];

        if (0 > x1 + ox) {
          ox -= ox + x1;
        }
        if (0 > y1 + oy) {
          oy -= oy + y1;
        }

        if (boundy < y2 + oy) {
          oy += boundy - (y2 + oy);
        }
        if (boundx < x2 + ox) {
          ox += boundx - (x2 + ox);
        }

        x1 += ox;
        x2 += ox;
        y1 += oy;
        y2 += oy;
      }
      //}}}
      function getCorner(ord) //{{{
      {
        var c = getFixed();
        switch (ord) {
        case 'ne':
          return [c.x2, c.y];
        case 'nw':
          return [c.x, c.y];
        case 'se':
          return [c.x2, c.y2];
        case 'sw':
          return [c.x, c.y2];
        }
      }
      //}}}
      function getFixed() //{{{
      {
        if (!options.aspectRatio) {
          return getRect();
        }
        // This function could use some optimization I think...
        var aspect = options.aspectRatio,
            min_x = options.minSize[0] / xscale,
            
            
            //min_y = options.minSize[1]/yscale,
            max_x = options.maxSize[0] / xscale,
            max_y = options.maxSize[1] / yscale,
            rw = x2 - x1,
            rh = y2 - y1,
            rwa = Math.abs(rw),
            rha = Math.abs(rh),
            real_ratio = rwa / rha,
            xx, yy, w, h;

        if (max_x === 0) {
          max_x = boundx * 10;
        }
        if (max_y === 0) {
          max_y = boundy * 10;
        }
        if (real_ratio < aspect) {
          yy = y2;
          w = rha * aspect;
          xx = rw < 0 ? x1 - w : w + x1;

          if (xx < 0) {
            xx = 0;
            h = Math.abs((xx - x1) / aspect);
            yy = rh < 0 ? y1 - h : h + y1;
          } else if (xx > boundx) {
            xx = boundx;
            h = Math.abs((xx - x1) / aspect);
            yy = rh < 0 ? y1 - h : h + y1;
          }
        } else {
          xx = x2;
          h = rwa / aspect;
          yy = rh < 0 ? y1 - h : y1 + h;
          if (yy < 0) {
            yy = 0;
            w = Math.abs((yy - y1) * aspect);
            xx = rw < 0 ? x1 - w : w + x1;
          } else if (yy > boundy) {
            yy = boundy;
            w = Math.abs(yy - y1) * aspect;
            xx = rw < 0 ? x1 - w : w + x1;
          }
        }

        // Magic %-)
        if (xx > x1) { // right side
          if (xx - x1 < min_x) {
            xx = x1 + min_x;
          } else if (xx - x1 > max_x) {
            xx = x1 + max_x;
          }
          if (yy > y1) {
            yy = y1 + (xx - x1) / aspect;
          } else {
            yy = y1 - (xx - x1) / aspect;
          }
        } else if (xx < x1) { // left side
          if (x1 - xx < min_x) {
            xx = x1 - min_x;
          } else if (x1 - xx > max_x) {
            xx = x1 - max_x;
          }
          if (yy > y1) {
            yy = y1 + (x1 - xx) / aspect;
          } else {
            yy = y1 - (x1 - xx) / aspect;
          }
        }

        if (xx < 0) {
          x1 -= xx;
          xx = 0;
        } else if (xx > boundx) {
          x1 -= xx - boundx;
          xx = boundx;
        }

        if (yy < 0) {
          y1 -= yy;
          yy = 0;
        } else if (yy > boundy) {
          y1 -= yy - boundy;
          yy = boundy;
        }

        return makeObj(flipCoords(x1, y1, xx, yy));
      }
      //}}}
      function rebound(p) //{{{
      {
        if (p[0] < 0) p[0] = 0;
        if (p[1] < 0) p[1] = 0;

        if (p[0] > boundx) p[0] = boundx;
        if (p[1] > boundy) p[1] = boundy;

        return [Math.round(p[0]), Math.round(p[1])];
      }
      //}}}
      function flipCoords(x1, y1, x2, y2) //{{{
      {
        var xa = x1,
            xb = x2,
            ya = y1,
            yb = y2;
        if (x2 < x1) {
          xa = x2;
          xb = x1;
        }
        if (y2 < y1) {
          ya = y2;
          yb = y1;
        }
        return [xa, ya, xb, yb];
      }
      //}}}
      function getRect() //{{{
      {
        var xsize = x2 - x1,
            ysize = y2 - y1,
            delta;

        if (xlimit && (Math.abs(xsize) > xlimit)) {
          x2 = (xsize > 0) ? (x1 + xlimit) : (x1 - xlimit);
        }
        if (ylimit && (Math.abs(ysize) > ylimit)) {
          y2 = (ysize > 0) ? (y1 + ylimit) : (y1 - ylimit);
        }

        if (ymin / yscale && (Math.abs(ysize) < ymin / yscale)) {
          y2 = (ysize > 0) ? (y1 + ymin / yscale) : (y1 - ymin / yscale);
        }
        if (xmin / xscale && (Math.abs(xsize) < xmin / xscale)) {
          x2 = (xsize > 0) ? (x1 + xmin / xscale) : (x1 - xmin / xscale);
        }

        if (x1 < 0) {
          x2 -= x1;
          x1 -= x1;
        }
        if (y1 < 0) {
          y2 -= y1;
          y1 -= y1;
        }
        if (x2 < 0) {
          x1 -= x2;
          x2 -= x2;
        }
        if (y2 < 0) {
          y1 -= y2;
          y2 -= y2;
        }
        if (x2 > boundx) {
          delta = x2 - boundx;
          x1 -= delta;
          x2 -= delta;
        }
        if (y2 > boundy) {
          delta = y2 - boundy;
          y1 -= delta;
          y2 -= delta;
        }
        if (x1 > boundx) {
          delta = x1 - boundy;
          y2 -= delta;
          y1 -= delta;
        }
        if (y1 > boundy) {
          delta = y1 - boundy;
          y2 -= delta;
          y1 -= delta;
        }

        return makeObj(flipCoords(x1, y1, x2, y2));
      }
      //}}}
      function makeObj(a) //{{{
      {
        return {
          x: a[0],
          y: a[1],
          x2: a[2],
          y2: a[3],
          w: a[2] - a[0],
          h: a[3] - a[1]
        };
      }
      //}}}

      return {
        flipCoords: flipCoords,
        setPressed: setPressed,
        setCurrent: setCurrent,
        getOffset: getOffset,
        moveOffset: moveOffset,
        getCorner: getCorner,
        getFixed: getFixed
      };
    }());

    //}}}
    // Shade Module {{{
    var Shade = (function() {
      var enabled = false,
          holder = $('<div />').css({
            position: 'absolute',
            zIndex: 240,
            opacity: 0
          }),
          shades = {
            top: createShade(),
            left: createShade().height(boundy),
            right: createShade().height(boundy),
            bottom: createShade()
          };

      function resizeShades(w,h) {
        shades.left.css({ height: px(h) });
        shades.right.css({ height: px(h) });
      }
      function updateAuto()
      {
        return updateShade(Coords.getFixed());
      }
      function updateShade(c)
      {
        shades.top.css({
          left: px(c.x),
          width: px(c.w),
          height: px(c.y)
        });
        shades.bottom.css({
          top: px(c.y2),
          left: px(c.x),
          width: px(c.w),
          height: px(boundy-c.y2)
        });
        shades.right.css({
          left: px(c.x2),
          width: px(boundx-c.x2)
        });
        shades.left.css({
          width: px(c.x)
        });
      }
      function createShade() {
        return $('<div />').css({
          position: 'absolute',
          backgroundColor: options.shadeColor||options.bgColor
        }).appendTo(holder);
      }
      function enableShade() {
        if (!enabled) {
          enabled = true;
          holder.insertBefore($img);
          updateAuto();
          Selection.setBgOpacity(1,0,1);
          $img2.hide();

          setBgColor(options.shadeColor||options.bgColor,1);
          if (Selection.isAwake())
          {
            setOpacity(options.bgOpacity,1);
          }
            else setOpacity(1,1);
        }
      }
      function setBgColor(color,now) {
        colorChangeMacro(getShades(),color,now);
      }
      function disableShade() {
        if (enabled) {
          holder.remove();
          $img2.show();
          enabled = false;
          if (Selection.isAwake()) {
            Selection.setBgOpacity(options.bgOpacity,1,1);
          } else {
            Selection.setBgOpacity(1,1,1);
            Selection.disableHandles();
          }
          colorChangeMacro($div,0,1);
        }
      }
      function setOpacity(opacity,now) {
        if (enabled) {
          if (options.bgFade && !now) {
            holder.animate({
              opacity: 1-opacity
            },{
              queue: false,
              duration: options.fadeTime
            });
          }
          else holder.css({opacity:1-opacity});
        }
      }
      function refreshAll() {
        options.shade ? enableShade() : disableShade();
        if (Selection.isAwake()) setOpacity(options.bgOpacity);
      }
      function getShades() {
        return holder.children();
      }

      return {
        update: updateAuto,
        updateRaw: updateShade,
        getShades: getShades,
        setBgColor: setBgColor,
        enable: enableShade,
        disable: disableShade,
        resize: resizeShades,
        refresh: refreshAll,
        opacity: setOpacity
      };
    }());
    // }}}
    // Selection Module {{{
    var Selection = (function () {
      var awake,
          hdep = 370,
          borders = {},
          handle = {},
          dragbar = {},
          seehandles = false;

      // Private Methods
      function insertBorder(type) //{{{
      {
        var jq = $('<div />').css({
          position: 'absolute',
          opacity: options.borderOpacity
        }).addClass(cssClass(type));
        $img_holder.append(jq);
        return jq;
      }
      //}}}
      function dragDiv(ord, zi) //{{{
      {
        var jq = $('<div />').mousedown(createDragger(ord)).css({
          cursor: ord + '-resize',
          position: 'absolute',
          zIndex: zi
        }).addClass('ord-'+ord);

        if (Touch.support) {
          jq.bind('touchstart.jcrop', Touch.createDragger(ord));
        }

        $hdl_holder.append(jq);
        return jq;
      }
      //}}}
      function insertHandle(ord) //{{{
      {
        var hs = options.handleSize,

          div = dragDiv(ord, hdep++).css({
            opacity: options.handleOpacity
          }).addClass(cssClass('handle'));

        if (hs) { div.width(hs).height(hs); }

        return div;
      }
      //}}}
      function insertDragbar(ord) //{{{
      {
        return dragDiv(ord, hdep++).addClass('jcrop-dragbar');
      }
      //}}}
      function createDragbars(li) //{{{
      {
        var i;
        for (i = 0; i < li.length; i++) {
          dragbar[li[i]] = insertDragbar(li[i]);
        }
      }
      //}}}
      function createBorders(li) //{{{
      {
        var cl,i;
        for (i = 0; i < li.length; i++) {
          switch(li[i]){
            case'n': cl='hline'; break;
            case's': cl='hline bottom'; break;
            case'e': cl='vline right'; break;
            case'w': cl='vline'; break;
          }
          borders[li[i]] = insertBorder(cl);
        }
      }
      //}}}
      function createHandles(li) //{{{
      {
        var i;
        for (i = 0; i < li.length; i++) {
          handle[li[i]] = insertHandle(li[i]);
        }
      }
      //}}}
      function moveto(x, y) //{{{
      {
        if (!options.shade) {
          $img2.css({
            top: px(-y),
            left: px(-x)
          });
        }
        $sel.css({
          top: px(y),
          left: px(x)
        });
      }
      //}}}
      function resize(w, h) //{{{
      {
        $sel.width(Math.round(w)).height(Math.round(h));
      }
      //}}}
      function refresh() //{{{
      {
        var c = Coords.getFixed();

        Coords.setPressed([c.x, c.y]);
        Coords.setCurrent([c.x2, c.y2]);

        updateVisible();
      }
      //}}}

      // Internal Methods
      function updateVisible(select) //{{{
      {
        if (awake) {
          return update(select);
        }
      }
      //}}}
      function update(select) //{{{
      {
        var c = Coords.getFixed();

        resize(c.w, c.h);
        moveto(c.x, c.y);
        if (options.shade) Shade.updateRaw(c);

        awake || show();

        if (select) {
          options.onSelect.call(api, unscale(c));
        } else {
          options.onChange.call(api, unscale(c));
        }
      }
      //}}}
      function setBgOpacity(opacity,force,now) //{{{
      {
        if (!awake && !force) return;
        if (options.bgFade && !now) {
          $img.animate({
            opacity: opacity
          },{
            queue: false,
            duration: options.fadeTime
          });
        } else {
          $img.css('opacity', opacity);
        }
      }
      //}}}
      function show() //{{{
      {
        $sel.show();

        if (options.shade) Shade.opacity(bgopacity);
          else setBgOpacity(bgopacity,true);

        awake = true;
      }
      //}}}
      function release() //{{{
      {
        disableHandles();
        $sel.hide();

        if (options.shade) Shade.opacity(1);
          else setBgOpacity(1);

        awake = false;
        options.onRelease.call(api);
      }
      //}}}
      function showHandles() //{{{
      {
        if (seehandles) {
          $hdl_holder.show();
        }
      }
      //}}}
      function enableHandles() //{{{
      {
        seehandles = true;
        if (options.allowResize) {
          $hdl_holder.show();
          return true;
        }
      }
      //}}}
      function disableHandles() //{{{
      {
        seehandles = false;
        $hdl_holder.hide();
      } 
      //}}}
      function animMode(v) //{{{
      {
        if (v) {
          animating = true;
          disableHandles();
        } else {
          animating = false;
          enableHandles();
        }
      } 
      //}}}
      function done() //{{{
      {
        animMode(false);
        refresh();
      } 
      //}}}
      // Insert draggable elements {{{
      // Insert border divs for outline

      if (options.dragEdges && $.isArray(options.createDragbars))
        createDragbars(options.createDragbars);

      if ($.isArray(options.createHandles))
        createHandles(options.createHandles);

      if (options.drawBorders && $.isArray(options.createBorders))
        createBorders(options.createBorders);

      //}}}

      // This is a hack for iOS5 to support drag/move touch functionality
      $(document).bind('touchstart.jcrop-ios',function(e) {
        if ($(e.currentTarget).hasClass('jcrop-tracker')) e.stopPropagation();
      });

      var $track = newTracker().mousedown(createDragger('move')).css({
        cursor: 'move',
        position: 'absolute',
        zIndex: 360
      });

      if (Touch.support) {
        $track.bind('touchstart.jcrop', Touch.createDragger('move'));
      }

      $img_holder.append($track);
      disableHandles();

      return {
        updateVisible: updateVisible,
        update: update,
        release: release,
        refresh: refresh,
        isAwake: function () {
          return awake;
        },
        setCursor: function (cursor) {
          $track.css('cursor', cursor);
        },
        enableHandles: enableHandles,
        enableOnly: function () {
          seehandles = true;
        },
        showHandles: showHandles,
        disableHandles: disableHandles,
        animMode: animMode,
        setBgOpacity: setBgOpacity,
        done: done
      };
    }());
    
    //}}}
    // Tracker Module {{{
    var Tracker = (function () {
      var onMove = function () {},
          onDone = function () {},
          trackDoc = options.trackDocument;

      function toFront(touch) //{{{
      {
        $trk.css({
          zIndex: 450
        });

        if (touch)
          $(document)
            .bind('touchmove.jcrop', trackTouchMove)
            .bind('touchend.jcrop', trackTouchEnd);

        else if (trackDoc)
          $(document)
            .bind('mousemove.jcrop',trackMove)
            .bind('mouseup.jcrop',trackUp);
      } 
      //}}}
      function toBack() //{{{
      {
        $trk.css({
          zIndex: 290
        });
        $(document).unbind('.jcrop');
      } 
      //}}}
      function trackMove(e) //{{{
      {
        onMove(mouseAbs(e));
        return false;
      } 
      //}}}
      function trackUp(e) //{{{
      {
        e.preventDefault();
        e.stopPropagation();

        if (btndown) {
          btndown = false;

          onDone(mouseAbs(e));

          if (Selection.isAwake()) {
            options.onSelect.call(api, unscale(Coords.getFixed()));
          }

          toBack();
          onMove = function () {};
          onDone = function () {};
        }

        return false;
      }
      //}}}
      function activateHandlers(move, done, touch) //{{{
      {
        btndown = true;
        onMove = move;
        onDone = done;
        toFront(touch);
        return false;
      }
      //}}}
      function trackTouchMove(e) //{{{
      {
        onMove(mouseAbs(Touch.cfilter(e)));
        return false;
      }
      //}}}
      function trackTouchEnd(e) //{{{
      {
        return trackUp(Touch.cfilter(e));
      }
      //}}}
      function setCursor(t) //{{{
      {
        $trk.css('cursor', t);
      }
      //}}}

      if (!trackDoc) {
        $trk.mousemove(trackMove).mouseup(trackUp).mouseout(trackUp);
      }

      $img.before($trk);
      return {
        activateHandlers: activateHandlers,
        setCursor: setCursor
      };
    }());
    //}}}
    // KeyManager Module {{{
    var KeyManager = (function () {
      var $keymgr = $('<input type="radio" />').css({
        position: 'fixed',
        left: '-120px',
        width: '12px'
      }).addClass('jcrop-keymgr'),

        $keywrap = $('<div />').css({
          position: 'absolute',
          overflow: 'hidden'
        }).append($keymgr);

      function watchKeys() //{{{
      {
        if (options.keySupport) {
          $keymgr.show();
          $keymgr.focus();
        }
      }
      //}}}
      function onBlur(e) //{{{
      {
        $keymgr.hide();
      }
      //}}}
      function doNudge(e, x, y) //{{{
      {
        if (options.allowMove) {
          Coords.moveOffset([x, y]);
          Selection.updateVisible(true);
        }
        e.preventDefault();
        e.stopPropagation();
      }
      //}}}
      function parseKey(e) //{{{
      {
        if (e.ctrlKey || e.metaKey) {
          return true;
        }
        shift_down = e.shiftKey ? true : false;
        var nudge = shift_down ? 10 : 1;

        switch (e.keyCode) {
        case 37:
          doNudge(e, -nudge, 0);
          break;
        case 39:
          doNudge(e, nudge, 0);
          break;
        case 38:
          doNudge(e, 0, -nudge);
          break;
        case 40:
          doNudge(e, 0, nudge);
          break;
        case 27:
          if (options.allowSelect) Selection.release();
          break;
        case 9:
          return true;
        }

        return false;
      }
      //}}}

      if (options.keySupport) {
        $keymgr.keydown(parseKey).blur(onBlur);
        if (ie6mode || !options.fixedSupport) {
          $keymgr.css({
            position: 'absolute',
            left: '-20px'
          });
          $keywrap.append($keymgr).insertBefore($img);
        } else {
          $keymgr.insertBefore($img);
        }
      }


      return {
        watchKeys: watchKeys
      };
    }());
    //}}}
    // }}}
    // API methods {{{
    function setClass(cname) //{{{
    {
      $div.removeClass().addClass(cssClass('holder')).addClass(cname);
    }
    //}}}
    function animateTo(a, callback) //{{{
    {
      var x1 = a[0] / xscale,
          y1 = a[1] / yscale,
          x2 = a[2] / xscale,
          y2 = a[3] / yscale;

      if (animating) {
        return;
      }

      var animto = Coords.flipCoords(x1, y1, x2, y2),
          c = Coords.getFixed(),
          initcr = [c.x, c.y, c.x2, c.y2],
          animat = initcr,
          interv = options.animationDelay,
          ix1 = animto[0] - initcr[0],
          iy1 = animto[1] - initcr[1],
          ix2 = animto[2] - initcr[2],
          iy2 = animto[3] - initcr[3],
          pcent = 0,
          velocity = options.swingSpeed;

      x1 = animat[0];
      y1 = animat[1];
      x2 = animat[2];
      y2 = animat[3];

      Selection.animMode(true);
      var anim_timer;

      function queueAnimator() {
        window.setTimeout(animator, interv);
      }
      var animator = (function () {
        return function () {
          pcent += (100 - pcent) / velocity;

          animat[0] = Math.round(x1 + ((pcent / 100) * ix1));
          animat[1] = Math.round(y1 + ((pcent / 100) * iy1));
          animat[2] = Math.round(x2 + ((pcent / 100) * ix2));
          animat[3] = Math.round(y2 + ((pcent / 100) * iy2));

          if (pcent >= 99.8) {
            pcent = 100;
          }
          if (pcent < 100) {
            setSelectRaw(animat);
            queueAnimator();
          } else {
            Selection.done();
            Selection.animMode(false);
            if (typeof(callback) === 'function') {
              callback.call(api);
            }
          }
        };
      }());
      queueAnimator();
    }
    //}}}
    function setSelect(rect) //{{{
    {
      setSelectRaw([rect[0] / xscale, rect[1] / yscale, rect[2] / xscale, rect[3] / yscale]);
      options.onSelect.call(api, unscale(Coords.getFixed()));
      Selection.enableHandles();
    }
    //}}}
    function setSelectRaw(l) //{{{
    {
      Coords.setPressed([l[0], l[1]]);
      Coords.setCurrent([l[2], l[3]]);
      Selection.update();
    }
    //}}}
    function tellSelect() //{{{
    {
      return unscale(Coords.getFixed());
    }
    //}}}
    function tellScaled() //{{{
    {
      return Coords.getFixed();
    }
    //}}}
    function setOptionsNew(opt) //{{{
    {
      setOptions(opt);
      interfaceUpdate();
    }
    //}}}
    function disableCrop() //{{{
    {
      options.disabled = true;
      Selection.disableHandles();
      Selection.setCursor('default');
      Tracker.setCursor('default');
    }
    //}}}
    function enableCrop() //{{{
    {
      options.disabled = false;
      interfaceUpdate();
    }
    //}}}
    function cancelCrop() //{{{
    {
      Selection.done();
      Tracker.activateHandlers(null, null);
    }
    //}}}
    function destroy() //{{{
    {
      $div.remove();
      $origimg.show();
      $origimg.css('visibility','visible');
      $(obj).removeData('Jcrop');
    }
    //}}}
    function setImage(src, callback) //{{{
    {
      Selection.release();
      disableCrop();
      var img = new Image();
      img.onload = function () {
        var iw = img.width;
        var ih = img.height;
        var bw = options.boxWidth;
        var bh = options.boxHeight;
        $img.width(iw).height(ih);
        $img.attr('src', src);
        $img2.attr('src', src);
        presize($img, bw, bh);
        boundx = $img.width();
        boundy = $img.height();
        $img2.width(boundx).height(boundy);
        $trk.width(boundx + (bound * 2)).height(boundy + (bound * 2));
        $div.width(boundx).height(boundy);
        Shade.resize(boundx,boundy);
        enableCrop();

        if (typeof(callback) === 'function') {
          callback.call(api);
        }
      };
      img.src = src;
    }
    //}}}
    function colorChangeMacro($obj,color,now) {
      var mycolor = color || options.bgColor;
      if (options.bgFade && supportsColorFade() && options.fadeTime && !now) {
        $obj.animate({
          backgroundColor: mycolor
        }, {
          queue: false,
          duration: options.fadeTime
        });
      } else {
        $obj.css('backgroundColor', mycolor);
      }
    }
    function interfaceUpdate(alt) //{{{
    // This method tweaks the interface based on options object.
    // Called when options are changed and at end of initialization.
    {
      if (options.allowResize) {
        if (alt) {
          Selection.enableOnly();
        } else {
          Selection.enableHandles();
        }
      } else {
        Selection.disableHandles();
      }

      Tracker.setCursor(options.allowSelect ? 'crosshair' : 'default');
      Selection.setCursor(options.allowMove ? 'move' : 'default');

      if (options.hasOwnProperty('trueSize')) {
        xscale = options.trueSize[0] / boundx;
        yscale = options.trueSize[1] / boundy;
      }

      if (options.hasOwnProperty('setSelect')) {
        setSelect(options.setSelect);
        Selection.done();
        delete(options.setSelect);
      }

      Shade.refresh();

      if (options.bgColor != bgcolor) {
        colorChangeMacro(
          options.shade? Shade.getShades(): $div,
          options.shade?
            (options.shadeColor || options.bgColor):
            options.bgColor
        );
        bgcolor = options.bgColor;
      }

      if (bgopacity != options.bgOpacity) {
        bgopacity = options.bgOpacity;
        if (options.shade) Shade.refresh();
          else Selection.setBgOpacity(bgopacity);
      }

      xlimit = options.maxSize[0] || 0;
      ylimit = options.maxSize[1] || 0;
      xmin = options.minSize[0] || 0;
      ymin = options.minSize[1] || 0;

      if (options.hasOwnProperty('outerImage')) {
        $img.attr('src', options.outerImage);
        delete(options.outerImage);
      }

      Selection.refresh();
    }
    //}}}
    //}}}

    if (Touch.support) $trk.bind('touchstart.jcrop', Touch.newSelection);

    $hdl_holder.hide();
    interfaceUpdate(true);

    var api = {
      setImage: setImage,
      animateTo: animateTo,
      setSelect: setSelect,
      setOptions: setOptionsNew,
      tellSelect: tellSelect,
      tellScaled: tellScaled,
      setClass: setClass,

      disable: disableCrop,
      enable: enableCrop,
      cancel: cancelCrop,
      release: Selection.release,
      destroy: destroy,

      focus: KeyManager.watchKeys,

      getBounds: function () {
        return [boundx * xscale, boundy * yscale];
      },
      getWidgetSize: function () {
        return [boundx, boundy];
      },
      getScaleFactor: function () {
        return [xscale, yscale];
      },
      getOptions: function() {
        // careful: internal values are returned
        return options;
      },

      ui: {
        holder: $div,
        selection: $sel
      }
    };

    if (is_msie) $div.bind('selectstart', function () { return false; });

    $origimg.data('Jcrop', api);
    return api;
  };
  $.fn.Jcrop = function (options, callback) //{{{
  {
    var api;
    // Iterate over each object, attach Jcrop
    this.each(function () {
      // If we've already attached to this object
      if ($(this).data('Jcrop')) {
        // The API can be requested this way (undocumented)
        if (options === 'api') return $(this).data('Jcrop');
        // Otherwise, we just reset the options...
        else $(this).data('Jcrop').setOptions(options);
      }
      // If we haven't been attached, preload and attach
      else {
        if (this.tagName == 'IMG')
          $.Jcrop.Loader(this,function(){
            $(this).css({display:'block',visibility:'hidden'});
            api = $.Jcrop(this, options);
            if ($.isFunction(callback)) callback.call(api);
          });
        else {
          $(this).css({display:'block',visibility:'hidden'});
          api = $.Jcrop(this, options);
          if ($.isFunction(callback)) callback.call(api);
        }
      }
    });

    // Return "this" so the object is chainable (jQuery-style)
    return this;
  };
  //}}}
  // $.Jcrop.Loader - basic image loader {{{

  $.Jcrop.Loader = function(imgobj,success,error){
    var $img = $(imgobj), img = $img[0];

    function completeCheck(){
      if (img.complete) {
        $img.unbind('.jcloader');
        if ($.isFunction(success)) success.call(img);
      }
      else window.setTimeout(completeCheck,50);
    }

    $img
      .bind('load.jcloader',completeCheck)
      .bind('error.jcloader',function(e){
        $img.unbind('.jcloader');
        if ($.isFunction(error)) error.call(img);
      });

    if (img.complete && $.isFunction(success)){
      $img.unbind('.jcloader');
      success.call(img);
    }
  };

  //}}}
  // Global Defaults {{{
  $.Jcrop.defaults = {

    // Basic Settings
    allowSelect: true,
    allowMove: true,
    allowResize: true,

    trackDocument: true,

    // Styling Options
    baseClass: 'jcrop',
    addClass: null,
    bgColor: 'black',
    bgOpacity: 0.6,
    bgFade: false,
    borderOpacity: 0.4,
    handleOpacity: 0.5,
    handleSize: null,

    aspectRatio: 0,
    keySupport: true,
    createHandles: ['n','s','e','w','nw','ne','se','sw'],
    createDragbars: ['n','s','e','w'],
    createBorders: ['n','s','e','w'],
    drawBorders: true,
    dragEdges: true,
    fixedSupport: true,
    touchSupport: null,

    shade: null,

    boxWidth: 0,
    boxHeight: 0,
    boundary: 2,
    fadeTime: 400,
    animationDelay: 20,
    swingSpeed: 3,

    minSelect: [0, 0],
    maxSize: [0, 0],
    minSize: [0, 0],

    // Callbacks / Event Handlers
    onChange: function () {},
    onSelect: function () {},
    onDblClick: function () {},
    onRelease: function () {}
  };

  // }}}
}(jQuery));



/*
 * jQuery dropdown: A simple dropdown plugin
 *
 * Inspired by Bootstrap: http://twitter.github.com/bootstrap/javascript.html#dropdowns
 *
 * Copyright 2013 Cory LaViska for A Beautiful Site, LLC. (http://abeautifulsite.net/)
 *
 * Dual licensed under the MIT / GPL Version 2 licenses
 *
*/
if(jQuery) (function($) {
    
    $.extend($.fn, {
        dropdown: function(method, data) {
            
            switch( method ) {
                case 'hide':
                    hide();
                    return $(this);
                case 'attach':
                    return $(this).attr('data-dropdown', data);
                case 'detach':
                    hide();
                    return $(this).removeAttr('data-dropdown');
                case 'disable':
                    return $(this).addClass('dropdown-disabled');
                case 'enable':
                    hide();
                    return $(this).removeClass('dropdown-disabled');
            }
            
        }
    });
    
    function show(event) {
        
        var trigger = $(this),
            dropdown = $(trigger.attr('data-dropdown')),
            isOpen = trigger.hasClass('dropdown-open');
        
        // In some cases we don't want to show it
        if( $(event.target).hasClass('dropdown-ignore') ) return;
        
        event.preventDefault();
        event.stopPropagation();
        hide();
        
        if( isOpen || trigger.hasClass('dropdown-disabled') ) return;
        
        // Show it
        trigger.addClass('dropdown-open');
        dropdown
            .data('dropdown-trigger', trigger)
            .show();
            
        // Position it
        position();
        
        // Trigger the show callback
        dropdown
            .trigger('show', {
                dropdown: dropdown,
                trigger: trigger
            });
        
    }
    
    function hide(event) {
        
        // In some cases we don't hide them
        var targetGroup = event ? $(event.target).parents().addBack() : null;
        
        // Are we clicking anywhere in a dropdown?
        if( targetGroup && targetGroup.is('.dropdown') ) {
            // Is it a dropdown menu?
            if( targetGroup.is('.dropdown-menu') ) {
                // Did we click on an option? If so close it.
                if( !targetGroup.is('A') ) return;
            } else {
                // Nope, it's a panel. Leave it open.
                return;
            }
        }
        
        // Hide any dropdown that may be showing
        $(document).find('.dropdown:visible').each( function() {
            var dropdown = $(this);
            dropdown
                .hide()
                .removeData('dropdown-trigger')
                .trigger('hide', { dropdown: dropdown });
        });
        
        // Remove all dropdown-open classes
        $(document).find('.dropdown-open').removeClass('dropdown-open');
        
    }
    
    function position() {
        
        var dropdown = $('.dropdown:visible').eq(0),
            trigger = dropdown.data('dropdown-trigger'),
            hOffset = trigger ? parseInt(trigger.attr('data-horizontal-offset') || 0, 10) : null,
            vOffset = trigger ? parseInt(trigger.attr('data-vertical-offset') || 0, 10) : null;
        
        if( dropdown.length === 0 || !trigger ) return;
        
        // Position the dropdown relative-to-parent...
        if( dropdown.hasClass('dropdown-relative') ) {
            dropdown.css({
                left: dropdown.hasClass('dropdown-anchor-right') ?
                    trigger.position().left - (dropdown.outerWidth(true) - trigger.outerWidth(true)) - parseInt(trigger.css('margin-right')) + hOffset :
                    trigger.position().left + parseInt(trigger.css('margin-left')) + hOffset,
                top: trigger.position().top + trigger.outerHeight(true) - parseInt(trigger.css('margin-top')) + vOffset
            });
        } else {
            // ...or relative to document
            dropdown.css({
                left: dropdown.hasClass('dropdown-anchor-right') ? 
                    trigger.offset().left - (dropdown.outerWidth() - trigger.outerWidth()) + hOffset : trigger.offset().left + hOffset,
                top: trigger.offset().top + trigger.outerHeight() + vOffset
            });
        }
    }
    
    $(document).on('click.dropdown', '[data-dropdown]', show);
    $(document).on('click.dropdown', hide);
    $(window).on('resize', position);
    
})(jQuery);

 

/*
    jQuery Autosize v1.16.4
    (c) 2013 Jack Moore - jacklmoore.com
    updated: 2013-01-29
    license: http://www.opensource.org/licenses/mit-license.php
*/

(function ($) {
    var
    defaults = {
        className: 'autosizejs',
        append: '',
        callback: false
    },
    hidden = 'hidden',
    borderBox = 'border-box',
    lineHeight = 'lineHeight',

    // border:0 is unnecessary, but avoids a bug in FireFox on OSX (http://www.jacklmoore.com/autosize#comment-851)
    copy = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden;"/>',

    // line-height is conditionally included because IE7/IE8/old Opera do not return the correct value.
    copyStyle = [
        'fontFamily',
        'fontSize',
        'fontWeight',
        'fontStyle',
        'letterSpacing',
        'textTransform',
        'wordSpacing',
        'textIndent'
    ],
    oninput = 'oninput',
    onpropertychange = 'onpropertychange',

    // to keep track which textarea is being mirrored when adjust() is called.
    mirrored,

    // the mirror element, which is used to calculate what size the mirrored element should be.
    mirror = $(copy).data('autosize', true)[0];

    // test that line-height can be accurately copied.
    mirror.style.lineHeight = '99px';
    if ($(mirror).css(lineHeight) === '99px') {
        copyStyle.push(lineHeight);
    }
    mirror.style.lineHeight = '';

    $.fn.autosize = function (options) {
        options = $.extend({}, defaults, options || {});

        if (mirror.parentNode !== document.body) {
            $(document.body).append(mirror);
        }

        return this.each(function () {
            var
            ta = this,
            $ta = $(ta),
            minHeight,
            active,
            resize,
            boxOffset = 0,
            callback = $.isFunction(options.callback);

//          console.log($ta);

            if ($ta.data('autosize')) {
                // exit if autosize has already been applied, or if the textarea is the mirror element.
                return;
            }

            if ($ta.css('box-sizing') === borderBox || $ta.css('-moz-box-sizing') === borderBox || $ta.css('-webkit-box-sizing') === borderBox){
                boxOffset = $ta.outerHeight() - $ta.height();
            }

            minHeight = Math.max(parseInt($ta.css('minHeight'), 10) - boxOffset, $ta.height());

            resize = ($ta.css('resize') === 'none' || $ta.css('resize') === 'vertical') ? 'none' : 'horizontal';

            $ta.css({
                overflow: hidden,
                overflowY: hidden,
                wordWrap: 'break-word',
                resize: resize
            }).data('autosize', true);

//          console.log($ta.is(':visible'));

            function initMirror() {
                mirrored = ta;
                mirror.className = options.className;

                // mirror is a duplicate textarea located off-screen that
                // is automatically updated to contain the same text as the
                // original textarea.  mirror always has a height of 0.
                // This gives a cross-browser supported way getting the actual
                // height of the text, through the scrollTop property.
                $.each(copyStyle, function(i, val){
                    mirror.style[val] = $ta.css(val);
                });
            }

            // Using mainly bare JS in this function because it is going
            // to fire very often while typing, and needs to very efficient.
            function adjust() {
                var height, overflow, original;

                if (mirrored !== ta) {
                    initMirror();
                }

                // the active flag keeps IE from tripping all over itself.  Otherwise
                // actions in the adjust function will cause IE to call adjust again.
                if (!active) {
                    active = true;
//                  console.log(mirror.scrollHeight, ta);
                    mirror.value = ta.value + options.append;
                    mirror.style.overflowY = ta.style.overflowY;
                    original = parseInt(ta.style.height,10);
                    // Update the width in case the original textarea width has changed
                    // A floor of 0 is needed because IE8 returns a negative value for hidden textareas, raising an error.
                    mirror.style.width = Math.max($ta.width(), 0) + 'px';

                    // The following three lines can be replaced with `height = mirror.scrollHeight` when dropping IE7 support.
                    mirror.scrollTop = 0;
                    mirror.scrollTop = 9e4;
                    height = mirror.scrollTop;
//                  height = mirror.scrollHeight;
//                  height = 300;


//                  console.log(ta, height);
                    var maxHeight = parseInt($ta.css('maxHeight'), 10);
                    // Opera returns '-1px' when max-height is set to 'none'.
                    maxHeight = maxHeight && maxHeight > 0 ? maxHeight : 9e4;
                    if (height > maxHeight) {
                        height = maxHeight;
                        overflow = 'scroll';
                    } else if (height < minHeight) {
                        height = minHeight;
                    }
                    height += boxOffset;
                    ta.style.overflowY = overflow || hidden;

                    if (original !== height) {
                        ta.style.height = height + 'px';
                        if (callback) {
                            options.callback.call(ta);
                        }
                    }

                    // This small timeout gives IE a chance to draw it's scrollbar
                    // before adjust can be run again (prevents an infinite loop).
                    setTimeout(function () {
                        active = false;
                    }, 1);
                }
            }

            if (onpropertychange in ta) {
                if (oninput in ta) {
                    // Detects IE9.  IE9 does not fire onpropertychange or oninput for deletions,
                    // so binding to onkeyup to catch most of those occassions.  There is no way that I
                    // know of to detect something like 'cut' in IE9.
                    ta[oninput] = ta.onkeyup = adjust;
                } else {
                    // IE7 / IE8
                    ta[onpropertychange] = adjust;
                }
            } else {
                // Modern Browsers
                ta[oninput] = adjust;
            }

            $(window).resize(adjust);

            // Allow for manual triggering if needed.
            $ta.bind('autosize', adjust);

            // Call adjust in case the textarea already contains text.
            adjust();
        });
    };
}(window.jQuery || window.Zepto));

// Chosen, a Select Box Enhancer for jQuery and Protoype
// by Patrick Filler for Harvest, http://getharvest.com
// 
// Version 0.9.11
// Full source at https://github.com/harvesthq/chosen
// Copyright (c) 2011 Harvest http://getharvest.com

// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `cake build`, do not edit it by hand.
(function() {
  var SelectParser;

  SelectParser = (function() {

    function SelectParser() {
      this.options_index = 0;
      this.parsed = [];
    }

    SelectParser.prototype.add_node = function(child) {
      if (child.nodeName.toUpperCase() === "OPTGROUP") {
        return this.add_group(child);
      } else {
        return this.add_option(child);
      }
    };

    SelectParser.prototype.add_group = function(group) {
      var group_position, option, _i, _len, _ref, _results;
      group_position = this.parsed.length;
      this.parsed.push({
        array_index: group_position,
        group: true,
        label: group.label,
        children: 0,
        disabled: group.disabled
      });
      _ref = group.childNodes;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        option = _ref[_i];
        _results.push(this.add_option(option, group_position, group.disabled));
      }
      return _results;
    };

    SelectParser.prototype.add_option = function(option, group_position, group_disabled) {
      if (option.nodeName.toUpperCase() === "OPTION") {
        if (option.text !== "") {
          if (group_position != null) {
            this.parsed[group_position].children += 1;
          }
          this.parsed.push({
            array_index: this.parsed.length,
            options_index: this.options_index,
            value: option.value,
            text: option.text,
            html: option.innerHTML,
            selected: option.selected,
            disabled: group_disabled === true ? group_disabled : option.disabled,
            group_array_index: group_position,
            classes: option.className,
            style: option.style.cssText
          });
        } else {
          this.parsed.push({
            array_index: this.parsed.length,
            options_index: this.options_index,
            empty: true
          });
        }
        return this.options_index += 1;
      }
    };

    return SelectParser;

  })();

  SelectParser.select_to_array = function(select) {
    var child, parser, _i, _len, _ref;
    parser = new SelectParser();
    _ref = select.childNodes;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      child = _ref[_i];
      parser.add_node(child);
    }
    return parser.parsed;
  };

  this.SelectParser = SelectParser;

}).call(this);

/*
Chosen source: generate output using 'cake build'
Copyright (c) 2011 by Harvest
*/


(function() {
  var AbstractChosen, root;

  root = this;

  AbstractChosen = (function() {

    function AbstractChosen(form_field, options) {
      this.form_field = form_field;
      this.options = options != null ? options : {};
      this.is_multiple = this.form_field.multiple;
      this.set_default_text();
      this.set_default_values();
      this.setup();
      this.set_up_html();
      this.register_observers();
      this.finish_setup();
    }

    AbstractChosen.prototype.set_default_values = function() {
      var _this = this;
      this.click_test_action = function(evt) {
        return _this.test_active_click(evt);
      };
      this.activate_action = function(evt) {
        return _this.activate_field(evt);
      };
      this.active_field = false;
      this.mouse_on_container = false;
      this.results_showing = false;
      this.result_highlighted = null;
      this.result_single_selected = null;
      this.allow_single_deselect = (this.options.allow_single_deselect != null) && (this.form_field.options[0] != null) && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : false;
      this.disable_search_threshold = this.options.disable_search_threshold || 0;
      this.disable_search = this.options.disable_search || false;
      this.enable_split_word_search = this.options.enable_split_word_search != null ? this.options.enable_split_word_search : true;
      this.search_contains = this.options.search_contains || false;
      this.choices = 0;
      this.single_backstroke_delete = this.options.single_backstroke_delete || false;
      this.max_selected_options = this.options.max_selected_options || Infinity;
      return this.inherit_select_classes = this.options.inherit_select_classes || false;
    };

    AbstractChosen.prototype.set_default_text = function() {
      if (this.form_field.getAttribute("data-placeholder")) {
        this.default_text = this.form_field.getAttribute("data-placeholder");
      } else if (this.is_multiple) {
        this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || "Select Some Options";
      } else {
        this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || "Select an Option";
      }
      return this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || "No results match";
    };

    AbstractChosen.prototype.mouse_enter = function() {
      return this.mouse_on_container = true;
    };

    AbstractChosen.prototype.mouse_leave = function() {
      return this.mouse_on_container = false;
    };

    AbstractChosen.prototype.input_focus = function(evt) {
      var _this = this;
      if (this.is_multiple) {
        if (!this.active_field) {
          return setTimeout((function() {
            return _this.container_mousedown();
          }), 50);
        }
      } else {
        if (!this.active_field) {
          return this.activate_field();
        }
      }
    };

    AbstractChosen.prototype.input_blur = function(evt) {
      var _this = this;
      if (!this.mouse_on_container) {
        this.active_field = false;
        return setTimeout((function() {
          return _this.blur_test();
        }), 100);
      }
    };

    AbstractChosen.prototype.result_add_option = function(option) {
      var classes, style;
      if (!option.disabled) {
        option.dom_id = this.container_id + "_o_" + option.array_index;
        classes = option.selected && this.is_multiple ? [] : ["active-result"];
        if (option.selected) {
          classes.push("result-selected");
        }
        if (option.group_array_index != null) {
          classes.push("group-option");
        }
        if (option.classes !== "") {
          classes.push(option.classes);
        }
        style = option.style.cssText !== "" ? " style=\"" + option.style + "\"" : "";
        return '<li id="' + option.dom_id + '" class="' + classes.join(' ') + '"' + style + '>' + option.html + '</li>';
      } else {
        return "";
      }
    };

    AbstractChosen.prototype.results_update_field = function() {
      if (!this.is_multiple) {
        this.results_reset_cleanup();
      }
      this.result_clear_highlight();
      this.result_single_selected = null;
      return this.results_build();
    };

    AbstractChosen.prototype.results_toggle = function() {
      if (this.results_showing) {
        return this.results_hide();
      } else {
        return this.results_show();
      }
    };

    AbstractChosen.prototype.results_search = function(evt) {
      if (this.results_showing) {
        return this.winnow_results();
      } else {
        return this.results_show();
      }
    };

    AbstractChosen.prototype.keyup_checker = function(evt) {
      var stroke, _ref;
      stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
      this.search_field_scale();
      switch (stroke) {
        case 8:
          if (this.is_multiple && this.backstroke_length < 1 && this.choices > 0) {
            return this.keydown_backstroke();
          } else if (!this.pending_backstroke) {
            this.result_clear_highlight();
            return this.results_search();
          }
          break;
        case 13:
          evt.preventDefault();
          if (this.results_showing) {
            return this.result_select(evt);
          }
          break;
        case 27:
          if (this.results_showing) {
            this.results_hide();
          }
          return true;
        case 9:
        case 38:
        case 40:
        case 16:
        case 91:
        case 17:
          break;
        default:
          return this.results_search();
      }
    };

    AbstractChosen.prototype.generate_field_id = function() {
      var new_id;
      new_id = this.generate_random_id();
      this.form_field.id = new_id;
      return new_id;
    };

    AbstractChosen.prototype.generate_random_char = function() {
      var chars, newchar, rand;
      chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      rand = Math.floor(Math.random() * chars.length);
      return newchar = chars.substring(rand, rand + 1);
    };

    return AbstractChosen;

  })();

  root.AbstractChosen = AbstractChosen;

}).call(this);

/*
Chosen source: generate output using 'cake build'
Copyright (c) 2011 by Harvest
*/


(function() {
  var $, Chosen, get_side_border_padding, root,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  root = this;

  $ = jQuery;

  $.fn.extend({
    chosen: function(options) {
      var browser, match, ua;
      ua = navigator.userAgent.toLowerCase();
      match = /(msie) ([\w.]+)/.exec(ua) || [];
      browser = {
        name: match[1] || "",
        version: match[2] || "0"
      };
      if (browser.name === "msie" && (browser.version === "6.0" || (browser.version === "7.0" && document.documentMode === 7))) {
        return this;
      }
      return this.each(function(input_field) {
        var $this;
        $this = $(this);
        if (!$this.hasClass("chzn-done")) {
          return $this.data('chosen', new Chosen(this, options));
        }
      });
    }
  });

  Chosen = (function(_super) {

    __extends(Chosen, _super);

    function Chosen() {
      return Chosen.__super__.constructor.apply(this, arguments);
    }

    Chosen.prototype.setup = function() {
      this.form_field_jq = $(this.form_field);
      this.current_value = this.form_field_jq.val();
      return this.is_rtl = this.form_field_jq.hasClass("chzn-rtl");
    };

    Chosen.prototype.finish_setup = function() {
      return this.form_field_jq.addClass("chzn-done");
    };

    Chosen.prototype.set_up_html = function() {
      var container_classes, container_div, container_props, dd_top, dd_width, sf_width;
      this.container_id = this.form_field.id.length ? this.form_field.id.replace(/[^\w]/g, '_') : this.generate_field_id();
      this.container_id += "_chzn";
      container_classes = ["chzn-container"];
      container_classes.push("chzn-container-" + (this.is_multiple ? "multi" : "single"));
      if (this.inherit_select_classes && this.form_field.className) {
        container_classes.push(this.form_field.className);
      }
      if (this.is_rtl) {
        container_classes.push("chzn-rtl");
      }
      this.f_width = this.form_field_jq.outerWidth();
      container_props = {
        id: this.container_id,
        "class": container_classes.join(' '),
        style: 'width: ' + this.f_width + 'px;',
        title: this.form_field.title
      };
      container_div = $("<div />", container_props);
      if (this.is_multiple) {
        container_div.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>');
      } else {
        container_div.html('<a href="javascript:void(0)" class="chzn-single chzn-default" tabindex="-1"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>');
      }
      this.form_field_jq.hide().after(container_div);
      this.container = $('#' + this.container_id);
      this.dropdown = this.container.find('div.chzn-drop').first();
      dd_top = this.container.height();
      dd_width = this.f_width - get_side_border_padding(this.dropdown);
//      dd_width = 140;
      this.dropdown.css({
        "width": dd_width + "px",
        "top": dd_top + "px"
      });
      this.search_field = this.container.find('input').first();
      this.search_results = this.container.find('ul.chzn-results').first();
      this.search_field_scale();
      this.search_no_results = this.container.find('li.no-results').first();
      if (this.is_multiple) {
        this.search_choices = this.container.find('ul.chzn-choices').first();
        this.search_container = this.container.find('li.search-field').first();
      } else {
        this.search_container = this.container.find('div.chzn-search').first();
        this.selected_item = this.container.find('.chzn-single').first();
        sf_width = dd_width - get_side_border_padding(this.search_container) - get_side_border_padding(this.search_field);
        this.search_field.css({
          "width": sf_width + "px"
        });
      }
      this.results_build();
      this.set_tab_index();
      return this.form_field_jq.trigger("liszt:ready", {
        chosen: this
      });
    };

    Chosen.prototype.register_observers = function() {
      var _this = this;
      this.container.mousedown(function(evt) {
        return _this.container_mousedown(evt);
      });
      this.container.mouseup(function(evt) {
        return _this.container_mouseup(evt);
      });
      this.container.mouseenter(function(evt) {
        return _this.mouse_enter(evt);
      });
      this.container.mouseleave(function(evt) {
        return _this.mouse_leave(evt);
      });
      this.search_results.mouseup(function(evt) {
        return _this.search_results_mouseup(evt);
      });
      this.search_results.mouseover(function(evt) {
        return _this.search_results_mouseover(evt);
      });
      this.search_results.mouseout(function(evt) {
        return _this.search_results_mouseout(evt);
      });
      this.form_field_jq.bind("liszt:updated", function(evt) {
        return _this.results_update_field(evt);
      });
      this.form_field_jq.bind("liszt:activate", function(evt) {
        return _this.activate_field(evt);
      });
      this.form_field_jq.bind("liszt:open", function(evt) {
        return _this.container_mousedown(evt);
      });
      this.search_field.blur(function(evt) {
        return _this.input_blur(evt);
      });
      this.search_field.keyup(function(evt) {
        return _this.keyup_checker(evt);
      });
      this.search_field.keydown(function(evt) {
        return _this.keydown_checker(evt);
      });
      this.search_field.focus(function(evt) {
        return _this.input_focus(evt);
      });
      if (this.is_multiple) {
        return this.search_choices.click(function(evt) {
          return _this.choices_click(evt);
        });
      } else {
        return this.container.click(function(evt) {
          return evt.preventDefault();
        });
      }
    };

    Chosen.prototype.search_field_disabled = function() {
      this.is_disabled = this.form_field_jq[0].disabled;
      if (this.is_disabled) {
        this.container.addClass('chzn-disabled');
        this.search_field[0].disabled = true;
        if (!this.is_multiple) {
          this.selected_item.unbind("focus", this.activate_action);
        }
        return this.close_field();
      } else {
        this.container.removeClass('chzn-disabled');
        this.search_field[0].disabled = false;
        if (!this.is_multiple) {
          return this.selected_item.bind("focus", this.activate_action);
        }
      }
    };

    Chosen.prototype.container_mousedown = function(evt) {
      var target_closelink;
      if (!this.is_disabled) {
        target_closelink = evt != null ? ($(evt.target)).hasClass("search-choice-close") : false;
        if (evt && evt.type === "mousedown" && !this.results_showing) {
          evt.preventDefault();
        }
        if (!this.pending_destroy_click && !target_closelink) {
          if (!this.active_field) {
            if (this.is_multiple) {
              this.search_field.val("");
            }
            $(document).click(this.click_test_action);
            this.results_show();
          } else if (!this.is_multiple && evt && (($(evt.target)[0] === this.selected_item[0]) || $(evt.target).parents("a.chzn-single").length)) {
            evt.preventDefault();
            this.results_toggle();
          }
          return this.activate_field();
        } else {
          return this.pending_destroy_click = false;
        }
      }
    };

    Chosen.prototype.container_mouseup = function(evt) {
      if (evt.target.nodeName === "ABBR" && !this.is_disabled) {
        return this.results_reset(evt);
      }
    };

    Chosen.prototype.blur_test = function(evt) {
      if (!this.active_field && this.container.hasClass("chzn-container-active")) {
        return this.close_field();
      }
    };

    Chosen.prototype.close_field = function() {
      $(document).unbind("click", this.click_test_action);
      this.active_field = false;
      this.results_hide();
      this.container.removeClass("chzn-container-active");
      this.winnow_results_clear();
      this.clear_backstroke();
      this.show_search_field_default();
      return this.search_field_scale();
    };

    Chosen.prototype.activate_field = function() {
      this.container.addClass("chzn-container-active");
      this.active_field = true;
      this.search_field.val(this.search_field.val());
      return this.search_field.focus();
    };

    Chosen.prototype.test_active_click = function(evt) {
      if ($(evt.target).parents('#' + this.container_id).length) {
        return this.active_field = true;
      } else {
        return this.close_field();
      }
    };

    Chosen.prototype.results_build = function() {
      var content, data, _i, _len, _ref;
      this.parsing = true;
      this.results_data = root.SelectParser.select_to_array(this.form_field);
      if (this.is_multiple && this.choices > 0) {
        this.search_choices.find("li.search-choice").remove();
        this.choices = 0;
      } else if (!this.is_multiple) {
        this.selected_item.addClass("chzn-default").find("span").text(this.default_text);
        if (this.disable_search || this.form_field.options.length <= this.disable_search_threshold) {
          this.container.addClass("chzn-container-single-nosearch");
        } else {
          this.container.removeClass("chzn-container-single-nosearch");
        }
      }
      content = '';
      _ref = this.results_data;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        data = _ref[_i];
        if (data.group) {
          content += this.result_add_group(data);
        } else if (!data.empty) {
          content += this.result_add_option(data);
          if (data.selected && this.is_multiple) {
            this.choice_build(data);
          } else if (data.selected && !this.is_multiple) {
            this.selected_item.removeClass("chzn-default").find("span").text(data.text);
            if (this.allow_single_deselect) {
              this.single_deselect_control_build();
            }
          }
        }
      }
      this.search_field_disabled();
      this.show_search_field_default();
      this.search_field_scale();
      this.search_results.html(content);
      return this.parsing = false;
    };

    Chosen.prototype.result_add_group = function(group) {
      if (!group.disabled) {
        group.dom_id = this.container_id + "_g_" + group.array_index;
        return '<li id="' + group.dom_id + '" class="group-result">' + $("<div />").text(group.label).html() + '</li>';
      } else {
        return "";
      }
    };

    Chosen.prototype.result_do_highlight = function(el) {
      var high_bottom, high_top, maxHeight, visible_bottom, visible_top;
      if (el.length) {
        this.result_clear_highlight();
        this.result_highlight = el;
        this.result_highlight.addClass("highlighted");
        maxHeight = parseInt(this.search_results.css("maxHeight"), 10);
        visible_top = this.search_results.scrollTop();
        visible_bottom = maxHeight + visible_top;
        high_top = this.result_highlight.position().top + this.search_results.scrollTop();
        high_bottom = high_top + this.result_highlight.outerHeight();
        if (high_bottom >= visible_bottom) {
          return this.search_results.scrollTop((high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0);
        } else if (high_top < visible_top) {
          return this.search_results.scrollTop(high_top);
        }
      }
    };

    Chosen.prototype.result_clear_highlight = function() {
      if (this.result_highlight) {
        this.result_highlight.removeClass("highlighted");
      }
      return this.result_highlight = null;
    };

    Chosen.prototype.results_show = function() {
      var dd_top;
      if (!this.is_multiple) {
        this.selected_item.addClass("chzn-single-with-drop");
        if (this.result_single_selected) {
          this.result_do_highlight(this.result_single_selected);
        }
      } else if (this.max_selected_options <= this.choices) {
        this.form_field_jq.trigger("liszt:maxselected", {
          chosen: this
        });
        return false;
      }
      dd_top = this.is_multiple ? this.container.height() : this.container.height() - 1;
      this.form_field_jq.trigger("liszt:showing_dropdown", {
        chosen: this
      });
      this.dropdown.css({
        "top": dd_top + "px",
        "left": 0
      });
      this.results_showing = true;
      this.search_field.focus();
      this.search_field.val(this.search_field.val());
      return this.winnow_results();
    };

    Chosen.prototype.results_hide = function() {
      if (!this.is_multiple) {
        this.selected_item.removeClass("chzn-single-with-drop");
      }
      this.result_clear_highlight();
      this.form_field_jq.trigger("liszt:hiding_dropdown", {
        chosen: this
      });
      this.dropdown.css({
        "left": "-9000px"
      });
      return this.results_showing = false;
    };

    Chosen.prototype.set_tab_index = function(el) {
      var ti;
      if (this.form_field_jq.attr("tabindex")) {
        ti = this.form_field_jq.attr("tabindex");
        this.form_field_jq.attr("tabindex", -1);
        return this.search_field.attr("tabindex", ti);
      }
    };

    Chosen.prototype.show_search_field_default = function() {
      if (this.is_multiple && this.choices < 1 && !this.active_field) {
        this.search_field.val(this.default_text);
        return this.search_field.addClass("default");
      } else {
        this.search_field.val("");
        return this.search_field.removeClass("default");
      }
    };

    Chosen.prototype.search_results_mouseup = function(evt) {
      var target;
      target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
      if (target.length) {
        this.result_highlight = target;
        this.result_select(evt);
        return this.search_field.focus();
      }
    };

    Chosen.prototype.search_results_mouseover = function(evt) {
      var target;
      target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
      if (target) {
        return this.result_do_highlight(target);
      }
    };

    Chosen.prototype.search_results_mouseout = function(evt) {
      if ($(evt.target).hasClass("active-result" || $(evt.target).parents('.active-result').first())) {
        return this.result_clear_highlight();
      }
    };

    Chosen.prototype.choices_click = function(evt) {
      evt.preventDefault();
      if (this.active_field && !($(evt.target).hasClass("search-choice" || $(evt.target).parents('.search-choice').first)) && !this.results_showing) {
        return this.results_show();
      }
    };

    Chosen.prototype.choice_build = function(item) {
      var choice_id, html, link,
        _this = this;
      if (this.is_multiple && this.max_selected_options <= this.choices) {
        this.form_field_jq.trigger("liszt:maxselected", {
          chosen: this
        });
        return false;
      }
      choice_id = this.container_id + "_c_" + item.array_index;
      this.choices += 1;
      if (item.disabled) {
        html = '<li class="search-choice search-choice-disabled" id="' + choice_id + '"><span>' + item.html + '</span></li>';
      } else {
        html = '<li class="search-choice" id="' + choice_id + '"><span>' + item.html + '</span><a href="javascript:void(0)" class="search-choice-close" rel="' + item.array_index + '"></a></li>';
      }
      this.search_container.before(html);
      link = $('#' + choice_id).find("a").first();
      return link.click(function(evt) {
        return _this.choice_destroy_link_click(evt);
      });
    };

    Chosen.prototype.choice_destroy_link_click = function(evt) {
      evt.preventDefault();
      if (!this.is_disabled) {
        this.pending_destroy_click = true;
        return this.choice_destroy($(evt.target));
      } else {
        return evt.stopPropagation;
      }
    };

    Chosen.prototype.choice_destroy = function(link) {
      if (this.result_deselect(link.attr("rel"))) {
        this.choices -= 1;
        this.show_search_field_default();
        if (this.is_multiple && this.choices > 0 && this.search_field.val().length < 1) {
          this.results_hide();
        }
        link.parents('li').first().remove();
        return this.search_field_scale();
      }
    };

    Chosen.prototype.results_reset = function() {
      this.form_field.options[0].selected = true;
      this.selected_item.find("span").text(this.default_text);
      if (!this.is_multiple) {
        this.selected_item.addClass("chzn-default");
      }
      this.show_search_field_default();
      this.results_reset_cleanup();
      this.form_field_jq.trigger("change");
      if (this.active_field) {
        return this.results_hide();
      }
    };

    Chosen.prototype.results_reset_cleanup = function() {
      this.current_value = this.form_field_jq.val();
      return this.selected_item.find("abbr").remove();
    };

    Chosen.prototype.result_select = function(evt) {
      var high, high_id, item, position;
      if (this.result_highlight) {
        high = this.result_highlight;
        high_id = high.attr("id");
        this.result_clear_highlight();
        if (this.is_multiple) {
          this.result_deactivate(high);
        } else {
          this.search_results.find(".result-selected").removeClass("result-selected");
          this.result_single_selected = high;
          this.selected_item.removeClass("chzn-default");
        }
        high.addClass("result-selected");
        position = high_id.substr(high_id.lastIndexOf("_") + 1);
        item = this.results_data[position];
        item.selected = true;
        this.form_field.options[item.options_index].selected = true;
        if (this.is_multiple) {
          this.choice_build(item);
        } else {
          this.selected_item.find("span").first().text(item.text);
          if (this.allow_single_deselect) {
            this.single_deselect_control_build();
          }
        }
        if (!((evt.metaKey || evt.ctrlKey) && this.is_multiple)) {
          this.results_hide();
        }
        this.search_field.val("");
        if (this.is_multiple || this.form_field_jq.val() !== this.current_value) {
          this.form_field_jq.trigger("change", {
            'selected': this.form_field.options[item.options_index].value
          });
        }
        this.current_value = this.form_field_jq.val();
        return this.search_field_scale();
      }
    };

    Chosen.prototype.result_activate = function(el) {
      return el.addClass("active-result");
    };

    Chosen.prototype.result_deactivate = function(el) {
      return el.removeClass("active-result");
    };

    Chosen.prototype.result_deselect = function(pos) {
      var result, result_data;
      result_data = this.results_data[pos];
      if (!this.form_field.options[result_data.options_index].disabled) {
        result_data.selected = false;
        this.form_field.options[result_data.options_index].selected = false;
        result = $("#" + this.container_id + "_o_" + pos);
        result.removeClass("result-selected").addClass("active-result").show();
        this.result_clear_highlight();
        this.winnow_results();
        this.form_field_jq.trigger("change", {
          deselected: this.form_field.options[result_data.options_index].value
        });
        this.search_field_scale();
        return true;
      } else {
        return false;
      }
    };

    Chosen.prototype.single_deselect_control_build = function() {
      if (this.allow_single_deselect && this.selected_item.find("abbr").length < 1) {
        return this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
      }
    };

    Chosen.prototype.winnow_results = function() {
      var found, option, part, parts, regex, regexAnchor, result, result_id, results, searchText, startpos, text, zregex, _i, _j, _len, _len1, _ref;
      this.no_results_clear();
      results = 0;
      searchText = this.search_field.val() === this.default_text ? "" : $('<div/>').text($.trim(this.search_field.val())).html();
      regexAnchor = this.search_contains ? "" : "^";
      regex = new RegExp(regexAnchor + searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
      zregex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
      _ref = this.results_data;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        option = _ref[_i];
        if (!option.disabled && !option.empty) {
          if (option.group) {
            $('#' + option.dom_id).css('display', 'none');
          } else if (!(this.is_multiple && option.selected)) {
            found = false;
            result_id = option.dom_id;
            result = $("#" + result_id);
            if (regex.test(option.html)) {
              found = true;
              results += 1;
            } else if (this.enable_split_word_search && (option.html.indexOf(" ") >= 0 || option.html.indexOf("[") === 0)) {
              parts = option.html.replace(/\[|\]/g, "").split(" ");
              if (parts.length) {
                for (_j = 0, _len1 = parts.length; _j < _len1; _j++) {
                  part = parts[_j];
                  if (regex.test(part)) {
                    found = true;
                    results += 1;
                  }
                }
              }
            }
            if (found) {
              if (searchText.length) {
                startpos = option.html.search(zregex);
                text = option.html.substr(0, startpos + searchText.length) + '</em>' + option.html.substr(startpos + searchText.length);
                text = text.substr(0, startpos) + '<em>' + text.substr(startpos);
              } else {
                text = option.html;
              }
              result.html(text);
              this.result_activate(result);
              if (option.group_array_index != null) {
                $("#" + this.results_data[option.group_array_index].dom_id).css('display', 'list-item');
              }
            } else {
              if (this.result_highlight && result_id === this.result_highlight.attr('id')) {
                this.result_clear_highlight();
              }
              this.result_deactivate(result);
            }
          }
        }
      }
      if (results < 1 && searchText.length) {
        return this.no_results(searchText);
      } else {
        return this.winnow_results_set_highlight();
      }
    };

    Chosen.prototype.winnow_results_clear = function() {
      var li, lis, _i, _len, _results;
      this.search_field.val("");
      lis = this.search_results.find("li");
      _results = [];
      for (_i = 0, _len = lis.length; _i < _len; _i++) {
        li = lis[_i];
        li = $(li);
        if (li.hasClass("group-result")) {
          _results.push(li.css('display', 'auto'));
        } else if (!this.is_multiple || !li.hasClass("result-selected")) {
          _results.push(this.result_activate(li));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    Chosen.prototype.winnow_results_set_highlight = function() {
      var do_high, selected_results;
      if (!this.result_highlight) {
        selected_results = !this.is_multiple ? this.search_results.find(".result-selected.active-result") : [];
        do_high = selected_results.length ? selected_results.first() : this.search_results.find(".active-result").first();
        if (do_high != null) {
          return this.result_do_highlight(do_high);
        }
      }
    };

    Chosen.prototype.no_results = function(terms) {
      var no_results_html;
      no_results_html = $('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>');
      no_results_html.find("span").first().html(terms);
      return this.search_results.append(no_results_html);
    };

    Chosen.prototype.no_results_clear = function() {
      return this.search_results.find(".no-results").remove();
    };

    Chosen.prototype.keydown_arrow = function() {
      var first_active, next_sib;
      if (!this.result_highlight) {
        first_active = this.search_results.find("li.active-result").first();
        if (first_active) {
          this.result_do_highlight($(first_active));
        }
      } else if (this.results_showing) {
        next_sib = this.result_highlight.nextAll("li.active-result").first();
        if (next_sib) {
          this.result_do_highlight(next_sib);
        }
      }
      if (!this.results_showing) {
        return this.results_show();
      }
    };

    Chosen.prototype.keyup_arrow = function() 
    {
      var prev_sibs;
//      log(this.results_showing, this.is_multiple, this.result_highlight, this.choices)
      if (!this.results_showing && !this.is_multiple) 
      {
        return this.results_show();
      } 
      else if (this.result_highlight) 
      {
        prev_sibs = this.result_highlight.prevAll("li.active-result");
        if (prev_sibs.length) 
        {
          return this.result_do_highlight(prev_sibs.first());
        } 
        else 
        {
          if (this.choices > 0) 
          {
            this.results_hide();
          }
          return this.result_clear_highlight();
        }
      }

    };

    Chosen.prototype.keydown_backstroke = function() {
      var next_available_destroy;
      if (this.pending_backstroke) {
        this.choice_destroy(this.pending_backstroke.find("a").first());
        return this.clear_backstroke();
      } else {
        next_available_destroy = this.search_container.siblings("li.search-choice").last();
        if (next_available_destroy.length && !next_available_destroy.hasClass("search-choice-disabled")) {
          this.pending_backstroke = next_available_destroy;
          if (this.single_backstroke_delete) {
            return this.keydown_backstroke();
          } else {
            return this.pending_backstroke.addClass("search-choice-focus");
          }
        }
      }
    };

    Chosen.prototype.clear_backstroke = function() {
      if (this.pending_backstroke) {
        this.pending_backstroke.removeClass("search-choice-focus");
      }
      return this.pending_backstroke = null;
    };

    Chosen.prototype.keydown_checker = function(evt) {
      var stroke, _ref;
      stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
      this.search_field_scale();
      if (stroke !== 8 && this.pending_backstroke) {
        this.clear_backstroke();
      }
      switch (stroke) {
        case 8:
          this.backstroke_length = this.search_field.val().length;
          break;
        case 9:
          if (this.results_showing && !this.is_multiple) {
            this.result_select(evt);
          }
          this.mouse_on_container = false;
          break;
        case 13:
          evt.preventDefault();
          break;
        case 38:
          evt.preventDefault();
          this.keyup_arrow();
          break;
        case 40:
          this.keydown_arrow();
          break;

        case 33:
            // Firefox and other non IE browsers
            if (evt.preventDefault)
            {
                evt.preventDefault();
                evt.stopPropagation();
            }
            // Internet Explorer
            else if (evt.keyCode)
            {
                evt.keyCode = 0;
                evt.returnValue = false;
                evt.cancelBubble = true;
            }

            var steps;
            if (this.result_highlight)
            {
                prev_sibs = this.result_highlight.prevAll("li.active-result");
                steps = Math.min(5, prev_sibs.length);
            }
            else
            {
                steps = 0;
            }

            for (iter = _i = 1; _i <= steps; iter = ++_i) 
            {
                this.keyup_arrow();
            }

            break;
        case 34:
            // Firefox and other non IE browsers
            if (evt.preventDefault)
            {
                evt.preventDefault();
                evt.stopPropagation();
            }
            // Internet Explorer
            else if (evt.keyCode)
            {
                evt.keyCode = 0;
                evt.returnValue = false;
                evt.cancelBubble = true;
            }

            var steps;
            if (this.result_highlight)
            {
                next_sibs = this.result_highlight.nextAll("li.active-result");
                steps = Math.min(5, next_sibs.length);
            }
            else
            {
                steps = 0;
            }

            for (iter = _j = 1; _j <= steps; iter = ++_j) 
            {
                this.keydown_arrow();
            }
          break;
      }
    };

    Chosen.prototype.search_field_scale = function() {
      var dd_top, div, h, style, style_block, styles, w, _i, _len;
      if (this.is_multiple) {
        h = 0;
        w = 0;
        style_block = "position:absolute; left: -1000px; top: -1000px; display:none;";
        styles = ['font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing'];
        for (_i = 0, _len = styles.length; _i < _len; _i++) {
          style = styles[_i];
          style_block += style + ":" + this.search_field.css(style) + ";";
        }
        div = $('<div />', {
          'style': style_block
        });
        div.text(this.search_field.val());
        $('body').append(div);
        w = div.width() + 25;
        div.remove();
        if (w > this.f_width - 10) {
          w = this.f_width - 10;
        }
        this.search_field.css({
          'width': w + 'px'
        });
        dd_top = this.container.height();
        return this.dropdown.css({
          "top": dd_top + "px"
        });
      }
    };

    Chosen.prototype.generate_random_id = function() {
      var string;
      string = "sel" + this.generate_random_char() + this.generate_random_char() + this.generate_random_char();
      while ($("#" + string).length > 0) {
        string += this.generate_random_char();
      }
      return string;
    };

    return Chosen;

  })(AbstractChosen);

  root.Chosen = Chosen;

  get_side_border_padding = function(elmt) {
    var side_border_padding;
    return side_border_padding = elmt.outerWidth() - elmt.width();
  };

  root.get_side_border_padding = get_side_border_padding;

}).call(this);


/*
 ### jQuery Star Rating Plugin v4.11 - 2013-03-14 ###
 * Home: http://www.fyneworks.com/jquery/star-rating/
 * Code: http://code.google.com/p/jquery-star-rating-plugin/
 *
    * Licensed under http://en.wikipedia.org/wiki/MIT_License
 ###
*/

/*# AVOID COLLISIONS #*/
;if(window.jQuery) (function($){
/*# AVOID COLLISIONS #*/
    
    // IE6 Background Image Fix
    if ((!$.support.opacity && !$.support.style)) try { document.execCommand("BackgroundImageCache", false, true)} catch(e) { };
    // Thanks to http://www.visualjquery.com/rating/rating_redux.html
    
    // plugin initialization
    $.fn.rating = function(options){
        if(this.length==0) return this; // quick fail
        
        // Handle API methods
        if(typeof arguments[0]=='string'){
            // Perform API methods on individual elements
            if(this.length>1){
                var args = arguments;
                return this.each(function(){
                    $.fn.rating.apply($(this), args);
    });
            };
            // Invoke API method handler
            $.fn.rating[arguments[0]].apply(this, $.makeArray(arguments).slice(1) || []);
            // Quick exit...
            return this;
        };
        
        // Initialize options for this call
        var options = $.extend(
            {}/* new object */,
            $.fn.rating.options/* default options */,
            options || {} /* just-in-time options */
        );
        
        // Allow multiple controls with the same name by making each call unique
        $.fn.rating.calls++;
        
        // loop through each matched element
        this
         .not('.star-rating-applied')
            .addClass('star-rating-applied')
        .each(function(){
            
            // Load control parameters / find context / etc
            var control, input = $(this);
            var eid = (this.name || 'unnamed-rating').replace(/\[|\]/g, '_').replace(/^\_+|\_+$/g,'');
            var context = $(this.form || document.body);
            
            // FIX: http://code.google.com/p/jquery-star-rating-plugin/issues/detail?id=23
            var raters = context.data('rating');
            if(!raters || raters.call!=$.fn.rating.calls) raters = { count:0, call:$.fn.rating.calls };
            var rater = raters[eid] || context.data('rating'+eid);
            
            // if rater is available, verify that the control still exists
            if(rater) control = rater.data('rating');
            
            if(rater && control)//{// save a byte!
                // add star to control if rater is available and the same control still exists
                control.count++;
                
            //}// save a byte!
            else{
                // create new control if first star or control element was removed/replaced
                
                // Initialize options for this rater
                control = $.extend(
                    {}/* new object */,
                    options || {} /* current call options */,
                    ($.metadata? input.metadata(): ($.meta?input.data():null)) || {}, /* metadata options */
                    { count:0, stars: [], inputs: [] }
                );
                
                // increment number of rating controls
                control.serial = raters.count++;
                
                // create rating element
                rater = $('<span class="star-rating-control"/>');
                input.before(rater);
                
                // Mark element for initialization (once all stars are ready)
                rater.addClass('rating-to-be-drawn');
                
                // Accept readOnly setting from 'disabled' property
                if(input.attr('disabled') || input.hasClass('disabled')) control.readOnly = true;
                
                // Accept required setting from class property (class='required')
                if(input.hasClass('required')) control.required = true;
                
                // Create 'cancel' button
                rater.append(
                    control.cancel = $('<div class="rating-cancel"><a title="' + control.cancel + '">' + control.cancelValue + '</a></div>')
                    .on('mouseover',function(){
                        $(this).rating('drain');
                        $(this).addClass('star-rating-hover');
                        //$(this).rating('focus');
                    })
                    .on('mouseout',function(){
                        $(this).rating('draw');
                        $(this).removeClass('star-rating-hover');
                        //$(this).rating('blur');
                    })
                    .on('click',function(){
                     $(this).rating('select');
                    })
                    .data('rating', control)
                );
                
            }; // first element of group
            
            // insert rating star (thanks Jan Fanslau rev125 for blind support https://code.google.com/p/jquery-star-rating-plugin/issues/detail?id=125)
//            var star = $('<div role="text" aria-label="'+ this.title +'" class="star-rating rater-'+ control.serial +'"><a title="' + this.title + '">' + this.value + '</a></div>');
            var star = $('<div role="text" aria-label="'+ this.title +'" class="star-rating rater-'+ control.serial +'"><a title="' + this.title + '"></a></div>');
            rater.append(star);
            
            // inherit attributes from input element
            if(this.id) star.attr('id', this.id);
            if(this.className) star.addClass(this.className);
            
            // Half-stars?
            if(control.half) control.split = 2;
            
            // Prepare division control
            if(typeof control.split=='number' && control.split>0){
                var stw = ($.fn.width ? star.width() : 0) || control.starWidth;
                var spi = (control.count % control.split), spw = Math.floor(stw/control.split);
                star
                // restrict star's width and hide overflow (already in CSS)
                .width(spw)
                // move the star left by using a negative margin
                // this is work-around to IE's stupid box model (position:relative doesn't work)
                .find('a').css({ 'margin-left':'-'+ (spi*spw) +'px' })
            };
            
            // readOnly?
            if(control.readOnly)//{ //save a byte!
                // Mark star as readOnly so user can customize display
                star.addClass('star-rating-readonly');
            //}  //save a byte!
            else//{ //save a byte!
             // Enable hover css effects
                star.addClass('star-rating-live')
                 // Attach mouse events
                    .on('mouseover',function(){
                        $(this).rating('fill');
                        $(this).rating('focus');
                    })
                    .on('mouseout',function(){
                        $(this).rating('draw');
                        $(this).rating('blur');
                    })
                    .on('click',function(){
                        $(this).rating('select');
                    })
                ;
            //}; //save a byte!
            
            // set current selection
            if(this.checked)    control.current = star;
            
            // set current select for links
            if(this.nodeName=="A"){
    if($(this).hasClass('selected'))
     control.current = star;
   };
            
            // hide input element
            input.hide();
            
            // backward compatibility, form element to plugin
            input.on('change.rating',function(event){
                if(event.selfTriggered) return false;
    $(this).rating('select');
   });
            
            // attach reference to star to input element and vice-versa
            star.data('rating.input', input.data('rating.star', star));
            
            // store control information in form (or body when form not available)
            control.stars[control.stars.length] = star[0];
            control.inputs[control.inputs.length] = input[0];
            control.rater = raters[eid] = rater;
            control.context = context;
            
            input.data('rating', control);
            rater.data('rating', control);
            star.data('rating', control);
            context.data('rating', raters);
            context.data('rating'+eid, rater); // required for ajax forms
  }); // each element
        
        // Initialize ratings (first draw)
        $('.rating-to-be-drawn').rating('draw').removeClass('rating-to-be-drawn');
        
        return this; // don't break the chain...
    };
    
    /*--------------------------------------------------------*/
    
    /*
        ### Core functionality and API ###
    */
    $.extend($.fn.rating, {
        // Used to append a unique serial number to internal control ID
        // each time the plugin is invoked so same name controls can co-exist
        calls: 0,
        
        focus: function(){
            var control = this.data('rating'); if(!control) return this;
            if(!control.focus) return this; // quick fail if not required
            // find data for event
            var input = $(this).data('rating.input') || $( this.tagName=='INPUT' ? this : null );
   // focus handler, as requested by focusdigital.co.uk
            if(control.focus) control.focus.apply(input[0], [input.val(), $('a', input.data('rating.star'))[0]]);
        }, // $.fn.rating.focus
        
        blur: function(){
            var control = this.data('rating'); if(!control) return this;
            if(!control.blur) return this; // quick fail if not required
            // find data for event
            var input = $(this).data('rating.input') || $( this.tagName=='INPUT' ? this : null );
   // blur handler, as requested by focusdigital.co.uk
            if(control.blur) control.blur.apply(input[0], [input.val(), $('a', input.data('rating.star'))[0]]);
        }, // $.fn.rating.blur
        
        fill: function(){ // fill to the current mouse position.
            var control = this.data('rating'); if(!control) return this;
            // do not execute when control is in read-only mode
            if(control.readOnly) return;
            // Reset all stars and highlight them up to this element
            this.rating('drain');
            this.prevAll().addBack().filter('.rater-'+ control.serial).addClass('star-rating-hover');
        },// $.fn.rating.fill
        
        drain: function() { // drain all the stars.
            var control = this.data('rating'); if(!control) return this;
            // do not execute when control is in read-only mode
            if(control.readOnly) return;
            // Reset all stars
            control.rater.children().filter('.rater-'+ control.serial).removeClass('star-rating-on').removeClass('star-rating-hover');
        },// $.fn.rating.drain
        
        draw: function(){ // set value and stars to reflect current selection
            var control = this.data('rating'); if(!control) return this;
            // Clear all stars
            this.rating('drain');
            // Set control value
            var current = $( control.current );//? control.current.data('rating.input') : null );
            var starson = current.length ? current.prevAll().addBack().filter('.rater-'+ control.serial) : null;
            if(starson) starson.addClass('star-rating-on');
            // Show/hide 'cancel' button
            control.cancel[control.readOnly || control.required?'hide':'show']();
            // Add/remove read-only classes to remove hand pointer
            this.siblings()[control.readOnly?'addClass':'removeClass']('star-rating-readonly');
        },// $.fn.rating.draw
        
        
        
        
        
        select: function(value,wantCallBack){ // select a value
            var control = this.data('rating'); if(!control) return this;
            // do not execute when control is in read-only mode
          if(control.readOnly) return;
            // clear selection
            control.current = null;
            // programmatically (based on user input)
            if(typeof value!='undefined' || this.length>1){
             // select by index (0 based)
                if(typeof value=='number')
             return $(control.stars[value]).rating('select',undefined,wantCallBack);
                // select by literal value (must be passed as a string
                if(typeof value=='string'){
                    //return
                    $.each(control.stars, function(){
                    //console.log($(this).data('rating.input'), $(this).data('rating.input').val(), value, $(this).data('rating.input').val()==value?'BINGO!':'');
                        if($(this).data('rating.input').val()==value) $(this).rating('select',undefined,wantCallBack);
                    });
                    // don't break the chain
            return this;
                };
            }
            else{
                control.current = this[0].tagName=='INPUT' ?
                 this.data('rating.star') :
                    (this.is('.rater-'+ control.serial) ? this : null);
            };
            // Update rating control state
            this.data('rating', control);
            // Update display
            this.rating('draw');
            // find current input and its sibblings
            var current = $( control.current ? control.current.data('rating.input') : null );
            var lastipt = $( control.inputs ).filter(':checked');
            var deadipt = $( control.inputs ).not(current);
            // check and uncheck elements as required
            deadipt.prop('checked',false);//.removeAttr('checked');
            current.prop('checked',true);//.attr('checked','checked');
            // trigger change on current or last selected input
            $(current.length? current : lastipt ).trigger({ type:'change', selfTriggered:true });
            // click callback, as requested here: http://plugins.jquery.com/node/1655
            if((wantCallBack || wantCallBack == undefined) && control.callback) control.callback.apply(current[0], [current.val(), $('a', control.current)[0]]);// callback event
            // don't break the chain
            return this;
  },// $.fn.rating.select
        
        
        
        
        
        readOnly: function(toggle, disable){ // make the control read-only (still submits value)
            var control = this.data('rating'); if(!control) return this;
            // setread-only status
            control.readOnly = toggle || toggle==undefined ? true : false;
            // enable/disable control value submission
            if(disable) $(control.inputs).attr("disabled", "disabled");
            else                $(control.inputs).removeAttr("disabled");
            // Update rating control state
            this.data('rating', control);
            // Update display
            this.rating('draw');
        },// $.fn.rating.readOnly
        
        disable: function(){ // make read-only and never submit value
            this.rating('readOnly', true, true);
        },// $.fn.rating.disable
        
        enable: function(){ // make read/write and submit value
            this.rating('readOnly', false, false);
        }// $.fn.rating.select
        
 });
    
    /*--------------------------------------------------------*/
    
    /*
        ### Default Settings ###
        eg.: You can override default control like this:
        $.fn.rating.options.cancel = 'Clear';
    */
    $.fn.rating.options = { //$.extend($.fn.rating, { options: {
            cancel: 'Cancel Rating',   // advisory title for the 'cancel' link
            cancelValue: '',           // value to submit when user click the 'cancel' link
            split: 0,                  // split the star into how many parts?
            
            // Width of star image in case the plugin can't work it out. This can happen if
            // the jQuery.dimensions plugin is not available OR the image is hidden at installation
            starWidth: 16//,
            
            //NB.: These don't need to be pre-defined (can be undefined/null) so let's save some code!
            //half:     false,         // just a shortcut to control.split = 2
            //required: false,         // disables the 'cancel' button so user can only select one of the specified values
            //readOnly: false,         // disable rating plugin interaction/ values cannot be.one('change',     //focus:    function(){},  // executed when stars are focused
            //blur:     function(){},  // executed when stars are focused
            //callback: function(){},  // executed when a star is clicked
 }; //} });
    
    /*--------------------------------------------------------*/
        
/*# AVOID COLLISIONS #*/
})(jQuery);
/*# AVOID COLLISIONS #*/



/* CONSOLE LOG WRAPPER */
window.log=function(){log.history=log.history||[];log.history.push(arguments);if(this.console){console.log(Array.prototype.slice.call(arguments))}};

    

/*
 * Metadata - jQuery plugin for parsing metadata from elements
 *
 * Copyright (c) 2006 John Resig, Yehuda Katz, Jörn Zaefferer, Paul McLanahan
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Sets the type of metadata to use. Metadata is encoded in JSON, and each property
 * in the JSON will become a property of the element itself.
 *
 * There are three supported types of metadata storage:
 *
 *   attr:  Inside an attribute. The name parameter indicates *which* attribute.
 *          
 *   class: Inside the class attribute, wrapped in curly braces: { }
 *   
 *   elem:  Inside a child element (e.g. a script tag). The
 *          name parameter indicates *which* element.
 *          
 * The metadata for an element is loaded the first time the element is accessed via jQuery.
 *
 * As a result, you can define the metadata type, use $(expr) to load the metadata into the elements
 * matched by expr, then redefine the metadata type and run another $(expr) for other elements.
 * 
 * @name $.metadata.setType
 *
 * @example <p id="one" class="some_class {item_id: 1, item_label: 'Label'}">This is a p</p>
 * @before $.metadata.setType("class")
 * @after $("#one").metadata().item_id == 1; $("#one").metadata().item_label == "Label"
 * @desc Reads metadata from the class attribute
 * 
 * @example <p id="one" class="some_class" data="{item_id: 1, item_label: 'Label'}">This is a p</p>
 * @before $.metadata.setType("attr", "data")
 * @after $("#one").metadata().item_id == 1; $("#one").metadata().item_label == "Label"
 * @desc Reads metadata from a "data" attribute
 * 
 * @example <p id="one" class="some_class"><script>{item_id: 1, item_label: 'Label'}</script>This is a p</p>
 * @before $.metadata.setType("elem", "script")
 * @after $("#one").metadata().item_id == 1; $("#one").metadata().item_label == "Label"
 * @desc Reads metadata from a nested script element
 * 
 * @param String type The encoding type
 * @param String name The name of the attribute to be used to get metadata (optional)
 * @cat Plugins/Metadata
 * @descr Sets the type of encoding to be used when loading metadata for the first time
 * @type undefined
 * @see metadata()
 */

(function($) {

$.extend({
    metadata : {
        defaults : {
            type: 'class',
            name: 'metadata',
            cre: /({.*})/,
            single: 'metadata'
        },
        setType: function( type, name ){
            this.defaults.type = type;
            this.defaults.name = name;
        },
        get: function( elem, opts ){
            var settings = $.extend({},this.defaults,opts);
            // check for empty string in single property
            if ( !settings.single.length ) settings.single = 'metadata';
            
            var data = $.data(elem, settings.single);
            // returned cached data if it already exists
            if ( data ) return data;
            
            data = "{}";
            
            if ( settings.type == "class" ) {
                var m = settings.cre.exec( elem.className );
                if ( m )
                    data = m[1];
            } else if ( settings.type == "elem" ) {
                if( !elem.getElementsByTagName )
                    return undefined;
                var e = elem.getElementsByTagName(settings.name);
                if ( e.length )
                    data = $.trim(e[0].innerHTML);
            } else if ( elem.getAttribute != undefined ) {
                var attr = elem.getAttribute( settings.name );
                if ( attr )
                    data = attr;
            }
            
            if ( data.indexOf( '{' ) <0 )
            data = "{" + data + "}";
            
            data = eval("(" + data + ")");
            
            $.data( elem, settings.single, data );
            return data;
        }
    }
});

/**
 * Returns the metadata object for the first member of the jQuery object.
 *
 * @name metadata
 * @descr Returns element's metadata object
 * @param Object opts An object contianing settings to override the defaults
 * @type jQuery
 * @cat Plugins/Metadata
 */
$.fn.metadata = function( opts ){
    return $.metadata.get( this[0], opts );
};

})(jQuery);


/*
 OverlappingMarkerSpiderfier
https://github.com/jawj/OverlappingMarkerSpiderfier
Copyright (c) 2011 - 2012 George MacKerron
Released under the MIT licence: http://opensource.org/licenses/mit-license
Note: The Google Maps API v3 must be included *before* this code
*/

(function(){
var h=!0,i=null,n=!1,p,q={}.hasOwnProperty,s=[].slice;
if(((p=this.google)!=i?p.maps:void 0)!=i){var u=function(b,c){var a,e,d,f,g=this;this.map=b;c==i&&(c={});for(a in c)q.call(c,a)&&(e=c[a],this[a]=e);this.e=new this.constructor.g(this.map);this.n();this.b={};f=["click","zoom_changed","maptypeid_changed"];e=0;for(d=f.length;e<d;e++)a=f[e],t.addListener(this.map,a,function(){return g.unspiderfy()})},t,v,w,x,y,z,A;z=u.prototype;z.VERSION="0.3.1";v=google.maps;t=v.event;y=v.MapTypeId;A=2*Math.PI;z.keepSpiderfied=n;z.markersWontHide=n;z.markersWontMove=
n;z.nearbyDistance=20;z.circleSpiralSwitchover=9;z.circleFootSeparation=23;z.circleStartAngle=A/12;z.spiralFootSeparation=26;z.spiralLengthStart=11;z.spiralLengthFactor=4;z.spiderfiedZIndex=1E3;z.usualLegZIndex=10;z.highlightedLegZIndex=20;z.legWeight=1.5;z.legColors={usual:{},highlighted:{}};x=z.legColors.usual;w=z.legColors.highlighted;x[y.HYBRID]=x[y.SATELLITE]="#fff";w[y.HYBRID]=w[y.SATELLITE]="#f00";x[y.TERRAIN]=x[y.ROADMAP]="#444";w[y.TERRAIN]=w[y.ROADMAP]="#f00";z.n=function(){this.a=[];this.j=
[]};z.addMarker=function(b){var c,a=this;if(b._oms!=i)return this;b._oms=h;c=[t.addListener(b,"click",function(){return a.F(b)})];this.markersWontHide||c.push(t.addListener(b,"visible_changed",function(){return a.o(b,n)}));this.markersWontMove||c.push(t.addListener(b,"position_changed",function(){return a.o(b,h)}));this.j.push(c);this.a.push(b);return this};z.o=function(b,c){if(b._omsData!=i&&(c||!b.getVisible())&&!(this.s!=i||this.t!=i))return this.H(c?b:i)};z.getMarkers=function(){return this.a.slice(0)};
z.removeMarker=function(b){var c,a,e,d,f;b._omsData!=i&&this.unspiderfy();c=this.m(this.a,b);if(0>c)return this;e=this.j.splice(c,1)[0];d=0;for(f=e.length;d<f;d++)a=e[d],t.removeListener(a);delete b._oms;this.a.splice(c,1);return this};z.clearMarkers=function(){var b,c,a,e,d,f,g,j;this.unspiderfy();j=this.a;b=e=0;for(f=j.length;e<f;b=++e){a=j[b];c=this.j[b];d=0;for(g=c.length;d<g;d++)b=c[d],t.removeListener(b);delete a._oms}this.n();return this};z.addListener=function(b,c){var a,e;((e=(a=this.b)[b])!=
i?e:a[b]=[]).push(c);return this};z.removeListener=function(b,c){var a;a=this.m(this.b[b],c);0>a||this.b[b].splice(a,1);return this};z.clearListeners=function(b){this.b[b]=[];return this};z.trigger=function(){var b,c,a,e,d,f;c=arguments[0];b=2<=arguments.length?s.call(arguments,1):[];c=(a=this.b[c])!=i?a:[];f=[];e=0;for(d=c.length;e<d;e++)a=c[e],f.push(a.apply(i,b));return f};z.u=function(b,c){var a,e,d,f,g;d=this.circleFootSeparation*(2+b)/A;e=A/b;g=[];for(a=f=0;0<=b?f<b:f>b;a=0<=b?++f:--f)a=this.circleStartAngle+
a*e,g.push(new v.Point(c.x+d*Math.cos(a),c.y+d*Math.sin(a)));return g};z.v=function(b,c){var a,e,d,f,g;d=this.spiralLengthStart;a=0;g=[];for(e=f=0;0<=b?f<b:f>b;e=0<=b?++f:--f)a+=this.spiralFootSeparation/d+5E-4*e,e=new v.Point(c.x+d*Math.cos(a),c.y+d*Math.sin(a)),d+=A*this.spiralLengthFactor/a,g.push(e);return g};z.F=function(b){var c,a,e,d,f,g,j,k,m;d=b._omsData!=i;(!d||!this.keepSpiderfied)&&this.unspiderfy();if(d||this.map.getStreetView().getVisible()||"GoogleEarthAPI"===this.map.getMapTypeId())return this.trigger("click",
b);d=[];f=[];c=this.nearbyDistance;g=c*c;e=this.c(b.position);m=this.a;j=0;for(k=m.length;j<k;j++)c=m[j],c.map!=i&&c.getVisible()&&(a=this.c(c.position),this.f(a,e)<g?d.push({A:c,p:a}):f.push(c));return 1===d.length?this.trigger("click",b):this.G(d,f)};z.markersNearMarker=function(b,c){var a,e,d,f,g,j,k,m,l,r;c==i&&(c=n);if(this.e.getProjection()==i)throw"Must wait for 'idle' event on map before calling markersNearMarker";a=this.nearbyDistance;g=a*a;d=this.c(b.position);f=[];m=this.a;j=0;for(k=m.length;j<
k;j++)if(a=m[j],!(a===b||a.map==i||!a.getVisible()))if(e=this.c((l=(r=a._omsData)!=i?r.l:void 0)!=i?l:a.position),this.f(e,d)<g&&(f.push(a),c))break;return f};z.markersNearAnyOtherMarker=function(){var b,c,a,e,d,f,g,j,k,m,l,r;if(this.e.getProjection()==i)throw"Must wait for 'idle' event on map before calling markersNearAnyOtherMarker";f=this.nearbyDistance;b=f*f;e=this.a;f=[];l=0;for(a=e.length;l<a;l++)c=e[l],f.push({q:this.c((g=(k=c._omsData)!=i?k.l:void 0)!=i?g:c.position),d:n});l=this.a;c=g=0;
for(k=l.length;g<k;c=++g)if(a=l[c],a.map!=i&&a.getVisible()&&(e=f[c],!e.d)){r=this.a;a=j=0;for(m=r.length;j<m;a=++j)if(d=r[a],a!==c&&(d.map!=i&&d.getVisible())&&(d=f[a],(!(a<c)||d.d)&&this.f(e.q,d.q)<b)){e.d=d.d=h;break}}l=this.a;a=[];b=g=0;for(k=l.length;g<k;b=++g)c=l[b],f[b].d&&a.push(c);return a};z.z=function(b){var c=this;return{h:function(){return b._omsData.i.setOptions({strokeColor:c.legColors.highlighted[c.map.mapTypeId],zIndex:c.highlightedLegZIndex})},k:function(){return b._omsData.i.setOptions({strokeColor:c.legColors.usual[c.map.mapTypeId],
zIndex:c.usualLegZIndex})}}};z.G=function(b,c){var a,e,d,f,g,j;this.s=h;d=b.length;a=this.C(function(){var a,c,d;d=[];a=0;for(c=b.length;a<c;a++)j=b[a],d.push(j.p);return d}());d=d>=this.circleSpiralSwitchover?this.v(d,a).reverse():this.u(d,a);var k,m,l,r=this;l=[];k=0;for(m=d.length;k<m;k++)e=d[k],a=this.D(e),g=this.B(b,function(a){return r.f(a.p,e)}),g=g.A,f=new v.Polyline({map:this.map,path:[g.position,a],strokeColor:this.legColors.usual[this.map.mapTypeId],strokeWeight:this.legWeight,zIndex:this.usualLegZIndex}),
g._omsData={l:g.position,i:f},this.legColors.highlighted[this.map.mapTypeId]!==this.legColors.usual[this.map.mapTypeId]&&(f=this.z(g),g._omsData.w={h:t.addListener(g,"mouseover",f.h),k:t.addListener(g,"mouseout",f.k)}),g.setPosition(a),g.setZIndex(Math.round(this.spiderfiedZIndex+e.y)),l.push(g);delete this.s;this.r=h;return this.trigger("spiderfy",l,c)};z.unspiderfy=function(b){var c,a,e,d,f,g,j;b==i&&(b=i);if(this.r==i)return this;this.t=h;d=[];e=[];j=this.a;f=0;for(g=j.length;f<g;f++)a=j[f],a._omsData!=
i?(a._omsData.i.setMap(i),a!==b&&a.setPosition(a._omsData.l),a.setZIndex(i),c=a._omsData.w,c!=i&&(t.removeListener(c.h),t.removeListener(c.k)),delete a._omsData,d.push(a)):e.push(a);delete this.t;delete this.r;this.trigger("unspiderfy",d,e);return this};z.f=function(b,c){var a,e;a=b.x-c.x;e=b.y-c.y;return a*a+e*e};z.C=function(b){var c,a,e,d,f;d=a=e=0;for(f=b.length;d<f;d++)c=b[d],a+=c.x,e+=c.y;b=b.length;return new v.Point(a/b,e/b)};z.c=function(b){return this.e.getProjection().fromLatLngToDivPixel(b)};
z.D=function(b){return this.e.getProjection().fromDivPixelToLatLng(b)};z.B=function(b,c){var a,e,d,f,g,j;d=g=0;for(j=b.length;g<j;d=++g)if(f=b[d],f=c(f),"undefined"===typeof a||a===i||f<e)e=f,a=d;return b.splice(a,1)[0]};z.m=function(b,c){var a,e,d,f;if(b.indexOf!=i)return b.indexOf(c);a=d=0;for(f=b.length;d<f;a=++d)if(e=b[a],e===c)return a;return-1};u.g=function(b){return this.setMap(b)};u.g.prototype=new v.OverlayView;u.g.prototype.draw=function(){};this.OverlappingMarkerSpiderfier=u};}).call(this);


// ==ClosureCompiler==
// @compilation_level ADVANCED_OPTIMIZATIONS
// @externs_url http://closure-compiler.googlecode.com/svn/trunk/contrib/externs/maps/google_maps_api_v3_3.js
// ==/ClosureCompiler==

/**
 * @name MarkerClusterer for Google Maps v3
 * @version version 1.0
 * @author Luke Mahe
 * @fileoverview
 * The library creates and manages per-zoom-level clusters for large amounts of
 * markers.
 * <br/>
 * This is a v3 implementation of the
 * <a href="http://gmaps-utility-library-dev.googlecode.com/svn/tags/markerclusterer/"
 * >v2 MarkerClusterer</a>.
 */

/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


/**
 * A Marker Clusterer that clusters markers.
 *
 * @param {google.maps.Map} map The Google map to attach to.
 * @param {Array.<google.maps.Marker>=} opt_markers Optional markers to add to
 *   the cluster.
 * @param {Object=} opt_options support the following options:
 *     'gridSize': (number) The grid size of a cluster in pixels.
 *     'maxZoom': (number) The maximum zoom level that a marker can be part of a
 *                cluster.
 *     'zoomOnClick': (boolean) Whether the default behaviour of clicking on a
 *                    cluster is to zoom into it.
 *     'averageCenter': (boolean) Wether the center of each cluster should be
 *                      the average of all markers in the cluster.
 *     'minimumClusterSize': (number) The minimum number of markers to be in a
 *                           cluster before the markers are hidden and a count
 *                           is shown.
 *     'styles': (object) An object that has style properties:
 *       'url': (string) The image url.
 *       'height': (number) The image height.
 *       'width': (number) The image width.
 *       'anchor': (Array) The anchor position of the label text.
 *       'textColor': (string) The text color.
 *       'textSize': (number) The text size.
 *       'backgroundPosition': (string) The position of the backgound x, y.
 * @constructor
 * @extends google.maps.OverlayView
 */
function MarkerClusterer(map, opt_markers, opt_options) {
  // MarkerClusterer implements google.maps.OverlayView interface. We use the
  // extend function to extend MarkerClusterer with google.maps.OverlayView
  // because it might not always be available when the code is defined so we
  // look for it at the last possible moment. If it doesn't exist now then
  // there is no point going ahead :)
  this.extend(MarkerClusterer, google.maps.OverlayView);
  this.map_ = map;

  /**
   * @type {Array.<google.maps.Marker>}
   * @private
   */
  this.markers_ = [];

  /**
   *  @type {Array.<Cluster>}
   */
  this.clusters_ = [];

  this.sizes = [53, 56, 66, 78, 90];

  /**
   * @private
   */
  this.styles_ = [];

  /**
   * @type {boolean}
   * @private
   */
  this.ready_ = false;

  var options = opt_options || {};

  /**
   * @type {number}
   * @private
   */
  this.gridSize_ = options['gridSize'] || 60;

  /**
   * @private
   */
  this.minClusterSize_ = options['minimumClusterSize'] || 2;


  /**
   * @type {?number}
   * @private
   */
  this.maxZoom_ = options['maxZoom'] || null;

  this.styles_ = options['styles'] || [];

  /**
   * @type {string}
   * @private
   */
  this.imagePath_ = options['imagePath'] ||
      this.MARKER_CLUSTER_IMAGE_PATH_;

  /**
   * @type {string}
   * @private
   */
  this.imageExtension_ = options['imageExtension'] ||
      this.MARKER_CLUSTER_IMAGE_EXTENSION_;

  /**
   * @type {boolean}
   * @private
   */
  this.zoomOnClick_ = true;

  if (options['zoomOnClick'] != undefined) {
    this.zoomOnClick_ = options['zoomOnClick'];
  }

  /**
   * @type {boolean}
   * @private
   */
  this.averageCenter_ = false;

  if (options['averageCenter'] != undefined) {
    this.averageCenter_ = options['averageCenter'];
  }

  this.setupStyles_();

  this.setMap(map);

  /**
   * @type {number}
   * @private
   */
  this.prevZoom_ = this.map_.getZoom();

  // Add the map event listeners
  var that = this;
  google.maps.event.addListener(this.map_, 'zoom_changed', function() {
    var zoom = that.map_.getZoom();

    if (that.prevZoom_ != zoom) {
      that.prevZoom_ = zoom;
      that.resetViewport();
    }
  });

  google.maps.event.addListener(this.map_, 'idle', function() {
    that.redraw();
  });

  // Finally, add the markers
  if (opt_markers && opt_markers.length) {
    this.addMarkers(opt_markers, false);
  }
}


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_PATH_ =
    'http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/' +
    'images/m';


/**
 * The marker cluster image path.
 *
 * @type {string}
 * @private
 */
MarkerClusterer.prototype.MARKER_CLUSTER_IMAGE_EXTENSION_ = 'png';


/**
 * Extends a objects prototype by anothers.
 *
 * @param {Object} obj1 The object to be extended.
 * @param {Object} obj2 The object to extend with.
 * @return {Object} The new extended object.
 * @ignore
 */
MarkerClusterer.prototype.extend = function(obj1, obj2) {
  return (function(object) {
    for (var property in object.prototype) {
      this.prototype[property] = object.prototype[property];
    }
    return this;
  }).apply(obj1, [obj2]);
};


/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.onAdd = function() {
  this.setReady_(true);
};

/**
 * Implementaion of the interface method.
 * @ignore
 */
MarkerClusterer.prototype.draw = function() {};

/**
 * Sets up the styles object.
 *
 * @private
 */
MarkerClusterer.prototype.setupStyles_ = function() {
  if (this.styles_.length) {
    return;
  }

  for (var i = 0, size; size = this.sizes[i]; i++) {
    this.styles_.push({
      url: this.imagePath_ + (i + 1) + '.' + this.imageExtension_,
      height: size,
      width: size
    });
  }
};

/**
 *  Fit the map to the bounds of the markers in the clusterer.
 */
MarkerClusterer.prototype.fitMapToMarkers = function() {
  var markers = this.getMarkers();
  var bounds = new google.maps.LatLngBounds();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }

  this.map_.fitBounds(bounds);
};


/**
 *  Sets the styles.
 *
 *  @param {Object} styles The style to set.
 */
MarkerClusterer.prototype.setStyles = function(styles) {
  this.styles_ = styles;
};


/**
 *  Gets the styles.
 *
 *  @return {Object} The styles object.
 */
MarkerClusterer.prototype.getStyles = function() {
  return this.styles_;
};


/**
 * Whether zoom on click is set.
 *
 * @return {boolean} True if zoomOnClick_ is set.
 */
MarkerClusterer.prototype.isZoomOnClick = function() {
  return this.zoomOnClick_;
};

/**
 * Whether average center is set.
 *
 * @return {boolean} True if averageCenter_ is set.
 */
MarkerClusterer.prototype.isAverageCenter = function() {
  return this.averageCenter_;
};


/**
 *  Returns the array of markers in the clusterer.
 *
 *  @return {Array.<google.maps.Marker>} The markers.
 */
MarkerClusterer.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 *  Returns the number of markers in the clusterer
 *
 *  @return {Number} The number of markers.
 */
MarkerClusterer.prototype.getTotalMarkers = function() {
  return this.markers_.length;
};


/**
 *  Sets the max zoom for the clusterer.
 *
 *  @param {number} maxZoom The max zoom level.
 */
MarkerClusterer.prototype.setMaxZoom = function(maxZoom) {
  this.maxZoom_ = maxZoom;
};


/**
 *  Gets the max zoom for the clusterer.
 *
 *  @return {number} The max zoom level.
 */
MarkerClusterer.prototype.getMaxZoom = function() {
  return this.maxZoom_;
};


/**
 *  The function for calculating the cluster icon image.
 *
 *  @param {Array.<google.maps.Marker>} markers The markers in the clusterer.
 *  @param {number} numStyles The number of styles available.
 *  @return {Object} A object properties: 'text' (string) and 'index' (number).
 *  @private
 */
MarkerClusterer.prototype.calculator_ = function(markers, numStyles) {
  var index = 0;
  var count = markers.length;
  var dv = count;
  while (dv !== 0) {
    dv = parseInt(dv / 10, 10);
    index++;
  }

  index = Math.min(index, numStyles);
  return {
    text: count,
    index: index
  };
};


/**
 * Set the calculator function.
 *
 * @param {function(Array, number)} calculator The function to set as the
 *     calculator. The function should return a object properties:
 *     'text' (string) and 'index' (number).
 *
 */
MarkerClusterer.prototype.setCalculator = function(calculator) {
  this.calculator_ = calculator;
};


/**
 * Get the calculator function.
 *
 * @return {function(Array, number)} the calculator function.
 */
MarkerClusterer.prototype.getCalculator = function() {
  return this.calculator_;
};


/**
 * Add an array of markers to the clusterer.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarkers = function(markers, opt_nodraw) {
  for (var i = 0, marker; marker = markers[i]; i++) {
    this.pushMarkerTo_(marker);
  }
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Pushes a marker to the clusterer.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.pushMarkerTo_ = function(marker) {
  marker.isAdded = false;
  if (marker['draggable']) {
    // If the marker is draggable add a listener so we update the clusters on
    // the drag end.
    var that = this;
    google.maps.event.addListener(marker, 'dragend', function() {
      marker.isAdded = false;
      that.repaint();
    });
  }
  this.markers_.push(marker);
};


/**
 * Adds a marker to the clusterer and redraws if needed.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @param {boolean=} opt_nodraw Whether to redraw the clusters.
 */
MarkerClusterer.prototype.addMarker = function(marker, opt_nodraw) {
  this.pushMarkerTo_(marker);
  if (!opt_nodraw) {
    this.redraw();
  }
};


/**
 * Removes a marker and returns true if removed, false if not
 *
 * @param {google.maps.Marker} marker The marker to remove
 * @return {boolean} Whether the marker was removed or not
 * @private
 */
MarkerClusterer.prototype.removeMarker_ = function(marker) {
  var index = -1;
  if (this.markers_.indexOf) {
    index = this.markers_.indexOf(marker);
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        index = i;
        break;
      }
    }
  }

  if (index == -1) {
    // Marker is not in our list of markers.
    return false;
  }

  marker.setMap(null);

  this.markers_.splice(index, 1);

  return true;
};


/**
 * Remove a marker from the cluster.
 *
 * @param {google.maps.Marker} marker The marker to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 * @return {boolean} True if the marker was removed.
 */
MarkerClusterer.prototype.removeMarker = function(marker, opt_nodraw) {
  var removed = this.removeMarker_(marker);

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  } else {
   return false;
  }
};


/**
 * Removes an array of markers from the cluster.
 *
 * @param {Array.<google.maps.Marker>} markers The markers to remove.
 * @param {boolean=} opt_nodraw Optional boolean to force no redraw.
 */
MarkerClusterer.prototype.removeMarkers = function(markers, opt_nodraw) {
  var removed = false;

  for (var i = 0, marker; marker = markers[i]; i++) {
    var r = this.removeMarker_(marker);
    removed = removed || r;
  }

  if (!opt_nodraw && removed) {
    this.resetViewport();
    this.redraw();
    return true;
  }
};


/**
 * Sets the clusterer's ready state.
 *
 * @param {boolean} ready The state.
 * @private
 */
MarkerClusterer.prototype.setReady_ = function(ready) {
  if (!this.ready_) {
    this.ready_ = ready;
    this.createClusters_();
  }
};


/**
 * Returns the number of clusters in the clusterer.
 *
 * @return {number} The number of clusters.
 */
MarkerClusterer.prototype.getTotalClusters = function() {
  return this.clusters_.length;
};


/**
 * Returns the google map that the clusterer is associated with.
 *
 * @return {google.maps.Map} The map.
 */
MarkerClusterer.prototype.getMap = function() {
  return this.map_;
};


/**
 * Sets the google map that the clusterer is associated with.
 *
 * @param {google.maps.Map} map The map.
 */
MarkerClusterer.prototype.setMap = function(map) {
  this.map_ = map;
};


/**
 * Returns the size of the grid.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getGridSize = function() {
  return this.gridSize_;
};


/**
 * Sets the size of the grid.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setGridSize = function(size) {
  this.gridSize_ = size;
};


/**
 * Returns the min cluster size.
 *
 * @return {number} The grid size.
 */
MarkerClusterer.prototype.getMinClusterSize = function() {
  return this.minClusterSize_;
};

/**
 * Sets the min cluster size.
 *
 * @param {number} size The grid size.
 */
MarkerClusterer.prototype.setMinClusterSize = function(size) {
  this.minClusterSize_ = size;
};


/**
 * Extends a bounds object by the grid size.
 *
 * @param {google.maps.LatLngBounds} bounds The bounds to extend.
 * @return {google.maps.LatLngBounds} The extended bounds.
 */
MarkerClusterer.prototype.getExtendedBounds = function(bounds) {
  var projection = this.getProjection();

  // Turn the bounds into latlng.
  var tr = new google.maps.LatLng(bounds.getNorthEast().lat(),
      bounds.getNorthEast().lng());
  var bl = new google.maps.LatLng(bounds.getSouthWest().lat(),
      bounds.getSouthWest().lng());

  // Convert the points to pixels and the extend out by the grid size.
  var trPix = projection.fromLatLngToDivPixel(tr);
  trPix.x += this.gridSize_;
  trPix.y -= this.gridSize_;

  var blPix = projection.fromLatLngToDivPixel(bl);
  blPix.x -= this.gridSize_;
  blPix.y += this.gridSize_;

  // Convert the pixel points back to LatLng
  var ne = projection.fromDivPixelToLatLng(trPix);
  var sw = projection.fromDivPixelToLatLng(blPix);

  // Extend the bounds to contain the new bounds.
  bounds.extend(ne);
  bounds.extend(sw);

  return bounds;
};


/**
 * Determins if a marker is contained in a bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @param {google.maps.LatLngBounds} bounds The bounds to check against.
 * @return {boolean} True if the marker is in the bounds.
 * @private
 */
MarkerClusterer.prototype.isMarkerInBounds_ = function(marker, bounds) {
  return bounds.contains(marker.getPosition());
};


/**
 * Clears all clusters and markers from the clusterer.
 */
MarkerClusterer.prototype.clearMarkers = function() {
  this.resetViewport(true);

  // Set the markers a empty array.
  this.markers_ = [];
};


/**
 * Clears all existing clusters and recreates them.
 * @param {boolean} opt_hide To also hide the marker.
 */
MarkerClusterer.prototype.resetViewport = function(opt_hide) {
  // Remove all the clusters
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    cluster.remove();
  }

  // Reset the markers to not be added and to be invisible.
  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    marker.isAdded = false;
    if (opt_hide) {
      marker.setMap(null);
    }
  }

  this.clusters_ = [];
};

/**
 *
 */
MarkerClusterer.prototype.repaint = function() {
  var oldClusters = this.clusters_.slice();
  this.clusters_.length = 0;
  this.resetViewport();
  this.redraw();

  // Remove the old clusters.
  // Do it in a timeout so the other clusters have been drawn first.
  window.setTimeout(function() {
    for (var i = 0, cluster; cluster = oldClusters[i]; i++) {
      cluster.remove();
    }
  }, 0);
};


/**
 * Redraws the clusters.
 */
MarkerClusterer.prototype.redraw = function() {
  this.createClusters_();
};


/**
 * Calculates the distance between two latlng locations in km.
 * @see http://www.movable-type.co.uk/scripts/latlong.html
 *
 * @param {google.maps.LatLng} p1 The first lat lng point.
 * @param {google.maps.LatLng} p2 The second lat lng point.
 * @return {number} The distance between the two points in km.
 * @private
*/
MarkerClusterer.prototype.distanceBetweenPoints_ = function(p1, p2) {
  if (!p1 || !p2) {
    return 0;
  }

  var R = 6371; // Radius of the Earth in km
  var dLat = (p2.lat() - p1.lat()) * Math.PI / 180;
  var dLon = (p2.lng() - p1.lng()) * Math.PI / 180;
  var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(p1.lat() * Math.PI / 180) * Math.cos(p2.lat() * Math.PI / 180) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  var d = R * c;
  return d;
};


/**
 * Add a marker to a cluster, or creates a new cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @private
 */
MarkerClusterer.prototype.addToClosestCluster_ = function(marker) {
  var distance = 40000; // Some large number
  var clusterToAddTo = null;
  var pos = marker.getPosition();
  for (var i = 0, cluster; cluster = this.clusters_[i]; i++) {
    var center = cluster.getCenter();
    if (center) {
      var d = this.distanceBetweenPoints_(center, marker.getPosition());
      if (d < distance) {
        distance = d;
        clusterToAddTo = cluster;
      }
    }
  }

  if (clusterToAddTo && clusterToAddTo.isMarkerInClusterBounds(marker)) {
    clusterToAddTo.addMarker(marker);
  } else {
    var cluster = new Cluster(this);
    cluster.addMarker(marker);
    this.clusters_.push(cluster);
  }
};


/**
 * Creates the clusters.
 *
 * @private
 */
MarkerClusterer.prototype.createClusters_ = function() {
  if (!this.ready_) {
    return;
  }

  // Get our current map view bounds.
  // Create a new bounds object so we don't affect the map.
  var mapBounds = new google.maps.LatLngBounds(this.map_.getBounds().getSouthWest(),
      this.map_.getBounds().getNorthEast());
  var bounds = this.getExtendedBounds(mapBounds);

  for (var i = 0, marker; marker = this.markers_[i]; i++) {
    if (!marker.isAdded && this.isMarkerInBounds_(marker, bounds)) {
      this.addToClosestCluster_(marker);
    }
  }
};


/**
 * A cluster that contains markers.
 *
 * @param {MarkerClusterer} markerClusterer The markerclusterer that this
 *     cluster is associated with.
 * @constructor
 * @ignore
 */
function Cluster(markerClusterer) {
  this.markerClusterer_ = markerClusterer;
  this.map_ = markerClusterer.getMap();
  this.gridSize_ = markerClusterer.getGridSize();
  this.minClusterSize_ = markerClusterer.getMinClusterSize();
  this.averageCenter_ = markerClusterer.isAverageCenter();
  this.center_ = null;
  this.markers_ = [];
  this.bounds_ = null;
  this.clusterIcon_ = new ClusterIcon(this, markerClusterer.getStyles(),
      markerClusterer.getGridSize());
}

/**
 * Determins if a marker is already added to the cluster.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker is already added.
 */
Cluster.prototype.isMarkerAlreadyAdded = function(marker) {
  if (this.markers_.indexOf) {
    return this.markers_.indexOf(marker) != -1;
  } else {
    for (var i = 0, m; m = this.markers_[i]; i++) {
      if (m == marker) {
        return true;
      }
    }
  }
  return false;
};


/**
 * Add a marker the cluster.
 *
 * @param {google.maps.Marker} marker The marker to add.
 * @return {boolean} True if the marker was added.
 */
Cluster.prototype.addMarker = function(marker) {
  if (this.isMarkerAlreadyAdded(marker)) {
    return false;
  }

  if (!this.center_) {
    this.center_ = marker.getPosition();
    this.calculateBounds_();
  } else {
    if (this.averageCenter_) {
      var l = this.markers_.length + 1;
      var lat = (this.center_.lat() * (l-1) + marker.getPosition().lat()) / l;
      var lng = (this.center_.lng() * (l-1) + marker.getPosition().lng()) / l;
      this.center_ = new google.maps.LatLng(lat, lng);
      this.calculateBounds_();
    }
  }

  marker.isAdded = true;
  this.markers_.push(marker);

  var len = this.markers_.length;
  if (len < this.minClusterSize_ && marker.getMap() != this.map_) {
    // Min cluster size not reached so show the marker.
    marker.setMap(this.map_);
  }

  if (len == this.minClusterSize_) {
    // Hide the markers that were showing.
    for (var i = 0; i < len; i++) {
      this.markers_[i].setMap(null);
    }
  }

  if (len >= this.minClusterSize_) {
    marker.setMap(null);
  }

  this.updateIcon();
  return true;
};


/**
 * Returns the marker clusterer that the cluster is associated with.
 *
 * @return {MarkerClusterer} The associated marker clusterer.
 */
Cluster.prototype.getMarkerClusterer = function() {
  return this.markerClusterer_;
};


/**
 * Returns the bounds of the cluster.
 *
 * @return {google.maps.LatLngBounds} the cluster bounds.
 */
Cluster.prototype.getBounds = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  var markers = this.getMarkers();
  for (var i = 0, marker; marker = markers[i]; i++) {
    bounds.extend(marker.getPosition());
  }
  return bounds;
};


/**
 * Removes the cluster
 */
Cluster.prototype.remove = function() {
  this.clusterIcon_.remove();
  this.markers_.length = 0;
  delete this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {number} The cluster center.
 */
Cluster.prototype.getSize = function() {
  return this.markers_.length;
};


/**
 * Returns the center of the cluster.
 *
 * @return {Array.<google.maps.Marker>} The cluster center.
 */
Cluster.prototype.getMarkers = function() {
  return this.markers_;
};


/**
 * Returns the center of the cluster.
 *
 * @return {google.maps.LatLng} The cluster center.
 */
Cluster.prototype.getCenter = function() {
  return this.center_;
};


/**
 * Calculated the extended bounds of the cluster with the grid.
 *
 * @private
 */
Cluster.prototype.calculateBounds_ = function() {
  var bounds = new google.maps.LatLngBounds(this.center_, this.center_);
  this.bounds_ = this.markerClusterer_.getExtendedBounds(bounds);
};


/**
 * Determines if a marker lies in the clusters bounds.
 *
 * @param {google.maps.Marker} marker The marker to check.
 * @return {boolean} True if the marker lies in the bounds.
 */
Cluster.prototype.isMarkerInClusterBounds = function(marker) {
  return this.bounds_.contains(marker.getPosition());
};


/**
 * Returns the map that the cluster is associated with.
 *
 * @return {google.maps.Map} The map.
 */
Cluster.prototype.getMap = function() {
  return this.map_;
};


/**
 * Updates the cluster icon
 */
Cluster.prototype.updateIcon = function() {
  var zoom = this.map_.getZoom();
  var mz = this.markerClusterer_.getMaxZoom();

  if (mz && zoom > mz) {
    // The zoom is greater than our max zoom so show all the markers in cluster.
    for (var i = 0, marker; marker = this.markers_[i]; i++) {
      marker.setMap(this.map_);
    }
    return;
  }

  if (this.markers_.length < this.minClusterSize_) {
    // Min cluster size not yet reached.
    this.clusterIcon_.hide();
    return;
  }

  var numStyles = this.markerClusterer_.getStyles().length;
  var sums = this.markerClusterer_.getCalculator()(this.markers_, numStyles);
  this.clusterIcon_.setCenter(this.center_);
  this.clusterIcon_.setSums(sums);
  this.clusterIcon_.show();
};


/**
 * A cluster icon
 *
 * @param {Cluster} cluster The cluster to be associated with.
 * @param {Object} styles An object that has style properties:
 *     'url': (string) The image url.
 *     'height': (number) The image height.
 *     'width': (number) The image width.
 *     'anchor': (Array) The anchor position of the label text.
 *     'textColor': (string) The text color.
 *     'textSize': (number) The text size.
 *     'backgroundPosition: (string) The background postition x, y.
 * @param {number=} opt_padding Optional padding to apply to the cluster icon.
 * @constructor
 * @extends google.maps.OverlayView
 * @ignore
 */
function ClusterIcon(cluster, styles, opt_padding) {
  cluster.getMarkerClusterer().extend(ClusterIcon, google.maps.OverlayView);

  this.styles_ = styles;
  this.padding_ = opt_padding || 0;
  this.cluster_ = cluster;
  this.center_ = null;
  this.map_ = cluster.getMap();
  this.div_ = null;
  this.sums_ = null;
  this.visible_ = false;

  this.setMap(this.map_);
}


/**
 * Triggers the clusterclick event and zoom's if the option is set.
 */
ClusterIcon.prototype.triggerClusterClick = function() {
  var markerClusterer = this.cluster_.getMarkerClusterer();

  // Trigger the clusterclick event.
  google.maps.event.trigger(markerClusterer, 'clusterclick', this.cluster_);

  if (markerClusterer.isZoomOnClick()) {
    // Zoom into the cluster.
    this.map_.fitBounds(this.cluster_.getBounds());
  }
};


/**
 * Adding the cluster icon to the dom.
 * @ignore
 */
ClusterIcon.prototype.onAdd = function() {
  this.div_ = document.createElement('DIV');
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.innerHTML = this.sums_.text;
  }

  var panes = this.getPanes();
  panes.overlayMouseTarget.appendChild(this.div_);

  var that = this;
  google.maps.event.addDomListener(this.div_, 'click', function() {
    that.triggerClusterClick();
  });
};


/**
 * Returns the position to place the div dending on the latlng.
 *
 * @param {google.maps.LatLng} latlng The position in latlng.
 * @return {google.maps.Point} The position in pixels.
 * @private
 */
ClusterIcon.prototype.getPosFromLatLng_ = function(latlng) {
  var pos = this.getProjection().fromLatLngToDivPixel(latlng);
  pos.x -= parseInt(this.width_ / 2, 10);
  pos.y -= parseInt(this.height_ / 2, 10);
  return pos;
};


/**
 * Draw the icon.
 * @ignore
 */
ClusterIcon.prototype.draw = function() {
  if (this.visible_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.top = pos.y + 'px';
    this.div_.style.left = pos.x + 'px';
  }
};


/**
 * Hide the icon.
 */
ClusterIcon.prototype.hide = function() {
  if (this.div_) {
    this.div_.style.display = 'none';
  }
  this.visible_ = false;
};


/**
 * Position and show the icon.
 */
ClusterIcon.prototype.show = function() {
  if (this.div_) {
    var pos = this.getPosFromLatLng_(this.center_);
    this.div_.style.cssText = this.createCss(pos);
    this.div_.style.display = '';
  }
  this.visible_ = true;
};


/**
 * Remove the icon from the map
 */
ClusterIcon.prototype.remove = function() {
  this.setMap(null);
};


/**
 * Implementation of the onRemove interface.
 * @ignore
 */
ClusterIcon.prototype.onRemove = function() {
  if (this.div_ && this.div_.parentNode) {
    this.hide();
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
  }
};


/**
 * Set the sums of the icon.
 *
 * @param {Object} sums The sums containing:
 *   'text': (string) The text to display in the icon.
 *   'index': (number) The style index of the icon.
 */
ClusterIcon.prototype.setSums = function(sums) {
  this.sums_ = sums;
  this.text_ = sums.text;
  this.index_ = sums.index;
  if (this.div_) {
    this.div_.innerHTML = sums.text;
  }

  this.useStyle();
};


/**
 * Sets the icon to the the styles.
 */
ClusterIcon.prototype.useStyle = function() {
  var index = Math.max(0, this.sums_.index - 1);
  index = Math.min(this.styles_.length - 1, index);
  var style = this.styles_[index];
  this.url_ = style['url'];
  this.height_ = style['height'];
  this.width_ = style['width'];
  this.textColor_ = style['textColor'];
  this.anchor_ = style['anchor'];
  this.textSize_ = style['textSize'];
  this.backgroundPosition_ = style['backgroundPosition'];
};


/**
 * Sets the center of the icon.
 *
 * @param {google.maps.LatLng} center The latlng to set as the center.
 */
ClusterIcon.prototype.setCenter = function(center) {
  this.center_ = center;
};


/**
 * Create the css text based on the position of the icon.
 *
 * @param {google.maps.Point} pos The position.
 * @return {string} The css style text.
 */
ClusterIcon.prototype.createCss = function(pos) {
  var style = [];
  style.push('background-image:url(' + this.url_ + ');');
  var backgroundPosition = this.backgroundPosition_ ? this.backgroundPosition_ : '0 0';
  style.push('background-position:' + backgroundPosition + ';');

  if (typeof this.anchor_ === 'object') {
    if (typeof this.anchor_[0] === 'number' && this.anchor_[0] > 0 &&
        this.anchor_[0] < this.height_) {
      style.push('height:' + (this.height_ - this.anchor_[0]) +
          'px; padding-top:' + this.anchor_[0] + 'px;');
    } else {
      style.push('height:' + this.height_ + 'px; line-height:' + this.height_ +
          'px;');
    }
    if (typeof this.anchor_[1] === 'number' && this.anchor_[1] > 0 &&
        this.anchor_[1] < this.width_) {
      style.push('width:' + (this.width_ - this.anchor_[1]) +
          'px; padding-left:' + this.anchor_[1] + 'px;');
    } else {
      style.push('width:' + this.width_ + 'px; text-align:center;');
    }
  } else {
    style.push('height:' + this.height_ + 'px; line-height:' +
        this.height_ + 'px; width:' + this.width_ + 'px; text-align:center;');
  }

  var txtColor = this.textColor_ ? this.textColor_ : 'black';
  var txtSize = this.textSize_ ? this.textSize_ : 11;

  style.push('cursor:pointer; top:' + pos.y + 'px; left:' +
      pos.x + 'px; color:' + txtColor + '; position:absolute; font-size:' +
      txtSize + 'px; font-family:Arial,sans-serif; font-weight:bold');
  return style.join('');
};


// Export Symbols for Closure
// If you are not going to compile with closure then you can remove the
// code below.
window['MarkerClusterer'] = MarkerClusterer;
MarkerClusterer.prototype['addMarker'] = MarkerClusterer.prototype.addMarker;
MarkerClusterer.prototype['addMarkers'] = MarkerClusterer.prototype.addMarkers;
MarkerClusterer.prototype['clearMarkers'] =
    MarkerClusterer.prototype.clearMarkers;
MarkerClusterer.prototype['fitMapToMarkers'] =
    MarkerClusterer.prototype.fitMapToMarkers;
MarkerClusterer.prototype['getCalculator'] =
    MarkerClusterer.prototype.getCalculator;
MarkerClusterer.prototype['getGridSize'] =
    MarkerClusterer.prototype.getGridSize;
MarkerClusterer.prototype['getExtendedBounds'] =
    MarkerClusterer.prototype.getExtendedBounds;
MarkerClusterer.prototype['getMap'] = MarkerClusterer.prototype.getMap;
MarkerClusterer.prototype['getMarkers'] = MarkerClusterer.prototype.getMarkers;
MarkerClusterer.prototype['getMaxZoom'] = MarkerClusterer.prototype.getMaxZoom;
MarkerClusterer.prototype['getStyles'] = MarkerClusterer.prototype.getStyles;
MarkerClusterer.prototype['getTotalClusters'] =
    MarkerClusterer.prototype.getTotalClusters;
MarkerClusterer.prototype['getTotalMarkers'] =
    MarkerClusterer.prototype.getTotalMarkers;
MarkerClusterer.prototype['redraw'] = MarkerClusterer.prototype.redraw;
MarkerClusterer.prototype['removeMarker'] =
    MarkerClusterer.prototype.removeMarker;
MarkerClusterer.prototype['removeMarkers'] =
    MarkerClusterer.prototype.removeMarkers;
MarkerClusterer.prototype['resetViewport'] =
    MarkerClusterer.prototype.resetViewport;
MarkerClusterer.prototype['repaint'] =
    MarkerClusterer.prototype.repaint;
MarkerClusterer.prototype['setCalculator'] =
    MarkerClusterer.prototype.setCalculator;
MarkerClusterer.prototype['setGridSize'] =
    MarkerClusterer.prototype.setGridSize;
MarkerClusterer.prototype['setMaxZoom'] =
    MarkerClusterer.prototype.setMaxZoom;
MarkerClusterer.prototype['onAdd'] = MarkerClusterer.prototype.onAdd;
MarkerClusterer.prototype['draw'] = MarkerClusterer.prototype.draw;

Cluster.prototype['getCenter'] = Cluster.prototype.getCenter;
Cluster.prototype['getSize'] = Cluster.prototype.getSize;
Cluster.prototype['getMarkers'] = Cluster.prototype.getMarkers;

ClusterIcon.prototype['onAdd'] = ClusterIcon.prototype.onAdd;
ClusterIcon.prototype['draw'] = ClusterIcon.prototype.draw;
ClusterIcon.prototype['onRemove'] = ClusterIcon.prototype.onRemove;