<form role="search" method="get" id="search-form" action="<?= home_url() ?>">
	<div class="form-elements inline-block">
		<div class="form-inputs line-inputs">
			<input type="text" name="s" id="search-blog-text" placeholder="Search through posts" class="form-inputs" tabindex="400">
			<div class="form-input-notes error-messages search-form-errors"></div>
		</div>
	</div><div class="form-elements inline-block search-button">
		<input type="submit" value="Search" class="buttons color-3-buttons search-button dark-background-buttons" id="search-blog-button" tabindex="500">
	</div>
</form>