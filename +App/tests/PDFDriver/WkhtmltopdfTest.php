<?php namespace mjolnir\librarian\tests;

use \mjolnir\librarian\PDFDriver_Wkhtmltopdf;

class PDFDriver_WkhtmltopdfTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\librarian\PDFDriver_Wkhtmltopdf'));
	}

	// @todo tests for \mjolnir\librarian\PDFDriver_Wkhtmltopdf

} # test
