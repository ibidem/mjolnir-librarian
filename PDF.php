<?php namespace mjolnir\librarian;

require_once \app\CFS::dir('vendor/dompdf').'dompdf_config.inc.php';

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
		$dompdf = new \DOMPDF();
		$dompdf->load_html($html);

		$dompdf->render();

		return $dompdf->output();
	}

} # class
