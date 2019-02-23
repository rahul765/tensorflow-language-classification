package repository;

import model.Spittle;

import java.util.List;

/**
 * Created by belenov on 3/6/17.
 */
public interface SpittleRepository {
    Spittle find(long id);
    List<Spittle> find(long max, int count);
}
