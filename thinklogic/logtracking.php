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
                <h3>Employee Log Tracking</h3>
              </div>

              <div class="title_right">
                
              </div>
            </div>
            
            <div class="clearfix"></div>

            <?php
            if(isset($_POST['log']) || isset($_GET['log_date'])){
              if(isset($_POST['log'])){
                $log_date = $_POST['log_date'];
              } else {
                $log_date = $_SESSION['log_date'];
              }
            }
            ?>

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Retrieve Log <small></small></h2>
                    
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form class="form-horizontal form-label-left input_mask" method="POST">
                      <div class="row">
                        <div class="form-group">
                          <label class="col-sm-1 control-label">Date</label>

                          <div class="col-sm-9">
                            <div class="input-group">
                              <input type="text" class="form-control" id="single_cal3" name="log_date" aria-describedby="inputSuccess2Status3" >
                              <span class="input-group-btn">
                                  <?php 
                                    if(isset($_POST['reset_date'])) {
                                      unset($_SESSION['log']);
                                      unset($_SESSION['log_date']);
                                    ?>
                                      <script>window.location.href="logtracking.php"</script>
                                    <?php
                                    }
                                    ?>
                                  <button type="submit" class="btn btn-primary" name="log" id="toggle">Go</button>
                                  <button type="submit" class="btn btn-default" name="reset_date" id="toggle">Reset</button>
                              </span>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                    </form>

                  </div>
                </div>
              </div>
            </div>

            <!-- Employee Masterlist -->
            <div class="row">
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 col-xs-12" width="708px">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Attendance</h2>
                                       <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="table-responsive">
                      <table id="datatable" class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th class="column-title">Action</th>
                            <th class="column-title">Date</th>
                            <th class="column-title">Employee</th>
                            <th class="column-title">Attendance</th>
                            <th class="column-title">Log-In</th>
                            <th class="column-title">Break1 Start</th>
                            <th class="column-title">Break1 End</th>
                            <th class="column-title">Break1 Total</th>
                            <th class="column-title">Log-Out</th>
                            <th class="column-title">Log-In</th>
                            <th class="column-title">Break2 Start</th>
                            <th class="column-title">Break2 End</th>
                            <th class="column-title">Break2 Total</th>
                            <th class="column-title">Log-Out</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                              if(isset($_POST['log']) || isset($_SESSION['log_date'])){
                                if(isset($_POST['log'])){
                                  $log_date = $_POST['log_date'];
                                  $_SESSION['log_date'] = $log_date;
                                } else {
                                  $log_date = $_SESSION['log_date'];
                                }


                                $employee_query = "SELECT *, logbook.in1 AS log_in1, logbook.out1 AS log_out1, logbook.in2 AS log_in2, logbook.out2 AS log_out2, logbook.employee_id AS log_empid, employees.employee_id AS emp_id
                                                  FROM employees
                                                  INNER JOIN logbook ON logbook.employee_id=employees.employee_id AND log_date='$log_date'
                                                  INNER JOIN employment ON employees.employee_id=employment.employee_id
                                                  INNER JOIN shift_schedule ON employment.shift_schedule=shift_schedule.shift_id
                                                  WHERE employment.status='Active'";
                                $stmt = $db->prepare($employee_query);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                                  ?>
                                  <tr>
                                    <?php $rowID = $row['log_id']; ?>
                                    <!-- <td class="a-center "><input type="checkbox" class="flat" name="table_records"></td> -->
                                    <td class="last"><a href="#" data-toggle="modal" data-target="#editLog<?php echo $rowID ?>" data-sfid="<?php echo $rowID; ?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></td>
                                    <td><?php echo $row['log_date'] ?></td>
                                    <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                    <td>
                                      <?php
                                        if($row["log_in1"] == $row["in1"]){
                                          ?>
                                          <button class="btn btn-primary btn-xs">Present</button>
                                          <?php
                                        }
                                        else if($row["log_in1"] > $row["in1"] OR $row["log_in1"] > $row["in2"])
                                        {
                                          ?>
                                          <button class="btn btn-success btn-xs">Late</button>
                                          <?php
                                        }
										else if($row["log_in1"] == $row["in2"])
										{
											?>
											<button class="btn btn-warning btn-xs">Half Day</button>
											<?php
										}
                                        else
                                        {
                                          ?>
                                          <button class="btn btn-danger btn-xs">Absent</button>
                                          <?php
                                        }
                                      ?>
                                    </td>
                                    <td><?php echo $row['log_in1'] ?></td>
                                    <td><?php echo $row['break1_start'] ?></td>
                                    <td><?php echo $row['break1_end'] ?></td>
                                    <td><?php echo $row['break1_total'] ?></td>
                                    <td><?php echo $row['log_out1'] ?></td>
                                    <td><?php echo $row['log_in2'] ?></td>
                                    <td><?php echo $row['break2_start'] ?></td>
                                    <td><?php echo $row['break2_end'] ?></td>
                                    <td><?php echo $row['break2_total'] ?></td>
                                    <td><?php echo $row['log_out2'] ?></td>
                                  </tr>
                                  <?php
                                }
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

        <!-- PHP Code for updating logs -->
        <?php
          if(isset($_POST['editlog'])){
          	$log_id = $_POST['log_id'];
            $log_in1 = $_POST["log_in1"];
            $break1_start = $_POST["break1_start"];
            $break1_end = $_POST["break1_end"];
            $log_out1 = $_POST["log_out1"];
            $log_in2 = $_POST["log_in2"];
            $break2_start = $_POST["break2_start"];
            $break2_end = $_POST["break2_end"];
            $log_out2 = $_POST["log_out2"];

            /* Shift Schedule */
            $in1 = $_POST["in1"];
            $out1 = $_POST["out1"];
            $in2 = $_POST["in2"];
            $out2 = $_POST["out2"];

            /* Automated Calculation */ 
            include "datediff.php";

            // Log In
            if ($log_in1 != '0'){
            	// Early Bird and Ontime Wholeday
            	if (strtotime($log_in1) <= strtotime($in1)){
            		$in1_late='0';
            		$stmt=$db->prepare("UPDATE logbook SET in1='$in1', in1_late='$in1_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	// Late Whole-day
            	else if (strtotime($log_in1) > strtotime($in1) AND strtotime($log_in1) < strtotime($out1)){
            		$dateDiff = (strtotime($log_in1) - strtotime($in1))/60;
            		$in1_late = $dateDiff;
            		$stmt=$db->prepare("UPDATE logbook SET in1='$log_in1', in1_late='$in1_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	// Early Bird and Ontime Halfday
            	else if (strtotime($log_in1) > strtotime($out1) AND strtotime($log_in1) <= strtotime($in2)){
            		$in1_late='0';
            		$stmt=$db->prepare("UPDATE logbook SET in1='$in2', in1_late='$in1_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	// Late Half-day
            	else if (strtotime($log_in1) > strtotime($in2)){
            		$dateDiff = (strtotime($log_in1) - strtotime($in2))/60;
            		$in1_late = $dateDiff;
            		$stmt=$db->prepare("UPDATE logbook SET in1='$log_in1', in1_late='$in1_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            }

            // Break 1 Total
            if ($break1_start != '0' AND $break1_end != '0'){
            	$break1_start;
            	$break1_end;
            	$dateDiff = (strtotime($break1_end) - strtotime($break1_start))/60;
            	$break1_total = $dateDiff;
            	$stmt=$db->prepare("UPDATE logbook SET break1_start='$break1_start', break1_end='$break1_end', break1_total='$break1_total' WHERE log_id='$log_id'");
            	$stmt->execute();
            }
            else{
            	$break1_start = '0';
            	$break1_end = '0';
            	$break1_total = '0';
            	$stmt=$db->prepare("UPDATE logbook SET break1_start='$break1_start', break1_end='$break1_end', break1_total='$break1_total' WHERE log_id='$log_id'");
            	$stmt->execute();
            }

            // Lunch
            if ($log_out1 != '0' AND $log_in2 != '0'){
            	// Lunch Start
            	// On time or late lunch start
            	if(strtotime($log_out1) >= strtotime($out1)){
            		$stmt=$db->prepare("UPDATE logbook SET out1='$out1' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	// Undertime or Early Lunch
            	else{
            		$stmt=$db->prepare("UPDATE logbook SET out1='$log_out1' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}

            	//Lunch End
            	// On time or early lunch end
            	if(strtotime($log_in2) <= strtotime($in2)){
            		$in2_late = '0';
            		$stmt=$db->prepare("UPDATE logbook SET in2='$in2', in2_late='$in2_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	else if(strtotime($log_in2) > strtotime($in2)){
            		$in2_late = (strtotime($log_in2)-strtotime($in2))/60;
            		$stmt=$db->prepare("UPDATE logbook SET in2='$log_in2', in2_late='$in2_late' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            }
            else{
            	$out1 = '0';
            	$in2 = '0';
            	$in2_late = '0';
            	$stmt=$db->prepare("UPDATE logbook SET out1='$out1', in2='$in2', in2_late='$in2_late' WHERE log_id='$log_id'");
            	$stmt->execute();
            }

            // Break 2 Total
            if ($break2_start != '0' AND $break2_end != '0'){
            	$break2_start;
            	$break2_end;
            	$dateDiff = (strtotime($break2_end) - strtotime($break2_start))/60;
            	$break2_total = $dateDiff;
            	$stmt=$db->prepare("UPDATE logbook SET break2_start='$break2_start', break2_end='$break2_end', break2_total='$break2_total' WHERE log_id='$log_id'");
            	$stmt->execute();
            } 
            else{
            	$break2_start = '0';
            	$break2_end = '0';
            	$break2_total = '0';
            	$stmt=$db->prepare("UPDATE logbook SET break2_start='$break2_start', break2_end='$break2_end', break2_total='$break2_total' WHERE log_id='$log_id'");
            	$stmt->execute();
            }       

            // Log-Out
            if ($log_out2 != '0'){
            	// Undertime
            	if(strtotime($log_out2) < strtotime($out2)){
            		$out2 = $log_out2;
            		$stmt=$db->prepare("UPDATE logbook SET out2='$out2' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            	// On time or after shift end
            	else if(strtotime($log_out2) >= strtotime($out2)){
            		$stmt=$db->prepare("UPDATE logbook SET out2='$out2' WHERE log_id='$log_id'");
            		$stmt->execute();
            	}
            }
            else{
            	$out2 = '0';
            	$stmt=$db->prepare("UPDATE logbook SET out2='$out2' WHERE log_id='$log_id'");
            	$stmt->execute();
            }

            /**** QUERY UPDATED LOGS TO CALCULATED HOURS WORKED ****/
            $hoursworked = "SELECT * FROM logbook WHERE log_id='$log_id'";
			$stmt = $db->prepare($hoursworked);
			$stmt->execute(array('log_id' => $log_id));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$in1 = $row['in1'];
			$in1_late = $row['in1_late'];
			$out1 = $row['out1'];
			$in2 = $row['in2'];
			$in2_late = $row['in2_late'];
			$out2 = $row['out2'];
			$break1_total = $row['break1_total'];
			$break2_total = $row['break2_total'];

			// Whole Day Computation
			if ($out1 != '0' AND $in2 != '0'){
				// Convert time into 24-hr format
				$in1 = date("H:i:s", strtotime($in1));
				$out1 = date("H:i:s", strtotime($out1));
				$in2 = date("H:i:s", strtotime($in2));
				$out2 = date("H:i:s", strtotime($out2));

				// Variables
				$break1_late = 0;
				$break2_late = 0;
				// Breaks
				if($break1_total > 15){
				  $break1_late = $break1_total - 15;
				}
				if($break2_total > 15){
				  $break2_late = $break2_total - 15;
				}
				//Break Late
				$break_late = ($break1_late + $break2_late);
				//Log-In Late
				$log_late = $in1_late + $in2_late;
				//Total Late (Break & Log-Ins)
				$total_late = $break_late + $log_late;
				// TOTAL HOURS WORKED COMPUTATION
				$half1 = dateDiff($out1, $in1);
				$half2 = dateDiff($out2, $in2);
				$hoursworked = ($half1 + $half2) / 60; //60 for minutes to hours
				$hoursworked = $hoursworked - ($break_late/60);
				$stmt = $db->prepare("UPDATE logbook SET total_hours='$hoursworked', total_late='$total_late' WHERE log_id='$log_id'");
				$stmt->execute();
			}
			// Half Day Computation
			else{
				// Convert time into 24-hr format
				$in1 = date("H:i:s", strtotime($in1));
				$out2 = date("H:i:s", strtotime($out2));

				// Variables
				$break1_late = 0;
				$break2_late = 0;
				// Breaks
				if($break1_total > 15){
				  $break1_late = $break1_total - 15;
				}
				if($break2_total > 15){
				  $break2_late = $break2_total - 15;
				}
				//Break Late
				$break_late = ($break1_late + $break2_late);
				//Log-In Late
				$log_late = $in1_late + $in2_late;
				//Total Late (Break & Log-Ins)
				$total_late = $break_late + $log_late;
				// TOTAL HOURS WORKED COMPUTATION
				$hoursworked = dateDiff($out2, $in1);
				$hoursworked = $hoursworked / 60; //60 for minutes to hours
				$hoursworked = $hoursworked - ($break_late/60);
				if($hoursworked > 8){
				  $log_late = ($log_late-$break_late) / 60; //minutes to hours
				  $hoursworked = 8 - $log_late;
				  $stmt = $db->prepare("UPDATE logbook SET total_hours='$hoursworked', total_late='$total_late' WHERE log_id='$log_id'");
				  $stmt->execute();
				  session_destroy();
				}
				else{
				  $stmt = $db->prepare("UPDATE logbook SET total_hours='$hoursworked', total_late='$total_late' WHERE log_id='$log_id'");
				  $stmt->execute();
				}
			}

            echo "<meta http-equiv='refresh' content='0'>";
          }
          if(isset($_POST['deletelog'])){
          	$log_id = $_POST['log_id'];
          	$stmt = $db->prepare("DELETE FROM logbook WHERE log_id='$log_id'");
			$stmt->execute();
          }
        ?>

        <!-- Modal for Updating Logs --> 
        <?php
          if(isset($_POST['log'])){
                                $log_date = $_POST['log_date'];
          $employee_query = "SELECT *, logbook.in1 AS log_in1, logbook.out1 AS log_out1, logbook.in2 AS log_in2, logbook.out2 AS log_out2, logbook.employee_id AS log_empid, employees.employee_id AS emp_id
                                                  FROM employees
                                                  LEFT JOIN logbook ON logbook.employee_id=employees.employee_id AND log_date='$log_date'
                                                  INNER JOIN employment ON employees.employee_id=employment.employee_id
                                                  INNER JOIN shift_schedule ON employment.shift_schedule=shift_schedule.shift_id
                                                  WHERE employment.status='Active'";
          $stmt = $db->prepare($employee_query);
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
            <!-- Edit Modal -->
            <div class="modal fade bs-example-modal-md" id="editLog<?php echo $row['log_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Edit Log</h4>
                  </div>
                  <div class="modal-body">
                    <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log ID
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="log_id" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["log_id"]?>" />
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
                      <input type="text" name="employee_name" class="form-control col-md-7 col-xs-12" readonly="readonly" value="<?php echo $row["first_name"] . " " . $row['last_name']?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-In
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="log_in1" class="form-control col-md-7 col-xs-12" value="<?php echo $row["log_in1"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Break1 Start
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="break1_start" class="form-control col-md-7 col-xs-12" value="<?php echo $row["break1_start"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Break1 End
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="break1_end" class="form-control col-md-7 col-xs-12" value="<?php echo $row["break1_end"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Lunch Start
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="log_out1" class="form-control col-md-7 col-xs-12" value="<?php echo $row["log_out1"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Lunch End
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="log_in2" class="form-control col-md-7 col-xs-12" value="<?php echo $row["log_in2"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Break2 Start
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="break2_start" class="form-control col-md-7 col-xs-12" value="<?php echo $row["break2_start"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Break2 End
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="break2_end" class="form-control col-md-7 col-xs-12" value="<?php echo $row["break2_end"]?>" />
                      </div>
                      </div>
                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-Out
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                      <input type="text" name="log_out2" class="form-control col-md-7 col-xs-12" value="<?php echo $row["log_out2"]?>" />
                      </div>
                      </div>
                      <!-- Shift Schedule -->
                      <div style="">
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-In
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="in1" class="form-control col-md-7 col-xs-12" value="<?php echo $row["in1"]?>" />
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-Out
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="out1" class="form-control col-md-7 col-xs-12" value="<?php echo $row["out1"]?>" />
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-In
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="in2" class="form-control col-md-7 col-xs-12" value="<?php echo $row["in2"]?>" />
                        </div>
                        </div>
                        <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Log-Out
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="out2" class="form-control col-md-7 col-xs-12" value="<?php echo $row["out2"]?>" />
                        </div>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <center>
                        <button type="submit" class="btn btn-success" name="editlog">Edit</button>
                        <button type="submit" class="btn btn-danger" name="deletelog">Delete</button>
                      </center>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          <?php 
          }
        }
        ?>

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
</html><!--  -->