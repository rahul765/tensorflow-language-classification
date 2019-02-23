package ui.exam.view.balls;

import java.awt.*;


/**
 * Author: Daniel
 */
public class StandardBallsPanel extends BallsPanel {
    private static final int HOW_MANY_BALLS = 20;

    public StandardBallsPanel() {
        super(HOW_MANY_BALLS);
        setLayout(new GridLayout(4, 5, 0, 5));
    }
}
