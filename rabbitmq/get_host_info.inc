<?php

function getHostInfo($machine){
	$data = parse_ini_file($machine, $process_sections=true);
	return $data;
}

