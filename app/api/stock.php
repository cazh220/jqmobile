<?php
date_default_timezone_get("PRC");
/**
 * 库存操作接口
 * 
 *2012-10-16 
 */

require_once '../common.inc.php';

class stock extends Action
{
	public   $app              = null;
	private  $db               = null;
	private  $db_distribution  = null;
	private  $store_db         = null;
	private  $data             = null;
	private  $action           = null;
	private  $partner_id       = null;
	private  $thetime          = null;

	function __construct(& $app)
	{   
		$this->thetime            = time();
		$this->app                = $app;
		$this->db                 = $app->orm($app->cfg['db'])->query();
		mysql_query("set names utf8");
		$this->db_distribution    = $app->orm($app->cfg['union'])->query();
		mysql_query("set names utf8"); 
		$this->store_db           = $app->orm($app->cfg['store_db'])->query();
		mysql_query("set names utf8"); 
		
		$this->mcache             = $app->cache('Memcached',$app->cfg['path']['cache']);
		if($this->db == null || $this->db_distribution == null || $this->store_db == null)
		{
			$log_data     = 'Could not connect database';
			$this->_log($log_data,'error');
			exit(json_encode(array('status'=>false,'message'=>$log_data)));
		}
		
		//获取请求方ID、Action、Data
		$this->partner_id         = empty($_REQUEST['partner']) ? '' : intval($_REQUEST['partner']);
		$this->action             = empty($_REQUEST['action'])  ? '' : strtolower(trim($_REQUEST['action']));
		$data                     = empty($_REQUEST['data'])    ? '' : $_REQUEST['data'];
		
		//解析数据、密钥验证
		$data_base64              = rawurldecode($data);
		$data                     = base64_decode($data_base64);
		$request_key              = substr($data,0,16);
		$data                     = substr($data, 16);
		$data                     = serialize($data);
		
		//写入到日志中去
		$log_data                 = $this->partner_id.'|'.$this->action.'|'.$data_base64;
		$this->_log($log_data,'access');
		
		//合作方密钥
		$sql     = "SELECT * FROM `partner` WHERE id = 1";
		$sql     = sprintf($sql,$this->partner_id);
		$result  = $this->mcache->get(md5('partner_'.$this->partner_id));
		if(empty($result))
		{
			$result     = $this->db_distribution->getRow($sql);
			$this->mcache->set(md5('partner_'>$this->partner_id),$result,86400);
		}
		
		$partner_user_id           = $result['id'];   //请求方ID
		$partner_key               = $result['key'];       //密钥
		$key_check                 = $partner_key.count($data);	
		$key_check                 = md5($key_check,true);
		
		//密钥验证    
		if($request_key!=$key_check)
		{   
			$log_data              = '密钥不正确！';
			$this->_log($log_data,'error');
		}	
		$this->data=$data;		
	}
	
	
	public function doDefault()
	{
		importModule('LogInfo','class');
        $obj_log = new LogInfo;
		import('util.Clean');		
		$data  = unserialize($this->data);
		$this->data = unserialize($data);
		$data = array();
		foreach($this->data['list'] as $key=>$val)
		{
			$data[$key]['goods_id']               = Clean::htmlSafe(trim($val['goods_id']));
			$data[$key]['size']                   = Clean::htmlSafe(trim($val['size']));
			$data[$key]['color']                  = Clean::htmlSafe(trim($val['color']));
			$data[$key]['goods_num']              = Clean::htmlSafe(trim($val['goods_num']));
			$data[$key]['agency_id']              = Clean::htmlSafe(trim($val['agency_id']));
			$data[$key]['order_sn']               = Clean::htmlSafe(trim($val['order_sn']));
			$data[$key]['goods_type']             = Clean::htmlSafe(trim($val['goods_type']));
		}
		function query($goods_id,$size,$color,$agency_id,$store)
		{   
			$where = "WHERE 1 ";
			if($goods_id)
			{
				$where .= " AND goods_id =".$goods_id;
			}
			if($size)
			{
				$where .= " AND size ='".$size."'";
			}
			if($color)
			{
				$where .= " AND color ='".$color."'";
			}
			if($agency_id)
			{
				$where .= " AND agency_id = ".$agency_id;
			}
			
			$sql = "SELECT * FROM goods_stock ".$where;
			$res = $store->getArray($sql);
			if($res === false)
			{
				$log_data = "查库存错误";
				$this->_log($log_data,'error');
			}
			
			return $res;
		}
		
		// 查库存
		if($this->action == 'search')
		{  
			$result = query($this->data['goods_id'],$this->data['size'],$this->data['color'],$this->data['agency_id'],$this->store_db);
			if(empty($result))
			{
				exit(json_encode(array('status'=>0,'message'=>'查询失败！','info'=>$ar_temp)));
			}else{
				exit(json_encode(array('status'=>1,'message'=>'查询成功！','info'=>$result)));
			}
			
		}
		$ar_temp = $data;

		if($this->data['type'] == 'freeze')
		{
			$type = 2;    //冻结
		}else if($this->data['type'] == 'return'){
			$type = 3;   //入库
		}else if($this->data['type'] == 'out'){
			$type = 1;  //出库
		}else if($this->data['type'] == 'insert'){
			$type = 4;  //新产品入库
		}
        
		//冻库存
		if($this->action == 'freeze')
		{
			$type                  = empty($type) ? '2' : $type;
			foreach ($ar_temp as $key=>$val)
			{
				$ar_temp[$key]['goods_type'] = empty($ar_temp[$key]['goods_type']) ? '1' : $ar_temp[$key]['goods_type'];
				$ar_temp[$key]['type']       = $type;
				
				$result = query($val['goods_id'],$val['size'],$val['color'],$val['agency_id'],$this->store_db);
				if(empty($result))
				{
					exit(json_encode(array('status'=>0,'message'=>'库存查询错误！','info'=>$ar_temp[$key])));
				}else {
					$s_where = " WHERE goods_id = ".$val['goods_id']." AND size = '".$val['size']."' AND color = '".$val['color']."' AND agency_id = ".$val['agency_id'];
					if(count($result)==1)
					{
						if($val['goods_num'] >=0 && $val['goods_num'] > $result[0]['efficacious_quantity'])
						{
							exit(json_encode(array('status'=>0,'message'=>'有效库存不足！','info'=>$ar_temp[$key])));
						}
						elseif ($val['goods_num'] <=0 && abs($val['goods_num']) > $result[0]['freeze_quantity']){
							exit(json_encode(array('status'=>0,'message'=>'冻结库存不足！','info'=>$ar_temp[$key])));
						}
						else{
							$sql = "UPDATE goods_stock SET efficacious_quantity = efficacious_quantity - (".$val['goods_num']."),freeze_quantity = freeze_quantity + (".$val['goods_num'].")".$s_where;
							$res = $this->store_db->exec($sql);  //冻结库存
							if($res != 1)
							{
								exit(json_encode(array('status'=>0,'message'=>'冻结失败','info'=>$ar_temp[$key])));
							}else {
								$this->stock_log($ar_temp[$key],$type);
							}
						}
					}else{
						exit(json_encode(array('status'=>0,'message'=>'冻结失败','info'=>$ar_temp[$key])));
					}
				}
			}
			exit(json_encode(array('status'=>1,'message'=>'冻结成功','info'=>$ar_temp)));
		}
		
		//减库存
		if($this->action == 'update')
		{
			$type                  = empty($type) ? '1' : $type;
			foreach ($ar_temp as $key=>$val)
			{
				$ar_temp[$key]['goods_type'] = empty($ar_temp[$key]['goods_type']) ? '1' : $ar_temp[$key]['goods_type'];
				$ar_temp[$key]['type']       = $type;
				$result = query($val['goods_id'],$val['size'],$val['color'],$val['agency_id'],$this->store_db);
				if(empty($result))
				{
					exit(json_encode(array('status'=>0,'message'=>'库存查询错误！','info'=>$ar_temp[$key])));
				}
				else{
					$s_where = " WHERE goods_id = ".$val['goods_id']." AND size = '".$val['size']."' AND color = '".$val['color']."' AND agency_id = ".$val['agency_id'];
					if(count($result)>1)
					{
						exit(json_encode(array('status'=>0,'message'=>'库存查询错误！','info'=>$ar_temp[$key])));
					}else{
						if($val['goods_num'] > 0 && $val['goods_num'] > $result[0]['freeze_quantity'])
						{
							exit(json_encode(array('status'=>0,'message'=>'减库存失败','info'=>$ar_temp[$key])));
						}
						else{
							$sql = "UPDATE goods_stock SET quantity = quantity - (".$val['goods_num']."),freeze_quantity = freeze_quantity - (".$val['goods_num'].")".$s_where;
						    $res = $this->store_db->exec($sql);
						    if($res != 1)
						    {
						    	exit(json_encode(array('status'=>0,'message'=>'减库存失败！','info'=>$ar_temp[$key])));
						    }else{
						    	$this->stock_log($ar_temp[$key],$type);   //1减库存
						    }
						}
					}
				}
			}
			exit(json_encode(array('status'=>1,'message'=>'减库存成功','info'=>$ar_temp)));			
		}
		
		//入库
		if($this->action == 'stock_in')
		{		
			if($type == 4)
			{
				//新产品入库
				foreach ($ar_temp as $key=>$val)
				{
					$ar_temp[$key]['type']       = $type;
					$ar_temp[$key]['agency_id']              = empty($val['agency_id']) ? '1':$val['agency_id'];
					$ar_temp[$key]['goods_type'] = empty($ar_temp[$key]['goods_type']) ? '1' : $ar_temp[$key]['goods_type'];
					$sql = "INSERT INTO goods_stock(goods_id,size,color,agency_id,quantity,efficacious_quantity)VALUES(".$val['goods_id'].",'".$val['size']."','".$val['color']."',".$ar_temp[$key]['agency_id'].",".$val['goods_num'].",".$val['goods_num'].")";	
					$res = $this->store_db->exec($sql);
					if($res!=1)
					{
						exit(json_encode(array('status'=>0,'message'=>'新产品入库失败！','info'=>$ar_temp[$key])));
					}else{
						$this->stock_log($ar_temp[$key], $type);
					}
				}
				exit(json_encode(array('status'=>1,'message'=>'新产品入库成功！','info'=>$ar_temp)));
			}else if($type == 3){
				//一般入库
				foreach ($ar_temp as $key=>$val)
				{
					$s_where = " WHERE goods_id = ".$val['goods_id']." AND size = '".$val['size']."' AND color = '".$val['color']."' AND agency_id = ".$val['agency_id'];
					if($val['goods_type']==0)
					{
						//不良品入库
						$sql = "UPDATE goods_stock SET quantity = quantity + ".$val['goods_num'].",bad_quantity = bad_quantity + ".$val['goods_num'].$s_where;
						$res = $this->store_db->exec($sql);
						if($res!=1)
						{
							exit(json_encode(array('status'=>0,'message'=>'不良品入库失败','info'=>$ar_temp[$key])));
						}else{
							$this->stock_log($ar_temp[$key], $type);
						}
					}
					else if($val['goods_type'] == 1)
					{
						//良品入库
						$sql = "UPDATE goods_stock SET quantity = quantity + ".$val['goods_num'].",efficacious_quantity = efficacious_quantity + ".$val['goods_num'].$s_where;
						$res = $this->store_db->exec($sql);
						if ($res!=1)
						{
							exit(json_encode(array('status'=>0,'message'=>'良品入库失败！','info'=>$ar_temp[$key])));
						}
						else{
							$this->stock_log($ar_temp[$key], $type);
						}
					}else{
						exit(json_encode(array('status'=>0,'message'=>'入库失败','info'=>$ar_temp[$key])));
					}
				}
				exit(json_encode(array('status'=>1,'message'=>'入库成功','info'=>$ar_temp)));
			}else{
				exit(json_encode(array('status'=>0,'message'=>'入库操作失败','info'=>$ar_temp[$key])));
			}
		}		
	}
	
	/**
	 * 库存操作日志
	 */
	private function stock_log($data,$type)
	{
		$user_id    = $_SESSION['user_id'] = 47;
		$order_sn   = $data['order_sn'];
		$goods_id   = $data['goods_id'];
		$size       = $data['size'];
		$color      = $data['color'];
		$goods_num  = $data['goods_num'];
		$goods_type = $data['goods_type'];
		$sql = "INSERT INTO stock_log(log_time,user_id,type,order_sn,goods_id,size,color,goods_num,goods_type)VALUES(".time().",".$user_id.",".$type.",".$order_sn.",".$goods_id.",'".$size."','".$color."',".$goods_num.",".$goods_type.")";
		return $this->store_db->exec($sql);
	}
	
	
	/**
	 * 错误日志
	 */
	private function _log($data,$title='')
	{
		$format_time = date('Y-m-d H:i:s');
		$log = $this->app->log();
		$log->reset()->setTitle($title)->setPath("api")->setData($format_time.$data)->write();
	}
}
$app->run();


?>