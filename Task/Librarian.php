<?php namespace mjolnir\librarian;

/**
 * @package    mjolnir
 * @category   Task
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Task_Librarian extends \app\Instantiatable implements \mjolnir\types\Task
{
	use \app\Trait_Task;

	/**
	 * Execute task.
	 */
	function run()
	{
		$manual = $this->get('manual', false);

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

		$manual_pdf_intro
			= "<!DOCTYPE html>\n<html>\n"
			. "<head>\n"
			. "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n"
			. "\t<title>Manual</title>\n"
			. "\t<style type=\"text/css\">\n"
			. \app\View::instance('mjolnir/librarian/pdf-style', '.css')->render()
			. "\t</style>\n"
			. "</head>\n\n"
			. "<body>\n"
			;

		$manual_html_intro
			= "<!DOCTYPE html>\n<html>\n"
			. "<head>\n"
			. "\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"/>\n"
			. "\t<title>Manual</title>\n"
			. "\t<style type=\"text/css\">\n"
			. \app\View::instance('mjolnir/librarian/html-style', '.css')->render()
			. "\t</style>\n"
			. "</head>\n\n"
			. "<body>\n"
			;

		// build table of contents
		$TOC = "<h2 class=\"nobreak\"><a name=\"toc\">Table of Contents</a></h2>\n<ul class=\"toc\">";
		$toc_book_idx = 0;

		$manual = '';

		foreach ($books as $book_key => $book)
		{
			// TOC entry
			$toc_section_idx = 0;
			$toc_book_idx++;
			$TOC .= "<li>{$toc_book_idx}. <a href=\"#{$book_key}\" class=\"toc-book\">{$book['title']}</a></li>";

			// create cover
			$manual .= "<h1><a name=\"{$book_key}\">{$book['title']}</a></h1>\n\n";
			$manual .= "<div class=\"titlequote\">{$book['quote']}</div>\n";
			if ( ! empty($book['cover']))
			{
				$manual .= "<center><img class=\"cover\" src=\"{$book['cover']}\"/></center>";
			}

			// introduction notes
			$manual .= '<div class="pagebreak">'.$this->parse_component($book['introduction']).'</div>';

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

				$manual .= "<h2><a class=\"toclink\" href=\"#toc\">{$toc_book_idx}.{$toc_section_idx}</a> <a name=\"{$book_key}_{$section_key}\">{$section['title']}</a></h2>\n\n";

				// insert introduction
				$manual .= $this->parse_component
					(
						$section['introduction'],
						$section['namespace']
					);

				// introductions rarely have enough content and cause bad page
				// breaks so we fix this by placing a page break imediatly
				// after it
				$manual .= '<div class="pagebreak"></div>';

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

					$title = "<h3><a class=\"toclink\" href=\"#toc\">{$toc_book_idx}.{$toc_section_idx}.{$toc_chapter_idx}</a> <a name=\"{$book_key}_{$section_key}_{$chapter_key}\">{$chapter['title']}</a></h3>\n\n";

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

		$manual_out = $manual_html_intro.$TOC.$manual.$manual_outro;

		$this->writer
			->writef(' Generating HTML manual... ');

		// generate html docs
		\file_put_contents(\app\Env::key('sys.path').'manual.html', $manual_out);

		$this->writer
			->writef('done.')
			->eol();

		if (\extension_loaded('gd'))
		{
			$manual_out = $manual_pdf_intro.$TOC.$manual.$manual_outro;

			$this->writer
				->writef(' Generating PDF manual... ');

			// generate pdf docs
			\file_put_contents(\app\Env::key('sys.path').'manual.pdf', \app\PDF::fromhtml($manual_out));

			$this->writer
				->writef('done.')
				->eol();
		}
	}

	/**
	 * @return string html contents of the specified component
	 */
	protected function parse_component(array $component, $namespace = null, $title = null)
	{
		if (isset($component['namespace']))
		{
			$namespace = $component['namespace'];
		}
		else # no component namespace
		{
			if ($namespace === null)
			{
				return "<p>Failed to compute component, missing namespace.</p>\n\n";
			}
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
				$html = "<p>Failed to compute component, unknown type: {$component['type']}</p>\n\n";
			}
		}
		else # file not found
		{
			$html = "<p>Failed to compute component, missing file: $file</p>\n\n";
		}

		$html .= "<br class=\"pagebreak\"/>\n\n";

		return $html;
	}

} # class
