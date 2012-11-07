<?php namespace mjolnir\librarian;

require_once \app\CFS::dir('vendor/php-markdown-extra').'markdown.php';

/**
 * @package    mjolnir
 * @category   Librarian
 * @author     Ibidem
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Markdown
{
	/**
	 * @return string
	 */
	static function to_html($markdown)
	{
		return \Markdown($markdown);
	}

} # class
