<?php
// Config for our awesome getting stuff done application
return array(
    'folder' => '/home/chuck/Documents/gsd/',
    'extension' => '.txt',
    'listOrder' => array(
        'inbox', 'actions', 'waiting', 'someday', 'calendar',
    ),
    'defaultList' => 'actions',      // default list when not specified
    'noListPrompt' => true,          // true=prompt for list, false=use default
    'dateCompleteFormat' => 'n/j/y', // date format for completed tasks
    'dateDueFormat' => 'M-j',        // date format for due tasks

    // Command aliases
    'aliases' => array(
        'gsd:list'    => array('gsd:ls'),
        'gsd:listall' => array('gsd:la'),
        'gsd:move'    => array('gsd:mv'),
        'gsd:remove'  => array('gsd:rm'),
    ),
);

