
<script type="text/javascript">
$('#checked_<?php echo $num_code?>').on('switch-change', function (e, data) {

  if (data.value == false )
  {
  	$(".month_chk_<?php echo $num_code?>").each(function(){
    	$(this).attr('checked', false);
		});
  }
  else
  {
  	$(".month_chk_<?php echo $num_code?>").each(function(){
    	$(this).attr('checked', true);
		});
  }
	
 });

</script>