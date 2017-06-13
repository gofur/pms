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
			<thead><tr><th>NIK</th><th>Name</th><th>Position</th></tr></thead>
			<tbody>
				<?php
				if(isset($subordinate)!=0)
				{

					foreach ($subordinate as $row) {	

						foreach ($subordinate_submit_1[$row['UserID']] as $submit_1) {
							$nik_ada=$submit_1->NIK;
						}

						foreach ($subordinate_not_submit_1[$row['UserID']] as $not_submit_1) {
							$nik_not_ada=$not_submit_1->NIK;
						}


						if(isset($nik_not_ada)!=NULL)
						{
							if($row['NIK']==$nik_not_ada)
							{

								echo '<tr>';
								echo '<td>'.$row['NIK'].'</td>';
								echo '<td>'.$row['Fullname'].'</td>';
								echo '<td>'.$row['PositionName'].'</td>';
								echo '</tr>';
							}


							if(isset($nik_ada)!=NULL)
							{
								if($row['NIK']!=$nik_ada)
								{

									if(isset($nik_not_ada)!=NULL)
									{
										if($nik_not_ada!=$row['NIK'])
										{
											echo '<tr>';
											echo '<td>'.$row['NIK'].'</td>';
											echo '<td>'.$row['Fullname'].'</td>';
											echo '<td>'.$row['PositionName'].'</td>';
											echo '</tr>';
										}
									}
									else
									{
										if($nik_not_ada!=$row['NIK'])
										{
											echo '<tr>';
											echo '<td>'.$row['NIK'].'</td>';
											echo '<td>'.$row['Fullname'].'</td>';
											echo '<td>'.$row['PositionName'].'</td>';
											echo '</tr>';
										}	
									}
								}
							}else
							{
								if(isset($nik_not_ada)!=NULL)
								{
									if($nik_not_ada!=$row['NIK'])
									{
										echo '<tr>';
										echo '<td>'.$row['NIK'].'</td>';
										echo '<td>'.$row['Fullname'].'</td>';
										echo '<td>'.$row['PositionName'].'</td>';
										echo '</tr>';
									}
								}
							}
						}
						else
						{
							if(isset($nik_not_ada)!=NULL)
							{
								if($row['NIK']==$nik_not_ada)
								{

									echo '<tr>';
									echo '<td>'.$row['NIK'].'</td>';
										echo '<td>'.$row['Fullname'].'</td>';
										echo '<td>'.$row['PositionName'].'</td>';
									echo '</tr>';
								}
							}
							else
							{
								if(isset($nik_ada)!=NULL)
								{
									if($row['NIK']!=$nik_ada)
									{

										echo '<tr>';
										echo '<td>'.$row['NIK'].'</td>';
										echo '<td>'.$row['Fullname'].'</td>';
										echo '<td>'.$row['PositionName'].'</td>';
										echo '</tr>';
									}
								}
							}
						}

						if(isset($nik_ada)==NULL AND isset($nik_not_ada)==NULL)
						{
								echo '<tr>';
								echo '<td>'.$row['NIK'].'</td>';
										echo '<td>'.$row['Fullname'].'</td>';
										echo '<td>'.$row['PositionName'].'</td>';
								echo '</tr>';
						}



						foreach ($y[$row['UserID']] as $sub1) {
							foreach ($subordinate_submit_2[$row['UserID']][$sub1->UserID] as $submit_2) {
								$nik_ada_1 = $submit_2->NIK;
							}							


							foreach ($subordinate_not_submit_2[$row['UserID']][$sub1->UserID]  as $not_submit_2) {
								$nik_not_ada_1=$not_submit_2->NIK;
							}

							if(isset($nik_ada_1)==NULL AND isset($nik_not_ada_1)==NULL)
							{
								
									echo '<tr>';
									echo '<td>'.$sub1->NIK.'</td>';
									echo '<td>'.$sub1->Fullname.'</td>';
									echo '<td>'.$sub1->PositionName.'</td>';
									echo '</tr>';
							}

							
						if(isset($nik_not_ada_1)!=NULL)
						{
							if($sub1->NIK==$nik_not_ada_1)
							{
								echo '<tr>';
								echo '<td>'.$sub1->NIK.'</td>';
								echo '<td>'.$sub1->Fullname.'</td>';
								echo '<td>'.$sub1->PositionName.'</td>';
								echo '</tr>';
							}


							if(isset($nik_ada_1)!=NULL)
							{

								if($sub1->NIK!=$nik_ada_1)
								{

									if(isset($nik_not_ada_1)!=NULL)
									{
										if($nik_not_ada_1!=$sub1->NIK)
										{
											echo '<tr>';
											echo '<td>'.$sub1->NIK.'</td>';
											echo '<td>'.$sub1->Fullname.'</td>';
											echo '<td>'.$sub1->PositionName.'</td>';
											echo '</tr>';
										}
									}
									else
									{
										if($nik_not_ada_1!=$sub1->NIK)
										{
											echo '<tr>';
											echo '<td>'.$sub1->NIK.'</td>';
											echo '<td>'.$sub1->Fullname.'</td>';
											echo '<td>'.$sub1->PositionName.'</td>';
											echo '</tr>';
										}	
									}
								}
							}
							else
							{
								if(isset($nik_not_ada_1)!=NULL)
								{
									if($nik_not_ada_1!=$sub1->NIK)
									{
										echo '<tr>';
										echo '<td>'.$sub1->NIK.'</td>';
										echo '<td>'.$sub1->Fullname.'</td>';
										echo '<td>'.$sub1->PositionName.'</td>';
										echo '</tr>';
									}
								}
							}
						}
						else
						{
							if(isset($nik_not_ada_1)!=NULL)
							{
								if($sub1->NIK==$nik_not_ada_1)
								{

									echo '<tr>';
									echo '<td>'.$sub1->NIK.'</td>';
									echo '<td>'.$sub1->Fullname.'</td>';
									echo '<td>'.$sub1->PositionName.'</td>';
									echo '</tr>';
								}
							}
							else
							{
								if(isset($nik_ada_1)!=NULL)
								{
									if($sub1->NIK!=$nik_ada_1)
									{

										echo '<tr>';
										echo '<td>'.$sub1->NIK.'</td>';
										echo '<td>'.$sub1->Fullname.'</td>';
										echo '<td>'.$sub1->PositionName.'</td>';
										echo '</tr>';
									}
								}
							}
						}




								
							foreach ($subordinate_2[$row['UserID']][$sub1->UserID]  as $sub2) {
								foreach ($subordinate_submit_3[$row['UserID']][$sub1->UserID][$sub2->UserID] as $submit_3) {
									$nik_ada_2=$submit_3->NIK;
								}

								foreach ($subordinate_not_submit_3[$row['UserID']][$sub1->UserID][$sub2->UserID]  as $not_submit_3) {
									$nik_not_ada_2=$not_submit_3->NIK;
								}

								if(isset($nik_not_ada_2)!=NULL)
								{

									if($sub2->NIK==$nik_not_ada_2)
									{
										echo '<tr>';
										echo '<td>'.$sub2->NIK.'</td>';
										echo '<td>'.$sub2->Fullname.'</td>';
										echo '<td>'.$sub2->PositionName.'</td>';
										echo '</tr>';
									}

									if(isset($nik_ada_2)!=NULL)
									{
										if($sub2->NIK!=$nik_ada_2)
										{
											if(isset($nik_not_ada_1)!=NULL)
											{
												if($nik_not_ada_2!=$sub2->NIK)
												{
													echo '<tr>';
													echo '<td>'.$sub2->NIK.'</td>';
													echo '<td>'.$sub2->Fullname.'</td>';
													echo '<td>'.$sub2->PositionName.'</td>';
													echo '</tr>';
												}
											}
											else
											{
												if($nik_not_ada_2!=$sub2->NIK)
												{
													echo '<tr>';
													echo '<td>'.$sub2->NIK.'</td>';
													echo '<td>'.$sub2->Fullname.'</td>';
													echo '<td>'.$sub2->PositionName.'</td>';
													echo '</tr>';
												}	
											}
										}
									}
									else
									{
										if($sub2->NIK!=$nik_not_ada_2)
										{

											echo '<tr>';
											echo '<td>'.$sub2->NIK.'</td>';
											echo '<td>'.$sub2->Fullname.'</td>';
											echo '<td>'.$sub2->PositionName.'</td>';
											echo '</tr>';
										}
									}
								}
								else
								{
									if(isset($nik_not_ada_2)!=NULL)
									{
										if($sub2->NIK==$nik_not_ada_2)
										{

											echo '<tr>';
											echo '<td>'.$sub2->NIK.'</td>';
											echo '<td>'.$sub2->Fullname.'</td>';
											echo '<td>'.$sub2->PositionName.'</td>';
											echo '</tr>';
										}
									}
									else
									{
										if(isset($nik_ada_2)!=NULL)
										{
											if($sub2->NIK!=$nik_ada_2)
											{

												echo '<tr>';
												echo '<td>'.$sub2->NIK.'</td>';
												echo '<td>'.$sub2->Fullname.'</td>';
												echo '<td>'.$sub2->PositionName.'</td>';
												echo '</tr>';
											}
										}
									}
								}

								if(isset($nik_ada_2)==NULL AND isset($nik_not_ada_2)==NULL)
								{
										echo '<tr>';
										echo '<td>'.$sub2->NIK.'</td>';
										echo '<td>'.$sub2->Fullname.'</td>';
										echo '<td>'.$sub2->PositionName.'</td>';
										echo '</tr>';
								}

								
								foreach ($subordinate_3[$row['UserID']][$sub1->UserID][$sub2->UserID]  as $sub3) {
										foreach ($subordinate_submit_4[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID] as $submit_4) {
											$nik_ada_3=$submit_4->NIK;
										}

										foreach ($subordinate_not_submit_4[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID]  as $not_submit_4) {
											$nik_not_ada_3=$not_submit_4->NIK;
										}


										if(isset($nik_ada_3)!=NULL)
										{
											if(isset($nik_not_ada_3)!=NULL)
											{

												if($sub3->NIK==$nik_not_ada_3)
												{

													echo '<tr>';
													echo '<td>'.$sub3->NIK.'</td>';
													echo '<td>'.$sub3->Fullname.'</td>';
													echo '<td>'.$sub3->PositionName.'</td>';
													echo '</tr>';
												}
											}

											if($sub3->NIK!=$nik_ada_3)
											{

												if(isset($nik_not_ada_3)!=NULL)
												{
													if($nik_not_ada_3!=$sub3->NIK)
													{
														echo '<tr>';
														echo '<td>'.$sub3->NIK.'</td>';
														echo '<td>'.$sub3->Fullname.'</td>';
														echo '<td>'.$sub3->PositionName.'</td>';
														echo '</tr>';
													}
												}
												else
												{
													if($nik_ada_3!=$sub3->NIK)
													{
														echo '<tr>';
														echo '<td>'.$sub3->NIK.'</td>';
														echo '<td>'.$sub3->Fullname.'</td>';
														echo '<td>'.$sub3->PositionName.'</td>';
														echo '</tr>';
													}	
												}
											}
										}
										else
										{
											if(isset($nik_not_ada_3)!=NULL)
											{
												if($sub3->NIK==$nik_not_ada_3)
												{

													echo '<tr>';
													echo '<td>'.$sub3->NIK.'</td>';
													echo '<td>'.$sub3->Fullname.'</td>';
													echo '<td>'.$sub3->PositionName.'</td>';
													echo '</tr>';
												}
											}
											else
											{
												if(isset($nik_ada_3)!=NULL)
												{
													if($sub3->NIK!=$nik_ada_3)
													{

														echo '<tr>';
														echo '<td>'.$sub3->NIK.'</td>';
														echo '<td>'.$sub3->Fullname.'</td>';
														echo '<td>'.$sub3->PositionName.'</td>';
														echo '</tr>';
													}
												}
											}
										}

										if(isset($nik_ada_3)==NULL AND isset($nik_not_ada_3)==NULL)
										{
												echo '<tr>';
												echo '<td>'.$sub3->NIK.'</td>';
												echo '<td>'.$sub3->Fullname.'</td>';
												echo '<td>'.$sub3->PositionName.'</td>';
												echo '</tr>';
										}

									foreach ($subordinate_4[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID]  as $sub4) {
										foreach ($subordinate_submit_5[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID] as $submit_5) {
											$nik_ada_4=$submit_5->NIK;
										}

										foreach ($subordinate_not_submit_5[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID]  as $not_submit_5) {
											$nik_not_ada_4=$not_submit_5->NIK;
										}


										if(isset($nik_ada_4)!=NULL)
										{
											if(isset($nik_not_ada_4)!=NULL)
											{
												if($sub4->NIK==$nik_not_ada_4)
												{

													echo '<tr>';
													echo '<td>'.$sub4->NIK.'</td>';
													echo '<td>'.$sub4->Fullname.'</td>';
													echo '<td>'.$sub4->PositionName.'</td>';
													echo '</tr>';
												}
											}

											if($sub4->NIK!=$nik_ada_4)
											{
												if(isset($nik_not_ada_4)!=NULL)
												{
													if($nik_not_ada_4!=$sub4->NIK)
													{
														echo '<tr>';
														echo '<td>'.$sub4->NIK.'</td>';
														echo '<td>'.$sub4->Fullname.'</td>';
														echo '<td>'.$sub4->PositionName.'</td>';
														echo '</tr>';
													}
												}
												else
												{
													if($nik_ada_4!=$sub4->NIK)
													{
														echo '<tr>';
														echo '<td>'.$sub4->NIK.'</td>';
														echo '<td>'.$sub4->Fullname.'</td>';
														echo '<td>'.$sub4->PositionName.'</td>';
														echo '</tr>';
													}	
												}
											}
										}
										else
										{
											if(isset($nik_not_ada_4)!=NULL)
											{
												if($sub4->NIK==$nik_not_ada_4)
												{

													echo '<tr>';
													echo '<td>'.$sub4->NIK.'</td>';
													echo '<td>'.$sub4->Fullname.'</td>';
													echo '<td>'.$sub4->PositionName.'</td>';
													echo '</tr>';
												}
											}
											else
											{
												if(isset($nik_ada_4)!=NULL)
												{
													if($sub4->NIK!=$nik_ada_4)
													{

														echo '<tr>';
														echo '<td>'.$sub4->NIK.'</td>';
														echo '<td>'.$sub4->Fullname.'</td>';
														echo '<td>'.$sub4->PositionName.'</td>';
														echo '</tr>';
													}
												}
											}
										}

										if(isset($nik_ada_4)==NULL AND isset($nik_not_ada_4)==NULL)
										{
												echo '<tr>';
												echo '<td>'.$sub4->NIK.'</td>';
												echo '<td>'.$sub4->Fullname.'</td>';
												echo '<td>'.$sub4->PositionName.'</td>';
												echo '</tr>';
										}



										foreach ($subordinate_5[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID]  as $sub5) {
											foreach ($subordinate_submit_6[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID] as $submit_6) {
												$nik_ada_5=$submit_6->NIK;
											}

											foreach ($subordinate_submit_6[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID]  as $not_submit_6) {
												$nik_not_ada_5=$not_submit_6->NIK;
											}

											if(isset($nik_ada_5)!=NULL)
											{
												if($sub5->NIK==$nik_not_ada_5)
												{

													echo '<tr>';
													echo '<td>'.$sub5->NIK.'</td>';
													echo '<td>'.$sub5->Fullname.'</td>';
													echo '<td>'.$sub5->PositionName.'</td>';
													echo '</tr>';
												}

												if($sub5->NIK!=$nik_ada_5)
												{
													if($nik_not_ada_5!=$sub5->NIK)
													{
														echo '<tr>';
														echo '<td>'.$sub5->NIK.'</td>';
														echo '<td>'.$sub5->Fullname.'</td>';
														echo '<td>'.$sub5->PositionName.'</td>';
														echo '</tr>';
													}
												}
											}
											else
											{
												if(isset($nik_not_ada_5)!=NULL)
												{
													if($sub5->NIK==$nik_not_ada_5)
													{

														echo '<tr>';
														echo '<td>'.$sub5->NIK.'</td>';
														echo '<td>'.$sub5->Fullname.'</td>';
														echo '<td>'.$sub5->PositionName.'</td>';
														echo '</tr>';
													}
												}
												else
												{
													if(isset($nik_ada_5)!=NULL)
													{
														if($sub5->NIK!=$nik_ada_5)
														{

															echo '<tr>';
															echo '<td>'.$sub5->NIK.'</td>';
															echo '<td>'.$sub5->Fullname.'</td>';
															echo '<td>'.$sub5->PositionName.'</td>';
															echo '</tr>';
														}
													}
												}
											}


											if(isset($nik_ada_5)==NULL AND isset($nik_not_ada_5)==NULL)
											{
													echo '<tr>';
													echo '<td>'.$sub5->NIK.'</td>';
													echo '<td>'.$sub5->Fullname.'</td>';
													echo '<td>'.$sub5->PositionName.'</td>';
													echo '</tr>';
											}

										foreach ($subordinate_6[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID]  as $sub6) {
											foreach ($subordinate_submit_7[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID][$sub6->UserID] as $submit_7) {
												$nik_ada_6=$submit_7->NIK;
											}

											foreach ($subordinate_submit_7[$row['UserID']][$sub1->UserID][$sub2->UserID][$sub3->UserID][$sub4->UserID][$sub5->UserID][$sub6->UserID]  as $not_submit_7) {
												$nik_not_ada_6=$not_submit_7->NIK;
											}

												if(isset($nik_ada_6)!=NULL)
												{
													if($sub6->NIK==$nik_not_ada_6)
													{

														echo '<tr>';
														echo '<td>'.$sub6->NIK.'</td>';
														echo '<td>'.$sub6->Fullname.'</td>';
														echo '<td>'.$sub6->PositionName.'</td>';
														echo '</tr>';
													}

													if($sub6->NIK!=$nik_ada_6)
													{
														if($nik_not_ada_6!=$sub6->NIK)
														{
															echo '<tr>';
															echo '<td>'.$sub6->NIK.'</td>';
															echo '<td>'.$sub6->Fullname.'</td>';
															echo '<td>'.$sub6->PositionName.'</td>';
															echo '</tr>';
														}
													}
												}
												else
												{
													if(isset($nik_not_ada_6)!=NULL)
													{
														if($sub6->NIK==$nik_not_ada_6)
														{

															echo '<tr>';
															echo '<td>'.$sub6->NIK.'</td>';
															echo '<td>'.$sub6->Fullname.'</td>';
															echo '<td>'.$sub6->PositionName.'</td>';
															echo '</tr>';
														}
													}
													else
													{
														if(isset($nik_ada_6)!=NULL)
														{
															if($sub6->NIK!=$nik_ada_6)
															{

																echo '<tr>';
																echo '<td>'.$sub6->NIK.'</td>';
																echo '<td>'.$sub6->Fullname.'</td>';
																echo '<td>'.$sub6->PositionName.'</td>';
																echo '</tr>';
															}
														}
													}
												}

												if(isset($nik_ada_6)==NULL AND isset($nik_not_ada_6)==NULL)
												{
														echo '<tr>';
														echo '<td>'.$sub6->NIK.'</td>';
														echo '<td>'.$sub6->Fullname.'</td>';
														echo '<td>'.$sub6->PositionName.'</td>';
														echo '</tr>';
												}
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
