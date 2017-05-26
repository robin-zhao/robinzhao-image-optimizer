<?php

/*
Plugin Name: robinzhao-image-optimizer
Plugin URI:
Description:
Version: 1.0.0
Author: Robin Zhao <boborabit@qq.com>
Author URI:
License: GPLv2
*/

/*
Copyright (C) 2017 Robin Zhao <boborabit@qq.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Load classes.
if (!class_exists('Robinzhao_IO')) {
    require __DIR__ . '/Robinzhao_Log.php';
    require __DIR__ . '/Robinzhao_Log_File.php';
    require __DIR__ . '/Robinzhao_Log_Wpcli.php';
    require __DIR__ . '/Robinzhao_DB.php';
    require __DIR__ . '/Robinzhao_Optimiser.php';
}

if (class_exists('WP_CLI')) {
    if (!class_exists('Robinzhao_Command')) {
        require __DIR__ . '/Robinzhao_Command.php';
    }
}

// Create the table on activation.
register_activation_hook(__FILE__, function () {
    Robinzhao_DB::getInstance()->install();
});

// Add sub menu page under Media Uploader.
add_action('admin_menu', function () {
    add_submenu_page(
        'upload.php',
        'Robinzhao Image Optimiser',
        'Robinzhao IO',
        'manage_options',
        'robinzhao-io',
        'robinzhao_io_page'
    );
});

function robinzhao_io_page()
{
    exec('which mogrify', $output, $return_var);
    if ($return_var === 1) {
        require __DIR__ . '/templates/missing-imagemagick.phtml';
        return;
    }
    exec('which wp', $output, $return_var);
    if ($return_var === 1) {
        require __DIR__ . '/templates/missing-wpcli.phtml';
        return;
    }

    Robinzhao_DB::getInstance()->fillId();

    $toProcess = Robinzhao_DB::getInstance()->numberUnprocessed();
    if (!$toProcess) {
        require __DIR__ . '/templates/all-set.phtml';
        return;
    }

    $number = Robinzhao_DB::getInstance()->number();

    require __DIR__ . '/templates/to-process.phtml';

}
