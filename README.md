# Remote-WakeOnLan

> Web inferface to manage (power on and shutdown) remote computers.

[Homepage](http://www.rigon.tk/main-page/remote-wakeonlan) - [Download](https://github.com/rigon/Remote-WakeOnLan/archive/master.zip) - [Demo Version](http://www.rigon.tk/remote-wakeonlan)

## Usage

The complete cycle is:
 - Power ON
 - Turning on (will take **waiting time** seconds)
 - Open (will open the specified URL in a new tab)
 - Shutdown

Then, the cycle starts over again. If some of the steps is not available, it will be skipped.

## Configuration
You can create a list of computers to manage remotelly. You will need per computer:
 - an ID that will identify the compuer
 - a name that will be shown in the interface
 - a MAC address that will be used to send the magic packages to WakeUp the computer
 - a command when executed will shutdown the remote computer
 - an URL to open when the remote computer is turned on
 - the powerup waiting time that the remote computer will take to turn on

Only ID and name are mandatory. The other parameters, you can choose not to specify its
value or, if you need, set it to null. The interface will adapt according to the
available parameters.

Format:

    id  =>  array(name, MAC address, shutdown command, URL to open, powerup waiting time)

### Note about shutdown command
We recommend use a SSH connection to shutdown the remote
computer. This requires some setup to make it work, but fortunatly you can find the
information that you need in [Remote PC Startup/Shutdown](http://www.rigon.tk/documentation/remote-pc-startupshutdown).


## Screenshots

![screenshot1](http://www.rigon.tk/main-page/files/remote-wakeonlan/screenshot1.jpg)

![screenshot2](http://www.rigon.tk/main-page/files/remote-wakeonlan/screenshot2.jpg)

![screenshot3](http://www.rigon.tk/main-page/files/remote-wakeonlan/screenshot3.jpg)

![screenshot4](http://www.rigon.tk/main-page/files/remote-wakeonlan/screenshot4.jpg)
