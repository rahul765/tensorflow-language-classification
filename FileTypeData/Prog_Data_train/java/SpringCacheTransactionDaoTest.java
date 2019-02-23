package org.caching;

import org.caching.context.SpringCachingContext;
import org.springframework.test.context.ContextConfiguration;

/**
 * Created by iurii.dziuban on 06.09.2016.
 */
@ContextConfiguration(classes = SpringCachingContext.class)
public class SpringCacheTransactionDaoTest extends AbstractTransactionDaoTest{

}
