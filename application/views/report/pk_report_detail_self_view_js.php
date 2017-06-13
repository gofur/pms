<script type="text/javascript">


	function printdiv(divName) 
	{
	     var printContents = document.getElementById(divName).innerHTML;
	     var originalContents = document.body.innerHTML;
	     document.body.innerHTML = printContents;
	     window.print();
	     document.body.innerHTML = originalContents;
	}	

if(<?php echo $total_agree ?> == 0)
{
	$('#id_agree').show();
}
else
{
	$('#id_agree').hide();	
}

</script>
