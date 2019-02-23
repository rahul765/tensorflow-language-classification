package pl.designpatterns.behavioural.state;

class StoppedEngineState implements EngineState {

    @Override
    public void start(Engine engine) {
        System.out.println("I am starting engine...");
        engine.setIntoRunningState();
        System.out.println("Engine started.");
    }

    @Override
    public void accelerate(Engine engine) {
        throw new IllegalStateException("Cannot accelerate, turn the engine on!");
    }

    @Override
    public void stop(Engine engine) {
        System.out.println("Stopping already stopped engine ... nothing happens");
    }
}
