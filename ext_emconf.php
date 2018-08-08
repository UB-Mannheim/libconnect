<?php

$EM_CONF[$_EXTKEY] = array (
  'title' => 'libconnect',
  'description' => 'Diese Extension ist von Avonis im Auftrag der Staats- und Universitaetsbibliothek Hamburg entwickelt worden. Mit ihr lassen sich Ergebnisse aus den Informationssystemen EZB und DBIS der Universitaet Regensburg direkt in das TYPO3-System einbinden.',
  'category' => 'plugin',
  'author' => 'Avonis New Media / SUB Hamburg',
  'author_email' => 'torsten.witt@sub.uni-hamburg.de',
  'author_company' => '',
  'state' => 'stable',
  'uploadfolder' => true,
  'createDirs' => '',
  'clearCacheOnLoad' => 0,
  'version' => '5.2.3 UBMA',
  'constraints' =>
  array (
    'depends' =>
    array (
      'typo3' => '6.0.0-8.7.99',
      'cms' => '',
      'extbase' => '',
      'fluid' => '',
    ),
    'conflicts' =>
    array (
    ),
    'suggests' =>
    array (
    ),
  ),
  'autoload' =>
  array (
    'psr-4' =>
    array (
      'Sub\\Libconnect\\' => 'Classes/',
    ),
  ),
  '_md5_values_when_last_written' => 'a:0:{}',
  'comment' => 'EZB: detail view: field "Preistyp Anmerkung" fixed for language english',
  'user' => 'subunihhdevteam',
  'clearcacheonload' => true,
);
