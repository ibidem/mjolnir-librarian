<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'mjolnir\librarian'

class Markdown extends \mjolnir\librarian\Markdown {}
class PDF extends \mjolnir\librarian\PDF {}
class Task_Librarian extends \mjolnir\librarian\Task_Librarian { /** @return \mjolnir\librarian\Task_Librarian */ static function instance($encoded_task = null) { return parent::instance($encoded_task); } }
