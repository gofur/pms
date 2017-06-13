<div class="row">
	<div class="span2">Strategic Objective #<?php echo $num; ?></div>
	<div class="span3">
		<?php 

			echo form_input('txt_so_'.$num, '', 'id="txt_so_'.$num.'" class="input-large"'); 
		?>
	</div>
	<div class="span5">
		<?php 
			echo form_textarea('txt_desc_'.$num, '', 'id="txt_desc_'.$num.'" class="input-large" rows="3"');
		?>
	</div>
</div>