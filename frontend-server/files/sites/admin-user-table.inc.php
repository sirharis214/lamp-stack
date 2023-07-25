<?php
if(isset($_SESSION['table_data']) AND $_SESSION['table_data']==true): 
	foreach($data as $row):
		echo '
			<tr>
				<td>' . $row['id'] . '</td>
				<td>' . $row['username'] . '</td>
				<td>' . $row['email'] . '</td>
				<td>' . $row['role'] . '</td>
				<td>' . $row['created_on'] . '</td>
				<td>' . $row['updated_on'] . '</td>
				<td><button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#userDetailsModal'.$row['id'].'">Update</button></td>
			</tr>
		';
	?>
	<!-- Modal -->
	<div class="modal fade" <?php echo "id='userDetailsModal".$row['id']."'";?> tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content">
		  <div class="modal-header">
		    <h1 class="modal-title fs-5" id="userDetailsModalLabel">Update User</h1>
		    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		  </div><!-- modal-header-->
		  <div class="modal-body">
		    <form action="../../php/client.php" method="POST" name="update-user-form">
		      <div class="row mb-3">
		        <div class="col">
		          <label for="username" class="form-label">Username</label>
		          <input type="text" class="form-control" id="username" name="username" <?php echo"value='".$row['username']."'";?> required>
		          <div name="usernameFeedback" class="form-text"></div>
		        </div>
		        <div class="col">
		          <label for="email" class="form-label">Email</label>
		          <input type="email" class="form-control" name="email" <?php echo"value='".$row['email']."'";?> required>
		        </div>
		        <div class="col">
		          <label for="role" class="form-label">Role</label>
		          <select class="form-select" name="role" required>
		          	<?php $roles = ["read_only", "admin", "power_user"];
		          	foreach ($roles as $role ):	
						if( $role == $row['role'] ):
							echo "<option selected value='" .$role. "'>" .ucfirst($role). "</option>";
						else:
							echo "<option value='" .$role. "'>" .ucfirst($role). "</option>";
						endif;
					endforeach;
		            ?>
		          </select>
		        </div>
		      </div>
		      <div class="row mb-3">
		        <div class="col">
		          <label class="form-label">Created On</label>
		          <input type="text" class="form-control" <?php echo "value='" .$row['created_on']. "'";?> readonly>
		        </div>
		        <div class="col">
		          <label class="form-label">Updated On</label>
		          <input type="text" class="form-control" <?php echo "value='" .$row['updated_on']. "'";?> readonly>
		        </div>
		      </div>
		      <div class="modal-footer">
		      	<input type="hidden" name="id" <?php echo "value='".$row['id']."'";?> >
		      	<input type="hidden" name="action" value="update-user">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary" name="update-user">Save changes</button>
			  </div> <!-- modal-footer-->
		    </form>	    
		  </div> <!-- modal-body-->
		</div><!-- modal-content-->
	  </div><!-- modal-dialog -->
	</div><!-- modal fade -->
	<?php	
	endforeach;
else:
	echo '
		<tr>
			<td>0</td>
			<td>test.test</td>
			<td>test@test.com</td>
			<td>read_only</td>
			<td>x/x/x x:x:x</td>
			<td>N/A</td>
		</tr>  
	';
endif;
?>

