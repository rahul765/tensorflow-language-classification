package pl.designpatterns.behavioural.mediator;

public class PersonBidder implements AuctionColleague {

    private String name;
    private final AuctionMediator mediator;

    public PersonBidder(String name, AuctionMediator mediator) {
        this.name = name;
        this.mediator = mediator;
    }

    @Override
    public String getName() {
        return name;
    }

    @Override
    public boolean bid(double price) {
        return mediator.registerBid(this, price);
    }

}
