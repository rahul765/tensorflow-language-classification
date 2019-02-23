package database.dao;

import java.util.ResourceBundle;

/**
 * Author: dmichalski
 */
public class TextsDao {
    public static String getText(String name) {
        ResourceBundle rb = ResourceBundle.getBundle("properties/texts");
        return rb.getString(name);
    }

    public static String getFileName(String name) {
        ResourceBundle rb = ResourceBundle.getBundle("properties/file_paths");
        return rb.getString(name);
    }
}
