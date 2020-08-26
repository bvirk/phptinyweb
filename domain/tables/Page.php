<?php

namespace bvirk\tables;

class Page extends Join {
    public function __construct() {  parent::__construct(self::class); } 
    static function t() { return new Page(); }
    public $struct   = <<<EOV
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOV;
}
