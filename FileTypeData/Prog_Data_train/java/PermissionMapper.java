package com.fcs.admin.mapper;

import com.fcs.admin.model.MenuTree;
import com.fcs.platform.mapper.SuperMapper;
import org.apache.ibatis.annotations.Param;

import java.util.List;

/**
 * Created by Lucare on 2016/1/29.
 */
public interface PermissionMapper extends SuperMapper<MenuTree>{

    List<MenuTree> selectMenuByUserId(@Param("userId") String userId, @Param("pid") String pid);

    List<MenuTree> selectPerByUserId(String userId);

    List<String> selectPerUrlByUserId(String userId);

    List<MenuTree> getMenuList(@Param("pid") String pid, @Param("type") int type);

    List<MenuTree> getPermissionList(String pid);

    List<MenuTree> getPermissionsByRole(String roleId);
}
