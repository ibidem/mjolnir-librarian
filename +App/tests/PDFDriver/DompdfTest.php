<?php namespace mjolnir\librarian\tests;

use \mjolnir\librarian\PDFDriver_Dompdf;

class PDFDriver_DompdfTest extends \PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\librarian\PDFDriver_Dompdf'));
	}

	// @todo tests for \mjolnir\librarian\PDFDriver_Dompdf

} # test
