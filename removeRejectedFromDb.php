<?php
require 'init.php';
// use Sunra\PhpSimple\HtmlDomParser;
$csv = array_map('str_getcsv', file('back_to_school_reject.txt'));


do {
        // select 1 row from table where statecheck = 0
        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();
        $sqlBuilder
        ->setQuery('SELECT * FROM bazanoua WHERE state = :state')
        ->setConditions(array('state' => '0'));
        $select = $sqlManager->fetchRow($sqlBuilder);

        print_r($select);
            
        // change statecheck to 1
        $data = array(
        'state' => '1',
        );

        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();

        $sqlBuilder
        ->setTableName('bazanoua')
        ->setConditions(array('id' => $select['id']))
        ->setConditionsQuery("id = :id")
        ->setData($data);

        $update = $sqlManager->update($sqlBuilder);

        // print_r($select['email']);
        
        foreach($csv as $key => $mail)
        {
            $email = $mail[0]; 
            if ($select['email'] == $email) 
            {
                echo "\n"."Am gasit rejectu"."\n";
                unsubscribe($select['id'], $sqlManager);
                // sleep(2);
            }  
            // print_r($email);       
        }

        $data = array(
        'state' => '2',
        );

        $sqlBuilder = new \Simplon\Mysql\Manager\SqlQueryBuilder();

        $sqlBuilder
        ->setTableName('bazanoua')
        ->setConditions(array('id' => $select['id']))
        ->setConditionsQuery("id = :id")
        ->setData($data);

        $update = $sqlManager->update($sqlBuilder);
        // break;
    } while ($select);

function unsubscribe($id, $sqlManager)
{
    try {
        $data = array(
                'unsubscribe' => '1',
            );
        $sqlBuilder = (new \Simplon\Mysql\Manager\SqlQueryBuilder())
        ->setTableName('bazanoua')
        ->setConditions(array('id' => $id))
        ->setConditionsQuery("id = :id")
        ->setData($data);

        $update = $sqlManager->update($sqlBuilder);
    } 
    catch (Simplon\Mysql\MysqlException $e)
    {
        echo "am prins eroarea";
    }
}