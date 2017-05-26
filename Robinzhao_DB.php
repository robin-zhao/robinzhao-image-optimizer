<?php

class Robinzhao_DB
{
    private static $instance;
    private $table;

    private function __construct()
    {
        global $wpdb;
        $this->table = $wpdb->prefix . 'rz_io';
    }

    private function __clone()
    {
    }

    /**
     * @return $this
     */
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function install()
    {
        global $wpdb;

        if($wpdb->get_var("show tables like '{$this->table}'") != $this->table)
        {
            $sql = "CREATE TABLE " . $this->table . " (
            `ID` bigint(20) unsigned NOT NULL,
            `processed` tinyint(1) NOT NULL default 0,
            UNIQUE KEY ID (ID)
            )";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }

    public function fetch($limit = 0)
    {
        global $wpdb;

        $sql = "select ID from {$this->table} where processed = 0";
        if ($limit > 0) {
            $sql .= ' limit ' . $limit;
        }
        return $wpdb->get_results($sql);
    }

    public function getImages($id)
    {
        global $wpdb;
        $sql = "select guid from {$wpdb->prefix}posts where ID = $id";
        $string = $wpdb->get_var($sql);
        $parts = explode('/wp-content/', $string);
        if (isset($parts[1])) {
            $file = ABSPATH . 'wp-content/' . $parts[1];
            if (file_exists($file)) {
                $pathParts = pathinfo($file);
                $withoutExtension = $pathParts['dirname'] . '/' . $pathParts['filename'];

                return glob($withoutExtension . '*');
            }
        }
        return [];
    }

    public function update($id)
    {
        global $wpdb;
        $sql = "update {$this->table} set processed = 1 where ID = $id";
        $wpdb->query($sql);
    }

    public function number()
    {
        global $wpdb;
        $sql = "select count(ID) from {$this->table}";
        return $wpdb->get_var($sql);
    }

    public function numberUnprocessed()
    {
        global $wpdb;
        $sql = "select count(ID) from {$this->table} where processed = 0";
        return $wpdb->get_var($sql);
    }

    public function fillId()
    {
        global $wpdb;

        $ids = "select ID from {$wpdb->prefix}posts"
        . " where post_type = 'attachment'"
            . " and post_mime_type = 'image/jpeg'"
            . " and ID not in (select ID from {$this->table})";

        $results = $wpdb->get_results($ids);

        if ($results) {
            foreach ($results as $object) {
                $wpdb->query("insert into {$this->table} values ({$object->ID}, 0)");
            }
        }
    }
}