package me.roybailey.springboot.kafka;

import lombok.extern.slf4j.Slf4j;
import me.roybailey.springboot.domain.Event;
import org.springframework.kafka.annotation.KafkaListener;

import java.util.concurrent.LinkedBlockingQueue;
import java.util.concurrent.TimeUnit;


@Slf4j
public class KafkaEventConsumer {

    private LinkedBlockingQueue<Event<?>> events = new LinkedBlockingQueue<>();

    @KafkaListener(topics = "eventdriven.t", containerFactory = "eventKafkaListenerContainerFactory")
    public void receiveMessage(Event<?> event) {
        log.info("received event='{}'", event);
        events.offer(event);
    }

    public <T> Event<T> getEvent(int seconds) throws InterruptedException {
        log.info("polling for event");
        Event<?> result = events.poll(seconds, TimeUnit.SECONDS);
        log.info("obtained event='{}'", result);
        return (Event<T>) result;
    }
}
