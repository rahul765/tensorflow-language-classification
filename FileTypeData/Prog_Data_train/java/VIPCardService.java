package com.guole.service.vip;

import java.util.List;
import java.util.Map;

import com.guole.vo.GiftCardTypeVO;
import com.guole.vo.GiftCardVO;

public interface VIPCardService {

	/**
	 * 获取全部的礼品卡类型
	 */
	public Object[] getVIPCards(int page);

}
