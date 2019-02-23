package test.main;

import ui.loading_new.LoadingFrame;

import javax.swing.*;

/**
 * Author: Daniel
 */
public class LoadingNewTest {
    public static void main(String[] args) {
        LoadingFrame loadingFrame = new LoadingFrame();
        loadingFrame.setDefaultCloseOperation(WindowConstants.EXIT_ON_CLOSE);
        loadingFrame.setVisible(true);
    }
}
