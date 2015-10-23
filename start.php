<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>How much your friend online in vk</title>

    <!-- Bootstrap core CSS -->
    <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">VKonline</a>
        </div>
       <nav id="bs-navbar" class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
      
      
      
        <li>
	    <?php
	    include '/home/danil/Projects/vkonline/online_table.php';

	    $myOnlineHistiry = new OnlineHistory();
	    $myOnlineHistiry->show_previous_dates($_GET['d']);
	    ?>

          <!--<a href="http://vk.pr.etersoft.ru/all/13.10.15.html">13.10.15</a>-->
        </li>
      </ul>
    </nav>

      </div>
    </div>
    <div class="container-fluid">
      <div class="row">
          <h2 class="sub-header">
            <?php  
	    date_default_timezone_set('Europe/Moscow');
	    $my_date = isset($_GET['d']) ? $_GET['d'] : date('d-m-y');
	    echo $my_date;
	     ?>
          
          </h2>
