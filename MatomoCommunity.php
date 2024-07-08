<?php

namespace Piwik\Plugins\MatomoCommunity;

use Piwik\Plugin;
use Piwik\Db;
use Piwik\Option;
use Piwik\Config;

class MatomoCommunity extends Plugin
{
    public function install()
    {
        $db = Db::get();
        $prefix = Config::getInstance()->database['tables_prefix'];

        // Create membership_groups table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}membership_groups` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `features` TEXT NOT NULL,
                PRIMARY KEY (`id`)
            )
        ");

        // Alter user table to add group_id
        $db->query("ALTER TABLE `{$prefix}user` ADD COLUMN `group_id` INT(11) DEFAULT NULL");

        // Insert default membership group
        $db->query("INSERT INTO `{$prefix}membership_groups` (`name`, `features`) VALUES ('Default', '[]')");
        $defaultGroupId = $db->lastInsertId();
        Option::set('default_membership_group', $defaultGroupId);

        // Create messages table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}messages` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `sender_id` INT(11) NOT NULL,
                `receiver_id` INT(11) NOT NULL,
                `subject` VARCHAR(255),
                `message` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create value_worth table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}value_worth` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `site_id` INT(11) NOT NULL,
                `value` DECIMAL(10, 2) NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create offers table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}offers` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `site_id` INT(11) NOT NULL,
                `sender_id` INT(11) NOT NULL,
                `message` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create watchlist table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}watchlist` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `user_id` INT(11) NOT NULL,
                `site_id` INT(11) NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");

        // Create community_experiments table
        $db->query("
            CREATE TABLE IF NOT EXISTS `{$prefix}community_experiments` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `experiment_id` INT(11) NOT NULL,
                `user_id` INT(11) NOT NULL,
                `result` TEXT NOT NULL,
                `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
        ");
    }

    public function uninstall()
    {
        $db = Db::get();
        $prefix = Config::getInstance()->database['tables_prefix'];

        // Drop membership_groups table
        $db->query("DROP TABLE IF EXISTS `{$prefix}membership_groups`");

        // Remove group_id column from user table
        $db->query("ALTER TABLE `{$prefix}user` DROP COLUMN `group_id`");

        // Remove default membership group from option table
        Option::delete('default_membership_group');

        // Drop messages table
        $db->query("DROP TABLE IF EXISTS `{$prefix}messages`");

        // Drop value_worth table
        $db->query("DROP TABLE IF EXISTS `{$prefix}value_worth`");

        // Drop offers table
        $db->query("DROP TABLE IF EXISTS `{$prefix}offers`");

        // Drop watchlist table
        $db->query("DROP TABLE IF EXISTS `{$prefix}watchlist`");

        // Drop community_experiments table
        $db->query("DROP TABLE IF EXISTS `{$prefix}community_experiments`");
    }

    public function registerEvents()
    {
        return [
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'Template.controllerMetaAttributes' => 'addControllerMetaAttributes',
        ];
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = 'plugins/MatomoCommunity/js/community.js';
    }

    public function getStylesheetFiles(&$stylesheetFiles)
    {
        $stylesheetFiles[] = 'plugins/MatomoCommunity/styles/community.css';
    }

    public function addControllerMetaAttributes(&$attributes, $controller)
    {
        if ($controller instanceof CommunityController) {
            $attributes['module'] = 'MatomoCommunity';
        }
    }
}
