<?php

namespace bvirk\tables;

class ReqLog extends Join {
    public function __construct() {  parent::__construct(self::class); } 
    static function t() { return new ReqLog(); }
    public $struct   = <<<EOV
CREATE TABLE `reqLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `path` varchar(50) NOT NULL,
  `ip` varchar(25) NOT NULL,
  `uaid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
EOV;
}