<?php
/*
	* Obliczanie kwoty brutto i zaokrąglanie.
*/
function fnc_calculate_brutto($price = 0) {
	
	if ($price == 0) {
		return false;
	}
	
	return round(($price * 2) * 1.23, 2, PHP_ROUND_HALF_UP);
	
}
