<?php 

namespace Generators\Exceptions;

class ArgumentParserException extends \Exception 
{
	public static function Error($msg)
	{
		throw new static($msg);
	}
}
