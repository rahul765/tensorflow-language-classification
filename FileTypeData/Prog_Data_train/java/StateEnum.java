package org.cassandrademo.example.pojo;

/**
 * @author: legezadm
 * @since: 21.07.13
 */
public enum StateEnum {
    STATE_NAME("stateName"), ZIP_CODE("zipCode");

    private String value;

    StateEnum(String stateName) {
        this.value = stateName;
    }

    public String getValue() {
        return value;
    }
}
