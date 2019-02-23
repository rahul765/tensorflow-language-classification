package guice.xml.config.utils;

public interface LoggerIF {
	public abstract void trace(Object... obj);
	public abstract void trace(Throwable t, Object... obj);
	public abstract void debug(Object... obj);
	public abstract void debug(Throwable t, Object... obj);
	public abstract void fatal(Object... obj);
	public abstract void fatal(Throwable t, Object... obj);
	public abstract void error(Object... obj);
	public abstract void error(Throwable t, Object... obj);
	public abstract void info(Object... obj);
	public abstract void info(Throwable t, Object... obj);
	public abstract void warn(Object... obj);
	public abstract void warn(Throwable t, Object... obj);
}
