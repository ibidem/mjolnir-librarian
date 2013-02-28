<?php namespace mjolnir\librarian;

require_once \app\CFS::dir('vendor/dompdf').'dompdf_config.inc.php';

/**
 * @package    mjolnir
 * @category   Librarian
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class PDFDriver_Dompdf extends \app\Instantiatable implements \mjolnir\types\PDFWriter
{
	use \app\Trait_PDFWriter;
	
	/**
	 * @return string pdf
	 */
	function fromhtml($html)
	{
		$dompdf = new \DOMPDF();
		$dompdf->load_html($html);

		$dompdf->render();

		return $dompdf->output();
	}

	/**
	 * Stream pdf to client.
	 */
	function stream($html, $filename)
	{
		$dompdf = new \DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream($filename, ['Attachment' => 0]);
	}

} # class
