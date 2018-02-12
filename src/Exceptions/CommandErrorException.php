<?php 

namespace Generators\Exceptions;

use Symfony\Component\Console\Exception\CommandNotFoundException;

class CommandErrorException 
{
	public static function NotFound($command)
	{
		throw new CommandNotFoundException ("Command '{$command}' is not defined.");
	}

	public static function NotSupport($command)
	{
		throw new CommandNotFoundException ("Not support the action '{$command}'!");
	}

	public static function EmptyCommand()
	{
		throw new CommandNotFoundException ("Action can't be empty!");
	}

	public static function ErrorInfo($msg)
	{
		throw new CommandNotFoundException ($msg);
	}
}