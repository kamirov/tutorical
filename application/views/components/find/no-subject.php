<section id="text-regular" class="cf pages containers">

<h1 id="page-heading" class="none-found-heading">
<? if ($groups == 'tutors'): ?>
	<span>Sorry, no tutors currently teach <span class="find-editables"><?= $readable_subject ?></span>...yet!</span>
<? else: ?>
	<span>Sorry, no students are looking to learn <span class="find-editables"><?= $readable_subject ?></span>...yet!</span>
<? endif; ?>
</h1>

	<div id="page-content" class="text-content">
	<? if ($groups == 'tutors'): ?>
		<p>But you might find some if you <a href="javascript:void(0);" data-reveal-id="request-modal">make a Tutor Request</a>. It's free and it'll let tutors find you!</p>
	<? endif; ?>
	</div>

</section>  <!-- /#text-regular -->
