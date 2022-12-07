<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="d-flex flex-column align-items-center" style="background-color: #FFFAF1">
    <h1 style="color: #3f2b96; margin-top: 20px">Student wise PLO Analysis</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      
        <input class="m-3" type="text" class="form-control" id="select1" placeholder="Student ID" name="student_id">
      

      <div class="dropdown" style="margin: 30px; display: inline">
        <select id="select3" name="course">
          <option value="#">Course ID</option>
          <option value="CSE303">CSE303/CSE303L</option>
        </select>
      </div>

      <input
        class="btn btn-primary"
        type="submit"
        value="View"
        style="margin: 30px; display: inline"
      />
    </form>

    
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $studID = $_POST['student_id'];
  $course = $_POST['course'];
  if (empty($studID) || empty($course)) {
    echo "Select from drop down menu or Give Input!";
  }
else{
    $servername = "localhost";
    $username = "username";
    $password = "";
    $database = "spms";

    $connection = new mysqli($servername, $username, $password, $database);

    if($connection->connect_error){
        die("Connection failed: " . $connection->connect_error);
    }

    
    $sql1="SELECT (CO1/(SELECT MAX(CO1)FROM course_ms))*100 as CO1,(CO2/(SELECT MAX(CO2)FROM course_ms))*100 as CO2,(CO3/(SELECT MAX(CO3)FROM course_ms))*100 as CO3,(CO4/(SELECT MAX(CO4)FROM course_ms))*100 as CO4 FROM course_ms WHERE ID='$studID' AND `Course ID`='$course';";
    $result = $connection->query($sql1);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
        $co1 = (int)$data['CO1'];
        $co2 = (int)$data['CO2'];
        $co3 = (int)$data['CO3'];
        $co4 = (int)$data['CO4'];
    }

    $sql2="SELECT CLO1,CLO2,CLO3,CLO4 FROM `course_outine` WHERE `Course ID`='$course';";
    $result = $connection->query($sql2);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }

    foreach($result as $data){
      $clo1 = $data['CLO1'];
      $clo2 = $data['CLO2'];
      $clo3 = $data['CLO3'];
      $clo4 = $data['CLO4'];
    }

  }
}
?>
  <div style="width: 800px">
    <canvas id="myChart"></canvas>
  </div>

  <script>
    const ctx = document.getElementById('myChart');

    const labels = [<?php echo json_encode($clo1)?>,<?php echo json_encode($clo2)?>,<?php echo json_encode($clo3)?>,<?php echo json_encode($clo4)?>];
const data = {
  labels: labels,
  datasets: [{
    data: [<?php echo json_encode($co1)?>, <?php echo json_encode($co2)?>, <?php echo json_encode($co3)?>, <?php echo json_encode($co4)?>],
    backgroundColor: [
      'rgba(255, 99, 132, 0.5)',
      'rgba(255, 159, 64, 0.5)',
      'rgba(255, 205, 86, 0.5)',
      'rgba(75, 192, 192, 0.5)',
    ],
    borderColor: [
      'rgb(255, 99, 132)',
      'rgb(255, 159, 64)',
      'rgb(255, 205, 86)',
      'rgb(75, 192, 192)',
    ],
    borderWidth: 1
  }]
};



const config = {
type: 'bar',
data: data,
options: {
  responsive: true,
  plugins: {
    legend: {
      display: false,
      position: 'bottom',
    }
  }
},
};

    new Chart(ctx, config);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
