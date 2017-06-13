<?php
	echo '<ul>';
	if (isset($emp_ls) == TRUE) {

		for ($i=0; $i < $max; $i++) { 
			echo '<li>'.anchor($emp_ls[$i]['link'],$emp_ls[$i]['text']).'</li>';
			# code...
		}

	}
	echo '</ul>';	

?>