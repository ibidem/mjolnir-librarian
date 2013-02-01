<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\librarian'

class Markdown extends \mjolnir\librarian\Markdown {}
class PDF extends \mjolnir\librarian\PDF {}
class PDFDriver_Dompdf extends \mjolnir\librarian\PDFDriver_Dompdf { /** @return \mjolnir\librarian\PDFDriver_Dompdf */ static function instance() { return parent::instance(); } }
class PDFDriver_Wkhtmltopdf extends \mjolnir\librarian\PDFDriver_Wkhtmltopdf { /** @return \mjolnir\librarian\PDFDriver_Wkhtmltopdf */ static function instance() { return parent::instance(); } }
class Task_Librarian extends \mjolnir\librarian\Task_Librarian { /** @return \mjolnir\librarian\Task_Librarian */ static function instance() { return parent::instance(); } }
