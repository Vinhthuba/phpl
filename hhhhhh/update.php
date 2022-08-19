<?php
require_once 'config.php';

// define variables ....
$name = $address = $salary = "";
$name_err = $address_err = $salary_err = "";

//processing form
if(isset($_POST["id"]) && !empty($_POST["id"])) {
    //get hidden input value
    $id = $_POST["id"];

    //Validate name
    $input_name= trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif (!filter_var(trim($_POST["name"]),FILTER_VALIDATE_REGEXP,array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $name_err = 'Please enter a valid name';
    } else{
        $name=$input_name;
    }
    // validate address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = 'please enter an address';
    } else{
        $address = $input_address;
    }
    //validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount. ";
    } elseif(!ctype_digit($input_salary)) {
        $salary_err = 'Please enter a positive integer value';
    } else{
        $salary = $input_salary;
    }

    //check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err)){
        //repare an insert statement
        $sql = "UPDATE employees SET name=?, address=?, salary=? WHERE id=?";
        $link =mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

        if($stmt = mysqli_prepare($link,$sql)){
            //bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"sssi",$param_name,$param_address,$param_salary,$param_id);

            //set parameters
            $param_name=$name;
            $param_address = $address;
            $param_salary = $salary;
            $param_id = $id;

            //Attempt to execute th prepared statement
            if(mysqli_stmt_execute($stmt)){
                //Records updated successfully.
                header("location: index.php");
                exit();
            } else{
                echo "something went wrong. Please try again later.";
            }
        }
        //close statement
        mysqli_stmt_close($stmt);
    }
    //close connection
    mysqli_close($link);
} else{
    //check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        //get url parameter
        $id = trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ? ";
        $link =mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME);

        if($stmt = mysqli_prepare($link, $sql)){
            //bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt,"i",$param_id);

            //set parameters
            $param_id = $id;
            //attempt to execute
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result)==1){
                    $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

                    //retrieve individual
                    $name = $row["name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                } else {
                    header("location : error.php");
                    exit();
                }

            }else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        //close statement
        mysqli_stmt_close($stmt);

        //close connection
        mysqli_close($link);
    } else{
        header("location: error.php");
        exit();
    }
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
            width:500px;
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
                    <h2>Update Record</h2>
                </div>
                <p>Please edit the input values and submit to update the record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                        <lable>Name</lable>
                        <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                        <span class="help-block"><?php echo $name_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                        <lable>Address</lable>
                        <textarea name="address" class="form-control"><?php echo $address;?></textarea>
                        <span class="help-block"><?php echo $address;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                        <lable>Salary</lable>
                        <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                        <span class="help-block"><?php echo $salary_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>