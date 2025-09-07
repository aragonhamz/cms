<?php
require('top.inc.php');
isAdmin();


$color ='';
$msg = '';
if(isset($_GET['type']) && $_GET['type']!=''){
	//$decryptid = decrypt($_GET['id']);
	$type=get_safe_value($con,$_GET['type']);
	if($type=='status'){
		$operation=get_safe_value($con,$_GET['operation']);
		
		$id=get_safe_value($con,decrypt($_GET['id']));
		if($operation=='activate'){
			$status='1';
		}else{
			$status='0';
		}
		$update_status_sql="update users set status='$status' where id='$id'";
		//mysqli_query($con,$update_status_sql);
		if($con->query($update_status_sql)==true){
			$color = 'success';
			$msg = "User has been '". $operation . "' successfully";
		}else{
			$msg = "Error '". $operation. "' user";
			$color = 'danger';
		}
	}
	
	if($type=='delete'){
		$id=get_safe_value($con,decrypt($_GET['id']));
		$delete_sql="delete from coupon_master where id='$id'";
		// if($con->query($delete_sql)==true){
		// 	$color = 'success';
		// 	$msg = "User has been deleted successfully";
		// }else{
		// 	$msg = "Error deleting user";
		// 	$color = 'danger';
		// }
	}
}

$sql="select * from users order by id desc";
$res=$con->query($sql);
?>
<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
		  <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  List of Registered users
                </h6>
              </div>
              <div class="card-body">
			  	<div class="alert alert-<?php echo $color; ?> alert-dismissible fade show" role="alert">
					<?php echo $msg; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
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
							   <th width="2%">ID</th>
							   <th width="20%">Name</th>
							   <th width="20%">Email</th>
							   <th width="20%">Role</th>
							   <th width="10%">Mobile</th>
							   <th width="26%"></th>
							</tr>
						 </thead>
						 <tbody>
							<?php 
							$i=1;
							while($row=mysqli_fetch_assoc($res)){?>
							<tr>
							   <td class="serial"><?php echo $i?></td>
							   <td><?php echo $row['id']?></td>
							   <td><?php echo $row['name']?></td>
							   <td><?php echo $row['email']?></td>
							   <td><?php echo $row['role']?></td>
							   <td><?php echo $row['mobile']?></td>
							  
							   <td>
								<?php
								// if($row['status']==1){
								// 	echo "<button class='btn rounded-pill text-bg-warning'><a href='?type=status&operation=deactivate&id=".$row['id']."' class='text-white'>Deactivate</a></button>&nbsp;";
								// }else{
								// 	echo "<button class='btn rounded-pill text-bg-secondary' id='statusbtn'><a href='?type=status&operation=activate&id=".$row['id']."' class='text-white' onclick='confirmSubmit()'>Activate</a></button>&nbsp;";
								// }
								// echo "<button class='btn rounded-pill text-bg-primary'><a href='?id=".$row['id']."' class='text-white'>Profile</a></button>&nbsp;";
								
								// echo "<button class='btn rounded-pill text-bg-danger'><i class='fa-solid fa-trash'></i><a href='?type=delete&id=".$row['id']."' class='text-white'>Delete</a></button>";
								$encryptedId = encrypt($row['id']);
								if($row['status']==1){
									echo "<button class='btn rounded-pill text-bg-warning' onclick=\"confirmAction('?type=status&operation=deactivate&id=".$encryptedId."')\"><i class='fa-solid fa-user-slash'></i></button>&nbsp;";
								}else{
									echo "<button class='btn rounded-pill text-bg-secondary' onclick=\"confirmAction('?type=status&operation=activate&id=".$encryptedId."')\"><i class='fa-solid fa-user-check'></i></button>&nbsp;";
								}
								echo "<button class='btn rounded-pill text-bg-primary'><a href='profile.php?type=view&id=".$encryptedId."' class='text-white'>Profile</a></button>&nbsp;";
								
								// echo "<button class='btn rounded-pill text-bg-danger'><i class='fa-solid fa-trash'></i><a href='?type=delete&id=".$row['id']."' class='text-white'>Delete</a></button>";
								echo "<button class='btn rounded-pill text-bg-danger' onclick=\"confirmDelete('?type=delete&id=".$row['id']."')\"><i class='fa-solid fa-trash'></i>Delete</button>&nbsp;";
								 ?>
							   </td>
							</tr>
							<?php } ?>
						 </tbody>
					  </table>
				   </div>
				</div>
			 </div>
		  </div>
	   </div>
	</div>
</div>
<script>
// function confirmAction(url) {
//     if (confirm("Are you sure you want to Activate/Deactivate this user?")) {
//         window.location.href = url;
//     }
// }
function confirmAction(url) {
    Swal.fire({
        title: 'Are you sure you want to Activate/Deactivate this user?',
        text: "",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

function confirmDelete(url) {
    Swal.fire({
        title: 'Are you sure you want to delete this user?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
</script>
<?php
require('footer.inc.php');
?>