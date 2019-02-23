package com.fcs.platform.mapper;

/**
 * Created by Lucare.Feng on 2016/5/24.
 * 通过这个桥梁可以随时更换id类型
 */
public interface SuperMapper<T> extends BaseMapper<T,String> {
}
