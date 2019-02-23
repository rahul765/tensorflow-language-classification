package hu.bets.timer.web;

import org.springframework.stereotype.Component;

import javax.ws.rs.GET;
import javax.ws.rs.Path;

@Component
@Path("timer/football/v1")
public class TimerResource {

    @GET
    @Path("info")
    public String getInfo() {
        return "<html><body><h1>Timer Service up and running!</h1></body></html>";
    }
}
