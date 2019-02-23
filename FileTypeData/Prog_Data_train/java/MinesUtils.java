package org.mines.dao.util;

import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.log4j.Logger;
import org.mines.dao.model.GamePlayerRecord;
import org.mines.dao.model.Stats;

public class MinesUtils {
	
	static Logger logger = Logger.getLogger(MinesUtils.class);

	public static Map<String,Stats> combine(List<GamePlayerRecord> list){
		Map<String, Stats> n = new HashMap<String, Stats>();
		for(GamePlayerRecord s : list){
			if(n.containsKey(s.getName())){
				n.put(s.getName(), combineStats(s.getStats(),n.get(s.getName())));
			}else {
				n.put(s.getName(), s.getStats());
			}
		}
		return n;
	}
	
	public static Stats combineStats(Stats m1, Stats m2){
		Map<String, Integer> m3 = new HashMap<String, Integer>();
		Stats s = new Stats();
		Iterator<String> it = m1.getStats().keySet().iterator();
		while(it.hasNext()){
			String k = it.next();
			m3.put(k, m1.getStats().get(k) + m2.getStats().get(k));
		}
		s.setStats(m3);
		return s;
	}
	
}
