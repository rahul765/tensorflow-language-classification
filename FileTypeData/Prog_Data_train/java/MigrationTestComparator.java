package org.flywaydb.test.runner;

import org.flywaydb.test.AfterMigration;
import org.flywaydb.test.BeforeMigration;
import org.junit.runner.Description;

import java.util.Comparator;

public class MigrationTestComparator implements Comparator<Description> {

    @Override
    public int compare(Description o1, Description o2) {
        if (o1.getAnnotation(BeforeMigration.class) != null) {
            if (o2.getAnnotation(BeforeMigration.class) != null) {
                return 0;
            } else {
                return -1;
            }
        } else if (o1.getAnnotation(AfterMigration.class) != null) {
            if (o2.getAnnotation(AfterMigration.class) != null) {
                return 0;
            } else {
                return 1;
            }
        }
        return 0;
    }
}
