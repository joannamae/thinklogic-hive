<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }
  if($_SESSION['access_id'] == '3' OR $_SESSION['access_id'] == '4'){
?>
  <!DOCTYPE html>
  <html lang="en">
    <?php
      include("includes/head.html");
    ?>
    <body class="nav-md" onload="startTime()">
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
                  <h3>Inbox</h3>
                </div>
                <div class="title_right">
                  <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>
                <div class="row">
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Inbox</h2>
                        
                      <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <ul class="nav nav-tabs" role="tablist">
                          <li role="presentation" class="active" ><a href="#open" aria-controls="pending" role="tab" data-toggle="tab">Open Leaves</a></li>
                          <li role="presentation"><a href="#leaves" aria-controls="pending" role="tab" data-toggle="tab">Leaves</a></li>
                        </ul>
                        <div class="tab-content">
                          <div role="tabpanel" class="tab-pane fade in active" id="open">
                            <br>
                            <div class="table-responsive">
                              <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th class="column-title no-link last">Action</th>
                                    <th class="column-title">Status</th>
                                    <th class="column-title">ID</th>
                                    <th class="column-title">Employee</th>
                                    <th class="column-title">Leave Type</th>
                                    <th class="column-title">Reason</th>
                                    <th class="column-title">Date(s)</th>
                                    <th class="column-title">No. of Hours</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      $leave_query = "SELECT * FROM leave_application WHERE leave_status='Open'";
                                      $stmt = $db->prepare($leave_query);
                                      $stmt->execute();
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                      ?>
                                        <tr>
                                          <?php $rowID = $row['leave_id']; ?>
                                          <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                          <td class="last"><a href="#" data-toggle="modal" data-target="#updateLeave<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                          <td><?php echo $row["leave_status"]; ?></td>
                                          <td><?php echo $row["leave_id"]; ?></td>
                                          <td><?php echo $row["employee_name"]; ?></td>
                                          <td><?php echo $row["leave_type"]; ?></td>
                                          <td><?php echo $row["leave_reason"]; ?></td>
                                          <td><?php echo $row["leave_date"]; ?></td>
                                          <td><?php echo $row["leave_hours"]; ?></td>
                                        </tr>
                                      <?php
                                      }
                                    ?>
                                </tbody>
                              </table>
                            </div> 
                          </div>

                          <div role="tabpanel" class="tab-pane fade in" id="leaves">
                            <br>
                            <div class="table-responsive">
                              <table id="datatable" class="table table-striped jambo_table bulk_action">
                                <thead>
                                  <tr class="headings">
                                    <th class="column-title">Status</th>
                                    <th class="column-title">ID</th>
                                    <th class="column-title">Employee</th>
                                    <th class="column-title">Leave Type</th>
                                    <th class="column-title">Reason</th>
                                    <th class="column-title">Date(s)</th>
                                    <th class="column-title">No. of Hours</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                      $leave_query = "SELECT * FROM leave_application WHERE leave_status='Approved' OR leave_status='Disapproved' ORDER BY leave_id DESC";
                                      $stmt = $db->prepare($leave_query);
                                      $stmt->execute();
                                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                      ?>
                                        <tr>
                                          <td><?php echo $row["leave_status"]; ?></td>
                                          <td><?php echo $row["leave_id"]; ?></td>
                                          <td><?php echo $row["employee_name"]; ?></td>
                                          <td><?php echo $row["leave_type"]; ?></td>
                                          <td><?php echo $row["leave_reason"]; ?></td>
                                          <td><?php echo $row["leave_date"]; ?></td>
                                          <td><?php echo $row["leave_hours"]; ?></td>
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
                  </div>
                </div>

              <!-- FOR OPEN TAB-->
              <!-- PHP Code for updating leave status -->
              <?php
                if(isset($_POST['approveleave'])){
                  $leave_id = $_POST["leave_id"];
                  $date_updated = $_POST["update_date"];
                  $updated_by = $_POST["updated_by"];
                  $stmt1 = $db->prepare("UPDATE leave_application SET leave_status='Approved', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE leave_id=$leave_id");
                  $stmt1->execute();

                  // Notification
                  $stmt6 = $db->prepare("INSERT INTO notifications (leave_id) VALUES (:Leave_ID)");
                  $stmt6->bindParam(':Leave_ID', $leave_id);
                  $stmt6->execute();

                  // Update Leave Credits
                  $employee_id = $_POST["employee_id"];
                  $leave_type = $_POST["leave_type"];
                  $leave_hours = $_POST["leave_hours"];

                  // Sick Leave
                  if ($leave_type == 'Sick'){    
                    $sl_query = "SELECT * FROM leave_credits WHERE employee_id=:employee_id AND leave_type='Sick'";
                    $stmt4 = $db->prepare($sl_query);
                    $stmt4->execute(array('employee_id' => $employee_id));
                    $row = $stmt4->fetch(PDO::FETCH_ASSOC);
                    $leave = $row['leave_type'];
                    $sl_credits = $row['leave_credits'];

                    $sl_credits = $sl_credits - $leave_hours;

                    $stmt5 = $db->prepare("UPDATE leave_credits SET leave_credits=$sl_credits WHERE employee_id=$employee_id AND leave_type='Sick'");
                    $stmt5->execute();
                  }

                  // Vacation Leave
                  if ($leave_type == 'Vacation'){
                    $vl_query = "SELECT * FROM leave_credits WHERE employee_id=:employee_id AND leave_type='Vacation'";
                    $stmt2 = $db->prepare($vl_query);
                    $stmt2->execute(array('employee_id' => $employee_id));
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $vl_credits = $row['leave_credits'];

                    $vl_credits = $vl_credits - $leave_hours;

                    $stmt3 = $db->prepare("UPDATE leave_credits SET leave_credits=$vl_credits WHERE employee_id=$employee_id AND leave_type='Vacation'");
                    $stmt3->execute();
                  }
                  
                  echo "<meta http-equiv='refresh' content='0'>";
                }

                if(isset($_POST['disapproveleave'])){
                  $leave_id = $_POST["leave_id"];
                  $update_date = $_POST["update_date"];
                  $updated_by = $_POST["updated_by"];
                  $stmt1 = $db->prepare("UPDATE leave_application SET leave_status='Disapproved', date_updated='".$update_date."', updated_by='".$updated_by."' WHERE leave_id=$leave_id");
                  $stmt1->execute();

                  // Notification
                  $stmt6 = $db->prepare("INSERT INTO notifications (leave_id) VALUES (:Leave_ID)");
                  $stmt6->bindParam(':Leave_ID', $leave_id);
                  $stmt6->execute();

                  echo "<meta http-equiv='refresh' content='0'>";
                }
              ?>

              <!-- Modal for Creative Team Leave Applications --> 
              <?php
                $leave_query = "SELECT * FROM leave_application WHERE leave_status='Open'";
                $stmt = $db->prepare($leave_query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                  <!-- Edit Modal -->
                  <div class="modal fade bs-example-modal-md" id="updateLeave<?php echo $row['leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Update Leave Application</h4>
                        </div>
                        <div class="modal-body">
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Application ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_id"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Status
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_status" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_status"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Employee ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="employee_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["employee_id"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Employee Name
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="employee_name" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["employee_name"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Type
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_type" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_type"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Reason
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea class="form-control" style="" name="leave_reason" class="form-control col-md-7 col-xs-12" readonly="readonly"><?php echo $row["leave_reason"]?></textarea>
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Date
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_date" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_date"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Hours
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_hours" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_hours"]?>" />
                            </div>
                            </div>
                            <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="update_date" />
                            <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"] ?>" name="updated_by" />
                            <div class="ln_solid"></div>
                            <center>
                              <button type="submit" class="btn btn-success" name="approveleave">Approve</button>
                              <button type="submit" class="btn btn-danger" name="disapproveleave">Disapprove</button>
                            </center>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php 
                }
              ?>

              <div class="clearfix"></div>
            </div>
          </div>
          <!-- /page content -->
        <?php
        include("includes/body-foot.html");
        ?>
        </div>
      </div>
    </body>
    <footer>
      <?php
        include("includes/foot.html");
      ?>
    </footer>
  </html>
<?php
  }
  else if($_SESSION['access_id'] == '2'){
?>    
  <!DOCTYPE html>
  <html lang="en">
    <?php
      include("includes/head.html");
    ?>
    <body class="nav-md" onload="startTime()">
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
                  <h3>Inbox</h3>
                </div>
                <div class="title_right">
                  <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                    
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>
                <div class="row">
                  <div class="clearfix"></div>
                  <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                    <div class="x_panel">
                      <div class="x_title">
                        <h2>Inbox</h2>
                      
                      <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                        <div class="table-responsive">
                          <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                            <thead>
                              <tr class="headings">
                                <th class="column-title no-link last">Action</th>
                                <th class="column-title">Status</th>
                                <th class="column-title">ID</th>
                                <th class="column-title">Employee</th>
                                <th class="column-title">Leave Type</th>
                                <th class="column-title">Reason</th>
                                <th class="column-title">Date(s)</th>
                                <th class="column-title">No. of Hours</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                                  $leave_query = "SELECT * FROM leave_application WHERE leave_status='Open' AND leave_team='".$_SESSION['team_name']."'";
                                  $stmt = $db->prepare($leave_query);
                                  $stmt->execute();
                                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  ?>
                                    <tr>
                                      <?php $rowID = $row['leave_id']; ?>
                                      <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                      <td class="last"><a href="#" data-toggle="modal" data-target="#updateLeave<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                      <td><?php echo $row["leave_status"]; ?></td>
                                      <td><?php echo $row["leave_id"]; ?></td>
                                      <td><?php echo $row["employee_name"]; ?></td>
                                      <td><?php echo $row["leave_type"]; ?></td>
                                      <td><?php echo $row["leave_reason"]; ?></td>
                                      <td><?php echo $row["leave_date"]; ?></td>
                                      <td><?php echo $row["leave_hours"]; ?></td>
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

              <!-- FOR OPEN TAB-->
              <!-- PHP Code for updating leave status -->
              <?php
                if(isset($_POST['approveleave'])){
                  $leave_id = $_POST["leave_id"];
                  $date_updated = $_POST["update_date"];
                  $updated_by = $_POST["updated_by"];
                  $stmt1 = $db->prepare("UPDATE leave_application SET leave_status='Approved', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE leave_id=$leave_id");
                  $stmt1->execute();

                  // Notification
                  $stmt6 = $db->prepare("INSERT INTO notifications (leave_id) VALUES (:Leave_ID)");
                  $stmt6->bindParam(':Leave_ID', $leave_id);
                  $stmt6->execute();

                  // Update Leave Credits
                  $employee_id = $_POST["employee_id"];
                  $leave_type = $_POST["leave_type"];
                  $leave_hours = $_POST["leave_hours"];

                  // Sick Leave
                  if ($leave_type == 'Sick'){    
                    $sl_query = "SELECT * FROM leave_credits WHERE employee_id=:employee_id AND leave_type='Sick'";
                    $stmt4 = $db->prepare($sl_query);
                    $stmt4->execute(array('employee_id' => $employee_id));
                    $row = $stmt4->fetch(PDO::FETCH_ASSOC);
                    $leave = $row['leave_type'];
                    $sl_credits = $row['leave_credits'];

                    $sl_credits = $sl_credits - $leave_hours;

                    $stmt5 = $db->prepare("UPDATE leave_credits SET leave_credits=$sl_credits WHERE employee_id=$employee_id AND leave_type='Sick'");
                    $stmt5->execute();
                  }

                  // Vacation Leave
                  if ($leave_type == 'Vacation'){
                    $vl_query = "SELECT * FROM leave_credits WHERE employee_id=:employee_id AND leave_type='Vacation'";
                    $stmt2 = $db->prepare($vl_query);
                    $stmt2->execute(array('employee_id' => $employee_id));
                    $row = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $vl_credits = $row['leave_credits'];

                    $vl_credits = $vl_credits - $leave_hours;

                    $stmt3 = $db->prepare("UPDATE leave_credits SET leave_credits=$vl_credits WHERE employee_id=$employee_id AND leave_type='Vacation'");
                    $stmt3->execute();
                  }
                  
                  echo "<meta http-equiv='refresh' content='0'>";
                }

                if(isset($_POST['disapproveleave'])){
                  $leave_id = $_POST["leave_id"];
                  $update_date = $_POST["update_date"];
                  $updated_by = $_POST["updated_by"];
                  $stmt1 = $db->prepare("UPDATE leave_application SET leave_status='Disapproved', date_update='".$date_update."', updated_by='".$updated_by."' WHERE leave_id=$leave_id");
                  $stmt1->execute();

                  // Notification
                  $stmt6 = $db->prepare("INSERT INTO notifications (leave_id) VALUES (:Leave_ID)");
                  $stmt6->bindParam(':Leave_ID', $leave_id);
                  $stmt6->execute();

                  echo "<meta http-equiv='refresh' content='0'>";
                }
              ?>

              <!-- Modal for Creative Team Leave Applications --> 
              <?php
                $leave_query = "SELECT * FROM leave_application WHERE leave_status='Open' AND leave_team='".$_SESSION['team_name']."'";
                $stmt = $db->prepare($leave_query);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                  <!-- Edit Modal -->
                  <div class="modal fade bs-example-modal-md" id="updateLeave<?php echo $row['leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog modal-md" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Update Leave Application</h4>
                        </div>
                        <div class="modal-body">
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Application ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_id"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Status
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_status" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_status"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Employee ID
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="employee_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["employee_id"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Employee Name
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="employee_name" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["employee_name"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Type
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_type" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_type"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Reason
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_reason" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_reason"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Date
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_date" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_date"]?>" />
                            </div>
                            </div>
                            <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Hours
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                            <input type="text" name="leave_hours" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["leave_hours"]?>" />
                            </div>
                            </div>
                            <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="update_date" />
                            <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"] ?>" name="updated_by" />
                            <div class="ln_solid"></div>
                            <center>
                              <button type="submit" class="btn btn-success" name="approveleave">Approve</button>
                              <button type="submit" class="btn btn-danger" name="disapproveleave">Disapprove</button>
                            </center>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php 
                }
              ?>

              <div class="clearfix"></div>
            </div>
          </div>
          <!-- /page content -->
        <?php
        include("includes/body-foot.html");
        ?>
        </div>
      </div>
    </body>
    <footer>
      <?php
        include("includes/foot.html");
      ?>
    </footer>
  </html>
<?php
  }
?>