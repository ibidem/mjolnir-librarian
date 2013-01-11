<?php namespace mjolnir\librarian;

require_once \app\CFS::dir('vendor/phpwkhtmltopdf').'WkHtmlToPdf.php';

/**
 * @package    mjolnir
 * @category   Librarian
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Driver_Wkhtmltopdf extends \app\Instantiatable implements \mjolnir\types\PDFWriter
{
	/**
	 * @return string pdf
	 */
	function from_html($html)
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
	 */
	function stream($html, $filename)
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
