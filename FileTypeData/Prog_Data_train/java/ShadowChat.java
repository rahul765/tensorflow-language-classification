package com.davespanton.nutbar.shadows;

import com.xtremelabs.robolectric.internal.Implementation;
import com.xtremelabs.robolectric.internal.Implements;
import com.xtremelabs.robolectric.internal.RealObject;
import org.jivesoftware.smack.Chat;

import java.util.ArrayList;

@Implements(Chat.class)
public class ShadowChat {

    private ArrayList<String> sentMessages = new ArrayList<String>();

    @RealObject
    private Chat chat;

    @Implementation
    public void sendMessage(String text) {
        sentMessages.add(text);
    }

    public boolean hasSentMessage(String text) {
        return sentMessages.contains(text);
    }
}
