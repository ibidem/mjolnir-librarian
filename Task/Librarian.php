<?php namespace mjolnir\librarian;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Librarian extends \app\Task
{
	/**
	 * Execute task.
	 */
	function execute()
	{
		$manual = $this->config['manual'];

		if ($manual)
		{
			$this->create_manual();
		}
	}

	/**
	 * Scan modules and create manual.
	 */
	function create_manual()
	{
		$books = \app\CFS::config('mjolnir/books');

		// sort books
		\uasort($books, function ($a, $b)
			{
				if ($a['idx'] < $b['idx'])
				{
					return -1;
				}
				else if ($a['idx'] > $b['idx'])
				{
					return +1;
				}
				else # equal
				{
					return 0;
				}
			});

		$manual
			= "<!DOCTYPE html>\n<html>\n"
			. "<head>\n\t<meta charset=\"UTF-8\"/>\n\t<title>Manual</title>\n"
			. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>"
			. "\t<style type=\"text/css\">\n"
			. \app\View::instance('mjolnir/librarian/style')->render()
			. "\t</style>\n"
			. "</head>\n\n"
			. "<body>\n"
			;

		foreach ($books as $book_key => $book)
		{
			// create cover
			$manual .= "<h1>{$book['title']}</h1>\n\n";

			// sort sections
			\uasort($book['sections'], function ($a, $b)
				{
					if ($a['idx'] < $b['idx'])
					{
						return -1;
					}
					else if ($a['idx'] > $b['idx'])
					{
						return +1;
					}
					else # equal
					{
						return 0;
					}
				});

			foreach ($book['sections'] as $section_key => $section)
			{
				$manual .= "<h2>{$section['title']}</h2>\n\n";

				// insert introduction
				$manual .= $this->parse_component
					(
						$section['introduction'],
						$section['namespace']
					);

				// sort chapters
				\uasort($section['chapters'], function ($a, $b)
					{
						if ($a['idx'] < $b['idx'])
						{
							return -1;
						}
						else if ($a['idx'] > $b['idx'])
						{
							return +1;
						}
						else # equal
						{
							return 0;
						}
					});

				foreach ($section['chapters'] as $chapter_key => $chapter)
				{
					$manual .= $this->parse_component
						(
							$chapter,
							$section['namespace']
						);
				}
			}
		}

		$manual .= '</body></html>';

		// generate html docs
		\file_put_contents(DOCROOT.'manual.html', $manual);

		// generate pdf docs
		\file_put_contents(DOCROOT.'manual.pdf', \app\PDF::from_html($manual));

		$this->writer->write(' Manual created.');
	}

	/**
	 * @return string html contents of the specified component
	 */
	protected function parse_component(array $component, $namespace)
	{
		if (isset($component['namespace']))
		{
			$namespace = $component['namespace'];
		}

		$html = '';

		if (isset($component['title']))
		{
			$html .= "<h3>{$component['title']}</h3>\n\n";
		}

		// find file
		$path = \app\CFS::modulepath($namespace).'+Docs'.DIRECTORY_SEPARATOR;
		$file = $path.$component['file'];

		if (\file_exists($file))
		{
			if ($component['type'] === 'markdown')
			{
				$html .= \app\Markdown::to_html(\file_get_contents($file));
			}
			else # unknown
			{
				$html = "<p>Failed to compute chapter, unknown type: {$component['type']}</p>\n\n";
			}
		}
		else # file not found
		{
			$html = "<p>Failed to compute chapter, missing file: $file</p>\n\n";
		}

		$html .= "<br class=\"pagebreak\"/>\n\n";

		return $html;
	}

} # class
