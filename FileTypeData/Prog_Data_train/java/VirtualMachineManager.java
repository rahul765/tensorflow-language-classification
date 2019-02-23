package com.kodkast.tech.devstack.workinprogress;

public interface VirtualMachineManager {

    public void prepareVirtualMachine(VMConfig vmConfig);

    public void bootstrap();
}
