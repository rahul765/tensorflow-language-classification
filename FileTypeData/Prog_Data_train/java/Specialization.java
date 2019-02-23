package work;


public class Specialization {

    private Integer id;

    private String name;

    public Specialization(Integer id, String name) {
        this.id = id;
        this.name = name;
    }

    public static Specialization nullObject() {
        return new Specialization(0, "brak");
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public boolean isPersisted() {
        return getId() != null;
    }

    @Override
    public String toString() {
        return "Specialization{" +
                "id=" + id +
                ", name='" + name + '\'' +
                '}';
    }

}
