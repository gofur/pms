
<?php
if(!empty($notif_text))
{	
				?>
<div class="row">
	<div class="span12">
		<div class="alert <?php echo $notif_type?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php 
			echo $notif_text;
			?>
		</div>
	</div>
</div>
<?php
} ?>