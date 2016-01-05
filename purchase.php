<?php

include 'inc/header.php';

$result = mysqli_query($con, "SELECT * FROM `subscriptions` WHERE `username` = '$username' AND `active` = '1' AND `expires` >= '$date'") or die(mysqli_error($con));
if (mysqli_num_rows($result) < 1 && $_SESSION['rank'] != "5") {
	$subscription = "0";
}else{
	$subscription = "1";
}

if(isset($_POST['purchase'])){
	$id = mysqli_real_escape_string($con, $_POST['purchase']);
	$result = mysqli_query($con, "SELECT * FROM `packages` WHERE `id` = '$id'") or die(mysqli_error($con));

	while ($row = mysqli_fetch_array($result)) {
		$packageprice = $row['price'];
		$packagename = $website." - ".$row['name'];
		$custom = $row['id']."|".$username;
	}
	
	$paypalurl = "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amount=".urlencode($packageprice)."&business=".urlencode($paypal)."&page_style=primary&item_name=".urlencode($packagename)."&return=http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/purchase.php?action=buy-success&rm=2&notify_url=http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/lib/ipn.php"."&cancel_return=http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])."/purchase.php?action=buy-error&custom=".urlencode($custom)."&mc_currency=USD";
	header('Location: '.$paypalurl);
}

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="24/7">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="<?php echo $favicon;?>">

    <title><?php echo $website;?> - Purchase</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-reset.css" rel="stylesheet">
	<!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link href="css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" >
      <!--header start-->
      <header class="header white-bg">
            <div class="sidebar-toggle-box">
                <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
            </div>
            <!--logo start-->
            <a href="index.php" class="logo"><?php echo $website;?></a>
            <!--logo end-->
            <div class="nav notify-row" id="top_menu">
                <!--  notification start -->
                <ul class="nav top-menu">
                    <!-- inbox dropdown start-->
					<?php
						$result = mysqli_query($con, "SELECT * FROM `support` WHERE `to` = '$username' AND `read` = '0' ORDER BY `id`");
						$messages = mysqli_num_rows($result);
							if($messages > 0){
								echo '
										<li id="header_inbox_bar" class="dropdown">
											<a data-toggle="dropdown" class="dropdown-toggle" href="#">
												<i class="icon-envelope-alt"></i>
												<span class="badge bg-important">'.$messages.'</span>
											</a>
											<ul class="dropdown-menu extended inbox">
												<div class="notify-arrow notify-arrow-red"></div>
												<li>
													<p class="red">You have '.$messages.' new messages</p>
												</li>
								';
								while ($row = mysqli_fetch_assoc($result)) {
									echo '
												<li>
													<a href="support.php">
														<span class="subject">
														<span class="from">'.$row['subject'].'</span>
														<span class="time">'.$row['date'].'</span>
														</span>
														<span class="message">
															'.$row['message'].'
														</span>
													</a>
												</li>
											';
								}
								echo '
												<li>
													<a href="support.php">See all messages</a>
												</li>
											</ul>
										</li>
								';
							}else{
							echo '
								<li id="header_inbox_bar" class="dropdown">
									<a data-toggle="dropdown" class="dropdown-toggle" href="#">
										<i class="icon-envelope-alt"></i>
										<span class="badge bg-important">0</span>
									</a>
									<ul class="dropdown-menu extended inbox">
										<div class="notify-arrow notify-arrow-red"></div>
										<li>
											<p class="red">You have '.$messages.' new messages</p>
										</li>
										<li>
											<a href="support.php">See all messages</a>
										</li>
									</ul>
								</li>
							';
							}
					?>			
                    <!-- inbox dropdown end -->
            </div>
            <div class="top-nav ">
                <!--user info start-->
                <ul class="nav pull-right top-menu">
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<img alt="" src="img/avatar_small.png">
                            <span class="username"><?php echo $username;?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="#"><i class=" icon-suitcase"></i>Profile</a></li>
                            <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                            <li><a href="#"><i class="icon-envelope-alt"></i> Messages</a></li>
                            <li><a href="lib/logout.php"><i class="icon-key"></i> Log Out</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!--user info end-->
            </div>
        </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu" id="nav-accordion">
                  <li>
                      <a href="index.php">
                          <i class="icon-dashboard"></i>
                          <span>Dashboard</span>
                      </a>
                  </li>
                  <li>
                      <a class="active" href="purchase.php">
                          <i class="icon-shopping-cart"></i>
                          <span>Purchase</span>
                      </a>
                  </li>
                  <li>
                      <a href="generator.php">
                          <i class="icon-refresh"></i>
                          <span>Generator</span>
                      </a>
                  </li>
                  <li>
                      <a href="support.php">
                          <i class="icon-envelope"></i>
                          <span>Support</span>
                      </a>
                  </li>
				  <?php
					if (($_SESSION['rank']) == "5") {
                        echo '
						  <legend style="margin-bottom: 5px;"></legend>
						  <li class="sub-menu">
							  <a href="javascript:;" >
								  <i class="icon-laptop"></i>
								  <span>Administration</span>
							  </a>
							  <ul class="sub">
								  <li><a  href="admin-manage.php">Manage</a></li>
								  <li><a  href="admin-support.php">Support</a></li>
								  <li><a  href="admin-statistics.php">Statistics</a></li>
								  <li><a  href="admin-flagged.php">Flagged</a></li>
								  <li><a  href="admin-news.php">News</a></li>
								  <li><a  href="admin-subscriptions.php">Subscriptions</a></li>
								  <li><a  href="admin-users.php">Users</a></li>
							  </ul>
						  </li>
						';
					}
				  ?>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
		  
              <div class="row product-list">
				<?php
					$result = mysqli_query($con, "SELECT * FROM `packages` ORDER BY CAST(price AS DECIMAL(10,2))");
					while ($row = mysqli_fetch_assoc($result)) {
						if($row['generator'] == ""){
							$generatorname = "All";
						}else{
							$generatorquery = mysqli_query($con, "SELECT * FROM `generators` WHERE `id` = '$row[generator]'") or die(mysqli_error($con));
							while($row1 = mysqli_fetch_array($generatorquery)){
								$generatorname = $row1['name'];
							}
						}
						if($row['accounts'] == "0" || $row['accounts'] == ""){
							$accounts = "Unlimited";
						}else{
							$accounts = $row['accounts']."/day";
						}
						echo '
                          <div class="col-md-4">
                              <section class="panel">
                                  <div class="panel-body text-center">
                                      <a href="#" class="pro-title">
                                          <H3>'.$row['name'].'</H3>
                                      </a>
                                      <p class="price">$'.$row['price'].'</p>
									  <legend></legend>
									  <label>Generator(s):</label> '.$generatorname.'</br>
									  <label>Length:</label> '.$row[length].'</br>
									  <label>Accounts:</label> '.$accounts.'</br></br>
									  <form method="POST" action="purchase.php">
										<input type="hidden" name="purchase" value="'.$row[id].'"/>
										<button type="submit" class="btn btn-info btn-lg btn-block"
						';
						if ($subscription != "0" || $_SESSION['rank'] == "5"){
							echo "disabled";
						}
						echo '
										><i class="icon-shopping-cart"></i> Buy Now</button>
									  </form>
								  </div>
                              </section>
                          </div>
						';
					}	 
				?>
              </div>

          </section>
		  
		  <?php 
		  
		  if($_GET['action'] == "buy-success"){
			  $result = mysqli_query($con, "SELECT * FROM `subscriptions` WHERE `username` = '$username' AND `date` = '$date'") or die(mysqli_error($con));
			  if (mysqli_num_rows($result) < 1) {
				  echo '
					  <div class="modal fade" id="buy-success" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top: 15%; overflow-y: visible; display: none;">
						<div class="modal-dialog modal-m">
							<div class="modal-content">
								<div class="modal-header">
									<center><h3 style="margin:0;">Waiting for purchase to complete..</h3></center>
								</div>
								<div class="modal-body">
									<script language="JavaScript" type="text/javascript">  
										var count = 10;
										function countDown(){
										 if (count <=0){  
										  document.getElementById("timer").innerHTML = "<b>Refreshing...</b>";
										 }else{  
										  count--;  
										  document.getElementById("timer").innerHTML = "<center>Refreshing in "+ count + " seconds</center>";
												  setTimeout("countDown()", 1000)
										 }  
										}
									</script>
									<span id="timer"><script>countDown();</script></span></br>
									<script type="text/javascript">
										window.setTimeout(function(){window.location.href="purchase.php?action=buy-success"},10000);
									</script>
									<div id="progress-bar" class="progress progress-striped active" style="margin-bottom:0;">
										<div class="progress-bar" style="width: 100%">
										</div>
									</div>
								</div>
							</div>
						</div>
					  </div>
				  ';
			  }else{
				echo '
					  <div class="modal fade" id="buy-success" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top: 15%; overflow-y: visible; display: none;">
						<div class="modal-dialog modal-m">
							<div class="modal-content">
								<div class="modal-header">
									<center><h3 style="margin:0;">Purchase Completed!</h3></center>
								</div>
								<div class="modal-body">
									<div id="progress-bar" class="progress progress-striped" style="margin-bottom:0;">
										<div class="progress-bar progress-bar-success" style="width: 100%">
										</div>
									</div>
								</div>
								<center>
									<p>Thanks for your purchase! You have succesfully received your subscription package.</p>
									<p>Visit the <a href="generator.php">Generator Page</a> to start generating.</p></br>
								</center>
							</div>
						</div>
					  </div>
				';
			  }
		  }
		  ?>
		  
      </section>
      <!--main content end-->
      <!--footer start-->
      <footer class="site-footer">
          <div class="text-center">
              <?php echo $footer;?>
              <a href="#" class="go-top">
                  <i class="icon-angle-up"></i>
              </a>
          </div>
      </footer>
      <!--footer end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.scrollTo.min.js"></script>
    <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="js/jquery.customSelect.min.js" ></script>
    <script src="js/respond.min.js" ></script>
	
    <script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>

    <!--common script for all pages-->
    <script src="js/common-scripts.js"></script>
	
	<?php
	if($_GET['action'] == "buy-success"){
		echo "<script type='text/javascript'>
				$(document).ready(function(){
				$('#buy-success').modal('show');
				});
			  </script>"
		;
	}
	?>

  </body>
</html>
