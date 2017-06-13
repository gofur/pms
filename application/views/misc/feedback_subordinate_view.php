<div class="row">
	<div class="span12 header-grid-button">
<?php

if($TotalRKK!=0)
{
  if($TotalFeedBack!=0)
	{
		echo anchor($link['add_feedback_aspect'].$Bawahan_RKK->RKKID,'Add Feedback Aspek',' class="fancybox btn" data-fancybox-type="iframe"').' ';
		echo '</div></div>';
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Feedback Aspect</th><th>Tgl Coaching</th><th>Feedback Point</th><th>Evidence</th><th>Cause</th><th>Alternative Solution</th><th width="180">Due Date</th><th width="180">Actual Date</th><th width="180">Check List</th><th>Notes</th><th width="180">Status</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
		
		foreach($FeedbackDetail as $row) 
		{
			echo '<tr>';	
			echo '<td rowspan="2">'.$row->FeedbackAspect;
			if($statusFeedback==1)
			{
				echo '<br><br>'.anchor($link['add_feedback_point'].$row->FeedbackDetailID,'Add Feedback Point','class="fancybox" data-fancybox-type="iframe"');
			}

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
					
					echo '</td><td>'.$rowDetail->Notes.'</td><td>';
					
					if($rowDetail->StatusPoint==NULL )
					{
						echo '<span class="label label-info">Pending</span>';
					}
					elseif ($rowDetail->StatusPoint==2) 
					{
						echo '<span class="label label-important">Disagree</span>';
					}
					else
					{
						echo '<span class="label label-success">Final</span>';
					}

					echo '</td></td><td>';
				if($rowDetail->Status==1)
				{
					echo anchor('misc/feedback/edit/'.$rowDetail->FeedbackPointID,'<i class="icon-pencil"></i>','title="Edit Feedback"  class="fancybox" data-fancybox-type="iframe"').' ';
					echo anchor('misc/feedback/remove/'.$rowDetail->FeedbackPointID,'<i class="icon-trash"></i>','title="Delete Feedback"  class="fancybox" data-fancybox-type="iframe"');
				}
				echo '</td></tr>';	
			}
		}
	}
	else
	{
		echo anchor($link['add_feedback_aspect'].$Bawahan_RKK->RKKID,'Add Feedback Aspek',' class="fancybox btn" data-fancybox-type="iframe"').' ';
		echo '</div></div>';
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Feedback Aspect</th><th>Tgl Coaching</th><th>Feedback Point</th><th>Evidence</th><th>Cause</th><th>Alternative Solution</th><th width="180">Due Date</th><th width="180">Actual Date</th><th width="180">Check List</th><th>Notes</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
	}

}
		
		echo '</tbody>';
		echo '</table>';
	
	
?>