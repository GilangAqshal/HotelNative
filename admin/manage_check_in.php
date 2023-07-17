<?php 
include('db_connect.php');
	$rid = $_GET['rid'];
if(isset($_GET['id'])){
	$id = $_GET['id'];
	$qry = $conn->query("SELECT * FROM checked where id =".$id);
	if($qry->num_rows > 0){
		foreach($qry->fetch_array() as $k => $v){
			$meta[$k]=$v;
		}
	}
	$calc_days = abs(strtotime($meta['date_out']) - strtotime($meta['date_in'])) ; 
 $calc_days =floor($calc_days / (60*60*24)  );
 $cat = $conn->query("SELECT * FROM room_categories");
$cat_arr = array();
while($row = $cat->fetch_assoc()){
	$cat_arr[$row['id']] = $row;
}
}
?>
<div class="container-fluid">
	
	<form action="" id="manage-check">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<?php if(isset($_GET['id'])):
			$rooms = $conn->query("SELECT * FROM rooms where status =0 or id = $rid order by id asc");
		 ?>

		<div class="form-group">
			<label for="name">Room</label>
			<select name="rid" id="" class="custom-select browser-default">
				
				<?php while($row=$rooms->fetch_assoc()): ?>
				<option value="<?php echo $row['id'] ?>" <?php echo $row['id'] == $rid ? "selected": '' ?>><?php echo $row['room'] . " | ". ($cat_arr[$row['category_id']]['name']) ?></option>
				<?php endwhile; ?>
			</select>
			
		</div>

		<?php else: ?>
		<input type="hidden" name="rid" value="<?php echo isset($_GET['rid']) ? $_GET['rid']: '' ?>">
		<?php endif; ?>


		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="contact">Contact #</label>
			<input type="text" name="contact" id="contact" class="form-control" value="<?php echo isset($meta['contact_no']) ? $meta['contact_no']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="date_in">Check-in Date</label>
			<input type="date" name="date_in" id="date_in" class="form-control" value="<?php echo isset($meta['date_in']) ? date("Y-m-d",strtotime($meta['date_in'])): date("Y-m-d") ?>" required>
		</div>
		<div class="form-group">
			<label for="date_in_time">Check-in Date</label>
			<input type="time" name="date_in_time" id="date_in_time" class="form-control" value="<?php echo isset($meta['date_in']) ? date("H:i",strtotime($meta['date_in'])): date("H:i") ?>" required>
		</div>
		<div class="form-group">
			<label for="days">Days of Stay</label>
			<input type="number" min ="1" name="days" id="days" class="form-control" value="<?php echo isset($meta['date_in']) ? $calc_days: 1 ?>" required>
		</div>
	</form>
</div>
<script>
	$('#manage-check').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'ajax.php?action=save_check-in',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp >0){
					alert_toast("Data successfully saved",'success')
					uni_modal("Check-in Details","manage_check_out.php?id="+resp)
					setTimeout(function(){
					end_load()
					},1500)
				}
			}
		})
	})
</script>