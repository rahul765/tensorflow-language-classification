package org.cassandrademo.example.pojo;

/**
 * @author: legezadm
 * @since: 21.07.13
 */
public enum UserEnum {

    USER_NAME("name"), USER_SURNAME("surname"), USER_PHONE("phoneNumber");

    private String value;


    UserEnum(String phoneNumber) {
        this.value = phoneNumber;
    }

    public String getValue() {
        return value;
    }
}
