package test.mongo.service;

import com.mongodb.BasicDBObject;
import com.mongodb.DBObject;
import com.mongodb.gridfs.GridFSDBFile;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.mongodb.core.query.Criteria;
import org.springframework.data.mongodb.core.query.Query;
import org.springframework.data.mongodb.gridfs.GridFsOperations;
import org.springframework.stereotype.Component;

import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.net.URISyntaxException;
import java.net.URL;
import java.util.List;

@Component
public class GridFsClient {

    private final
    GridFsOperations operations;

    @Autowired
    public GridFsClient(GridFsOperations operations) {
        this.operations = operations;
    }

    public void storeFileToGridFs(String filename) {
        DBObject metaData = new BasicDBObject();
        metaData.put("extra1", "anything 1");
        metaData.put("extra2", "anything 2");

        InputStream inputStream = null;
        try {
            URL resource = this.getClass().getClassLoader().getResource(filename);
            assert resource != null;
            File file = new File(resource.toURI());
            inputStream = new FileInputStream(file);
            operations.store(inputStream, filename, "image/png", metaData);

        } catch (FileNotFoundException | URISyntaxException e) {
            e.printStackTrace();
        } finally {
            if (inputStream != null) {
                try {
                    inputStream.close();
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }
    }

    public void findFile() {
        List<GridFSDBFile> result = operations.find(
                new Query().addCriteria(Criteria.where("filename").is("testing.png")));

        for (GridFSDBFile file : result) {
            try {
                System.out.println(file.getFilename());
                System.out.println(file.getContentType());

                //save as another image
                file.writeTo(new File("build/tmp/" + file.getFilename()));
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
}
