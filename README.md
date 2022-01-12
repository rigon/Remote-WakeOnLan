# Remote WakeOnLan

> Web interface to manage (power on and shutdown) remote computers

[GitHub Project](https://github.com/rigon/Remote-WakeOnLan) - [Download](https://github.com/rigon/Remote-WakeOnLan/archive/master.zip) - [Demo Version](https://www.rigon.tk/files/remote-wakeonlan/Remote-WakeOnLan/index.php)

## Usage

The complete cycle is:

 1. Power ON
 2. Turning on (will take **waiting time** seconds)
 3. Open (will open the specified URL in a new tab)
 4. Shutdown

Then, the cycle starts over again. If some of the steps is not available, it will be skipped.

## Configuration
You can create a list of computers to manage remotely. You will need per computer:

 - an ID that will identify the computer
 - a name that will be shown in the interface
 - a MAC address that will be used to send the magic packages to WakeUp the computer
 - a command when executed will shutdown the remote computer
 - an URL to open when the remote computer is turned on
 - the powerup waiting time that the remote computer will take to turn on

Only ID and name are mandatory. The other parameters, you can choose not to specify its value or, if you need, set it to null. The interface will adapt according to the available parameters.

Format:

    id  =>  array(name, MAC address, shutdown command, URL to open, powerup waiting time)

### Note about shutdown command
We recommend use a SSH connection to shutdown the remote computer. This requires some setup to make it work, but fortunately you can find the information that you need in [Remote PC Startup/Shutdown](https://docs.rigon.tk/remote-pc-startupshutdown).


## Screenshots

![screenshot1](https://www.rigon.tk/files/remote-wakeonlan/screenshot1.jpg)

![screenshot2](https://www.rigon.tk/files/remote-wakeonlan/screenshot2.jpg)

![screenshot3](https://www.rigon.tk/files/remote-wakeonlan/screenshot3.jpg)

![screenshot4](https://www.rigon.tk/files/remote-wakeonlan/screenshot4.jpg)
