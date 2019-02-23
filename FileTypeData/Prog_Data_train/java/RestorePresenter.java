package org.home.game.map.factory.resume;

import org.home.game.common.mvp.AbstractPresenter;
import org.home.game.map.entities.Entity;
import org.home.game.map.factory.resume.marshaller.Marshaller;

import java.nio.file.Paths;
import java.util.List;

import static java.nio.file.Files.notExists;

public class RestorePresenter extends AbstractPresenter<RestoreView> implements Restorer {
    private final Marshaller marshaller;

    public RestorePresenter(RestoreView view, Marshaller marshaller) {
        super(view);
        this.marshaller = marshaller;
    }

    @Override
    public List<List<Entity>> restore() {
        String path;
        do {
            show();
            path = view.getPath();
        } while (notExists(Paths.get(path)));
        return marshaller.unmarshall(path);
    }
}
