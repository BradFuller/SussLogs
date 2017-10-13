<?php

include 'config.php';
if (!isset($_GET['view'])) {
   
$ftp_connection = ftp_connect($host,$port);
      
ftp_login($ftp_connection, $user, $password);
if ($passiveftp == 1) {
   ftp_pasv($ftp_connection, true);
}
$logpath = $path;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Log Viewer</title>

    <!-- Material Design Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.min.css" integrity="sha256-X/mlyZAafJ8j5e74pWh4+qNUD1zurCvLA6hODVobQX0=" crossorigin="anonymous" />

    <!-- Custom styles for this template -->
    <link href="css/sticky.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Log Viewer</a>
          </div>
        </div>
      </div>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>Log Viewer</h1>
          <div class="alert alert-info"><h4>Status</h4>
<?php
echo "<b>Folder:</b> ".$path."<br>";
if (ftp_chdir($ftp_connection, "".$path."")) {
    echo "Successfully accessed log folder.";
} else { 
    echo "Couldn't access log folder";
}
$contents = ftp_nlist($ftp_connection, ".");
?>
          </div>
        </div>
        <p>
<?php
$arrlength=count($contents);
echo '<table class="table table-condensed">
      <thead>
        <tr>
          <th>File</th>
          <th>Last Modified</th>
        </tr>
      </thead>
      <tbody>';
for($x=0;$x<$arrlength;$x++)
  {
   $newname = str_replace("./","",$contents[$x]);
   $lastchanged = ftp_mdtm($ftp_connection, $contents[$x]);




   echo '
        <tr>
          <td><a href="?view='.$newname.'">'.$newname.'</a></td>
          <td>'.date('F d Y H:i:s.',$lastchanged).'</td>
        </tr>       
   ';

  }
  echo '      </tbody>
    </table>';
ftp_close($ftp_connection);
}


if (isset($_GET['view'])) {
   if (strstr($_GET['view'],"../")) {
      die("<h1>Forbidden URL: ".$_GET['view'].". <a href='index.php'>Go Back</a></h1>");
   }
      if (preg_match('%\b(.txt|.log)\b%i', $_GET['view']) == 0) {
      die("<h1>Can't view that.<a href='index.php'>Go Back</a></h1>");
   }
$file = file_get_contents('ftp://'.$user.':'.$password.'@'.$host.'/'.$path.'/'.$_GET['view'].'');

echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Log Viewer</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sticky.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don\'t actually copy this line! -->
    <!--[if lt IE 9]><script src="../../docs-assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <!-- Wrap all page content here -->
    <div id="wrap">

      <!-- Fixed navbar -->
      <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">'.$community.' Log Viewer</a>
          </div>
        </div>
      </div>

      <!-- Begin page content -->
      <div class="container">
        <div class="page-header">
          <h1>'.$_GET['view'].'</h1>
          <div class="alert alert-info"><h4>Here is your file.</h3>

Filename: <b>'.$_GET['view'].'</b>
<form>
<input type="button" class="btn btn-success" value="Select All" onClick="javascript:this.form.ta.focus();this.form.ta.select();"><br />
<br>

<textarea class="form-control" id="ta" rows="20" cols="200" readonly>
'.$file.'
</textarea>
</form>
';

}

?>
        </p>
      </div>
    </div>

    <div id="footer">
      <div class="container">
        <p class="text-muted"> Version 0.0.2 | No folder support | Created by <a href='http://trurascalz.tk'>Trurascalz</a> Topic: <a href='http://forums.ulyssesmod.net/index.php/topic,7155.0.html'>ULX log viewer</a></p>
      </div>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.slim.min.js" integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.5/esm/popper.min.js" integrity="sha256-KLtP3dHangu39xHpe/GulhmvfvAy+Iuh9LneCuSyRcs=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.0.2/bootstrap-material-design.iife.js" integrity="sha256-nX1Z3jVnd0z8/vLcmMZVs9B6kbuVY153wWHueF1o2Tg=" crossorigin="anonymous"></script>
    <script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script>
  </body>
</html>
