<?php namespace mjolnir\librarian;

require_once \app\CFS::dir('vendor/php-wkhtmltopdf').'WkHtmlToPdf.php';

/**
 * @package    mjolnir
 * @category   Librarian
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class PDFDriver_Wkhtmltopdf extends \app\Instantiatable implements \mjolnir\types\PDFWriter
{
	use \app\Trait_PDFWriter;

	/**
	 * @return string pdf
	 */
	function fromhtml($html)
	{
		try
		{
			$pdf = new \WkHtmlToPdf;
			$pdf->addPage($html);

			$tmp = \tempnam("/tmp", "mjolnir_wkhtmltopdf_");
			$pdf->saveAs($tmp);
			$contents = \app\Filesystem::gets($tmp);
			\app\Filesystem::delete($tmp);

			return $contents;
		}
		catch (\Exception $exception)
		{
			throw new \app\Exception('WkHtmlToPdf not correctly configured on system: '.$exception->getMessage());
		}
	}

	/**
	 * Stream to client.
	 *
	 * You may pass extra configuration, such as paper and orientation, but if
	 * the configuration is read depends on the driver, so the configuration
	 * should be considered along the lines of hints.
	 */
	function stream($html, $filename, $hints = [])
	{
		try
		{
			$pdf = new \WkHtmlToPdf;
			$pdf->addPage($html);
			$pdf->send('test.pdf');
		}
		catch (\Exception $exception)
		{
			throw new \app\Exception('WkHtmlToPdf not correctly configured on system: '.$exception->getMessage());
		}
	}

} # class
