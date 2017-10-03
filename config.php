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


/*
 * Command executed to WakeUp the remote computer
 *   %s is replaced by the MAC address
 */
define('WAKEUP_COMMAND', 'ether-wake %s');

/*
 * Automatically redirect to server page if the computer is turned on
 */
define('AUTO_REDIR', false);

/*
 * Use reCaptcha to perform PowerOn's and Shutdown's
 * Setup your reCaptcha Keys here: http://www.google.com/recaptcha/admin
 * See the documentation here: https://developers.google.com/recaptcha/intro
 *
 * The keys provided here are only for demonstration. If you want to setup this
 * application, you need to configure them.
 */
define('RECAPTCHA', true);
define('RECAPTCHA_SITE_KEY', '6LczADMUAAAAAE85bwTc4RHMsc8iccOHoUVoyTHG');
define('RECAPTCHA_SECRET_KEY', '6LczADMUAAAAALEnnBm5rDiQVacavVZP-MiVxDCg')

/*
 *  List of PCs to manage
 *    You can create a ist of computers to manage remotelly. You will need per computer:
 *    - an ID that will identify the compuer
 *    - a name that will be shown in the interface
 *    - a MAC address that will be used to send the magic packages to WakeUp the computer
 *    - a command when executed will shutdown the remote computer
 *    - an URL to open when the remote computer is turned on
 *    - the powerup waiting time that the remote computer will take to turn on
 *    Only ID and name are mandatory. The other parameters, you can choose not to specify its
 *    value or, if you need, set it to null. The interface will adapt according to the
 *    available parameters.
 * 
 *    NOTE for shutdown command: we recommend use a SSH connection to shutdown the remote
 *    computer. This requires some setup to make it work, but fortunatly you can find the
 *    information that you need here: http://www.rigon.tk/documentation/remote-pc-startupshutdown
 *
 *	  Format:
 *       id  =>  array(name, MAC address, shutdown command, URL to open, powerup waiting time)
 * 
 */
 
$list = array(
	"google"	=> array("Google", "00:00:00:00:00:00", "echo That would be interesting!", "http://www.google.com", 10),
	"pc2"		=> array("MAC&Open", "mac address", null, "http://www.rigon.tk"),
	"pc3"		=> array("Only PwrOn", "only mac address"),
	"pc4"       => array("Only Shtdwn", null, "echo Maybe later I will shutdown..."),
	"nothing"	=> array("Nothing")
);


/*
 * ID of the default computer
 */
$default = "google";

?>
