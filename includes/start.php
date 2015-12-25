<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../img/favicon.ico">

    <title>Сколько времени твои друзья проводят ВКонтакте</title>

    <!-- Bootstrap core CSS -->

    <!--     <link href="../../dist/css/bootstrap.min.css" rel="stylesheet">

Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
    <script src="js/charts_model.js"></script>
    <script src="https://f.efnez.ru/js/sorttable.js"></script>
    <!-- ... -->
  <script type="text/javascript" src="/bower_components/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
  <script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/dist/css/bootstrap-datepicker3.min.css" />  
 </head>
  <body>
	    <?php
	    include '/var/www/html/online_table.php';
	    //include '../online_table.php';
	    $myOnlineHistiry = new OnlineHistory();
	    ?>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>">VKonline</a>
        </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
	    <?php
	    $myOnlineHistiry->show_previous_dates($myOnlineHistiry->get_correct_date($_GET['d']));
	    ?>

<!-- 
<li>
	    <input data-date-format="dd.mm.yy" size="5"
             onkeydown="if (event.keyCode == 13) document.getElementById('date_link').click()"
             value=<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?> >
</li>
-->
      </ul>


    </div>
      </div>
    </div>
