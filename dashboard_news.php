<?php
require('top.inc.php');
isAdmin();


$color ='';
$msg = '';
if(isset($_GET['type']) && $_GET['type']!=''){
	$type=get_safe_value($con,$_GET['type']);
	if($type=='delete'){
		$id=get_safe_value($con,decrypt($_GET['id']));
        $stmt = $con->prepare("delete from posts where id= ?");
        $stmt->bind_param("i", $id);
        if($stmt->execute()){
            $color = 'success';
            $msg = "Deleted successfully";
            $stmt->close();
            header('location: dashboard_news.php');
            exit();
        }else{
            $color = 'danger';
            $msg = "Error deleting news";
        }
        
	}
}
if(isset($_POST['savedata'])){
    // Receive all input values from the form
    $title = mysqli_real_escape_string($con, $_POST["title"]);
    $stmt = $con->prepare("INSERT INTO posts (title) VALUES (?)");
    $stmt->bind_param("s", $title);
    if($stmt->execute()){
        $color = 'success';
		$msg = "News added successfully";
    }else{
        $color = 'danger';
		$msg = "Error adding new news";
    }
    $stmt->close();

}

$sql="select * from posts order by id desc";
$res=$con->query($sql);
// $stmt = $con->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW()");
// $stmt->bind_param("s", $token);
// $stmt->execute();
// $result = $stmt->get_result();
// $data = $result->fetch_assoc();
// $stmt->close();

?>

<!-- Insert Modal -->
<div class="modal fade" id="insertModal" tabindex="-1" aria-labelledby="insertModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="insertModalLabel">Add News item</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form row="g-3" method="post" id="reg" >
        <div class="modal-body row">     
        <div class="col-12">
      
          <h5>News title</h5>
          <input type="text" class="form-control" id="title" name="title" placeholder="News title" required>
          
        </div>
        <!-- <div class="col-12">
       
        <h5>Date</h5>
        <input type="date" class="form-control" id="postdate" name="postdate" placeholder="" required>

        </div>
        -->

      </div>
      
      <div class="modal-footer">
      <button type="submit" name="savedata" id="savedata" class="btn btn-primary" >Save</button>
      </div>
      </form>
    </div>
  </div>
</div>


<div class="content pb-0">
	<div class="orders">
	   <div class="row">
		  <div class="col-xl-12">
		  <div class="card shadow mb-4">
              <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                  List of News
                </h6>
              </div>
              <div class="card-body">
			  	<div class="alert alert-<?php echo $color; ?> alert-dismissible fade show" role="alert">
					<?php echo $msg; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
                <div class="mb-3">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertModal">
                    New
                    </button>
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
							   <th class="serial" width="1%">#</th>
							   <th width="4%">Post ID</th>
							   <th width="20%">Title</th>
							   <th width="20%">Post Date</th>
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
							   <td><?php echo $row['title']?></td>
							   <td><?php echo $row['post_date']?></td>
							   							  
							   <td>
								<?php
								
								$encryptedId = encrypt($row['id']);
								// if($row['status']==1){
								// 	echo "<button class='btn rounded-pill text-bg-warning' onclick=\"confirmAction('?type=status&operation=deactivate&id=".$encryptedId."')\"><i class='fa-solid fa-user-slash'></i></button>&nbsp;";
								// }else{
								// 	echo "<button class='btn rounded-pill text-bg-secondary' onclick=\"confirmAction('?type=status&operation=activate&id=".$encryptedId."')\"><i class='fa-solid fa-user-check'></i></button>&nbsp;";
								// }
								// echo "<button class='btn rounded-pill text-bg-primary'><a href='profile.php?type=view&id=".$encryptedId."' class='text-white'>Profile</a></button>&nbsp;";
								
								// echo "<button class='btn rounded-pill text-bg-danger'><i class='fa-solid fa-trash'></i><a href='?type=delete&id=".$row['id']."' class='text-white'>Delete</a></button>";
								echo "<button class='btn rounded-pill text-bg-danger' onclick=\"confirmDelete('?type=delete&id=".$encryptedId."')\"><i class='fa-solid fa-trash'></i>Delete</button>&nbsp;";
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
        title: 'Are you sure you want to delete this news?',
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