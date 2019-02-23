package com.kncept.mirage;

import java.util.List;

public interface MirageMethod {
	
	public Mirage getDeclaredBy();
	
	public String getName();
	
	public int getModifiers();
	
	public MirageType getReturnType();
	
	public List<MirageType> getParameterTypes();
	
	public List<MirageAnnotation> getAnnotations();

}
