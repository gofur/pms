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
	<div class="span6">
		<div class="row">
			<div class="span2">IDP Start</div>
			<div class="span4"><?php echo substr($idp->BeginDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">IDP End</div>
			<div class="span4"><?php echo substr($idp->EndDate,0,10); ?></div>
		</div>
		<div class="row">
			<div class="span2">Status</div>
			<div class="span4"><?php 
			$status = array(
				'<span class="label">Draft</span>',
				'<span class="label label-warning">Assigned</span>',
				'<span class="label label-important">Rejected</span>',
				'<span class="label label-success">Agreed</span>',
				'<span class="label label-success">Agreed</span>',
				'<span class="label label-success">Agreed</span>',
			);
			echo $status[$statusFlagIDP]; 
			?></div>
		</div>
	</div>
	<?php if(isset($spr_person)!='')
	{?>
	<div class="span6">
		<div class="row">
			<div class="span2">Superior</div>
			<div class="span4"><?php echo $spr_person->NIK ; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php echo $spr_person->Fullname; ?></div>
		</div>
		<div class="row">
			<div class="span4 offset2"><?php echo $spr_post->PositionName; ?></div>
		</div>
	</div>
	<?php } ?>
</div>
<?php
	echo '<div class="row"><div class="span12 header-grid-button">';
	//echo anchor($link['finish_idp'].$RKK->RKKID,'Finish IDP','class="fancybox btn btn-primary pull-right" data-fancybox-type="iframe"');

	if($TotalIDPHeaderByRKKID!=0)
	{

		if($TotalIDPDetailArea!=0)
		{
			if($countHeaderIDP!=0)
			{
			
				if($statusFlagIDP==0)
				{
					echo anchor($link['add_development_plan'].$RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
					echo anchor($link['finish_idp'].$RKK->RKKID,'Submit IDP',' class="fancybox btn btn-primary" data-fancybox-type="iframe"');
				}
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
					
					echo '<td>'.$row_IDPDetailProgram->DevelopmentProgram.' - <br>'.$row_IDPDetailProgram->Description.'</td>';
					echo '<td>'.format_timedate($row_IDPDetailProgram->Planned_BeginDate).' s/d '.format_timedate($row_IDPDetailProgram->Planned_EndDate).'</td>';
					echo '<td>'.thousand_separator($row_IDPDetailProgram->Planned_Investment).'</td>';
					echo '<td>'.$row_IDPDetailProgram->Notes.'</td>';
					echo '<td>';
						if($statusFlagIDP==0)
						{
							echo anchor('objective/idp/edit/'.$row_IDPDetailProgram->IDPDevelopmentProgramID,'<i class="icon-pencil"></i>','title="Ubah"  class="fancybox" data-fancybox-type="iframe"');
						}
						echo '</td></tr>';	
					}
				
				}
			}
		}
		else
		{	
			if($RoleID==4)
			{
				if($statusFlagIDP==0)
				{
					echo anchor($link['add_development_plan'].$RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
					echo anchor($link['finish_idp'].$RKK->RKKID,'Submit IDP',' class="fancybox btn btn-primary" data-fancybox-type="iframe"');
				}
			}
			echo '</div></div>';
			echo '<table id="table-tree1-1" class="table table-bordered">';
			echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
			echo '<tbody>';
		}
	}
	else
	{
		if($RoleID==4)
			{
				if($countHeaderIDP!=0)
				{
					if($statusFlagIDP==0)
					{
						echo anchor($link['add_development_plan'].$RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
						echo anchor($link['finish_idp'].$RKK->RKKID,'Finish IDP',' class="fancybox btn btn-primary" data-fancybox-type="iframe"');
					}
				}
				else
				{
						echo anchor($link['add_development_plan'].$RKK->RKKID,'Add Development Plan',' class="fancybox btn" data-fancybox-type="iframe"').' ';
						echo anchor($link['finish_idp'].$RKK->RKKID,'Finish IDP',' class="fancybox btn btn-primary" data-fancybox-type="iframe"');
				}
				
			}
			echo '</div></div>';
			echo '<table id="table-tree1-1" class="table table-bordered">';
			echo '<thead><tr><th width="180px">Development Area Type 1</th><th>Development Program</th><th>Planned Time</th><th>Investment</th><th>Notes</th><th width="180">Action</th></tr></thead>';
			echo '<tbody>';
	}
		
		echo '</tbody>';
		echo '</table>';
	
	
?>
