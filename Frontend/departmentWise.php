<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>
  <body class="d-flex flex-column align-items-center" style="background-color: #FFE6EE">
    <h1 style="color: #3f2b96; margin-top: 20px">Department Wise Enrollment in SETS</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
      <div class="dropdown" style="margin: 30px; display: inline">
        <select id="select1" name="semester">
          <option value="#">Semester</option>
          <option value="Spring">Spring</option>
          <option value="Summer">Summer</option>
          <option value="Autumn">Autumn</option>
        </select>
      </div>

      <div class="dropdown" style="margin: 30px; display: inline">
        <select id="select2" name="year">
          <option value="#">Year</option>
          <option value="2020">2020</option>
          <option value="2021">2021</option>
        </select>
      </div>

      <input
        class="btn btn-primary"
        type="submit"
        value="View"
        style="margin: 30px; display: inline"
      />
    </form>

    

<table class="table" style="width: 800px">
      <thead>
        <tr>
          <th scope="col">CSE</th>
          <th scope="col">EEE</th>
          <th scope="col">PS</th>
          <th scope="col">SETS</th>
      </thead>
      <tbody>
        <?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // collect value of input field
  $semester = $_POST['semester'];
  $year = $_POST['year'];
  if (empty($semester) || empty($year)) {
    echo "Select from drop down menu!";
  }
  else if($semester=='Summer' && $year=='2020'){
    echo "No Data";
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

    $sql = "SELECT 
    SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'CCR%' OR C.courseId LIKE 'CNC%' OR C.courseId LIKE 'CEN%' OR  C.courseId LIKE 'SEN%' OR C.courseId LIKE 'CIS%' OR C.courseId LIKE 'CSC%' OR C.courseId LIKE 'CSE%' THEN CS.enrolled*C.credit_hour END) as CSE,
    
    SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'ETE%' OR C.courseId LIKE 'ECR%' OR C.courseId LIKE 'EEE%' THEN CS.enrolled*C.credit_hour END) as EEE,
    
    SUM(CASE WHEN C.school_title='SETS' AND C.courseId LIKE 'PHY%' OR C.courseId LIKE 'MAT%' THEN CS.enrolled*C.credit_hour END) as PS,
    
    SUM(CASE WHEN C.school_title='SETS' THEN CS.enrolled*C.credit_hour END) as SETS FROM course_section as CS, course as C WHERE CS.courseId=C.courseId AND semester = '$semester' AND year = '$year' AND CS.blocked IN ('-1', '0') GROUP BY year, semester ORDER BY semester;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }

    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[CSE]</td>
          <td>$row[EEE]</td>
          <td>$row[PS]</td>
          <td>$row[SETS]</td>
        </tr>
        "; }

        foreach($result as $data){
          $cse = $data['CSE'];
          $eee = $data['EEE'];
          $ps = $data['PS'];
          $sets = $data['SETS'];
        }
      }
      }
?>
      </tbody>
    </table>

    <div style="width: 800px; margin: 50px">
      <canvas id="myChart"></canvas>
    </div>

    <script>
      const ctx = document.getElementById('myChart');


const labels = ["CSE","EEE","PS","SETS"]
const data = {
  labels: labels,
  datasets: [{
    axis: 'y',
    label: 'My First Dataset',
    data: [<?php echo json_encode($cse)?>, <?php echo json_encode($eee)?>, <?php echo json_encode($ps)?>, <?php echo json_encode($sets)?>],
    fill: false,
    backgroundColor: [
        'rgba(54, 162, 235, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(153, 102, 255, 0.2)',
      'rgba(255, 99, 132, 0.2)',
    ],
    borderColor: [
        'rgb(54, 162, 235)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
      'rgb(255, 99, 132)',
    ],
    borderWidth: 1
  }]
};

const config = {
  type: 'bar',
  data,
  options: {
    indexAxis: 'y',
    responsive: true,
  plugins: {
    legend: {
      display: false,
    }
  }
}
};

new Chart(ctx, config);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>