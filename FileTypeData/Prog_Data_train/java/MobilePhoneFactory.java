package pl.designpatterns.creational.factorymethod;

public class MobilePhoneFactory extends PhoneFactory {

    @Override
    public Product createPhone() {
        return new MobilePhone();
    }

}
