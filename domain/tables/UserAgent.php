<?php

namespace bvirk\tables;

class UserAgent extends Join {
    public function __construct() {  parent::__construct(self::class); } 
    static function t() { return new UserAgent(); }
    public $struct   = <<<EOV
CREATE TABLE `userAgent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOV;
}