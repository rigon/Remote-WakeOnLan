# Remote-WakeOnLan
Web inferface to manage (power on and shutdown) remote computers

## Configuration
List of computers to manage remotelly. You will need:
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

## Note about shutdown command
We recommend use a SSH connection to shutdown the remote
computer. This requires some setup to make it work, but fortunatly you can find the
information that you need here: http://www.rigon.tk/documentation/remote-pc-startupshutdown
