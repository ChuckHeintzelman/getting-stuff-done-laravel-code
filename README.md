getting-stuff-done-laravel-code
===============================

This code is the companion for the book [Getting Stuff Done with Larvel](https://leanpub.com/gettingstuffdonelaravel).

Each branch in this repository contains source code as it is at the end of each chapter named in the branch.

This is from Chapter 38 - The ListAllCommand

Changes in this chapter:

* Created ListAllCommand
* Told artisan about the new command
* Fixed namespacing bug in TodoManager.php
* Fixed namespacing bug in TodoManagerTest.php
* Added tearDown() to TodoManagerTest and TodoListTest
* Refactored ListInterface::taskCount()