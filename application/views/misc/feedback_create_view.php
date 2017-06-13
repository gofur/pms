
<?php
	echo '<div class="row"><div class="span12 header-grid-button">';
	//echo anchor($link['finish_idp'].$RKK->RKKID,'Finish IDP','class="fancybox btn btn-primary pull-right" data-fancybox-type="iframe"');
	if($TotalFeedBack!=0)
	{
	echo '</div></div>';


		$attributes = array('id' => 'genFrom'); 
		echo form_open($process,$attributes);
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Feedback Aspect</th><th>Tgl Coaching</th><th>Feedback Point</th><th>Evidence</th><th>Cause</th><th>Alternative Solution</th><th width="180">Due Date</th><th width="180">Actual Date</th><th width="180">Check List</th><th>Notes</th>';
		echo '<th>Action</th>';
		echo '</tr></thead>';
		echo '<tbody>';
		
		foreach($FeedbackDetail as $row) 
		{
			echo '<tr>';	
			echo '<td rowspan="2">'.$row->FeedbackAspect;
			echo '</td></tr><tr>';
			
			$i=1;
			foreach ($FeedbackDetailAll[$row->FeedbackDetailID] as $rowDetail) 
			{
				if($rowDetail->FeedbackAspectID==1)
				{
					if($i>1)
					{
						echo '<td></td>';	
					}
					$i++;
				}
			
			echo '<td>'.format_timedate($rowDetail->TglCoaching).'</td>
					<td>'.$rowDetail->FeedbackPoint.'</td>
					<td>'.$rowDetail->Evidence.'</td>
					<td>'.$rowDetail->Cause.'</td><td>'.$rowDetail->AlternativeSolution.'
					<td>'.format_timedate($rowDetail->DueDate).'</td><td>'.format_timedate($rowDetail->ActualDate).'</td>
					<td>';
					if($rowDetail->CheckList==1)
						{ echo '<i class="icon-ok"></i>';}
					else{ echo '<i class="icon-remove"></i>'; }
					echo '</td><td>'.$rowDetail->Notes.'</td></td>';
					if($rowDetail->StatusPoint==NULL)
					{
						echo '<td><input type="checkbox" name="checkPoint[]" class="input-small" value="'.$rowDetail->FeedbackPointID.'"></td>';
					}
					else
					{
						echo '<td></td>';
					}
					echo '</tr>';	
			}
		
		}

		echo '</tbody>';
		echo '</table>';
		$flag=0;
		foreach($FeedbackDetail as $row) 
		{
			$i=1;
			foreach ($FeedbackDetailAll[$row->FeedbackDetailID] as $rowDetail) 
			{
					if($rowDetail->StatusPoint==NULL)
					{
						$flag=1;break;
					}	
			}
		
		}
		
		if($flag==1)
		{
			echo '<div class="row"><div class="span12 offset5">
			<button class="btn btn-success"  type="submit"  value="Agree" id="btnAgree" name="btnAgree" >Agree</button>
			<button class="btn btn-danger"  type="submit"  value="Disagree" id="btnDisAgree" name="btnDisagree" >Disagree</button>
			</div>
			</div>';
		}
	}
	else
	{
		echo '</div></div>';
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Feedback Aspect</th><th>Tgl Coaching</th><th>Feedback Point</th><th>Evidence</th><th>Cause</th><th>Alternative Solution</th><th width="180">Due Date</th><th width="180">Actual Date</th><th width="180">Check List</th><th>Notes</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
		echo '</tbody>';
		echo '</table>';
	}
		
		
	
?>
