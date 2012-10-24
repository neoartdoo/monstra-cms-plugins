<?php defined('MONSTRA_ACCESS') or die('No direct script access.');


if ( ! Dir::exists(STORAGE . DS . 'starrating')) {
    Dir::create(STORAGE . DS . 'starrating');
}
