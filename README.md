# optinMonster_mautic_integration
This simple php file receives json form data from optinMonster and submit it to mautic 
------
instructions:
1) put optionMonster_mautic.php inside mautic directory where the path should be domain/mautic_folder/optinMonster.php or you can put it anywhere on your server and then change the mautic form path. 
2) create a form in mautic with the required form fields
3) make sure form array indexes in this php file matches mautic forms 
4) note: Mautic merges contacts based on ip address if email is not set to unique identifier, go to costome fields in mautic to change it.
5) login to your optinMonster and under integration add new, choose webhook under email provider then add your url that directs to this script. 

-------