<?php

$templates = array(
  'small' => array(
    'width' => 100,
    'height' => 100,
  ),
  'large' => array(
    'width' => 300,
    'height' => 300,
  ),
);

$orig_dir = 'https://s3-us-west-2.amazonaws.com/mybucket';
$cache_folder = dirname(__FILE__) . '/cache';