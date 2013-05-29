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
	static function from_html($html)
	{
		$driver = static::driver();
		return $driver->from_html($html);
	}
	
	/**
	 * Stream pdf to client.
	 */
	static function stream($html, $filename, $config = [])
	{
		$driver = static::driver();
		$driver->stream($html, $filename, $config);
	}

	/**
	 * @return \mjolnir\types\PDFDriver
	 */
	protected static function driver()
	{
		$driver = '\app\Driver_'.\ucfirst(\strtolower(\app\CFS::config('mjolnir/pdf')['driver']));
		return $driver::instance();
	}
	
} # class
