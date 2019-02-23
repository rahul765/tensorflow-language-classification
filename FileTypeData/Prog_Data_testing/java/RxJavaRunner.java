package test.rxjava;

import test.rxjava.db.model.Processed;
import test.rxjava.model.DataItem;
import test.rxjava.service.DbWriterObserver;
import io.reactivex.Observable;
import org.apache.kafka.clients.consumer.ConsumerRecord;
import org.apache.kafka.clients.consumer.ConsumerRecords;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import java.util.Date;

@Component
public class RxJavaRunner {

    @Autowired
    private DbWriterObserver dataItemObserver;

    public void run(ConsumerRecords<String, String> records) throws InterruptedException {
        Observable<Date> dateObservable = Observable.range(1, 100).map(tick -> new Date());
        Observable<Integer> sequenceObservable = Observable.range(1, 1000);
        Observable<ConsumerRecord<String, String>> consumerRecordObservable = Observable.fromIterable(records);

        for (ConsumerRecord<String, String> record : records) {
            System.out.printf("offset = %d, key = %s, value = %s%n", record.offset(), record.key(), record.value());

        }
        Observable<DataItem> itemsObservable = consumerRecordObservable
                .filter(record -> record.value() != null && !record.value().isEmpty()).map(this::toDataItem).map(this::printItem)
                .zipWith(dateObservable, this::setDateInItem).zipWith(sequenceObservable, this::setSequenceInItem);

        itemsObservable.map(this::createProcessedEntity).subscribe(dataItemObserver);
        printItem("COMPLETED");
        Thread.sleep(2_000);
        printItem("DONE");

    }

    private DataItem toDataItem(ConsumerRecord<String, String> stringStringConsumerRecord) {
        return new DataItem(stringStringConsumerRecord.value());
    }

    private Processed createProcessedEntity(DataItem dataItem) {
        Processed processed = new Processed();
        processed.setData(dataItem.getData());
        processed.setSequence(dataItem.getSequence());
        processed.setDate(dataItem.getDate());
        return processed;
    }

    private DataItem setDateInItem(DataItem item, Date date) {
        item.setDate(date);
        return item;
    }

    private DataItem setSequenceInItem(DataItem item, int sequence) {
        item.setSequence(sequence);
        return item;
    }

    private <T> T printItem(T item) {
        System.out.println("============ " + Thread.currentThread() + " : " + item);
        return item;
    }

}

