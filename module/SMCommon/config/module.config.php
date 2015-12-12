<?php 

return array(
	
	// The format used by the formatDate ViewHelper
	'formatdate' => array(
		/**
		 * This specifies the format used by FormatDate to format dates.
		 */
		'date-format' => 'd.m.Y',
			
		/**
		 * This specifies the format used by FormatDate to format a time.
		 */
		'time-format' => 'G:i'
	),

	'mailer' => [
		'api-host' => 'https://mailer.fidelisfactory.ch/api/v1/',
	],
	
	// Doctrine configuration for added types and filters
	'doctrine' => array(
		'configuration' => array(
			'orm_default' => array(
				'types' => array(
					'utcdatetime' => 'SMCommon\Doctrine\DBAL\Types\UTCDateTimeType',
				),
				'filters' => array(
					'soft_delete' => 'SMCommon\Doctrine\Filter\DeleteFilter',
				),
			)
		)
	)
);