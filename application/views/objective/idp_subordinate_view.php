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
<div class="row">
	<div class="span12 header-grid-button">
<?php

if(count($totalBawahanRKK)!=0)
{
	if($Bawahan_RKK->statusFlag==0 || $Bawahan_RKK->statusFlag==2)
	{
	  if($countHeaderIDP!=0)
		{

			if($statusFlagIDP==0 && $totalIDPDetail==0)
			{
				echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
				echo anchor($link['finish_idp'].$Bawahan_RKK->RKKID.'/'.$Bawahan_RKK->PositionID.'/'.$Chief_RKKID,'Submit IDP','onclick="return confirmPost()" class="fancybox btn btn-primary"');
			}
			elseif($statusFlagIDP==3 OR $statusFlagIDP==1)
			{
				//
			}
			else
			{
				echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
				echo anchor($link['finish_idp'].$Bawahan_RKK->RKKID.'/'.$Bawahan_RKK->PositionID.'/'.$Chief_RKKID,'Submit IDP','onclick="return confirmPost()" class="fancybox btn btn-primary"');	
			}
			echo '</div></div>';


		
			echo '<table id="table-tree1-1" class="table table-bordered">';
			echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Planned Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
			echo '<tbody>';
			
			foreach ($IDPDetailArea as $row_IDPDetailArea) 
			{
				$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
				echo '<tr>';	
				echo '<td rowspan="2">'.$DevelopmentAreaType1.' - <br>'.$DevelopmentAreaType[$row_IDPDetailArea->IDPDetailID];
				
				if($statusFlagIDP==0)
				{
					//echo '<br><br>'.anchor($link['add_development_program'].$row_IDPDetailArea->IDPDetailID,'Add Development Program','class="btnbtn_ fancybox" data-fancybox-type="iframe"');
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
					if($statusFlagIDP!=3)
					{
						echo anchor('objective/idp/edit/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-pencil"></i>&nbsp;','title="Ubah"  class="fancybox" data-fancybox-type="iframe"');
						echo anchor('objective/idp/delete/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-remove"></i>','title="Delete"  class="fancybox" data-fancybox-type="iframe"');
						echo anchor($link['transfer_idp'].$Bawahan_RKK->NIK.'/'.$Chief_RKKID.'/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-share-alt"></i>','title="Transfer"  class="fancybox" data-fancybox-type="iframe"');
					}

					echo '</td></tr>';	
				}
			
			}
		}
		else
		{
			// echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Developmen Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
			echo '</div></div>';
			echo '<table id="table-tree1-1" class="table table-bordered">';
			echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Planned Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
			echo '<tbody>';
		}
	}
	elseif($Bawahan_RKK->statusFlag==1 || $Bawahan_RKK->statusFlag==3) 
	{
		if($TotalIDPDetailArea!=0)
		{
			if($countHeaderIDP!=0)
			{
				if($statusFlagIDP==0 && $totalIDPDetail==0)
				{
					echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
					echo anchor($link['finish_idp'].$Bawahan_RKK->RKKID,'Submit IDP','onclick="return confirmPost()"  class="fancybox btn btn-primary" ');
				}
				elseif($statusFlagIDP==3 OR $statusFlagIDP==1)
				{
					//
				}
				else
				{
					echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
					echo anchor($link['finish_idp'].$Bawahan_RKK->RKKID,'Submit IDP','onclick="return confirmPost()"  class="fancybox btn btn-primary" ');	
				}

				echo '</div></div>';
				echo '<table id="table-tree1-1" class="table table-bordered" style="font-size:10pt;">';
				echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Planned Investment</th><th>Realization Time</th><th>Realization Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
				echo '<tbody>';
				

				foreach ($IDPDetailArea as $row_IDPDetailArea) 
				{
					$DevelopmentAreaType1 = $row_IDPDetailArea->DevelopmentAreaType1;
					echo '<tr>';	
					echo '<td rowspan="2">'.$DevelopmentAreaType1.' - <br>'.$DevelopmentAreaType[$row_IDPDetailArea->IDPDetailID];
					if($statusFlagIDP==0)
					{
						//echo '<br><br>'.anchor($link['add_development_program'].$row_IDPDetailArea->IDPDetailID,'Add Development Program','class="btn btn-primary fancybox" data-fancybox-type="iframe"');
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
							<td>'.format_timedate($row_IDPDetailProgram->Realization_BeginDate).' s/d '.format_timedate($row_IDPDetailProgram->Realization_EndDate).'</td>
							<td>'.thousand_separator($row_IDPDetailProgram->Realization_Investment).'</td>
							<td>'.$row_IDPDetailProgram->Notes.'</td>';
							echo '<td>';
							if($statusFlagIDP!=3)
							{
								echo anchor('objective/idp/edit/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-pencil"></i>','title="Ubah"  class="fancybox" data-fancybox-type="iframe"');
							}
							else
							{
								echo anchor('objective/idp/add_realization/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-plus-sign"></i>','title="Add Realization"  class="fancybox" data-fancybox-type="iframe"');	
							}
						echo '</td></tr>';	
					}
				
				}
			}
		}
		else
		{
			//echo anchor($link['add_development_plan'].$Bawahan_RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
			echo '</div></div>';
			echo '<table id="table-tree1-1" class="table table-bordered">';
			echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Planned Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
			echo '<tbody>';
		}

	}
}		
		echo '</div></div>';
		echo '</table>';
	
	
?>
<script type="text/javascript">
function confirmPost()
{
	var agree=confirm("Are you sure to finish IDP?");
	if (agree)
	return true ;
	else
	return false ;
}
</script>