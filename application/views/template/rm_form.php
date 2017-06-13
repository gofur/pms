<div class="row">
	<div class="span2">Action</div>
	<div class="span2">
		<?php 
			echo form_label(form_radio('rd_action', 'delimit', FALSE,'id="rd_delimit"').' DELIMIT', 'rd_action', 'class="radio"');
			echo form_input('dt_end', $end, 'id="dt_end" class="input-small datepicker disabled"');
			echo form_label(form_radio('rd_action', 'remove', FALSE,'id="rd_remove"').' REMOVE', 'rd_action', 'class="radio"');
		?>
	</div>
</div>