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
		return $driver->fromhtml($html);
	}

	/**
	 * Stream pdf to client.
	 *
	 * You may pass extra configuration, such as paper and orientation, but if
	 * the configuration is read depends on the driver, so the configuration
	 * should be considered along the lines of hints.
	 */
	static function stream($html, $filename, $hints = [])
	{
		$driver = static::driver();
		$driver->stream($html, $filename, $hints);
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
