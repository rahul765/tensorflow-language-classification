package com.epam.chat.camel;

import com.epam.chat.IProducerPool;

import org.apache.camel.ProducerTemplate;

import java.util.HashMap;
import java.util.Map;

/**
 * @author Maksym_Labazov
 * 
 */
public class ProducerPool implements IProducerPool {

    private Map<String, ProducerTemplate> pool;

    /**
     * 
     */
    public ProducerPool() {
        pool = new HashMap<>();
    }

    @Override
    public void addProducer(String room, ProducerTemplate producer) {
        synchronized (pool) {
            pool.put(room, producer);
        }
    }

    @Override
    public ProducerTemplate getProducer(String room) {
        synchronized (pool) {
            ProducerTemplate result = pool.get(room);
            return result;
        }
    }

    @Override
    public void removeProducer(String room) {
        synchronized (pool) {
            pool.remove(room);
        }
    }

}
