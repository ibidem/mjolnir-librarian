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

		$manual_intro
			= "<!DOCTYPE html>\n<html>\n"
			. "<head>\n\t<meta charset=\"UTF-8\"/>\n\t<title>Manual</title>\n"
			. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>"
			. "\t<style type=\"text/css\">\n"
			. \app\View::instance('mjolnir/librarian/style')->render()
			. "\t</style>\n"
			. "</head>\n\n"
			. "<body>\n"
			. "<h1 class=\"nobreak\">Manual<h1>"
			;

		// build table of contents
		$TOC = "<h2>Table of Contents</h2>\n<ul class=\"toc\">";
		$toc_book_idx = 0;

		$manual = '';

		foreach ($books as $book_key => $book)
		{
			// TOC entry
			$toc_section_idx = 0;
			$toc_book_idx++;
			$TOC .= "<li>{$toc_book_idx}. <a href=\"#{$book_key}\" class=\"toc-book\">{$book['title']}</a></li>";

			// create cover
			$manual .= "<h1 id=\"{$book_key}\">{$book['title']}</h1>\n\n";

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
				// TOC entry
				$toc_chapter_idx = 0;
				$toc_section_idx++;
				$TOC .= "<li>{$toc_book_idx}.{$toc_section_idx} <a href=\"#{$book_key}_{$section_key}\" class=\"toc-section\">{$section['title']}</a></li>";

				$manual .= "<h2 id=\"{$book_key}_{$section_key}\">{$toc_book_idx}.{$toc_section_idx} {$section['title']}</h2>\n\n";

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
					// TOC entry
					$toc_chapter_idx++;
					$TOC .= "<li>{$toc_book_idx}.{$toc_section_idx}.{$toc_chapter_idx} <a href=\"#{$book_key}_{$section_key}_{$chapter_key}\" class=\"toc-chapter\">{$chapter['title']}</a></li>";

					$title = "<h3 id=\"{$book_key}_{$section_key}_{$chapter_key}\">{$toc_book_idx}.{$toc_section_idx}.{$toc_chapter_idx} {$chapter['title']}</h3>\n\n";

					$manual .= $this->parse_component
						(
							$chapter,
							$section['namespace'],
							$title
						);
				}
			}
		}

		$TOC .= "</ul>\n";

		$manual_outro = '</body></html>';

		$manual = $manual_intro.$TOC.$manual.$manual_outro;

		$this->writer
			->write(' Generating HTML manual... ');

		// generate html docs
		\file_put_contents(DOCROOT.'manual.html', $manual);

		$this->writer
			->write('done.')
			->eol();

		$this->writer
			->write(' Generating PDF manual... ');

		// generate pdf docs
		\file_put_contents(DOCROOT.'manual.pdf', \app\PDF::from_html($manual));

		$this->writer
			->write('done.')
			->eol();
	}

	/**
	 * @return string html contents of the specified component
	 */
	protected function parse_component(array $component, $namespace, $title = null)
	{
		if (isset($component['namespace']))
		{
			$namespace = $component['namespace'];
		}

		$html = '';

		if ($title !== null)
		{
			$html .= $title;
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
