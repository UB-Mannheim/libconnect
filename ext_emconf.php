<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "libconnect".
 *
 * Auto generated 21-11-2016 08:43
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

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
  'version' => '5.0.3',
  'constraints' => 
  array (
    'depends' => 
    array (
      'typo3' => '6.0.0-7.6.99',
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
  '_md5_values_when_last_written' => 'a:114:{s:9:"ChangeLog";s:4:"3406";s:21:"ext_conf_template.txt";s:4:"3fbc";s:12:"ext_icon.gif";s:4:"b57c";s:17:"ext_localconf.php";s:4:"7c9c";s:14:"ext_tables.php";s:4:"1845";s:14:"ext_tables.sql";s:4:"81d1";s:25:"ext_tables_static+adt.sql";s:4:"a98f";s:12:"wiz_icon.gif";s:4:"38d5";s:37:"Classes/Controller/DbisController.php";s:4:"c9d7";s:36:"Classes/Controller/EzbController.php";s:4:"15f4";s:40:"Classes/Controller/SubjectController.php";s:4:"7fcc";s:32:"Classes/Domain/Model/Subject.php";s:4:"0a4b";s:44:"Classes/Domain/Repository/DbisRepository.php";s:4:"4656";s:43:"Classes/Domain/Repository/EzbRepository.php";s:4:"7a1b";s:47:"Classes/Domain/Repository/SubjectRepository.php";s:4:"d244";s:51:"Classes/UserFunctions/IsfirstPlugInUserFunction.php";s:4:"fcd1";s:72:"Classes/UserFunctions/user_libconnect_hasSelectedPluginForCSSInclude.php";s:4:"ebe9";s:41:"Classes/ViewHelpers/CompareViewHelper.php";s:4:"70bf";s:40:"Classes/ViewHelpers/IntvalViewHelper.php";s:4:"4748";s:41:"Classes/ViewHelpers/IsArrayViewHelper.php";s:4:"52ed";s:40:"Classes/ViewHelpers/StrlenViewHelper.php";s:4:"23e8";s:44:"Classes/ViewHelpers/StrreplaceViewHelper.php";s:4:"f245";s:46:"Classes/ViewHelpers/TrimedstrlenViewHelper.php";s:4:"219e";s:42:"Classes/ViewHelpers/TruncateViewHelper.php";s:4:"bdb2";s:43:"Classes/ViewHelpers/UrldecodeViewHelper.php";s:4:"049f";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"aa96";s:41:"Configuration/FlexForms/dbis_flexform.xml";s:4:"bec3";s:40:"Configuration/FlexForms/ezb_flexform.xml";s:4:"5af7";s:26:"Configuration/TCA/Dbis.php";s:4:"09d3";s:25:"Configuration/TCA/Ezb.php";s:4:"76f5";s:29:"Configuration/TCA/Subject.php";s:4:"a45b";s:38:"Configuration/TypoScript/constants.txt";s:4:"2965";s:34:"Configuration/TypoScript/setup.txt";s:4:"1fdd";s:58:"Configuration/Wizicon/class.tx_libconnect_dbis_wizicon.php";s:4:"943c";s:57:"Configuration/Wizicon/class.tx_libconnect_ezb_wizicon.php";s:4:"1d49";s:40:"Resources/Private/Language/locallang.xml";s:4:"301a";s:76:"Resources/Private/Language/locallang_csh_tx_libconnect_domain_model_dbis.xml";s:4:"ebeb";s:75:"Resources/Private/Language/locallang_csh_tx_libconnect_domain_model_ezb.xml";s:4:"db02";s:79:"Resources/Private/Language/locallang_csh_tx_libconnect_domain_model_subject.xml";s:4:"b005";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"ea05";s:38:"Resources/Private/Layouts/Default.html";s:4:"5e54";s:36:"Resources/Private/Lib/class_DBIS.php";s:4:"17d0";s:35:"Resources/Private/Lib/class_EZB.php";s:4:"98a4";s:49:"Resources/Private/Lib/class_XMLPageConnection.php";s:4:"c14e";s:35:"Resources/Private/Lib/class_ZDB.php";s:4:"5347";s:54:"Resources/Private/Oldtemplates/Dbis/DisplayDetail.html";s:4:"dfe9";s:53:"Resources/Private/Oldtemplates/Dbis/DisplayError.html";s:4:"d41d";s:52:"Resources/Private/Oldtemplates/Dbis/DisplayForm.html";s:4:"139d";s:52:"Resources/Private/Oldtemplates/Dbis/DisplayList.html";s:4:"e636";s:56:"Resources/Private/Oldtemplates/Dbis/DisplayMiniForm.html";s:4:"71a7";s:51:"Resources/Private/Oldtemplates/Dbis/DisplayNew.html";s:4:"d406";s:56:"Resources/Private/Oldtemplates/Dbis/DisplayOverview.html";s:4:"964e";s:54:"Resources/Private/Oldtemplates/Dbis/DisplaySearch.html";s:4:"7f4d";s:51:"Resources/Private/Oldtemplates/Dbis/DisplayTop.html";s:4:"7c14";s:53:"Resources/Private/Oldtemplates/Ezb/DisplayDetail.html";s:4:"ba45";s:51:"Resources/Private/Oldtemplates/Ezb/DisplayForm.html";s:4:"6d33";s:51:"Resources/Private/Oldtemplates/Ezb/DisplayList.html";s:4:"4940";s:55:"Resources/Private/Oldtemplates/Ezb/DisplayMiniForm.html";s:4:"471a";s:50:"Resources/Private/Oldtemplates/Ezb/DisplayNew.html";s:4:"cb8e";s:55:"Resources/Private/Oldtemplates/Ezb/DisplayOverview.html";s:4:"ff04";s:53:"Resources/Private/Oldtemplates/Ezb/DisplaySearch.html";s:4:"370c";s:57:"Resources/Private/Oldtemplates/Subject/DisplayDetail.html";s:4:"33cb";s:48:"Resources/Private/Oldtemplates/Subject/Show.html";s:4:"498f";s:47:"Resources/Private/Partials/Dbis/Properties.html";s:4:"9d7b";s:46:"Resources/Private/Partials/Ezb/Properties.html";s:4:"9d7b";s:51:"Resources/Private/Templates/Dbis/DisplayDetail.html";s:4:"61bb";s:50:"Resources/Private/Templates/Dbis/DisplayError.html";s:4:"d41d";s:49:"Resources/Private/Templates/Dbis/DisplayForm.html";s:4:"c2a7";s:49:"Resources/Private/Templates/Dbis/DisplayList.html";s:4:"7308";s:53:"Resources/Private/Templates/Dbis/DisplayMiniForm.html";s:4:"783c";s:48:"Resources/Private/Templates/Dbis/DisplayNew.html";s:4:"da81";s:53:"Resources/Private/Templates/Dbis/DisplayOverview.html";s:4:"0059";s:51:"Resources/Private/Templates/Dbis/DisplaySearch.html";s:4:"f503";s:48:"Resources/Private/Templates/Dbis/DisplayTop.html";s:4:"f83d";s:50:"Resources/Private/Templates/Ezb/DisplayDetail.html";s:4:"699a";s:48:"Resources/Private/Templates/Ezb/DisplayForm.html";s:4:"145f";s:48:"Resources/Private/Templates/Ezb/DisplayList.html";s:4:"d0b3";s:52:"Resources/Private/Templates/Ezb/DisplayMiniForm.html";s:4:"2f9c";s:47:"Resources/Private/Templates/Ezb/DisplayNew.html";s:4:"adb9";s:52:"Resources/Private/Templates/Ezb/DisplayOverview.html";s:4:"ff04";s:50:"Resources/Private/Templates/Ezb/DisplaySearch.html";s:4:"8273";s:54:"Resources/Private/Templates/Subject/DisplayDetail.html";s:4:"33cb";s:45:"Resources/Private/Templates/Subject/Show.html";s:4:"498f";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:58:"Resources/Public/Icons/tx_libconnect_domain_model_dbis.gif";s:4:"1103";s:57:"Resources/Public/Icons/tx_libconnect_domain_model_ezb.gif";s:4:"1103";s:61:"Resources/Public/Icons/tx_libconnect_domain_model_subject.gif";s:4:"1103";s:42:"Resources/Public/Img/cdrom_dbis_list_3.png";s:4:"54f2";s:36:"Resources/Public/Img/dbis-list_0.png";s:4:"4ca5";s:36:"Resources/Public/Img/dbis-list_1.png";s:4:"fffe";s:36:"Resources/Public/Img/dbis-list_2.png";s:4:"efff";s:36:"Resources/Public/Img/dbis-list_4.png";s:4:"5b3e";s:36:"Resources/Public/Img/dbis-list_5.png";s:4:"1a6a";s:36:"Resources/Public/Img/dbis-list_6.png";s:4:"8ff8";s:36:"Resources/Public/Img/dbis-list_7.png";s:4:"2000";s:29:"Resources/Public/Img/euro.png";s:4:"76f7";s:35:"Resources/Public/Img/ezb-list_1.png";s:4:"4ca5";s:35:"Resources/Public/Img/ezb-list_2.png";s:4:"fffe";s:35:"Resources/Public/Img/ezb-list_4.png";s:4:"5b3e";s:35:"Resources/Public/Img/ezb-list_6.png";s:4:"9401";s:35:"Resources/Public/Img/ezb-list_7.png";s:4:"5b3e";s:33:"Resources/Public/Img/ezb_plus.png";s:4:"b989";s:32:"Resources/Public/Img/germany.png";s:4:"5f7e";s:36:"Resources/Public/Img/zdb-state_2.gif";s:4:"bb80";s:32:"Resources/Public/Styles/dbis.css";s:4:"14d0";s:31:"Resources/Public/Styles/ezb.css";s:4:"710c";s:44:"Tests/Unit/Controller/DbisControllerTest.php";s:4:"bcb2";s:43:"Tests/Unit/Controller/EzbControllerTest.php";s:4:"01b4";s:47:"Tests/Unit/Controller/SubjectControllerTest.php";s:4:"67ba";s:36:"Tests/Unit/Domain/Model/DbisTest.php";s:4:"81eb";s:35:"Tests/Unit/Domain/Model/EzbTest.php";s:4:"1ee5";s:39:"Tests/Unit/Domain/Model/SubjectTest.php";s:4:"d561";s:14:"doc/manual.pdf";s:4:"e3a1";s:14:"doc/manual.sxw";s:4:"2c36";}',
  'comment' => 'Fix for compatibility to TYPO3 7.6',
  'user' => 'subunihhdevteam',
  'clearcacheonload' => true,
);

