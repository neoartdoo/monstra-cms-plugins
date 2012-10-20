<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

    // Add New Options
    Option::add('guestbook_template', 'index');

    // Create New Table 'guestbook' width fields: 'username', 'email', 'message', 'date'
    Table::create('guestbook', array('username', 'email', 'message', 'date'));