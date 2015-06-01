<?
class ActsModel
{
    public function printAct($application , $id)
    {
        $act_data = $this->getActData($application, $id);
        if(empty($act_data))
        {
           throw new Exception('act '.$id.' not found');
        }
        $file_name = Application::$docsPath.DIRECTORY_SEPARATOR.'act1.xls';
        $template = Application::$templatePath.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'act.xls';
        //throw new Exception($template);
        $engine = new TemplateEngine($act_data, $template, $file_name);
        $engine->compile();
        return $file_name;
    }

    private function getActData($application, $id)
    {
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
}