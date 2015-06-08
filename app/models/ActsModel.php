<?
class ActsModel
{
    public function printAct($id)
    {
        $act_data = $this->getActData($id);
        if(empty($act_data))
        {
           throw new Exception('act '.$id.' not found');
        }
        $campData = $this->getCampData($id);
        $this->prepareData($act_data);

        $file_name = Application::$docsPath.DIRECTORY_SEPARATOR.$this->get_file_name($act_data);
        $template = Application::$templatePath.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'act.xls';
        //throw new Exception($template);
        $engine = new TemplateEngine(array_merge(
            $act_data,
            $campData), $template, $file_name);
        $engine->compile();
        return $file_name;
    }

    private function getActData($id)
    {
        $application = Application::getInstance();
        $db = $application['db'];
        /**
         * @var \Doctrine\DBAL\Connection $db
         */
        $sql = <<<q
select a.id,date,number,sum,`desc`,
p.name as partner,p.inn as partner_inn,p.kpp as partner_kpp,p.address as partner_address,
p.acc as partner_acc,p.kor_acc as partner_kor_acc,p.bank as partner_bank,p.bik as partner_bik,
p.phone as partner_phone
 from acts as a
 join partners as p on p.id = a.partner
 where a.id=?
q;
        return $db->fetchAssoc($sql, array((int) $id));
    }

    private function getCampData()
    {
        $application = Application::getInstance();
        $paramManager = $application['paramManager'];
        return [
        'org_inn'=>$paramManager->getParam('org_inn'),
        'org_kpp'=>$paramManager->getParam('org_kpp'),
        'org_acc'=>$paramManager->getParam('org_acc'),
        'org_address'=>$paramManager->getParam('org_address'),
        'org_kor_acc'=>$paramManager->getParam('org_kor_acc'),
        'org_bank'=>$paramManager->getParam('org_bank'),
        'org_bik'=>$paramManager->getParam('org_bik'),
        'org_phone'=>$paramManager->getParam('org_phone'),
        'organization'=>$paramManager->getParam('organization'),
        ];
    }

    private function prepareData(array & $data)
    {
        $data['sum_prop'] = MoneyUtils::num2str($data['sum']);
        $data['sum'] = ' '.$data['sum'];
        $data['date'] = DateUtils::make_date_to_output($data['date']);
    }
    private function get_file_name($act_data)
    {
        return 'act '.$act_data['number'].'.xls';
    }
}