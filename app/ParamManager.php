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
        $sql = <<<q
select value from params where `name`=?
q;
        return $db->fetch($sql, array($paramName));
    }
} 