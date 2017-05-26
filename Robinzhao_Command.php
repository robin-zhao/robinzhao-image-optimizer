<?php
/**
 * Run Image Optimiser.
 *
 * @doc https://make.wordpress.org/cli/handbook/commands-cookbook/
 */
class Robinzhao_Command extends WP_CLI_Command {

    /**
     * Convert Images for better web page usage.
     *
     * ## OPTIONS
     *
     * [--limit=<limit>]
     * : Number of images to process.
     * ---
     * default: 10
     * ---
     *
     * [--log=<console>]
     * : Log format
     * ---
     * default: console
     * options:
     *   - console
     *   - file
     * ---
     *
     * ## EXAMPLES
     *
     *     wp io run --number=100
     *
     * @when after_wp_load
     */
    function run( $args, $assoc_args ) {

        $limit = $assoc_args['limit'];
        $log = $assoc_args['log'];

        if ($log == 'console') {
            $logObj = new Robinzhao_Log_Wpcli();
        } else {
            $logObj = new Robinzhao_Log_File();
        }

        $o = new Robinzhao_Optimiser($logObj);
        $o->run($limit);
    }

}

WP_CLI::add_command( 'io', 'Robinzhao_Command' );