<?php

if (isset($_POST['classSize'], $_POST['baseSalary'], $_POST['statusIndex']))
{
	$max_size = $_POST['classSize'];
	$base_salary = $_POST['baseSalary'];
	$status_index = $_POST['statusIndex'];
}

function generateSizeBonus($potential)
{
	$size_divisor = floor($potential / 25);
	$bonus = 1 + ($size_divisor / 10);

	return $bonus;
}

function indexDeterminator($status_index, $max_size)
{
	$size_product = $status_index * $max_size;
	$potential_size = $size_product > $max_size ? $max_size : $size_product;

	return $potential_size;
}

$potential = indexDeterminator($status_index, $max_size);
$bonus = generateSizeBonus($potential);

$predicted_salary = $base_salary * $bonus;

echo $predicted_salary;

?>