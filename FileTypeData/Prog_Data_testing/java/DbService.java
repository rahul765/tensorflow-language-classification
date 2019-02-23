package test.mongo.service;

import test.mongo.db.model.Customer;
import test.mongo.db.repo.CustomerRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

@Component
public class DbService {
    private final CustomerRepository repository;

    @Autowired
    public DbService(CustomerRepository repository) {
        this.repository = repository;
    }

    public void updateDB() throws Exception {
        repository.deleteAll();

        repository.save(new Customer("1", "11"));
        repository.save(new Customer("2", "22"));

        System.out.println("------------------Customers found with findAll()------------");
        for (Customer cust : repository.findAll()) {
            System.out.println(cust);
        }
        System.out.println("-------------------------------");

        System.out.println("--------------Customer found with findByFirstName('2')--------------");
        System.out.println(repository.findByFirstName("2"));
        System.out.println("-------------------------------");

        System.out.println("-----------------Customers found with findByLastName('11')-----------------");
        System.out.println(repository.findByLastName("11"));
        System.out.println("-------------------------------");

    }
}
