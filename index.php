<?php

define('WAKEUP_COMMAND', 'sleep 2s #wakeonlan %s');

/*
 * List of MAC addresses
 * id => array(name, MAC address, shutdown command, open address, poweron time)
 */
$list = array(
	"server"	=> array("Servidor", "00:02:b3:46:8a:ea", "ssh poweroff@server.home", "http://server.sgrg.tk", 65),
	"piv"		=> array("PIV", "00:0B:6A:17:56:52", null, "ls"),
	"amd"		=> array("AMD", "00:0C:6E:5A:D0:F2"),
	"new"		=> array("New", null, "sleep 2s", null, 10),
	"nop"		=> array("Nop")
);

$default = "server";



$stage = 0;
$message = "";
	
// Different default selected
if(isset($_GET['default']) and array_key_exists($_GET['default'], $list))
	$default = $_GET['default'];



/* *******
 * Options
 */

// Wakeup selected computer
if(isset($_GET['wakeup'])) {
	if(isset($list[$default][1])) {
		$wakeup_command = sprintf(WAKEUP_COMMAND, $list[$default][1]);
		exec($wakeup_command, $output);
		foreach($output as $outputline)
			$message .= "$outputline\n";
		
		$stage = 1;
	}
	else
		$message = "MAC address not available.";
}

// Open selected computer
else if(isset($_GET['open'])) {
	if(isset($list[$default][3])) {
		$open_url = $list[$default][3];
		
		header("Location: $open_url");
		exit("Redirecting to $open_url");
	}
	else
		$message = "Redirect not available.";
}

// Shutdown selected computer
else if(isset($_GET['shutdown'])) {
	if(isset($list[$default][2])) {
		$message .= "Shutting down. Please wait...\n\n";
		$shutdown_command = $list[$default][2];
		
		exec($shutdown_command, $output);
		foreach($output as $outputline)
			$message .= "$outputline\n";
		
		$stage = 0;
	}
	else
		$message = "Open URL not available";
}


// The selected computer is turned off
else if(isset($_GET['turned-off'])) {
	$message = "The computer is turned off or is not reachable.";
}


/* if(preg_match("/^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$/", $_GET['macaddr']) == 1) */


// Selected computer
$selected = $list[$default];

if($message == "") $message = "&nbsp;";

$actionURL = "";
switch($stage) {
	case 0: $actionURL = "?wakeup&default=$default"; break;
	case 1: $actionURL = "#"; break;
	case 2: $actionURL = "?open&default=$default"; break;
	case 3: $actionURL = "?shutdown&default=$default"; break;
	default: $actionURL = "#";
}

?>
<!-- saved from url=(0014)about:internet -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">

		<title>Remote WakeOnLan</title>

		<!-- Bootstrap core CSS -->
		<link href="bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="cover.css" rel="stylesheet">
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="jquery.min.js"></script>
		<script src="bootstrap.min.js"></script>
		
		<style type="text/css">
			body {
				background: url("images/imagedoc-darknoise.png");
			}
			
			.cover-heading {
				font-size: 4em;
			}
			
			.power-button .lead {
				font-size: 2.5em;
			}
			
			.power-button {
				width: 300px;
				height: 300px;
				margin: 60px auto 60px auto;
			}
			
			.power-button a, .power-button a:hover, .power-button a:focus {
				height: 100%;
				width: 100%;
				display: inline-block;
				padding-top: 125px;
				
				background-color: transparent;
				background-image: url("images/buttons.png");
				background-repeat: no-repeat;
				text-decoration: none;
			}
			.power-button canvas {
				position: absolute;
				z-index: -1;
				width: 300px;
				height: 300px;
				margin-left: 0px;
			}
			
			.power-button .gray {
				background-position: 0px 0px;
			}
			.power-button .gray:hover {
				background-position: -300px 0px;
			}
			.power-button .red {
				background-position: 0px -300px;
			}
			.power-button .red:hover {
				background-position: -300px -300px;
			}
			.power-button .green {
				background-position: 0px -600px;
			}
			.power-button .green:hover {
				background-position: -300px -600px;
			}
		</style>
	</head>

	<body>
		<div class="site-wrapper">

			<div class="site-wrapper-inner">

				<div class="cover-container">

					<div class="masthead clearfix">
						<div class="inner">
							<h3 class="masthead-brand">Remote <strong class="text-smallcaps">WakeOnLan</strong></h3>
							<nav>
								<ul class="nav masthead-nav">
									<?php
									foreach($list as $id => $desc) {
										$active = $id === $default ? ' class="active"' : ''; ?>
										
										<li<?php echo $active; ?>>
											<div class="btn-group">
												<a href="?default=<?php echo $id; ?>" class="btn btn-default"><?php echo $desc[0]; ?></a>
												<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu">
													<li><a href="?default=<?php echo $id; ?>">Select</a></li>
													
													<?php if(isset($desc[1]) or isset($desc[2])) { ?><li role="separator" class="divider"></li><?php } ?>
													<?php if(isset($desc[1])) { ?><li><a href="?wakeup&default=<?php echo $id; ?>">Power ON</a></li><?php } ?>
													<?php if(isset($desc[2])) { ?><li><a href="?shutdown&default=<?php echo $id; ?>">Shutdown</a></li><?php } ?>
													
													<?php if(isset($desc[3])) { ?>
														<li role="separator" class="divider"></li>
														<li><a href="?open&default=<?php echo $id; ?>">Open</a></li>
														<li><a href="<?php echo $desc[3]; ?>" target="_blank">Open in a new tab</a></li>
													<?php } ?>
												</ul>
											</div>
										</li>
									<?php } ?>
								</ul>
							</nav>
						</div>
					</div>
					
					<div class="inner cover">
						<h1 class="cover-heading"><?php echo $selected[0]; ?></h1>
						
						<div class="power-button">
							<canvas></canvas>
							<a class="gray" href="<?php echo $actionURL; ?>">
								<p class="lead">Power On</p>
								<p class="counter"></p>
							</a>
						</div>
						
						<p class="message lead" title="Click to see the complete details"><?php echo $message; ?></p>
					</div>

					<div class="mastfoot">
						<div class="inner">
							<p>
								Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a> -
								<a href="#">Terms of Service</a> -
								<a href="#">Features</a> -
								<a href="#">About</a>
							</p>
						</div>
					</div>

				</div>

			</div>
		</div>

		
		<script type="text/javascript">
			var openLink = "<?php echo $list[$default][3]; ?>";
			var wakeupTime = <?php echo $list[$default][4]; ?> * 10;
			var stage = <?php echo $stage; ?>;
			var context;
			
			function runAnimation(time, callback) {
				var count = time;
				var lastTime = new Date();
				
				$(".power-button .counter").html(Math.round(time / 1000));
				var intID = setInterval(function() {
					var currentTime = new Date();
					count -= (currentTime.getTime() - lastTime.getTime());
					lastTime = currentTime;
				
					$(".power-button .counter").html(count < 0 ? 0 : Math.round(count / 1000));
					
					var progress = count < 1 ? 0 : count / wakeupTime;
					var red = progress > 0.25 ? 255 : Math.floor(255 * progress * 4);
					var green = progress > 0.66 ? 0 : Math.floor(255 * (1 - progress * 1.5));
					
					context.strokeStyle = "#" +
						(red < 16 ? "0" : "") + red.toString(16) +
						(green < 16 ? "0" : "") + green.toString(16) + "00";
					
					context.clearRect(0, 0, 300, 300);
					context.beginPath();
					context.arc(150, 150, 135, 2 * Math.PI * (1 - progress) - Math.PI/2, - Math.PI/2, true);
					context.stroke();
					
					if(count < 0) {
						clearInterval(intID);
						callback();
						return;
					}
					
				}, 75);
			}
			
			function setStage(value, lead, background = "gray", counter = "", drawArc = false) {
				$(".power-button .lead").html(lead);
				$(".power-button a").attr("class", background);
				$(".power-button a").removeAttr("target", "_blank");
				$(".power-button .counter").html(counter);
				
				context.clearRect(0, 0, 300, 300);
				if(drawArc) {
					context.strokeStyle = "#00ff00";
					context.beginPath();
					context.arc(150, 150, 135, 2 * Math.PI - Math.PI/2, - Math.PI/2, true);
					context.stroke();
				}
				stage = value;
			}
			
			function powerOn() {
				setStage(0, "Power On", "gray");
			}
			function turningOn(animate = true) {
				setStage(1, "Turning On", "red", "Loading...")
				if(animate) runAnimation(wakeupTime, open);
			}
			function open() {
				setStage(2, "Open", "green", "", true);
				$(".power-button a").attr("href", openLink);
				$(".power-button a").attr("target", "_blank");
			}
			function shutdown() {
				setStage(3, "Shutdown", "red", "", true);
				$(".power-button a").attr("href", "?shutdown");
			}
			
			window.onload = function() {
				var canvas = $(".power-button canvas").get(0);
				canvas.setAttribute('width', 300);
				canvas.setAttribute('height', 300);
				context = canvas.getContext("2d");
				context.lineWidth = 30;
				context.scale(1, 1);
				
				switch(stage) {
					default:
					case 0: powerOn(); break;
					case 1: turningOn(); break;
					case 2: open(); break;
					case 3: shutdown(); break;
				}
				
				$(".power-button a").click(function() {
					$(".message").html("&nbsp;");
					switch(stage) {
						case 0: turningOn(false); break;
						case 2:
							window.open($(".power-button a").attr("href"));
							shutdown();
							return false;
						case 3:
							$(".power-button .counter").html("Loading...");
							break;
					}
				});
				
				$(".message").click(function() {
					alert($(this).html());
				});
			}
		</script>
	</body>
</html>

