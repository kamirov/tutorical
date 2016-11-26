<!DOCTYPE html>  
<html lang="en">  
<head>  
<meta charset="utf-8">

<!--[if lte IE 6]>
<meta http-equiv="refresh" content="0;url=<?= base_url('old') ?>" />
<![endif]-->

<link rel="icon" type="image/png" href="<?= base_url('favicon.png').'?'.date('l jS \of F Y h:i:s A') ?>">

<!--[if lt IE 9]>  
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>  
<![endif]-->  

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>

<!--
<link rel="stylesheet/less" href="<?= base_url('assets/css/tutorical.less').'?'.date('l jS \of F Y h:i:s A') ?>" ?> 
-->
<link rel="stylesheet" href="<?= base_url('assets/css/tutorical.css') ?>">

<!--[if lte IE 9]>
  <script src="<?= base_url('assets/js/selectivizr-min.js') ?>"></script>
  <link rel="stylesheet" href="<?= base_url('assets/css/ie9-and-down.css') ?>" />
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

<script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>

<script src="<?= base_url('assets/js/scripts.js') ?>"></script>

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
  $.fn.qtip.defaults.style.classes = 'qtip-tipsy qtip-shadow qtip-dark';

  // qTip called here because subsequent qTip calls overwrite this
  $('.qtipped').qtip(
  {
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

// These are global and CI variables needed for external scripts

awaitingResponse = {
  registerForm: false,
  loginForm: false,
  general: false
};

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

/* Analytics
   ========= */

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26323495-3', 'tutorical.com');
  ga('send', 'pageview');

</script>

<script src="<?= base_url('assets/js/less-1.3.1.min.js') ?>"></script>

<? if (isset($meta['title'])): ?>
<title><?= $meta['title'] ?></title>
<? else: ?>
<title>Tutorical</title>
<? endif; ?>

<? if (isset($meta['description'])): ?>
<meta name="description" content="<?= $meta['description'] ?>">
<? endif; ?>

</head>

<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="wrapper">