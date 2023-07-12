<?php

function getHostInfo($ini_file){
	$data = parse_ini_file($ini_file, $process_sections=true);
	return $data;
}

