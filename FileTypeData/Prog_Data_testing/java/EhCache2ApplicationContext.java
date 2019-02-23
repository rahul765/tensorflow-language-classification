package org.caching.context;

import net.sf.ehcache.CacheManager;
import org.springframework.cache.annotation.EnableCaching;
import org.springframework.cache.ehcache.EhCacheCacheManager;
import org.springframework.cache.ehcache.EhCacheManagerFactoryBean;
import org.springframework.context.annotation.AdviceMode;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.core.io.ClassPathResource;

/**
 * Created by iurii.dziuban on 06.09.2016.
 */
@Configuration
@ComponentScan(basePackages = {"org.caching.data.value", "org.caching.data.ehcache2_dao"})
@EnableCaching(mode = AdviceMode.PROXY)
public class EhCache2ApplicationContext {

    @Bean
    public CacheManager ehcacheManager() {
        return CacheManager.create(getClass().getClassLoader().getResourceAsStream("ehcache2.xml"));
    }

    @Bean
    public EhCacheCacheManager ehCacheCacheManager(CacheManager ehcacheManager) {
        return new EhCacheCacheManager(ehcacheManager);
    }

    @Bean
    public EhCacheManagerFactoryBean ehcache() {
        EhCacheManagerFactoryBean ehCacheFactoryBean = new EhCacheManagerFactoryBean();
        ehCacheFactoryBean.setConfigLocation(new ClassPathResource("ehcache2.xml"));
        ehCacheFactoryBean.setShared(true);
        return ehCacheFactoryBean;
    }

}
