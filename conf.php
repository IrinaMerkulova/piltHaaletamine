<?php
$serverinimi="localhost";
$kasutaja="irina";
$parool="123456";
$andmebaas="irina";


$yhendus=new mysqli($serverinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset("utf8");