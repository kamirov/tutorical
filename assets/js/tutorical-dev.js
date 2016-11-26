var fastEffectSpeed = 200;

$(function() 
{	
	activateToggleButtons();
	activateChangeShows();
	showReactionNotice();

	$('.focus-messages').each(function()
	{		
		var $message = $(this),
			$input = $message.siblings('input');

		$input.focus(function()
		{
			$message.stop(true,true).slideDown(fastEffectSpeed);
		}).blur(function()
		{
			$message.stop(true,true).slideUp(fastEffectSpeed);
		});
	});

	$('.password').setcheckCaps();

	$('.select-on-click-inputs').click(function()
	{
		$(this).select();
	});

	$('.check-none').click(function()
	{
		$(this).parents('.form-elements').find(':checkbox').prop('checked', '').change();
		return false;
	});

	$('.check-all').click(function()
	{
		$(this).parents('.form-elements').find(':checkbox').prop('checked', 'checked').change();
		return false;
	});

//	$('textarea').autosize();

	$(':input[name]').not('[type=button], [type=submit]').on('keydown', function()
	{
		$(this).showErrors('');
	})

	$('[type=checkbox], [type=radio]').on('change', function()
	{
		$(this).showErrors('');
	})

	$("[href=#top]").click(function() 
	{
		$("html, body").animate({ scrollTop: 0 }, 200);
		return false;
	});

	$(window).scroll(function()
	{
		 updatePersistentHeaders();
	});

	$('#search-location, .hourly-rates').placeholder();

	$('.no-submit').submit(function()
	{
		return false;
	});
 
	$('.no-enter-submit').keydown(function(e) 
	{
	    if ( e.keyCode == 13 ) {
			e.preventDefault();
	    	return false;
	    }
	});

	$('.on-enter-submit').keydown(function(e) 
	{
	    if ( e.keyCode == 13 ) {
			e.preventDefault();
	    	return false;
	    }
	});
	
	var $active_nav = $('#account-nav li.active');
	
	if ($active_nav.length)
	{
		var centerX = $active_nav.position().left + ($active_nav.outerWidth() / 2);
		$('#account-nav-indicator').css({
			'left': centerX - 8
		});		
	}

	$('.email').addMailcheck();
});

function setupOvernightTimeLinks(selector)
{
	$('.show-overnight-times-links', selector).click(function()
	{
		toggleOvernight($(this));
	});
}

function toggleOvernight($link)
{
	var $overnight = $link.parents('.availabilities').find('.overnight-rows-cont');
	
	if ($overnight.is(':visible'))
	{
		$overnight.slideUp(200);
		$link.text('Show overnight times');
	}
	else
	{
		// IE7 poops up with slideDown
		if (navigator.appVersion.indexOf("MSIE 7.") != -1)
		{
			$overnight.show();
		}
		else
		{
			$overnight.slideDown(200);			
		}
		$link.text('Hide overnight times');
	}
}


(function($) {
	$.fn.addMailcheck = function() {
		return this.each(function() {
			var suggestionSlideSpeed = 300,
				$email = $(this),
				$emailSuggestion = $email.siblings('.form-input-notes').find('.email-suggestion');

			$email.blur(function()  {

				$email.mailcheck( {
					suggested: function(element, suggestion)  {
						$emailSuggestion
							.find('.address-part')
								.text(suggestion.address).end()
							.find('.domain-part')
								.text(suggestion.domain).end()
						.slideDown(suggestionSlideSpeed);
					},
					empty: function(element) {
						hideEmailSuggestion();
					}
				});
			});

			$emailSuggestion.find('.suggested-values').click(function()
			{
				$email.val($(this).text()).hideErrors().focus();
				hideEmailSuggestion();
			}).keypress(function(e) 
			{
				$email.val($(this).text()).hideErrors().focus();
				hideEmailSuggestion();
			});

			function hideEmailSuggestion()
			{
				$emailSuggestion.slideUp(suggestionSlideSpeed);
			}			

		});
	}
}) (jQuery);

//toTitleCase
(function(){
	var small = "(a|an|and|as|at|but|by|en|for|if|in|of|on|or|the|to|v[.]?|via|vs[.]?)";
	var punct = "([!\"#$%&'()*+,./:;<=>?@[\\\\\\]^_`{|}~-]*)";
  
	this.toTitleCase = function(title){
		var parts = [], split = /[:.;?!] |(?: |^)["Ò]/g, index = 0;
		
		while (true) {
			var m = split.exec(title);

			parts.push( title.substring(index, m ? m.index : title.length)
				.replace(/\b([A-Za-z][a-z.'Õ]*)\b/g, function(all){
					return /[A-Za-z]\.[A-Za-z]/.test(all) ? all : upper(all);
				})
				.replace(RegExp("\\b" + small + "\\b", "ig"), lower)
				.replace(RegExp("^" + punct + small + "\\b", "ig"), function(all, punct, word){
					return punct + upper(word);
				})
				.replace(RegExp("\\b" + small + punct + "$", "ig"), upper));
			
			index = split.lastIndex;
			
			if ( m ) parts.push( m[0] );
			else break;
		}
		
		return parts.join("").replace(/ V(s?)\. /ig, " v$1. ")
			.replace(/(['Õ])S\b/ig, "$1s")
			.replace(/\b(AT&T|Q&A)\b/ig, function(all){
				return all.toUpperCase();
			});
	};
    
	function lower(word) {
		return word.toLowerCase();
	}
    
	function upper(word) {
	  return word.substr(0,1).toUpperCase() + word.substr(1);
	}
})();


(function($) {
	$.fn.saveFormField = function () {
		return this.each(function() {
/*			var $this = $(this);

			$.ajax({
				type: "POST",
				url: baseUrl("signup/save_form_field"),
				data: {
					name : $this.attr('name'),
					value : $this.val()
				}
			}).done(function(response) {
				// // console.log(response);
			});
*/		});
	}

	$.fn.addEmailValidation = function (isRegister) {
		isRegister = isRegister || 0;

		return this.each(function() {
			var $this = $(this);

			$this.after('<input type="hidden" class="form-validity-indicators" data-corresponding-field="' + $this.attr('id') + '" value="false">');

			$this.blur(function() {
				$this.validateEmail(isRegister);
			}).focus(function() {
				$this.hideErrors();
			});

		});
	}

	$.fn.validateEmail = function(isRegister) {

		return this.each(function() {
			var $this = $(this)
				formType = (isRegister ? 'registerForm' : 'loginForm');

			window.awaitingResponse[formType] = true;
			// // console.log('in validateEmail', $this.attr('id'), $this.attr('value'));

			$.ajax({
				type: "POST",
				url: baseUrl("auth/validate_email"),
				data: {
					email : $this.attr('value'),
					is_register: isRegister
				}
			}).done(function(error) {

				window.awaitingResponse[formType] = false;

				$this.showErrors(error);

				updateValidity($this.attr('id'), error);

			});
		});
	}

	$.fn.addValidation = function(options) {

		var settings = $.extend({
			type: 'regular',
			event: 'blur',
			showGood: false,
			data: {},
			ajax: false
//			message: 'Sorry! There\'s an error with this field'
		}, options)

		var enclosers = {
					start: '<div class="error-messages">',
					end: '</div>'
				};

		return this.each(function() {
			var $this = $(this);	

			$this.after('<input type="hidden" class="form-validity-indicators" data-corresponding-field="' + $this.attr('id') + '" value="false">');

			$this.on(settings.event, function() {
				var val = $this.val(),
					error = '';

				switch (settings.type) {
					case 'password':
						if (val.length == 0)
						{
							error += enclosers.start + 'But...but where\'s the password?' + enclosers.end;
						}
						break;
					case 'auth_password':
						var fadeSpeed = 400;
						if (val.length == 0)
						{
							error += enclosers.start + 'But...but where\'s the password?' + enclosers.end;

							$this.showErrors(error);	
							updateValidity($this.attr('id'), error);
						}
						else if (settings.data.userId)
						{
							if (settings.data.loader)
							{
								var $loader = $(settings.data.loader.selector).fadeIn(fadeSpeed).css('display', (settings.data.loader.display ? settings.data.loader.display : 'block'));
							}
							$.ajax({
								type: "POST",
								url: baseUrl("account/confirm_password"),
								data: {
									password : val
								}
							})
							.done(function(error) {
//							// // console.log(error);
								$this.showErrors(error);	
								updateValidity($this.attr('id'), error);
							}).always(function() {
								if (settings.data.loader)
								{
									$loader.hide();
//											$loader.fadeOut((settings.data.loader.fadeOutSpeed ? settings.data.loader.fadeOutSpeed : fadeSpeed));
								}
							});
						}
						else if (settings.data.emailSelector)
						{
//							// // console.log(settings.data.emailSelector.val());
							if (settings.data.loader)
							{
								var $loader = $(settings.data.loader.selector).fadeIn(fadeSpeed).css('display', (settings.data.loader.display ? settings.data.loader.display : 'block'));
							}
							$.ajax({
								type: "POST",
								url: baseUrl("auth/check_password"),
								data: {
									password : val,
									email : settings.data.emailSelector.val()
								}
							})
							.done(function(error) {	
//								// // console.log(error);
								$this.showErrors(error);	
								updateValidity($this.attr('id'), error);
							}).always(function() {
								if (settings.data.loader)
								{
//									$loader.hide();
									$loader.fadeOut(fadeSpeed);
								}
							});
						}
						break;
					case 'location':
						if (val.length == 0)
						{
							error += enclosers.start + 'Please don\'t leave me blank.' + enclosers.end;
						}
						else if (!($('#lat').val() && $('#lon').val() && $('viewport')))
						{
							error += enclosers.start + 'Sorry, there was a problem getting info from Google.<br>Please check for a typo or try different location.' + enclosers.end;
						}
						else
						{
							if (!($('#country').val() || $('#city').val()))
							{
								error += enclosers.start + 'Sorry, your location must include a city/country.' + enclosers.end;
							}

							else if (!$('#country').val())
							{
								error += enclosers.start + 'Sorry, your location must include a country.' + enclosers.end;
							}
							else if (!$('#city').val())
							{
								error += enclosers.start + 'Sorry, your location must include a city.' + enclosers.end;
							}									
						}
						break;
					case 'email':
						if (val.length == 0)
						{
							error += enclosers.start + 'Please don\'t leave me blank.' + enclosers.end;
						}
						else if (!isValidEmail(val))
						{
							error += enclosers.start + 'Sorry, that\'s not a valid email.' + enclosers.end;									
						}
						break;
					default:
						if (val.length == 0)
						{
							error += enclosers.start + 'Please don\'t leave me blank.' + enclosers.end;
						}
				}
				if (!settings.ajax)
				{
					$this.showErrors(error);	
					updateValidity($this.attr('id'), error);
				}
			});

			$this.keydown(function() {
				$this.showErrors('');
			});

			$this.focus(function() {
				$this.hideErrors();
			});


		});
	}

	$.fn.validate = function(errors) 
	{
		return this.each(function() 
		{
			var $form = $(this),
				focused = false;
			
			$form.find('[name]').not('.input-clones').each(function()
			{
				var $this = $(this),
					name = $this.attr('name').replace('[]', ''),
					error = '';
			
				if (errors && errors.hasOwnProperty(name))
				{
					error = errors[name];

					if (!focused)
					{
						// We enable it again, in case the input has been disabled. Solves problems with login, contact, and register forms not focusing after error
						$this.prop('disabled', false).focus();
						focused = true;
					}
				}
				$this.showErrors(error);
			});
		});
	}

	$.fn.showErrors = function(error, additionalClasses) 
	{
		var additionalClasses = (typeof additionalClasses === 'undefined') ? '' : additionalClasses;

		return this.each(function() 
		{
			var $this = $(this),
				errorEffectSpeed = 200,
				$input = $this.parents('.form-inputs'),
				$form = $this.parents('form'),
				name = $this.attr('name').replace('[]', ''),	// get name, but remove []
				$error = $form.find('.error-messages[data-input-name='+name+']');

			// The first $error is for when multiple .error-messages divs exist side by side (we distinguish them by the data-input-name attr). If none exist, then we default to the regular error div
			if ($error.length === 0)
				$error = $input.find('.error-messages'); 

			if (error) 
			{
//				$this.addClass('form-errors', errorEffectSpeed/2).removeClass('form-good', errorEffectSpeed/2);
				error = '<span class="'+additionalClasses+'">'+error+'</span>';
				
				$error.html(error).stop(true,true).slideDown(errorEffectSpeed);
			}
			else 
			{
//				$this.removeClass('form-errors', errorEffectSpeed/2);
				$error.empty().stop(true,true).slideUp(errorEffectSpeed);
			}
		});
	}

	$.fn.hideErrors = function () {
		return this.each(function() {
			var $this = $(this),
				errorEffectSpeed = 200,
				id = $this.attr('id'),
				$error = $this.parents('.form-inputs').find('.error-messages');

			$error.hide();
			$this.removeClass('form-errors', errorEffectSpeed/2).removeClass('form-good', errorEffectSpeed/2);	
		});
	}

	$.fn.checkAutofill = function () {
		return this.each(function() {
			var $form = $(this);	
			// God damn it autofill. Useful, but AFAIK it can't be detected, so we have to do a check on form submit. Since validation occurs on blur, just blur through each element to set validation flag vars. The data-autofill-checked value is because when we blur through an ajax-validated field, the form's awaitingResponse var is set to true, which sets a form submit to occur after x seconds. But on that form submit, it blurs through the fields and does the ajax validation again --> infinite loop. This does, however, cause a graphical glitch where non-ajax-validated field errors show up before ajax-validated ones (try submitting with no data and no errors present); don't know if people actually care about that though
//			// // console.log('in checkAutofill');
			if (!$form.attr('data-autofill-checked'))
			{
//			// // console.log('in if');
				$form.find('input, textarea').each(function() {
					$(this).blur();
//					// // console.log('in each');

				});
				$form.attr('data-autofill-checked', 'true');
			}
	
		});
	}

	$.fn.isValid = function () {
		var $form = this,
			isValid = true;

		// Reverse order so that last focused input is first in form
		$($('.form-validity-indicators', $form).get().reverse()).each(function() {
			var $this = $(this),
				id = $this.attr('data-corresponding-field');

			if ($this.val() == 'false')
			{
				if (globalAutofocus)
					$('#'+id).focus();

				$('#'+id).siblings('.current-errors').show();
				isValid = false;
			}
//			// // console.log('external = ',$this.attr('data-corresponding-field'));
		});
		return isValid;
	}

	$.fn.textWidth = function(){
	    var self = $(this),
	        children = self.children(),
	        calculator = $('<span style="display: inline-block;" />'),
	        width;

	    children.wrap(calculator);
	    width = children.parent().width(); // parent = the calculator wrapper
	    children.unwrap();
	    return width;
	};

	$.fn.setcheckCaps = function()
	{
		return this.each(function()
		{
			var $this = $(this),
			    effectSpeed = 200,
			    capsText = '<b>Caps Lock is on</b>';

			$this.parents('form').submit(function()
			{
				$(this).find('.info-messages').empty().hide();
			});

			$this.keypress(function(e) 
			{
			    var s = String.fromCharCode(e.which)

			    if (s.toUpperCase() === s 
			    	&& s.toLowerCase() !== s 
			    	&& !e.shiftKey) 
			    {
			    	$this.attr('data-caps', 'on');
			    	$this.siblings('.info-messages').add('.form-input-notes-conts .info-messages').html(capsText).stop(true,true).slideDown(effectSpeed);
			    }
			    else
			    {
			    	$this.attr('data-caps', 'off');
			    	$this.siblings('.info-messages').add('.form-input-notes-conts .info-messages').empty().hide();
			    }
			}).keydown(function(e)
			{
				/*
				if (e.keyCode == 20)
				{
					if ($this.attr('data-caps') == 'on')
					{
						$this.attr('data-caps', 'off');
						$this.siblings('.info-messages').add('.form-input-notes-conts .info-messages').empty().hide();					
					}
					// Need to else if here rather than else because data-caps only equals 'off' if we capsed and decapsed before
					else if ($this.attr('data-caps') == 'off')
					{
						$this.attr('data-caps', 'on');
						$this.siblings('.info-messages').add('.form-input-notes-conts .info-messages').html(capsText).stop(true,true).slideDown(effectSpeed);				
					}
				}
				*/
			});
		});
	}

}) (jQuery);


function updateValidity(id, error)
{
//	// // console.log(id, error);
	if (error)
		$('[data-corresponding-field='+id+']').val(false);
	else
		$('[data-corresponding-field='+id+']').val(true);
}

function nl2br(str, is_xhtml) 
{   
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
	return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function stripBrs(str) 
{
    return str.replace(/<br\s*\/?>/mg,'');
}


function br2nl(str) 
{
    return str.replace(/<br\s*\/?>/mg,"\n");
}

function isValidEmail(email)
{
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(email);
};

function scrollToTop() 
{
	$("html, body").animate({ scrollTop: 0 }, 400);
}

function ajaxFailNoty(selector)
{
	var text = 'Sorry, something happened on our end. Please try again.<hr>If the problem persists, please contact us.',
		type = 'warning',
		timeout = 4000;

	$.noty.closeAll();

	if (typeof selector == 'undefined')
	{
		noty(
		{
			text: text,
			type: type,
			timeout: timeout
		});
	}
	else
	{
		$(selector).noty(
		{
			text: text,
			type: type,
			timeout: timeout
		});
	}
}

function toMoney(number) 
{
    var number = number.toString() || '0';
   number = number.replace('$', '');
    
    var dollars = number.split('.')[0], 
    	cents = (number.split('.')[1] || '') +'00';
    
    dollars = dollars.split('').reverse().join('')
 //       .replace(/(\d{3}(?!$))/g, '$1,')
        .replace(/(\d{3}(?!$))/g, '$1')
        .split('').reverse().join('');
    return dollars + '.' + cents.slice(0, 2);
}

if (!String.prototype.trim) {
	String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g, '');};
	String.prototype.ltrim=function(){return this.replace(/^\s+/,'');};
	String.prototype.rtrim=function(){return this.replace(/\s+$/,'');};
	String.prototype.fulltrim=function(){return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');};
}

$.fn.exists = function () {
    return this.length !== 0;
}

function strcasecmp(f_string1, f_string2) 
{
	var string1 = (f_string1 + '').toLowerCase();
	var string2 = (f_string2 + '').toLowerCase();

 	if (string1 > string2) 
	    return 1;
    else if (string1 == string2) 
	    return 0;
	return -1;
}

function updatePersistentHeaders()
{
	$('.persistent-headers').each(function()
	{
		var $el = $(this),
			elAppearOffset = $el.attr('data-appear-offset'),
			scrollTop = $(window).scrollTop(),
			scrollSpeed = 200;

		if (scrollTop >= elAppearOffset)
		{
			$el.stop(true, true).fadeIn(scrollSpeed);
		}
		else
		{
			$el.stop(true, true).fadeOut(scrollSpeed);
		}
	});
}

function parseLocation(components)
{
	// // console.log(components);
	var length = components.length,
		loc = [];

	for (var i = 0; i < length; i++)
	{
		var type = components[i].types[0];

		// Check if has city
		if (type == 'colloquial_area' || type == 'locality' || type == 'administrative_area_level_3')
		{
			loc['city'] = components[i].long_name;
		}

		// Check if has country
		if (type == 'country')
		{
			loc['country'] = components[i].long_name;
		}

		// Check if has any more specific details
		if (type == 'establishment' 
			|| type == 'postal_code'
			|| type == 'postal_code_prefix' 
			|| type == 'route'
			|| type == 'neighborhood')
		{
			loc['specific'] = components[i].long_name;
		}
	}
	if (!loc['city'])
		loc['city'] = '';
	if (!loc['country'])
		loc['country'] = '';
	if (!loc['specific'])
		loc['specific'] = '';

	// // console.log(loc);

	return loc;
}

function showReactionNotice()
{
	if (window.reactionNotice)
	{
		noty(reactionNotice);
	}
}

function scrollAndFocus($el, focus)
{
	var additionalHeight = parseInt($el.attr('data-additional-height'));

	if (isNaN(additionalHeight))
		additionalHeight = 0;

	var topLim = $(window).scrollTop(),
		vpHeight = $(window).height(),
		botLim = topLim + vpHeight,
		elHeight = $el.outerHeight() + additionalHeight,
		elTop = $el.offset().top,
		elBot = elTop + elHeight,
		topOffset,
		
		leftLim = $(window).scrollLeft(),
		vpWidth = $(window).width(),
		rightLim = leftLim + vpWidth,
		elWidth = $el.outerWidth(),
		elLeft = $el.offset().left,
		elRight = elLeft + elWidth,
		leftOffset,

		scrollSpeed = 300;

	if (elHeight >= vpHeight)		// If el is higher than viewport, than just scroll to its top
	{
		topOffset = elTop;
	}
	else
	{
		if (elTop >= topLim && elTop < botLim)		// If top of el is visible, check if bot is visible; if it is, then all is well; if it's not, then scroll till bot visible
		{
			if (elBot > botLim)	// elBot can't be above the topLim, so no need to check
			{
				topOffset = topLim + (elBot - botLim);
			} 
		}
		else if (elBot >= botLim)
		{
			topOffset = topLim + (elBot - botLim);
		}
		else
		{
			topOffset = elTop;
		}
	}

	if (elWidth >= vpWidth)
	{
		leftOffset = elLeft;
	}
	else
	{
		if (elLeft >= leftLim && elLeft < rightLim)		// If top of el is visible, check if bot is visible; if it is, then all is well; if it's not, then scroll till bot visible
		{
			if (elRight > rightLim)	// elRight can't be above the leftLim, so no need to check
			{
				leftOffset = leftLim + (elRight - rightLim);
			} 
		}
		else if (elRight >= rightLim)
		{
			leftOffset = leftLim + (elRight - rightLim);
		}
		else
		{
			leftOffset = elLeft;
		}
	}

	scrollPage($el, topOffset, leftOffset, scrollSpeed, focus);
}

function scrollPage($el, topOffset, leftOffset, speed, focus)
{
	$('html, body').animate(
	{
    	scrollTop: topOffset,
    	scrollLeft: leftOffset
 	}, speed, function()
 	{
 		if (focus == true)
 		{
			$el.find('input, textarea, select').not('[type=hidden], [type=button], [type=submit]').first().focus();
 		}
 	});
}

function activateToggleButtons()
{
	$('.toggle-buttons').click(function()
	{
		var $this = $(this),
			$connectedDiv = $($this.attr('data-toggle-container')),
			toggleText = $this.attr('data-toggle-text'),
			animationSpeed = 200;

		if ($connectedDiv.is(':visible'))
		{
			$this.removeClass('active');
		}
		else
		{
			$this.addClass('active');
		}

		$connectedDiv.fadeToggle(animationSpeed, function()
		{
			scrollAndFocus($(this));
		});

		if (toggleText)
		{
			$this.attr('data-toggle-text', $this.text());
			$this.text($toggleText);			
		}
	});
}

function activateChangeShows()
{
	$('[data-change-selector]').change(function()
	{
		var $this = $(this),
			$change = $($this.attr('data-change-selector')),
			animationSpeed = 200;

		if (this.checked)
		{
			$change.stop(true, true).slideDown(animationSpeed);
		}
		else
		{
			$change.stop(true, true).slideUp(animationSpeed);
		}
	})
}

function stripTags(str)
{
	return str.replace(/(<([^>]+)>)/ig,"");
}

function activateTempReveals()
{
	$('[data-modal-state=temp]').click(function()
	{	
		var $this = $(this),
			$reveal = $('#'+$this.attr('data-reveal-id')),
			redirectUrl = $this.attr('data-redirect');
		
		$reveal.foundation('reveal', 'open')
				   .find('form').attr('action', redirectUrl);	// Have to do this here for reveals that aren't made on page load

		$.noty.closeAll();
		
		return false;
	})
}

$.fn.autosize_select = function(padding)
{
	var select_arrow_width = 30, // this is standard
		select_width_cont_id = 'ae-select-width-cont',
		$select_width_cont = $('#'+select_width_cont_id);

	if (typeof padding === 'undefined')
		padding = 30;	// default

	// If we don't already have the autocomplete width element, make it
	if (!$select_width_cont.length)
	{
		$select_width_cont = $('<span id="ae-select-width-cont">').appendTo('body');
	}

	return this.each(function()
	{
		var $select = $(this);

		autosize(); // Autosize it when it appears
		$select.change(autosize);	// Then autosize on each change

		function autosize()
		{
			var option_text = $select.find('option:selected').text(),
				new_width;

			$select_width_cont.text(option_text);
			new_width = $select_width_cont.width() + padding;

			$select.width(new_width); 
		}
	});
}