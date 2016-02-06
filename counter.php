Saturday, 6th February 2016
Jonas Bunawan

<?php

// Establishing a function (with 2 parameters) to calculate remainder of the number that is divided by certain divider
function RemainderCalculation($number,$divider) {
	$result = $number % $divider;
	return $result;
}

// Looping 100 numbers to be displayed which then each number is processed by certain rules.
for ($num = 1; $num <= 100; $num++){

	// Displaying output based on particular condition with the use of pre-user-defined RemainderCalculation function
	// If remainder of looped number that is divided by three (3) and five (5) / 15 is equal to 0, then the number and message of 'triplefiver' as its description will be displayed on the user's screen
	if (RemainderCalculation($num, 15) == 0){
		echo $num . " is triplefiver" . "\n";
	} // If looped number is divisible by five (5), then the number and message of 'fiver' as its description will be displayed on the user's screen
	  elseif (RemainderCalculation($num, 5) == 0) {
		echo $num . " is fiver" . "\n";
	} // If looped number is divisible by three (3), then the number and message of 'triple' as its description will be displayed on the user's screen
	  elseif (RemainderCalculation($num, 3) == 0) {
		echo $num . " is triple" . "\n";
	} // If none of the conditions above are met, then the number itself will be displayed on the screen
	  else {
		echo $num . "\n";
	}
}

echo "\n" . "This is the end line of triple fiver mini program execution :) TA" . "\n";
?>
