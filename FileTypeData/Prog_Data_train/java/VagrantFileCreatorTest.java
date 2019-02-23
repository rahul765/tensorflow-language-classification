package com.kodkast.tech.devstack.workinprogress;

import org.junit.BeforeClass;
import org.junit.Test;

public class VagrantFileCreatorTest {

    private static VagrantFileCreator vagrantFileCreator;

    @BeforeClass
    public static void setup() {
        vagrantFileCreator = new VagrantFileCreator();
    }

    @Test
    public void testGetVagrantFileContents() {

        VMConfig config = new VMConfig.Builder("dev", "dev.kodkast.com")
            .build();

        String fileContents = vagrantFileCreator.getVagrantFileContents(config);

        System.out.println(fileContents);
    }
}
