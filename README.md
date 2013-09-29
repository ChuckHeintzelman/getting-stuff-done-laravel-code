getting-stuff-done-laravel-code
===============================

This code is the companion for the book [Getting Stuff Done with Larvel](https://leanpub.com/gettingstuffdonelaravel).

Each branch in this repository contains source code as it is at the end of each chapter named in the branch.

This is from Chapter 40 - The UncreateCommand

Changes in this chapter:

* Created UncreateCommand
* Deleted constructors from CreateCommand and ListAllCommand
* Implemented askForListId for existing
* Clean up pick_from_list
* Fix unit tests for pick_from_list changes
