<script type="text/javascript">
	$(document).ready(function(){
		$("#genForm").validate({
			rules: {
				SlcRef:{required:true},
				<?php
				for ($i=0; $i <$subordinate_num ; $i++) { 
					for($x=0;$x<$subordinate[$i]['KPI_Num'];$x++){
						echo 'SlcGenKPI_'.$i.'_'.$x.':{required:true},';
						echo 'TxtWeight_'.$i.'_'.$x.':{required:true,number:true,max:100},';
						echo 'TxtBaseline_'.$i.'_'.$x.':{required:true,number:true},';


					}
				}
				?>
			}
		});
		$('.proposionalDiv').hide();
		$('#SlcRef').change(function(){
			if($(this).val()==3){
				$('.proposionalDiv').show();
			}else{
				$('.proposionalDiv').hide();
			}
		});
		<?php
		for ($i=0; $i <$subordinate_num ; $i++) { 
			for($x=0;$x<$subordinate[$i]['KPI_Num'];$x++){
				echo '$("#Hidden_'.$i.'_'.$x.'").hide();';

				echo '$("#SlcGenKPI_'.$i.'_'.$x.'").change(function(){';
					echo 'if($(this).val()=="other"){';
						echo '$("#Hidden_'.$i.'_'.$x.'").show();';
					echo '}else{';
						echo '$("#Hidden_'.$i.'_'.$x.'").hide();';
					echo '}';
				echo '});';

				echo "$('#checked_".$i ."_". $x."').on('switch-change', function (e, data) {";
					echo 'if (data.value == false){';
						echo '$(".month_chk_'.$i.'_'.$x.'").each(function(){$(this).prop("checked", false);});';
					echo '} else {';
						echo '$(".month_chk_'.$i.'_'.$x.'").each(function(){$(this).prop("checked", true);});';
					echo '}';
				echo '});';


			}
		}
		?>
	});
</script>
