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
                    <h3>Tasks</h3>
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
                           
                          <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <ul class="nav nav-tabs" role="tablist">
                              <li role="presentation" class="active" ><a href="#open" aria-controls="pending" role="tab" data-toggle="tab">Open Tickets</a></li>
                              <li role="presentation"><a href="#approved" aria-controls="pending" role="tab" data-toggle="tab">Approved Tickets</a></li>
                              <li role="presentation"><a href="#assigned" aria-controls="messages" role="tab" data-toggle="tab">Assigned Tickets</a></li>
                            </ul>
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
                                        <th class="column-title">Requestor</th>
                                        <th class="column-title">Team Concern</th>
                                        <th class="column-title">Subject</th>
                                        <th class="column-title">Description</th>
                                        <th class="column-title">Priority</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                          $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Open'";
                                          $stmt = $db->prepare($ticket_query);
                                          $stmt->execute();
                                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                          ?>
                                            <tr>
                                              <?php $rowID = $row['ticket_id']; ?>
                                              <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                              <td class="last"><a href="#" data-toggle="modal" data-target="#approveTicket<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                              <td><?php echo $row["ticket_status"]; ?></td>
                                              <td><?php echo $row["ticket_id"]; ?></td>
                                              <td><?php echo $row["ticket_requestor"]; ?></td>
                                              <td><?php echo $row["ticket_team"]; ?></td>
                                              <td><?php echo $row["ticket_subject"]; ?></td>
                                              <td><?php echo $row["ticket_description"]; ?></td>
                                              <td>
                                                <?php 
                                                  $ticket_priority = $row["ticket_priority"];

                                                  if ($ticket_priority == 'Low'){
                                                    ?>
                                                    <button class="btn btn-primary btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Moderate'){
                                                    ?>
                                                    <button class="btn btn-success btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'High'){
                                                    ?>
                                                    <button class="btn btn-warning btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Urgent'){
                                                    ?>
                                                    <button class="btn btn-danger btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                ?>
                                              </td>
                                            </tr>
                                          <?php
                                          }
                                        ?>
                                    </tbody>
                                  </table>
                                </div> 
                              </div>
                              <div role="tabpanel" class="tab-pane fade in" id="approved">
                                <br>
                                <div class="table-responsive">
                                  <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                                    <thead>
                                      <tr class="headings">
                                        <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                                        <th class="column-title no-link last">Action</th>
                                        <th class="column-title">Status</th>
                                        <th class="column-title">ID</th>
                                        <th class="column-title">Requestor</th>
                                        <th class="column-title">Team Concern</th>
                                        <th class="column-title">Subject</th>
                                        <th class="column-title">Description</th>
                                        <th class="column-title">Priority</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                          $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Approved'";
                                          $stmt = $db->prepare($ticket_query);
                                          $stmt->execute();
                                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                          ?>
                                            <tr>
                                              <?php $rowID = $row['ticket_id']; ?>
                                              <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                              <td class="last"><a href="#" data-toggle="modal" data-target="#assignTicket<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                              <td><?php echo $row["ticket_status"]; ?></td>
                                              <td><?php echo $row["ticket_id"]; ?></td>
                                              <td><?php echo $row["ticket_requestor"]; ?></td>
                                              <td><?php echo $row["ticket_team"]; ?></td>
                                              <td><?php echo $row["ticket_subject"]; ?></td>
                                              <td><?php echo $row["ticket_description"]; ?></td>
                                              <td>
                                                <?php 
                                                  $ticket_priority = $row["ticket_priority"];

                                                  if ($ticket_priority == 'Low'){
                                                    ?>
                                                    <button class="btn btn-primary btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Moderate'){
                                                    ?>
                                                    <button class="btn btn-success btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'High'){
                                                    ?>
                                                    <button class="btn btn-warning btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Urgent'){
                                                    ?>
                                                    <button class="btn btn-danger btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                ?>
                                              </td>
                                            </tr>
                                          <?php
                                          }
                                        ?>
                                    </tbody>
                                  </table>
                                </div> 
                              </div>
                              <div role="tabpanel" class="tab-pane fade in" id="assigned">
                                <br>
                                <div class="table-responsive">
                                  <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                                    <thead>
                                      <tr class="headings">
                                        <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                                        <th class="column-title no-link last">Action</th>
                                        <th class="column-title">Status</th>
                                        <th class="column-title">Note</th>
                                        <th class="column-title">ID</th>
                                        <th class="column-title">Requestor</th>
                                        <th class="column-title">Team Concern</th>
                                        <th class="column-title">Subject</th>
                                        <th class="column-title">Description</th>
                                        <th class="column-title">Priority</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                          $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Assigned'";
                                          $stmt = $db->prepare($ticket_query);
                                          $stmt->execute();
                                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                          ?>
                                            <tr>
                                              <?php $rowID = $row['ticket_id']; ?>
                                              <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                              <td class="last"><a href="#" data-toggle="modal" data-target="#completeTicket<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                              <td><?php echo $row["ticket_status"]; ?></td>
                                              <td><?php echo $row["ticket_note"]; ?></td>
                                              <td><?php echo $row["ticket_id"]; ?></td>
                                              <td><?php echo $row["ticket_requestor"]; ?></td>
                                              <td><?php echo $row["ticket_team"]; ?></td>
                                              <td><?php echo $row["ticket_subject"]; ?></td>
                                              <td><?php echo $row["ticket_description"]; ?></td>
                                              <td>
                                                <?php 
                                                  $ticket_priority = $row["ticket_priority"];

                                                  if ($ticket_priority == 'Low'){
                                                    ?>
                                                    <button class="btn btn-primary btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Moderate'){
                                                    ?>
                                                    <button class="btn btn-success btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'High'){
                                                    ?>
                                                    <button class="btn btn-warning btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                  else if ($ticket_priority == 'Urgent'){
                                                    ?>
                                                    <button class="btn btn-danger btn-xs"><?php echo $ticket_priority ?></button>
                                                    <?php
                                                  }
                                                ?>
                                              </td>
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
                <!-- PHP Code for updating ticket status -->
                <?php
                  if(isset($_POST['approveticket'])){
                    $ticket_id = $_POST["ticket_id"];
                    $date_updated = $_POST["date_updated"];
                    $updated_by = $_POST["updated_by"];
                    $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Approved', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                    $stmt1->execute();
                    
                    echo "<meta http-equiv='refresh' content='0'>";
                  }

                  if(isset($_POST['disapproveticket'])){
                    $ticket_id = $_POST["ticket_id"];
                    $date_updated = $_POST["date_updated"];
                    $updated_by = $_POST["updated_by"];
                    $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Disapproved', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                    $stmt1->execute();

                    echo "<meta http-equiv='refresh' content='0'>";
                  }
                ?>

                <!-- Modal for Approving/Disapproving Team Ticket Requests --> 
                <?php
                  $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Open'";
                  $stmt = $db->prepare($ticket_query);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                    <!-- Edit Modal -->
                    <div class="modal fade bs-example-modal-md" id="approveTicket<?php echo $row['ticket_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Update Ticket Request</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_id"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket Status
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_status" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_status"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requestor
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_requestor" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_requestor"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Team Concern
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_team" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_team"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Subject
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_subject" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_subject"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Description
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control" style="" name="ticket_description" class="form-control col-md-7 col-xs-12"><?php echo $row["ticket_description"]?></textarea>
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Priority
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_priority" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_priority"]?>" />
                              </div>
                              </div>
                              <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="date_updated" />
                              <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>" name="updated_by" />
                              <div class="ln_solid"></div>
                              <center>
                                <!--<input type="submit" value="Approve" class="btn btn-primary" name="approveleave" />-->
                                <button type="submit" class="btn btn-success" name="approveticket">Approve</button>
                                <button type="submit" class="btn btn-danger" name="disapproveticket">Disapprove</button>
                              </center>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php 
                  }
                ?>

                <!-- START FOR APPROVED TAB-->
                <!-- PHP Code for updating ticket status -->
                <?php
                  if(isset($_POST['assignticket'])){
                    $ticket_id = $_POST["ticket_id"];
                    $ticket_assignment = $_POST["ticket_assignment"];
                    $ticket_note = $_POST["ticket_note"];
                    $date_updated = $_POST["date_updated"];
                    $updated_by = $_POST["updated_by"];
                    $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Assigned', ticket_assignment='".$ticket_assignment."', ticket_note='".$ticket_note."', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                    $stmt1->execute();
                    
                    echo "<meta http-equiv='refresh' content='0'>";
                  }
                ?>

                <!-- Modal for Assigning ".$_SESSION["team_name"]." Team Ticket Requests --> 
                <?php
                  $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Approved'";
                  $stmt = $db->prepare($ticket_query);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                    <!-- Edit Modal -->
                    <div class="modal fade bs-example-modal-md" id="assignTicket<?php echo $row['ticket_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Update Ticket Request</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_id"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket Status
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_status" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_status"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requestor
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_requestor" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_requestor"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Team Concern
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_team" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_team"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Subject
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_subject" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_subject"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Description
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control" style="" name="ticket_description" class="form-control col-md-7 col-xs-12"><?php echo $row["ticket_description"]?></textarea>
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Priority
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_priority" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_priority"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket Assignment
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <select id="ticket_assignment" class="select2_single form-control" tabindex="-1" name="ticket_assignment" required>
                                  <option disabled selected></option>
                                  <?php
                                    $group_query = "SELECT employee_id FROM employee_group WHERE team_id='".$_SESSION["team_id"]."'";
                                    $stmt = $db->prepare($group_query);
                                    $stmt->execute();
                                    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                                      $employee_id = $row['employee_id'];
                                      $employee_query = "SELECT * FROM employees WHERE employee_id=$employee_id";
                                      $stmt1 = $db->prepare($employee_query);
                                      $stmt1->execute();
                                      while($row=$stmt1->fetch(PDO::FETCH_ASSOC)){
                                      ?>
                                        <option><?php echo $row['first_name'] . " " . $row['last_name' ]?></option>
                                      <?php
                                      }
                                    }
                                  ?>
                                </select>
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_note" class="form-control col-md-7 col-xs-12" />
                              </div>
                              </div>
                              <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="date_updated" />
                              <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>" name="updated_by" />
                              <div class="ln_solid"></div>
                              <center>
                                <!--<input type="submit" value="Approve" class="btn btn-primary" name="approveleave" />-->
                                <button type="submit" class="btn btn-success" name="assignticket">Assign</button>
                              </center>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php 
                  }
                ?>
                <!-- END FOR APPROVED TAB-->

                <!-- FOR ASSIGNED TAB-->
                <!-- PHP Code for updating ticket status -->
                <?php
                  if(isset($_POST['resolveticket'])){
                    $ticket_id = $_POST["ticket_id"];
                    $ticket_note = $_POST["ticket_note"];
                    $date_updated = $_POST["date_updated"];
                    $updated_by = $_POST["updated_by"];
                    $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Completed - Resolved', ticket_note='".$ticket_note."', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                    $stmt1->execute();
                    
                    echo "<meta http-equiv='refresh' content='0'>";
                  }

                  if(isset($_POST['unresolveticket'])){
                    $ticket_id = $_POST["ticket_id"];
                    $ticket_note = $_POST["ticket_note"];
                    $date_updated = $_POST["date_updated"];
                    $updated_by = $_POST["updated_by"];
                    $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Completed - Unresolved', ticket_note='".$ticket_note."', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                    $stmt1->execute();

                    echo "<meta http-equiv='refresh' content='0'>";
                  }
                ?>

                <!-- Modal for Completing ".$_SESSION["team_name"]." Team Ticket Requests --> 
                <?php
                  $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Assigned'";
                  $stmt = $db->prepare($ticket_query);
                  $stmt->execute();
                  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                    <!-- Edit Modal -->
                    <div class="modal fade bs-example-modal-md" id="completeTicket<?php echo $row['ticket_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Update Ticket Request</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket ID
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_id"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Ticket Status
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_status" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_status"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Requestor
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_requestor" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_requestor"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Team Concern
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_team" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_team"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Subject
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_subject" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_subject"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Description
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <textarea class="form-control" style="" name="ticket_description" class="form-control col-md-7 col-xs-12"><?php echo $row["ticket_description"]?></textarea>
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Priority
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_priority" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["ticket_priority"]?>" />
                              </div>
                              </div>
                              <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Note
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                              <input type="text" name="ticket_note" class="form-control col-md-7 col-xs-12" />
                              </div>
                              </div>
                              <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="date_updated" />
                              <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>" name="updated_by" />
                              <div class="ln_solid"></div>
                              <center>
                                <!--<input type="submit" value="Approve" class="btn btn-primary" name="approveleave" />-->
                                <button type="submit" class="btn btn-success" name="resolveticket">Completed - Resolved</button>
                                <button type="submit" class="btn btn-danger" name="unresolveticket">Completed - Unresolved</button>
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
