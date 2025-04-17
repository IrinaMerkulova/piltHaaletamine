<?php
$serverinimi="d70420.mysql.zonevs.eu";
$kasutaja="d70420_irinamerkulova";
$parool="123456";
$andmebaas="d70420_phpbaas";


$yhendus=new mysqli($serverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset("utf8");
