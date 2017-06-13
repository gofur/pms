<h3><?=$Title?></h3>
<?php 
	if (isset($link['view_self']))
	{
?>
	<div class="row">
		<div class="span12">
			<ul class="breadcrumb">
				<li><?php echo anchor ($link['view_self'],'Me')?><span class="divider">/</span></li>
				<li class="active"><?php echo $userDetail->NIK .' - '. $userDetail->Fullname  ?></li>
			</ul>
		</div>
	</div>
<?php
}
?>

<div class="row">
	<div class="span12">
		<table class="table datatable table-bordered table-stripted table-hover">
			<thead><tr><th>Program IDP</th><th>Realization Date</th><th>NIK - Nama</th></tr></thead>
			<tbody>
				<?php
				if(isset($subordinate)!=0)
				{
					foreach ($subordinate as $row) {	

						foreach ($subordinate_not_submit_1[$row['UserID']] as $submit_1) {

									echo '<tr>';
									echo '<td>'.$submit_1['DevelopmentAreaType'].'</td>';
									echo '<td>'.format_timedate($submit_1['Realization_BeginDate']).' s/d '.format_timedate($submit_1['Realization_EndDate']).'</td>';
									echo '<td>'.$submit_1['NIK'].' - '.$submit_1['Fullname'].'</td>';
									echo '</tr>';
						}			

						
						foreach ($y[$row['UserID']] as $sub1) {
							foreach ($subordinate_not_submit_2[$row['UserID']][$sub1->UserID] as $submit_2) {


									echo '<tr>';
									echo '<td>'.$submit_2['DevelopmentAreaType'].'</td>';
									echo '<td>'.format_timedate($submit_2['Realization_BeginDate']).' s/d '.format_timedate($submit_2['Realization_EndDate']).'</td>';
									echo '<td>'.$submit_2['NIK'].' - '.$submit_2['Fullname'].'</td>';
									echo '</tr>';
							}
						
							foreach ($subordinate_2[$row['UserID']][$sub1->UserID]  as $sub2) {
								foreach ($subordinate_not_submit_3[$row['UserID']][$sub1->UserID][$sub2->UserID] as $submit_3) {

									echo '<tr>';
									echo '<td>'.$submit_3['DevelopmentAreaType'].'</td>';
									echo '<td>'.format_timedate($submit_3['Realization_BeginDate']).' s/d '.format_timedate($submit_3['Realization_EndDate']).'</td>';
									echo '<td>'.$submit_3['NIK'].' - '.$submit_3['Fullname'].'</td>';
									echo '</tr>';
								}

								foreach ($subordinate_3[$row['UserID']][$sub1->UserID][$sub2->UserID]  as $sub3) {
									foreach ($subordinate_not_submit_4[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID] as $submit_4) {

									echo '<tr>';
									echo '<td>'.$submit_4['DevelopmentAreaType'].'</td>';
									echo '<td>'.format_timedate($submit_4['Realization_BeginDate']).' s/d '.format_timedate($submit_4['Realization_EndDate']).'</td>';
									echo '<td>'.$submit_4['NIK'].' - '.$submit_4['Fullname'].'</td>';
									echo '</tr>';
									}

									foreach ($subordinate_4[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID]  as $sub4) {
										foreach ($subordinate_not_submit_5[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID] as $submit_5) {

										echo '<tr>';
										echo '<td>'.$submit_5['DevelopmentAreaType'].'</td>';
										echo '<td>'.format_timedate($submit_5['Realization_BeginDate']).' s/d '.format_timedate($submit_5['Realization_EndDate']).'</td>';
										echo '<td>'.$submit_5['NIK'].' - '.$submit_5['Fullname'].'</td>';
										echo '</tr>';
										}
										
										foreach ($subordinate_5[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID]  as $sub5) {
											foreach ($subordinate_not_submit_6[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID] as $submit_6) {

											echo '<tr>';
											echo '<td>'.$submit_6['DevelopmentAreaType'].'</td>';
											echo '<td>'.format_timedate($submit_6['Realization_BeginDate']).' s/d '.format_timedate($submit_6['Realization_EndDate']).'</td>';
											echo '<td>'.$submit_6['NIK'].' - '.$submit_6['Fullname'].'</td>';
											echo '</tr>';
											}
										}
									}
								}
							}

						}
					}
				}
			?>
			</tbody>
		</table>
	</div>
</div>
