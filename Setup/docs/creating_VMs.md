# Creating Ubuntu VM's on Mac M1 using UTM

Here is the process of creating an Ubuntu 22.04 VM on MacOS M1 chip.

Normally you would use something like Oracle VirtualBox to create your VM's, good luck trying to get that working efficiently on a Mac with M1 chip. Even though VirtualBox v7.0 has a beta version thats suppose to work on a `ARM` based platform, I have had no luck getting a working VM. SO the other option, and in my opinion a way better option, is to use [UTM](https://mac.getutm.app/). It has such a user friendly interface and provides a seemless experience interacting with the VM's.

I have experienced great VM performance and its also nice not having to go through extra steps just to get full-screen capabilties into the VM's.

Here is the process of creating a VM using UTM.

# How To
## iSO image
Get a ARM based Ubuntu iso image for [Ubuntu Jellyfish Server v22.04](https://cdimage.ubuntu.com/releases/22.04/release/).

<image src="./images/1_iso_jellyfish.png" height="60%" width="40%">

## Creating VM
1. Click the + button to create a new VM
2. Choose the `Virtualize` option

<image src="./images/2_virtualize.png" height="40%" width="40%">

3. Choose the OS: `Linux`

<image src="./images/3_vm_os.png" height="60%" width="30%">

4. Go to the section for "Boot iso image" and choose "Browse"
    - choose the [iso image](#iso-image) you downloaded.

<image src="./images/4_browse_iso.png" height="40%" width="40%">

5. Configure the RAM and Core's 

<image src="./images/5_hardware_config.png" height="40%" width="40%">

6. Configure the Storage

<image src="./images/6_storage.png" height="40%" width="40%">

7. Choose a name for the VM
    - Note: this is not the hostname, it is just the VM display name that will appear in UTM portal. 

<image src="./images/7_vm_name.png" height="60%" width="30%">

8. configure the VM `Network` settings to change the network mode to `Bridged.`

<image src="./images/8_edit_vm_settings.png" height="60%" width="40%">
<image src="./images/9_change_network_mode.png" height="50%" width="60%">

## Installing OS on VM
1. Double click the VM to start it
2. Choose the "Try or Install" option

<image src="./images/10_start_vm.png" height="40%" width="40%">

3. Once the VM boots up, click the installer app to begin installation process. 

<image src="./images/11_install_os.png" height="30%" width="30%">

4. Most options can be left at default so click next on most BUT...
5. Choose `minimal install` and `download third party software`

<image src="./images/12_minimal_install.png" height="40%" width="40%">

6. When you choose your username and password, be sure to update your VM's hostname

<image src="./images/13_computer_name.png" height="40%" width="40%">

7. Once the installation completes, Click restart now option

<image src="./images/14_installation_complete.png" height="30%" width="50%">

8. You'll be left on a blank screen rather than an actual restart, so we must force kill the VM

<image src="./images/15_blank_screen.png" height="30%" width="50%">
<image src="./images/16_force_shut.png" height="40%" width="30%">

9. Clear the iso image from the CD/DVD drive

<image src="./images/17_clear_drive.png" height="50%" width="50%">

10. Now your all done ! double click the VM to start it up.

