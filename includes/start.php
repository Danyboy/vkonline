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



    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="//vk.com/js/api/openapi.js" type="text/javascript"></script>
    <script src="js/charts_controller.js"></script>
    <script src="//efnez.ru/js/sorttable.js"></script>
  <script type="text/javascript" src="/bower_components/jquery/jquery.min.js"></script>
  <script type="text/javascript" src="/bower_components/moment/min/moment.min.js"></script>
  <script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

    <!-- ... -->
    <!-- Bootstrap core CSS -->

  <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="/dist/css/bootstrap-datepicker3.min.css" />  

    <!-- Custom styles for this template -->

  <link rel="stylesheet" href="css/dashboard.css">
 </head>
  <body>
	    <?php
	    include '/var/www/html/online_table.php';
	    //include '../online_table.php';
	    $myOnlineHistiry = new OnlineHistory();
	    ?>

    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a style='' class="navbar-brand" href="/?u=<?php echo $myOnlineHistiry->get_current_id($_GET['u']); ?>">
	<!--    <img src='img/vkonline_50.png' title='ВКонлайн'> -->
	    ВКонлайн
	  </a>
        </div>
    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
	    <?php
	    $myOnlineHistiry->show_previous_dates($myOnlineHistiry->get_correct_date($_GET['d']));
	    ?>


<li>
	    <input data-date-format="dd.mm.yy" size="5" style="margin: 12px; border: 0px; padding: 3px;"
             onkeydown="if (event.keyCode == 13) document.getElementById('date_link').click()"
             value=<?php echo $myOnlineHistiry->get_correct_date($_GET['d']); ?> >
</li>
<!-- 

-->
      </ul>


    </div>
      </div>
    </div>
