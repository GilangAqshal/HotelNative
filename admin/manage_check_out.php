<?php 
include('db_connect.php');
if($_GET['id']){
	$id = $_GET['id'];
	$qry = $conn->query("SELECT * FROM checked where id =".$id);
	if($qry->num_rows > 0){
		foreach($qry->fetch_array() as $k => $v){
			$$k=$v;
		}
	}
	if($room_id > 0){
	$room = $conn->query("SELECT * FROM rooms where id =".$room_id)->fetch_array();
	$cat = $conn->query("SELECT * FROM room_categories where id =".$room['category_id'])->fetch_array();
}else{
	$cat = $conn->query("SELECT * FROM room_categories where id =".$booked_cid)->fetch_array();

}
 $calc_days = abs(strtotime($date_out) - strtotime($date_in)) ; 
 $calc_days =floor($calc_days / (60*60*24)  );
}
?>
<style>
	.container-fluid p{
		margin: unset
	}
	#uni_modal .modal-footer{
		display: none;
	}
</style>
<div class="container-fluid">
	<p><b>Room : </b><?php echo isset($room['room']) ? $room['room'] : 'NA' ?></p>
	<p><b>Room Category : </b><?php echo $cat['name'] ?></p>
	<p><b>Room Price : </b><?php echo '$'.number_format($cat['price'],2) ?></p>
	<p><b>Reference no : </b><?php echo $ref_no ?></p>
	<p><b>Checked In : </b><?php echo $name ?></p>
	<p><b>Contact no : </b><?php echo $contact_no ?></p>
	<p><b>Check-in Date/Time : </b><?php echo date("M d, Y h:i A",strtotime($date_in)) ?></p>
	<p><b>Check-out Date/Time : </b><?php echo date("M d, Y h:i A",strtotime($date_out)) ?></p>
	<p><b>Days : </b><?php echo $calc_days ?></p>
	<p><b>Amount (Price * Days) : </b><?php echo '$'.number_format($cat['price'] * $calc_days ,2) ?></p>
	
		<div class="row">
			<?php if(isset($_GET['checkout']) && $status != 2): ?>
				<div class="col-md-3">
					<button type="button" class="btn btn-primary" id="checkout">Checkout</button>
				</div>
				<div class="col-md-3">
					<button type="button" class="btn btn-primary" id="edit_checkin">Edit</button>
				</div>
		<?php endif; ?>	
				<div class="col-md-3">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
		
		</div>
</div>
<script>
	$(document).ready(function(){
		
	})
	$('#edit_checkin').click(function(){
		uni_modal("Edit Check In","manage_check_in.php?id=<?php echo $id ?>&rid=<?php echo $room_id ?>")
	})
	$('#checkout').click(function(){
		start_load()
		$.ajax({
			url:'ajax.php?action=save_checkout',
			method:'POST',
			data:{id:'<?php echo $id ?>',rid:'<?php echo $room_id ?>'},
			success:function(resp){
				if(resp ==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	})
</script>