<?php include 'filesLogic.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <link rel="stylesheet" href="style.css">
    <title>Question Bank </title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <form action="index.php" method="post" enctype="multipart/form-data" >
          <h3>Upload Question</h3>
          <input type="file" name="myfile"> <br>
          <button type="submit" name="save">upload</button>
        </form>
      </div>
    </div>
  </body>
</html>