package com.customer.service.processor;

import org.apache.camel.Exchange;
import org.apache.camel.Processor;
import com.customer.service.types.v1.DeleteCustomerResponse;

public class DeleteCustomerResponseProcessor  implements Processor{

    @Override
    public void process(Exchange exchange) throws Exception {
        int rowUpdated = exchange.getIn().getHeader("CamelSqlUpdateCount", Integer.class);
        DeleteCustomerResponse response = new DeleteCustomerResponse();
        if (rowUpdated > 0) {
            response.setStatus("Successfully updated");
        } else {
            response.setStatus("Customer Not found");
        }
        exchange.getOut().setBody(response);
        
    }

}
