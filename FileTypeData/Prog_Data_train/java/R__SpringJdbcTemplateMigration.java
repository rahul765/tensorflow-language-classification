package db.migration;

import org.flywaydb.core.api.logging.Log;
import org.flywaydb.core.api.logging.LogFactory;
import org.flywaydb.core.api.migration.spring.SpringJdbcMigration;
import org.springframework.jdbc.core.JdbcTemplate;

/**
 * Created by dziubani on 4/8/2016.
 */
/**
 * Java repeatable spring jdbc template migration example. Repeated if checksum is changed
 */
public class R__SpringJdbcTemplateMigration implements SpringJdbcMigration{

    private static final Log LOG = LogFactory.getLog(R__SpringJdbcTemplateMigration.class);

    @Override
    public void migrate(JdbcTemplate jdbcTemplate) throws Exception {
        LOG.info(" R__SpringJdbcTemplateMigration migration. Jdbc template fetch size = " + jdbcTemplate.getFetchSize());
    }
}
