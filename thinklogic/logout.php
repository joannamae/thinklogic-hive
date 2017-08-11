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
          include("datediff.php")
        ?>
        <div class="right_col" role="main">
          <div class="row">
            <input style="display:none" value="<?php echo $_SESSION['log_id']; ?>" name="log_id" />
            <?php
              $log_id=$_SESSION['log_id'];
              $out2=date("h:i:sa");
              $stmt = $db->prepare("UPDATE logbook SET out2='$out2' WHERE log_id='$log_id'");
              $stmt->execute();

              //Query Log Details
              $log_query = "SELECT * FROM logbook WHERE log_id='$log_id' ORDER BY log_id DESC LIMIT 1";
              $stmt = $db->prepare($log_query);
              $stmt->execute(array('log_id' => $log_id));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $out2 = $row['out2'];

              if(strtotime($out2) >= strtotime($_SESSION['log_out1']) AND strtotime($out2) < strtotime($_SESSION['log_in2'])){
                $log_out1 = $_SESSION['log_out1'];
                $stmt = $db->prepare("UPDATE logbook SET out2='$log_out1' WHERE log_id='$log_id'");
                $stmt->execute();
              }

              else if (strtotime($out2) >= strtotime($_SESSION['log_out2'])){
                $log_out2 = $_SESSION['log_out2'];
                $stmt = $db->prepare("UPDATE logbook SET out2='$log_out2' WHERE log_id='$log_id'");
                $stmt->execute();
              }

              //Query Log Details
              $timediff = "SELECT * FROM logbook WHERE log_id='$log_id'";
              $stmt = $db->prepare($timediff);
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

              $shift_id = $_SESSION['shift_id'];

              //Query Shift Schedule
              $shift_query = "SELECT * FROM shift_schedule WHERE shift_id='$shift_id'";
              $stmt = $db->prepare($shift_query);
              $stmt->execute(array('shift_id' => $shift_id));
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $log_in1 = $row['in1'];
              $log_out1 = $row['out1'];
              $log_in2 = $row['in2'];
              $log_out2 = $row['out2'];

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
                session_destroy();
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
                  session_destroy();
                }
              }
            ?>
            <script>window.location.href="login.php"</script>
          </div>
        </div>
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