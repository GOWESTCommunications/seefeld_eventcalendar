<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'GOWEST.' . $_EXTKEY,
	'EventsSeefeld',
	'RSS Seefeld Events Calendar'
);

$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_eventcalendar'] = 'layout,select_key,pages,recursive';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_eventcalendar'] = 'pi_flexform';
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($_EXTKEY . '_eventcalendar', 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForms/Settings.xml');


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'EventsSeefeld');