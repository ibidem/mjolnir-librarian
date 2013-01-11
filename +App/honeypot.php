<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\librarian'

class Driver_Dompdf extends \mjolnir\librarian\Driver_Dompdf { /** @return \mjolnir\librarian\Driver_Dompdf */ static function instance() { return parent::instance(); } }
class Driver_Wkhtmltopdf extends \mjolnir\librarian\Driver_Wkhtmltopdf { /** @return \mjolnir\librarian\Driver_Wkhtmltopdf */ static function instance() { return parent::instance(); } }
class Markdown extends \mjolnir\librarian\Markdown {}
class PDF extends \mjolnir\librarian\PDF {}
class Task_Librarian extends \mjolnir\librarian\Task_Librarian { /** @return \mjolnir\librarian\Task_Librarian */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
