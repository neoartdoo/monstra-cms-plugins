<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

    // Delete Options
    Option::delete('guestbook_template');

    // Drop table 'guestbook'
    Table::drop('guestbook');