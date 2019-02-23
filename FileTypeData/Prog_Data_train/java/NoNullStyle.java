package com.lzbruby.lang;

import org.apache.commons.lang.builder.ToStringStyle;

import java.io.Serializable;

/**
 * 功能描述：重写ToStringStyle，不打印出对象的空属性字段
 * <p/>
 * null 不打印
 * “” 打印
 *
 * @author: Zhenbin.Li
 * email： lizhenbin08@sina.cn
 * company：org.lzbruby
 * Date: 15/8/5 Time: 11:21
 */
public class NoNullStyle extends ToStringStyle implements Serializable {

    private static final long serialVersionUID = 2347542971151578670L;

    @Override
    public void append(StringBuffer buffer, String fieldName, Object value, Boolean fullDetail) {
        if (value != null) {
            super.append(buffer, fieldName, value, fullDetail);
        }
    }
}
