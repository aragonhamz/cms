<?php
require('top.inc.php');

isAdmin();
if(isset($_GET['type']) && $_GET['type']!=''){
	$type=get_safe_value($con,$_GET['type']);
	if($type=='delete'){
		$id=get_safe_value($con,$_GET['id']);
		$delete_sql="delete from tbl_member where id='$id'";
		mysqli_query($con,$delete_sql);
	}
}


$sql="select * from regcard order by reg_id asc";
$res=mysqli_query($con,$sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
			      <!-- DataTales Example -->
			<div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  Registration Cards
                </h6>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table
                    class="table table-bordered"
                    id="dataTable"
                    width="100%"
                    cellspacing="0"
                  >
						 <thead>
							<tr>
							   <th class="serial">#</th>
							   
							   <th>Name</th>
							   <th>Email</th>
							   <th>Mobile NO.</th>
							   <th>Gender</th>
							   <th>Father's Name</th>
							   <th>Date of birth</th>
							   <th>Applied on</th>
							   <th></th>
							   <th></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while($row=mysqli_fetch_assoc($res)){?>
							<tr>
							   <td class="serial"><?php echo $i?></td>
							   <!-- <td><?php echo $row['reg_id']?></td> -->
							   <td><?php echo $row['name']?></td>
							   <td><?php echo $row['email']?></td>
							   <td><?php echo $row['contact']?></td>
							   <td><?php echo $row['gender']?></td>
							   <td><?php echo $row['father']?></td>
							   <td><?php echo $row['dob']?></td>
							   <td><?php echo $row['reg_on']?></td>
							   <td>
								<?php
								echo "<button class='btn btn-success rounded-pill'><a href='uploads/".$row['file']."' class='text-light'>View</a></button>";
								?>
							   </td>
							   <td>
								<?php
								echo "<button class='btn btn-info rounded-pill'><a href='uploads/".$row['file']."' class='text-light'>Approve</a></button>";
								?>
							   </td>
							</tr>
							<?php $i++; } ?>
						 </tbody>
					  </table>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<?php
require('footer.inc.php');
?>