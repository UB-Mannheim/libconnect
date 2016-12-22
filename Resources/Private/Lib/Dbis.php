<?php

/***************************************************************
* Copyright notice
*
* (c) 2009 by Avonis - New Media Agency
*
* All rights reserved
*
* This script is part of the EZB/DBIS-Extention project. The EZB/DBIS-Extention project
* is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
*
* Project sponsored by:
*  Avonis - New Media Agency - http://www.avonis.com/
***************************************************************/

/**
 *
 * @package libconnect
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 * Doku:
 * @author niklas guenther
 * @author Torsten Witt
 *
 */
 
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('libconnect') . 'Resources/Private/Lib/Xmlpageconnection.php');
require_once(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('libconnect') . 'Resources/Private/Lib/Httppageconnection.php');

class Tx_Libconnect_Resources_Private_Lib_Dbis {

    //such meta
    private $fachgebiet;
    private $colors = 255;
    private $ocolors = 40;
    private $lett = 'f';
    private $fachliste_url =  'http://dbis.uni-regensburg.de/dbinfo/fachliste.php?xmloutput=1&bib_id=';
    private $dbliste_url = 'http://dbis.uni-regensburg.de/dbinfo/dbliste.php?xmloutput=1&bib_id=';
    private $db_detail_url = 'http://dbis.uni-regensburg.de/dbinfo/detail.php?xmloutput=1&bib_id=';
    private $db_detail_suche_url = 'http://dbis.uni-regensburg.de/dbinfo/suche.php?xmloutput=1&bib_id=';

    public $all;
    public $top_five_dbs;
    // typoscript Konfigurationsvariablen
    private $bibID;
    private $licenceForbid = array();
    // sources
    private $XMLPageConnection;
    private $HttpPageConnection;

    /**
     * construtor
     *
     */
    public function __construct() {
        $this->XMLPageConnection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_libconnect_resources_private_lib_xmlpageconnection');
        $this->HttpPageConnection = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tx_libconnect_resources_private_lib_httppageconnection');
        $this->setBibID();
    }

    /**
     * sets ID of the library
     *
     */
    public function setBibID() {
        $this->bibID = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_libconnect.']['dbisbibid'];
    }
    
    /**
     * Set id Fachgebiet
     *
     * @param integer $fachgebiet
     */
    public function setGebiet($fachgebiet) {
        $this->fachgebiet = (int) $fachgebiet;
    }

    /**
     * Set the letter string
     *
     * @param string $lett
     */
    public function setLett($lett) {
        $this->lett = $lett;
    }

    /**
     * Set the int value colors
     *
     * @param integer $colors
     */
    public function setColors($colors) {
        $this->colors = $colors;
    }

    /**
     * Set the int value ocolors
     *
     * @param integer $ocolors
     */
    public function setOcolors($ocolors) {
        $this->ocolors = $ocolors;
    }

    /**
     * Set the array with unused licences
     *
     */
    public function setLicenceForbid() {
        $this->licenceForbid = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_libconnect.']['settings.']['dbislicenceforbid.'];
    }

    /**
     * Alle Fachbereiche auslesen
     *
     * @return array()
     */
    public function getFachliste() {

        $xml_categories = $this->getRequestFachliste('');
        $categories = array();

        if (isset($xml_categories->list_subjects_collections->list_subjects_collections_item)) {
            foreach ($xml_categories->list_subjects_collections->list_subjects_collections_item AS $key => $value) {
                $categories[(string) $value['notation']] = array(
                    'title' => (string) $value, 
                    'id' => (string) $value['notation'], 
                    'count' => (int) (string) $value['number'], 
                    'lett' => (string) $value['lett']);
            }
        }

        return $categories;
    }

    /**
     * Return the list Fachgebiet
     *
     * @param integer $fachgebiet
     * @param string $sort
     *
     * @return array
     */
    public function getDbliste($fachgebiet, $sort = 'type') {

        $sortlist = array();
        $url =  $this->dbliste_url. $this->bibID .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&sort='. $sort . '&';
        $headline = '';

        //UBMA: Workaround um alle Buchstaben in der alphabetischen Übersicht zu haben
        if ($fachgebiet == 'all') {
            //Information einholen, welche Charblocks es gibt
            $url =  $this->dbliste_url. $this->bibID .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&sort='. $sort . '&' . 'lett=a';
            $xml_fachgebiet_db = $this->XMLPageConnection->getDataFromXMLPage($url);

            /* UBMA Debugging
            var_dump($xml_fachgebiet_db);
            foreach($xml_fachgebiet_db->list_dbs->alphabetical_list->block_of_chars as $charBlock) {
                echo " chars: ";
               foreach ($charBlock->char as $char) {
                echo $char;
                }
                echo " fc: ", $charBlock->attributes()->fc;
                echo " lc: ", $charBlock->attributes()->lc;
                echo " ### ";
            }*/

            
            //echo \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($xml_fachgebiet_db,'xml antwort auf neue url anfrage');
            //echo \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($xml_fachgebiet_db->list_dbs->alphabetical_list,'summe der charblocks?');
            $list = array(
                'top' => array(),
                'groups' => array(),
                'access_infos' => array()
            );

            if (is_object($xml_fachgebiet_db->list_dbs->alphabetical_list)) {
                foreach ($xml_fachgebiet_db->list_dbs->alphabetical_list->block_of_chars as $charBlock) {
                    //Anfrage URL um FC und LC ergänzen
                    $url =  $this->dbliste_url. $this->bibID .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&sort='. $sort . '&' . 'lett=a' . '&lc=' . $charBlock->attributes()->lc . '&fc=' . $charBlock->attributes()->fc;
                    //echo \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($url,'url mit neuen fc und lc');
                    //Daten von DBIS abfragen
                    $xml_fachgebiet_db = $this->XMLPageConnection->getDataFromXMLPage($url);
                    //echo \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($xml_fachgebiet_db,'xml antwort auf neue url anfrage');
                    
                    if (isset($xml_fachgebiet_db->list_dbs->db_access_infos->db_access_info)) {
                        foreach ($xml_fachgebiet_db->list_dbs->db_access_infos->db_access_info as $value) {
                            //Für jede Datenbank innerhalb des Charblocks Eintrag im Gesamtlistenarray erstellen
                            $id = (string) $value->attributes()->access_id;
                            $list['access_infos'][$id] = array(
                                'id' => $id,
                                'title' => (string) $value->db_access,
                                'description' => (string) $value->db_access_short_text,
                                'dbs' => array()
                            );
                        }
                    }
                    if (isset($xml_fachgebiet_db->list_dbs->dbs)) {
                        foreach ($xml_fachgebiet_db->list_dbs->dbs as $value) {
                            //Für jeden Char! eine Gruppe mit den einzelnen Datenbanken erstellen 
                            $id = (string) $value->attributes()->char;
                            $title = (string) $value->attributes()->char;
                            $list['groups'][$id] = array(
                                'id' => $id,
                                'title' => $title,
                                'dbs' => array()
                            );
                        }
                    }
                    if (isset($xml_fachgebiet_db->list_dbs->dbs)) {
                    foreach ($xml_fachgebiet_db->list_dbs->dbs as $dbs) {
            
                        foreach ($dbs->db as $value) {
                            $db = array(
                                'id' => (int) $value['title_id'],
                                'title' => (string) $value,
                                'access_ref' => (string) $value['access_ref'],
                                'access' => $list['access_infos'][(string) $value['access_ref']]['title'],
                                'db_type_refs' => (string) $value['db_type_refs'],
                                'top_db' => (int) $value['top_db'],
                                'link' => $this->db_detail_url . $this->bibID .'&lett='. $this->lett .'&titel_id='. $value['title_id'],
                            );
         
                        $list['groups'][(string) $dbs->attributes()->char]['dbs'][] = $db;
                        $sortlist[$db['access']] = $db['access_ref'];
                        }
                    }
                    }
                    
                    if (!empty($sortlist) && ($sort == 'access')) {
                        natsort($sortlist);
                        foreach ($sortlist as $value => $key) {
                            $list['alphasort'][$value] = $key;
                        }
                }
           }
           //echo \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($list,'List nach foreach schleife');
           $list['alphNavList'] = TRUE;
           $list['alphasort'] = $sortlist;
            return array('groups' => $access_infos, 'list' => $list, 'headline' =>$headline);
            exit();
            } 
        } else {

            if (is_numeric($fachgebiet)) {
                // notation ist eine id => dbis sammlung
                $url .= 'lett=f&gebiete=' . $fachgebiet;
            } else {
                // notation ist ein Zeichen => eigene sammlung
                $url .= 'lett=c&collid=' . $fachgebiet;
            }
        }
        //EOF workaround for alphabetical listing
        $xml_fachgebiet_db = $this->XMLPageConnection->getDataFromXMLPage($url);

        $list = array(
            'top' => array(),
            'groups' => array(),
            'access_infos' => array()
        );

        // Name der eigenen Sammlung
        if (isset($xml_fachgebiet_db->headline)){
            $headline = $xml_fachgebiet_db->headline;
        }
        /*
        //BOF workaround for alphabetical listing
        if (is_object($xml_fachgebiet_db->list_dbs->alphabetical_list)) {
            $alphabeticalNavList = array();
            if(isset($xml_fachgebiet_db->list_dbs->alphabetical_list->block_of_chars)){
                foreach ($xml_fachgebiet_db->list_dbs->alphabetical_list->block_of_chars as $charBlock) {
                    $tmpCharArray = array();
                    foreach ($charBlock->char as $char) {
                        $tmpCharArray[] = $char;
                    }
                    $alphabeticalNavList[] = array(
                        'chars' => $tmpCharArray,
                        'fc' => $charBlock->attributes()->fc,
                        'lc' => $charBlock->attributes()->lc,
                        //check current view for which char is shown
                        'current' => ($charBlock->attributes()->lc == $tmpParams['lc'] || $charBlock->attributes()->fc == $tmpParams['lc'] ? TRUE : FALSE)
                    );
                }
            }
            //check if a current view got set
            // if not, set the first charBlock as current view
            $currentChk = FALSE;
            foreach ($alphabeticalNavList as $value) {
                if ($value['current']) {
                    $currentChk = TRUE;
                    break;
                }
            }
            if (!$currentChk && count($alphabeticalNavList)) {
                $alphabeticalNavList[0]['current'] = TRUE;
            }
        }
        $list['alphNavList'] = (isset($alphabeticalNavList) && count($alphabeticalNavList) ? $alphabeticalNavList : FALSE);
        //EOF workaround for alphabetical listing
        */
        if (isset($xml_fachgebiet_db->list_dbs->db_access_infos->db_access_info)) {
            foreach ($xml_fachgebiet_db->list_dbs->db_access_infos->db_access_info as $value) {
                $id = (string) $value->attributes()->access_id;
                $list['access_infos'][$id] = array(
                    'id' => $id,
                    'title' => (string) $value->db_access,
                    'description' => (string) $value->db_access_short_text,
                    'dbs' => array()
                );
            }
        }

        if ($sort == 'access') {
            $list['groups'] = &$list['access_infos'];
            //BOF workaround for alphabetical listing			
        } elseif ($fachgebiet == 'all') {
          /*  if (isset($xml_fachgebiet_db->list_dbs->dbs)) {
                foreach ($xml_fachgebiet_db->list_dbs->dbs as $value) {
                    $id = (string) $value->attributes()->char;
                    $title = (string) $value->attributes()->char;
                    $list['groups'][$id] = array(
                        'id' => $id,
                        'title' => $title,
                        'dbs' => array()
                    );
                }
            }*/
            //EOF workaround for alphabetical listing
        } else {
            if (isset($xml_fachgebiet_db->list_dbs->db_type_infos->db_type_info)) {
                foreach ($xml_fachgebiet_db->list_dbs->db_type_infos->db_type_info as $value) {
                    $id = (string) $value->attributes()->db_type_id;
                    $title = (string) $value->db_type;
                    $list['groups'][$id] = array(
                        'id' => $id,
                        'title' => $title,
                        'dbs' => array()
                    );
                }
            }
        }

        if (isset($xml_fachgebiet_db->list_dbs->dbs)) {
            foreach ($xml_fachgebiet_db->list_dbs->dbs as $dbs) {
    
                foreach ($dbs->db as $value) {
                    $db = array(
                        'id' => (int) $value['title_id'],
                        'title' => (string) $value,
                        'access_ref' => (string) $value['access_ref'],
                        'access' => $list['access_infos'][(string) $value['access_ref']]['title'],
                        'db_type_refs' => (string) $value['db_type_refs'],
                        'top_db' => (int) $value['top_db'],
                        'link' => $this->db_detail_url . $this->bibID .'&lett='. $this->lett .'&titel_id='. $value['title_id'],
                    );
        
                    if ($db['top_db']) {
                        $list['top'][] = $db;
                        //BOF workaround for alphabetical listing			
                    } elseif ($fachgebiet == 'all') {
                       /* $list['groups'][(string) $dbs->attributes()->char]['dbs'][] = $db;
                        $sortlist[$db['access']] = $db['access_ref'];
                        */
                        //EOF workaround for alphabetical listing
                    } else {
                        if ($sort == 'alph') {
                            $list['groups']['Treffer']['dbs'][] = $db;
                            $sortlist['Treffer'] = $db['Treffer'];
                        } elseif ($sort == 'access') {
                            $list['access_infos'][$db['access_ref']]['dbs'][] = $db;
                            $sortlist[$db['access']] = $db['access_ref'];
                        } else {
                            foreach (explode(' ', $db['db_type_refs']) as $ref) {
                                $list['groups'][$ref]['dbs'][] = $db;
                                $sortlist[$db['access']] = $db['access_ref'];
                            }
                        }
                    }
                }
            }
        }


        if (!empty($sortlist) && ($sort == 'access')) {
            natsort($sortlist);
            foreach ($sortlist as $value => $key) {
                $list['alphasort'][$value] = $key;
            }
        }

        $list['alphasort'] = $sortlist;

        return array('groups' => $access_infos, 'list' => $list, 'headline' =>$headline);
    }

    /**
     * liefert Detailinformationen
     *
     * @param integer Id der DB
     *
     * @return array
     */
    public function getDbDetails($db_id) {

        $details = array();
        $url =  $this->db_detail_url. $this->bibID .'&lett='. $this->lett .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&titel_id='. $db_id;
        $xml_db_details = $this->XMLPageConnection->getDataFromXMLPage($url);

        //@todo Fehlerbehandlung
        if (!isset($xml_db_details->details)) {
            return FALSE;
        }

        if (count($xml_db_details->details->children()) > 0){

            foreach ($xml_db_details->details->children() as $key => $value) {

                if ($key == 'titles') {
                    $details['else_titles'] = array();
                    foreach ($value->children() as $key2 => $value2) {
                        if (((string) $value2->attributes()->main) == 'Y') {
                            $details['title'] = (string) $value2;
                        } else {
                            $details['else_titles'][] = (string) $value2;
                        }
                    }
                } else if ($key == 'db_access_info') {
                    $details['access_id'] = (string) $value->attributes()->access_id;
                    $details['access_icon'] = (string) $value->attributes()->access_icon;
                    $details['db_access'] = (string) $value->db_access;
                    $details['db_access_short_text'] = (string) $value->db_access_short_text;
                } else if ($key == 'accesses') {

                    //Zugänge
                    foreach ($value->access as $access) {

                        $main = (string) $access->attributes()->main;
                        $type = (string) $access->attributes()->type;
                        $href = (string) $access->attributes()->href;
                        if ($main == 'Y') {
                            $details['access'] = array(
                                'type' => $type,
                                'href' => $href
                            );
                        } else {
                            $details['access_lic'][$type][] = array(
                                'name' => (string) $access,
                                'href' => $href
                            );
                        }
                    }
                } else if ($key == 'subjects') {
                    foreach ($value->children() as $key2 => $value2) {
                        $details['subjects'][] = (string) $value2;
                    }
                } else if ($key == 'keywords') {
                    foreach ($value->children() as $key2 => $value2) {
                        $details['keywords'][] = (string) $value2;
                    }
                    $details['keywords_join'] = join(', ', $details['keywords']);
                } else if ($key == 'db_type_infos') {
                    $i=0;
                    foreach ($value->children() as $value2) {
                        //(string)$value->attributes()->db_type_id
                        //@todo da muss noch iergendwie die id rein und nicht nur i
                        $details['db_type_infos'][$i]['type'] = (string) $value2->db_type;
                        $details['db_type_infos'][$i]['long_text'] = (string) $value2->db_type_long_text;
                        $i++;
                    }
                    //$details['db_type_infos_join'] = join(', ', $details['db_type_infos']);
                } else if ($key == 'hints') {
                    //warpto-Link ist relativ, daher muss er vervollstaendigt werden
                    $hint = preg_replace('/warpto/', 'http://rzblx10.uni-regensburg.de/dbinfo/warpto',  (string) $value);
                    $details['hints'] =  $hint;
                } else if ($key == 'instruction') {
                    $details['instruction'] = (string) $value;
                }
                // copy all left values into array
                else {
                    $details[(string)$key] = (string) $value;
                }
            }
        }

        //$url = 'http://rzblx10.uni-regensburg.de/dbinfo/detail.php?bib_id='.$this->bibID.'&colors=&ocolors=&lett=fs&tid=0&titel_id='. $db_id;

        //$details['more_internet_accesses'] = $this->HttpPageConnection->getDataFromHttpPage($url);

        return $details;
    }

    /**
     * Detailsuche Formular ausgeben
     *
     * @return array
     */
    public function detailSucheFormFelder() {

        $url =  $this->db_detail_suche_url. $this->bibID .'&colors='. $this->colors .'&ocolors='.$this->ocolors;
        $xml_such_form = $this->XMLPageConnection->getDataFromXMLPage($url);

        //Zugaenge werden ermittelt
        if (isset($xml_such_form->dbis_search->option_list)) {
            foreach ($xml_such_form->dbis_search->option_list AS $key => $value) {
                foreach ($value->option AS $key2 => $value2) {
                    $form[(string) $value->attributes()->name][(string) $value2->attributes()->value] = (string) $value2;
                }
            }
        }

        //zu sperrende Zugaenge auslesen und aus Gesamtmenge entfernen
        $this->setLicenceForbid();
        if((!empty($this->licenceForbid)) && ($this->licenceForbid!= FALSE)){
            foreach($this->licenceForbid as $key =>$licence){
                unset($form['zugaenge'][$key]);
            }
        }

        /*$zugaenge = array(
            1000 => $form[zugaenge][1000],
            0 => $form[zugaenge][0],
            1 => $form[zugaenge][1],
            7 => $form[zugaenge][7],
            5 => $form[zugaenge][5],
            6 => $form[zugaenge][6],
            2 => $form[zugaenge][2],
            3 => $form[zugaenge][3],
            4 => $form[zugaenge][4],
            500 => $form[zugaenge][500],
            300 => $form[zugaenge][300],
        );

        $form[zugaenge] = $zugaenge;*/

        return $form;
    }

    /**
     * Suchurl erzeugen
     *
     * @param array $searchVars
     * @param string $lett
     *
     * @retun string
     */
    private function createSearchUrl($searchVars, $lett = 'k') {

        $searchUrl = $this->dbliste_url . $this->bibID .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&lett='. $lett;

        foreach ($searchVars as $var => $values) {
            if (!is_array($values)) {
                //falls jemand kein utf-8 verwendet
                if((mb_strtolower($GLOBALS['TSFE']->metaCharset)) == 'utf-8'){
                    $values = utf8_decode($values);
                }
                $searchUrl .= '&'.$var.'=' . urlencode($values);
            } else {
                foreach ($values as $value) {
                    if((mb_strtolower($GLOBALS['TSFE']->metaCharset)) == 'utf-8'){
                        $value = utf8_decode($value);
                    }
                    $searchUrl .= '&' . $var . '[]=' . urlencode($value);
                }
            }
        }

        return $searchUrl;
    }

    /**
     * Suche durchführen
     *
     * @param string $term
     * @param mixed $searchVars
     * @param string $lett
     *
     * @return array
     */
    public function search($term, $searchVars = FALSE, $lett = 'fs') {
        //falls jemand kein utf-8 verwendet
        if((mb_strtolower($GLOBALS['TSFE']->metaCharset)) == 'utf-8'){
            $term = utf8_decode($term);
        }

        // encode term
        $term = urlencode($term);

        $searchUrl = '';
        if (!$searchVars) {
            $searchUrl = $this->dbliste_url . $this->bibID .'&colors='. $this->colors .'&ocolors='. $this->ocolors .'&lett='. $lett .'&Suchwort='. $term;
        } else {
            $searchUrl = $this->createSearchUrl($searchVars);
        }

        $request = $this->XMLPageConnection->getDataFromXMLPage($searchUrl);

        if (!$request) {
            return FALSE;
        }

        $list = array(
            'top' => array(),
            //'groups' => array(),
            //'access_infos' => array(),
            'page_vars' => array(),
            'values' => array(),
            'db_count' => 0
        );
        $dbsid = array();

        foreach ($request->page_vars->children() AS $key => $value) {
            $page_vars[$key] = (string) $value;
        }

        if (isset($request->list_dbs->db_access_infos->db_access_info)) {
            foreach ($request->list_dbs->db_access_infos->db_access_info as $value) {
                $id = (string) $value->attributes()->access_id;
                $list['access_infos'][$id] = array(
                    'id' => $id,
                    'title' => (string) $value->db_access,
                    'description' => (string) $value->db_access_short_text,
                );
            }
        }

        if (isset($request->list_dbs->db_type_infos->db_type_info)) {
            foreach ($request->list_dbs->db_type_infos->db_type_info as $value) {
                $id = (string) $value->attributes()->db_type_id;
                $list['groups'][$id] = array(
                    'id' => $id,
                    'title' => (string) $value->db_type,
                    'dbs' => array()
                );
            }
        }

        if (isset($request->list_dbs->dbs)) {

            foreach ($request->list_dbs->dbs as $dbs) {
                if(isset($dbs->attributes()->db_count)){
                    $list['db_count'] = (int) $dbs->attributes()->db_count;
                }

                foreach ($dbs->db as $value) {

                    $db = array(
                        'id' => (int) $value['title_id'],
                        'title' => (string) $value,
                        'access_ref' => (string) $value['access_ref'],
                        'access' => $list['access_infos'][(string) $value['access_ref']]['title'],
                        'db_type_refs' => (string) $value['db_type_refs'],
                        'top_db' => (int) $value['top_db'],
                        'link' => $this->db_detail_url . $this->bibID .'&lett='. $this->lett .'&titel_id='. $value['title_id'],
                    );

                    if ($db['top_db']) {
                        $list['top'][] = $db;
                    }
                    $list['values'][$db['title'] . '_' . $db['id']] = $db;
                    $sort[$db['title'] . '_' . $db['id']] = (string) $db['title'];

                    /* foreach(explode(' ', $db['db_type_refs']) as $ref) {
                        $list['groups'][$ref]['dbs'][] = $db;
                    } */
                }
            }
        }

        $list['searchDescription'] = array();
        if (isset($request->search_desc->search_desc_item)) {
            foreach ($request->search_desc->search_desc_item as $searchDesc) {
                $list['searchDescription'][] = (string)$searchDesc;
            }
        }

        if (isset($request->error)) {
            $list['error'] = (string) $request->error;
        }

        return array('page_vars' => $page_vars, /* 'groups' => $access_infos, */ 'list' => $list);
    }

    /**
     *
     * helper function get fachliste
     *
     * @param string $request
     * @return array
     */
    public function getRequestFachliste($request) {
        $url = $this->fachliste_url . $this->bibID . '&' . $request;
        $xml_request = $this->XMLPageConnection->getDataFromXMLPage($url);

        return $xml_request;
    }

    /**
     * helper function get db liste
     *
     * @param string $request
     *
     * @return array
     */
    public function getRequestDbliste($request) {
        $url = $this->dbliste_url . $this->bibID . '&' . $request;
        $xml_request = $this->XMLPageConnection->getDataFromXMLPage($url);

        return $xml_request;
    }
}
?>