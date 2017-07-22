// 导航栏配置文件
var outlookbar=new outlook();
var t;

//配置进销存管理
t=outlookbar.addtitle('采购管理' , '系统设置' , 1)
outlookbar.additem('采购管理' , t , '../purchase.php?do=PurchaseList&act=main')


t=outlookbar.addtitle('出库申请' , '系统设置' , 1)
outlookbar.additem('分销商出库申请管理' , t , '../stockout.php?act=request&user=distributor')
outlookbar.additem('供应商产品退货申请管理' , t , '../stockout.php?act=request&user=supplier')
outlookbar.additem('员工提货申请管理' , t , '../stockout.php?act=request&user=emp')
outlookbar.additem('损益申请管理' , t , '../stockout.php?act=request&user=lose')
outlookbar.additem('不良品申报管理' , t , '../stockout.php?act=request&user=bad')


t=outlookbar.addtitle('出入库管理' , '系统设置' , 1)
outlookbar.additem('供应商产品入库管理' , t , '../stockin.php?act=main&user=supplier')
outlookbar.additem('员工提货出库入库管理' , t , '../stockin.php?act=main&user=emp')
outlookbar.additem('分销商产品退货入库管理' , t ,'../stockin.php?act=main&user=distributor')
outlookbar.additem('供应商产品出库管理' , t , '../stockout.php?act=main&user=supplier')
outlookbar.additem('员工提货出库管理' , t ,'../stockout.php?act=main&user=emp')
outlookbar.additem('分销商产品退货出库管理' , t ,'../stockout.php?act=main&user=distributor')


t=outlookbar.addtitle('库存管理' , '系统设置' , 1)
outlookbar.additem('仓库盘点' , t , '../checkstock.php?do=AgencyCheck')
outlookbar.additem('盘点管理' , t , '../checkstock.php')
outlookbar.additem('库存统计' , t , '../stockcount.php')
outlookbar.additem('库存调拨' , t , '../stock.php?do=StockAllocate')
outlookbar.additem('库存预告商品' , t , '../stock.php')

t=outlookbar.addtitle('商品统计' , '系统设置' , 1)
outlookbar.additem('当前库存统计' , t , '../stockcount.php?do=GoodsCount')
outlookbar.additem('时间段出入库统计' , t , '../stockcount.php?do=InOutCount')

t=outlookbar.addtitle('权限管理' , '系统设置' , 1)
outlookbar.additem('管理员列表' , t , '../privilege.php')
outlookbar.additem('添加管理员' , t , '../privilege.php?do=EditAdmin')
outlookbar.additem('管理员日志' , t , '../privilege.php')



//
t=outlookbar.addtitle('退出系统','管理首页',1)
outlookbar.additem('点击退出登录',t,'../user.php?do=logout')