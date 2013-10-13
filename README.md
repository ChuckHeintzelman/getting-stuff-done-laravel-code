getting-stuff-done-laravel-code
===============================

This code is the companion for the book [Getting Stuff Done with Larvel](https://leanpub.com/gettingstuffdonelaravel).

Each branch in this repository contains source code as it is at the end of each chapter named in the branch.

This is from Chapter 50 - Refactoring again

Changes in this chapter:

* Added abort() to CommandBase
* Updated AddTaskCommand, ArchiveListCommand, CreateCommand, DoTaskCommand, EditListCommand, EditTaskCommand, ListTasksCommand, RenameListCommand, UnarchiveListCommand, UncreateCommand to use the abort() method
* Updated CommandBase, AddTaskCommand, ArchiveListCommand, DoTaskCommand, EditListCOmmand, EditTaskCommand, ListTasksCommand, RenameListCommand, UnarchiveListCommand, UncreateListCommand to use askForListId() differently.
* Updated various docblocks

