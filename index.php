<?php

/*
 *  Remote WakeOnLan - a web inferface to manage (power on and shutdown) remote computers
 *  Copyright (C) 2016  rigon <http://www.rigon.tk>
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

// Load configutations, please make you configurations in config.php
require("config.php");

// Init vars
$stage = 0;
$message = "";

// Different default selected
if(isset($_GET['default']) and array_key_exists($_GET['default'], $list))
	$default = $_GET['default'];

// Selected computer
$selected = $list[$default];

/* *******
 * Options
 */

// Wakeup selected computer
if(isset($_GET['wakeup'])) {
	if(isset($selected[1])) {
		$wakeup_command = sprintf(WAKEUP_COMMAND, $selected[1]);
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
	if(isset($selected[3]) and $selected[3] != null) {
		$open_url = $selected[3];
		
		header("Location: $open_url");
		exit("Redirecting to $open_url");
	}
	else
		$message = "Open URL not available.";
}

// Shutdown selected computer
else if(isset($_GET['shutdown'])) {
	if(isset($selected[2])) {
		$message .= "Shutting down ".$selected[0].". Please wait...\n\n";
		$shutdown_command = $selected[2];
		
		exec($shutdown_command, $output);
		foreach($output as $outputline)
			$message .= "$outputline\n";
		
		$stage = 0;
	}
	else
		$message = "Shutdown command not available";
}


// The selected computer is turned off
else if(isset($_GET['turned-off'])) {
	$message = "The computer is turned off or is not reachable.";
	$stage = 0;
}

// The selected computer is turned on
else if(isset($_GET['turned-on'])) {
	$message = "The computer is turned on!";
	$stage = 2;
}

// No state selected, check if selected computer is responding
else {
	if(isset($selected[3]) and $selected[4] != null) {	// If it has an URL to open
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $selected[4]);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		$response = curl_exec($ch);
		curl_close($ch);

		// The remote computer is turned on
		if($response !== false) {
			$stage = 2;
			$message = "The computer is turned on!";

			// Redirect to server page if the computer is turned on
			if(AUTO_REDIR) {
				$open_url = $selected[3];
				header("Location: $open_url");
				exit("Redirecting to $open_url");
			}
		}
	}
}

/* if(preg_match("/^([0-9A-Fa-f]{2}:){5}([0-9A-Fa-f]{2})$/", $_GET['macaddr']) == 1) */


if($message == "") $message = "&nbsp;";


$actionsURL = array(
	"?wakeup&default=$default",
	"#",
	"?open&default=$default",
	"?shutdown&default=$default");

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
		<link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="cover.css" rel="stylesheet">
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="bower_components/jquery/dist/jquery.min.js"></script>
		<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="bower_components/bootpopup/bootpopup.js"></script>
		
		<style type="text/css">
			body {
				background: url("images/imagedoc-darknoise.png");
			}
			
			.cover-heading {
				font-size: 4em;
				padding-top: 30px;
			}
			
			.power-button .lead {
				font-size: 2.5em;
			}
			
			.power-button {
				width: 300px;
				height: 300px;
				margin: 50px auto 60px auto;
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
						<h1 class="cover-heading" id="cover-heading"><?php echo $selected[0]; ?></h1>
						
						<div class="power-button">
							<canvas></canvas>
							<a class="gray" href="<?php echo $actionsURL[$stage]; ?>">
								<p class="lead">Power On</p>
								<p class="counter"></p>
							</a>
						</div>
						
						<p class="message lead" title="Click to see the complete details"><?php echo $message; ?></p>
					</div>

					<div class="mastfoot">
						<div class="inner">
							<p>
								<h4><a href="#" data-toggle="modal" data-target="#about">About</a></h4>
							</p>
							<p>
								<b>Created by <a href="http://www.rigon.tk">rigon</a></b> - 
								Template by <a href="https://twitter.com/mdo">@mdo</a>, <a href="http://getbootstrap.com">Bootstrap</a>
							</p>
						</div>
					</div>

				</div>

			</div>
		</div>
		
		<!-- About -->
		<div class="modal fade" id="about" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="modal-title" id="myModalLabel" class="masthead-brand">Remote <strong class="text-smallcaps">WakeOnLan</strong></h3>
					</div>
					<div class="modal-body">
						<h4>A web inferface to manage (power on and shutdown) remote computers.</h4>
						<p>
							<b>Created by <a href="http://www.rigon.tk">rigon</a></b> - 
							Template by <a href="https://twitter.com/mdo">@mdo</a>, <a href="http://getbootstrap.com">Bootstrap</a>
						</p>
						<hr>
						
						<p>The complete cycle is:</p>
						<ol>
							<li>Power ON</li>
							<li>Turning on (will take <b>waiting time</b> seconds)</li>
							<li>Open (will open the specified URL in a new tab)</li>
							<li>Shutdown</li>
						</ol>
						<p>Then, the cycle starts over again. If some of the steps is not available, it will be skipped.</p>
						
						<h4>Configuration</h4>
						<p>You can create a list of computers to manage remotelly. You will need per computer:</p>
						<ul>
							<li>an ID that will identify the compuer</li>
							<li>a name that will be shown in the interface</li>
							<li>a MAC address that will be used to send the magic packages to WakeUp the computer</li>
							<li>a command when executed will shutdown the remote computer</li>
							<li>an URL to open when the remote computer is turned on</li>
							<li>the powerup waiting time that the remote computer will take to turn on</li>
						</ul>
						<p>
							Only ID and name are mandatory. The other parameters, you can choose not to specify its
							value or, if you need, set it to <code>null</code>. The interface will adapt according to the
							available parameters.
						</p>
						<p>
							<b>NOTE for shutdown command:</b> we recommend use a SSH connection to shutdown the remote
							computer. This requires some setup to make it work, but fortunatly you can find the
							information that you need in
							<a href="http://www.rigon.tk/documentation/remote-pc-startupshutdown" target="_blank">Remote PC Startup/Shutdown</a>.
						</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>

		
		<script type="text/javascript">
			var hasPowerOn = <?php echo (($selected[1]) ? "true" : "false"); ?>;
			var hasShutdown = <?php echo (isset($selected[2]) ? "true" : "false"); ?>;
			var hasOpen = <?php echo (isset($selected[3]) ? "true" : "false"); ?>;
			var hasTurningOn = <?php echo (isset($selected[4]) ? "true" : "false"); ?>;
			var hasNoAction = false;
			
			var actionsURL = ["<?php echo implode('", "', $actionsURL); ?>"];
			var wakeupTime = <?php echo (isset($selected[4]) ? $selected[4] : 0); ?> * 1000;
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
			
			function setStage(value, lead, background, counter, drawArc, updateHref) {
				// Default values for parameters
				if(background === undefined) background = "gray"; if(counter === undefined) counter = "";
				if(drawArc === undefined) drawArc = false; if(updateHref === undefined) updateHref = true;
				
				
				$(".power-button .lead").html(lead);
				$(".power-button a").attr("class", background);
				if(updateHref) $(".power-button a").attr("href", actionsURL[value]);
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
				if(hasNoAction) return; hasNoAction = true;	// Avoid recursion
				if(!hasPowerOn) { turningOn(); return; }
				setStage(0, "Power On", "gray");
			}
			function turningOn(animate) {
				// Default values for parameters
				if(animate === undefined) animate = true;
			
				if(!hasTurningOn || !hasPowerOn) { openLink(); return; }
				setStage(1, "Turning On", "red", "Loading...", false, false);
				if(animate) runAnimation(wakeupTime, openLink);
			}
			function openLink() {
				if(!hasOpen) { shutdown(); return; }
				setStage(2, "Open", "green", "", true);
				$(".power-button a").attr("target", "_blank");
			}
			function shutdown() {
				if(!hasShutdown) { powerOn(); return; }
				setStage(3, "Shutdown", "red", "", true);
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
					case 2: openLink(); break;
					case 3: shutdown(); break;
				}
				
				$(".power-button a").click(function() {
					$(".message").html("&nbsp;");
					switch(stage) {
						case 0:
							if(hasTurningOn)
								turningOn(false);
							break;
						case 2:
							window.open($(".power-button a").attr("href"), "_blank");
							shutdown();
							return false;
						case 3:
							$(".power-button .counter").html("Loading...");
							break;
					}
				});
				
				$(".message").click(function() {
					var message = $(this).html();
					if(message == "&nbsp;") message = "Nothing to show!";
					bootpopup.alert(message);
				});
				
				window.location.hash = "cover-heading";
			}
		</script>
	</body>
</html>

