<!-- Employees.php -->

<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    include("includes/head.html");
  ?>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
          include("includes/body-nav.html");
        ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Access Registry</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <?php
              if(isset($_POST['addaccess'])){
                $stmt = $db->prepare("INSERT INTO access_registry (employee_id, access_id, username, password) VALUES (:Employee_Id, :Access_ID, :Username, :Password)");

                $stmt->bindParam(':Employee_Id', $Employee_Id);
                $stmt->bindParam(':Access_ID', $Access_ID);
                $stmt->bindParam(':Username', $Username);
                $stmt->bindParam(':Password', $Password);

                $Employee_Id = $_POST['employee_id'];
                $Access_ID = $_POST['access_id'];
                $Username = $_POST['username'];
                $Password = md5($_POST['password']);

                $stmt->execute();
                ?>
                  <script>window.location.href="accessregistry.php"</script>
                <?php
              }
          ?>

            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                      New Access
                    </button>

                    <!-- Modal -->
                    <div class="modal fade bs-example-modal-md" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Access Information</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                                <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Employee <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="employee_id">
                                <option disabled selected></option>
                                  <?php
                                    $employee_query = "SELECT DISTINCT * 
                                    					FROM employees
                                    					WHERE employee_id NOT IN(SELECT employee_id FROM access_registry)
                                    					";
                                    $stmt = $db->prepare($employee_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <option value="<?php echo $row['employee_id']; ?>"><?php echo $row['first_name'] . " " . $row['last_name'] ?></option>
                                    <?php
                                    }
                                  ?>
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Access Type <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <select class="select2_single form-control" tabindex="-1" required name="access_id">
                                <option disabled selected></option>
                                <option value="1">User</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Manager</option>
                                <option value="4">Administrator</option>
                                </select>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Username  <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="username" required/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Password <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text"  name="password" required/>
                                </div>
                                </div>
                                <br>
                                <center>
                                    <input type="submit" value="Add Access" class="btn btn-primary" name="addaccess" />
                                </center>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Access Registry</h2>
                    
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                            <th class="column-title no-link last">Action</th>
                            <th class="column-title">Employee ID</th>
                            <th class="column-title">Access ID</th>
                            <th class="column-title">Username</th>
                            <th class="column-title">Password</th>
                          </tr>
                        </thead>

                        <tbody>
                          <?php
                              $access_query = "SELECT * FROM access_registry";
                              $tbl_stmt = $db->prepare($access_query);
                              $tbl_stmt->execute();
                              while ($row = $tbl_stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <?php $rowID = $row['employee_id']; ?>
                                  <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                  <td class="last"><a href="#" data-toggle="modal" data-target="#editAccess<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                  <td><?php echo $row["employee_id"]; ?></td>
                                  <td><?php echo $row["access_id"]; ?></td>
                                  <td><?php echo $row["username"]; ?></td>
                                  <td><?php echo $row["password"]; ?></td>
                                </tr>
                                 <?php
                              }
                            ?> 
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Modal for Updating Access --> 
            <?php
              $access_query = "SELECT * FROM access_registry";
              $stmt = $db->prepare($access_query);
              $stmt->execute();
              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                <!-- Edit Modal -->
                <div class="modal fade bs-example-modal-md" id="editAccess<?php echo $row['employee_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Update Employee Access</h4>
                      </div>
                      <div class="modal-body">
                        <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Employee ID
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="employee_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["employee_id"]?>" />
                          </div>
                          </div>
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Access ID
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <select id="ticket_team" class="select2_single form-control" tabindex="-1" name="access_id" required>
                            <option disabled selected></option>
                            <option value="1" <?php if($row['access_id'] == '1') echo 'selected'; ?>>User</option>
                            <option value="2" <?php if($row['access_id'] == '2') echo 'selected'; ?>>Supervisor</option>
                            <option value="3" <?php if($row['access_id'] == '3') echo 'selected'; ?>>Manager</option>
                            <option value="4" <?php if($row['access_id'] == '4') echo 'selected'; ?>>Administrator</option>
                          </select>
                          </div>
                          </div>
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Username
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="username" class="form-control col-md-7 col-xs-12" value="<?php echo $row["username"]?>" />
                          </div>
                          </div>
                          <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Password
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <input type="text" name="password" class="form-control col-md-7 col-xs-12" value="<?php echo $row["password"]?>" />
                          </div>
                          </div>
                          <div class="ln_solid"></div>
                          <center>
                            <!--<input type="submit" value="Approve" class="btn btn-primary" name="approveleave" />-->
                            <button type="submit" class="btn btn-success" name="updateaccess">Update</button>
                            <button type="submit" class="btn btn-danger">Cancel</button>
                          </center>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              <?php 
              }
            ?>

            <!-- PHP Code for updating ticket status -->
            <?php
              if(isset($_POST['updateaccess'])){
                $stmt = $db->prepare("UPDATE access_registry SET access_id=?, username=?, password=? WHERE employee_id=?");
                $stmt->bindParam(1,$access_id);
                $stmt->bindParam(2,$username);
                $stmt->bindParam(3,$password);
                $stmt->bindParam(4,$employee_id);

                $access_id = $_POST['access_id'];
                $username = $_POST['username'];
                $password = md5($_POST['password']);
                $employee_id = $_POST['employee_id'];

                $stmt->execute();
                
                echo "<meta http-equiv='refresh' content='0'>";
              }
            ?>

          </div>
        </div>
        <!-- /page content -->
        <?php
          include("includes/body-foot.html");
        ?>
      </div>
    </div>
    </div>
    
  </body>
  <footer>
        <?php
          include("includes/foot.html");
        ?>
  </footer>
</html>