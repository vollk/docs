<?php
/**
 * User: kasatkin.o.v
 * Date: 11.06.2015
 * Time: 17:34
 */

class BillsModel extends BaseModel{

    private $table = 'bills';

    public function printBill($id)
    {
        $bill_data = $this->getBillData($id);
        if(empty($bill_data))
        {
            throw new Exception('bill '.$id.' not found');
        }
        $campData = $this->getCampData($id);
        $this->prepareData($bill_data);

        $file_name = Application::$docsPath.DIRECTORY_SEPARATOR.$this->get_file_name($bill_data);
        $template = Application::$templatePath.DIRECTORY_SEPARATOR.'default'.DIRECTORY_SEPARATOR.'bill.xls';

        $engine = new TemplateEngine(array_merge(
            $bill_data,
            $campData), $template, $file_name);
        $engine->compile();
        return $file_name;
    }

    /**
     * @param $id
     * @return array
     */
    private function getBillData($id)
    {
        $db = $this->db;
        /**
         * @var \Doctrine\DBAL\Connection $db
         */
        $sql = <<<q
select a.id,date,number,sum,`desc`,
p.name as partner,p.inn as partner_inn,p.kpp as partner_kpp,p.address as partner_address,
p.acc as partner_acc,p.kor_acc as partner_kor_acc,p.bank as partner_bank,p.bik as partner_bik,
p.phone as partner_phone
 from {$this->table} as a
 join partners as p on p.id = a.partner
 where a.id=?
q;
        return $db->fetchAssoc($sql, array((int) $id));
    }

    private function prepareData(array & $data)
    {
        $data['sum_prop'] = MoneyUtils::num2str($data['sum']);
        $data['sum'] = ' '.$data['sum'];
        $data['date'] = DateUtils::make_date_to_output($data['date']);
    }
    private function get_file_name(array $bill_data)
    {
        return 'bill_'.$bill_data['number'].'.xls';
    }

    protected  function createObject(array $actParams)
    {
        if(!$actParams['partner']) throw new Exception('partner not set');
        if(!$actParams['sum']) throw new Exception('sum not set');
        if(!$actParams['date']) throw new Exception('date not set');
        if(!$actParams['desc']) throw new Exception('desc not set');

        $date = DateUtils::make_date($actParams['date']);
        $year = substr($date,0,4);
        $new_number = $this->next_number($this->table,$year);

        $db = $this->db;
        $prepared = [
            '`partner`'=>$actParams['partner'],
            '`sum`'=>$actParams['sum'],
            '`date`'=>$date,
            '`desc`'=>$actParams['desc'],
            '`number`'=>$new_number,
        ];
        $db->insert($this->table, $prepared);
    }

} 