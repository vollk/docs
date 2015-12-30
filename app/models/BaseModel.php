<?php
/**
 * User: kasatkin.o.v
 * Date: 11.06.2015
 * Time: 17:32
 */

abstract class BaseModel {

    /**
     * @var \Doctrine\DBAL\Connection $db
     */
    protected $db;

    /**
     * @param \Doctrine\DBAL\Connection $db
     */
    public function setDb(\Doctrine\DBAL\Connection $db)
    {
        $this->db = $db;
    }

    protected  function getCampData()
    {
        $application = Application::getInstance();
        $paramManager = $application['paramManager'];
        return [
            'org_inn'=>' '.$paramManager->getParam('org_inn'),
            'org_kpp'=>' '.$paramManager->getParam('org_kpp'),
            'org_acc'=>' '.$paramManager->getParam('org_acc'),
            'org_address'=>$paramManager->getParam('org_address'),
            'org_kor_acc'=>' '.$paramManager->getParam('org_kor_acc'),
            'org_bank'=>$paramManager->getParam('org_bank'),
            'org_bik'=>' '.$paramManager->getParam('org_bik'),
            'org_phone'=>' '.$paramManager->getParam('org_phone'),
            'organization'=>$paramManager->getParam('organization'),
        ];
    }

    protected function next_number($table_name, $year)
    {
        $db = $this->db;
        $max = $db->createQueryBuilder()
             ->select('max(number)')
             ->from($table_name)
             ->where('year = ?')
            ->setParameter(0,$year)->execute()->fetch(PDO::FETCH_COLUMN);
        return $max++;
    }

    abstract protected  function createObject(array $params);
    abstract public  function createOne(array $params);
}