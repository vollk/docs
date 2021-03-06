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
             ->where("date LIKE ?")
            ->setParameter(0,$year.'%',PDO::PARAM_STR)->execute()->fetch(PDO::FETCH_COLUMN);
        return ++$max;
    }

    abstract protected  function createObject(array $params);

    public function createOne(array $docParams)
    {

        $db = $this->db;
        $db->beginTransaction();
        try{
            $this->createObject($docParams);
            $db->commit();
        }
        catch(Exception $e)
        {
            $db->rollBack();
            throw $e;
        }
        return true;
    }

    public function createYear(array $params)
    {
        $months = DateUtils::getMonths();
        $db = $this->db;

        $desc_base = $params['desc'];

        foreach($months as $i=>$month)
        {
            $year = $params['year'];
            $num_monts = sprintf('%02s',$i+1);
            $b_date = "$year-$num_monts-01";
            $e_date = date("Y-m-t", strtotime($b_date));

            $params['date'] = $e_date;
            $params['desc'] = $desc_base.' за '.$month;
            $db->beginTransaction();
            try
            {
                $this->createObject($params);
                $db->commit();
            }
            catch(Exception $e)
            {
                $db->rollBack();
                throw $e;
            }
        }

        return true;
    }

    public function createQuarter(array $params)
    {
        $quatersBounds = DateUtils::getQuatersBounds();
        $db = $this->db;

        $desc_base = $params['desc'];

        foreach($quatersBounds as $i=>$quaterBounds)
        {
            $year = $params['year'];

            $bBound = $quaterBounds[0];
            $eBound = $quaterBounds[1];
            $b_date = "$year-$bBound";
            $e_date = "$year-$eBound";

            $params['date'] = $e_date;

            $b_date_output = DateUtils::make_date_to_output($b_date);
            $e_date_output = DateUtils::make_date_to_output($e_date);
            $params['desc'] = $desc_base . " за период $b_date_output - $e_date_output";
            $db->beginTransaction();
            try
            {
                $this->createObject($params);
                $db->commit();
            }
            catch(Exception $e)
            {
                $db->rollBack();
                throw $e;
            }
        }

        return true;
    }
}