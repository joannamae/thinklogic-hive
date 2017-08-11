<!-- Check if user is logged in -->
<?php 
  if(isset($_SESSION['username']))
  {
    if($_SESSION['access_type'] == 'User'){
      header('location: dashboard-user.php');
    }
    else if($_SESSION['access_type'] == 'Supervisor'){
      header('location: dashboard-supervisor.php');
    }
    else if($_SESSION['access_type'] == 'Manager' || $_SESSION['access_tpye'] == 'Administrator'){
      header('location: dashboard-manager.php');
    }
  }
?>
<!DOCTYPE html>
<html >
  <head>
    <meta charset="UTF-8">
    <link href="assets/login/css/style.css" rel="stylesheet">
    <link href="assets/login/css/alert.css" rel="stylesheet">
    <link rel="shortcut icon" type="image/png" href="assets/images/hive-ico.png"/>
    <title>ThinkLogic Agent Portal</title>
  </head>
  <body>
    <div class="">
      <div class="header">
        <div>
          <img src="assets/images/hive.png" height="93" width="300" class="back" alt=""/>
        </div>
      </div>
      <br>
      <?php
        include "config.php";

      if(isset($_POST['username']) && isset($_POST['password']))
      {
        $username = $_POST['username'];
        $password = md5($_POST['password']);

        //All Database Queries go here
        //Query access_registry
        $register_query = "SELECT * FROM access_registry WHERE username=:username AND password=:password";
        $stmt = $db->prepare($register_query);
        $stmt->execute(array('username' => $username, 'password' => $password));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user = $row['username'];
        $pass = $row['password'];
        $employee_id = $row['employee_id'];
        $access_id = $row['access_id'];

        //Query access_control
        $access_query = "SELECT * FROM access_control WHERE access_id=:access_id";
        $stmt = $db->prepare($access_query);
        $stmt->execute(array('access_id' => $access_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $access_id = $row['access_id'];
        $access_type = $row['access_type'];

        //Query employees
        $employee_query = "SELECT * FROM employees WHERE employee_id=:employee_id";
        $stmt = $db->prepare($employee_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $row['first_name'];
        $middle_name = $row['middle_name'];
        $last_name = $row['last_name'];
        $gender = $row['gender'];
        $civil_status = $row['civil_status'];
        $birth_date = $row['birth_date'];
        $age = $row['age'];
        $address = $row['address'];
        $contact_no = $row['contact_no'];

        //Query employment
        $employment_query = "SELECT * FROM employment WHERE employee_id=:employee_id";
        $stmt = $db->prepare($employment_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $job_title = $row['job_title'];
        $shift_id = $row['shift_schedule'];
        $basic_salary = $row['basic_salary'];

        //Query Shift Schedule
        $shift_query = "SELECT * FROM shift_schedule WHERE shift_id=:shift_id";
        $stmt = $db->prepare($shift_query);
        $stmt->execute(array('shift_id' => $shift_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $log_in1 = $row['in1'];
        $log_out1 = $row['out1'];
        $log_in2 = $row['in2'];
        $log_out2 = $row['out2'];

        //Query employee group
        $empgroup_query = "SELECT * FROM employee_group WHERE employee_id=:employee_id";
        $stmt = $db->prepare($empgroup_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $team_id = $row['team_id'];

        //Query team
        $team_query = "SELECT * FROM team WHERE team_id=:team_id";
        $stmt = $db->prepare($team_query);
        $stmt->execute(array('team_id' => $team_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $team_name = $row['team_name'];

        //Query leave credits
        $vlcredits_query = "SELECT * FROM leave_credits WHERE employee_id=:employee_id";
        $stmt = $db->prepare($vlcredits_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $leave_type = $row['leave_type'];
        $leave_credits = $row['leave_credits'];

        //Query benefits
        $benefits_query = "SELECT * FROM benefits WHERE employee_id=:employee_id";
        $stmt = $db->prepare($benefits_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $tin = $row['tin_no'];
        $sss = $row['sss_no'];

        //Query employee image
        $empblob_query = "SELECT * FROM employee_blob WHERE employee_id=:employee_id";
        $stmt = $db->prepare($empblob_query);
        $stmt->execute(array('employee_id' => $employee_id));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $employee_picture = $row['employee_picture'];

        if($username==$user && $pass==$password)
        {
          //Query access_registry
          $query = "SELECT * FROM access_registry WHERE username=:username AND password=:password";
          $stmt = $db->prepare($query);
          $stmt->execute(array('username' => $username, 'password' => $password));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $employee_id = $row['employee_id'];

          if(isset($_POST['login'])){
            $current_date = date('m/d/Y');
            $checkLogin = $db->prepare("SELECT count(*) FROM logbook WHERE log_date=:log_date AND employee_id=:employee_id");
            $checkLogin->execute(array('log_date' => $current_date, 'employee_id' => $employee_id));
            $hasLogin = $checkLogin->fetchColumn();

            if(!($hasLogin)) {  
              $stmt = $db->prepare("INSERT INTO logbook (employee_id, log_day, log_date, in1) VALUES (:Employee_ID, :Log_Day, :Log_Date, :in1)");

              $stmt->bindParam(':Employee_ID', $Employee_ID);
              $stmt->bindParam(':Log_Day', $Log_Day);
              $stmt->bindParam(':Log_Date', $Log_Date);
              $stmt->bindParam(':in1', $Log_In);

              $Employee_ID = $employee_id;
              $Log_Day = $_POST['log_day'];
              $Log_Date = $_POST['log_date'];
              $Log_In = date("h:i:sa");

              $stmt->execute();
            }
          }

          //Query Log Details
          $log_query = "SELECT * FROM logbook WHERE employee_id=:employee_id ORDER BY log_id DESC LIMIT 1";
          $stmt = $db->prepare($log_query);
          $stmt->execute(array('employee_id' => $employee_id));
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $log_id = $row['log_id'];
          $in1 = $row['in1'];

          if (strtotime($in1) <= strtotime($log_in1)){
            $stmt = $db->prepare("UPDATE logbook SET in1='$log_in1' WHERE log_id='$log_id'");
            $stmt->execute();
          }
          else if (strtotime($in1) > strtotime($log_in1) AND strtotime($in1) < strtotime($log_out1)){
            include "datediff.php";
            $in1_late = dateDiff(strtotime($in1), strtotime($log_in1));
            $stmt = $db->prepare("UPDATE logbook SET in1_late='$in1_late' WHERE log_id='$log_id'");
            $stmt->execute();
          }
          else if (strtotime($in1) > strtotime($log_out1) AND strtotime($in1) <= strtotime($log_in2)){
            $stmt = $db->prepare("UPDATE logbook SET in1='$log_in2' WHERE log_id='$log_id'");
            $stmt->execute();
          }
          else if (strtotime($in1) > strtotime($log_in2)){
            include "datediff.php";
            $in1_late = dateDiff(strtotime($in1), strtotime($log_in2));
            $stmt = $db->prepare("UPDATE logbook SET in1_late='$in1_late' WHERE log_id='$log_id'");
            $stmt->execute();
          }

          session_start();

          $_SESSION['username'] = $user;
          $_SESSION['password'] = $pass;
          //Access Type
          $_SESSION['access_id'] = $access_id;
          $_SESSION['access_type'] = $access_type;
          //Employee ID
          $_SESSION['employee_id'] = $employee_id;
          //Log
          $_SESSION['log_id'] = $log_id;
          //Employee
          $_SESSION['first_name'] = $first_name;
          $_SESSION['middle_name'] = $middle_name;
          $_SESSION['last_name'] = $last_name;
          $_SESSION['gender'] = $gender;
          $_SESSION['civil_status'] = $civil_status;
          $_SESSION['birth_date'] = $birth_date;
          $_SESSION['age'] = $age;
          $_SESSION['address'] = $address;
          $_SESSION['contact_no'] = $contact_no;
          //Employment
          $_SESSION['job_title'] = $job_title;
          $_SESSION['shift_id'] = $shift_id;
          $_SESSION['basic_salary'] = $basic_salary;
          //Shift Schedule
          $_SESSION['log_in1'] = $log_in1;
          $_SESSION['log_out1'] = $log_out1;
          $_SESSION['log_in2'] = $log_in2;
          $_SESSION['log_out2'] = $log_out2;
          //Team
          $_SESSION['team_id'] = $team_id;
          $_SESSION['team_name'] = $team_name;
          //Leave
          $_SESSION['leave_type'] = $leave_type;
          $_SESSION['leave_credits'] = $leave_credits;
          //Benefits
          $_SESSION['tin'] = $tin;
          $_SESSION['sss'] = $sss;
          //Employee Blob
          if($employee_picture == NULL) $_SESSION['employee_picture'] = 'assets/images/default.jpg';
          else $_SESSION['employee_picture'] = $employee_picture;

          // Dashboard
          ?>
          <script>window.location.href="dashboard.php"</script>
          <?php
        }
        else
        {
          ?>
          <div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <center>
          <strong>Sorry!</strong> Incorrect username and password.
          </center>
          </div>
          <?php
        }
      }
      ?>
      <div class="login">
        <form method="POST">
          <input style="display:none" value="<?php date_default_timezone_set('Asia/Singapore'); $date=date("F j, Y"); echo $date = date('m/d/Y' ,strtotime($date)); ?>" name="log_date" />
          <input style="display:none" value="<?php echo date("l"); ?>" name="log_day" />
          <input type="text" placeholder="Username" name="username">
          <input type="password" placeholder="Password" name="password">
          <input type="submit" value="Login" class="btn btn-primary" name="login" />
        </form>
      </div>
    </div>  
    <?php
      $getQuery = $db->prepare("SELECT break_date FROM bio_break");
      $getQuery->execute();
      while ($row1 = $getQuery->fetch(PDO::FETCH_ASSOC)) {
        $date = $row1['break_date'];
        $current_date = date('m/d/Y');
        if ($date != $current_date) {
          $query = $db->prepare("UPDATE bio_break SET break_remaining = ? , break_date = ?");
          $break_remaining = 180;
          $query->bindParam(1,$break_remaining);
          $query->bindParam(2,$current_date);
          $query->execute();
        }
      }
    ?> 
  </body>
</html>