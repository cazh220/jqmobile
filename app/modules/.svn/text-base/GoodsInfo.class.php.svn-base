<?php 
/**
 * 商品信息处理类
 * 
 * @package     modules
 * @author      鲍(chenglin.bao@lyceem.com)
 * @copyright   2010-3-22
 */

class GoodsInfo {

	/**
	 * 应用程序对象
	 * @var Application
	 */
	private $app = null;
	
	/**
	 * 数据库操作对象
	 * @var OrmQuery
	 */
	private $db = null;
	
	private $union = null;
	
	private $store_db = null;
	
	
	//配置导航目录数据
	private $ar_nav_func = array(46,42,47,53);
	
	private $ar_nav_sence = array(412,413,414,415,416,417,418,419,420);
	
	private $ar_root_type = array(46=>'服装',42=>'鞋袜',47=>'箱包',53=>'装备');
	
 	/**
     * 构造函数，获取数据库连接对象
     *
     */
    public function __construct(){
        global $app;
  
        $this->app = $app;
		
        $this->db = $app->orm($app->cfg['db'])->query();
		
		mysql_query("set names utf8");
		
		$this->store_db = $app->orm($app->cfg['store_db'])->query();
		
        mysql_query("set names utf8");
		
		$this->union = $app->orm($app->cfg['union'])->query();
		
        mysql_query("set names utf8");
    }
	
    /**
     * 根据商品的id查询商品
     * 
     * @param array $ar_goodsid 商品id
     * @return array|bool       成功返回商品数组,失败返回false
     */
    public function getGoodsById($ar_goodsid){
   		if($this->db == null){
			return false;
		}
		
		if(!$ar_goodsid){
			return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error, good_id = ' . $ar_goodsid , date("Y-m-d H:i:s")));	
		}
		
		if(!is_array($ar_goodsid))
		{
			$ar_goodsid = array($ar_goodsid);	
		}
		
		$sql = "SELECT g.goods_id,g.goods_sn,g.goods_name,gu.color FROM ecs_goods g,ecs_goods_unique gu ".
			"WHERE g.goods_id = gu.goods_id AND g.goods_id in (". join(',',$ar_goodsid).")";                                           //不要用 in  转成  = .    谢佐福  2012-6-14 14:48
		
		/*
		$whereOr = '';	
		foreach($ar_goodsid as $k=>$v)
		{
			if( !empty( $v ) )
			{
				if( ""==$whereOr )
				{
					$whereOr = " g.goods_id =  ".$v;
				}else{
					$whereOr .= " Or g.goods_id =  ".$v;
				}
			}
		}
		if( empty( $whereOr  ) )
		{
			return false;
		}
		$sql = "SELECT g.goods_id,g.goods_sn,g.goods_name,gu.color FROM ecs_goods g,ecs_goods_unique gu ".
				"WHERE g.goods_id = gu.goods_id AND (".$whereOr.")"; 
				
		unset($whereOr);unset($v);		
		*/
		$r   = $this->db->getArray($sql);
			//die($sql);
		if($r === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error .' . $sql , date("Y-m-d H:i:s")));
		}
		
		if(!empty($r) && !is_array($r)){
			return false;
		}
		
		unset($ar_goodsid , $sql);
		
		$ar_goods = array();
		foreach($r as $val){
			$ar_goods[$val['goods_id']] = $val;
		}

		return $ar_goods;
    }
    
    /**
     * 根据条码获取商品信息ID
     */
    public function getBarcodeId($barcode)
    {
    	if($this->db == null) return false;
    	if(!$barcode)
    	{
    		return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error, good_sn = ' . $ar_goodssn , date("Y-m-d H:i:s")));	
    	}
    	$sql = "SELECT goods_id FROM `ecs_goods_unique` WHERE barcode = $barcode";
    	 
    	$r = $this->db->getValue($sql);
    	 
    	if($r === false){
    		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error .' . $sql , date("Y-m-d H:i:s")));
		}
    	
		return $r;
    	
    	
    }
    
    
    /**
     * 根据商品的货号查询商品的id
     * 
     * @param array $ar_goodssn 商品货号
     * @return int|bool 		成功返回商品id,失败返回false
     */
	public function getGoodsId($ar_goodssn){
		if($this->db == null){
			return false;
		}
		
		if(!$ar_goodssn)
		{
			return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error, good_sn = ' . $ar_goodssn , date("Y-m-d H:i:s")));	
		}
		
		if(!is_array($ar_goodssn))
		{
			$ar_goodssn = array($ar_goodssn);	
		}
		
		$s_goodssn = "'" . implode("','" , $ar_goodssn) . "'";
		
		$sql = "SELECT goods_id FROM ecs_goods WHERE goods_sn in (". $s_goodssn .")";
		
		$r   = $this->db->getColumn($sql);
		
		if($r === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error .' . $sql , date("Y-m-d H:i:s")));
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}
		
		unset($ar_goodssn , $s_goodssn , $sql);
		
		return $r;
	}

    /**
     * 根据商品的货号查询商品的id
     * 
     * @param array $where  查询条件
     * @return int|bool 		成功返回商品id,失败返回false
     */
	public function getGoodsIdByWhere($where=''){
		if($this->db == null){
			return false;
		}

		
		$sql = "SELECT goods_id FROM ecs_goods ".$where.' order by goods_id asc';
		$r   = $this->db->getColumn($sql);
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}
		
		unset($ar_goodssn , $s_goodssn , $sql);
		
		return $r;
	}	
	
	
	/**
     * 根据商品的货号查询商品的id
     * 
     * @param array $ar_goodssn 商品货号
     * @return int|bool 		成功返回商品id,失败返回false
     */
	public function getGIdBySN($ar_goodssn){
		if($this->db == null){
			return false;
		}
		
		if(!$ar_goodssn)
		{
			return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error, good_sn = ' . $ar_goodssn , date("Y-m-d H:i:s")));	
		}
		
		if(!is_array($ar_goodssn))
		{
			$ar_goodssn = array($ar_goodssn);	
		}
		
		$s_goodssn = "'" . implode("','" , $ar_goodssn) . "'";
		
		$sql = "SELECT goods_id,goods_sn FROM ecs_goods WHERE goods_sn in (". $s_goodssn .")";
		$r   = $this->db->getArray($sql);
		
		if($r === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error .' . $sql , date("Y-m-d H:i:s")));
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}

		$ar = array();
		
		foreach($r as $v){
			$ar[$v['goods_sn']] = $v['goods_id'];
		}
		
		unset($ar_goodssn , $s_goodssn , $sql);
		
		return $ar;
	}
	
	/**
     * 根据商品的查询商品的信息
     * 
     * @param array $param  查询条件
	 * @param array $field  查询条件字段
     * @return int|bool 		
     */
	public function getGoodsIdBy($param,$field = 'goods_sn'){
		if($this->db == null){
			return false;
		}
		
		if(!$param)
		{
			return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error, good_sn = ' . $ar_goodssn , date("Y-m-d H:i:s")));	
		}

		$sql = "SELECT goods_id,goods_sn,goods_name FROM ecs_goods WHERE $field LIKE '%$param%' AND extension_code = ''";
		$r   = $this->db->getArray($sql);
		
		if($r === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql error .' . $sql , date("Y-m-d H:i:s")));
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;
		}
		
		return $r;
	}
	
	/**
	 * 获取所有颜色列表
	 * 
	 * @param  array $ar_goodsid 商品id(默认为空)
	 * @return array|bool  成功返回颜色列表,失败返回false
	 */
	public function getColor($ar_goodsid=array()){
		if($this->db == null){
			return false;
		}
		
		if(!empty($ar_goodsid) && is_array($ar_goodsid)){
			$sql = "SELECT distinct(goods_id),color FROM ecs_goods_unique WHERE goods_id IN (".join(',',$ar_goodsid).")";
			$res = $this->db->getArray($sql);
		}else{
			$res = $this->db->addTable('ecs_config')->addField('config_id')->addField('config_name')->addWhere('config_type',5)->getArray();
		}
		
		if($res === false){
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . 'execute select fail.'.$res, date("Y-m-d H:i:s")));
		}
		
		if(empty($res) || !is_array($res)){
			return false;
		}
		
		$ar_color = array();
		
		foreach($res as $r){
			$ar_color[$r['goods_id']] = $r; 
		}
		
		unset($r,$sql);
		
		return $ar_color;
	}
	
	
	/**
	 * 获取商品尺寸列表
	 * 
	 * @param  string  $ar_goodsid  商品id
	 * @return array|bool 
	 */
	public function getGoodsSize($ar_goodsid)
	{
		if($this->db == null)
			return false;
		
		if(!$ar_goodsid)
		{
			return false;	
		}
		
		if(!is_array($ar_goodsid))
		{
			$ar_goodsid = array($ar_goodsid);
		}
		
		$s_goodsid = implode(',' , $ar_goodsid);
		
		$sql = "SELECT distinct(size),goods_id FROM ecs_goods_unique WHERE goods_id IN ($s_goodsid) ";
		$r   = $this->db->getArray($sql);
		
		if(!$r)
		{
			return $this->_log(array('Products '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'','table:ecs_goods:'.$result,date("Y-m-d H:i:s")));		
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;	
		}
		
		$ar_size = array();
		foreach($r as $v)
		{
			$ar_size[$v['goods_id']][] = $v['size'];	
		}
		
		unset($ar_goodsid , $s_goodsid , $sql , $r);
		return $ar_size;
	}
	
	
	
	/**
     * 获取商品分类
     * @return   array|bool    执行成功返回结果数组，失败返回false
     */
	public function getCat() {
		if($this->db == null)
			return false;
		
		if(!is_array($this->ar_nav_func) || count($this->ar_nav_func) < 1){
			return false;
		}
		
		$s_nav_func = join(',',$this->ar_nav_func);
			
		//获取功能目的
		$sql = "SELECT cat_id,cat_name,parent_id FROM  ecs_category  WHERE parent_id in (".$s_nav_func.") AND show_in_nav <> 0 AND is_show <> 0 ORDER BY sort_order ASC";
		$result = $this->db->getArray($sql);
	
		if($result === false){
			return $this->_log(array('Menu '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'','table:ecs_category'.
			$sql,date("Y-m-d H:i:s")));
		}
		
		$temp		=	array();
		$ar_temp	=	array();
		

		//设置数组
		if(!empty($result) && is_array($result) && !empty($this->ar_root) && is_array($this->ar_root)){
			foreach ($this->ar_root_type as $k =>$v){
				foreach($result as $res){
						$temp[]	=	$res;
				}
				$ar_temp[$v]	=	$temp;
				unset($temp);
			}
		}
		

		if(!is_array($this->ar_nav_sence) || count($this->ar_nav_sence) < 1){
			return false;
		}
		
		$s_nav_sence = join(',',$this->ar_nav_sence);
		
		//获取旅行场景
		$sql = "SELECT cat_id,cat_name,parent_id FROM ecs_category  WHERE cat_id in (".$s_nav_sence.") AND show_in_nav <> 0 AND is_show <> 0 ORDER BY sort_order ASC";
		$res_sence = $this->db->getArray($sql);

		if($res_sence === false){
			return $this->_log(array('Menu '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'','table:ecs_category'.
			$sql,date("Y-m-d H:i:s")));
		}

		if(!empty($res_sence) && is_array($res_sence)){
			foreach($res_sence as $sence){
				$temp[] = $sence;
			}
			$ar_temp['旅游主题系列'] = $temp;
			unset($temp);
		}

		return $ar_temp;
	}
	
	/**
	 * 根据商品条码获取商品信息
	 */
	public function getGoodsInfo_bar($barcode)
	{
		if($this->db == null)  return false;
		if($barcode)
		{
//			$sql = "SELECT goods_id FROM `ecs_goods_unique` WHERE barcode = $barcode";
//			$res = $this->db->getValue($sql);

				$sql = "SELECT g.goods_id,g.goods_style_sn ,g.goods_sn,g.goods_name,g.market_price , g.shop_price , g.is_real , g.is_on_sale,
				egu.barcode,egu.size,egu.color FROM ecs_goods as g, ecs_goods_unique as egu WHERE g.goods_id = egu.goods_id AND   egu.barcode = $barcode ";
				$r = $this->db->getArray($sql);
		}

		if($r === false)
		{
			return $this->_log(array('Products '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'','table:ecs_goods:'.$result,date("Y-m-d H:i:s")));
		}

		$ar_goodsinfo = array();
		foreach($r as $v)
		{
			$ar_goodsinfo[$v['goods_id']] = $v;
			$ar_goodsinfo[$v['goods_id']]['size'] = array();
			$ar_goodsinfo[$v['goods_id']]['size'][0] = $v['size'];
		}
		
	

//		$ar_size = $this->getGoodsSize(array_keys($ar_goodsinfo));
//
//		if(!$ar_size || !is_array($ar_size) || count($ar_size) == 0)
//		{
//			return false;	
//		}
//	
//		foreach($ar_goodsinfo as $k=>&$v)
//		{
//			$v['size'] = $ar_size[$k];
//		}	
		unset($sql , $r , $k , $v);
		return $ar_goodsinfo;
	}
	
	
	/**
	 * 根据商品sn获取商品信息
	 *
	 * @param array   $ar_goods    商品id组成的数组   
	 * @return false | array
	 */
	public function getGoodsInfo($ar_goodssn)
	{
		if($this->db == null)
			return false;
			
		if(!is_array($ar_goodssn))
		{
			$ar_goodssn = array($ar_goodssn);
		}
		
		$ar_goodssn = "'" . implode("','" , $ar_goodssn) . "'";
		
		$sql = "SELECT g.goods_id , g.goods_style_sn , g.goods_sn , g.goods_name , g.market_price , g.shop_price , g.is_real , g.is_on_sale , gc.color ".
				" FROM ecs_goods AS g , ecs_goods_colors_img AS gc  ".
				" WHERE g.goods_sn IN (" . $ar_goodssn . ") AND g.goods_id = gc.goods_id ";		
		$r   = $this->db->getArray($sql);
		
		if($r === false)
		{
			return $this->_log(array('Products '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'','table:ecs_goods:'.$result,date("Y-m-d H:i:s")));	
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;		
		}
		
		$ar_goodsinfo = array();
		foreach($r as $v)
		{
			$ar_goodsinfo[$v['goods_id']] = $v;	
		}
	
		$ar_size = $this->getGoodsSize(array_keys($ar_goodsinfo));

		if(!$ar_size || !is_array($ar_size) || count($ar_size) == 0)
		{
			return false;	
		}
	
		foreach($ar_goodsinfo as $k=>&$v)
		{
			$v['size'] = $ar_size[$k];
		}
		
		unset($ar_goodsid , $s_goodsid , $sql , $r , $k , $v);
		return $ar_goodsinfo;
	}
	
	
	/**
	 * 获取商品的批次
	 * 
	 * @param  int     $i_goodsid  商品id
	 * @return array|bool 
	 */
	public function getGoodsBatch($i_goodsid)
	{
		if($this->store_db == null)
			return false;
		
		$i_goodsid = (int)$i_goodsid;
		
		if($i_goodsid == 0)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error ' , date("Y-m-d H:i:s")));
		}
		
		$sql = "SELECT distinct(batch_id) FROM goods_stock WHERE goods_id = ".$i_goodsid;
		$r   = $this->store_db->getColumn($sql);
		
		if($r === false)
		{
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		if(!is_array($r) || count($r) < 1){
			return false;
		}
		
		$sql = "SELECT batch_id, batch_code FROM batch WHERE batch_id IN (".join(',',$r).")";
		$r   = $this->store_db->getArray($sql);
		
		
		//echo "<pre>";print_r($r);die;
		//$sql = "SELECT distinct(b.batch_id) , b.batch_code FROM batch AS b , goods_stock AS gs WHERE gs.goods_id = " . $i_goodsid . " AND gs.batch_id = b.batch_id AND b.confirm_status = 1";
		//$r   = $this->store_db->getArray($sql);

		if($r === false)
		{
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		$ar_batch = array();
		if(is_array($r) && count($r) > 0)
		{
			foreach($r as $v)
			{
				$ar_batch[$v['batch_id']] = $v;
			}
		}

		unset($i_goodsid , $sql , $r);
		return array_values($ar_batch);
	}
	
	
	/**
	 * 获取商品各尺寸库存数量
	 * 
	 * @param  int     $i_goodsid  商品id
	 * @param  string  $ar_size    商品尺寸
	 * @return array|bool 
	 */
	public function getStockNumByGoods($i_goodsid , $ar_size ,$i_agencyid = 0)
	{
		if($this->store_db == null)
			return false;

		$i_goodsid = (int)$i_goodsid;
		$i_agencyid = (int)$i_agencyid;

		if(!is_array($ar_size))
		{
			$ar_size = array($ar_size);
		}

		if($i_goodsid == 0 || count($ar_size) == 0)
		{
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' parameter error ' , date("Y-m-d H:i:s")));	
		}
		
		$ar_size = "'" . implode("','" , $ar_size) . "'";
		
		$sql = "SELECT goods_id , agency_id , size , color , quantity , bad_quantity , efficacious_quantity ".
				" FROM goods_stock ".
				" WHERE goods_id = " . $i_goodsid . " AND size IN (" . $ar_size . ")";
				
		if($i_agencyid != 0) {
			$sql .= " AND agency_id = $i_agencyid";
		}
		
		$sql .= " ORDER BY size";
		
		$r   = $this->store_db->getArray($sql);     
		
		if($r === false)
		{
			return $this->_log(array('Products '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		return $r;
	}
	
     /**
	 * 根据条码查询商品的所属仓库
	 *
	 * @param int $i_goodsid 
	 * @return array|bool
	 */
	public function getGoodsAgency_bac($i_goodsid,$size,$color) {
		if($this->store_db == null){
			return false;
    	}
		
		$i_goodsid = (int)$i_goodsid;
		$size      = (string)$size;
		$color     = (string)$color;
		
		if($i_goodsid == 0 || empty($size) || empty($color))  return false;
		
		$sql = "SELECT agency_id FROM goods_stock WHERE goods_id = $i_goodsid AND size = '".$size."' AND color = '".$color."'";
		$res = $this->store_db->getColumn($sql);
		//print_r($res);exit;
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		return $res;
	}
	
	
	/**
	 * 查询商品的所属仓库
	 *
	 * @param int $i_goodsid 
	 * @return array|bool
	 */
	public function getGoodsAgency($i_goodsid) {
		if($this->store_db == null){
			return false;
    	}
		
		$i_goodsid = (int)$i_goodsid;
		
		if($i_goodsid == 0)  return false;
		
		$sql = "SELECT agency_id FROM goods_stock WHERE goods_id = $i_goodsid";
		$res = $this->store_db->getColumn($sql);
		//print_r($res);exit;
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));	
		}
		
		return $res;
	}
	
	/**
	 * 根据商品条码查询商品信息
	 * 
	 * @param array $ar_unique 
	 * @return array|bool
	 */
	public function getGoodsByUnique($ar_unique) {
		if($this->db == null) return false;
		
		if(!$ar_unique || !is_array($ar_unique)) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' package unique is empty ' , date("Y-m-d H:i:s"))); 
		}
		
		$sql = "SELECT goods_id,size,barcode FROM ecs_goods_unique WHERE barcode IN ('".join("','",$ar_unique)."')";
		$res = $this->db->getArray($sql);
		
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' select is fail,sql:'.$sql.' mysql_error:'.mysql_error() , date("Y-m-d H:i:s")));	
		}
		
		$ar_goods = array();
		
		if($res && is_array($res)) {
			foreach($res as $r) {
				$ar_goods[$r['barcode']] = $r;
			}
		}
		
		return $ar_goods;
	}
	
	/**
	 * 判断某件商品是否属于某个供应商
	 *
	 * @param  int  $i_goodsid   商品id
	 * @param  int  $i_supplies  供应商编号
	 * @return array|bool 
	 */
	public function isBelongToSupplies($i_goodsid , $i_supplies)
	{
		if($this->store_db == null){
			return false;
    	}
	
		$i_goodsid  = (int)$i_goodsid;
		$i_supplies = (int)$i_supplies;
		
		if($i_goodsid == 0 || $i_supplies == 0)
		{
			return false;	
		}
		
		$sql = "SELECT bd.goods_id  FROM batch AS b, batch_details AS bd WHERE b.supplier_id = $i_supplies AND b.batch_id = bd.batch_id ";
		$r   = $this->store_db->getColumn($sql);
		if(!$r || !is_array($r))
		{
			return $this->_log(array('Products '.__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql = '.$sql , date("Y-m-d H:i:s")));		
		}
		
		$r = array_unique($r);
		return in_array($i_goodsid , $r) ? true : false;		
	}
	
	
	/**
	 * 判断某件商品是否属于某个供应商
	 *
	 * @param  int  $ar_goodsbarcode   商品条码
	 * @return array|bool 
	 */
	public function getGoodsByBarcode($ar_goodsbarcode)
	{
		if($this->db == null){
			return false;
    	}
		
		if(!$ar_goodsbarcode)
		{
			return false;	
		}
		
		if(!is_array($ar_goodsbarcode))
		{
			$ar_goodsbarcode = array($ar_goodsbarcode);	
		}
		
		$s_goodsbarcode = "('" . implode("'),('" , $ar_goodsbarcode) . "')";
		
		$sql = "SELECT u.goods_id, u.size, u.color, g.goods_sn, g.goods_name, now() time".
				" FROM ecs_goods_unique AS u, ecs_goods AS g WHERE u.barcode IN $s_goodsbarcode AND u.goods_id = g.goods_id";
		$r   = $this->db->getArray($sql);
		if($r === false)
		{
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));		
		}
		
		if(!is_array($r) || count($r) == 0)
		{
			return false;	
		}
		
		return $r;
	}
	
	/**
	 * 仓库已出库订单商品统计
	 * 
	 * @param array $ar_param 查询条件
	 * @return bool|array
	 */
	public function getOrderGoods($ar_param){
		if($this->union == null){
			return false;
    	}
    	
    	if(!is_array($ar_param) || count($ar_param) < 1) {
    		return false;
    	}
    	
    	//$sql = "SELECT order_id FROM order_goods_logistics ".
    	//		"WHERE confirm_time >= ".$ar_param['start_time']." AND confirm_time <= ".$ar_param['end_time'].
    	//		" AND logistics_status = 2 ";
		$sql = "SELECT order_id FROM order_info ".
    			"WHERE shipping_time >= ".$ar_param['start_time']." AND shipping_time <= ".$ar_param['end_time'].
    			" AND order_status = 1 AND shipping_status IN(1,2)";
		if(!empty($ar_param['partner_id'])) {
			$sql .= " AND partner_id = ".$ar_param['partner_id'];
		}
    	$res_orderid = $this->union->getColumn($sql);

    	if($res_orderid === false) {
    		return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));		
    	}
    	
    	if(!$res_orderid || !is_array($res_orderid)) {
    		return false;
    	}
    	
    	$sql = "SELECT goods_id,goods_name,goods_sn,color,size,goods_number as quantity FROM order_goods ".
    			"WHERE package_id = 0 AND order_id IN (".join(',',$res_orderid).") ORDER BY goods_id";

    	$res_goods = $this->union->getArray($sql);
    	
		if($res_goods === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		 
		return $res_goods;
	}

	/**
	 * 查询条码
	 */
	public function getGoodsBarcode($goods_id,$size)
	{
		if($this->db == null)
		{
			return false;
		}
		
		$sql = "select barcode from ecs_goods_unique where goods_id = '".$goods_id."' AND size = '".$size."'";
		$res = $this->db->getValue($sql);
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		
		return $res;
	
	}
	
	/**
	 * 获取所有商品信息
	 */
	public function getAllGoods($condition)
	{ 
		$start = ($condition['currentpage'] -1);
		$pagesize = $condition['pagesize'];
		if($this->db == null) return false;
		$sql  = "SELECT eg.goods_id,eg.goods_name,eg.goods_sn,ec.cat_name,eg.standard_cost FROM ecs_goods eg,ecs_category ec WHERE eg.cat_id = ec.cat_id";
        if(empty($condition['cat']))
        {
        	$sql .= " AND eg.cat_id = 0";
        }
        else 
        {
        	$sql .= " AND eg.cat_id IN (".$condition['cat'].")";
        }
		if($condition['goods_name'] != '')
		{
			$sql .= " AND eg.goods_name LIKE '%".$condition['goods_name']."%'";
		}
		
		$sql .= " ORDER BY eg.goods_id DESC";
		//echo $sql;
		$r    = $this->db->getArray($sql);
		$count = count($r);
		$pages = ceil($count/$pagesize);
		$sql .= " LIMIT ".($start*$pagesize).",".($pagesize);
		$res = $this->db->getArray($sql);
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		$ar = array();
		$ar['goods_list'] = $res;
		$ar['page']['recordcount'] = $count;
		$ar['page']['pagecount'] = $pages;
		$ar['page']['pagesize']  = $pagesize;
		$ar['page']['currentpage']  = ($start+1);
		return $ar;
	}
		
	/**
	 * 获取类别
	 */
	public function getCategory($cat_id)
	{
		if($this->db == null) return false;
		$sql = "SELECT cat_id FROM ecs_category";
		if($cat_id != 0)
		{
			$sql .= " WHERE parent_id = ".$cat_id;
		}
		$res = $this->db->getArray($sql);
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		if(is_array($res) && $res)
		{
			foreach ($res as $k=>$v)
			{
				$cats_id .= $v['cat_id'].',' ;
			}
		}
		$cats_id = rtrim($cats_id,',');
        return  $cats_id;
	}
	
	/**
	 * 修改标准成本
	 */
	public function editStandardCost($cost,$goods_id)
	{   
		if($this->db == null) return false;
		$sql = "UPDATE ecs_goods SET standard_cost = ".$cost." WHERE goods_id = ".$goods_id;

		$res = $this->db->exec($sql);
		if($res === false) {
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		
		if($res)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	/**
	 * 获取标准成本
	 */
	public function getstandard_price($goods_id)
	{
		if($this->db == null)  return false;
		$sql = "SELECT standard_cost FROM ecs_goods WHERE goods_id = ".$goods_id;
		$res  = $this->db->getValue($sql);
		if($res === false)
		{
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		return $res;
	}
	
	/**
	 * 获取批次价格
	 */
	public function getBatchPrice($goods_id)
	{
		if($this->store_db==NULL)  return false;
		$sql = "SELECT AVG(cost_price) as 'price' FROM `batch_details` WHERE goods_id =".$goods_id." GROUP BY batch_id";
		$res = $this->store_db->getArray($sql);
		
		if($res === false)
		{
			return $this->_log(array(__CLASS__.'.class.php function '.__FUNCTION__.' line '.__LINE__.'',' sql error, sql = '.$sql , date("Y-m-d H:i:s")));
		}
		return $res;
	}
		
	/**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param 	Array 	$data
	 * @return 	bool	false
	 */
	private function _log($data){
	    $log = $this->app->log();
	    $log->reset()->setPath("modules/GoodsInfo")->setData($data)->write();
	    
	    return false;
	}
}
?>