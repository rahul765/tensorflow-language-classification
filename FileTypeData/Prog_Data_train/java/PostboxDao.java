package com.guole.dao.notify;

import java.util.List;
import java.util.Map;

import com.guole.vo.MessageVO;

public interface PostboxDao {
	/**
	 * 添加信息
	 * @param message 信息实体
	 * @return boolean 成功标识
	 */
	public int insertMessage(MessageVO message);
	
	/**
	 * 删除指定信息
	 * @param id
	 */
	public void delMessage(Map<String,Object> params);

	/**
	 * 查询消息数量
	 * @param conditions
	 * @return
	 */
	public int queryMessageCount(int userId);
	/**
	 * 获取指定页信息
	 * @param pageIndex
	 * @return
	 */
	public List<MessageVO> queryMessage(Map<String,Object> params);
}
