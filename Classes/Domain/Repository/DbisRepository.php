<?php
namespace Sub\Libconnect\Domain\Repository;
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
use TYPO3\CMS\Extbase\Annotation\Inject;

/**
 *
 *
 * @package libconnect
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */

Class DbisRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
    private $dbis_to_t3_subjects = array();
    private $t3_to_dbis_subjects = array();

    /**
     * subjectRepository
     *
     * @var \Sub\Libconnect\Domain\Repository\SubjectRepository
     * @Inject
     * @inject
     */
    protected $subjectRepository;

    /**
     * shows top databases
     * 
     * @param array $config
     *
     * @return array $result
     */
    public function loadTop($config) {
        $this->loadSubjects();

        $subject = $this->t3_to_dbis_subjects[$config['subject']];
        $dbis_id = $subject['dbisid'];

        $dbis = NEW \Sub\Libconnect\Lib\Dbis;
        $result = $dbis->getDbliste($dbis_id);

        //get top dbs
        $result = $this->getListTop($result['list']['top'], $config['detailPid']);

        return $result;
    }

    /**
     * shows a list of databases(for start, search and list of chose subject)
     * 
     * @param integer $subject_id
     * @param array array('subject'=>array(), 'list'=>array())
     *
     * @return array
     */
    public function loadList($subject_id, $config) {
        $this->loadSubjects();

        $dbis = NEW \Sub\Libconnect\Lib\Dbis;

        $accessFilter = FALSE;
        
        if(isset($config['search']['zugaenge']) && (strlen($config['search']['zugaenge']) > 0) ){
            $accessFilter = $config['search']['zugaenge'];
        }

        //list a subject
        if(is_numeric($subject_id)){
            $subject = $this->t3_to_dbis_subjects[$subject_id];

            $dbis_id = $subject['dbisid'];

            $result = $dbis->getDbliste($dbis_id, $config['sort'], $accessFilter);
        }else{//for own collection or all subject
            
            //access sort for all entries in all subjects is not possible
            if($config['sort'] == 'access'){
                $config['sort'] = 'alph';
            }
            
            $result = $dbis->getDbliste($subject_id, $config['sort'], $accessFilter);

            $subject['title'] = $result['headline'];
        }

        //get top dbs
        $result['list']['top'] = $this->getListTop($result['list']['top'], $config['detailPid']);

        foreach(array_keys($result['list']['groups']) as $group) {
            foreach(array_keys($result['list']['groups'][$group]['dbs']) as $db) {
                $result['list']['groups'][$group]['dbs'][$db]['detail_link'] = $GLOBALS['TSFE']->cObj->getTypolink_URL(
                    intval($config['detailPid']),
                    array(
                        'libconnect[titleid]' => $result['list']['groups'][$group]['dbs'][$db]['id'],
                    )
                );
            }
        }

        // sort groups by name
        $alph_sort_groups = array();
        foreach ($result['list']['groups'] as $group) {
            $alph_sort_groups[$group['title']] = $group;
        }
        ksort($alph_sort_groups, SORT_STRING); //added sort-flag SORT_STRING for correct sorting of alphabetical listings
        $result['list']['groups'] = $alph_sort_groups;

        return array('subject' => $subject['title'], 'list' => $result['list']);
    }

    /**
     * show start
     * 
     * @return array $list
     */
    public function loadOverview() {
        $this->loadSubjects();

        $dbis = NEW \Sub\Libconnect\Lib\Dbis;

        $list = $dbis->getFachliste();

        foreach($list as $el) {

            if($el['lett'] != "c"){
                //get id of subject from database
                $subject = $this->dbis_to_t3_subjects[$el['id']];

                $el['link'] = $GLOBALS['TSFE']->cObj->getTypolink_URL($GLOBALS['TSFE']->id, array(
                    'libconnect[subject]' => $subject['uid'])
                );
            }else{
                $el['link'] = $GLOBALS['TSFE']->cObj->getTypolink_URL($GLOBALS['TSFE']->id, array(
                    'libconnect[subject]' => $el['id'])
                );
            }

            $list[$el['id']] = $el;
        }

        return $list;
    }

    /**
     * load subjects from database
     */
    private function loadSubjects() {
        $res = $this->subjectRepository->findAll();

        foreach($res as $row){        

            $this->dbis_to_t3_subjects[$row->getDbisId()]['dbisid'] = $row->getDbisId();
            $this->dbis_to_t3_subjects[$row->getDbisId()]['title'] = $row->getTitle();
            $this->dbis_to_t3_subjects[$row->getDbisId()]['uid'] = $row->getUid();

            $this->t3_to_dbis_subjects[$row->getUid()]['uid'] = $row->getUid();
            $this->t3_to_dbis_subjects[$row->getUid()]['dbisid'] = $row->getDbisId();
            $this->t3_to_dbis_subjects[$row->getUid()]['title'] = $row->getTitle();
        }
    }

    /**
     * show details
     * 
     * @param integer $title_id
     * 
     * @return array $db
     */
    public function loadDetail($title_id) {
        $dbis = NEW \Sub\Libconnect\Lib\Dbis;
        $db = $dbis->getDbDetails($title_id);

        if (! $db ){
            return FALSE;
        }

        return $db;
    }

    /**
     * execute search
     * 
     * @param array $searchVars
     * @param array $config
     * 
     * @return array $result
     */
    public function loadSearch($searchVars, $config) {
        $this->loadSubjects();

        //execute search
        $dbis = NEW \Sub\Libconnect\Lib\Dbis;
        $result = $dbis->search($searchVars);

        //stop if function called from "New" - controller
        if(isset($config['onlyNew'])){
            return $result['list'];
        }

        //get top dbs
        $result['list']['top'] = $this->getListTop($result['list']['top'], $config['detailPid']);

        foreach(array_keys($result['list']['values']) as $value) {
            $result['list']['values'][$value]['detail_link'] = $GLOBALS['TSFE']->cObj->getTypolink_URL(
                intval($config['detailPid']),
                array(
                    'libconnect[titleid]' => $result['list']['values'][$value]['id'],
                )
            );
        }

        return $result['list'];
    }

     /**
     * return miniform
     *
     * @return array $form
     */
    public function loadMiniForm() {
        $dbis = NEW \Sub\Libconnect\Lib\Dbis;
        $form = $dbis->getExtendedForm();

        return $form;
    }

    /**
     * return form for detail search
     *
     * @return array $form
     */
    public function loadForm() {
        $dbis = NEW \Sub\Libconnect\Lib\Dbis;
        $form = $dbis->getExtendedForm();
        
        return $form;
    }

    /**
     * returns a subject
     * 
     * @param integer $subjectId Id of subject
     */
    public function getSubject($subjectId){
        $this->loadSubjects();
        
        return $this->t3_to_dbis_subjects[$subjectId];
    }
    
    /**
     * 
     * @param array $list
     * @param integer $detailPid id of the detail page
     * @return array Description
     */
    private function getListTop($list, $detailPid){

        foreach(array_keys($list) as $db) {
            $list[$db]['detail_link'] = $GLOBALS['TSFE']->cObj->getTypolink_URL(
                intval($detailPid),
                array(
                    'libconnect[titleid]' => $list[$db]['id'],
                )
            );
        }

        return $list;
    }
}
?>
