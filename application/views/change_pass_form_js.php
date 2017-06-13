<script type="text/javascript">
	$(document).ready(
		function()
		{
			$("#changePasswordForm").validate(
			{
				rules: {
					TxtOldPassword:{required:true},
					TxtNewPassword:{required:true},
					TxtReNewPassword:{required:true,equalTo:"#TxtNewPassword"}
				}
			})
		}
	)
</script>