package org.home.game.map.entities.character.create;

import org.home.game.common.mvp.View;
import org.home.game.map.entities.character.Race;
import org.home.game.map.entities.character.Sex;

public interface NewCharacterView extends View<NewCharacterView.ActionDelegate> {
    interface ActionDelegate {
        void onChosen(Race race);

        void onChosen(Sex sex);

        void onChosen(String name);

        void onCompleted();
    }
}
