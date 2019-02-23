package pl.designpatterns.structual.bridge;

public class VeryComplicatedActionProcessor implements ActionProcessor {

    @Override
    public void processAddAction(Object processedObject) {
        System.out.printf("I am COMPLICATED ADD action on object: %s ", processedObject.toString());
        System.out.println("Doing complicated stuff...");
        System.out.println();
    }

    @Override
    public void processEditAction(Object processedObject) {
        System.out.printf("I am COMPLICATED EDIT action on object: %s ", processedObject.toString());
        System.out.println("Doing complicated stuff...");
        System.out.println();
    }

    @Override
    public void processRemoveAction(Object processedObject) {
        System.out.printf("I am COMPLICATED REMOVE action on object: %s ", processedObject.toString());
        System.out.println("Doing complicated stuff...");
        System.out.println();
    }

}
