<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\librarian'


class Markdown extends \mjolnir\librarian\Markdown
{
}

class PDFDriver_Dompdf extends \mjolnir\librarian\PDFDriver_Dompdf
{
	/** @return \app\PDFDriver_Dompdf */
	static function instance() { return parent::instance(); }
}

class PDFDriver_Wkhtmltopdf extends \mjolnir\librarian\PDFDriver_Wkhtmltopdf
{
	/** @return \app\PDFDriver_Wkhtmltopdf */
	static function instance() { return parent::instance(); }
}

class PDF extends \mjolnir\librarian\PDF
{
	/** @return \app\PDFDriver */
	static function driver() { return parent::driver(); }
}

/**
 * @method \app\Task_Librarian set($name, $value)
 * @method \app\Task_Librarian add($name, $value)
 * @method \app\Task_Librarian metadata_is(array $metadata = null)
 * @method \app\Task_Librarian writer_is($writer)
 * @method \app\Writer writer()
 */
class Task_Librarian extends \mjolnir\librarian\Task_Librarian
{
	/** @return \app\Task_Librarian */
	static function instance() { return parent::instance(); }
}
