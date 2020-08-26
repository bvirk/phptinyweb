<?php

namespace bvirk\tables;

class Site extends Join {
    public function __construct() {  parent::__construct(self::class); } 
    static function t() { return new Site(); }
    public $struct   = <<<EOV
CREATE TABLE `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pageid` int(11) NOT NULL,
  `secid` int(11) NOT NULL,
  `content` varchar(1024) NOT NULL,
  `pic` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOV;
}