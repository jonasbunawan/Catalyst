# Catalyst
Programming Evaluation for Developer Skills Assessment Test

Systems Requirements:
This solution was made and tested on Windows based WAMP server.

PHP Version:
PHP 5.5.12 (cli) (built: Apr 30 2014 11:20:58)
Copyright (c) 1997-2014 The PHP Group
Zend Engine v2.5.0, Copyright (c) 1998-2014 Zend Technologies
    with Xdebug v2.2.5, Copyright (c) 2002-2014, by Derick Rethans
    
MySQL Version:
Server version: 5.6.17 MySQL Community Server (GPL)

Task 1
Assumptions:
Provided users.csv(with header line) would be used for application execution. Simple modification can be easily made to adjust the application to be used for file without header(stated as comment within the script).
No separate scripts would be included within the user_upload.php script, that is why all of the functions are pre-defined in the single script.
As application is script command line based program which execute the script by line, more lines means slower application running time, not all of the functions are pre-defined within separate distinguished functions blocks, but conditional statements take in place.

Technical Assumptions:
FILTER_VALIDATE_EMAIL will be sufficient enough to validate common valid e-mail addresses, although some valid e-mail addresses are considered to be invalid by the built-in function.

Requirements:
Built-in command line directives are case sensitive(as stated on help description).

Task 2
Assumptions:
As it is a mini program, a function is pre-defined by user to calculate remainder which will be used to check the condition of a specific number whether it is a triple, fiver, or triplefiver as required.
