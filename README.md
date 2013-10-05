getting-stuff-done-laravel-code
===============================

This code is the companion for the book [Getting Stuff Done with Larvel](https://leanpub.com/gettingstuffdonelaravel).

Each branch in this repository contains source code as it is at the end of each chapter named in the branch.

This is from Chapter 42 - Refactoring Files and Config

Changes in this chapter:

* Removed gsd config from config/app.php and placed in config/todo.php
* Moved config/testing/app.php to config/testing/todo.php and updated
* Updated config in TodoRepository.php, ListAllCommand.php, CommandBase.php and TodoRepositoryTest.php
* Changed file routines to File facade in TodoRepository.php
