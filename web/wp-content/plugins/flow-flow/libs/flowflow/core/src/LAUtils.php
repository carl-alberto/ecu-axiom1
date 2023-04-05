<?php namespace la\core;

use la\core\db\LADBManager;

/**
 *
 * @author    navdeykin <navdeykin@gmail.com>
 * @copyright 2014-2020 Looks Awesome
 */
class LAUtils {
    /**
     * @param array $context
     * @return LADBManager
     */
    public static function dbm( $context ) {
        return $context['db_manager'];
    }

    /**
     * @param array $context
     * @return string
     */
    public static function root( $context ) {
        return $context['root'];
    }

    /**
     * @param array $context
     * @return string
     */
    public static function slug( $context ) {
        return $context['slug'];
    }

    /**
     * @param array $context
     * @return string
     */
    public static function slug_down( $context ) {
        return $context['slug_down'];
    }

    /**
     * @param array $context
     * @return string
     */
    public static function version( $context ) {
        return $context['version'];
    }

    /**
     * @param array $context
     * @return string
     */
    public static function plugin_url( $context ) {
        return $context['plugin_url'] . LAUtils::slug($context);
    }
}