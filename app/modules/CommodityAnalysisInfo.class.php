<?php 
/**
 * 商品统计数据处理类
 * 
 * @package		modules
 * @author		<xiangji.yang@lyceem.com>
 * @copyright
 * 
 * $Id
 */
class CommodityAnalysisInfo {

    
	  /**
     * 应用程序对像
     *
     * @var Application
     */
    private $app = null;
    
    /**
     * 数据库操作对像
     *
     * @var OrmQuery
     */
    private $db = null;
	    /**
     * 数据库操作对像
     *
     * @var OrmQuery
     */
    private $stock = null;
    
    /**
     * 数据库操作对像
     *
     * @var OrmQuery
     */
    private $distribution = null;
    /**
     * 商品分类数组
     *
     * @var Array
     */
	public   $type_list=Array();
    /**
     * 文件缓存
     *
     * @var 
     */
	public $fileCache=NULL;
	/**
     * 构造函数
     *
     * @param String $mx_pfx
     */
    public function __construct() {
				global $app;				
				$this->app = $app; 
				$this->distribution = $app->orm($app->cfg['union'])->query();
				mysql_query("set names utf8");
				$this->db = $app->orm($app->cfg['db'])->query();
				mysql_query("set names utf8");
				$this->stock = $app-> orm($app->cfg['store_db'])->query();
				mysql_query("set names utf8"); 
			    $this->fileCache = $app->cache('FileCache',$app->cfg['path']['cache']);
			 
	 }
	 
	/**
	 * 分页查询所有活动商品记录
	 * @param Array $arr_form_data 查询条件
	 * @param int  $i_curpage 当前页
	 * @param int $i_page_size 页大小
	 * @return |bool 查询所有订单记录,失败返回false
	 */
	public function getGoodsInfoPage($ar_form_data,$i_curpage=1,$i_page_size=10)
	{     
			$type_list=$this->getAllOneCategory();
            
			if(!is_array($type_list)||!count($type_list)){ 
				  return false; 
			} 
			if(!is_array($ar_form_data)||!count($ar_form_data)){
					return  false;
			}
			$ar_where=Array();
			$ar_stock_where=Array();
			$s_goods_name=addslashes($ar_form_data['s_goods_name']);	
			$i_goods_typeid=intVal($ar_form_data['i_goods_typeid']);
			$i_timebegin=$ar_form_data['i_timebegin'];
			$i_timeend=$ar_form_data['i_timeend']; 
			$i_desc_type=$ar_form_data['i_desc_type'];
			$i_page_size=$ar_form_data['i_page_size'];  
			$i_curpage=$ar_form_data['i_curpage']; 
			$i_isall=$ar_form_data['i_isall']; 
			$time_end=($i_timeend+(60*60*24-1));//时间调整
			if($i_timebegin && $i_timeend && ($i_timebegin-$i_timeend==0)){
				$i_timebegin and $ar_where[] = ' o.add_time  >='.$i_timebegin.' ' and $ar_stock_where[]=' g.confirm_time  >='.$i_timebegin.' ';
				$i_timeend and $ar_where[] = ' o.add_time  <='.$time_end.' ' and $ar_stock_where[]=' g.confirm_time  <='.$time_end.' '; 
			}else{
			     $i_timebegin and $i_timebegin < $i_timeend and $ar_where[] = ' o.add_time  >='.$i_timebegin.' ' and $ar_stock_where[]=' g.confirm_time >='.$i_timebegin.' ';
		       $i_timeend and $i_timebegin < $i_timeend and $ar_where[] = ' o.add_time  <='.$time_end.' ' and $ar_stock_where[]=' g.confirm_time <='.$time_end.' ';
			}
			
			
			if(!empty($s_goods_name)){
			    $ar_where[] = " og.goods_name  like '%".$s_goods_name."%' "; 
				$s_goodsid_empty="  goods_name like '%".$s_goods_name."%' ";
			}
			/*名字模糊查询商品ID*/
			$ar_goods_id=Array();
			$flag_name=false;
			
			if(!empty($s_goodsid_empty)){ 
				   $res_data=$this->getGoodsbyName($s_goodsid_empty);
				  
					if(!is_array($res_data)){
						 return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail ', date("Y-m-d H:i:s")));
					}
					foreach($res_data as $val){
					   $ar_goods_id[$val['goods_id']]=Array('goods_id'=>$val['goods_id']);
					} 
                   $flag_name=true;
				   
			} 
			if($this-> distribution == null){
				return false;
			} 
		
			$s_where = '';
			if(is_array($ar_where) && count($ar_where) > 0){
				$s_where = join('  AND  ',$ar_where);
			} 
			 /*所有的商品信息*/
			$ar_goods=$this->getAllGoods($s_where); //查询所有符合条件的商品  
			
			if(!is_array($ar_goods)){
			     return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail ', date("Y-m-d H:i:s")));
		    }
			/*缩小查询范围*/
			foreach($ar_goods as $key=>$val){ 
			     $ar_goods_id[$val['goods_id']]=Array('goods_id'=>$val['goods_id']);
			}  
			
			

			$s_stock_where=count($ar_stock_where)? '  AND '.implode(" AND ", $ar_stock_where):"";  
			
			/*查询(进销存)库存出库*/
			$ar_stock_out=$this->getStockGoodInfoByGoodsId($s_stock_where,$flag_name ? $ar_goods_id:Array());/*进销存系统出库记录*/ 
            
			
			if(!is_array($ar_stock_out)){
			   return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_stock_out), date("Y-m-d H:i:s")));
			}
		
			/*所有的出库记录*/  
			$ar_data=Array();
			$ar_goodsid_data=Array();/*商品ID*/
			foreach($ar_stock_out as $v){
					 $ar_goodsid_data[$v['goods_id']]=Array('goods_id'=>$v['goods_id']);
					 if(array_key_exists($v['goods_id'].$v['size'],$ar_data))
						 $ar_data[$v['goods_id'].$v['size']]['sum_number']= intVal($ar_data[$v['goods_id'].$v['size']]['sum_number'])+intVal($v['quantity']);  
					 else
						  $ar_data[$v['goods_id'].$v['size']]=Array(   "goods_id"=>$v['goods_id'],
																	 "goods_name"=>'',
																	   "goods_sn"=>'',
																	 "sum_number"=>$v['quantity'],
																		  "color"=>'',
																		   "size"=>$v['size'],
																	   "order_id"=>$v['stock_out_sn']					 
													  );
			}
			
			unset($ar_stock_out); 

			foreach($ar_goods as $v){
				$ar_goodsid_data[$v['goods_id']]=Array('goods_id'=>$v['goods_id']);
				if(array_key_exists($v['goods_id'].$v['size'],$ar_data))/*是否存在*/
					  $ar_data[$v['goods_id'].$v['size']]['sum_number']= intVal($ar_data[$v['goods_id'].$v['size']]['sum_number'])+intVal($v['sum_number']);  
				else
					  $ar_data[$v['goods_id'].$v['size']]=$v;
			}  
			
			unset($ar_goods);
			
			$ar_stock= $this -> socket_get_goods_stock(' WHERE  1=1 ',' WHERE '); 
			
			if(!is_array($ar_stock)||!count($ar_stock)){
				return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_stock), date("Y-m-d H:i:s")));
			} 
			
			$ar_stock_data=Array();
			foreach($ar_stock as $v){
			  if(array_key_exists($v['goods_id'].$v['size'],$ar_stock_data))
				$ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity']=intVal($ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity'])+intVal($v['sum_quantity']);
			  else
				$ar_stock_data[$v['goods_id'].$v['size']]=$v;
			}
		
			unset($ar_stock); 
			
			$ar_goods_data=$this-> getArrayGoods($ar_goodsid_data); 
	
			if(!is_array($ar_goods_data)||!count($ar_goods_data)){
					return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_goods_data), date("Y-m-d H:i:s")));
			} 

			/*查询库存出库*/ 
			 $res=$this->ArrayDataProcessing($ar_data,$ar_stock_data,$ar_goods_data,$i_curpage,$i_page_size); 

	         $arr_data=Array();
			 $arr_return_goods_data=Array();
			 if(!$i_goods_typeid){
					  foreach($type_list as $k=>$v){
						  if(is_array($res[$k])&&count($res[$k]))
							 $arr_data=array_merge($arr_data,$res[$k]); 
					  }
			 }else{
				  if(is_array($res[$i_goods_typeid])&&count($res[$i_goods_typeid]))
			         $arr_data=$res[$i_goods_typeid]; 
			 }

			 if(!is_array($arr_data)||!count($arr_data)){
			   $arr_data=Array(); 
			 } 
 
			 if(!is_array($res['info'])){
			     return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($arr_data), date("Y-m-d H:i:s")));
		
			 } 
		
			$i_sum=$res['info']['i_instant_sum_num'];  //库存数量
			$i_sum_momey=$res['info']['i_instant_sum_momey']; //库存金额
			foreach($arr_data as $key=>$val){
			   if(!is_array($arr_data[$key])&&!count($arr_data[$key]))
				    continue ;
			   if(intVal($i_sum))
				   $arr_data[$key]['storage_money']= round(floatVal($i_sum_momey),2);
			   if(is_array($arr_data[$key])&&$arr_data[$key]['storage_money'])
				   $arr_data[$key]['goods_turnover']=round(round($arr_data[$key]['sale'],2)/round($arr_data[$key]['storage_money'],2)*100,2);
			   else 
				   $arr_data[$key]['goods_turnover']=0;			    
			} 	 		 
			usort($arr_data,!$i_desc_type ? 'goods_turnover_sort_desc':'goods_turnover_sort_asc'); 
			foreach($arr_data as $key =>$val){
				$arr_data[$key]['type_name']=$type_list[$val['cat_id']]['type_name']; 
			} 
			$arr_return_goods_data['count']=count($arr_data);
			if($arr_return_goods_data['count']){ 
					$temp_curpage=ceil($arr_return_goods_data['count']/$i_page_size);  
					$i_start=intVal((($i_curpage > $temp_curpage ? $temp_curpage:$i_curpage)-1)*intVal($i_page_size));  
					if($i_isall){
						 $i_start=0;
						 $i_page_size=$arr_return_goods_data['count'];
					} 
					if(count($arr_data)){
						 $arr_return_goods_data['data']=array_slice($arr_data,$i_start,$i_page_size,true); 
					}else{
						 $arr_return_goods_data['data']=Array();
					}
			}else{
			  $arr_return_goods_data['data']=Array();
			}

			$arr_return_goods_data['info']=$res['info'];
			
			return $arr_return_goods_data;
	} 


 	/**
	 * 查询所有商品分类记录
	 * @param Array $arr_form_data 查询条件
	 * @param int  $i_curpage 当前页
	 * @param int $i_page_size 页大小
	 * @return |bool 查询所有订单记录,失败返回false
	 */
	public function getGoodsInfoType($ar_form_data,$i_curpage=1,$i_page_size=10)
	{       
		    $type_list=$this->getAllOneCategory();
			if(!is_array($type_list)||!count($type_list)){ 
				  return false; 
			} 
			if(!is_array($ar_form_data)||!count($ar_form_data)){
					return  false;
			}
			$ar_where=Array();
			$ar_stock_where=Array();
			$s_goods_name=addslashes($ar_form_data['s_goods_name']);	
			$i_goods_typeid=intVal($ar_form_data['i_goods_typeid']);
			$i_timebegin=$ar_form_data['i_timebegin'];
			$i_timeend=$ar_form_data['i_timeend'];
			$i_desc_type=$ar_form_data['i_desc_type'];
			$i_page_size=$ar_form_data['i_page_size']; 
			$i_curpage=$ar_from_data['i_curpage'];
			$i_isall=$ar_form_data['i_isall']; 
			$time_end=($i_timeend+(60*60*24-1));//时间调整
			if($i_timebegin && $i_timeend && ($i_timebegin-$i_timeend==0)){
				$i_timebegin and $ar_where[] = ' o.add_time  >='.$i_timebegin.' '  and $ar_stock_where[]=' g.confirm_time  >='.$i_timebegin.' ';
				$i_timeend and $ar_where[] = ' o.add_time  <='.$time_end.' ' and $ar_stock_where[]=' g.confirm_time  <='.$time_end.' ';
 
			}else{
			     $i_timebegin and $i_timebegin < $i_timeend and $ar_where[] = ' o.add_time  >='.$i_timebegin.' ' and $ar_stock_where[]=' g.confirm_time >='.$i_timebegin.' ';
		         $i_timeend and $i_timebegin < $i_timeend and $ar_where[] = ' o.add_time  <='.$time_end.' ' and $ar_stock_where[]=' g.confirm_time <='.$time_end.' ';
			}
			if(!empty($s_goods_name)){
			    $ar_where[] = " og.goods_name  like '%".$s_goods_name."%' "; 
				$s_goodsid_empty="  goods_name like '%".$s_goods_name."%' ";
			}
			$ar_goods_id=Array();
			$flag_name=false;
			if(!empty($s_goodsid_empty)){ 
				   $res_data=$this->getGoodsbyName($s_goodsid_empty);  
					if(!is_array($res_data)){
						 return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail ', date("Y-m-d H:i:s")));
					}
					foreach($res_data as $val){
					   $ar_goods_id[$val['goods_id']]=Array('goods_id'=>$val['goods_id']);
					} 
                   $flag_name=true;
				   
			} 
			if($this-> distribution == null){
				return false;
			} 
		
			$s_where = '';
			if(is_array($ar_where) && count($ar_where) > 0){
				$s_where = join('  AND  ',$ar_where);
			} 
			 
			$ar_goods = $this -> getAllGoods($s_where); //查询所有符合条件的商品  
            
			if(!is_array($ar_goods)){
			     return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail ', date("Y-m-d H:i:s")));
		    } 
			foreach($ar_goods as $key=>$val){ 
			     $ar_goods_id[$val['goods_id']]=Array('goods_id'=>$val['goods_id']);
			}  
               
			/*查询库存出库存*/
			$s_stock_where=count($ar_stock_where)? '  AND '.implode(" AND ", $ar_stock_where):"";   
			$ar_stock_out=$this->getStockGoodInfoByGoodsId($s_stock_where,$flag_name ? $ar_goods_id:Array());
				
			if(!is_array($ar_stock_out)){
			   return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$ar_stock_out, date("Y-m-d H:i:s")));
			} 
			$ar_data=Array();
			$ar_goodsid_data=Array();/*商品ID*/
			foreach($ar_goods as $v){
					 $ar_goodsid_data[$v['goods_id']]=Array('goods_id'=>$v['goods_id']);
					 if(array_key_exists($v['goods_id'].$v['size'],$ar_data))
					  $ar_data[$v['goods_id'].$v['size']]['sum_number']= intVal($ar_data[$v['goods_id'].$v['size']]['sum_number'])+intVal($v['sum_number']);  
					 else
					  $ar_data[$v['goods_id'].$v['size']]=$v;
			} 
			unset($ar_goods); 
			foreach($ar_stock_out as $v){
					 $ar_goodsid_data[$v['goods_id']]=Array('goods_id'=>$v['goods_id']);
					 if(array_key_exists($v['goods_id'].$v['size'],$ar_data))
					  $ar_data[$v['goods_id'].$v['size']]['sum_number']= intVal($ar_data[$v['goods_id'].$v['size']]['sum_number'])+intVal($v['quantity']);  
					 else
					  $ar_data[$v['goods_id'].$v['size']]=Array(   "goods_id"=>$v['goods_id'],
																 "goods_name"=>'',
																   "goods_sn"=>'',
																 "sum_number"=>$v['quantity'],
																	  "color"=>'',
																	   "size"=>$v['size'],
																   "order_id"=>$v['stock_out_sn']					 
												  );
			}
			unset($ar_stock_out); 
			$ar_stock= $this-> socket_get_goods_stock(' WHERE  1=1 ',' WHERE ');	 
			 
			if(!is_array($ar_stock)||!count($ar_stock)){
					return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_stock), date("Y-m-d H:i:s")));
			} 
			$ar_stock_data=Array();
			foreach($ar_stock as $v){ 
			  if(array_key_exists($v['goods_id'].$v['size'],$ar_stock_data))
				$ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity']=intVal($ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity'])+intVal($v['sum_quantity']);
			  else
				$ar_stock_data[$v['goods_id'].$v['size']]=$v;
			}
			unset($ar_stock_out); 
			$ar_goods_data=$ar_goods_info=$this-> getArrayGoods($ar_goodsid_data);  
			if(!is_array($ar_goods_data)||!count($ar_goods_data)){
					return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_goods_data), date("Y-m-d H:i:s")));
			}  
			/*查询库存出库*/ 
			 $res=$this->ArrayDataProcessing($ar_data,$ar_stock_data,$ar_goods_data,$i_curpage,$i_page_size);   
	         $arr_data=Array();
			 $arr_return_goods_data=Array();  
			 if(!is_array($res['info'])){
			     return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.serialize($ar_stock), date("Y-m-d H:i:s")));
		
			 }
		     $i_sum_momey=intVal($res['info']['i_instant_sum_momey']); //总的库存金额 
			  foreach($type_list as $k=>$v){
				  if(is_array($res['info'][$k])&&count($res['info'][$k])){ 
						$arr_data[$k]['goods_id']=$k;
						$arr_data[$k]['typeinfo_name']=$type_list[$k]['type_name']; 
						$arr_data[$k]['sale']=round($res['info'][$k]['i_sale'],2);
						if($res['info']['i_sum_momey']) 
						  $arr_data[$k]['storage_money']=round(round($i_sum_momey,2)/count($type_list),2);	 
						else
						  $arr_data[$k]['storage_money']=0;					
						if( $arr_data[$k]['storage_money']) 
						  $arr_data[$k]['goods_turnover']=round(round($arr_data[$k]['sale'],2)/round($arr_data[$k]['storage_money'],2)*100,2); 
						else
						  $arr_data[$k]['goods_turnover']=0; 
						 
				  }  
			  }
			  
			 if(!is_array($arr_data)||!count($arr_data)){
			   $arr_data=Array(); 
			 } 
			usort($arr_data,!$i_desc_type ? 'goods_turnover_sort_desc':'goods_turnover_sort_asc'); 
			foreach($arr_data as $key =>$val){
				$arr_data[$key]['type_name']=$type_list[$val['cat_id']]['type_name'];  
			} 
		 
			$arr_return_goods_data['info']=$res['info'];
			$arr_return_goods_data['data']=$arr_data;  
			return $arr_return_goods_data;
	} 


	 
    /**
	 * @param String $s_where 查询条件 
	 * @return |bool 查询所有订单商品记录,失败返回false
	 */ 
	public function  getAllGoods($s_where='')
	{  
		if($this->distribution===null){
		  return false;
		}
		$s_where = !empty($s_where) ? ' AND '.$s_where : ''; 
		$sql=" SELECT og.order_id, og.goods_id,og.goods_name,og.goods_sn,og.goods_number,og.color,og.size,sum(og.goods_number) as  sum_number "
			." FROM  order_info o,order_goods og  "
			." WHERE  og.order_id=o.order_id AND  o.order_status=1  AND  og.extension_code =' ' AND og.goods_number>0 ".$s_where
			." GROUP BY  og.goods_id "
			." ORDER BY o.add_time  ";  
		
		
		$res = $this-> distribution
					-> clear()
					-> getArray($sql); 
		
		if($res === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$res, date("Y-m-d H:i:s")));
		}
		
	    return $res;
	} 

	/**
	 * @param String $s_where 查询条件 
	 * @return |bool 查询所有指定某个名字的商品,失败返回false
	 */ 
	public function  getGoodsbyName($s_where='')
	{  
		if($this->db===null){
		  return false;
		}
		if(empty($s_where)){
		  return false;
		}
		$sql=" SELECT goods_id "
			." FROM  ecs_goods  "
			." WHERE  ".$s_where; 
		$res = $this-> db
					-> clear()
					-> getArray($sql); 
		if($res === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$sql, date("Y-m-d H:i:s")));
		} 
		   return $res;
	} 

     /*库存出货*/
        
    /**
	 * 查询所有库存出库的商品
	 * @param String $s_where 查询条件的商品条件SQL  没有则传  '';
	 * @param Array $ar_goods_id 商品id数组
	 * @return |bool 返回商品信息,失败返回false
	 */
	public function getStockGoodInfoByGoodsId($s_where="",$ar_goods_id=Array())
	{   
		if($this->stock===null){
		  return false;
		}	
		if(!is_array($ar_goods_id)){
			return	false;
		} 
		$s_where=!empty($s_where)?$s_where:"";  
		if(count($ar_goods_id)){
			$ar_goods_id_temp=Array();
            foreach($ar_goods_id as $val){
			   $ar_goods_id_temp[]="  gd.goods_id=".$val['goods_id']." ";
			}
		   $s_where.=count($ar_goods_id_temp)?" AND (".implode(" OR ", $ar_goods_id_temp). " ) ":" ";
		}
		$sql="  SELECT g.stock_out_sn, gd.goods_id, gd.size, gd.quantity, g.confirm_time   "
			 ." FROM   goods_stock_out  g ,goods_stock_out_details gd "
			 ." WHERE  gd.stock_out_id=g.stock_out_id AND g.stock_out_type>5 AND g.confirm_status=1 ".$s_where; 
		$arr_goods = $this -> stock
						   -> clear()
						   -> getArray($sql);
						   
		if($arr_goods === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$res, date("Y-m-d H:i:s")));
		} 
		return $arr_goods;
	}         



	 /*库存出货*/
    /**
	 * 查询某个分类下的所有商品 根据分类ID  
	 *
	 * @param int $i_good_id 商品id
	 * @return |bool 返回商品信息,失败返回false
	 */
	public function getGoodInfoByTypeId($i_type_id=0)
	{ 
		if($this-> db === null){
			return false;
		}
		if(!intVal($i_type_id)){
			return false;
		}
		$sql="  SELECT distinct(eg.goods_id),eg.goods_name,eg.shop_price "
			 ." FROM ecs_category ec,ecs_goods eg "
			 ." WHERE  eg.cat_id = ec.cat_id AND ec.parent_id=".intVal($i_type_id);			 
		$arr_goods = $this -> db
						   -> clear()
						   -> getArray($sql);
		if($arr_goods === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$res, date("Y-m-d H:i:s")));
		} 
		
		return $arr_goods;
	}

	/**
	 * 查询某个分类下的所有商品 根据分类ID  
	 *
	 * @param int $i_good_id 商品id
	 * @return |bool 返回商品信息,失败返回false
	 */
	public function getArrayTypeIdById($i_type_id=0)
	{
		if($this-> db == null){
			return false;
		}
		if(!intVal($i_type_id)){
			return false;
		}
		$sql=" SELECT cat_id "
			 ." FROM ecs_category  "
			 ." WHERE  cat_id=".intVal($i_type_id)." OR parent_id = ".intVal($i_type_id);  
		$arr_goods = $this -> db
						   -> clear()
						   -> getArray($sql);
		if($arr_goods === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$res, date("Y-m-d H:i:s")));
		} 
		return $arr_goods;
	}

    /**
	 * 查询所有一级分类  
	 * @return |bool 返回商品信息,失败返回false
	 */
	public function getAllOneCategory()
	{    
		
		$key='type_list';
		//从缓存中获取数据
		$category_data = $this->fileCache->get($key);

		if(is_array($category_data)&&count($category_data))
		  return $category_data;
		$category_data=Array();
		if($this-> db == null){
		   return false;
		}
		$sql=" SELECT cat_id  as type_id, cat_name as type_name  "
			 ." FROM ecs_category  "
			 ." WHERE   parent_id = 0  AND is_show AND cat_id<>411";   
		$arr_category = $this -> db
							  -> clear()
							  -> getArray($sql);
		if($arr_category === false){
			return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$sql, date("Y-m-d H:i:s")));
		}

		if(is_array($arr_category)&&count($arr_category)){
		  foreach($arr_category as $v){
		     $category_data[$v['type_id']]=$v;
		  }
		}
		if(!count($category_data)){
		  return false;
		} 
		$this->fileCache->set($key,$category_data,60*5);
		return $category_data;
	}
     /**
	   *
	   *计算商品数量
	   */
	public function col_sum($array,$col) 
	{ 
	  $sum=0;
	  foreach($array as $key=>$val) 
		$sum += $val[$col]; 
	  return $sum;
	}

  /**
	 * 查询所有商品ID
	 * @param Array $arr_goods_id 商品ID 数组
	 * @return array|bool 成功返回仓库数组,失败返回false
	 */
   public function getArrayGoods($arr_goods_id=Array())
   {          
		    if ($this->db == null) return false;
			if(!is_array($arr_goods_id)||!count($arr_goods_id)){
			  return false; 
			}
			$arr_temp=Array();
			foreach($arr_goods_id as $key => $val){
				$arr_temp[]=' g.goods_id='.intVal($val['goods_id']); 
			}
			$s_temp=implode(' OR ',$arr_temp);
			$s_where.=!empty($s_temp)? 'AND ('.$s_temp.') ': ' ';
			$sql = "  SELECT  g.goods_id,g.cat_id,g.goods_sn,g.goods_name,g.shop_price, c.parent_id "
					 ." FROM lyceem.ecs_category c,ecs_goods g "
					 ."WHERE  g.cat_id = c.cat_id ".$s_where; 					 
			$res = $this->db-> getArray($sql); 
			if( $res === false ){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
			}
			
 			$ar_goods_price=$this->getArrayGoodsStockPrice($arr_goods_id);
			if(!is_array($ar_goods_price)){
					return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$ar_goods_price, date("Y-m-d H:i:s")));
			}
			$ar_goods_price_temp=Array();
			foreach($ar_goods_price as $v){
					$ar_goods_price_temp[$v['goods_id']]=$v; 
			}
			unset($ar_goods_price);
			foreach($res as $k=>$v){
				   if(array_key_exists($v['goods_id'],$ar_goods_price_temp))
					   $res[$k]['avg_shop_price']=$ar_goods_price_temp[$v['goods_id']]['avg_cost_price'];
				   else
					   $res[$k]['avg_shop_price']=0; 
			} 
		    return $res;
    } 
    /**
	 * 查询所有商品ID
	 * @param Array $arr_goods_id 商品ID 数组
	 * @return array|bool 成功返回仓库数组,失败返回false
	 */
   public function getArrayGoodsStockPrice($arr_goods_id=Array())
   {            
		    if ($this->stock == null) return false;
			if(!is_array($arr_goods_id)||!count($arr_goods_id)){
			  return false; 
			}
			$arr_temp=Array();
			foreach($arr_goods_id as $key => $val){
				$arr_temp[]=' b.goods_id='.intVal($val['goods_id']); 
			}
			$s_temp=implode(' OR ',$arr_temp);
			$s_where.=!empty($s_temp)? 'AND ('.$s_temp.') ': ' ';
			$sql = "  SELECT batch_id,goods_id,size,cost_price, avg(cost_price) as avg_cost_price "
					 ." FROM batch_details b  "
					 ."  WHERE  1 ".$s_where
					 ." GROUP BY goods_id "	 ; 					 
			$res = $this->stock-> getArray($sql); 
			if( $res === false ){
				return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
			} 
		    return $res;
    }
	
 
	 
  /**
	 * 处理商品周转率
	 * $param Array $ar_buy   已出库的商品
	 * $param Array $ar_stock 库存数量
	 * $param Array $ar_goods 商品信息
	 * @return array|bool 成功返回仓库数组,失败返回false
	 */
   public function ArrayDataProcessing($ar_buy=Array(),$ar_stock=Array(),$ar_goods=Array())
   {      
		   if(!is_array($ar_buy)||!is_array($ar_stock)||!is_array($ar_goods)) return Array();		         
		    $type_list=$this->getAllOneCategory();

			if(!is_array($type_list)||!count($type_list)){ 
				  return false; 
			} 
		   $temp_ar_goods=Array();   
		   foreach($ar_goods as $k=>$v){
			  $temp_ar_goods[$v['goods_id']]=$v;
		   } 
		   $arr_data=array('info'=>Array()); 
		   $arr_instant_stock=$this->getAllGoodsSotckCount();
			if(!is_array($arr_instant_stock)){
				return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$ar_stock, date("Y-m-d H:i:s")));
			}
		  $arr_data['info']['i_instant_sum_num']=$arr_instant_stock['i_sum_number'];
		  $arr_data['info']['i_instant_sum_momey']=$arr_instant_stock['i_sum_money']; 
		   foreach($ar_buy as $k=>$v){
				 if(!array_key_exists($v['goods_id'],$temp_ar_goods))//去调多余的商品
				   unset($ar_buy[$k]); 
				 if(array_key_exists($v['goods_id'].$v['size'],$ar_stock))
					  $ar_buy[$k]['storage_number']=intVal($ar_buy[$k]['storage_number'])+intVal($ar_stock[$v['goods_id'].$v['size']]['sum_quantity']);
				 if(array_key_exists($v['goods_id'],$temp_ar_goods))
					  $ar_buy[$k]['goods_newname']=$temp_ar_goods[$v['goods_id']]['goods_name'] AND $ar_buy[$k]['goods_sn']=$temp_ar_goods[$v['goods_id']]['goods_sn']	AND
					  $ar_buy[$k]['cat_id']=$temp_ar_goods[$v['goods_id']]['parent_id']  ;  
				 if(1)
					$ar_buy[$k]['sale']=round(intVal($ar_buy[$k]['sum_number'])*round($temp_ar_goods[$v['goods_id']]['shop_price'],2),2);
				 if(1)
					$ar_buy[$k]['storage_money']=round(floatVal($ar_buy[$k]['storage_number'])*floatVal($temp_ar_goods[$v['goods_id']]['avg_shop_price']),2);
				 if(1)
					$ar_buy[$k]['avg_shop_price']= floatVal($temp_ar_goods[$v['goods_id']]['avg_shop_price']);
				 if(1)
					$ar_buy[$k]['shop_price']= floatVal($temp_ar_goods[$v['goods_id']]['shop_price']);
				 if(array_key_exists($ar_buy[$k]['cat_id'],$type_list))
					$arr_data[intVal($ar_buy[$k]['cat_id'])][]=$ar_buy[$k];
				 if(1)
				   $arr_data['info']['i_sum']+=intVal($ar_buy[$k]['storage_number']);
				 if(1)
				   $arr_data['info']['i_sum_momey']+=round($ar_buy[$k]['storage_money'],2);
				 if(1)
				   $arr_data['info']['i_sum_sale_number']+=intVal($ar_buy[$k]['sum_number']);
				 if(1)
				   $arr_data['info']['i_sum_sale_momey']+=round($ar_buy[$k]['sale'],2);
		   }
		  unset($ar_buy);

		 //服装  
		 if($type_list){
				 foreach($type_list as $k=>$v)
						 if(is_array($arr_data[$k]))
							 foreach($arr_data[$k] as $key=>$val){ 
								  $arr_data['info'][$k]['i_sum_number']+=intVal($val['storage_number']);
								  $arr_data['info'][$k]['i_sum']+=round($val['storage_money'],2); 
								  $arr_data['info'][$k]['i_sale']+=round($val['sale'],2);
								  $arr_data['info'][$k]['i_sale_number']+=intVal($val['sum_number']); 
							  }
		 } 
 
		 return $arr_data;

    }



    /**
	 * 查询所有库存商品的金额数量
	 * @param Array $arr_goods_id 商品ID 数组
	 * @return array|bool 成功返回仓库数组,失败返回false
	 */
   public function getAllGoodsSotckCount()
   {            
			$ar_stock= $this-> socket_get_goods_stock(' WHERE  1=1 ',' WHERE '); 
			if(!is_array($ar_stock)||!count($ar_stock)){
				return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$ar_stock, date("Y-m-d H:i:s")));
			} 
			$ar_stock_data=Array();
			$ar_goodsid_data=Array();
			foreach($ar_stock as $v){
				$ar_goodsid_data[$v['goods_id']]=Array('goods_id'=>$v['goods_id']);
			  if(array_key_exists($v['goods_id'].$v['size'],$ar_stock_data))
				$ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity']=intVal($ar_stock_data[$v['goods_id'].$v['size']]['sum_quantity'])+intVal($v['sum_quantity']);
			  else
				$ar_stock_data[$v['goods_id'].$v['size']]=$v;
			}
			unset($ar_stock); 
			$ar_goods_data=Array();
			$ar_goods=$this-> getArrayGoodsStockPrice($ar_goodsid_data); 	
			if(!is_array($ar_goods)||!count($ar_goods)){
					return $this->_log(array(__CLASS__.'.class.php line '.__LINE__,'function '.__FUNCTION__.' result is fail '.$ar_stock, date("Y-m-d H:i:s")));
			}
			foreach($ar_goods as  $v){
			  $ar_goods_data[$v['goods_id']]=$v;
			}
			unset($ar_goods); 
 		$arr_return_data=Array('i_sum_number'=>0,'i_sum_money'=>0);
			foreach($ar_stock_data as $k=>$v){
			   if($ar_stock_data[$k]['sum_quantity'])
			      $arr_return_data['i_sum_number']+=intVal($ar_stock_data[$k]['sum_quantity']);
			   if(array_key_exists($v['goods_id'],$ar_goods_data)&&$ar_stock_data[$k]['sum_quantity'])
                $arr_return_data['i_sum_money']+=round(round($ar_goods_data[$v['goods_id']]['avg_cost_price'],2)*$ar_stock_data[$k]['sum_quantity'],2);			   
			}
			return $arr_return_data;

   }
   
   
    /**
     * 获取订单商品
     *
     * @param Integer $i_orderid  订单编号
     * @return Array|False
     */
    public function getOrderGoods($i_orderid)
    {  
    	if ($this->distribution == null) return false;
		$fields='goods_id,goods_number,color,size';
    	if ( is_array($i_orderid) && count($i_orderid))
		{
			$sql = "SELECT $fields FROM lyceem_distribution.order_goods WHERE order_id in (".implode(',',$i_orderid).") ORDER BY order_id DESC";
		}
		else
		{
			$i_orderid = (int)$i_orderid;
			if($i_orderid < 1)
				return $this->_log(array( __CLASS__.'.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' user_id is null' , date("Y-m-d H:i:s")));
	 		$sql = "SELECT $fields FROM lyceem_distribution.order_goods WHERE order_id = $i_orderid ORDER BY goods_sn DESC";
		}
		$res = $this->distribution->getArray($sql);
		if($res === false)
			return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		return $res;
    }
    
    
    /**
	 *根据条件订单确认表
	 *@param string $start    开始条数
	 *@param string $end      结束条数
	 */
	public function getchecklist($check)
	{   
		if ($this->distribution == null) return false;
		
		$sql = "SELECT order_id FROM lyceem_distribution.order_info " .
				"WHERE $check " .
				"order by add_time desc";
		$r = $this->distribution->getArray($sql);
		if( $r === false )
		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		$ar['order'] = $r;
		$sql = "SELECT count(order_id) FROM lyceem_distribution.order_info WHERE $check";
		
		$r = $this->distribution->getValue($sql);
		if( $r === false )
		return $this->_log(array( __CLASS__ . '.class.php line ' . __LINE__ , 'function '. __FUNCTION__ . ' sql Error : '.$sql, date("Y-m-d H:i:s")));
		$ar['count'] = $r;
		return $ar;
	}
	
	/**
	 * 获取商品最大Id
	 */
	public function getGoodsMaxid()
	{
		if ($this->distribution == null) return false;
		$sql = "SELECT MAX(goods_id) FROM lyceem_distribution.order_goods";
		$res = $this->distribution->getValue($sql);
		return $res;
	}
	
	/**
	 * 获取商品ID
	 */
	public function getAllGoodsid()
	{
		if($this->db == null)  return false;
		$sql = "SELECT goods_id FROM `ecs_goods`";
		$res = $this->db->getColumn($sql);
		
		return $res;
	}
 	
	/**
	 * 获取商品的尺寸
	 */
	public function getAllsize($goods_id)
	{
		if($this->db == null)  return false;
		$sql = "SELECT size FROM `ecs_goods_unique` WHERE goods_id = .$goods_id";
		$r = $this->db->getColumn($sql);
		//$arr[$res['goods_id']][] = $res['size'];
		$res[$goods_id] = $r;
		return $res;
		
	}
	
	/**
	 * 提货出库
	 */
	public function getStockout_goodsnum($where,$goods_name)
	{
		if($this->stock == null || $this->db == null)  return false;
		$goods_name = trim($goods_name);
		//echo $where;
		$sql = "SELECT gsod.goods_id,gsod.size,sum(gsod.quantity) as quantity,gso.confirm_time from stock.goods_stock_out as gso LEFT JOIN stock.goods_stock_out_details as gsod ON gso.stock_out_id = gsod.stock_out_id WHERE";
		if($goods_name)
		{
			$sql_s = "SELECT goods_id  FROM lyceem.ecs_goods WHERE goods_name LIKE '%$goods_name%'";
			$goodsid = $this->db->getArray($sql_s);
			foreach ($goodsid as $key=>$val)
			{
				$str .= $val['goods_id'].',';
			}
			$str = rtrim($str,',');
			$sql .= " goods_id IN ($str) AND ";
		}

		$sql  .= " gso.confirm_status =1 AND gso.stock_out_type IN (5,6,7,8) AND ";
		$sql  .= $where;		
		$sql_1 = "SELECT stock_out_sn FROM goods_stock_out WHERE ".$where." AND confirm_status =1 AND stock_out_type = 4";
		$sql_2 = "SELECT stock_out_id FROM goods_stock_out WHERE  stock_out_type = 7 AND ".$where." AND confirm_status =1 
                  AND stock_out_sn NOT IN ($sql_1) ";
		$sql  .= " AND gso.stock_out_id IN ($sql_2)";
		$sql  .= " GROUP BY goods_id,size";

		$res = $this->stock->getArray($sql);
		return $res;
	} 
	
	/**
	 * 设置在途数量
	 */
	public function SetOn_Ship_num($goods_id,$size,$num_ship,$type)
	{
		if($this->db == null)  return false;
		if($type == 'ship')
		{
			$sql = "UPDATE ecs_goods_unique SET on_transport = '$num_ship' WHERE goods_id = '$goods_id' AND size = '$size'";
		}
		else if($type == 'ahead_day')
		{
			$sql = "UPDATE ecs_goods_unique SET ahead_time = '$num_ship' WHERE goods_id = '$goods_id' AND size = '$size'";
		}
	    else if($type == 'minimum')
		{
			$sql = "UPDATE ecs_goods_unique SET minimum = '$num_ship' WHERE goods_id = '$goods_id' AND size = '$size'";
		}
		else 
		{
			$sql = "UPDATE ecs_goods_unique SET warning_stock = '$num_ship' WHERE goods_id = '$goods_id' AND size = '$size'";
		}
		//echo $sql;exit;
		return $this->db->exec($sql);
	}
	
	/**
	 * 获取在途数量、提前期、最小批量、预警库存
	 */
	public function getOnTrsnport($goods_id,$size)
	{
		if($this->db == null) return false;
		$sql = "SELECT on_transport,ahead_time,minimum,warning_stock FROM lyceem.ecs_goods_unique WHERE goods_id = '$goods_id' AND size = '$size'";
		$res = $this->db->getArray($sql);
		return $res;
	}
    
 	/** 
	 *	
	 *
	 * 获取不同仓库的商品库存(socket)
	 * 
	 * @param string $where 查询条件 ($where = where 1)
	 * @param string $act   查询方式（where 按sql 的where语句查询）
	 * @return array 
	 */
	function socket_get_goods_stock($where,$act='where'){
		$url    = SOCKET_REQUEST_URL.'/stock.php?do=getGoodsStock';	

		$limit  = 0; 
		$post   = serialize($where.'@'.$act);		
		$cookie = '';
		$bysocket = false;
		$ip       = '';
		$timeout  = 15;
		$block    = true; 
		import("util.HttpServer"); 
		$httpserver = new HttpServer;
		$s = $httpserver->dfopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block);

		//解析数据	
		$ar =  json_decode($s,true);//echo '<pre>';print_r($ar);//die;
		if(empty($ar) || !is_array($ar)){
			return array();
		} 
		
		if(!$ar['status'] || !is_array($ar['info'])){
			return array();
		} 
		 return $ar['info'];
	}  
	/**/
   
     
 /**
	 * 数据更新失败记录日志，并标识操作失败
	 *
	 * @param Array $data
	 * @return false
	 */
	private function _log($data)
	{
	    $log = $this->app->log();
	    $log->reset()->setPath("orderStatisticsinfo")->setData($data)->write();
	    return false;
	}
}
    /*定义排序*/
	 function goods_turnover_sort_asc($x,$y)
		{ 
		if(floatVal($x['goods_turnover']) == floatVal($y['goods_turnover'])) 
          return 0; 
        else if(floatVal($x['goods_turnover']) < floatVal($y['goods_turnover'])) 
          return -1; 
        else 
          return 1;
    } 

	    /*定义排序*/
	 function goods_turnover_sort_desc($x,$y)
		{ 
		if(floatVal($x['goods_turnover']) == floatVal($y['goods_turnover'])) 
          return 0; 
        else if(floatVal($x['goods_turnover']) < floatVal($y['goods_turnover'])) 
          return 1; 
        else 
          return -1;
    } 
    
    

    
    
?>