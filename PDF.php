<?php namespace mjolnir\librarian;

/**
 * @package    mjolnir
 * @category   Librarian
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class PDF
{
	/**
	 * @return string pdf
	 */
	static function fromhtml($html)
	{
		$driver = static::driver();
		return $driver->from_html($html);
	}

	/**
	 * Stream pdf to client.
	 */
	static function stream($html, $filename)
	{
		$driver = static::driver();
		$driver->stream($html, $filename);
	}

	/**
	 * @return \mjolnir\types\PDFDriver
	 */
	protected static function driver()
	{
		$driver = '\app\PDFDriver_'.\ucfirst(\strtolower(\app\CFS::config('mjolnir/pdf')['driver']));
		return $driver::instance();
	}

} # class
