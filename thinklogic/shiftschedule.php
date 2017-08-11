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
                <h3>Shift Schedule</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <?php
              if(isset($_POST['addshift'])){
                $stmt = $db->prepare("INSERT INTO shift_schedule (in1, out1, in2, out2) VALUES (:In1, :Out1, :In2, :Out2)");

                $stmt->bindParam(':In1', $In1);
                $stmt->bindParam(':Out1', $Out1);
                $stmt->bindParam(':In2', $In2);
                $stmt->bindParam(':Out2', $Out2);

                $In1 = $_POST['in1'];
                $Out1 = $_POST['out1'];
                $In2 = $_POST['in2'];
                $Out2 = $_POST['out2'];

                $stmt->execute();
                ?>
                  <script>window.location.href="shiftschedule.php"</script>
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
                      New Schedule
                    </button>

                    <!-- Modal -->
                    <div class="modal fade bs-example-modal-md" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Shift Schedule</h4>
                          </div>
                          <div class="modal-body">
                            <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                                <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Shift ID</label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="form-control col-md-7 col-xs-12" readonly="readonly"/>
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Shift Start <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="in1" required />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">1-Hr Break Start <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="out1" required />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">1-Hr Break End<span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="in2" required />
                                </div>
                                </div>
                                <div class="form-group">
                                <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Shift End <span class="required">*</span></label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" name="out2" required />
                                </div>
                                </div>
                                <br>
                                <center>
                                    <input type="submit" value="Add Shift" class="btn btn-primary" name="addshift" />
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
                    <h2>Schedule Masterlist</h2>
                   
                    <div class="clearfix"></div>
                  </div>

                  <!-- Query for Updating Team -->
                  <?php 
                    if(isset($_POST['update_shift']))
                    {
                      $stmt = $db->prepare("UPDATE shift_schedule SET in1=?, out1=?, in2=?, out2=? WHERE shift_id=?");
                      $stmt->bindParam(1,$_POST['in1']);
                      $stmt->bindParam(2,$_POST['out1']);
                      $stmt->bindParam(3,$_POST['in2']);
                      $stmt->bindParam(4,$_POST['out2']);
                      $stmt->bindParam(5,$_POST['shift_id']);
                      $stmt->execute();
                   ?>
                      <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>Thank you!</strong> Shift successfully updated.
                      </div>
                      <?php
                    }
                  ?>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <!-- <th class="column-title"><input type="checkbox" id="check-all" class="flat"></th> -->
                            <th class="column-title no-link last">Action</th>
                            <th class="column-title">Shift ID</th>
                            <th class="column-title">Shift Start</th>
                            <th class="column-title">1-Hr Break Start</th>
                            <th class="column-title">1-Hr Break End</th>
                            <th class="column-title">Shift End</th>
                          </tr>
                        </thead>
                        <tbody>

                          <?php
                              $shift_query = "SELECT * FROM shift_schedule";
                              $stmt = $db->prepare($shift_query);
                              $stmt->execute();
                              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                              ?>
                                <tr>
                                  <?php $rowID = $row['shift_id']; ?>
                                  <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                  <td class="last"><a href="#" data-toggle="modal" data-target="#editModal<?php echo $rowID; ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                  <td><?php echo $row["shift_id"]; ?></td>
                                  <td><?php echo $row["in1"]; ?></td>
                                  <td><?php echo $row["out1"]; ?></td>
                                  <td><?php echo $row["in2"]; ?></td>
                                  <td><?php echo $row["out2"]; ?></td>
                                </tr>
                              <?php
                              }
                            ?>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <!-- Modal for Updating Team -->
                  <?php
                    $shift_query = "SELECT * FROM shift_schedule";
                    $stmt = $db->prepare($shift_query);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <!-- EDIT MODAL -->
                  <div class="modal fade bs-example-modal-md" id="editModal<?php echo $row['shift_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                  <div class="modal-dialog modal-md" role="document">
                    <div class="modal-content" style="width: 800px;padding-left:2em;" >
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Edit Shift</h4>
                      </div>
                      <div class="modal-body">
                        <div>
                          <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                            <div class="form-group" style="display:none">
                              <label for="middle-name" class="control-label col-md-4 col-sm-5 col-xs-12">Shift ID</label>
                              <div class="col-md-6 col-sm-10 col-xs-12">
                                <input class="form-control col-md-7 col-xs-12" type="text" readonly="readonly" value="<?php echo $row['shift_id'] ?>"  name="shift_id" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Shift Start
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row['in1']; ?>" name="in1" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">1-Hr Break Start
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row['out1']; ?>" name="out1" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">1-Hr Break End
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row['in2']; ?>" name="in2" />
                              </div>
                            </div>
                            <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Shift End
                              </label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $row['out2']; ?>" name="out2" />
                              </div>
                            </div>
                            <div class="ln_solid"></div>
                            <div class="form-group">
                              <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                <button class="btn btn-primary" type="reset">Reset</button>
                                <button type="submit" class="btn btn-success" name="update_shift">Submit</button>
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