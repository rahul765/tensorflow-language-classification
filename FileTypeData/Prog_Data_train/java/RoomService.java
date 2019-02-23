package com.epam.chat.service;

import com.copyright.rup.chat.common.Room;

import org.apache.http.HttpResponse;

public class RoomService extends RestService<Room> {

    public static final String entityName = "room/";
    public static final String host = "http://localhost:8082/";

    @Override
    protected String getEntityName() {
        return entityName;
    }

    @Override
    protected String getHost() {
        return host;
    }

    public static void main(String[] args) {
        RoomService serv = new RoomService();
        serv.create(null);
        serv.get("5");
    }

    @Override
    protected Room parseJson(HttpResponse resultText) {
        return new Room();
    }

    @Override
    protected String buildJson(Room entity) {
        return "{\"id\":5,\"name\":\"qwerty\",\"description\":\"asdfgh\"}";
    }
}
