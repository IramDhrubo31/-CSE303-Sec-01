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
    <h1 style="color: #3f2b96; margin-top: 20px">Course wise PLO Analysis</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <div class="dropdown" style="margin: 30px; display: inline">
        <select id="select1" name="semester">
          <option value="#">Semester</option>
          <!-- <option value="Spring">Spring</option> -->
          <option value="Summer22">Summer 2022</option>
          <!-- <option value="Autumn">Autumn</option> -->
        </select>
      </div>

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
  $semester = $_POST['semester'];
  $course = $_POST['course'];
  if (empty($semester) || empty($course)) {
    echo "Select from drop down menu!";
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

    $sql1="SELECT  
    COUNT((CO1/(SELECT MAX(CO1)FROM course_ms))*100) as CO1
    FROM course_ms WHERE Semester = '$semester' AND `Course ID`='CSE303' AND (CO1/(SELECT MAX(CO1)FROM course_ms))*100>=40 AND `ID` IS NOT NULL;";

    $result = $connection->query($sql1);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
      $co1 = (int)$data['CO1'];
    }

    $sql2="SELECT  
    COUNT((CO2/(SELECT MAX(CO2)FROM course_ms))*100) as CO2
    FROM course_ms WHERE Semester = '$semester' AND `Course ID`='CSE303' AND (CO2/(SELECT MAX(CO2)FROM course_ms))*100>=40 AND `ID` IS NOT NULL;";
    $result = $connection->query($sql2);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
      $co2 = (int)$data['CO2'];
    }

    $sql3="SELECT  
    COUNT((CO3/(SELECT MAX(CO3)FROM course_ms))*100) as CO3
    FROM course_ms WHERE Semester = '$semester' AND `Course ID`='CSE303' AND (CO3/(SELECT MAX(CO3)FROM course_ms))*100>=40 AND `ID` IS NOT NULL;";
    $result = $connection->query($sql3);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
      $co3 = (int)$data['CO3'];
    }

    $sql4="SELECT  
    COUNT((CO4/(SELECT MAX(CO4)FROM course_ms))*100) as CO4
    FROM course_ms WHERE Semester = '$semester' AND `Course ID`='CSE303' AND (CO4/(SELECT MAX(CO4)FROM course_ms))*100>=40 AND `ID` IS NOT NULL;";
    $result = $connection->query($sql4);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
      $co4 = (int)$data['CO4'];
    }

               
    $sql5="SELECT COUNT(ID) as Total FROM course_ms WHERE ID IS NOT NULL AND Semester='$semester';";
    $result = $connection->query($sql5);
    if (!$result){
        die("Invalid query: " . $connection->error);
    }
    foreach($result as $data){
      $total = (int)$data['Total'];
    }

    $co_a = [$co1, $co2, $co3, $co4];
    $co_na = [$total-$co1, $total-$co2, $total-$co3, $total-$co4];

    $sql6="SELECT CLO1,CLO2,CLO3,CLO4 FROM `course_outine` WHERE `Course ID`='$course';";
    $result = $connection->query($sql6);
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

  
    const DATA_COUNT = 5;
    const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

    const labels = [<?php echo json_encode($clo1)?>,<?php echo json_encode($clo2)?>,<?php echo json_encode($clo3)?>,<?php echo json_encode($clo4)?>];
    const data = {
    labels: labels,
    datasets: [
      {
        label: 'Achieved',
        data: <?php echo json_encode($co_a)?>,
        backgroundColor: 
          'rgba(0, 153, 0, 0.5)',
          borderColor: 
          'rgba(0, 153, 0)',
      },
      {
        label: 'Not Achieved',
        data: <?php echo json_encode($co_na)?>,
        backgroundColor: 
          'rgba(204, 0, 0, 0.5)',
          borderColor: 
          'rgba(204, 0, 0)',
      }
    ]
    };


    const config = {
    type: 'bar',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'PLO Analysis'
        }
      }
    },
    };

    new Chart(ctx, config);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
