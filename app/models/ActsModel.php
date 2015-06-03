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

        $file_name = Application::$docsPath.DIRECTORY_SEPARATOR.'act1.xls';
        $template = Application::$templatePath.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'act.xls';
        //throw new Exception($template);
        $engine = new TemplateEngine(array_merge($act_data,$campData), $template, $file_name);
        $engine->compile();
        return $file_name;
    }

    private function getActData($id)
    {
        $application = Application::getInstance();
        $db = $application['db'];
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
        $paramManager = $application['ParamManager'];
        return [
        'org_acc'=>$paramManager->getParam('org_acc')];
    }

}