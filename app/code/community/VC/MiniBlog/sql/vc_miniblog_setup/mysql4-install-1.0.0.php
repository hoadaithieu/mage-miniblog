<?php
$installer = $this;

$installer->startSetup();
$installer->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_post')}` (
	`post_id` int(11) NOT NULL,
	`title` varchar(200) DEFAULT NULL,
	`identifier` varchar(200) DEFAULT NULL,
	`enable` tinyint(1) DEFAULT '1',
	`enable_comment` tinyint(1) DEFAULT '1',
	`tags` varchar(200) DEFAULT NULL,
	`short_content` text,
	`content` text,
	`meta_keyword` text,
	`meta_description` text,
	`poster` varchar(200) DEFAULT NULL,
	`post_id` int(11) DEFAULT '0',
	`created_at` datetime DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `{$this->getTable('vc_miniblog_post')}`
ADD PRIMARY KEY (`post_id`);
ALTER TABLE `{$this->getTable('vc_miniblog_post')}`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;
");

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_category')}` (
	`category_id` int(11) NOT NULL,
	`title` varchar(200) DEFAULT NULL,
	`identifier` varchar(200) DEFAULT NULL,
	`sort_order` int(11) DEFAULT '0',
	`meta_keyword` text,
	`meta_description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `{$this->getTable('vc_miniblog_category')}`
ADD PRIMARY KEY (`category_id`);

ALTER TABLE `{$this->getTable('vc_miniblog_category')}`
MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;
");

$installer->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_comment')}` (
	`comment_id` int(11) NOT NULL,
	`email` varchar(200) DEFAULT NULL,
	`enable` tinyint(1) DEFAULT '0',
	`content` text,
	`user` varchar(200) DEFAULT NULL,
	`post_id` int(11) DEFAULT '0',
	`created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `{$this->getTable('vc_miniblog_comment')}`
ADD PRIMARY KEY (`comment_id`);
 
ALTER TABLE `{$this->getTable('vc_miniblog_comment')}`
MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;");

$installer->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_post_category')}` (
	`post_id` int(11) DEFAULT '0',
	`category_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_post_store')}` (
	`post_id` int(11) DEFAULT '0',
	`store_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");


$installer->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('vc_miniblog_category_store')}` (
	`category_id` int(11) DEFAULT '0',
	`store_id` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
");


$installer->endSetup(); 
