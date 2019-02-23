package test;

import test.rxjava.RxJavaRunner;
import org.apache.kafka.clients.consumer.ConsumerRecords;
import org.apache.kafka.clients.consumer.KafkaConsumer;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.CommandLineRunner;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.context.annotation.Bean;

@SpringBootApplication
public class KafkaApplication {
    private final RxJavaRunner rxJavaRunner;

    @Autowired
    public KafkaApplication(RxJavaRunner rxJavaRunner) {
        this.rxJavaRunner = rxJavaRunner;
    }

    public static void main(String[] args) {
        SpringApplication.run(KafkaApplication.class, args);
    }

    @Bean
    @SuppressWarnings("unchecked")
    public CommandLineRunner run(KafkaConsumer kafkaConsumer) {

        return args -> {
            kafkaConsumer.seekToBeginning(kafkaConsumer.assignment());
            while (true) {
                ConsumerRecords<String, String> records = kafkaConsumer.poll(3000);
                System.out.println("Records read: " + records.count());

                if (records.count()!= 0) rxJavaRunner.run(records);

            }
        };

    }
}
