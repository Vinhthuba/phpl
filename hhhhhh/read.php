<?php
//check existence
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    //include config
    require_once  'config.php';
    //prepare a select statement
    $sql = "SELECT * FROM employees WHERE id=?";

    if($stmt = mysqli_prepare($link, $sql)){
        //bind variables to the prepared
        mysqli_stmt_bind_param($stmt,"i",$param_id);

        //set parameters
        $param_id = trim($_GET["id"]);

        //attemp to the execute the prepared
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result)==1){

                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                $name = $row["name"];
                $address = $row["address"];
                $salary = $row["salary"];
            } else {
                header("location: error.php");
                exit();
            }

        }
        else{
            echo "Oops! Something went wrong. PLease try again later.";
        }
    }
    mysqli_stmt_close($stmt);

    mysqli_close($link);
} else{
    header("location: error.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>View Record</h1>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <p class="form-control-static"><?php echo $row["name"];?></p>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <p class="form-control-static"><?php echo $row["address"];?></p>
                </div>

                <div class="form-group">
                    <label>Salary</label>
                    <p class="form-control-static"><?php echo $row["salary"];?></p>
                </div>
                <p><a href="index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>