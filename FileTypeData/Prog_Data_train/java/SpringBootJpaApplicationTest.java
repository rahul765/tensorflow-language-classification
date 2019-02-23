package me.roybailey.springboot.jpa;

import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.TestPropertySource;
import org.springframework.test.context.junit4.SpringRunner;
import org.springframework.test.context.web.WebAppConfiguration;


@RunWith(SpringRunner.class)
@SpringBootTest(classes = SpringBootJpaApplication.class)
@TestPropertySource(locations = "classpath:application-test.properties")
@WebAppConfiguration
public class SpringBootJpaApplicationTest {

	@Test
	public void contextLoads() {
	}

}
