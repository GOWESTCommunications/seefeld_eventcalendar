<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

/***************
 * PageTS
 */

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'GOWEST.' . $_EXTKEY,
	'EventsSeefeld',
	array(
		'Data' => 'list',
	),
	array(
		'Data' => '',
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'constants',' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/constants.txt">'); 
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript($_EXTKEY,'setup',    ' <INCLUDE_TYPOSCRIPT: source="FILE:EXT:'. $_EXTKEY .'/Configuration/TypoScript/setup.txt">');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['GOWEST\EventsSeefeld\Task\Task'] = array(
    'extension' => $_EXTKEY,
    'title' => 'Eventfeed für Seefeld',
    'description' => 'Connects the formhandler to the CRM of Sandbox8',
 );





