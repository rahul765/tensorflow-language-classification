package me.roybailey.springboot.kafka;

import lombok.extern.slf4j.Slf4j;
import org.springframework.kafka.annotation.KafkaListener;

import java.util.concurrent.LinkedBlockingQueue;
import java.util.concurrent.TimeUnit;


@Slf4j
public class KafkaSampleConsumer {

    private LinkedBlockingQueue<String> events = new LinkedBlockingQueue<>();

    @KafkaListener(topics = "helloworld.t")
    public void receiveMessage(String message) {
        log.info("received message='{}'", message);
        events.offer(message);
    }

    public String getMessage(int seconds) throws InterruptedException {
        log.info("polling for event");
        String result = events.poll(seconds, TimeUnit.SECONDS);
        log.info("obtained event='{}'", result);
        return result;
    }

}
