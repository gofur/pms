<h3>Report Subordinate IDP</h3>
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
	<div class="span6">
		<div class="row">
			<div class="span2">NIK</div>
			<div class="span4"><strong><?=$userDetail->NIK?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Name</div>
			<div class="span4"><strong><?=$userDetail->Fullname; ?></strong></div>
		</div>
		<div class="row">
			<div class="span2">Position</div>
			<div class="span4">
			<?php
			if(isset($PositionName))
			{
				echo $PositionName; 
				
			}
			else
			{
				$attributes = array('class' => 'form-inline', 'id' => 'genForm'); 
				echo form_open('report/idp_report/',$attributes);
				echo '<select class="input-medium" name="SlcPost" id="SlcPost">';
				foreach ($PositionList_SAP as $row) {
					if($Holder==('1.'.$row->HolderID)){
						echo '<option selected="selected" value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="1.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}foreach ($PositionList_nonSAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}
				foreach ($PositionAssignmentList_nonSAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}
				foreach ($PositionAssignmentList_SAP as $row) {
					if($Holder==('0.'.$row->HolderID)){
						echo '<option selected="selected" value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';

					}else{
						echo '<option value="0.'.$row->HolderID.'">'.$row->PositionName.'</option>';
					}
				}
				echo '</select> <input type="submit" value="View" class="btn"></form>'; 
			}
			?>
				</div>
		</div>
		
	</div>
	<div class="span6">
		<div class="row">
			<div class="span2">Period</div>
			<div class="span1"><?=$Periode->Tahun; ?></div>
			<div class="span1"><?php echo anchor('period', 'Change Period', 'class="fancybox" data-fancybox-type="iframe"'); ?></div>
		</div>
		<div class="row">
			<div class="span2">Start Date</div>
			<div class="span4"><?=format_timedate($Periode->BeginDate); ?></div>
		</div>
		<div class="row">
			<div class="span2">End Date</div>
			<div class="span4"><?=format_timedate($Periode->EndDate); ?></div>
		</div>
	</div>
</div>

<div class="row">
	<div class="span6">
		<div class="row">
			<div class="span6">
				<ul id="tt" class="easyui-tree tree">
				<?php

				//jika yang memiliki anak buah maka bisa di klik sub nodenya
					if(isset($subordinate)){
						foreach ($subordinate as $row) 
						{
							//count($row);
							echo '<li ><span>'.$row['NIK'].' - 
								'.$row['Fullname'].' ('.$row['PositionName'].')'.'</span>';


							foreach($y[$row['UserID']] as $row_1)
							{
								/*echo '<ul><li><span>'.anchor($row_1->UserID.'/'.$row_1->PositionID,$row_1->NIK.' - 
								'.$row_1->Fullname.' ('.$row_1->PositionName.')').'</span>';*/

								echo '<ul><li><span>'.$row_1->NIK.' - 
								'.$row_1->Fullname.' ('.$row_1->PositionName.')'.'</span>';

								//check lagi apakah dia memiliki anak buah
								foreach ($subordinate_2[$row['UserID']][$row_1->UserID] as $subordinate_detail_2) {
									echo '<ul><li><span>'.$subordinate_detail_2->NIK.' - 
								'.$subordinate_detail_2->Fullname.' ('.$subordinate_detail_2->PositionName.')'.'</span>';

									foreach ($subordinate_3[$row['UserID']][$row_1->UserID][$subordinate_detail_2->UserID] as $subordinate_detail_3) {
										echo '<ul><li><span>'.$subordinate_detail_3->NIK.' - 
								'.$subordinate_detail_3->Fullname.' ('.$subordinate_detail_3->PositionName.')'.'</span>';


										foreach ($subordinate_4[$row['UserID']][$row_1->UserID][$subordinate_detail_2->UserID][$subordinate_detail_3->UserID] as $subordinate_detail_4) {
													echo '<ul><li><span>'.$subordinate_detail_4->NIK.' - 
												'.$subordinate_detail_4->Fullname.' ('.$subordinate_detail_4->PositionName.')'.'</span>';

												foreach ($subordinate_5[$row['UserID']][$row_1->UserID][$subordinate_detail_2->UserID][$subordinate_detail_3->UserID][$subordinate_detail_4->UserID] as $subordinate_detail_5) {
													echo '<ul><li><span>'.$subordinate_detail_5->NIK.' - 
												'.$subordinate_detail_5->Fullname.' ('.$subordinate_detail_5->PositionName.')'.'</span></li></ul>';
												}

													echo '</li></ul>';
										}

										echo '</li></ul>';

									}

									echo '</li></ul>';
								
								}

								echo '</li></ul>';
							}
							echo '</li>';
							
						}	
					}

				?>
				</ul>
			
				
				
			</div>
			<div class="span6"></div>
		</div>
	</div>
	<div class="span6">
		<table class="table table-striped">
      <thead>
        <tr>
          <th>Total IDP</th>
          <th>Jumlah IDP yang terealisasi</th>
          <th>Jumlah IDP yang belum terealisasi</th>
          <th>Jumlah IDP yang terealisasi tepat waktu</th>
          <th>% IDP yang terealisasi tepat waktu</th>
          </tr>
      </thead>
      <tbody>
        
        <?php
			if(isset($subordinate)){
				
				if(isset($total_idp))
				{
					echo '<tr><td>'.$total_idp.'</td>';
					echo '<td>'.anchor($link['view_submited_idp'],$total_idp_terealisasi,'title="View Realization IDP"  class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
					echo '<td>'.anchor($link['view_not_submited_idp'],$total_idp_not_terealisasi,'title="View Not Realization IDP"  class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
					echo '<td>'.anchor($link['view_idp_on_time'],$total_idp_terealisasi_tepat_waktu,'title="View IDP Tepat Waktu"  class="fancybox-nonrefresh" data-fancybox-type="iframe"').'</td>';
					echo '<td>'.$total_average_idp.'</td></tr>';
				}
			}
		?>
        
        
      </tbody>
    </table>
	</div>
</div>