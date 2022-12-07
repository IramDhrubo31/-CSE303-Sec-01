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
    <h1 style="color: #3f2b96; margin-top: 20px">Enrollment wise course distribution among the schools</h1>
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
          <th scope="col">Enrollment</th>
          <th scope="col">SBE</th>
          <th scope="col">SELS</th>
          <th scope="col">SETS</th>
          <th scope="col">SLASS</th>
          <th scope="col">SPPH</th>
          <th scope="col">Total</th>
        </tr>
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
    CASE WHEN enrolled BETWEEN 1 AND 10 THEN '1-10'
    WHEN enrolled BETWEEN 11 AND 20 THEN '11-20'
    WHEN enrolled BETWEEN 21 AND 30 THEN '21-30'
    WHEN enrolled BETWEEN 31 AND 35 THEN '31-35'
    WHEN enrolled BETWEEN 36 AND 40 THEN '36-40'
    WHEN enrolled BETWEEN 41 AND 50 THEN '41-50'
    WHEN enrolled BETWEEN 51 AND 55 THEN '51-55'
    WHEN enrolled BETWEEN 56 AND 60 THEN '56-60'
    WHEN enrolled > 60 THEN '60+' 
    END AS Enrollment,
    COUNT(CASE WHEN course.school_title='SBE' THEN 'SBE' END) AS SBE,
    COUNT(CASE WHEN course.school_title='SELS' THEN 'SELS' END) AS SELS,
    COUNT(CASE WHEN course.school_title='SETS' THEN 'SETS' END) AS SETS,
    COUNT(CASE WHEN course.school_title='SLASS' THEN 'SLASS' END) AS SLASS,
    COUNT(CASE WHEN course.school_title='SPPH' THEN 'SPPH' END) AS SPPH,
    COUNT(course_section.courseId) AS TOTAL
    FROM course_section, course
    WHERE semester = '$semester' AND year = '$year' AND course_section.courseId=course.courseId
    GROUP BY enrollment
    HAVING enrollment IS NOT NULL;";
    $result = $connection->query($sql);

    if (!$result){
        die("Invalid query: " . $connection->error);
    }

    while($row = $result->fetch_assoc()){ echo "
        <tr>
          <td>$row[Enrollment]</td>
          <td>$row[SBE]</td>
          <td>$row[SELS]</td>
          <td>$row[SETS]</td>
          <td>$row[SLASS]</td>
          <td>$row[SPPH]</td>
          <td>$row[TOTAL]</td>
        </tr>
        "; }

        foreach($result as $data){
          $enrollment[] = $data['Enrollment'];
          $sbe[] = $data['SBE'];
          $sels[] = $data['SELS'];
          $sets[] = $data['SETS'];
          $slass[] = $data['SLASS'];
          $spph[] = $data['SPPH'];
        }
      }
      }
?>
      </tbody>
    </table>

    <div style="width: 800px">
      <canvas id="myChart"></canvas>
    </div>

    <script>
      const ctx = document.getElementById('myChart');

  
const DATA_COUNT = 5;
const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

const labels = <?php echo json_encode($enrollment)?>;
const data = {
labels: labels,
datasets: [
  {
    label: 'SBE',
    data: <?php echo json_encode($sbe)?>,
  },
  {
    label: 'SELS',
    data: <?php echo json_encode($sels)?>,
  },
  {
    label: 'SETS',
    data: <?php echo json_encode($sets)?>,
  },
  {
    label: 'SLASS',
    data: <?php echo json_encode($slass)?>,
  },
  {
    label: 'SPPH',
    data: <?php echo json_encode($spph)?>,
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
      text: 'Class size distribution'
    }
  }
},
};

new Chart(ctx, config);
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>
