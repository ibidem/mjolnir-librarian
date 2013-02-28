<?php namespace mjolnir\theme;

return array
	(
		'mjolnir\librarian' => array
			(
				 'extension=php_gd2 (or compatible)' => function ()
					{
						if (\extension_loaded('gd'))
						{
							return 'available';
						}

						return 'failed';
					}
			),
	);
