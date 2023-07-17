<?php include('db_connect.php'); ?>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="container-fluid">
							<div class="col-md-12">
								<form id="filter">
									<div class="row">
										<div class=" col-md-4">
											<label class="control-label">Category</label>
											<select class="custom-select browser-default" name="category_id">
												<option value="all" <?php echo isset($_GET['category_id']) && $_GET['category_id'] == 'all' ? 'selected' : '' ?>>All</option>
												<?php 
												$cat = $conn->query("SELECT * FROM room_categories order by name asc ");
												while($row= $cat->fetch_assoc()) {
													$cat_name[$row['id']] = $row['name'];
													?>
													<option value="<?php echo $row['id'] ?>" <?php echo isset($_GET['category_id']) && $_GET['category_id'] == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
												<?php
												}
												?>
											</select>
										</div> 
										<div class="col-md-2">
											<label for="" class="control-label">&nbsp</label>
											<button class="btn btn-block btn-primary">Filter</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered">
							<thead>
								<th>#</th>
								<th>Category</th>
								<th>Room</th>
								<th>Status</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$where = '';
								if(isset($_GET['category_id']) && !empty($_GET['category_id'])  && $_GET['category_id'] != 'all'){
									$where .= " where category_id = '".$_GET['category_id']."' ";
								}
									if(empty($where))
										$where .= " where status = '0' ";
									else
										$where .= " and status = '0' ";
								$rooms = $conn->query("SELECT * FROM rooms ".$where." order by id asc");
								while($row=$rooms->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="text-center"><?php echo $cat_name[$row['category_id']] ?></td>
									<td class=""><?php echo $row['room'] ?></td>
									<?php if($row['status'] == 0): ?>
										<td class="text-center"><span class="badge badge-success">Available</span></td>
									<?php else: ?>
										<td class="text-center"><span class="badge badge-default">Unavailable</span></td>
									<?php endif; ?>
									<td class="text-center">
											<button class="btn btn-sm btn-primary check_in" type="button" data-id="<?php echo $row['id'] ?>">Check-in</button>
									</td>
								</tr>
							<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('table').dataTable()
	$('.check_in').click(function(){
		uni_modal("Check In","manage_check_in.php?rid="+$(this).attr("data-id"))
	})
	$('#filter').submit(function(e){
		e.preventDefault()
		location.replace('index.php?page=check_in&category_id='+$(this).find('[name="category_id"]').val()+'&status='+$(this).find('[name="status"]').val())
	})
</script>