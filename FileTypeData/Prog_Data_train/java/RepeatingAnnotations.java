package customannotation;

import java.lang.annotation.*;
import java.util.ArrayList;
import java.util.Arrays;

/**
 * @author ajay
 */
public class RepeatingAnnotations {

    public static void main(String args[]) {
        Annotation[] annotation = Filterable.class.getAnnotationsByType(Filter.class);
        new ArrayList<>(Arrays.asList(annotation)).forEach(annotation1 -> {
            Filter filter = (Filter) annotation1;
            System.out.println(filter.value());
        });
    }
}
