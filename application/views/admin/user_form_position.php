<div class="row">
	<div class="span2">Position</div>
	<div class="span10"><select name="SlcPost" id="SlcPost" class="input-large">
		<option value=""></option>
		<?php
		foreach ($postList as $row) {
			if ($row->PositionID==$current){
				echo '<option selected="selected" value="'.$row->PositionID.'">'. $row->PositionName .'</option>';
				
			}else{
				echo '<option value="'.$row->PositionID.'">'. $row->PositionName .'</option>';

			}
		}
	?></select>
	</div>
</div>