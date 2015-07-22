<?php

require_once('pagination.php');

// Basic Usage

$pagination = new pagination();

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' =>  $_SERVER['PHP_SELF'],
	'total' => $total,
	'start' => $start,
	'limit' => $limit
));

echo $pagination->paging ();


// Basic Using pagination in javascript

$pagination = new pagination();

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'callscript'	=> 'move_to_page',
	'param' 		=> array('hello'),
	'type' 			=> 'script',
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit
));


echo $pagination->paging ();


// Adding custom css class to pagging


$pagination = new pagination();

$pagination->container_class = "pagination pagination-sm pull-right"; // class name for page container

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit
));


echo $pagination->paging ();


// Get Paging Element as an array


$pagination = new pagination();

$pagination->container_class = "pagination pagination-sm pull-right"; // class name for page container

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit
));


$pagination->paging ();


$pages = $pagination->get_array_pages();

var_dump($pages);

// Prevent end point navigation

$pagination = new pagination();

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit,
	'endpointnavigation' => false
));


echo $pagination->paging ();


// coustom navigation buttons

$pagination = new pagination();

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit,
	'next' 			=> '<span >Next</span> ',
	'prev' 			=> '<span >Prev</span>',
	'first' 		=> '<span >First</span>',
	'last' 			=> '<span >Last</span>'
));


echo $pagination->paging ();

// Pagination without page buttons

$pagination = new pagination();

$total = 1000;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit,
	'singlepage' 	=> false,
	'next' 			=> '<span >Next</span> ',
	'prev' 			=> '<span >Prev</span>',
	'first' 		=> '<span >First</span>',
	'last' 			=> '<span >Last</span>'
));


echo $pagination->paging ();


// Show navigation even if no item founf

$pagination = new pagination();

$total = 0;

$limit = 50;

$start = isset($_GET['start']) ? $_GET['start'] : 0;

$pagination->setOptions(array(
	'link' 			=> $_SERVER['PHP_SELF'],
	'total'			=> $total,
	'start' 		=> $start,
	'limit' 		=> $limit,
	'forcenav' 		=> true,
));


echo $pagination->paging ();


?>


<script type="text/javascript">
	
function move_to_page(obj,param,start,limit){
	alert('param : ' + param);
	alert('start : ' + start);
	alert('limit : ' + limit);

}

</script>