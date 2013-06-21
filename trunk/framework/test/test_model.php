<?php

require_once '../app/AppModel.php';
require_once '../config/lib/Config.php';
require_once '../core/PathService.php';
require_once '../app/coreLib/db/MysqlAccess.php';

$apm = new AppModel();
$apm->initialDB();

// test getActByAtt

$page = 1;
$number_page = 10;

$offset = ($page - 1) * $number_page;

$columns = array(
    0 => 'activity.id',
    1 => 'activity.title',
    2 => 'activity.startdate',
    3 => 'activity.enddate',
    4 => 'activity_mng.attend_date',
    5 => 'activity.allow_attend',
    6 => 'attendent.email',
    7 => 'attendent.address');
$joinTables = array(0 => 'activity_mng',
    1 => 'attendent');
$condition = array("AND" => array(array("=", 'activity' => 'id',
            'activity_mng' => 'activity_id'),
        array("=", 'attendent' => 'id',
            'activity_mng' => 'attendent_id')
        ));

$apm->db->select($columns, 'activity');
$apm->db->innerJoin($joinTables);
$apm->db->joinOn($condition);
if (!is_null($attendent_id)) {
    $where = array("" => array("=" => array('attendent_id' => $attendent_id)));
    $apm->db->where($where);
}
$apm->db->limit($offset, $number_page);
$mysql_results = $apm->db->execute();
$sql = $apm->db->getStatement();

$rows = mysql_fetch_array($mysql_results, MYSQL_BOTH);

var_dump($rows);
echo $sql;

echo " =========== \n";


//getAwdByAtt

$columns1 = array(0 => 'attendent.id',
    1 => 'attendent.img',
    2 => 'attendent.name',
    3 => 'award.name',
    4 => 'award.annotation',
    5 => 'activity_mng.attend_date');
$joinTables1 = array(0 => 'activity_mng',
                     1 => 'award',);
$condition1 = array("AND" => array(
        array("=", 'award'        => 'id',
                   'activity_mng' => 'award_id'),
        array("=", 'attendent'    => 'id',
                   'activity_mng' => 'attendent_id')
        ));

$apm->db->select($columns1, 'attendent');
$apm->db->innerJoin($joinTables1);
$apm->db->joinOn($condition1);
//$where1 = array("" => array("=" => array('activity_id' => 1)));
$where1 = array("AND" => array("=" => array('activity_id' => 5), 
                              ">" => array('award_id' => 0)
                             )
              );
//$wherex = array("AND"=>array("="=>array(field1=>value1, field2=>value2), ">"=>array(field3=>value3)));
$apm->db->where($where1);
$apm->db->limit($offset, $number_page);
$sql1 = $apm->db->getStatement();
echo $sql1;
$mysql_results1 = $apm->db->execute();


$rows1 = mysql_fetch_array($mysql_results1, MYSQL_BOTH);
var_dump($rows1);

?>
