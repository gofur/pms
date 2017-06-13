<div class="row">
	<div class="span10">
		<div class="alert <?php echo $notif_type?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo $notif_text?>
		</div>
	</div>
</div>
<div class="row">
	<div class="span10">
		<?php 
		echo anchor($link, 'Add More', 'class="btn" title="Add More" ');
		echo anchor('home','Close', 'class="btn" onClick="parent.$.fancybox.close();"');
		?>
	</div>
</div>