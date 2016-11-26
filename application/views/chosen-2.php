<html>
<head>

<style>
.dropdown {
	position: absolute;
	z-index: 9999999;
	display: none;
}

.dropdown .dropdown-menu,
.dropdown .dropdown-panel {
	min-width: 160px;
	max-width: 360px;
	list-style: none;
	background: #FFF;
	border: solid 1px #DDD;
	border: solid 1px rgba(0, 0, 0, .2);
	border-radius: 6px;
	box-shadow: 0 5px 10px rgba(0, 0, 0, .2);
	overflow: visible;
	padding: 4px 0;
	margin: 0;
}

.dropdown .dropdown-panel {
	padding: 10px;
}

.dropdown.dropdown-tip {
	margin-top: 8px;
}

.dropdown.dropdown-tip:before {
  position: absolute;
  top: -6px;
  left: 9px;
  content: '';
  border-left: 7px solid transparent;
  border-right: 7px solid transparent;
  border-bottom: 7px solid #CCC;
  border-bottom-color: rgba(0, 0, 0, 0.2);
  display: inline-block;
}

.dropdown.dropdown-tip.dropdown-anchor-right:before {
	left: auto;
	right: 9px;
}

.dropdown.dropdown-tip:after {
  position: absolute;
  top: -5px;
  left: 10px;
  content: '';
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-bottom: 6px solid #FFF;
  display: inline-block;
}

.dropdown.dropdown-tip.dropdown-anchor-right:after {
	left: auto;
	right: 10px;
}


.dropdown.dropdown-scroll .dropdown-menu,
.dropdown.dropdown-scroll .dropdown-panel {
	max-height: 358px;
	overflow: auto;
}

.dropdown .dropdown-menu LI {
	list-style: none;
	padding: 0 0;
	margin: 0;
	line-height: 18px;
}

.dropdown .dropdown-menu LI > A,
.dropdown .dropdown-menu LABEL {
	display: block;
	color: #555;
	text-decoration: none;
	line-height: 18px;
	padding: 3px 15px;
	white-space: nowrap;
}

.dropdown .dropdown-menu LI > A:hover,
.dropdown .dropdown-menu LABEL:hover {
	background-color: #08C;
	color: #FFF;
	cursor: pointer;
}

.dropdown .dropdown-menu .dropdown-divider {
	font-size: 1px;
	border-top: solid 1px #E5E5E5;
	padding: 0;
	margin: 5px 0;
}

/* Icon Examples - icons courtesy of http://p.yusukekamiyamane.com/ */
.dropdown.has-icons LI > A {
	padding-left: 30px;
	background-position: 8px center;
	background-repeat: no-repeat;
}

.dropdown .undo A { background-image: url(icons/arrow-curve-180-left.png); }
.dropdown .redo A { background-image: url(icons/arrow-curve.png); }
.dropdown .cut A { background-image: url(icons/scissors.png); }
.dropdown .copy A { background-image: url(icons/document-copy.png); }
.dropdown .paste A { background-image: url(icons/clipboard.png); }
.dropdown .delete A { background-image: url(icons/cross-script.png); }
</style>

</head>
<body>
<a href="#" data-dropdown="#dropdown-1">dropdown</a>

<div id="dropdown-1" class="dropdown dropdown-tip">
    <ul class="dropdown-menu">
        <li><a href="#1">Item 1</a></li>
        <li><a href="#2">Item 2</a></li>
        <li><a href="#3">Item 3</a></li>
        <li class="dropdown-divider"></li>
        <li><a href="#4">Item 4</a></li>
        <li><a href="#5">Item 5</a></li>
        <li><a href="#5">Item 6</a></li>
    </ul>
</div>
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<script>
/*
 * jQuery dropdown: A simple dropdown plugin
 *
 * Copyright 2013 Cory LaViska for A Beautiful Site, LLC. (http://abeautifulsite.net/)
 *
 * Licensed under the MIT license: http://opensource.org/licenses/MIT
 *
*/
if (jQuery) (function ($) {

    $.extend($.fn, {
        dropdown: function (method, data) {

            switch (method) {
                case 'show':
                    show(null, $(this));
                    return $(this);
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

    function show(event, object) {

        var trigger = event ? $(this) : object,
			dropdown = $(trigger.attr('data-dropdown')),
			isOpen = trigger.hasClass('dropdown-open');

        // In some cases we don't want to show it
        if (event) {
            if ($(event.target).hasClass('dropdown-ignore')) return;

            event.preventDefault();
            event.stopPropagation();
        } else {
            if (trigger !== object.target && $(object.target).hasClass('dropdown-ignore')) return;
        }
        hide();

        if (isOpen || trigger.hasClass('dropdown-disabled')) return;

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
        if (targetGroup && targetGroup.is('.dropdown')) {
            // Is it a dropdown menu?
            if (targetGroup.is('.dropdown-menu')) {
                // Did we click on an option? If so close it.
                if (!targetGroup.is('A')) return;
            } else {
                // Nope, it's a panel. Leave it open.
                return;
            }
        }

        // Hide any dropdown that may be showing
        $(document).find('.dropdown:visible').each(function () {
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

        if (dropdown.length === 0 || !trigger) return;

        // Position the dropdown relative-to-parent...
        if (dropdown.hasClass('dropdown-relative')) {
            dropdown.css({
                left: dropdown.hasClass('dropdown-anchor-right') ?
					trigger.position().left - (dropdown.outerWidth(true) - trigger.outerWidth(true)) - parseInt(trigger.css('margin-right'), 10) + hOffset :
					trigger.position().left + parseInt(trigger.css('margin-left'), 10) + hOffset,
                top: trigger.position().top + trigger.outerHeight(true) - parseInt(trigger.css('margin-top'), 10) + vOffset
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
</script>
</html>