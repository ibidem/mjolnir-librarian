<?php namespace mjolnir\librarian\tests;

use \mjolnir\librarian\Task_Librarian;

class Task_LibrarianTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\librarian\Task_Librarian'));
	}

	// @todo tests for \mjolnir\librarian\Task_Librarian

} # test
