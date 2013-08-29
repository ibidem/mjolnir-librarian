<?php namespace mjolnir\librarian\tests;

use \mjolnir\librarian\Markdown;

class MarkdownTest extends \app\PHPUnit_Framework_TestCase
{
	/** @test */ function
	can_be_loaded()
	{
		$this->assertTrue(\class_exists('\mjolnir\librarian\Markdown'));
	}

	// @todo tests for \mjolnir\librarian\Markdown

} # test
