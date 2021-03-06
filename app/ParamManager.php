<?php
/**
 * User: kasatkin.o.v
 * Date: 03.06.2015
 * Time: 19:24
 */

class ParamManager {
    public function getParam($paramName)
    {
        $application = Application::getInstance();
        $db = $application['db'];
        /**
         * @var \Doctrine\DBAL\Connection $db
         */

        $sql = <<<q
select value from params where `name`=?
q;
        return $db->fetchColumn($sql, array($paramName));
    }
} 