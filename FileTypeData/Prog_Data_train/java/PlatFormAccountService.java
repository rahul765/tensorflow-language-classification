package com.guoleMIS.service.finance;

import java.util.List;

import com.guoleMIS.vo.BillVO;

public interface PlatFormAccountService {
	//平台总账
	String getPlatFormTotal();
	//平台金额
	String getPlatFormMoney();
	//虚拟账户总金额
	String getUserAccountMoney();
	//平台流水
	List<BillVO> getPlantFormBill(int indexPage,String timeRange,int type);
	//平台流水总数
	int getPlantFormBillCountPage(String timeRange,int type);
}
