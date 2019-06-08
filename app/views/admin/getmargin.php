<?php if (!Auth::userCan('message_users')) page_restricted(); ?>

<?php echo View::make('admin.header')->render() ?>
<?php
$users = DB::table('users')->get();


if (isset($_POST['margeNow'])) {
	$settings = DB::table('settings')->where('id', 1)->first();
	$Amount       = $_POST['amount'];
	$username     = $_POST['sendername'];
	$receivername = $_POST['receivername'];
	$sendername   = $_POST['sendername'];
	$package_id   = $_POST['package_id'];
	
    $userss = DB::table('users')->where('username', $username)->first();
	$sql = DB::table('requestMaching')->where('status', 'pending')->where('userid', $userss->id)->first();
    $senderBalance   = $sql->balance;

	if ($username =="NULL") {
		echo ' <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Oh snap!</strong> Please choose sender from the dropdown menu
            </div>';
	}else if ($Amount > $senderBalance) {
		echo ' <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Oh snap!</strong> you have set '.$username.' to pay  '.$settings->currency.''.$Amount.' and his/her pending payout balance is  '.$settings->currency.''.$senderBalance.', please input '.$settings->currency.''.$senderBalance.' in the amount input field
            </div>';
	}else{
	StartMarginReq($Amount,$username,$receivername,$sendername,$package_id,$senderBalance);
}
}


if (isset($_POST['SetMargin'])) {
	$id       = $_POST['id'];
	SettMargin($id); 
  }

if (isset($_POST['UnsetMargin'])) {
	$id       = $_POST['id'];
	UnsettMargin($id); 
  }

if (isset($_POST['delete'])) {
	$id = $_POST['id'];
 $sql = DB::table('requestHelp')->where('id', $id)->delete();
if ($sql) {
	echo '<div class="alert alert-success alert-dismissable">
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <strong>Well done!</strong> You successfully deleted user request mergin.
   </div>';
}else{
		echo ' <div class="alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <strong>Oh snap!</strong> Change a few things up and try submitting again.
            </div>';
}
} 

?>
<h3 class="page-header">
	<?php 
$marginSet = DB::table('settings')->where('id', 1)->first(); 

if ($marginSet->margintype ==1) {
	?>
 <form method="POST" action=""> 
  	<input type="hidden" name="id" value="1">
	<input type="submit" class="btn btn-success btn-lg" name="UnsetMargin" value="Manual Margin" >
	 </form>
	<?php 
}
elseif ($marginSet->margintype ==2) {
	?>
 <form method="POST" action=""> 
  	<input type="hidden" name="id" value="1">
	<input type="submit" class="btn btn-danger btn-lg" name="SetMargin" value="Auto Margin" >
	 </form>
	<?php
}
?>
	All Get Help Request

</h3>

<form action="" method="POST" id="messages_form">
	<table class="table table-striped table-bordered table-hover table-dt" id="messages">
		<thead>
			<tr>
				<th><input type="checkbox" class="select-all" value="1"></th>
				<th>Username</th>
				<th>Names</th>
				<th>Amount</th>
				<th>Profit</th>
				<th>Balance</th>
				<th>Margin Time</th>
				<th>Status</th>
				<th>Delete</th>
				<th>Action</th>
			</tr>
		</thead>

		   <?php GetMarginHelp(); ?>
		<tbody>
		</tbody>
	</table>
</form>



<?php echo View::make('admin.footer')->render() ?>