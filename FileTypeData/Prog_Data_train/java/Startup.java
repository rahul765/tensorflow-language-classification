package com.spring.integration.channel;

import org.springframework.context.support.ClassPathXmlApplicationContext;

/**
 *
 * @author ajay
 */

public class Startup {

    @SuppressWarnings("resource, unused")
    public static void main(String args[]) {
        /*
        * This classPathXmlApplicationContext is used for spring classpath looks for the resource folder which we mention.
        * */
        ClassPathXmlApplicationContext context = new ClassPathXmlApplicationContext("/META-INF/spring/si-component.xml");

        while (true) {
            // This is used for run the jvm for infinite time
        }

    }
}
