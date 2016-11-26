<!DOCTYPE html>  
<html lang="en" <? if ($page == 'tutors') echo 'itemscope itemtype="http://schema.org/LocalBusiness"' ?> class="<?= $type_classes ?>">  
<head>  
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<? if (isset($meta['canonical'])): ?>
<link rel="canonical" href="<?= $meta['canonical'] ?>">
<? endif; ?>


<? if ($page == 'tutors'): 
    $snippet = ($tutor['snippet'] ?: 'A tutor on Tutorical');
?>

<!--
  <meta itemprop="name" content=<?= json_encode($tutor['display_name']) ?>>
  <meta itemprop="description" content=<?= json_encode($tutor['snippet']) ?>>
  <meta itemprop="image" content="<?= $tutor['avatar_url'] ?>">
-->

  <meta property="og:url" content="<?= current_url() ?>" /> 
  <meta property="og:title" content=<?= json_encode($tutor['display_name'].' - Tutor in '.$tutor['city'].', '.$tutor['country'].' | Tutorical') ?> /> 


  <meta property="og:description" content=<?= json_encode($snippet) ?> />  
  <meta property="og:image" content="<?= $tutor['avatar_url'] ?>" /> 
  <meta property='og:site_name' content='Tutorical'/>

<? endif; ?>

<!--[if lte IE 6]>
<meta http-equiv="refresh" content="0;url=<?= base_url('old') ?>" />
<![endif]-->

<!--[if lte IE 9]>
  <script src="<?= base_url('assets/js/selectivizr-min.js') ?>"></script>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie9-and-down.css') ?>" />
<![endif]-->

<!--[if lte IE 8]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>  
  <link rel="stylesheet" href="<?= base_url('assets/css/ie8-and-down.css') ?>" />
<![endif]-->

<!--[if lte IE 7]>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie7-and-down.css') ?>" />
  <script>
    $(function()
    {
      $('#global-overlay').remove();
    });
  </script>
<![endif]-->

<link rel="icon" type="image/png" href="<?= base_url('favicon.png').'?'.date('l jS \of F Y h:i:s A') ?>">
<link rel="stylesheet" href="<?= base_url('assets/css/tutorical-base.css') ?>">

<? if (INTERNET_CONNECTION): ?>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>
  <script>
    geocoder = new google.maps.Geocoder();
  </script>
<? else: ?>
  <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
  <script src="<?= base_url('assets/js/jquery-ui.js') ?>"></script>
<? endif; ?>

<script src="<?= base_url('assets/js/scripts-'.ENV.'.js') ?>"></script>
<script src="<?= base_url('assets/js/foundation-'.ENV.'.js') ?>"></script>

<script>
// These must be called in header, not footer, for various reasons

$(function() 
{
  var $as = $('<div class="as" style="display: none;"><p>You shouldn\'t be seeing this! If you are, then your CSS is turned off! In any case, please leave these values as they are. They\'re just here to check that you\'re a real person :)</p><input type="text" class="as-inputs" name="as_e"><input type="text" class="as-inputs" name="as_f" value="<?= ANTI_SPAM_FILLED_TEXT ?>"><input type="hidden" class="as-inputs" name="as_h"></div>');

  $('.ased').append($as);

  // If these get unruly, then user $.extend

  $.fn.qtip.defaults.position.my = 'left center';
  $.fn.qtip.defaults.position.at = 'right center';
  $.fn.qtip.defaults.position.container = $('#main-content');
  $.fn.qtip.defaults.show.effect = function() { $(this).fadeIn(250) };
  $.fn.qtip.defaults.hide.effect = function() { $(this).fadeOut(250) };
  $.fn.qtip.defaults.style.classes = 'qtip-tipsy qtip-light';

  // qTip called here because subsequent qTip calls overwrite this
  $('.qtipped').qtip(
  {
    
    //prerender: true,
    /*
    show: 
    {
      ready: true
    },
    */
    position: 
    {
      adjust: 
      {
        x: 5
      }
    }
  });
  // qTip called here because subsequent qTip calls overwrite this
  $('.downwards-qtipped').qtip(
  {
    position: 
    {
      my: 'left top',
      at: 'right center',
      adjust: 
      {
        x: 5
      }
    }
  });

  // qTip called here because subsequent qTip calls overwrite this
  $('.up-qtipped').qtip(
  {
    position: 
    {
      my: 'bottom center',
      at: 'top center',
    }
  });

  $('.left-qtipped').qtip(
  {
    position: 
    {
      my: 'right center',
      at: 'left center',
      adjust: 
      {
        x: -5
      }
    }
  });                   
});

function baseUrl(segments)
{
  return "<?= base_url() ?>"+segments;
}

function viewportWidth()
{
    var e = window, a = 'inner';
    if (!('innerWidth' in window )) {
        a = 'client';
        e = document.documentElement || document.body;
    }
//    return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
  return e[a+'Width'];
}

// These are global and CI variables needed for external scripts

awaitingResponse = {
  registerForm: false,
  loginForm: false,
  general: false
};

<? if ($loc = $this->session->userdata('search-location')): ?>
loc = <?= json_encode($loc) ?>
<? endif; ?>

vpWidth = viewportWidth();
vpHeight = $(window).height();

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) 
{
  handheld = true;
}
else
{
  handheld = false;
}

handheld = true; // temp

allSubjects = <?= json_encode($all_subjects) ?>;

$(window).resize(resizeSite);

function resizeSite()
{
  vpWidth = viewportWidth();
  vpHeight = $(window).height();

  if (window.vpWidth > <?= SCREEN_SMALL ?>)
  {
    $('.chzn-done').trigger("liszt:updated").hide();
    $('.chzn-container').show();
  }
  else
  {
    $('.chzn-done').show();
    $('.chzn-container').hide();
  }

}

<? 
if (isset($reaction_notice))
{
  echo 'reactionNotice = '.json_encode($reaction_notice).';';
}
else
{
  echo 'reactionNotice = null;';
}
?>

currentUserEmail = "<?= $this->session->userdata('email') ?>";

</script>

<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/"> 
<link rel="schema.DCTERMS" href="http://purl.org/dc/terms/"> 
<meta name="DC.identifier" scheme="DCTERMS.URI" content="<?= current_url() ?>"> 
<meta name="DC.creator" content="Andrei Khramtsov"> 
<meta name="DC.publisher" content="Aeterna"> 
<meta name="DC.date" scheme="DCTERMS.W3CDTF" content="2013-01-09"> 
<meta name="DC.type" scheme="DCTERMS.DCMIType" content="Text"> 
<meta name="DC.format" scheme="DCTERMS.IMT" content="text/html">
<meta name="DC.language" scheme="DCTERMS.RFC1766" content="EN"> 
<meta name="DC.rights" content="Copyright <?= date('Y') ?> Aeterna">

<? if (isset($meta['title'])): ?>
  <title><?= $meta['title'] ?></title>
  <meta name="DC.title" lang="en" content="<?= $meta['title'] ?>"> 
<? else: ?>
  <title>Tutorical</title>
  <meta name="DC.title" lang="en" content="Tutorical"> 
<? endif; ?>

<? if (isset($meta['description'])): ?>
  <meta name="description" content="<?= $meta['description'] ?>">
  <meta name="DC.description" lang="en" content="<?= $meta['description'] ?>">
<? endif; ?>

<? if (isset($meta['keywords'])): ?>
<meta name="keywords" content="<?= $meta['keywords'] ?>">
<meta name="DC.subject" lang="en" content="<?= $meta['keywords'] ?>"> 
<? endif; ?>

</head>

<body>

<? if (INTERNET_CONNECTION): ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&status=0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<? endif; ?>

<div class="wrapper">

<div id="global-overlay"></div>

<!--[if lte IE 8]>
<div class="old-browser-messages" style="text-align: center; padding: 10px 0; margin: 0 auto;">
  <h1>Hey! Looks like you're using an older Internet Explorer.</h1>
  <p style="margin-bottom: 0px; padding-bottom: 0;">Tutorical makes use of some new features that will look funky / not work on your browser. For a faster, prettier, and more secure internet experience, please<br> <a rel="nofollow" href="http://windows.microsoft.com/en-us/internet-explorer/products/ie/home"><b>Upgrade to the newest Internet Explorer</b></a> or <a rel="nofollow" href="http://www.telegraph.co.uk/technology/3794213/Web-browsers-five-alternatives-to-Internet-Explorer.html"><b>see some alternative browsers</b></a>.</p>
</div>
<![endif]-->

<noscript>
  <div class="noscript-messages">
    <h1>Your JavaScript is disabled! Some things will look funny and others won't work!</h1>
    <p>Many features, like search and profile maps, require JavaScript to work. It looks like it's been disabled on your computer. To use Tutorical site features, please enable it. If you're not sure how to do this, get in touch with us at <?= mailto(SITE_EMAIL) ?> and we'll be glad to help.</p>
  </div>
</noscript>

<header id="reg-header" class="site-headers">

  <div class="containers" id="long-noty-container"></div>

  <div class="containers cf" id="header-content">
    
    <div id="above-search" class="cf">
      <h1 id="actual-site-title">Tutorical</h1>
        <a href="<?= base_url() ?>" class="<? if ($logged_in) { echo 'logged-in'; } ?>" tabindex="50" id="site-title" title="Go to the Tutorical home page"></a>
    </div>

    <? if ($logged_in): ?>
    <div class="logged-status-cont" id="logged-in-cont" data-dropdown="#dropdown-account">
        <span id="account-link" class="truncate"><?= $display_name ?></span>
        <div class="logged-conts">
          <div id="header-avatar">
            <? if ($notices_count > 0): ?>
              <div class="notices-cont">
                <span class="notices-count" title="You have <?= $notices_count.' new '.($notices_count == 1 ? 'notice' : 'notices') ?>. See your Dashboard for more info."><?= $notices_count ?></span>
                <div class="notices-background"></div>
              </div>
            <? endif; ?>
            <div class="vertically-aligning-ghost"></div><img src="<?= $this->session->userdata('avatar_url') ?>">
          </div>
        </div>
        <div class="logged-conts">
          <div class="arrows" id="logged-in-dropdown-arrow"></div>
        </div>
    </div>
    <div id="dropdown-account" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">
      <ul class="dropdown-menu">
        <?
          $requests_title = 'See the status of your tutor requests';

          if ($role == ROLE_STUDENT)
          {
            $requests_title .= ' and applications';
          }

          if ($notices_count > 0)
            $dashboard_title = $notices_count.' new account '.($notices_count == 1 ? 'notice' : 'notices');
          else
            $dashboard_title = 'See important account notices';            

//          if ($has_students)
            $students = anchor('account/students', 'Students', 'tabindex="133" title="View all your current and past students\' info, their review of you, and their initial message"');
/*  
          else
            $students = '<a href="javascript:void(0);" class="no-click" title="Available when a student contacts you through your profile or accepts you for a tutor request." tabindex="133">Students</a>';
*/

//          if ($has_tutors)
            $tutors = anchor('account/tutors', 'Tutors', 'tabindex="133" title="View your active, past, and favourited tutors"');
/*          else
            $tutors = '<a href="javascript:void(0);" class="no-click" title="Available when you contact a tutor through their profile or accept them for a tutor request." tabindex="133">Tutors</a>';
*/
          if ($profile_made)
            $marketing = anchor('account/marketing', 'Marketing', 'tabindex="133" title="See useful tools, snippets, and tips to market your profile"');
          else
            $marketing = '<a href="javascript:void(0);" class="no-click" title="Available when you finish making your profile" tabindex="133">Marketing</a>';

        ?>

        <li>
            <a href="<?= base_url('account') ?>" tabindex="127" title="<?= $dashboard_title ?>">
              Dashboard <? if ($notices_count > 0) echo "<span id='dropdown-notices-count'>($notices_count)</span>"; ?>
            </a>
        </li>
        <li><?= anchor('account/profile', 'Profile', 'tabindex="129" title="Edit and view your profile"') ?></li>
        <li><?= anchor('account/requests', 'Requests', 'tabindex="131" title="'.$requests_title.'"') ?></li>
<? if ($role != ROLE_STUDENT): ?>
        <li><?= $students ?></li>
<? endif; ?>
        <li><?= $tutors ?></li>
<? if ($role != ROLE_STUDENT): ?>
        <li><?= $marketing ?></li>
<? endif; ?>
        <li class="dropdown-divider"></li>
        <li><?= anchor('requests/new-request', 'Request Tutor', 'tabindex="139" data-reveal-id="request-modal"') ?></li>
        <li><?= anchor('account/settings', 'Change Settings', 'tabindex="141"') ?></li>
        <li class="dropdown-divider"></li>
        <li><?= anchor('logout', 'Log Out', 'tabindex="143"') ?></li>
      </ul>
    </div>

    <? else: ?>
    <div class="logged-status-cont" id="logged-out-cont">
      <div class="button-groups">
        <a href="javascript:void(0);" class="buttons color-3-buttons" data-dropdown="#dropdown-signup" id="sign-up-button" tabindex="120">Sign Up</a><?= anchor('login', 'Log In', 'class="buttons" id="log-in-button" data-reveal-id="login-modal" data-redirect="'.base_url('account').'" tabindex="130"'); ?>
      </div>
    </div>
    <div id="dropdown-signup" class="dropdown dropdown-tip dropdown-anchor-right dropdown-relative">
      <ul class="dropdown-menu">
        <li><?= anchor('signup/tutor', 'New Tutor', 'tabindex="124" data-reveal-id="signup-tutor-modal"') ?></li>
        <li><?= anchor('signup/student', 'New Student', 'tabindex="126" data-reveal-id="signup-student-modal" id="student-signup-link"') ?></li>
      </ul>
    </div>

    <? endif; ?>
  </div>

  <div id="search-bar" class="cf">
    <div id="search-contents-container" class="containers">
      <?= $search_form ?> 
    </div>
  </div>  

</header>

<script>

$(window).load(function()
{
  $('#student-signup-link').click(function()
  {
    $('[name=have-account]', '.signup-students').eq(1).attr('checked', true);
    setStudentSignupToNoAccount();
  });

<? if ($notices_count > 0): ?>
  setTimeout(function() 
  {
    $('.notices-cont', '#reg-header').fadeIn(1000).end()
    .find('.notices-count').animate(
    {
      marginTop: 7,
      opacity: 1
    }, 1000);    
  }, 500);

<? endif; ?>

});

</script>

<div id="main-content">