<?php
/**
 * Public class
 *
 * @package TS_ML_Integration
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Public class
 */
class TS_ML_Public {
    
    /**
     * Instance
     *
     * @var TS_ML_Public
     */
    private static $instance = null;
    
    /**
     * Get instance
     *
     * @return TS_ML_Public
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        // Constructor
    }
}
