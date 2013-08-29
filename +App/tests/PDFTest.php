<?php namespace mjolnir\librarian\tests;

use \mjolnir\librarian\PDF;

class PDFTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\librarian\PDF'));
	}

	// @todo tests for \mjolnir\librarian\PDF

} # test
