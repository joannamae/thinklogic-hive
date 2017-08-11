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
                <h3>Leave Tracking</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>
            <!-- Ticket Tables -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    
                    <div class="clearfix"></div>
                  </div>
                  <?php 
                    if(isset($_POST['save_edit']))
                    {
                      $stmt = $db->prepare("UPDATE leave_application SET leave_type=?, leave_date=?, leave_hours=?, leave_reason=?, updated_by=?, date_updated=? WHERE leave_id=?");
                      $stmt->bindParam(1,$_POST['leave_type']);
                      $stmt->bindParam(2,$_POST['leave_date']);
                      $stmt->bindParam(3,$_POST['leave_hours']);
                      $stmt->bindParam(4,$_POST['leave_reason']);
                      $stmt->bindParam(5,$_POST['leave_employee']);
                      $stmt->bindParam(6,$_POST['update_date']);
                      $stmt->bindParam(7,$_POST['leave_id']);
                      $stmt->execute();
                   ?>
                      <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Thank you!</strong> Leave application successfully updated.
                      </div>
                      <?php
                    }
                    if(isset($_POST['cancelleave'])){
                      $leave_id = $_POST["leave_id"];
                      $leave_employee = $_POST["leave_employee"];
                      $update_date = $_POST["update_date"];
                      $stmt1 = $db->prepare("UPDATE leave_application SET leave_status='Cancelled', date_updated='".$update_date."', updated_by='".$leave_employee."' WHERE leave_id=$leave_id");
                      $stmt1->execute();
                    ?> 
                      <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Thank you!</strong> Leave application successfully cancelled.
                      </div>
                      <?php
                    }
                  ?>
                  <div class="x_content">

                    <div>
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" ><a href="#open" aria-controls="pending" role="tab" data-toggle="tab">Open Leaves</a></li>
                        <li role="presentation"><a href="#approved" aria-controls="messages" role="tab" data-toggle="tab">Approved Leaves</a></li>
                        <li role="presentation"><a href="#disapproved" aria-controls="messages" role="tab" data-toggle="tab">Disapproved Leaves</a></li>
                        <li role="presentation"><a href="#cancelled" aria-controls="messages" role="tab" data-toggle="tab">Cancelled</a></li>
                      </ul>
                      <!-- Tab panes -->
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="open">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
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
                                    $ticket_query = "SELECT * FROM leave_application WHERE leave_status='Open' and employee_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['leave_id']; ?>
                                        <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                        <td class="last"><a href="#" data-toggle="modal" data-target="#editModal<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                        <td><?php echo $row["leave_status"]; ?></td>
                                        <td><?php echo $row["leave_id"]; ?></td>
                                        <td><?php echo $row["employee_id"]; ?></td>
                                        <td><?php echo $row["leave_type"]; ?></td>
                                        <td><?php echo $row["leave_reason"]; ?></td>
                                        <td><?php echo $row["leave_date"]; ?></td>
                                        <td><?php echo $row["leave_hours"]; ?></td>
                                      </tr>
                                      <!-- Edit Modal -->
                                      <div class="modal fade bs-example-modal-md" id="editModal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                        <div class="modal-dialog modal-md" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                              <h4 class="modal-title" id="myModalLabel">Edit Leave Application</h4>
                                            </div>
                                            <div class="modal-body">
                                              <?php echo $row['leave_id']; ?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    <?php
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </div> 
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="approved">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                                  <!-- <th class="column-title no-link last">Action</th> -->
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
                                    $ticket_query = "SELECT * FROM leave_application WHERE leave_status='Approved' and employee_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    $i=1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['leave_id']; ?>
                                        <td><?php echo $row["leave_status"]; ?></td>
                                        <td><?php echo $row["leave_id"]; ?></td>
                                        <td><?php echo $row["employee_id"]; ?></td>
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
                        <div role="tabpanel" class="tab-pane fade" id="disapproved">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                                  <!-- <th class="column-title no-link last">Action</th> -->
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
                                    $ticket_query = "SELECT * FROM leave_application WHERE leave_status='Disapproved' and employee_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    $i=1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['leave_id']; ?>
                                        <td><?php echo $row["leave_status"]; ?></td>
                                        <td><?php echo $row["leave_id"]; ?></td>
                                        <td><?php echo $row["employee_id"]; ?></td>
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
                        <div role="tabpanel" class="tab-pane fade" id="cancelled">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                                  <!-- <th class="column-title no-link last">Action</th> -->
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
                                    $ticket_query = "SELECT * FROM leave_application WHERE leave_status='Cancelled' and employee_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    $i=1;
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['leave_id']; ?>
                                        <td><?php echo $row["leave_status"]; ?></td>
                                        <td><?php echo $row["leave_id"]; ?></td>
                                        <td><?php echo $row["employee_id"]; ?></td>
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

                        <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
                        <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

                        <!-- Include Date Range Picker -->
                        <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
                        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

                        <script type="text/javascript">
                          $(function() {
                              $('input[name="leave_date"]').daterangepicker({
                                  timePicker: true,
                                  timePickerIncrement: 30,
                                  locale: {
                                      format: 'MM/DD/YYYY h:mm A'
                                  }
                              });
                          });
                        </script>
                        <?php
                          $ticket_query = "SELECT * FROM leave_application WHERE leave_status='Open' and employee_id='".$_SESSION['employee_id']."'";
                          $stmt = $db->prepare($ticket_query);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        ?>
                        <!-- EDIT MODAL -->
                        <div class="modal fade bs-example-modal-md" id="editModal<?php echo $row['leave_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog modal-md" role="document">
                          <div class="modal-content" style="width: 800px;padding-left:2em;" >
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="myModalLabel">Edit Leave Application</h4>
                            </div>
                            <div class="modal-body">
                              <div>
                              <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                                <div class="form-group" style="display:none">
                                  <label for="middle-name" class="control-label col-md-4 col-sm-5 col-xs-12">Employee ID</label>
                                  <div class="col-md-6 col-sm-10 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['employee_id'] ?>"  name="employee_id" />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Application Date
                                  </label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" required="required" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row['leave_application']; ?>" name="leave_application" />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Leave Application ID
                                  </label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input type="text" name="leave_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row['leave_id'] ?>"/>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Employee</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $_SESSION['first_name'] . " " . $_SESSION['last_name'] ?>"   name="leave_employee" />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Team</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input class="form-control col-md-7 col-xs-12" type="text"  readonly="readonly" value="<?php echo $_SESSION['team_name']; ?>"   name="leave_team" />
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Reason for Request</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" style="" name="leave_reason" required><?php echo $row['leave_reason'] ?></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Leave Type</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select id="leave_type" class="select2_single form-control" tabindex="-1" name="leave_type" required>
                                      <option disabled selected></option>
                                      <option value="Vacation" <?php if($row['leave_type'] == 'Vacation') echo 'selected'; ?>>Vacation Leave</option>
                                      <option value="Sick" <?php if($row['leave_type'] == 'Sick') echo 'selected'; ?>>Sick Leave</option>
                                      <option value="Unpaid" <?php if($row['leave_type'] == 'Unpaid') echo 'selected'; ?>>Unpaid Leave</option>
                                      <option value="Undertime" <?php if($row['leave_type'] == 'Undertime') echo 'selected'; ?>>Undertime</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Inclusive Date and Time</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset>
                                      <div class="col-md-12 control-group">
                                        <div class="controls">
                                          <div class="input-prepend input-group">
                                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                            <input type="text" name="leave_date" class="form-control" value="<?php echo $row['leave_date']?>"/>
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">No. of Working Hours</label>
                                  <div class="col-md-6 col-sm-6 col-xs-12">
                                    <fieldset>
                                      <div class="col-md-12 control-group">
                                        <div class="controls">
                                          <div class="input-prepend input-group">
                                            <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-clock-o"></i></span>
                                            <input type="text" class="form-control" required  name="leave_hours" value="<?php echo $row['leave_hours']?>">
                                          </div>
                                        </div>
                                      </div>
                                    </fieldset>
                                  </div>
                                </div>
                                <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="update_date" />
                                <div class="ln_solid"></div>

                                <div class="form-group">
                                  <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <button type="submit" class="btn btn-success" name="save_edit">Update</button>
                                    <button type="submit" class="btn btn-danger" name="cancelleave">Cancel Ticket</button>
                                  </div>
                                </div>
                              </form>

                            </div>
                          </div>
                        </div>
                      </div>
                      </div>
                      <?php 
                        }
                      ?>
                        
                    </div>
                  </div>
                </div>
              </div>
            </div>

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