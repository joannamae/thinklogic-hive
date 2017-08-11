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
                <h3>Ticket Tracking</h3>
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
                  <div class="x_content">
                    <div>
                      <!-- Nav tabs -->
                      <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active" ><a href="#open" aria-controls="pending" role="tab" data-toggle="tab">Open</a></li>
                        <li role="presentation"><a href="#approved" aria-controls="messages" role="tab" data-toggle="tab">Approved</a></li>
                        <li role="presentation"><a href="#disapproved" aria-controls="messages" role="tab" data-toggle="tab">Disapproved</a></li>
                        <li role="presentation"><a href="#assigned" aria-controls="messages" role="tab" data-toggle="tab">Assigned</a></li>
                        <li role="presentation"><a href="#completed-resolved" aria-controls="messages" role="tab" data-toggle="tab">Completed-Resolved</a></li>
                        <li role="presentation"><a href="#completed-unresolved" aria-controls="messages" role="tab" data-toggle="tab">Completed-Unresolved</a></li>
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
                                  <th class="column-title">Requestor</th>
                                  <th class="column-title">Team Concern</th>
                                  <th class="column-title">Subject</th>
                                  <th class="column-title">Description</th>
                                  <th class="column-title">Priority</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Open' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['ticket_id']; ?>
                                        <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                        <td class="last"><a href="#" data-toggle="modal" data-target="#editTicket<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
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
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Approved' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
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

                        <div role="tabpanel" class="tab-pane fade in" id="disapproved">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
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
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Disapproved' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
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
                                  <th class="column-title">Status</th>
                                  <th class="column-title">ID</th>
                                  <th class="column-title">Requestor</th>
                                  <th class="column-title">Team Concern</th>
                                  <th class="column-title">Subject</th>
                                  <th class="column-title">Description</th>
                                  <th class="column-title">Priority</th>
                                  <th class="column-title">Assigned To</th>
                                  <th class="column-title">Note</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Assigned' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
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
                                        <td><?php echo $row["ticket_assignment"]; ?></td>
                                        <td><?php echo $row["ticket_note"]; ?></td>
                                      </tr>
                                    <?php
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </div> 
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="completed-resolved">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <th class="column-title">Status</th>
                                  <th class="column-title">ID</th>
                                  <th class="column-title">Requestor</th>
                                  <th class="column-title">Team Concern</th>
                                  <th class="column-title">Subject</th>
                                  <th class="column-title">Description</th>
                                  <th class="column-title">Priority</th>
                                  <th class="column-title">Assigned To</th>
                                  <th class="column-title">Note</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Completed - Resolved' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
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
                                        <td><?php echo $row["ticket_assignment"]; ?></td>
                                        <td><?php echo $row["ticket_note"]; ?></td>
                                      </tr>
                                    <?php
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </div> 
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="completed-unresolved">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <th class="column-title">Status</th>
                                  <th class="column-title">ID</th>
                                  <th class="column-title">Requestor</th>
                                  <th class="column-title">Team Concern</th>
                                  <th class="column-title">Subject</th>
                                  <th class="column-title">Description</th>
                                  <th class="column-title">Priority</th>
                                  <th class="column-title">Assigned To</th>
                                  <th class="column-title">Note</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Completed - Unresolved' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
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
                                        <td><?php echo $row["ticket_assignment"]; ?></td>
                                        <td><?php echo $row["ticket_note"]; ?></td>
                                      </tr>
                                    <?php
                                    }
                                  ?>
                              </tbody>
                            </table>
                          </div> 
                        </div>

                        <div role="tabpanel" class="tab-pane fade in" id="cancelled">
                          <br>
                          <div class="table-responsive">
                            <table id="datatable-checkbox" class="table table-striped jambo_table bulk_action">
                              <thead>
                                <tr class="headings">
                                  <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
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
                                    $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Cancelled' and requestor_id='".$_SESSION['employee_id']."'";
                                    $stmt = $db->prepare($ticket_query);
                                    $stmt->execute();
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                    ?>
                                      <tr>
                                        <?php $rowID = $row['ticket_id']; ?>
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

                        <!-- OPEN TAB -->
                        <!-- PHP Code for updating ticket status -->
                        <?php
                          if(isset($_POST['updateticket'])){
                            $stmt = $db->prepare("UPDATE ticket_request SET ticket_team=?, ticket_subject=?, ticket_description=?, ticket_priority=?, date_updated=?, updated_by=? WHERE ticket_id=?");
                            $stmt->bindParam(1,$_POST['ticket_team']);
                            $stmt->bindParam(2,$_POST['ticket_subject']);
                            $stmt->bindParam(3,$_POST['ticket_description']);
                            $stmt->bindParam(4,$_POST['ticket_priority']);
                            $stmt->bindParam(5,$_POST['date_updated']);
                            $stmt->bindParam(6,$_POST['updated_by']);
                            $stmt->bindParam(7,$_POST['ticket_id']);
                            $stmt->execute();
                            
                            echo "<meta http-equiv='refresh' content='0'>";
                          }

                          if(isset($_POST['cancelticket'])){
                            $ticket_id = $_POST["ticket_id"];
                            $date_updated = $_POST["date_updated"];
                            $updated_by = $_POST["updated_by"];
                            $stmt1 = $db->prepare("UPDATE ticket_request SET ticket_status='Cancelled', date_updated='".$date_updated."', updated_by='".$updated_by."' WHERE ticket_id=$ticket_id");
                            $stmt1->execute();
                            
                            echo "<meta http-equiv='refresh' content='0'>";
                          }
                        ?>

                        <!-- Modal for Updating Creative Team Ticket Requests --> 
                        <?php
                          $ticket_query = "SELECT * FROM ticket_request WHERE ticket_status='Open' and requestor_id='".$_SESSION['employee_id']."'";
                          $stmt = $db->prepare($ticket_query);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
                            <!-- Edit Modal -->
                            <div class="modal fade bs-example-modal-md" id="editTicket<?php echo $row['ticket_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                                      <select id="ticket_team" class="select2_single form-control" tabindex="-1" name="ticket_team" required>
                                        <option disabled selected></option>
                                        <option value="Admin" <?php if($row['ticket_team'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                        <option value="Cjay" <?php if($row['ticket_team'] == 'Cjay') echo 'selected'; ?>>Cjay</option>
                                        <option value="Chris" <?php if($row['ticket_team'] == 'Chris') echo 'selected'; ?>>Chris</option>
                                        <option value="Nholan" <?php if($row['ticket_team'] == 'Nholan') echo 'selected'; ?>>Nholan</option>
                                        <option value="Ops Management" <?php if($row['ticket_team'] == 'Ops Management') echo 'selected'; ?>>Ops Management</option>
                                        <option value="Sales" <?php if($row['ticket_team'] == 'Sales') echo 'selected'; ?>>Sales</option>
                                        <option value="Creative" <?php if($row['ticket_team'] == 'Creative') echo 'selected'; ?>>Creative</option>
                                      </select>
                                      </div>
                                      </div>
                                      <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Subject
                                      </label>
                                      <div class="col-md-6 col-sm-6 col-xs-12">
                                      <input type="text" name="ticket_subject" class="form-control col-md-7 col-xs-12" value="<?php echo $row["ticket_subject"]?>" />
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
                                      <select id="ticket_priority" class="select2_single form-control" tabindex="-1" name="ticket_priority" required>
                                        <option disabled selected></option>
                                        <option value="Low" <?php if($row['ticket_priority'] == 'Low') echo 'selected'; ?>>Low</option>
                                        <option value="Moderate" <?php if($row['ticket_priority'] == 'Moderate') echo 'selected'; ?>>Moderate</option>
                                        <option value="High" <?php if($row['ticket_priority'] == 'High') echo 'selected'; ?>>High</option>
                                        <option value="Urgent" <?php if($row['ticket_priority'] == 'Urgent') echo 'selected'; ?>>Urgent</option>
                                      </select>
                                      </div>
                                      </div>
                                      <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="date_updated" />
                                      <input style="display:none" value="<?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>" name="updated_by" />
                                      <div class="ln_solid"></div>
                                      <center>
                                        <!--<input type="submit" value="Approve" class="btn btn-primary" name="approveleave" />-->
                                        <button type="submit" class="btn btn-success" name="updateticket">Update</button>
                                        <button type="submit" class="btn btn-danger" name="cancelticket">Cancel Ticket</button>
                                      </center>
                                    </form>
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