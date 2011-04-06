<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace theos;
/**
 * Description of DBGen
 *
 * @author felipe
 */
class DBGen{
	
	private $dbData= false;
	
	public function generateDatabase(Array $projectData)
	{
		$ar= Array(
			'driver'=>$projectData['database_drive'],
			'dbName'=>$projectData['database_name'],
			'host'  =>$projectData['database_addr'],
			'port'  =>$projectData['database_port'],
			'user'  =>$projectData['database_user'],
			'pwd'   =>$projectData['database_pwd']
		);
		$this->dbData= $ar;
		$this->dbal= new \MindDBAL($ar);
		\DQB\QueryFactory::$showHeader= false;
		\DQB\QueryFactory::setUp($ar['driver']);
		\DQB\QueryFactory::buildQuery('*');
		$qrs= \DQB\QueryFactory::getCompleteQuery(false, true, 'array');
		
		$this->dbal->begin();
		foreach($qrs as $qr)
		{
			$exec = $this->dbal->execute($qr);
			
			if($exec === false)
			{
				echo "ERROR: a problem occurred in the following query\n";
				echo "All the queries will be aborted...\n";
				echo $qr."\n\n";
				return false;
			}
		}
		$this->dbal->commit();
		echo "Database created successfuly\n";
	}
}