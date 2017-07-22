<?php 
/**
 * 货架管理类
 * 
 * @package     modules
 * @author	<xiaochen.li@lyceem.com)
 * @copyright   2011-04-29
 * 
 * $Id: ShelfInfo.class.php 5006 2012-04-24 02:24:32Z caozheng $
 */

class ShelfInfo {
	/**
	 * 定义数据库连接
	 * @var Application
	 */
	private $app = null;
	
	private $db = null;
	

	private $stock = null;
	

    public function __construct(){
        global $app;
        
        $this->app = $app;
        
        $this->stock = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
        
        $this->db = $app->orm($app->cfg['db'])->query();
		
        mysql_query("set names utf8");
        
        //初始化memcache
		$this->memcache = new Memcache;
		$this->memcache->connect($app->cfg['memcache'][0]['host'],$app->cfg['memcache'][0]['port']) or die('链接错误');
    }
    
    /**
     * 货架列表
     *
     * @access  public
     * @param   array    
     * 
     * @return  array|bool      
     */
    public function getShelfInfo($item = '',$s_where = ''){
    	if($this->db == null || $this->stock == null){
    		return false;
    	}
 
    	//$this->memcache->flush();
    	//memcache键设置
    	$query = md5($item.$s_where);
    	if($this->memcache->get($query))
    	     return $this->memcache->get($query);
    	if(!empty($s_where)){
    		if($item == "GOODS_SN")
    		{
    			$sql = "SELECT goods_id,goods_name,goods_sn,goods_style_sn FROM ecs_goods WHERE $s_where";
    		}
    		elseif ($item == "BARCODE")
    		{  
    			$sql = "SELECT eg.goods_id,eg.goods_name,eg.goods_sn,eg.goods_style_sn FROM ecs_goods as eg left join ecs_goods_unique as eu on eg.goods_id = eu.goods_id WHERE $s_where";
    		}
    		elseif ($item == "SHELF_CODE")
    		{
    			$sql = "SELECT DISTINCT(eg.goods_id),eg.goods_name,eg.goods_sn,eg.goods_style_sn FROM lyceem.ecs_goods as eg left join stock.goods_stock_shelf as sf on eg.goods_id = sf.goods_id WHERE $s_where";
    		}
    					
    	}else{
    		$sql = "SELECT goods_id,goods_name,goods_sn,goods_style_sn FROM ecs_goods ";
    	}
    
    	
    	$res = $this->db->getArray($sql);
    	
    	if($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    	}

    	if(!empty($res) && !is_array($res)){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
    	}

		for($i=0;$i<count($res);$i++){
			
			$sql="SELECT size,barcode,goods_id FROM ecs_goods_unique WHERE goods_id=".$res[$i]['goods_id'];	
            $res_shelf=$this->db->getArray($sql);
      		$sql="SELECT * FROM goods_stock_shelf a join agency b on a.agency_id=b.agency_id AND a.goods_id=".$res[$i]['goods_id'];;
         	$res_shel=$this->stock->getArray($sql);
			if($res_shelf === false){
    			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute is error. sql = '.$sql, date("Y-m-d H:i:s")));
    		}

    		if(!empty($res_shelf) && !is_array($res_shelf)){
    			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
    		}
			$res[$i]['shelf']=$res_shelf;
			$res[$i]['shelf']['ll']=$res_shel;

		}
		$this->memcache->set($query,$res,0,$this->app->cfg['memcached'][0]['expire']);   //保存memcache键值
//		echo 'not memcache';
		//unset($_SESSION['insert']);  //释放SESSION标记
		return $res;
    }
   
    /*
     * 仓库列表(下拉框)
     */
    public function agencyInfo(){
		if($this->stock==null){
			return false;
		}
		$sql="SELECT agency_name,agency_id FROM  agency";
		$res=$this->stock->getArray($sql);
		$angency=$res;
		return $angency;
		
    }
    /*
     * 衣服尺码下拉列表
     */
    public function sizeInfo($rel_id){
    		if($this->db==null){
			return false;
		}
		$sql="SELECT * FROM ecs_goods_unique  WHERE goods_id=".$rel_id;
		$res=$this->db->getArray($sql);
		$goods_size=$res;
		return $goods_size;	
    }

	public function selectInfo($i_selectid){
	    if($this->stock == null||$this->db == null){
    		return false;
    	}
    	$i_selectid = (int)$i_selectid;
    	
    	if($i_selectid == 0 || $i_selectid == '') {
    		return false;
    	}

		$sql="SELECT b.agency_name as agency_name ,a.safe_quantity as safe_quantity,a.size as size,a.shelf_code,b.agency_id,a.goods_id,a.shelf_id  FROM goods_stock_shelf a left join agency b on a.agency_id=b.agency_id WHERE a.goods_id=".$i_selectid;
		$res=$this->stock->getArray($sql);
		
		if ($res === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
    	}
		
		for($i=0;$i<count($res);$i++){
				
			$sql="SELECT barcode FROM ecs_goods_unique WHERE goods_id=".$res[$i][goods_id];
		
			$r=$this->db->getValue($sql);
			
			if ($r === false){
	    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'result is error. '.$res, date("Y-m-d H:i:s")));
	    	}
	    	
	    	$res[$i]['barcode'] = $r;
		}
	//print_r($res);die;				
    	return $res;
	}
/*
 * 款号
 */
	public  function kuanInfo($i_selectid){
		 if($this->stock == null||$this->db == null){
    		return false;
    	}
    	$i_selectid = (int)$i_selectid;
    	$sql="SELECT goods_style_sn  FROM  `ecs_goods` WHERE  goods_id=".$i_selectid;
    	$res=$this->db->getArray($sql);
    	$goods_sn=$res;
    	return $goods_sn;
	}
	/*
	 * 多项货架添加列表
	 */
	public function allinsertInfo($regroup=''){
		if($this->stock == null||$this->db == null){
			return false;
		}
		
    	//$_SESSION['insert'] = 1;
		//如果存在组合数据则进行操作
		if($regroup){
			//print_r($regroup);
			foreach($regroup as $k=>$val)
			{	
				if(!empty($val['stockid'])||!empty($val['goosize'])||!empty($val['shcod'])||!empty($val['saqua'])){ 
				
					//查找该类商品是否有货架号
				
					$sql="select shelf_code from goods_stock_shelf where agency_id='$val[stockid]' and size ='$val[goosize]' and goods_id = '$_REQUEST[goodid]'";
				
					$re=$this->stock->getArray($sql);
					//查找该货架号是否存在
					$sql="select * from goods_stock_shelf where agency_id='$val[stockid]' and shelf_code='$val[shcod]' ";
					$res=$this->stock->getArray($sql);
					
					
		    	 		$sql = "INSERT INTO  goods_stock_shelf (shelf_code,agency_id,goods_id,size,safe_quantity)
		           	 	VALUES('$val[shcod]','$val[stockid]','$_REQUEST[goodid]','$val[goosize]','$val[saqua]')";

		    	 		$res1=$this->stock->exec($sql);
		    	 		
		    	 		$allinsert=$res1;
						importModule('LogSqs');
		 				$logsqs=new LogSqs;
		    	 
				}
			}
			$this->memcache->flush();//清除缓存
			return $allinsert;
		}
		
		$nuy=$_REQUEST['num'];
//		$nub=$_REQUEST['nub'];  //安全量
		$n_id=$_REQUEST['shelf_id'];
		
		if($nuy&&$n_id){
			for($i=0;$i<count($nuy);$i++){
				$sql="SELECT  * FROM `goods_stock_shelf` WHERE shelf_id =".$n_id[$i];
				$rs=$this->stock->exec($sql);
				$num=mysql_num_rows($rs);
				if($num==1){
					$sql="UPDATE `goods_stock_shelf` SET shelf_code='$nuy[$i]',safe_quantity='$nub[$i]' where shelf_id=".$n_id[$i];
					$rss=$this->stock->exec($sql);
					
					importModule('LogSqs');
					$logsqs=new LogSqs;
			
				}
				
			}
		}else{
			return false;
		}
		$this->memcache->flush();//清除缓存
	}
	
	/**
	 * 删除相应的货架
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function Delete_shelf($shelf_id)
	{
	    if($this->stock == null||$this->db == null){
			return false;
		}
		$sql = "DELETE FROM `goods_stock_shelf` WHERE shelf_id = '$shelf_id'";
		$this->stock->exec($sql);
		//$_SESSION['insert'] = 1;
		$this->memcache->flush();//清除缓存
	}
	
    /**
	 * 删除商品所有货架
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function Delete_allshelf($goods_id)
	{
	    if($this->stock == null||$this->db == null){
			return false;
		}
		$sql = "DELETE FROM `goods_stock_shelf` WHERE goods_id = '$goods_id'";
		$this->stock->exec($sql);
		//$_SESSION['insert'] = 1;
		$this->memcache->flush();//清除缓存
	}
	
	
	/**
	 * 获取颜色
	 * Enter description here ...
	 * @param unknown_type $data
	 */
	public function getGoods_color($where)
	{
		if($this->db == null)  return false;
		$sql = "SELECT goods_id,color FROM ecs_goods_unique ";
		if($where)
		{
			$sql .= $where;
		}
		
		$res = $this->db->getArray($sql);
		
		if($res == false)
		{
			$this->_log($res);
		}
		return $res;
	}
	
	/**
	 * 获取指定商品的货架地址
	 * Enter description here ...
	 * @param $data
	 */
	public function getGoodsShelf_code($goods_id,$size)
	{
		if($this->stock == null)  return false;
		$sql = "SELECT shelf_code FROM goods_stock_shelf WHERE goods_id = $goods_id AND size = '".$size."'";
		$res = $this->stock->getArray($sql);
		
		foreach ($res as $k=>$v)
		{
			$res[$k] = current($v);
		}
		
		if($res == false)
		{
			$this->_log($res);
		}
		return $res;
	}
	
	
    
	private function _log($data)
	{
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/ShelfInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>