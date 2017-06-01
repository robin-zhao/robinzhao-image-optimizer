<?php

/*
Plugin Name: Robinzhao / Image Optimizer
Plugin URI:
Description: Auto optimize uploaded images with Imagemagick.
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
        require __DIR__ . '/missing-imagemagick.phtml';
        return;
    }

    require __DIR__ . '/all-set.phtml';
}

add_filter( 'wp_handle_upload', function (array $upload, string $context) {

    $file = $upload['file'];

    if (file_exists($file)) {
        $cmd = "mogrify -strip -interlace Plane -quality 85%% -resize '768x432>' -sampling-factor 4:2:0 %s";
        exec(sprintf($cmd, $file), $out, $rtn);
        if ($rtn === 1) {
            // @todo
        }
    }

    return $upload;

}, 10, 2 );