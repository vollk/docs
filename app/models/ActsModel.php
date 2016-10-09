<?
use \Doctrine\DBAL\Query\QueryBuilder as Qb;

class ActsModel extends BaseModel
{
    private $table = "acts";

    private $filter_config = ['id'=>'a.id'];

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

        $engine = new TemplateEngine(array_merge(
            $act_data,
            $campData), $template, $file_name);
        $engine->compile();
        return $file_name;
    }

    public function getActs($serializeFilters)
    {
        $db = $this->db;
        $select = $db->createQueryBuilder()
            ->select(
                'a.id',
                'date',
                'number',
                'sum',
                '`desc`',
                'p.name as partner',
                'p.inn as partner_inn',
                'p.kpp as partner_kpp',
                'p.address as partner_address',
                'p.acc as partner_acc',
                'p.kor_acc as partner_kor_acc',
                'p.bank as partner_bank',
                'p.bik as partner_bik',
                'p.phone as partner_phone')
            ->from($this->table,'a')
            ->join('a','partners','p','p.id=a.partner')
            if($sort)
            {
                $qb->orderBy('?');
                $qb->setParameter(0,$sort,PDO::PARAM_STR);
            }
            ->orderBy('a.date','desc');

        if($serializeFilters)
            $this->applyFilters($select, $serializeFilters);
        //throw new Exception($select->getSQL());
        return $select->execute()->fetchAll();
    }

    private function getActData($id)
    {
        $db = $this->db;
        /**
         * @var \Doctrine\DBAL\Connection $db
         */
        $select = $db->createQueryBuilder()
            ->select(
                'a.id',
                'date',
                'number',
                'sum',
                '`desc`',
                'p.name as partner',
                'p.inn as partner_inn',
                'p.kpp as partner_kpp',
                'p.address as partner_address',
                'p.acc as partner_acc',
                'p.kor_acc as partner_kor_acc',
                'p.bank as partner_bank',
                'p.bik as partner_bik',
                'p.phone as partner_phone')
            ->from($this->table,'a')
            ->join('a','partners','p','p.id=a.partner')
            ->where('a.id=?')
            ->setParameter(0,(int) $id);
        return $select->execute()->fetch();
    }

    private function prepareData(array & $data)
    {
        $data['sum_prop'] = MoneyUtils::num2str($data['sum']);
        $data['sum'] = ' '.$data['sum'];
        $data['date'] = DateUtils::make_date_to_output($data['date']);
    }
    private function get_file_name($act_data)
    {
        return 'act_'.$act_data['number'].'.xls';
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

    protected function applyFilters(Qb $select, $serializeFilters)
    {
        $filters = json_decode($serializeFilters,true);
        $i = 0;
        $select->where("1=1");
        foreach($filters as $field=>$val)
        {
            $i++;
            $db_column  = array_key_exists($field,$this->filter_config) ? $this->filter_config[$field] : $field;
            $select->andWhere("$db_column LIKE :filter$i")->setParameter("filter$i",'%'.$val.'%');
        }
    }

}