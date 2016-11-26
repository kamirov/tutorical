<!-- No header or footer -->
<? if (!$email): ?>
<p style="line-height:1.6; margin: 0; font-style: italic;">[No email provided]</p><br>
<? endif; ?>
<p style="line-height:1.6; margin: 0;"><?= nl2br($message) ?></p>