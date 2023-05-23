<?php

session_start();
require_once("config.php");


$_SESSION['url'] = $_SERVER['REQUEST_URI'];
if(!isset($_SESSION["un"]))
{
  header("Location:login.php");
}

if(isset($_SESSION['un']))
{
  $username=$_SESSION['un'];
}


$mysql="SELECT  status from user WHERE name='$username'";
$snd=mysqli_query($con,$mysql);
$arrow=mysqli_fetch_array($snd);

$st=$arrow['status'];


$access=0;






?>

<!DOCTYPE html>
<html>
<head>
  
    
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Announcement</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="img/ruet.png">
        <script src="js/vendor/modernizr-2.8.3.min.js"></script>
        <script src="js/vendor/jquery-1.12.0.min.js"></script>
        <script src="bootstrap-3.3.7/js/bootstrap.min.js" </script>
        <script src="bootstrap-3.3.7/js/bootstrap.js" </script>







</head>
<body>
<div class="main">
 <div class="row">
  <div class="col-sm-12">
  <nav class="shadow navbar navbar-inverse navbar-fixed-top nbar">
    <div class="navbar-header">
      <a class="navbar-brand lspace" href="home.php">RUET OJ</a>
       <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-menubuilder"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button>
    </div>
    <div class="collapse navbar-collapse navbar-menubuilder">
    <ul class="nav navbar-nav">
      <li class="space"><a href="compiler.php"><i class="fa fa-code ispace"></i>Compiler</a></li>
      <li class="space"><a href="archive.php"><i class="fa fa-archive ispace"></i>Problem Archive</a></li>
      <li class="space"><a href="contest.php"><i class="fa fa-cogs ispace"></i>Contests</a></li>
      <li class="space"><a href="#"><i class="fa fa-check-square ispace"></i>Debug</a></li>
      <li class="lgspace space"><a href="profile.php?user=<?php echo("$username"); ?>"><i class="fa fa-user ispace"></i><?php echo("$username"); ?></a></li>
      <li class="space"><a href="logout.php"><i class="fa fa-power-off ispace"></i>Logout</a></li>
      
    </ul>
    </div>
</nav>
</div>
</div>



<div class="row log">
<div class="col-sm-10">
<div class=""><h3 style="text-align:center;">Announcement</h3></div>
</div>

<div class="col-sm-1">

</div>

<div class="col-sm-1">
  
</div>

</div>




<div class="row cspace">
<div class="col-sm-7">

<?php

require_once("connection.php");



if(isset($_POST['cr']))
{

   

   $cid=$_POST['ci'];
   $cname=$_POST['cn'];
   $announcement=$_POST['an'];


 $fowner="SELECT  owner from rapl_oj_contest where cname='$cname'";
 $sendit=mysqli_query($con,$fowner);
 $frow=mysqli_fetch_array($sendit);
 $owner=$frow['owner'];

 if($username==$owner)
 {
      $access=1;
 }
 else if($st=="Teacher" || $st=="Developer")
 {   
      $access=1;
 }


   if($access==1)
   {

     $query="INSERT INTO announcement(id,cname,des,aid) VALUES('$cid','$cname','$announcement','')";
     $send=mysqli_query($con,$query);

     if($send)
     {
       echo "Submitted Successfully. <a href=\"announcement.php\">Check Here</a>";
     }
   }
   else
   {
      header("Location:announcement.php?fail=1");
   }


   
   
  
 

}
else
{
   $query="SELECT * from announcement";
   $send=mysqli_query($con,$query);
  
   while($row=mysqli_fetch_array($send))
   {
       $aid=$row['aid'];
       echo "<button class=\"btn btn-success\">$aid</button><button class=\"btn btn-primary\">$row[cname]</button> <div class=\"alert alert-info\">$row[des]</div><br>";
   }

}


if(isset($_POST['up']))
{

  

   $aid=$_POST['ann'];
   $cont=$_POST['con'];
   

 $fowner="SELECT  owner from rapl_oj_contest where cname='$cont'";
 $sendit=mysqli_query($con,$fowner);
 $frow=mysqli_fetch_array($sendit);
 $owner=$frow['owner'];

 if($username==$owner)
 {
      $access=1;
 }
 else if($st=="Teacher" || $st=="Developer")
 {   
      $access=1;
 }


   if($access==1)
   {
     $query="DELETE FROM announcement WHERE aid='$aid'";
     $send=mysqli_query($con,$query);

     if($send)
     {
       echo "Deleted Successfully. <a href=\"announcement.php\">Check Here</a>";
     }
  }
 else
 {
    header("Location:announcement.php?fail=1");
 }
}



?>


</div>

<?php

if($st=="Teacher" || $st=="Developer" || $st=="Problem Setter")
{
?>

<div class="col-sm-5">
<div class="form-group">
<legend>Create Announcement</legend>
<form action="<?php echo $_SERVER['PHP_SELF']?>" name="f2" method="POST">
<label for="ta">Enter Your Contest ID</label>
<input type="text" name="ci" class="form-control" required><br><br>
<label for="ta">Enter Your Contest Name</label>
<input type="text" name="cn" class="form-control" required><br><br>
<label for="in">Enter Announcement Description</label>
<textarea name="an" class="form-control" rows="10" cols="60" required></textarea><br><br>
<input type="submit" name="cr" class="btn btn-success" value="Create Announcement">
<br><br>

</form>

<form action="<?php echo $_SERVER['PHP_SELF']?>" name="f3" method="POST">
  
<legend>Delete Announcement</legend>
<label for="ta">Enter Announcement Number</label>
<input type="text" name="ann" class="form-control"><br><br>
<label for="ta">Enter Contest Name</label>
<input type="text" name="con" class="form-control" required><br><br>
<input type="submit" name="up" class="btn btn-danger" value="Delete">




</form>


</div>

</div>

<?php
}

if(isset($_GET['fail']))
{
   echo "<script>alert(\"You Are Not Owner Of This Contest. Only Owner Can Control Announcement\");</script>";
}
?>


</div>
</div><br><br><br>


<div class="area">
<div class="well foot">
<div class="row area">
<div class="col-sm-3">
</div>

<div class="col-sm-5">


<div class="fm">

<b>Beta Version-2016</b><br>
<b>Developed By Ashadullah Shawon</b>

</div>
</div>


<div class="col-sm-4">
<?php

require_once("time.php");

?>
</div>
</div>
</div>
</div>

</body>
</html>
















