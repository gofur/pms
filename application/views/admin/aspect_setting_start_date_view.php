	<div class="span10" id="state">
		<?php 
			if(isset($start_date))
			{
				echo format_timedate($start_date);
			}
		?> 
		to 
		<?php 
			if(isset($end_date))
			{
				echo format_timedate($end_date);
			}
		?>
	</div>