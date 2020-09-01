<?php

namespace bvirk\tables;

class Template extends Join {
    public function __construct() {  parent::__construct(self::class); } 
    static function t() { return new Template(); }
    public $struct   = <<<EOV
CREATE TABLE `template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOV;
}