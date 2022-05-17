<?php

class InputValidator
{
	// Otestuje zda je správně zadán vstup
	public static function isCorrect($input, $arg): bool
	{
		if(strpos($input->getvalue(), ".") !== false) {
			$s = explode('.', $input->getvalue());	
			if ($s[0] !== "" && $s[1] !== "") {
				return true;					
			} else return false;
		}
		else return false;
	}
}