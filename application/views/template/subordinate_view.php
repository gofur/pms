<?php

	echo '<div class="row">';
	echo '<div class="span2">Subordinate</div>';
	echo '<div class="span4" style="height:277px;overflow:scroll;overflow-x:hidden;"><ul>';
	if (isset($link) == FALSE) {
		foreach ( $sub_ls as $row) {
			echo '<li><a href="#">'.$row->NIK.' - '.$row->Fullname.' ('.$row->PositionName.')'.'</a></li>';
		}
	}else {
		foreach ( $sub_ls as $row) {
			$key = $row->NIK .'|'.$row->isSAP.'|'.$row->PositionID;
			echo '<li>'.anchor($link[$key],$row->NIK.' - '.$row->Fullname.' ('.$row->PositionName.')').'</li>';
		}

	}
	echo '</ul></div></div>';	

?>