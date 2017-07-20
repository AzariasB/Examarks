# Examarks

UWE web project


Dependencies
------
This project uses :
 - symfony 3
 - angular.js
 - bootstrap


Installation
------------
To be able to install the project dependencies you'll need `bower` (for the front-end dependencies) and `composer` (for the back-end dependencies)

To install the project, you need to do the followings, from the project directory :

```bash
bower install
composer install
```

You then need to create a database called `examarks` and execute the given sql script `examarks.sql` onto this database.


Explanations
----
With this website, you can connect as a student or a teacher/root.
The student can view his marks and answer a survey once.

The teacher can create new students and modules. The new modules contains all the students
and the new students will attend to all modules. This can be edited.

Defaults logins are :
- root : `root`
- teacher : `teacher`
- student : `student`

The website is accessible (if not closed) at [examarks.tk](http://examarks.tk).
