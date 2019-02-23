package com.guoleMIS.dao.member;

import java.util.List;

import com.guoleMIS.vo.NotifyVO;

/**
 * 消息信息持久层接口
 * @author zhenhua.kou
 * @version V1.0
 * @createTime   2013-06-06
 * @history  版本	修改者	   时间	修改内容
 */
public interface NotifyDao {
	
	/**
     * 获取消息信息列表
     * @param filter 查询条件
     * @return 
     */
    public List<NotifyVO> loadNotifys(String conditions);
    
    /**
     * 获取消息信息记录数
     * @param filter 查询条件
     * @return 
     */
    public int getNotifyInfosCount(String conditions);
    
    /**
     * 发送消息信息
     * @param notifyVO 消息信息
     * @return 
     */
    public boolean publishNotify(NotifyVO notifyVO);

}
