package me.roybailey.springboot.jpa.configuration;

import org.springframework.boot.autoconfigure.EnableAutoConfiguration;
import org.springframework.boot.autoconfigure.domain.EntityScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;
import org.springframework.transaction.annotation.EnableTransactionManagement;

@Configuration
@EnableAutoConfiguration
@EnableTransactionManagement
@EntityScan(basePackages = {"me.roybailey.springboot.jpa.domain"})
@EnableJpaRepositories(basePackages = {"me.roybailey.springboot.jpa.repository"})
public class JpaRepositoryConfiguration {
}
