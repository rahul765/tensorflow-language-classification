package getithere.backend;

import com.mongodb.MongoClient;
import com.mongodb.client.MongoDatabase;
import org.springframework.beans.factory.DisposableBean;


public class MongoConnection implements DisposableBean {

    public static final String HOST = "127.0.0.1";
    public static final int PORT = 27017;
    private MongoClient mongoClient;
    private MongoDatabase db;
    
    public MongoConnection(){
        mongoClient = new MongoClient(HOST, PORT);
        db = mongoClient.getDatabase("mydb");
    }
    
    @Override
    public void destroy() throws Exception {
        mongoClient.close();
    }
    
    public MongoDatabase getDb(){
        return db;
    }
}
