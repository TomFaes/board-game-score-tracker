## About Board game score tracker
The idea is to create a score tracker for board games. You will be able to create an account with one of 3 socialite account: google, Facebook or Hotmail. I have chosen to keep the authentication outside of the program. Creating & logging in through socialite accounts is more secure. This way there wont be any passphrases in the database. From the socialite accounts we will get the first name, last name & e-mail account. 

All authenticated users will be able to create groups, the admin of the group will be able to add games, users & links. If a user is known in the database he can approve himself to see the group info. 

All members of a group will be able to add game scores and update them. The admin can always correct the scores. 
# Version 1: 27/04/2020
    - create/Login to an account
    - create/edit groups
    - create group games
    - create group game links
    - create group users
    - group statistics
    - played games
# Version 1.01: 30/04/2020
    - update packages to the latest version
    - create roadmap view
    - Choose favorite group
    - add cookie consent
# Version 2.00: 29/04/2021
    - update Route api to the tuple way
    - create global layout components for layout and input fields
    - implement vue router
    - implement vuex
    - cleanup unused methods in Controllers, repos, services
    - cleanup api routes
    - update to Larevel to version 8
    - update & add phpunit testing
    - bugfixes
# Version 2.01: 15/05/2021
    - laravel version: 8.40.0
    - update packages to the latest version
    - Start using php 8
    - replace email from group user with a code string. Needed to reduce the amount of user data not provided by the owner of the email
    - change related model to Model::class instead of App\Models\Model
    - remove links to 000webhost
    - bugfixes
        - if you are in a group and go back to the home page you get an 500 error
        - faker text option didn't work anymore
# Version 2.02: 01/07/2021
    - update packages to the latest version
    - add mailto at the group user to invite a user to a group. 
    - replace Validator with Request
    - add GameResource & GameCollection
# Version 2.03: 
    - Replace Validators with request
    - add Resource & Collection for all models
    - bugfixes
        - user validation changed to profile validation


## Future plans
Version 2.04: 
     - update packages to the latest version
     - rework front-end
        - rework split up API data return (group api call only returns groupdata with creator data, new call for group users, played games, groupgames)
        - update Resources to make better use of this return data. 

     
     
     
        


Undetermined futere plans
    - game statistics for player over the groups
    - Game detailed scores: if you play games like 7 wonders you will notices the score fiche will exist out of several  lines(points for blue cards, points for wonders, ?) . It should be a nice feature to have this present.
    - Turn order drawer: Our group usually draws who is first, second and so on. 
    - Game drawer:  sometimes we are undecided on which game to play, so a program that could do it for us would be welcome
