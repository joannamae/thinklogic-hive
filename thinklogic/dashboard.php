<?php
  include "config.php";
  session_start();
  if(!isset($_SESSION['username']))
  {
    header('location: login.php');
  }

  $current_date = date('m/d/Y') ;
  $emp_id = $_SESSION['employee_id'];
  $team_name = $_SESSION['team_name'];
  if(isset($_POST['addWaive'])){
    $stmt = $db->prepare("INSERT INTO waive (lateEmp_id,lateEmp_name,waive_reason,log_id) VALUES (:lateEmp_id,:lateEmp_name,:waive_reason,:log_id)");

    $stmt->bindParam(':lateEmp_id', $_POST['lateEmp_id']);
    $stmt->bindParam(':lateEmp_name', $_POST['lateEmp_name']);
    $stmt->bindParam(':waive_reason', $_POST['waive_reason']);
    $stmt->bindParam(':log_id', $_POST['log_id']);

    $stmt->execute();

    $stmt2 = $db->prepare("INSERT INTO notifications (log_id) VALUES (:Log_ID)");
    $stmt2->bindParam(':Log_ID', $_POST['log_id']);
    $stmt2->execute();

    ?>
    <script>window.location.href="dashboard.php"</script>
    <?php 
  } 
?>

<!DOCTYPE html>
<html lang="en">
  <?php
    include("includes/head.html");
  ?>
  <body class="nav-md" onload="startTime(), chart()">
    <div class="container body">
      <div class="main_container">
        <?php
          include("includes/body-nav.html");
        ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- ************ FIRST ROW *************** -->
          <!-- Administrator and Manager-->
          <?php
            if($_SESSION['access_type']=='Administrator' || $_SESSION['access_type']=='Manager'){
            ?>
              <!-- top tiles -->
              <div class="row tile_count">
                <?php 
                $current_date = date('m/d/Y');
                // total employees
                $employees = $db->prepare("SELECT count(*) FROM employees");
                $employees->execute();
                $total_employees = $employees->fetchColumn();

                //total teams
                $teams = $db->prepare("SELECT count(*) FROM team");
                $teams->execute();
                $total_teams = $teams->fetchColumn();

                // total male employees
                $male = $db->prepare("SELECT count(*) FROM employees WHERE gender='Male'");
                $male->execute();
                $total_male = $male->fetchColumn();

                // total female employees
                $female = $db->prepare("SELECT count(*) FROM employees WHERE gender='Female'");
                $female->execute();
                $total_female = $female->fetchColumn();

                // active users 
                $active = $db->prepare("SELECT count(DISTINCT employee_id) FROM logbook WHERE log_date='$current_date'");
                $active->execute();
                $total_active = $active->fetchColumn();

                function workingDaysMonth() {
                    $year = date('Y');
                    $month = date('m');
                    $ignore = array(0,6);
                    $count = 0;
                    $counter = mktime(0, 0, 0, $month, 1, $year);
                    while (date("n", $counter) == $month) {
                        if (in_array(date("w", $counter), $ignore) == false) {
                            $count++;
                        }
                        $counter = strtotime("+1 day", $counter);
                    }
                    return $count;
                }
                
                $total_month = workingDaysMonth();


                ?>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-users"></i> Total Employees</span>
                  <div class="count"><?php echo $total_employees; ?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-users"></i> Total Teams</span>
                  <div class="count"><?php echo $total_teams; ?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-male"></i> Total Males</span>
                  <div class="count"><?php echo $total_male; ?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-female"></i> Total Females</span>
                  <div class="count"><?php echo $total_female; ?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-calendar"></i> Active Users</span>
                  <div class="count"><?php echo $total_active; ?></div>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                  <span class="count_top"><i class="fa fa-calendar"></i> Work days this month</span>
                  <div class="count"><?php echo $total_month; ?></div>
                </div>
              </div>
              <!-- /top tiles -->
            <?php    
            }
          ?>
          <!-- ************ END FIRST ROW *************** -->

          <!-- ************ SECOND ROW *************** -->
          <div class="row">
            <!-- /// FIRST COLUMN /// -->
            <!-- Administrator and Manager-->
            <?php
              if($_SESSION['access_type']=='Administrator' || $_SESSION['access_type']=='Manager'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12" >
                  <div class="x_panel tile fixed_height_320" >
                    <div class="x_title ">
                      <h2>Team Attendance</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="overflow-y: auto; height:230px;">
                      <?php     
                      $temp = $db->prepare("SELECT count(*) FROM logbook where log_date='$current_date'");
                      $temp->execute();
                      $total = $temp->fetchColumn();
                     
                      $stmt = $db->prepare("SELECT DISTINCT *, count( DISTINCT logbook.employee_id) as total1 FROM logbook 
                                            JOIN employee_group ON logbook.employee_id = employee_group.employee_id 
                                            JOIN team ON employee_group.team_id=team.team_id where log_date='$current_date' AND logbook.employee_id!='1' GROUP BY team_name ");
                      $stmt->execute();

                      if($total > 0){
                      while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
                        $bar_length = ($row['total1'] / $total) * 100;
                        echo '<br>';
                      ?>
                      <div class="widget_summary">
                        <div class="w_left w_25">
                          <span><?php echo $row['team_name']; ?></span>
                        </div>
                        <div class="w_center w_55">
                          <div class="progress">
                            <div class="progress-bar bg-green" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $bar_length;?>%;">
                              <span class="sr-only"><?php echo $bar_length;?>% Complete</span>
                            </div>
                          </div>
                        </div>
                        <div class="w_right w_20">
                          <span><?php echo $row['total1']; ?></span>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                        
                      <?php
                        }
                      } else {
                      ?>
                          <center><h5>No records to show</h5></center>
                      <?php 
                        }
                      ?>           
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- Supervisor-->
            <?php
              if($_SESSION['access_type']=='Supervisor'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="x_panel tile fixed_height_320">
                    <div class="x_title">
                      <h2>Team Attendance</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="overflow-y: auto; height:230px;">
                      <div class="dashboard-widget-content">
                        <table class="countries_list"> 
                          <tbody> 
                            <?php
                              //Query team
                              $team_query = "SELECT team_id FROM team WHERE team_name='$team_name'";
                              $stmt = $db->prepare($team_query);
                              $stmt->execute();
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                              $team_id = $row['team_id'];

                              $query1 = "SELECT employee_id FROM employee_group WHERE team_id = '$team_id' AND employee_id!='1'";
                              $query2 = "SELECT * FROM employees WHERE employee_id IN ($query1)";
                              $TeamAtt = $db->prepare($query2);
                              $TeamAtt->execute();
                              while($row = $TeamAtt->fetch(PDO::FETCH_ASSOC)){ 
                                $queryGetLeaveDate = $db->prepare("SELECT * FROM leave_application");
                                $queryGetLeaveDate->execute();

                                $eID = $row['employee_id'] ;
                                $queryA = $db->prepare("SELECT logbook.*,waive.* FROM logbook,waive WHERE logbook.employee_id = '$eID' AND logbook.log_date = '$current_date' AND waive.status='Approved' AND logbook.log_id = waive.log_id");
                                $queryA->execute();
                                $rowA = $queryA->fetch(PDO::FETCH_ASSOC);

                                $queryB = $db->prepare("SELECT logbook.*,waive.* FROM logbook,waive WHERE logbook.employee_id = '$eID' AND logbook.log_date = '$current_date' AND waive.status='Disapproved' AND logbook.log_id = waive.log_id");
                                $queryB->execute();
                                $rowB = $queryB->fetch(PDO::FETCH_ASSOC);

                                $queryC = $db->prepare("SELECT * FROM logbook WHERE employee_id = '$eID' AND log_date = '$current_date'");
                                $queryC->execute();
                                $rowC= $queryC->fetch(PDO::FETCH_ASSOC);
                               
                                if ($rowA == TRUE) { ?>
                                  <tr>
                                    <td><?php echo $row['first_name'] ;?></td>
                                    <td><?php echo $rowA['in1'] ;?></td>
                                    <td>
                                      Not Late
                                    </td>
                                  </tr>
                                <?php  }elseif($rowB == TRUE){ ?>
                                  <tr>
                                    <td><?php echo $row['first_name'] ;?></td>
                                    <td><?php echo $rowB['in1'] ;?></td>
                                    <td>
                                      Late
                                    </td>
                                  </tr>
                                <?php }elseif($rowC == TRUE){ 
                                  if($rowC['in1_late'] > 0){ ?>
                                    <tr >
                                    <td><?php echo $row['first_name'] ;?></td>
                                    <td><?php echo $rowC['in1'] ;?></td>
                                    <td>
                                      <a href="#" data-toggle="modal" data-target="#waiveModal" 
                                      data-emp-id="<?php echo $row['employee_id'] ;?>" data-emp-name="<?php echo $row['first_name'].' '.$row['last_name'] ;?>"
                                      data-log-id="<?php echo $rowC['log_id'] ;?>" class="waiveDataModal">
                                        <span class="label label-success"><span class=""></span><strong>Waive</strong></span>
                                      </a> 
                                    </td>
                                    </tr >
                                  <?php }else{?>
                                  <tr >
                                  <td><?php echo $row['first_name'] ;?></td>
                                  <td><?php echo $rowC['in1'] ;?></td>
                                  <td></td>
                                  </tr >
                                  <?php } ?>
                                <?php }else{  ?>
                                <tr>
                                  <td><?php echo $row['first_name'] ;?></td>
                                  <td>Not Present</td>
                                </tr>
                                <?php while($row1 =$queryGetLeaveDate->fetch(PDO::FETCH_ASSOC)) {
                                  $lbEmpID = $row1['employee_id'];
                                  if($eID == $lbEmpID ){
                                    $startDate = substr($row1['leave_date'], 0, 10);
                                    $endDate = substr($row1['leave_date'], 21, 11); 
                                      $arr = array();
                                      $now = strtotime($startDate);
                                      $last = strtotime($endDate);
                                      $count =0;
                                      while($now <= $last ) {

                                        $arr[] = date('m/d/Y', $now);
                                        $now = strtotime('+1 day', $now);
                                        if(date('m/d/Y',$now) == $current_date){ ?>
                                        
                                          <td><?php echo $row1['leave_type'].' Leave' ?></td>
                                        
                                        <?php  $count++; }
                                      }
                                    }
                                  } echo "</tr>"; } 
                                } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- User-->
            <?php
              if($_SESSION['access_type']=='User'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="x_panel tile fixed_height_320">
                    <div class="x_title">
                      <h2>Daily Attendance</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <div class="dashboard-widget-content">
                        <table class="countries_list"> 
                          <tbody> 
                            <?php
                              $current_date = date('m/d/Y') ;
                              $emp_id = $_SESSION['employee_id'];
                              $dailyAttendanceQuery = "SELECT * FROM logbook WHERE log_date = '$current_date' and employee_id = '$emp_id'";
                              $stmt = $db->prepare($dailyAttendanceQuery);
                              $stmt->execute();
                              $row = $stmt->fetch(PDO::FETCH_ASSOC);
                              $log_in = $row['in1'];
                              $mbreak_starts = $row['break1_start'];
                              $mbreak_ends = $row['break1_end'];
                              $lbreak_starts = $row['out1'];
                              $lbreak_ends = $row['in2'];
                              $abreak_starts = $row['break2_start'];
                              $abreak_ends = $row['break2_end'];
                              $log_out = $row['out2'];
                              // echo '<prev>';
                              // print_r($row);
                              // echo '</prev>';
                            ?>
                            <tr >
                              <td>Log In</td>
                              <td class="fs15 fw700 text-right"><?php echo $log_in; ?></td>
                            </tr>
                            <tr>
                                <td>Log Out </td>
                                <td class="fs15 fw700 text-right"><?php echo $log_out; ?></td>
                            </tr>
                            <tr>
                              <td>Morning Break</td>
                              <td class="fs15 fw700 text-right"><?php echo "$mbreak_starts - $mbreak_ends"; ?></td>
                            </tr>
                            <tr>
                              <td>Lunch Break</td>
                              <td class="fs15 fw700 text-right"><?php echo "$lbreak_starts - $lbreak_ends"; ?></td>
                            </tr>
                            <tr>
                                <td>Afternoon Break </td>
                                <td class="fs15 fw700 text-right"><?php echo "$abreak_starts - $abreak_ends"; ?></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- /// END FIRST COLUMN /// -->

            <div class="">
            <!-- /// SECOND COLUMN /// -->

            <!-- Administrator and Manager-->
            <?php
              if($_SESSION['access_type']=='Administrator' || $_SESSION['access_type']=='Manager'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="x_panel tile fixed_height_320 overflow_hidden">
                    <div class="x_title">
                      <h2>Attendance Chart <small><a href="attendancereport.php">Reports</a></small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <table class="" style="width:100%">
                        <tr>
                          <th style="width:37%;">
                            <p></p>
                          </th>
                          <th>
                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                              <p class=""></p>
                            </div>
                          </th>
                        </tr>
                        <tr>
                          <td>
                            <style>
                             canvas {
                                 -moz-user-select: none;
                                 -webkit-user-select: none;
                                 -ms-user-select: none;
                             }
                             </style>
                            <canvas id="chart-area" height="140" width="140" style="margin: 10px 10px 10px 10px; "></canvas>
                            <?php 
                              // total present
                              $present = $db->prepare("SELECT count(*) FROM logbook WHERE log_date='$current_date' AND in1_late=0 AND employee_id!='1'");
                              $present->execute();
                              $total_present = $present->fetchColumn();

                              // total late
                              $late = $db->prepare("SELECT count(*) FROM logbook WHERE log_date='$current_date' AND in1_late>0 AND employee_id!='1'");
                              $late->execute();
                              $total_late = $late->fetchColumn();

                              // total vacation
                              $vacation = $db->prepare("SELECT * FROM leave_application WHERE leave_type='Vacation' and leave_status='Approved'");
                              $vacation->execute();
                              $total_vacation = 0;
                              $date_time = date('Y-m-d h:i A');
                              while($row = $vacation->fetch(PDO::FETCH_ASSOC)){
                                $date = $row['leave_date'];
                                $separator_pos = strpos($date,'-');
                                $start = substr($date,0,$separator_pos-1);
                                $end = substr($date,$separator_pos+2);
                                if(strtotime($date_time) >= strtotime($start) && strtotime($date_time) <= strtotime($end)){
                                  $total_vacation++;
                                }
                              }

                              // total sick
                              $sick = $db->prepare("SELECT * FROM leave_application WHERE leave_type='Sick' and leave_status='Approved'");
                              $sick->execute();
                              $total_sick = 0;
                              while($row = $vacation->fetch(PDO::FETCH_ASSOC)){
                                $date = $row['leave_date'];
                                $separator_pos = strpos($date,'-');
                                $start = substr($date,0,$separator_pos-1);
                                $end = substr($date,$separator_pos+2);
                                if(strtotime($date_time) >= strtotime($start) && strtotime($date_time) <= strtotime($end)){
                                  $total_sick++;
                                }
                              }

                              // total absent 
                              $absent = $db->prepare("SELECT count(*) FROM employees");
                              $absent->execute();
                              $total_absent = $absent->fetchColumn() - $total_present - $total_late - $total_sick - $total_vacation;
                            ?>
                            <script>
                               var config = {
                                   type: 'doughnut',
                                   data: {
                                       datasets: [{
                                           data: [
                                               <?php echo $total_present;?>,
                                               <?php echo $total_late;?>,
                                               <?php echo $total_vacation;?>,
                                               <?php echo $total_sick;?>,
                                               <?php echo $total_absent;?>,
                                           ],
                                           backgroundColor: [
                                               "#3498db",
                                               "#1abb9c",
                                               "#9b59b6",
                                               "#9cc2cb",
                                               "#e74c3c",
                                           ],
                                           label: 'Dataset 1'
                                       }],
                                       labels: [
                                           "Present",
                                           "Late",
                                           "Vacation",
                                           "Sick",
                                           "Absent"
                                       ]
                                   },
                                   options: {
                                       responsive: true,
                                       legend: {
                                           position: 'top',
                                       },
                                       title: {
                                           display: false,
                                           text: 'Chart.js Doughnut Chart'
                                       },
                                       animation: {
                                           animateScale: true,
                                           animateRotate: true
                                       }
                                   }
                               };
                               </script>
                          </td>
                          <td>
                            <table class="tile_info">
                              <tr>
                                <td>
                                  <p><i class="fa fa-square blue"></i>Present </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square green"></i>Late </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square purple"></i>Vacation </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square aero"></i>Sick </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square red"></i>Absent </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- Supervisor and User-->
            <?php
              if($_SESSION['access_type']=='User' || $_SESSION['access_type']=='Supervisor'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="x_panel tile fixed_height_320 overflow_hidden">
                    <div class="x_title">
                      <h2>Monthly Attendance</h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <table class="" style="width:100%">
                        <tr>
                          <td>
                           <style>
                            canvas {
                                -moz-user-select: none;
                                -webkit-user-select: none;
                                -ms-user-select: none;
                            }
                            </style>
                           <canvas id="chart-area" height="60px" width="60px" style="margin: 5px 1px 1px 0"></canvas>
                           <?php
                            // total monthly attendance For Present
                            $emp_id = $_SESSION['employee_id'];
                            $monthlyPresent = $db->prepare("SELECT * FROM logbook WHERE employee_id = '$emp_id' ");
                            $monthlyPresent->execute(); 
                            $totalPresent = 0;
                            while($row = $monthlyPresent->fetch(PDO::FETCH_ASSOC)){
                              if(date('m',strtotime($row['log_date'])) == date('m')){
                                $totalPresent++;
                              }
                            }

                            // total monthly attendance For Late
                            $monthLate = $db->prepare("SELECT * FROM logbook WHERE in1_late>0 AND employee_id = '$emp_id' ");
                            $monthLate->execute();
                            $totalLate = 0;
                            while($row = $monthLate->fetch(PDO::FETCH_ASSOC)){
                              if(date('m',strtotime($row['log_date'])) == date('m')){
                                $totalLate++;
                              }
                            }

                            // total monthly attendance For Vacation
                            $vacation = $db->prepare("SELECT * FROM leave_application WHERE leave_type='Vacation' AND leave_status='Approved' AND employee_id = '$emp_id' ");
                            $vacation->execute();
                            $totalVacation = 0;
                            while($data = $vacation->fetch(PDO::FETCH_ASSOC)){
                              if((substr($data['leave_date'], 0, 2) == date('m')) || (substr($data['leave_date'], 21, 2)) == date('m')){
                                $totalVacation++;
                              }
                            }

                            // total monthly attendance For Sick
                            $sick = $db->prepare("SELECT * FROM leave_application WHERE leave_type='Sick' and leave_status='Approved' AND employee_id = '$emp_id'");
                            $sick->execute();
                            $totalSick = 0;
                            while($data = $sick->fetch(PDO::FETCH_ASSOC)){
                              if((substr($data['leave_date'], 0, 2) == date('m')) || (substr($data['leave_date'], 21, 2)) == date('m')){
                                $totalSick++;
                              }
                            }
                            
                            // total monthly attendance For Absent
                            $year = date('Y');
                            $month = date('m');
                            $ignore = array(0,6);
                            $count = 0;
                            $list=array();
                            $counter = mktime(0, 0, 0, $month, 1, $year);
                            while (date("m", $counter) == $month) {
                              if (in_array(date("w", $counter), $ignore) == false) {
                                $count++;
                                if(date('Y-m-d-D', $counter) == date('Y-m-d-D')) {
                                  break;
                                } 
                              }
                              $counter = strtotime("+1 day", $counter);
                            }
                            $total_absent = $count - $totalPresent;
                          ?>
                            <script>
                              var config = {
                                  type: 'doughnut',
                                  data: {
                                      datasets: [{
                                          data: [
                                            <?php echo $totalPresent;?>,
                                            <?php echo $totalLate;?>,
                                            <?php echo $totalVacation;?>,
                                            <?php echo $totalSick;?>,
                                            <?php echo $total_absent;?>,
                                          ],
                                          backgroundColor: [
                                              "#3498db",
                                              "#1abb9c",
                                              "#9b59b6",
                                              "#9cc2cb",
                                              "#e74c3c",
                                          ],
                                          label: 'Dataset 1'
                                      }],
                                      labels: [
                                          "Present",
                                          "Late",
                                          "Vacation",
                                          "Sick",
                                          "Absent"
                                      ]
                                  },
                                  options: {
                                      responsive: true,
                                      legend: {
                                          position: 'top',
                                      },
                                      title: {
                                          display: false,
                                          text: 'Chart.js Doughnut Chart'
                                      },
                                      animation: {
                                          animateScale: true,
                                          animateRotate: true
                                      }
                                  }
                              };
                              </script>
                          </td>
                          <td>
                            <table class="tile_info">
                              <tr>
                                <td>
                                  <p><i class="fa fa-square blue"></i>Present </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square green"></i>Late </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square purple"></i>Vacation </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square aero"></i>Sick </p>
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <p><i class="fa fa-square red"></i>Absent </p>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- /// END SECOND COLUMN /// -->
            </div>

            <!-- /// THIRD COLUMN /// -->
            <!-- Administrator and Manager-->
            <?php
              if($_SESSION['access_type']=='Administrator' || $_SESSION['access_type']=='Manager'){
              ?>
                <div class="col-md-4 col-sm-4 col-xs-12" style="height=100%;">
                  <div class="x_panel fixed_height_320 overflow_hidden">
                    <div class="x_title">
                      <h2>To Do List <small>Memo</small></h2>
                      <div class="clearfix"></div>
                    </div>

                    <div class="x_content" style="overflow-y: auto; height:230px;">

                      <div class="">
                        <ul class="to_do">
                        <?php 
                          // memo
                        $emp_memo = $db->prepare("SELECT DISTINCT * FROM logbook JOIN employees ON logbook.employee_id = employees.employee_id where log_date='$current_date' and in1_late>0 AND logbook.employee_id!='1' GROUP BY logbook.employee_id ORDER BY first_name ASC");
                        $emp_memo->execute();

                        $result = $total_late;

                        if($result > 0){
                        while($row = $emp_memo->fetch(PDO::FETCH_ASSOC)){
                        ?>
                            <li>
                              <p> <center><?php echo $row['first_name'] . ' ' . $row['last_name'];?></center></p>
                            </li> 

                        <?php 
                            }
                          } else {
                        ?> 
                          <li>
                            <p> <center>No records to show</center></p>
                          </li> 
                        <?php 
                          }
                        ?>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
            ?>
            <!-- Supervisor and User-->
            <?php
              if($_SESSION['access_type']=='User' || $_SESSION['access_type']=='Supervisor'){
              ?>
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="x_panel fixed_height_320">
                      <div class="x_title">
                        <h2>Accumulated Lates</h2>
                        <ul class="nav navbar-right panel_toolbox">
                          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                          </li>
                          <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a href="#">Settings 1</a>
                              </li>
                              <li><a href="#">Settings 2</a>
                              </li>
                            </ul>
                          </li>
                          <li><a class="close-link"><i class="fa fa-close"></i></a>
                          </li>
                        </ul>
                        <div class="clearfix"></div>
                      </div>
                      <div style="height: 85%;overflow-y: auto;">
                        <div class="x_content">
                        <?php

                          $accumulated = $db->prepare("SELECT * FROM logbook WHERE employee_id = '$emp_id' AND in1_late>0 ORDER BY log_date DESC ");
                          $accumulated->execute();
                          $count = 0;
                          while ($rows = $accumulated->fetch(PDO::FETCH_ASSOC)) { ?>
                          <?php if (date('m',strtotime($rows['log_date'])) == date('m')) { $count++;?>
                            <article class="media event">
                              <a class="pull-left date">
                                <p class="month"><?php echo date('F',strtotime($rows['log_date']));?></p>
                                <p class="day"><?php echo date('d',strtotime($rows['log_date']));?></p>
                              </a>
                              <div class="media-body">
                                <a class="title" href="#"><?php echo $rows['in1'];?></a>
                                <p><?php echo $rows['in1_late'].' minutes late';?></p>
                              </div>
                            </article>
                          <?php
                            }
                            
                          }
                          if ($count >= 3) { ?>
                          <br>
                          <div>
                            <strong style="color:red;">SUBJECT FOR DISCIPLINARY ACTION</strong>
                          </div>  
                        <?php  }

                        ?>
                        </div>
                      </div>
                    </div>
                </div>
              <?php
              }
            ?>
            <!-- /// END THIRD COLUMN /// -->
          </div>
          <!-- ************ END SECOND ROW *************** -->

          <!-- ************ THIRD ROW *************** -->
          <div class="row">
            <div class="col-md-8 col-sm-8 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Announcements <?php if($_SESSION['access_type']=='Manager' || $_SESSION['access_type']=='Administrator'){?><small><a href="" data-toggle="modal" data-target="#addEntryModal" >Add Entry</a></small><?php } ?></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content" style="overflow-y: auto; height:430px;">
                  <ul class="list-unstyled timeline">
                    <?php 
                      $post_query = $db->prepare("SELECT * FROM announcement ORDER BY announcement_id DESC");
                      $post_query->execute();
                      $counter = 0;
                      while($row=$post_query->fetch(PDO::FETCH_ASSOC)){
                    ?>
                    <li>
                      <div class="block">
                        <div class="tags">
                          <a href="" class="tag">
                            <span><?php echo date('F d, Y',strtotime($row['date_added']))?></span>
                          </a>
                        </div>
                        <div class="block_content">
                          <h2>
                            <a><?php echo $row['title'];?></a>
                          </h2>
                          <div class="byline">
                            by <a><?php echo $row['announced_by']; ?></a>
                          </div>
                          <br>
                          <p>
                            <!--<?php $description=$row['description']; echo nl2br("".$row['description'].""); ?>-->
                            <?php echo $row['description'];?>
                          </p>
                          <center><img src="<?php echo $row['image']; ?>" class="img-responsive" ></center>
                        </div>
                      </div>
                    </li>
                    <?php
                      $counter++;
                      }
                      if($counter == 0){
                    ?>
                        <center><h5>No announcements at this time</h5></center>
                    <?php   
                      }
                    ?>
                  </ul>
                  <center><a href="announcement.php"><h5>Show all announcements</h5></a></center>
                </div>
              </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
              <div class="x_panel" style="height: 506px">
                <div class="x_title">
                  <h2>Birthdays</h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content" style="overflow-y: auto; height:230px;">
                <?php
                  $birthday = $db->prepare("SELECT distinct * FROM employees");
                  $birthday->execute();
                  $counter = 0;
                  while($row = $birthday->fetch(PDO::FETCH_ASSOC)){
                  if(date('m-d',strtotime($row['birth_date'])) == date('m-d')){
                ?>
                  <article class="media event">
                    <a class="pull-left date">
                      <p class="month"><?php echo date('F');?></p>
                      <p class="day"><?php echo date('d');?></p>
                    </a>
                    <div class="media-body">
                      <a class="title" href="#"><?php echo $row['first_name'] . ' ' . $row['last_name']?></a>
                      <p>Send a birthday greeting</p>
                    </div>
                  </article>
                <?php
                    $counter++;
                  }
                }
                if($counter == 0){
                ?>
                  <article class="media event">
                    <a class="pull-left date">
                      <p class="month"><?php echo date('F');?></p>
                      <p class="day"><?php echo date('d');?></p>
                    </a>
                    <div class="media-body">
                      <a class="title" href="#">No birthdays today</a>
                    </div>
                  </article>
                <?php
                }
                ?>
                </div>
              </div>
            </div>
          </div>
          <!-- ************ END THIRD ROW *************** -->
        </div>

        <!-- Modal for Announcement Entry -->
        <div class="modal fade" id="addEntryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">New entry</h4>
              </div>
              <div class="modal-body">
              <?php 

              if(isset($_POST['add_entry']))
              {
                function do_upload()
                {
                  $type = explode('.', $_FILES["img"]["name"]);
                  $type = strtolower($type[count($type)-1]);
                  $cwd = str_replace('/','\\', getcwd()); 
                  $url = uniqid(rand()).'.'.$type;
                  if(in_array($type, array("jpg", "jpeg", "gif", "png")))
                    if(is_uploaded_file($_FILES["img"]["tmp_name"]))
                      if(move_uploaded_file($_FILES["img"]["tmp_name"],"assets/images/announcements/" .$url)){
                        $result = "assets/images/announcements/" . $url;
                        return $result;
                      }
                  return "";
                }

                $entry = $db->prepare("INSERT INTO announcement (date_added, title, description,image) VALUES (:date_added,:title,:description,:img)");
                $entry->bindParam(':date_added',date('Y-m-d'));
                $entry->bindParam(':title',$_POST['title']);
                $entry->bindParam(':description',$_POST['description']);
                $entry->bindParam(':img',$url);
                //$varFormPostDescription = mysql_real_escape_string($_POST['description']);
                $url = do_upload();
                
                $entry->execute();        
              
              ?>
              <script>window.location.href="dashboard-manager.php"</script>
              <?php 
                }
              ?>
                <form method="POST" enctype="multipart/form-data">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Date</label>
                    <input type="text" class="form-control" value="<?php echo date('F d, Y');?>" name='date_added' readonly>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Title</label>
                    <input type="text" class="form-control" name='title' required>
                  </div>
                  <div class="form-group">
                    <label for="message-text" class="control-label">Description</label>
                    <textarea class="form-control" name='description' required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputFile">Upload Image <small>(1MB Max)</small></label>
                    <input type="file" name='img'>
                  </div>  
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-success" value="Add Entry" name='add_entry'>
              </div>
              </form>
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
  <div class="modal fade bs-example-modal-md" id="waiveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><center>Waive Application</center></h4>
        </div>
        <form data-parsley-validate class="form-horizontal form-label-left" method="POST">
          <div class="modal-body">
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee ID </label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <input type="text" class="form-control col-md-7 col-xs-12" name="lateEmp_id" value="empIDLate" readonly="readonly"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Employee Name</label>
              <div class="col-md-8 col-sm-6 col-xs-12"> 
                <input type="text" class="form-control col-md-7 col-xs-12" name="lateEmp_name" value="empNameLate" readonly="readonly"/>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Reason</label>
              <div class="col-md-8 col-sm-6 col-xs-12">
                <textarea class="form-control col-md-7 col-xs-12" name="waive_reason" ></textarea>
              </div>
            </div>
            <input type="hidden" name="log_id" value="logID" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <input type="submit" value="Send" class="btn btn-primary" name="addWaive" />
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    $(document).on("click", ".waiveDataModal", function () {
     $('input[value="logID"]').val($(this).data('log-id'));
     $('input[value="empIDLate"]').val($(this).data('emp-id'));
     $('input[value="empNameLate"]').val($(this).data('emp-name'));
    });
  </script>
</html>