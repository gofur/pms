
<script type="text/javascript">
$('#checked').on('switch-change', function (e, data) {

  if (data.value == false )
  {
  	$(".month_chk").each(function(){
    	$(this).attr('checked', false);
		});
  }
  else
  {
  	$(".month_chk").each(function(){
    	$(this).attr('checked', true);
		});
  }
	
 });

</script>