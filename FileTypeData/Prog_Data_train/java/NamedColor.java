package bagotricks.tuga.turtle;

public class NamedColor {

    public final String name;
    public final float r;
    public final float g;
    public final float b;

    public NamedColor(String name, float r, float g, float b) {
        this.name = name;
        this.r = r;
        this.g = g;
        this.b = b;
    }

    public float[] rgb() {
        return new float[]{r, g, b};
    }
}
