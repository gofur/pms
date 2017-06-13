<?php
if(isset($notif_text)!=''){
	if($notif_text!='')
	{
		echo '<div class="alert alert-info">';
  		echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
  		echo $notif_text;
  	echo '</div>';
  }
}
?>
<?php
	echo '<div class="row"><div class="span12 header-grid-button">';
	if($countHeaderIDP!=0)
	{
	echo '</div></div>';


	
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
		
		foreach ($IDPDetailArea as $row_IDPDetailArea) 
		{
			$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
			echo '<tr>';	
			echo '<td rowspan="2">'.$DevelopmentAreaType1.' - <br>'.$DevelopmentAreaType[$row_IDPDetailArea->IDPDetailID];
			
			if($statusFlagIDP==0)
			{
				echo '<br><br>'.anchor($link['add_development_program'].$row_IDPDetailArea->IDPDetailID,'Add Development Program','class="fancybox" data-fancybox-type="iframe"');
			}

			echo '</td></tr>';
			
			$i=1;
			foreach ($IDPDetailProgram[$row_IDPDetailArea->IDPDetailID] as $row_IDPDetailProgram) 
			{
				if($row_IDPDetailArea->DevelopmentAreaType1ID==2)
				{
					if($i>1)
					{
						echo '<td></td>';	
					}
					$i++;
				}

				if($row_IDPDetailArea->DevelopmentAreaType1ID==1)
				{
					if($i>1)
					{
						echo '<td></td>';	
					}
					$i++;
				}
				if($row_IDPDetailArea->DevelopmentAreaType1ID==3)
				{
					if($i>1)
					{
						echo '<td></td>';	
					}
					$i++;
				}
			
			echo '<td>'.$row_IDPDetailProgram->DevelopmentProgram.' - <br>'.$row_IDPDetailProgram->Description.'</td>
					<td>'.format_timedate($row_IDPDetailProgram->Planned_BeginDate).' s/d '.format_timedate($row_IDPDetailProgram->Planned_EndDate).'</td>
					<td>'.thousand_separator($row_IDPDetailProgram->Planned_Investment).'</td>
					<td>'.$row_IDPDetailProgram->Notes.'</td>';
					echo '<td>';
				if($statusFlagIDP==3)
				{
					echo anchor('performance/idr/edit/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-edit"></i>','title="Update Realization"  class="fancybox" data-fancybox-type="iframe"');
				}
				echo '</td></tr>';	
			}
		
		}
	}
	else
	{
		echo anchor($link['add_development_plan'].$RKK->RKKID,'Add Developmen Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
		echo '</div></div>';
		echo '<table id="table-tree1-1" class="table table-bordered">';
		echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
		echo '<tbody>';
	}
		
		echo '</tbody>';
		echo '</table>';
	
?>
