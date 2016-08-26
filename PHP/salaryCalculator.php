<?php

// Initializing variables when they are posted. Would be sanizited in production
if (isset($_POST['classSize'], $_POST['baseSalary'], $_POST['statusIndex']))
{
	$max_size = $_POST['classSize'];
	$base_salary = $_POST['baseSalary'];
	$status_index = $_POST['statusIndex'];
}

// Generated a salary bonus based on class size. 
function generateSizeBonus($potential)
{
	$size_divisor = floor($potential / 25);
	$bonus = 1 + ($size_divisor / 10);

	return $bonus;
}

/* Generates the predicted class size. The status_index would be generated using statistical analysis in production,
 * as the user should have no part in providing a complex variable such as this. This is just a simple demonstration.
 */
function indexDeterminator($status_index, $max_size)
{
	$size_product = $status_index * $max_size;
	$potential_size = $size_product > $max_size ? $max_size : $size_product;

	return $potential_size;
}

// Creating variables for the potential class size and bonus percentage, used to generate final salary.
$potential = indexDeterminator($status_index, $max_size);
$bonus = generateSizeBonus($potential);

// Generate final salary
$predicted_salary = $base_salary * $bonus;

// Send back to JS
echo $predicted_salary;

?>